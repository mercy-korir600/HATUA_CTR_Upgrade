<?php
App::uses('AppModel', 'Model');
/**
 * Capa Model
 *
 * CAPA (Corrective and Preventive Action) record. Raised automatically by
 * ReviewDeadlineAlertShell when a reviewer misses the Review-stage
 * submission deadline. Field set mirrors the CAPA table format from the
 * CAPA.doc business requirement - Description of non conformity | Root
 * cause | Corrective/preventive action | Status | Target date |
 * Responsible person - plus the bookkeeping this app needs (reference no.,
 * which application/review/reviewer, follow-up threading):
 *
 *   - `description`        - Description of non conformity. Auto-filled
 *                             on the Initial row; a FollowUp's own
 *                             `description` instead holds that follow-up's
 *                             progress note (see manager_add_capa_followup()).
 *   - `root_cause`          - Root cause. NULL on the auto-opened Initial
 *                             row (nobody has investigated yet) - filled
 *                             in via a follow-up.
 *   - `corrective_action`   - Corrective/preventive action. Same as above.
 *   - `status`              - Open / In Progress / Closed.
 *   - `target_date`         - Target date for completing the corrective/
 *                             preventive action. Distinct from
 *                             `deadline_date`, which is the original
 *                             Review-stage SLA deadline that was missed.
 *
 * "Responsible person" (the doc's last column) has no column of its own -
 * it's just `reviewer_user_id`/the Reviewer association below, relabeled
 * "Responsible Person" wherever the CAPA views display it (see
 * app/View/Capas/manager_view.ctp, app/View/Capas/manager_index.ctp,
 * csv_export.ctp). The reviewer whose missed deadline opened the case is
 * exactly who needs to act to close it, so a second, separately-tracked
 * "who's responsible" value would just be duplicate data that could drift
 * out of sync with it.
 *
 * root_cause/corrective_action/target_date are plain columns (not User
 * associations) - simple values matching what the source document
 * specifies, not something that needs to resolve against another table.
 *
 * One CAPA "case" per reviewer assignment is modelled as a small group of
 * rows sharing the same (review_id, source_stage) - not a separate table:
 *
 *   - `type` = 'Initial'  - the auto-opened record (one per assignment;
 *                            see ReviewDeadlineAlertShell::_ensureCapa()).
 *                            `capa_id` is NULL - it has no parent.
 *   - `type` = 'FollowUp' - a later update appended by a manager (progress
 *                            note and/or a `status` change, and/or an
 *                            update to root cause / corrective action /
 *                            target date). `capa_id` points at whichever
 *                            row it's replying to -
 *                            the Initial row, OR another FollowUp row, so
 *                            a follow-up can itself gain follow-ups (see
 *                            buildThread() below). See
 *                            ApplicationsController::manager_add_capa_followup().
 *
 * The case's *current* detail (status, root cause, corrective action,
 * target date) is that of the most recently-saved row anywhere in the
 * thread - but for cheap filtering/listing (see
 * CapasController), the 'Initial' row's own copy of each of those fields
 * is ALSO kept in sync with every follow-up (see ApplicationsController::
 * manager_add_capa_followup()), so callers that only need "the case's
 * current detail" can read it directly off the Initial row without
 * walking the tree.
 *
 * `closed_date` is stamped automatically (never hand-entered) the moment a
 * row's own `status` is saved as 'Closed', and cleared back to NULL if
 * that row/case is later reopened - see manager_add_capa_followup() again,
 * which keeps the Initial row's `closed_date` in sync alongside `status`
 * for the same reason.
 *
 * @property Application $Application
 * @property ApplicationStage $ApplicationStage
 * @property Review $Review
 * @property User $Reviewer
 * @property User $CreatedBy
 * @property Capa $Parent
 */
class Capa extends AppModel {

    public $actsAs = array('Containable', 'Search.Searchable');

    // Powers the filter form on the dedicated CAPA section
    // (app/Controller/CapasController.php / app/View/Capas/manager_index.ctp)
    // - same Search.Searchable + Search.Prg pattern used by Sae/Budget/
    // AuditTrail elsewhere in this app.
    public $filterArgs = array(
        'reference_no' => array('type' => 'like', 'encode' => true),
        'protocol_no' => array('type' => 'query', 'method' => 'findByProtocolNo', 'encode' => true),
        'reviewer_user_id' => array('type' => 'value'),
        'status' => array('type' => 'value'),
        'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Capa.created BETWEEN ? AND ?'),
        'start_date' => array('type' => 'query', 'method' => 'dummy'),
        'end_date' => array('type' => 'query', 'method' => 'dummy'),
    );

    public function dummy($data = array())
    {
        return array('1' => '1');
    }

    public function makeRangeCondition($data = array())
    {
        if (!empty($data['start_date'])) {
            $start_date = date('Y-m-d', strtotime($data['start_date']));
        } else {
            $start_date = date('Y-m-d', strtotime('2012-05-01'));
        }
        if (!empty($data['end_date'])) {
            $end_date = date('Y-m-d', strtotime($data['end_date']));
        } else {
            $end_date = date('Y-m-d');
        }
        return array($start_date, $end_date);
    }

    public function findByProtocolNo($data = array())
    {
        return array($this->alias . '.application_id' => $this->Application->find('list', array(
            'conditions' => array('Application.protocol_no LIKE' => '%' . $data['protocol_no'] . '%'),
            'fields' => array('id', 'id'),
        )));
    }

    public $belongsTo = array(
        'Application' => array(
            'className' => 'Application',
            'foreignKey' => 'application_id',
        ),
        'ApplicationStage' => array(
            'className' => 'ApplicationStage',
            'foreignKey' => 'application_stage_id',
        ),
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'review_id',
        ),
        // Aliased so it's unambiguous in views/reports that this is "the
        // reviewer who missed the deadline", not just any User association.
        'Reviewer' => array(
            'className' => 'User',
            'foreignKey' => 'reviewer_user_id',
        ),
        // Who logged this particular row. NULL on system-generated
        // 'Initial' rows (opened by the cron shell, not a person); set to
        // the acting manager on 'FollowUp' rows.
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by_user_id',
        ),
        // Self-reference: the row this one is a follow-up of. NULL for
        // 'Initial' rows.
        'Parent' => array(
            'className' => 'Capa',
            'foreignKey' => 'capa_id',
        ),
    );

    public $validate = array(
        'application_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'A CAPA must reference an application.',
            ),
        ),
        'reviewer_user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'A CAPA must reference the reviewer it concerns.',
            ),
        ),
        'source_stage' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'A CAPA must record which stage triggered it.',
            ),
        ),
        'status' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'type' => array(
            'inList' => array(
                'rule' => array('inList', array('Initial', 'FollowUp')),
                'message' => 'Type must be Initial or FollowUp.',
            ),
        ),
    );

    /**
     * Arranges a flat list of Capa rows (normally one "case" - every row
     * sharing a review_id/source_stage) into a parent/child tree via
     * `capa_id`, so a follow-up can itself have follow-ups. Each returned
     * node is the row's array with an added '_children' key holding its
     * immediate children in the same shape, recursively.
     *
     * Rows with no parent (capa_id empty, or pointing outside this list)
     * are returned as the top-level roots - normally exactly one, the
     * 'Initial' row.
     *
     * @param array $rows Flat Capa rows, e.g. from find('all').
     * @return array Root node(s), each with nested '_children'.
     */
    public function buildThread($rows)
    {
        $byId = array();
        foreach ($rows as $row) {
            $row['_children'] = array();
            $byId[$row['Capa']['id']] = $row;
        }

        $rootIds = array();
        foreach ($byId as $id => $row) {
            $parentId = !empty($row['Capa']['capa_id']) ? $row['Capa']['capa_id'] : null;
            if ($parentId && isset($byId[$parentId])) {
                $byId[$parentId]['_children'][] = $id;
            } else {
                $rootIds[] = $id;
            }
        }

        $assemble = function ($id) use (&$assemble, &$byId) {
            $node = $byId[$id];
            $childIds = $node['_children'];
            $node['_children'] = array();
            foreach ($childIds as $childId) {
                $node['_children'][] = $assemble($childId);
            }
            return $node;
        };

        $roots = array();
        foreach ($rootIds as $id) {
            $roots[] = $assemble($id);
        }
        return $roots;
    }
}

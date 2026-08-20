<?php
App::uses('AppModel', 'Model');
/**
 * Capa Model
 *
 * Minimal CAPA (Corrective and Preventive Action) record. For now this is
 * raised automatically by ReviewDeadlineAlertShell when a reviewer misses
 * the Review-stage submission deadline - kept intentionally small (per
 * request) so it just references the application and the reviewer plus
 * enough basic detail to act on. Extend with root-cause, corrective action,
 * verification, closure-date fields etc. as the CAPA process is fleshed out.
 *
 * One CAPA "case" per reviewer assignment is modelled as a small group of
 * rows sharing the same (review_id, source_stage) - not a separate table:
 *
 *   - `type` = 'Initial'  - the auto-opened record (one per assignment;
 *                            see ReviewDeadlineAlertShell::_ensureCapa()).
 *   - `type` = 'FollowUp' - a later update appended by a manager (progress
 *                            note and/or a `status` change), any number of
 *                            these per case. See ApplicationsController::
 *                            manager_add_capa_followup().
 *
 * The case's *current* status is simply the `status` of the most recent
 * row (Initial or FollowUp) for that (review_id, source_stage) pair -
 * there is no separate "head" record to keep in sync.
 *
 * @property Application $Application
 * @property ApplicationStage $ApplicationStage
 * @property Review $Review
 * @property User $Reviewer
 * @property User $CreatedBy
 */
class Capa extends AppModel {

    public $actsAs = array('Containable');

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
}

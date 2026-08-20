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
 * @property Application $Application
 * @property ApplicationStage $ApplicationStage
 * @property Review $Review
 * @property User $Reviewer
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
    );
}

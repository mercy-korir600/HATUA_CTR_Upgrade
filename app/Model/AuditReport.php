<?php
App::uses('AppModel', 'Model');

/**
 * AuditReport Model
 *
 * @property Application $Application
 * @property User $User
 * @property AuditChecklist $AuditChecklist
 */
class AuditReport extends AppModel {

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'Application' => array(
            'className' => 'Application',
            'foreignKey' => 'application_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public $hasMany = array(
        'AuditChecklist' => array(
            'className' => 'AuditChecklist',
            'foreignKey' => 'audit_report_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    public $validate = array(
        'outcome' => array(
            'rule' => array('inList', array(
                'Compliant',
                'Compliant with Conditions (CAPA Required)',
                'Non-Compliant (Suspension/Revocation Recommended)'
            )),
            'message' => 'Please select a valid audit outcome.',
            'allowEmpty' => true
        )
    );
}

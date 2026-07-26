<?php
App::uses('AppModel', 'Model');

/**
 * AuditChecklist Model
 *
 * @property AuditReport $AuditReport
 */
class AuditChecklist extends AppModel {

    public $belongsTo = array(
        'AuditReport' => array(
            'className' => 'AuditReport',
            'foreignKey' => 'audit_report_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

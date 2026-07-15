<?php
App::uses('AppModel', 'Model');

class StudyAuditor extends AppModel {
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Application' => array(
            'className' => 'Application',
            'foreignKey' => 'application_id'
        )
    );
}
<?php
App::uses('AppModel', 'Model');

class Amend extends AppModel {
	public $actsAs = array('Containable');

	public $belongsTo = array(
		'Amendment' => array(
			'className' => 'Amendment',
			'foreignKey' => 'amendment_id',
		),
		'Application' => array(
			'className' => 'Application',
			'foreignKey' => 'application_id',
		),
	);

	public $validate = array(
		'cover_letter' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Cover letter is required.',
			),
		),
		'summary' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Summary is required.',
			),
		),
		'reason' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Reason is required.',
			),
		),
		'objectives_impacts' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Impact on objectives is required.',
			),
		),
		'endpoints_impacts' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Impact on endpoints is required.',
			),
		),
		'safety_impacts' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Impact on safety and wellbeing is required.',
			),
		),
	);
}

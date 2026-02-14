<?php
App::uses('AppModel', 'Model');
/**
 * Amendment Model
 *
 * @property Application $Application
 * @property TrialStatus $TrialStatus
 */
class Amendment extends AppModel {
	public $actsAs = array('Containable');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'submitted' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Application' => array(
			'className' => 'Application',
			'foreignKey' => 'application_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'TrialStatus' => array(
			'className' => 'TrialStatus',
			'foreignKey' => 'trial_status_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
             'Attachment' => array(
                        'className' => 'Attachment',
                        'foreignKey' => 'foreign_key',
                        'dependent' => true,
                        'conditions' => array('Attachment.model' => 'Amendment', 'Attachment.group' => 'attachment'),
             ),
		'CoverLetter' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'dependent' => true,
			'conditions' => array('CoverLetter.model' => 'Amendment', 'CoverLetter.group' => 'cover_letter'),
		),
       );

	public $hasOne = array(
		'Amend' => array(
			'className' => 'Amend',
			'foreignKey' => 'amendment_id',
			'dependent' => true,
		),
	);

	public function beforeSave() {
		if (!empty($this->data['Amendment']['date_of_protocol'])) {
			$this->data['Amendment']['date_of_protocol'] = $this->dateFormatBeforeSave($this->data['Amendment']['date_of_protocol']);
		}
		if (!empty($this->data['Amendment']['approval_date'])) {
			$this->data['Amendment']['approval_date'] = $this->dateFormatBeforeSave($this->data['Amendment']['approval_date']);
		}
		if (!empty($this->data['Amendment']['declaration_date1'])) {
			$this->data['Amendment']['declaration_date1'] = $this->dateFormatBeforeSave($this->data['Amendment']['declaration_date1']);
		}
		if (!empty($this->data['Amendment']['declaration_date2'])) {
			$this->data['Amendment']['declaration_date2'] = $this->dateFormatBeforeSave($this->data['Amendment']['declaration_date2']);
		}

		if(empty($this->data['Amendment']['ecct_ref_number'])){
			$this->data['Amendment']['ecct_ref_number'] = '';
		}
		return true;
	}


	function afterFind($results) {
		foreach ($results as $key => $val) {
			if (isset($val['Amendment']['date_of_protocol'])) {
				$results[$key]['Amendment']['date_of_protocol'] = $this->dateFormatAfterFind($val['Amendment']['date_of_protocol']);
			}
			if (isset($val['Amendment']['approval_date'])) {
				$results[$key]['Amendment']['approval_date'] = $this->dateFormatAfterFind($val['Amendment']['approval_date']);
			}
			if (isset($val['Amendment']['declaration_date1'])) {
				$results[$key]['Amendment']['declaration_date1'] = $this->dateFormatAfterFind($val['Amendment']['declaration_date1']);
			}
			if (isset($val['Amendment']['declaration_date2'])) {
				$results[$key]['Amendment']['declaration_date2'] = $this->dateFormatAfterFind($val['Amendment']['declaration_date2']);
			}
		}
		return $results;
	}

	// UTILITY METHODS


}

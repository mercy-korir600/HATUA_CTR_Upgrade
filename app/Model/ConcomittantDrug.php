<?php
App::uses('AppModel', 'Model');
/**
 * ConcomittantDrug Model
 *
 * @property Sae $Sae
 * @property Route $Route
 */
class ConcomittantDrug extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'generic_name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the generic name of the concomittant drug!'
			),
		),
		'dose' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the dose of the concomittant drug!'
			),
		),
		'route_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select the administration route of the concomittant drug!'
			),
		),
		'indication' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the indication for use for the concomittant drug!'
			),
		),
		'date_from' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the therapy date for the concomittant drug!'
			),
			'beforeStopDate' => array(
				'rule' => 'beforeStopDate',
				'message' => 'The therapy start date must be less than or equal to the therapy stop date'
			)
		),
		'date_to' => array(
			'beforeStopDate' => array(
				'rule' => 'beforeStopDate',
				'allowEmpty' => true,
				'message' => 'The therapy stop date must be greater than or equal to the therapy start date'
			)
		),
		'deleted' => array(
			'boolean' => array(
				'rule' => array('boolean'),
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
		'Sae' => array(
			'className' => 'Sae',
			'foreignKey' => 'sae_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Route' => array(
			'className' => 'Route',
			'foreignKey' => 'route_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public function beforeSave() {
        if (!empty($this->data['ConcomittantDrug']['date_from'])) {
            $this->data['ConcomittantDrug']['date_from'] = $this->dateFormatBeforeSave($this->data['ConcomittantDrug']['date_from']);
        }
        if (!empty($this->data['ConcomittantDrug']['date_to'])) {
            $this->data['ConcomittantDrug']['date_to'] = $this->dateFormatBeforeSave($this->data['ConcomittantDrug']['date_to']);
        }
        return true;
    }

	public function beforeStopDate($field = null){
		$value = reset($field);
		$startDate = !empty($this->data['ConcomittantDrug']['date_from']) ? strtotime($this->data['ConcomittantDrug']['date_from']) : false;
		$endDate = !empty($this->data['ConcomittantDrug']['date_to']) ? strtotime($this->data['ConcomittantDrug']['date_to']) : false;

		if ($value === null || $value === '' || $startDate === false || $endDate === false) {
			return true;
		}

		return ($startDate <= $endDate);
    }
}

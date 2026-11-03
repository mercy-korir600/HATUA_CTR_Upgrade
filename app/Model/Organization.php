<?php
App::uses('AppModel', 'Model');
/**
 * Organization Model
 *
 * @property Application $Application
 */
class Organization extends AppModel {


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
		)
	);
	
	public $validate = array(
		'organization' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please enter the organization name'
            ),
        ),
		'contact_person' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please enter the organization contact person'
            ),
        ),
		'address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'allowEmpty' => true,
                'message'  => 'Organizations : Please enter the organization address'
            ),
        ),
		'physical_address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'allowEmpty' => true,
                'message'  => 'Organizations : Please enter the organization physical address'
            ),
        ),
		'postal_address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'allowEmpty' => true,
                'message'  => 'Organizations : Please enter the organization postal address'
            ),
        ),
		'email_address' => array(
            'email' => array(
                'rule'     => 'email',
                'required' => false,
                'allowEmpty' => true,
                'message'  => 'Organizations : Please enter a valid organization email address'
            ),
        ),
		'telephone_number' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please enter the organization\'s telephone number'
            ),
        ),
		'all_tasks' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred all tasks to the organization'
            ),
        ),
		'monitoring' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred monitoring to the organization'
            ),
        ),
		'regulatory' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred regulatory tasks to the organization'
            ),
        ),
		'investigator_recruitment' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred investigator recruitment to the organization'
            ),
        ),
		'ivrs_treatment_randomisation' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred IVRS - treatment randomisation to the organization'
            ),
        ),
		'data_management' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred data management to the organization'
            ),
        ),
		'e_data_capture' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred E-Data capture to the organization'
            ),
        ),
		'susar_reporting' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred SUSAR reporting to the organization'
            ),
        ),
		'quality_assurance_auditing' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred quality assurance auditing to the organization'
            ),
        ),
		'statistical_analysis' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred statistical analysis to the organization'
            ),
        ),
		'medical_writing' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred medical writing to the organization'
            ),
        ),
		'other_duties' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred other duties to the organization'
            ),
        ),
		'other_duties_specify' => array(
            'ifOtherDuties' => array(
                'rule'     => array('ifOtherDuties'),
                'required' => true,
                'message'  => 'Organizations : Please specify the other duties transferred to the organization'
            ),
        ),
	);
	
	public function ifOtherDuties($field = null) {
		if($this->data['Organization']['other_duties'] == 'Yes') {
			return !empty($field['other_duties_specify']);
		}
		return true;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['Organization']['physical_address']) && !empty($this->data['Organization']['physical_address'])) {
			if (empty($this->data['Organization']['address'])) {
				$this->data['Organization']['address'] = $this->data['Organization']['physical_address'];
			}
		} elseif (isset($this->data['Organization']['address']) && !empty($this->data['Organization']['address'])) {
			if (empty($this->data['Organization']['physical_address'])) {
				$this->data['Organization']['physical_address'] = $this->data['Organization']['address'];
			}
		}
		return parent::beforeSave($options);
	}
	
		
	// UTILITY METHODS
	public function isOwnedBy($organization, $user) {
		$application = $this->field('application_id', array('id' => $organization));
		return $this->Application->field('id', array('id' => $application, 'user_id' => $user)) === $application;
	}
}

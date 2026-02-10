<?php
App::uses('AppModel', 'Model');
/**
 * InvestigatorContact Model
 *
 * @property Application $Application
 */
class InvestigatorContact extends AppModel {

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
		'investigator_role' => array(
            'validRole' => array(
                'rule' => array('inList', array('principal', 'co_pi', 'co_i')),
                'allowEmpty' => true,
                'required' => false,
                'message' => 'Investigator : Invalid investigator role selected'
            ),
        ),
		'given_name' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the given name of the principal investigator'
            ),
        ),
		'family_name' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the family name of the principal investigator'
            ),
        ),
		'qualification' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the qualification of the principal investigator'
            ),
        ),
		'professional_address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the qualification of the principal investigator'
            ),
        ),
        'telephone' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please enter the investigator\'s phone number'
            ),
        ),
        'email' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please enter a valid investigator\'s email address'
            ),
        ),
	);

	public function beforeValidate($options = array()) {
		$role = null;
		if (isset($this->data[$this->alias]['investigator_role'])) {
			$role = $this->data[$this->alias]['investigator_role'];
		}

		if (empty($role)) {
			$hasInvestigatorData = false;
			foreach (array('given_name', 'middle_name', 'family_name', 'qualification', 'professional_address', 'telephone', 'email') as $field) {
				if (!empty($this->data[$this->alias][$field])) {
					$hasInvestigatorData = true;
					break;
				}
			}
			if ($hasInvestigatorData) {
				$this->data[$this->alias]['investigator_role'] = 'principal';
			}
		}

		return true;
	}

	// UTILITY METHODS
	public function isOwnedBy($investigator, $user) {
		$application = $this->field('application_id', array('id' => $investigator));
		return $this->Application->field('id', array('id' => $application, 'user_id' => $user)) === $application;
	}
}

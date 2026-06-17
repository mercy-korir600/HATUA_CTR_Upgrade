<?php
App::uses('AppModel', 'Model');
/**
 * Sae Model
 *
 * @property Application $Application
 * @property User $User
 */
class Sae extends AppModel {

    public $actsAs = array('Containable', 'Search.Searchable');
    public $allowedFormTypes = array('SAE', 'SUSAR');
    public $allowedGenders = array('Male', 'Female');
    public $allowedCausality = array(
        'Certain',
        'Probable/Likely',
        'Possible',
        'Unlikely',
        'Conditional/Unclassified',
        'Not related',
        'Unassessable/Unclassifiable',
    );
    public $filterArgs = array(
            'reference_no' => array('type' => 'like', 'encode' => true),
            'protocol_no' => array('type' => 'query', 'method' => 'findByProtocolNo', 'encode' => true),
            'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Sae.created BETWEEN ? AND ?'),
            'start_date' => array('type' => 'query', 'method' => 'dummy'),
            'end_date' => array('type' => 'query', 'method' => 'dummy'),
            'drug_name' => array('type' => 'query', 'method' => 'findByDrugName', 'encode' => true),
            'indication' => array('type' => 'query', 'method' => 'findByIndication', 'encode' => true),
            'gender' => array('type' => 'value'),
            'country_id' => array('type' => 'value'),
            'report_type' => array('type' => 'value'),
            'patient_died' => array('type' => 'value'),
            'prolonged_hospitalization' => array('type' => 'value'),
            'incapacity' => array('type' => 'value'),
            'life_threatening' => array('type' => 'value'),
            'reaction_other' => array('type' => 'value'),
            'source_study' => array('type' => 'value'),
            'source_literature' => array('type' => 'value'),
            'source_health_professional' => array('type' => 'value'),
            'patient_initials' => array('type' => 'like', 'encode' => true),
            'reporter' => array('type' => 'query', 'method' => 'reporterFilter', 'encode' => true),
            'age_start' => array('type' => 'query', 'method' => 'ageFilter', 'encode' => true),
            'age_end' => array('type' => 'query', 'method' => 'ageFilter', 'encode' => true),
            'causality' => array('type' => 'value', 'encode' => true),
        );
    
    public function dummy($data = array()) {
        return array( '1' => '1');
    }

    public function makeRangeCondition($data = array()) {
            if(!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
            else $start_date = date('Y-m-d', strtotime('2012-05-01'));

            if(!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
            else $end_date = date('Y-m-d');

            return array($start_date, $end_date);
    }

    public function findByProtocolNo($data = array()) {
            $cond = array($this->alias.'.application_id' => $this->Application->find('list', array(
                'conditions' => array(
                        'Application.protocol_no LIKE' => '%' . $data['protocol_no'] . '%',),
                'fields' => array('id', 'id')
                    )));
            return $cond;
    }

    public function findByDrugName($data = array()) {
            $cond = array($this->alias.'.id' => $this->SuspectedDrug->find('list', array(
                'conditions' => array(
                        'SuspectedDrug.generic_name LIKE' => '%' . $data['drug_name'] . '%'),
                'fields' => array('sae_id', 'sae_id')
                    )));
            return $cond;
    }


    public function findByIndication($data = array()) {
            $cond = array($this->alias.'.id' => $this->SuspectedDrug->find('list', array(
                'conditions' => array(
                        'SuspectedDrug.indication LIKE' => '%' . $data['indication'] . '%'),
                'fields' => array('sae_id', 'sae_id')
                    )));
            return $cond;
    }

    public function reporterFilter($data = array()) {
            $filter = $data['reporter'];
            $cond = array(
                'OR' => array(
                    $this->alias . '.reporter_name LIKE' => '%' . $filter . '%',
                    $this->alias . '.reporter_email LIKE' => '%' . $filter . '%',
                    $this->alias . '.reporter_phone LIKE' => '%' . $filter . '%',
                ));
            return $cond;
    }

    public function ageFilter($data = array()) {
        $start = date('Y-m-d'); $end = date('Y-m-d', strtotime("-140 years"));
        $start1 = 0; $end1 = 140;
        if(!empty($data['age_start'])) {
            $start = date('Y-m-d', strtotime("-".$data['age_start']." years"));
            $start1 = $data['age_start'];
        }
        if(!empty($data['age_end'])) {
            $end = date('Y-m-d', strtotime("-".$data['age_end']." years"));
            $end1 = $data['age_end'];
        }

        $cond = array(
            'OR' => array(
                $this->alias . '.date_of_birth between ? AND ?' => array($end, $start),
                $this->alias . '.age_years between ? AND ?' => array($start1, $end1),
            ));
        return $cond;
    }

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
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

/**
 * hasMany associations
 *
 * @var array
 */

    public $hasMany = array(
        'SuspectedDrug' => array(
            'className' => 'SuspectedDrug',
            'foreignKey' => 'sae_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),    
        'ConcomittantDrug' => array(
            'className' => 'ConcomittantDrug',
            'foreignKey' => 'sae_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ), 
        'SaeFollowup' => array(
            'className' => 'Sae',
            'foreignKey' => 'sae_id',
            'dependent' => true,
            'conditions' => array('SaeFollowup.report_type' => 'Followup'),
        ),
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Attachment.model' => 'Sae', 'Attachment.group' => 'sae'),
        ),
        'Comment' => array(
            'className' => 'Comment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Comment.model' => 'Sae', 'Comment.category' => 'external' ),
        ),
    );

    public $validate = array(
        'application_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select an approved protocol!'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please select an approved protocol!'
            ),
        ),
        'form_type' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select a valid SAE type!'
            ),
            'inList' => array(
                'rule' => array('inList', array('SAE', 'SUSAR')),
                'message' => 'Please select a valid SAE type!'
            ),
        ),
        'patient_initials' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please enter the patient initials!'
            ),
        ),
        'country_id' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select a country!'
            ),
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Please select a country!'
            ),
        ),
        'date_of_birth' => array(
            'dateOrYears' => array(
                'rule' => 'dateOrYears',
                'message' => 'Please select a valid date of birth or enter the age in years!'
            ),
            'validDateValue' => array(
                'rule' => 'validDateValue',
                'allowEmpty' => true,
                'message' => 'Please select a valid date of birth or enter the age in years!'
            ),
        ),
        'age_years' => array(
            'dateOrYears' => array(
                'rule' => 'dateOrYears',
                'message' => 'Please select a valid date of birth or enter the age in years!'
            ),
            'validAgeYears' => array(
                'rule' => 'validAgeYears',
                'allowEmpty' => true,
                'message' => 'Please enter a valid age in years between 0 and 140!'
            ),
        ),
        'enrollment_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please enter the date of enrollment into the study!'
            ),
            'validDateValue' => array(
                'rule' => 'validDateValue',
                'message' => 'Please enter a valid date of enrollment into the study!'
            ),
            'dateBeforeAdministration' => array(
                'rule' => 'dateBeforeAdministration',
                'message' => 'The enrollment date must be before the date of administration of investigational product!'
            ),
            'dateAfterBirthDate' => array(
                'rule' => 'dateAfterBirthDate',
                'message' => 'The enrollment date must be after the date of birth!'
            )
        ),
       'administration_date' => array(
                 'checkInitialRequired' => array(
                     'rule' => 'checkInitialRequired',
                     'message' => 'Please enter the initial date of administration (or check "IP was not given").'
                 ),
                 'validDateValue' => array(
                     'rule' => 'validDateValue',
                     'allowEmpty' => true,
                     'message' => 'Please enter a valid date of initial administration of the investigational product!'
               ),
                'dateBeforeAdministration' => array(
                    'rule' => 'dateBeforeAdministration',
                    'allowEmpty' => true,
                    'message' => 'The date of initial administration of the investigational product must be after the date of
      enrollment into the study!'
                ),
                'dateAfterBirthDate' => array(
                    'rule' => 'dateAfterBirthDate',
                    'allowEmpty' => true,
                    'message' => 'The date of initial administration of the investigational product must be after the date of
      birth!'
                )
            ),
            'latest_date' => array(
                'checkLatestDependency' => array(
                   'rule' => 'checkLatestDependency',
                    'message' => 'You cannot provide a latest administration date without an initial administration date.'
                ),
                'validDateValue' => array(
                    'rule' => 'validDateValue',
                    'allowEmpty' => true,
                    'message' => 'Please enter a valid date of latest administration of the investigational product!'
                ),
                'dateBeforeAdministration' => array(
                    'rule' => 'dateBeforeAdministration',
                    'allowEmpty' => true,
                  'message' => 'The date of the latest administration of the investigational product must be after the date
      of enrollment into the study and date of initial administration!'
               ),
                'dateAfterBirthDate' => array(
                   'rule' => 'dateAfterBirthDate',
                   'allowEmpty' => true,
                    'message' => 'The date of the latest administration of the investigational product must be after the date
      of birth!'
                )
            ),
            'ip_not_given_narrative' => array(
                'checkNarrativeRequired' => array(
                    'rule' => 'checkNarrativeRequired',
                    'message' => 'Please provide a narrative explaining why the IP was not given.'
                )
            ),

        'reaction_onset' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select a valid reaction onset date!'
            ),
            'validDateValue' => array(
                'rule' => 'validDateValue',
                'message' => 'Please select a valid reaction onset date!'
            ),
            'dateAfterStartDates' => array(
                'rule' => 'dateAfterStartDates',
                'message' => 'The reaction onset date must be after the date of drug administration'
            )
        ),
        'reaction_end_date' => array(
            'validDateValue' => array(
                'rule' => 'validDateValue',
                'allowEmpty' => true,
                'message' => 'Please select a valid reaction end date!'
            ),
            'dateAfterOnsetDate' => array(
                'rule' => 'dateAfterOnsetDate',
                'message' => 'The reaction end date must be after the reaction onset date'
            )
        ),
        'gender' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select the gender!'
            ),
            'inList' => array(
                'rule' => array('inList', array('Male', 'Female')),
                'message' => 'Please select a valid gender!'
            ),
        ),
        'causality' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please select the causality!'
            ),
            'inList' => array(
                'rule' => array('inList', array(
                    'Certain',
                    'Probable/Likely',
                    'Possible',
                    'Unlikely',
                    'Conditional/Unclassified',
                    'Not related',
                    'Unassessable/Unclassifiable',
                )),
                'message' => 'Please select a valid causality!'
            ),
        ),
        'reaction_description' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Please describe the reaction(s)!'
            ),
        ),
        'manufacturer_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the manufacturer details!'
            ),
        ),
        'mfr_no' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the manufacturer control number!'
            ),
        ),
        'manufacturer_date' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the date received by the manufacturer!'
            ),
            'validDateValue' => array(
                'rule' => 'validDateValue',
                'message' => 'Please enter a valid date received by the manufacturer!'
            ),
        ),
        'reporter_email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter the reporter email address!'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter a valid reporter email address!'
            ),
        ),
        'source_study' => array(
            'sourceSelected' => array(
                'rule' => 'sourceSelected',
                'message' => 'Select at least one report source!!'
            )
        ),
        'source_literature' => array(
            'sourceSelected' => array(
                'rule' => 'sourceSelected',
                'message' => 'Select at least one report source!!'
            )
        ),
        'source_health_professional' => array(
            'sourceSelected' => array(
                'rule' => 'sourceSelected',
                'message' => 'Select at least one report source!!'
            )
        ),
        'patient_died' => array(
            'reactionSelected' => array(
                'rule' => 'reactionSelected',
                'message' => 'Select at least one adverse reaction!!'
            )
        ),
        'life_threatening' => array(
            'reactionSelected' => array(
                'rule' => 'reactionSelected',
                'message' => 'Select at least one adverse reaction!!'
            )
        ),
        'reaction_other' => array(
            'reactionSelected' => array(
                'rule' => 'reactionSelected',
                'message' => 'Select at least one adverse reaction!!'
            )
        ),
      );

    public function generateReferenceNumber($formType = 'SAE', $createdAt = null) {
        if (empty($createdAt)) {
            $createdAt = date('Y-m-d H:i:s');
        }

        $year = date('Y', strtotime($createdAt));
        $count = $this->find('count', array(
            'conditions' => array(
                'Sae.form_type' => $formType,
                'Sae.created BETWEEN ? and ?' => array($year . '-01-01 00:00:00', $createdAt)
            )
        ));
        $count++;
        $count = ($count < 10) ? '0' . $count : $count;

        return $formType . '/' . $year . '/' . $count;
    }

    public function beforeSave() {

 if (!empty($this->data['Sae']['ip_not_given'])) {
            
                 $this->data['Sae']['administration_date'] = null;
                 $this->data['Sae']['latest_date'] = null;
             } else {
        
                 $this->data['Sae']['ip_not_given_narrative'] = null;
                $this->data['Sae']['ip_not_given'] = 0;
                
                if (empty($this->data['Sae']['latest_date'])) {
                     $this->data['Sae']['latest_date'] = null;
                }
            }

        if (!empty($this->data['Sae']['date_of_birth'])) {
            $this->data['Sae']['date_of_birth'] = $this->dateFormatBeforeSave($this->data['Sae']['date_of_birth']);
        }
        if (!empty($this->data['Sae']['enrollment_date'])) {
            $this->data['Sae']['enrollment_date'] = $this->dateFormatBeforeSave($this->data['Sae']['enrollment_date']);
        }
        if (!empty($this->data['Sae']['administration_date'])) {
            $this->data['Sae']['administration_date'] = $this->dateFormatBeforeSave($this->data['Sae']['administration_date']);
        }
        if (!empty($this->data['Sae']['latest_date'])) {
            $this->data['Sae']['latest_date'] = $this->dateFormatBeforeSave($this->data['Sae']['latest_date']);
        }
        if (!empty($this->data['Sae']['reaction_onset'])) {
            $this->data['Sae']['reaction_onset'] = $this->dateFormatBeforeSave($this->data['Sae']['reaction_onset']);
        }
        if (!empty($this->data['Sae']['reaction_end_date'])) {
            $this->data['Sae']['reaction_end_date'] = $this->dateFormatBeforeSave($this->data['Sae']['reaction_end_date']);
        }
        if (!empty($this->data['Sae']['manufacturer_date'])) {
            $this->data['Sae']['manufacturer_date'] = $this->dateFormatBeforeSave($this->data['Sae']['manufacturer_date']);
        }
        return true;
    }

    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['Sae']['date_of_birth'])) {
                $results[$key]['Sae']['date_of_birth'] = $this->dateFormatAfterFind($val['Sae']['date_of_birth']);
            }
            if (isset($val['Sae']['enrollment_date'])) {
                $results[$key]['Sae']['enrollment_date'] = $this->dateFormatAfterFind($val['Sae']['enrollment_date']);
            }
            if (isset($val['Sae']['administration_date'])) {
                $results[$key]['Sae']['administration_date'] = $this->dateFormatAfterFind($val['Sae']['administration_date']);
            }
            if (isset($val['Sae']['latest_date'])) {
                $results[$key]['Sae']['latest_date'] = $this->dateFormatAfterFind($val['Sae']['latest_date']);
            }
            if (isset($val['Sae']['reaction_onset'])) {
                $results[$key]['Sae']['reaction_onset'] = $this->dateFormatAfterFind($val['Sae']['reaction_onset']);
            }
            if (isset($val['Sae']['reaction_end_date'])) {
                $results[$key]['Sae']['reaction_end_date'] = $this->dateFormatAfterFind($val['Sae']['reaction_end_date']);
            }
            if (isset($val['Sae']['manufacturer_date'])) {
                $results[$key]['Sae']['manufacturer_date'] = $this->dateFormatAfterFind($val['Sae']['manufacturer_date']);
            }
        }
        return $results;
    }

    public function dateOrYears($field = null) {
        if(empty($this->data['Sae']['date_of_birth']) && empty($this->data['Sae']['age_years']))    return false;
        
        return true;
    }

    public function validDateValue($field = null) {
        $value = reset($field);
        if ($value === null || $value === '') {
            return true;
        }

        return ($this->_dateTimestamp($value) !== false);
    }

    public function validAgeYears($field = null) {
        $value = reset($field);
        if ($value === null || $value === '') {
            return true;
        }

        return is_numeric($value) && $value >= 0 && $value <= 140;
    }

    public function dateAfterStartDates($field = null) {
        $reactionOnset = $this->_dateTimestamp(reset($field));
        if ($reactionOnset === false) {
            return true;
        }

        if (!empty($this->data['SuspectedDrug'])) {
            foreach ($this->data['SuspectedDrug'] as $val) {
                if (empty($val['date_from'])) {
                    continue;
                }

                $therapyStart = $this->_dateTimestamp($val['date_from']);
                if ($therapyStart !== false && $reactionOnset < $therapyStart) {
                    return false;
                }
            }
        }
        return true;
    }

    public function dateBeforeAdministration($field = null) {
        $fieldName = key($field);
        $value = $this->_dateTimestamp(reset($field));
        if ($value === false) {
            return true;
        }

        $enrollmentDate = !empty($this->data['Sae']['enrollment_date']) ? $this->_dateTimestamp($this->data['Sae']['enrollment_date']) : false;
        $administrationDate = !empty($this->data['Sae']['administration_date']) ? $this->_dateTimestamp($this->data['Sae']['administration_date']) : false;
        $latestDate = !empty($this->data['Sae']['latest_date']) ? $this->_dateTimestamp($this->data['Sae']['latest_date']) : false;

        if ($fieldName === 'enrollment_date') {
            if ($administrationDate !== false && $value > $administrationDate) {
                return false;
            }
            if ($latestDate !== false && $value > $latestDate) {
                return false;
            }

            return true;
        }

        if ($fieldName === 'administration_date' && $enrollmentDate !== false) {
            if ($value < $enrollmentDate) {
                return false;
            }
            if ($latestDate !== false && $value > $latestDate) {
                return false;
            }

            return true;
        }

        if ($fieldName === 'latest_date') {
            if ($enrollmentDate !== false && $value < $enrollmentDate) {
                return false;
            }
            if ($administrationDate !== false && $value < $administrationDate) {
                return false;
            }
        }

        return true;
    }

    public function dateAfterBirthDate($field = null) {
        $value = $this->_dateTimestamp(reset($field));
        $birthDate = !empty($this->data['Sae']['date_of_birth']) ? $this->_dateTimestamp($this->data['Sae']['date_of_birth']) : false;

        if ($value === false || $birthDate === false) {
            return true;
        }

        return ($value >= $birthDate);
    }

    public function dateAfterOnsetDate($field = null) {
        $reactionEndDate = $this->_dateTimestamp(reset($field));
        $reactionOnset = !empty($this->data['Sae']['reaction_onset']) ? $this->_dateTimestamp($this->data['Sae']['reaction_onset']) : false;

        if ($reactionEndDate === false || $reactionOnset === false) {
            return true;
        }

        return ($reactionEndDate >= $reactionOnset);
    }

    public function sourceSelected($field = null) {
        if (empty($this->data['Sae']['source_study']) and empty($this->data['Sae']['source_literature']) and empty($this->data['Sae']['source_health_professional'])) {
            return false;
        }
        return true;
    }

    public function reactionSelected($field = null) {
        if (empty($this->data['Sae']['patient_died']) and empty($this->data['Sae']['prolonged_hospitalization']) and empty($this->data['Sae']['incapacity']) and empty($this->data['Sae']['life_threatening']) and empty($this->data['Sae']['reaction_other'])) {
            return false;
        }
        return true;
    }

    protected function _dateTimestamp($value) {
        if ($value === null || $value === '') {
            return false;
        }

        return strtotime($value);
    }

         public function checkInitialRequired($check) {
             if (!empty($this->data['Sae']['ip_not_given'])) {
                 return true; 
             }
             $value = reset($check);
             return !empty($value); 
         }
    
        public function checkLatestDependency($check) {
            $latestDate = reset($check);
            
            if (!empty($latestDate) && empty($this->data['Sae']['administration_date'])) {
                return false;
            }
            return true;
        }

        public function checkNarrativeRequired($check) {
            if (!empty($this->data['Sae']['ip_not_given'])) {
                $value = reset($check);
                return !empty($value); 
            }
            return true;
        }
}

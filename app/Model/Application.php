<?php
App::uses('AppModel', 'Model');
/**
 * Application Model
 *
 * @property User $User
 */
class Application extends AppModel
{

    public $actsAs = array('Containable', 'Search.Searchable', 'SoftDelete');
    public $filterArgs = array(
        'protocol_no' => array('type' => 'like', 'encode' => true),
        'disease_condition' => array('type' => 'like', 'encode' => true),
        'product_type_biologicals' => array('type' => 'value'),
        'product_type_proteins' => array('type' => 'value'),
        'product_type_immunologicals' => array('type' => 'value'),
        'product_type_vaccines' => array('type' => 'value'),
        'product_type_hormones' => array('type' => 'value'),
        'product_type_toxoid' => array('type' => 'value'),
        'product_type_chemical' => array('type' => 'value'),
        'product_type_medical_device' => array('type' => 'value'),
        'filter' => array('type' => 'query', 'method' => 'orConditions', 'encode' => true),
        'start_date' => array('type' => 'query', 'method' => 'dummy'),
        'end_date' => array('type' => 'query', 'method' => 'dummy'),
        'submitted' => array('type' => 'value'),
        'approved' => array('type' => 'value'),
        'deactivated' => array('type' => 'value'),
        'deleted' => array('type' => 'value'),
        'trial_status_id' => array('type' => 'value'),
        'trial_human_pharmacology' => array('type' => 'value'),
        'trial_therapeutic_exploratory' => array('type' => 'value'),
        'trial_therapeutic_confirmatory' => array('type' => 'value'),
        'trial_therapeutic_use' => array('type' => 'value'),
        'approvedrange' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Application.approval_date BETWEEN ? AND ?'),
        'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Application.date_submitted BETWEEN ? AND ?'),
        'investigator' => array('type' => 'query', 'method' => 'findByInvestigators', 'encode' => true),
        'users' => array('type' => 'query', 'method' => 'findByReviewer', 'encode' => true),
        'ercs' => array('type' => 'query', 'method' => 'findByErc', 'encode' => true),
        'sites' => array('type' => 'query', 'method' => 'orSites', 'encode' => true),
        'stages' => array('type' => 'query', 'method' => 'findByStage', 'encode' => true),
        'month_year' => array('type' => 'query', 'method' => 'dummy'),
        'mode' => array('type' => 'expression', 'method' => 'makeMonthYearCondition', 'field' => 'Application.date_submitted BETWEEN ? AND ?'),
        'phase'=>array('type' => 'query', 'method' => 'phaseConditions', 'encode' => true),
    );
    public function phaseConditions($data=array()){
        $filter = $data['phase'];     
        $cond = array();
    
        if ($filter == 1) {
            $cond[$this->alias . '.trial_human_pharmacology'] = 1;
        } elseif ($filter == 2) {
            $cond[$this->alias . '.trial_therapeutic_exploratory'] = 1;
        } elseif ($filter == 3) {
            $cond[$this->alias . '.trial_therapeutic_confirmatory'] = 1;
        } elseif ($filter == 4) {
            $cond[$this->alias . '.trial_therapeutic_use'] = 1;
        }
    
        // Add other conditions if needed
    
        return $cond;
     

    }
    public function dummy($data = array())
    {
        return array('1' => '1');
    }

    public function findByInvestigators($data = array())
    {
        $cond = array($this->alias . '.id' => $this->InvestigatorContact->find('list', array(
            'conditions' => array(
                'OR' => array(
                    'InvestigatorContact.given_name LIKE' => '%' . $data['investigator'] . '%',
                    'InvestigatorContact.middle_name LIKE' => '%' . $data['investigator'] . '%',
                    'InvestigatorContact.family_name LIKE' => '%' . $data['investigator'] . '%',
                )
            ),
            'fields' => array('application_id', 'application_id')
        )));
        return $cond;
    }

    public function findByReviewer($data = array())
    {
        $cond = array($this->alias . '.id' => $this->Review->find('list', array(
            'conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted', 'Review.user_id' => $data['users']),
            'fields' => array('application_id', 'application_id')
        )));
        return $cond;
    }

    public function findByErc($data = array())
    {
        // debug($data['ercs']);
        $cond = array($this->alias . '.id' => $this->EthicalCommittee->find('list', array(
            'conditions' => array('EthicalCommittee.ethical_committee' => $data['ercs']),
            'fields' => array('application_id', 'application_id')
        )));
        return $cond;
    }

    public function findByStage($data = array())
    {
        // debug($data);
        $cond = array($this->alias . '.id' => $this->ApplicationStage->find('list', array(
            'conditions' => array('ApplicationStage.status' => $data['status'], 'ApplicationStage.stage' => $data['stages']),
            'fields' => array('application_id', 'application_id')
        )));
        return $cond;
    }

    public function orSites($data = array())
    {
        $counties = $this->SiteDetail->County->find('list', array(
            'conditions' => array('County.county_name LIKE' => '%' . $data['sites'] . '%'),
            'fields' => array('id')
        ));
        if (empty($counties)) $counties = 0;
        $cond = array(
            'OR' => array(
                $this->alias . '.location_of_area LIKE' => '%' . $data['sites'] . '%',
                $this->alias . '.id' => $this->SiteDetail->find('list', array(
                    'conditions' => array(
                        'OR' => array(
                            'SiteDetail.site_name LIKE' => '%' . $data['sites'] . '%',
                            'SiteDetail.county_id' => $counties
                        )
                    ),
                    'fields' => array('application_id')
                )),
            )
        );
        return $cond;
    }

    public function orConditions($data = array())
    {
        $filter = $data['filter'];
        $cond = array(
            'OR' => array(
                $this->alias . '.study_title LIKE' => '%' . $filter . '%',
                $this->alias . '.abstract_of_study LIKE' => '%' . $filter . '%',
                $this->alias . '.short_title LIKE' => '%' . $filter . '%',
            )
        );
        return $cond;
    }

    public function makeRangeCondition($data = array())
    {
        if (!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
        else $start_date = date('Y-m-d', strtotime('2012-05-01'));

        if (!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
        else $end_date = date('Y-m-d');

        return array($start_date, $end_date);
    }
    public function makeMonthYearCondition($data = array())
    {
        if (!empty($data['month_year'])) {
            // Convert the string to a DateTime object
            $date = DateTime::createFromFormat('m-Y', $data['month_year']);
            // Get the first day of the month
            $start_date = $date->format('Y-m-01');
            // Get the last day of the month
            $end_date = $date->format('Y-m-t');
        } else {
            $start_date = date('Y-m-d', strtotime('2012-05-01'));
            $end_date = date('Y-m-d');
        }

        return array($start_date, $end_date);
    }


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     */

    public $hasMany = array(
        'Review' => array(
            'className' => 'Review',
            'foreignKey' => 'application_id',
            'dependent' => false,
            'conditions' => array('Review.category' => '0'),
        ),
        'InternalReview' => array(
            'className' => 'Review',
            'foreignKey' => 'application_id',
            'dependent' => false,
            'conditions' => array('InternalReview.category' => '1'),
        ),
        'ActiveInspector' => array(
            'className' => 'ActiveInspector',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'ManagerReview' => array(
            'className' => 'Review',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'StudyAuditor' => array(
            'className' => 'StudyAuditor',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'AuditReport' => array(
            'className' => 'AuditReport',
            'foreignKey' => 'application_id',
            'dependent' => true,
        ),
        // 'Request' => array(
        //            'className' => 'Review',
        //            'foreignKey' => 'application_id',
        //            'dependent' => true,
        //            'conditions' => array('Request.type' => 'response'),
        //  ),
        'Acceptance' => array(
            'className' => 'Review',
            'foreignKey' => 'application_id',
            'dependent' => true,
            'conditions' => array('Acceptance.type' => 'acceptance'),
        ),
        'Amendment' => array(
            'className' => 'Amendment',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'InvestigatorContact' => array(
            'className' => 'InvestigatorContact',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'ApplicationStage' => array(
            'className' => 'ApplicationStage',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Pharmacist' => array(
            'className' => 'Pharmacist',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'EthicalCommittee' => array(
            'className' => 'EthicalCommittee',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'AnnualLetter' => array(
            'className' => 'AnnualLetter',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'AmendmentLetter' => array(
            'className' => 'AmendmentLetter',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'AmendmentApproval' => array(
            'className' => 'AmendmentApproval',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),

        'AmendmentApprovalSummary' => array(
            'className' => 'AmendmentApproval',
            'foreignKey' => 'application_id',
            'dependent' => false,            
            'conditions' => array('AmendmentApprovalSummary.status' => 'summary'),
        ),

        'Organization' => array(
            'className' => 'Organization',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'SiteDetail' => array(
            'className' => 'SiteDetail',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Placebo' => array(
            'className' => 'Placebo',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Sponsor' => array(
            'className' => 'Sponsor',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'SiteInspection' => array(
            'className' => 'SiteInspection',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Sae' => array(
            'className' => 'Sae',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Ciom' => array(
            'className' => 'Ciom',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'StudyRoute' => array(
            'className' => 'StudyRoute',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Manufacturer' => array(
            'className' => 'Manufacturer',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Reassignment' => array(
            'className' => 'Reassignment',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Attachment.model' => 'Application', 'Attachment.group' => 'attachment'),
        ),
        'AnnualApproval' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('AnnualApproval.model' => 'AnnualApproval'),
        ),
        'AmendmentChecklist' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('AmendmentChecklist.model' => 'AmendmentChecklist'),
        ),
        'Reminder' => array(
            'className' => 'Reminder',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Reminder.model' => 'Application'),
        ),
        'ParticipantFlow' => array(
            'className' => 'ParticipantFlow',
            'foreignKey' => 'application_id',
            'dependent' => false
        ),
        'Budget' => array(
            'className' => 'Budget',
            'foreignKey' => 'application_id',
            'dependent' => false
        ),
        'Deviation' => array(
            'className' => 'Deviation',
            'foreignKey' => 'application_id',
            'dependent' => false
        ),
        'Document' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Document.model' => 'Application', 'Document.group' => 'document'),
        ),
        'CoverLetter' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('CoverLetter.model' => 'Application', 'CoverLetter.group' => 'cover_letter'),
        ),
        'Protocol' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Protocol.model' => 'Application', 'Protocol.group' => 'protocol'),
        ),
        'PatientLeaflet' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('PatientLeaflet.model' => 'Application', 'PatientLeaflet.group' => 'patient_leaflet'),
        ),
        'Brochure' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Brochure.model' => 'Application', 'Brochure.group' => 'brochure'),
        ),
        'GmpCertificate' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('GmpCertificate.model' => 'Application', 'GmpCertificate.group' => 'gmp_certificate'),
        ),
        'Cv' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Cv.model' => 'Application', 'Cv.group' => 'cv'),
        ),
        'Finance' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Finance.model' => 'Application', 'Finance.group' => 'finance'),
        ),
        'Declaration' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Declaration.model' => 'Application', 'Declaration.group' => 'declaration'),
        ),
        'IndemnityCover' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('IndemnityCover.model' => 'Application', 'IndemnityCover.group' => 'indemnity_cover'),
        ),
        'OpinionLetter' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('OpinionLetter.model' => 'Application', 'OpinionLetter.group' => 'opinion_letter'),
        ),
        'ApprovalLetter' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('ApprovalLetter.model' => 'Application', 'ApprovalLetter.group' => 'approval_letter'),
        ),
        'Statement' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Statement.model' => 'Application', 'Statement.group' => 'statement'),
        ),
        'ParticipatingStudy' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('ParticipatingStudy.model' => 'Application', 'ParticipatingStudy.group' => 'participating_study'),
        ),
        'Addendum' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Addendum.model' => 'Application', 'Addendum.group' => 'addendum'),
        ),
        'Registration' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Registration.model' => 'Application', 'Registration.group' => 'registration'),
        ),
        'Fee' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Fee.model' => 'Application', 'Fee.group' => 'fee'),
        ),
        'Checklist' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'conditions' => array('Checklist.model' => 'Checklist'),
        ),
        'StudyMonitor' => array(
            'className' => 'StudyMonitor',
            'foreignKey' => 'user_id',
            'dependent' => false,
        ), 
         'ProtocolOutsource' => array(
            'className' => 'ProtocolOutsource',
            'foreignKey' => 'user_id',
            'dependent' => false,
        ),
        
        'Outsource' => array(
            'className' => 'Outsource',
            'foreignKey' => 'application_id',
            'dependent' => false,
        ),
    );

    public $validate = array(
        'study_title' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter the study title'
            ),
        ),
        'abstract_of_study' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter the abstract of the study'
            ),
        ),
        /*'protocol_no' => array(
            // 'notEmpty' => array(
            //     'rule'     => 'notEmpty',
            //     'required' => true,
            //     'message'  => 'Please enter the protocol number'
            // ),
    'unique' => array(
       'rule' => 'isUnique',
       'required' => false,
       'message' => 'The protocol number you have provided is already in use!',
    ),
        ),*/
        'version_no' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter the version number'
            ),
        ),
        'date_of_protocol' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter the Date of protocol.'
            ),
        ),
        'study_drug' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter the study drug'
            ),
        ),
        'disease_condition' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please enter disease condition being investigated'
            ),
        ),
        'product_type' => array(
            'ifProductType' => array(
                'rule'     => array('ifProductType'),
                'required' => true,
                'message'  => 'Abstract : Please select at least one product type'
            ),
        ),
        'product_type_chemical' => array(
            'ifProductTypeChemical' => array(
                'rule'     => array('ifProductTypeChemical'),
                'required' => true,
                'message'  => 'Abstract : Please enter the generic name of the product type'
            ),
        ),
        'product_type_medical_device' => array(
            'ifProductTypeChemical' => array(
                'rule'     => array('ifProductTypeMedicalDevice'),
                'required' => true,
                'message'  => 'Abstract : Please enter the name of the medical device under product type'
            ),
        ),
        'product_type_biologicals' => array(
            'ifProductTypeBiologicals' => array(
                'rule'     => array('ifProductTypeBiologicals'),
                'required' => true,
                'message'  => 'Abstract : Please select the type of biologicals'
            ),
        ),
        'comparator' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Abstract : Please select yes/no for comparator'
            ),
        ),
        'site_exists' => array(
            'ifSiteExists' => array(
                'rule'     => array('ifSiteExists'),
                'required' => true,
                'message'  => 'Sites : You have to select yes for either single site, muliple sites or multiple countries'
            ),
        ),
        'single_site_member_state' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Sites : Please select yes/no for single site in Kenya'
            ),
        ),
        'location_of_area' => array(
            'ifSingleSite' => array(
                'rule'     => array('ifSingleSite'),
                'required' => true,
                'message'  => 'Sites : Please enter the name for the single site in Kenya'
            ),
        ),
        'single_site_physical_address' => array(
            'ifSingleSite1' => array(
                'rule'     => array('ifSingleSite1'),
                'required' => true,
                'message'  => 'Sites : Please enter the physical address for the single site in Kenya'
            ),
        ),
        'single_site_contact_person' => array(
            'ifSingleSite2' => array(
                'rule'     => array('ifSingleSite2'),
                'required' => true,
                'message'  => 'Sites : Please enter the contact person for the single site in Kenya'
            ),
        ),
        'single_site_telephone' => array(
            'ifSingleSite3' => array(
                'rule'     => array('ifSingleSite3'),
                'required' => true,
                'message'  => 'Sites : Please enter the telephone for the single site in Kenya'
            ),
        ),
        'multiple_sites_member_state' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Sites : Please select yes/no for multiple sites in Kenya'
            ),
        ),
        'number_of_sites' => array(
            'ifMultipleSites' => array(
                'rule'     => array('ifMultipleSites'),
                'required' => true,
                'message'  => 'Sites : Please enter the number of multiple sites expected in Kenya'
            ),
        ),
        'multiple_countries' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Sites : Please select yes/no for multiple countries'
            ),
        ),
        'multiple_member_states' => array(
            'ifMultipleStates' => array(
                'rule'     => array('ifMultipleStates1'),
                'required' => true,
                'message'  => 'Sites : Please enter the number of multiple states expected in the trial'
            ),
        ),
        'multi_country_list' => array(
            'ifMultipleStates' => array(
                'rule'     => array('ifMultipleStates2'),
                'required' => true,
                'message'  => 'Sites : Please enter the list of states expected in the trial'
            ),
        ),
        'data_monitoring_committee' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Sites : Please indicate yes/no for the data monitoring committee.'
            ),
        ),
        'staff_numbers' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Sites : Please enter the capacity for the site(s)'
            ),
        ),
        'number_participants' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please enter expected number of participants in Kenya.'
            ),
        ),
        'total_enrolment_per_site' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please enter total enrollment in each site in Kenya.'
            ),
        ),
        'total_participants_worldwide' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please enter total number of participants worldwide.'
            ),
        ),

        // 'ecct_ref_number' => array(
        // 'ifApplicable' => array(
        // 'rule'     => array('ifApplicable'),
        // 'required' => false,
        // 'message'  => 'Please enter ECCT ref number if applicable'
        // ),
        // ),

        // 'trial_status_id' => array(
        // 'numeric' => array(
        // 'rule' => array('numeric'),
        // ),
        // ),

        'age_span' => array(
            'ifAgeSpanExists' => array(
                'rule'     => array('ifAgeSpanExists'),
                'required' => true,
                'message'  => 'Participants : You have to select yes for either population below 18 years or above 18 years'
            ),
        ),
        'population_less_than_18_years' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population preterm newborn infants'
            ),
            'ifBelow18years' => array(
                'rule'     => array('ifBelow18years'),
                'required' => true,
                'message'  => 'Participants : Please select yes for population below 18 years because some options below are selected yes.'
            ),
        ),
        'population_utero' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population in utero'
            ),
        ),
        'population_preterm_newborn' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population preterm newborn infants'
            ),
        ),
        'population_newborn' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population newborn'
            ),
        ),
        'population_infant_and_toddler' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population infant and toddler'
            ),
        ),
        'population_children' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population children'
            ),
        ),
        'population_adolescent' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population adolescent'
            ),
        ),
        'population_above_18' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please state if population is above 18 Years.'
            ),
            'ifAbove18years' => array(
                'rule'     => array('ifAbove18years'),
                'required' => true,
                'message'  => 'Participants : Please select yes for population above 18 years because some options selected below are yes.'
            ),
        ),
        'population_adult' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population is adult'
            ),
        ),
        'population_elderly' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Participants : Please select yes/no for population is elderly'
            ),
        ),
        'subjects_healthy' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are healthy volunteers'
            ),
        ),
        'subjects_vulnerable_populations' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects belong to specific vulnerable populations'
            ),
            'ifVulnerableOptions' => array(
                'rule'     => array('ifVulnerableOptions'),
                'required' => false,
                'message'  => 'Participants : Please select yes for subjects belong to specific vulnerable populations because some of the options selected below is yes'
            ),
        ),
        'subjects_patients' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are patients'
            ),
        ),
        'subjects_women_child_bearing' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are women of child bearing potential'
            ),
        ),
        'subjects_women_using_contraception' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are women of child bearing potential using contraception'
            ),
        ),
        'subjects_pregnant_women' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are pregnant women'
            ),
        ),
        'subjects_nursing_women' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are nursing women'
            ),
        ),
        'subjects_emergency_situation' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are in emergency situation'
            ),
        ),
        'subjects_incapable_consent' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are incapable of giving consent personally'
            ),
        ),
        'subjects_specify' => array(
            'ifIncapableConsent' => array(
                'rule'     => array('ifIncapableConsent'),
                'required' => false,
                'message'  => 'Participants : Please enter the reason why subjects are incapable of giving consent personally'
            ),
        ),
        'subjects_others' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please select yes/no if subjects are others and specify'
            ),
        ),
        'subjects_others_specify' => array(
            'ifSubjectsOthers' => array(
                'rule'     => array('ifSubjectsOthers'),
                'required' => false,
                'message'  => 'Participants : Please specify the other specific vulnerable group'
            ),
        ),
        'gender' => array(
            'ifGenderExists' => array(
                'rule'     => array('ifGenderExists'),
                'required' => true,
                'message'  => 'Participants : Please select at least one gender for the participants'
            ),
        ),

        'contact_person' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Participants : Please enter the contact person'
            ),
        ),
        'address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Please enter the address'
            ),
        ),
        'cell_number' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Please enter the cell number'
            ),
        ),
        'email_address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => false,
                'message'  => 'Please enter the email address'
            ),
        ),
        'investigator1_given_name' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the given name of the co-ordinating investigator'
            ),
        ),
        'investigator1_family_name' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the family name of the co-ordinating investigator'
            ),
        ),
        'investigator1_qualification' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the qualification of the co-ordinating investigator'
            ),
        ),
        'investigator1_telephone' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please enter the investigator\'s phone number'
            ),
        ),
        'investigator1_email' => array(
            'notEmpty' => array(
                'rule'     => 'email',
                'required' => true,
                'message'  => 'Investigator : Please enter a valid investigator\'s email address'
            ),
        ),
        'investigator1_professional_address' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Investigator : Please state the qualification of the co-ordinating investigator'
            ),
        ),
        'organisations_transferred_' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Organizations : Please state if the sponsor has transferred trial related duties to another organization.'
            ),
        ),
        'study_objectives' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Criteria : Please enter the study objectives.'
            ),
        ),
        'principal_inclusion_criteria' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Criteria : Please enter the principal inclusion criteria.'
            ),
        ),
        'principal_exclusion_criteria' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Criteria : Please enter the principal exclusion criteria.'
            ),
        ),
        'primary_end_points' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Criteria : Please enter the primary end points.'
            ),
        ),
        'scope' => array(
            'atLeastScope' => array(
                'rule'     => array('atLeastScope'),
                'required' => true,
                'message'  => 'Scope : Please select at least one option for scope of the trial'
            ),
        ),
        'scope_others_specify' => array(
            'ifScopeOthers' => array(
                'rule'     => array('ifScopeOthers'),
                'required' => true,
                'message'  => 'Scope : Please specify the scope of the trial for the others option'
            ),
        ),
        'phase' => array(
            'atLeastPhase' => array(
                'rule'     => array('atLeastPhase'),
                'required' => true,
                'message'  => 'Scope : Please select at least one option for the trial type and phase'
            ),
        ),
        'trial_other_specify' => array(
            'ifTrialOther' => array(
                'rule'     => array('ifTrialOther'),
                'required' => true,
                'message'  => 'Scope : Please specify the other trial type and phase'
            ),
        ),
        'design_controlled' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is controlled.'
            ),
        ),
        'design_controlled_randomised' => array(
            'notEmptyRadioControlled' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_randomised'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is randomised.'
            ),
        ),
        'design_controlled_open' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_open'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is open.'
            ),
        ),
        'design_controlled_single_blind' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_single_blind'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is single blind.'
            ),
        ),
        'design_controlled_double_blind' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_double_blind'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is double blind.'
            ),
        ),
        'design_controlled_parallel_group' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_parallel_group'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is parallel group.'
            ),
        ),
        'design_controlled_cross_over' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_cross_over'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is cross over.'
            ),
        ),
        'design_controlled_other' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_other'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is other and specify'
            ),
        ),
        'design_controlled_other_medicinal' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_other_medicinal'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is medicinal.'
            ),
        ),
        'design_controlled_placebo' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_placebo'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is placebo.'
            ),
        ),
        'design_controlled_medicinal_other' => array(
            'notEmpty' => array(
                'rule'     => array('notEmptyRadioControlled', 'design_controlled_medicinal_other'),
                'required' => true,
                'message'  => 'Design : Please state if the design of the trial is other medicinal and specify'
            ),
        ),
        'design_controlled_specify' => array(
            'ifDesignControlledOther' => array(
                'rule'     => array('ifDesignControlledOther'),
                'required' => true,
                'message'  => 'Design : Please specify the design of the trial'
            ),
        ),
        'design_controlled_comparator' => array(
            'ifDesignControlledComparator' => array(
                'rule'     => array('ifDesignControlledComparator'),
                'required' => true,
                'message'  => 'Design : Please specify the comparator'
            ),
        ),
        'design_controlled_medicinal_specify' => array(
            'ifDesignMedicinalSpecify' => array(
                'rule'     => array('ifDesignMedicinalSpecify'),
                'required' => true,
                'message'  => 'Design : Please specify the medicinal product'
            ),
        ),
        'placebo_present' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Placebo : Please specify the placebo other'
            ),
        ),
        // 'site_capacity' => array(
        // 'notEmpty' => array(
        // 'rule'     => 'notEmpty',
        // 'required' => true,
        // 'message'  => 'Please enter the capacity of the site(s).'
        // ),
        // ),
        'estimated_duration' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Other details : Please enter the estimated duration of the trial.'
            ),
        ),
        'other_details_regulatory_notapproved' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Other details : Name other Regulatory Authorities to which applications to do this trial have been submitted, but approval
                                has not yet been granted. Include date(s) of application.'
            ),
        ),
        'other_details_regulatory_approved' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Other details : Please enter the Names of other Regulatory Authorities which have approved this trial, date(s) of approval
                                and number of sites per country.'
            ),
        ),
        'applicant_covering_letter' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_covering_letter'),
                'required' => true,
                'message'  => 'Checklist : Please upload the cover letter'
            ),
        ),
        'applicant_protocol' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_protocol'),
                'required' => true,
                'message'  => 'Checklist : Please upload the protocol'
            ),
        ),
        'applicant_patient_information' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_patient_information'),
                'required' => true,
                'message'  => 'Checklist : Please upload the patient information leaflet and informed consent form'
            ),
        ),
        'applicant_investigators_brochure' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_investigators_brochure'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Investigators Brochure/Package inserts or Investigational Medicinal Product Dossier (IMPD)'
            ),
        ),
        'applicant_gmp_certificate' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_gmp_certificate'),
                'required' => true,
                'message'  => 'Checklist : Please upload the GMP certificate of the investigational product'
            ),
        ),
        'applicant_investigators_cv' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_investigators_cv'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Signed investigator(s) CV(s)'
            ),
        ),
        'applicant_financial_declaration' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_financial_declaration'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Financial declaration by Sponsor and/or PI '
            ),
        ),
        'applicant_signed_declaration' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_signed_declaration'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Signed Declaration by Sponsor or Principal investigator'
            ),
        ),
        'applicant_indemnity_cover' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_indemnity_cover'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Indemnity cover and Insurance Certificate for the participants'
            ),
        ),
        'applicant_opinion_letter' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_opinion_letter'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Copy of favourable opinion letter from the local Institutional Review Board (IRB) and Ethics committee.'
            ),
        ),
        // 'applicant_approval_letter' => array(
        // 'notEmptyCheckbox' => array(
        // 'rule'     => array('notEmptyCheckbox', 'applicant_approval_letter'),
        // 'required' => true,
        // 'message'  => 'Please upload the Copy of approval letter(s) from collaborating institutions or other regulatory authorities, if applicable'
        // ),
        // ),
        'applicant_statement' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_statement'),
                'required' => true,
                'message'  => 'Checklist : Please upload a signed statement by the applicant indicating that all information contained in,
                                or referenced by, the application is complete and accurate and is not false or misleading.'
            ),
        ),
        // 'applicant_participating_countries' => array(
        // 'notEmptyCheckbox' => array(
        // 'rule'     => array('notEmptyCheckbox', 'applicant_participating_countries'),
        // 'required' => true,
        // 'message'  => 'Please upload the cover letter'
        // ),
        // ),
        'applicant_fees' => array(
            'notEmptyCheckbox' => array(
                'rule'     => array('notEmptyCheckbox', 'applicant_fees'),
                'required' => true,
                'message'  => 'Checklist : Please upload the Fees receipt'
            ),
        ),
        'declaration_applicant' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Declaration : Please enter the applicant\'s local contact.'
            ),
        ),
        'declaration_date1' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Declaration : Please enter the applicant\'s local contact date'
            ),
        ),
        'declaration_principal_investigator' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Declaration : Please enter the declaration by the National Principal Investigator / National Co-ordinator /
                                Other (state designation'
            ),
        ),
        'declaration_date2' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Declaration : Please enter the date for the declaration by the National Principal Investigator / National Co-ordinator /
                                Other (state designation'
            ),
        ),
    );

    public function notEmptyCheckbox($field = null, $key)
    {
        return $field[$key] > 0;
    }

    public function notEmptyRadioControlled($field = null, $key)
    {
        if ($this->data['Application']['design_controlled'] == 'Yes') return !empty($field[$key]);
        return true;
    }

    public function ifApplicable($field = null)
    {
        // pr($this->data['Application']['ecct_not_applicable']);
        if ($this->data['Application']['ecct_not_applicable'] == '0') return !empty($field['ecct_ref_number']);
        return true;
    }

    public function ifProductType($field = null)
    {
        return $this->data['Application']['product_type_biologicals'] == '1' ||
            $this->data['Application']['product_type_chemical'] == '1' ||
            $this->data['Application']['product_type_medical_device'] == '1';
    }

    public function ifProductTypeBiologicals($field = null)
    {
        if ($field['product_type_biologicals'] == 1) {
            return $this->data['Application']['product_type_proteins'] == '1' ||
                $this->data['Application']['product_type_immunologicals'] == '1' ||
                $this->data['Application']['product_type_vaccines'] == '1' ||
                $this->data['Application']['product_type_hormones'] == '1' ||
                $this->data['Application']['product_type_toxoid'] == '1';
        }
        return true;
    }

    public function ifProductTypeChemical($field = null)
    {
        if ($field['product_type_chemical'] == 1) {
            return !empty($this->data['Application']['product_type_chemical_name']);
        }
        return true;
    }

    public function ifProductTypeMedicalDevice($field = null)
    {
        if ($field['product_type_medical_device'] == 1) {
            return !empty($this->data['Application']['product_type_medical_device_name']);
        }
        return true;
    }

    public function ifSiteExists($field = null)
    {
        return $this->data['Application']['single_site_member_state'] == 'Yes' ||
            $this->data['Application']['multiple_sites_member_state'] == 'Yes' ||
            $this->data['Application']['multiple_countries'] == 'Yes';
    }

    public function ifSingleSite($field = null)
    {
        if ($this->data['Application']['single_site_member_state'] == 'Yes' && !empty($field['location_of_area'])) {
            return true;
        } else if ($this->data['Application']['single_site_member_state'] == 'No' && empty($field['location_of_area'])) {
            return true;
        }
        return false;
    }

    public function ifSingleSite1($field = null)
    {
        if (isset($this->data['Application']['single_site_member_state'])) {
            if ($this->data['Application']['single_site_member_state'] == 'Yes' && !empty($field['single_site_physical_address'])) {
                return true;
            } else if ($this->data['Application']['single_site_member_state'] == 'No' && empty($field['single_site_physical_address'])) {
                return true;
            }
        } else if (!isset($this->data['Application']['single_site_member_state']) && !empty($field['single_site_physical_address'])) {
            return true;
        }
        return false;
    }

    public function ifSingleSite2($field = null)
    {
        if (isset($this->data['Application']['single_site_member_state'])) {
            if ($this->data['Application']['single_site_member_state'] == 'Yes' && !empty($field['single_site_contact_person'])) {
                return true;
            } else if ($this->data['Application']['single_site_member_state'] == 'No' && empty($field['single_site_contact_person'])) {
                return true;
            }
        } else if (!isset($this->data['Application']['single_site_member_state']) && !empty($field['single_site_contact_person'])) {
            return true;
        }
        return false;
    }

    public function ifSingleSite3($field = null)
    {
        if (isset($this->data['Application']['single_site_member_state'])) {
            if ($this->data['Application']['single_site_member_state'] == 'Yes' && !empty($field['single_site_telephone'])) {
                return true;
            } else if ($this->data['Application']['single_site_member_state'] == 'No' && empty($field['single_site_telephone'])) {
                return true;
            }
        } else if (!isset($this->data['Application']['single_site_member_state']) && !empty($field['single_site_telephone'])) {
            return true;
        }
        return false;
    }

    public function ifMultipleSites($field = null)
    {
        if ($this->data['Application']['multiple_sites_member_state'] == 'Yes' && !empty($field['number_of_sites'])) {
            return true;
        } else if ($this->data['Application']['multiple_sites_member_state'] == 'No' && empty($field['number_of_sites'])) {
            return true;
        }
        return false;
    }

    public function ifMultipleStates1($field = null)
    {
        if ($this->data['Application']['multiple_countries'] == 'Yes' && !empty($field['multiple_member_states'])) {
            return true;
        } else if ($this->data['Application']['multiple_countries'] == 'No' && empty($field['multiple_member_states'])) {
            return true;
        }
        return false;
    }

    public function ifMultipleStates2($field = null)
    {
        if ($this->data['Application']['multiple_countries'] == 'Yes' && !empty($field['multi_country_list'])) {
            return true;
        } else if ($this->data['Application']['multiple_countries'] == 'No' && empty($field['multi_country_list'])) {
            return true;
        }
        return false;
    }

    public function ifAgeSpanExists($field = null)
    {
        return $this->data['Application']['population_less_than_18_years'] == 'Yes' ||
            $this->data['Application']['population_above_18'] == 'Yes';
    }

    public function ifBelow18years($field = null)
    {
        if ($this->data['Application']['population_less_than_18_years'] == 'No') {
            return $this->data['Application']['population_utero'] != 'Yes' &&  $this->data['Application']['population_preterm_newborn'] != 'Yes' &&
                $this->data['Application']['population_newborn'] != 'Yes' && $this->data['Application']['population_infant_and_toddler'] != 'Yes' &&
                $this->data['Application']['population_children'] != 'Yes' && $this->data['Application']['population_adolescent'] != 'Yes';
        }
        return true;
    }


    public function ifAbove18years($field = null)
    {
        if ($this->data['Application']['population_above_18'] == 'No') {
            return $this->data['Application']['population_adult'] != 'Yes' &&  $this->data['Application']['population_elderly'] != 'Yes';
        }
        return true;
    }

    public function ifVulnerableOptions($field = null)
    {
        if ($this->data['Application']['subjects_vulnerable_populations'] == 'No') {
            return $this->data['Application']['subjects_patients'] != 'Yes' &&  $this->data['Application']['subjects_women_child_bearing'] != 'Yes'  &&
                $this->data['Application']['subjects_women_using_contraception'] != 'Yes' &&  $this->data['Application']['subjects_pregnant_women'] != 'Yes'  &&
                $this->data['Application']['subjects_nursing_women'] != 'Yes' &&  $this->data['Application']['subjects_emergency_situation'] != 'Yes'  &&
                $this->data['Application']['subjects_incapable_consent'] != 'Yes' &&  $this->data['Application']['subjects_others'] != 'Yes';
        }
        return true;
    }

    public function ifIncapableConsent($field = null)
    {
        if ($this->data['Application']['subjects_incapable_consent'] == 'Yes' && !empty($field['subjects_specify'])) {
            return true;
        } else if ($this->data['Application']['subjects_incapable_consent'] == 'No' && empty($field['subjects_specify'])) {
            return true;
        }
        return false;
    }

    public function ifSubjectsOthers($field = null)
    {
        if ($this->data['Application']['subjects_others'] == 'Yes' && !empty($field['subjects_others_specify'])) {
            return true;
        } else if ($this->data['Application']['subjects_others'] == 'No' && empty($field['subjects_others_specify'])) {
            return true;
        }
        return false;
    }

    public function ifGenderExists($field = null)
    {
        return $this->data['Application']['gender_female'] == '1' ||
            $this->data['Application']['gender_male'] == '1';
    }

    public function atLeastScope($field = null)
    {
        return $this->data['Application']['scope_diagnosis'] + $this->data['Application']['scope_prophylaxis'] +
            $this->data['Application']['scope_therapy'] + $this->data['Application']['scope_safety'] +
            $this->data['Application']['scope_efficacy'] + $this->data['Application']['scope_pharmacokinetic'] +
            $this->data['Application']['scope_pharmacodynamic'] + $this->data['Application']['scope_bioequivalence'] +
            $this->data['Application']['scope_dose_response'] + $this->data['Application']['scope_pharmacogenetic'] +
            $this->data['Application']['scope_pharmacogenomic'] + $this->data['Application']['scope_pharmacoecomomic'] +
            $this->data['Application']['scope_others'] > 0;
    }

    public function ifScopeOthers($field = null)
    {
        if ($this->data['Application']['scope_others'] == '1' && empty($field['scope_others_specify'])) return false;
        return true;
    }

    public function atLeastPhase($field = null)
    {
        return $this->data['Application']['trial_human_pharmacology'] + $this->data['Application']['trial_administration_humans'] +
            $this->data['Application']['trial_bioequivalence_study'] + $this->data['Application']['trial_other'] +
            $this->data['Application']['trial_therapeutic_exploratory'] + $this->data['Application']['trial_therapeutic_confirmatory'] +
            $this->data['Application']['trial_therapeutic_use'] > 0;
    }

    public function ifTrialOther($field = null)
    {
        if ($this->data['Application']['trial_other'] == '1' && empty($field['trial_other_specify'])) return false;
        return true;
    }

    public function ifDesignControlledOther($field = null)
    {
        if ($this->data['Application']['design_controlled_other'] == 'Yes' && !empty($field['design_controlled_specify'])) {
            return true;
        } else if ($this->data['Application']['design_controlled_other'] == 'No' && empty($field['design_controlled_specify'])) {
            return true;
        } else if (empty($this->data['Application']['design_controlled_other']) && empty($field['design_controlled_specify'])) {
            return true;
        }
        return false;
    }

    public function ifDesignControlledComparator($field = null)
    {
        if ($this->data['Application']['design_controlled'] == 'Yes' && !empty($field['design_controlled_comparator'])) {
            return true;
        } else if ($this->data['Application']['design_controlled'] == 'No' && empty($field['design_controlled_comparator'])) {
            return true;
        } else if (empty($this->data['Application']['design_controlled']) && empty($field['design_controlled_comparator'])) {
            return true;
        }
        return false;
    }

    public function ifDesignMedicinalSpecify($field = null)
    {
        if ($this->data['Application']['design_controlled_medicinal_other'] == 'Yes' && !empty($field['design_controlled_medicinal_specify'])) {
            return true;
        } else if ($this->data['Application']['design_controlled_medicinal_other'] == 'No' && empty($field['design_controlled_medicinal_specify'])) {
            return true;
        } else if (empty($this->data['Application']['design_controlled_medicinal_other']) && empty($field['design_controlled_medicinal_specify'])) {
            return true;
        } else if (empty($this->data['Application']['design_controlled']) && empty($field['design_controlled_medicinal_specify'])) {
            return true;
        }
        return false;
    }

    // END -- custom validation rules

    // BEGIN -- model callback methods
    public function beforeValidate()
    {
        if (isset($this->data['Application']['multiple_sites_member_state']) && $this->data['Application']['multiple_sites_member_state'] == 'No') {
            $this->SiteDetail->validator()->remove('site_name')->remove('contact_details')->remove('contact_person')
                ->remove('physical_address')->remove('county_id');
        }
        if (isset($this->data['Application']['organisations_transferred_']) && $this->data['Application']['organisations_transferred_'] == 'No') {
            $this->Organization->validator()->remove('organization')->remove('contact_person')->remove('address')->remove('telephone_number')
                ->remove('all_tasks')->remove('monitoring')->remove('regulatory')->remove('investigator_recruitment')
                ->remove('ivrs_treatment_randomisation')
                ->remove('data_management')->remove('e_data_capture')->remove('susar_reporting')
                ->remove('quality_assurance_auditing')->remove('statistical_analysis')->remove('medical_writing')
                ->remove('other_duties');
        }
        if (isset($this->data['Application']['placebo_present']) && $this->data['Application']['placebo_present'] == 'No') {
            $this->Placebo->validator()->remove('pharmaceutical_form')->remove('route_of_administration')->remove('composition')
                ->remove('identical_indp')->remove('major_ingredients');
        }
        if (isset($this->data['Application']['protocol_no'])) {
            $this->validator()
                ->add('protocol_no', 'notEmpty', array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'message' => 'Please enter a valid protocol number.'
                ))
                ->add('protocol_no', 'unique', array(
                    'rule' => 'isUnique',
                    'required' => true,
                    'message' => 'The protocol number you have is already in use! Protocol number must be unique.'
                ));
        }
        return true;
    }

    public function beforeSave()
    {
        if (!empty($this->data['Application']['date_of_protocol'])) {
            $this->data['Application']['date_of_protocol'] = $this->dateFormatBeforeSave($this->data['Application']['date_of_protocol']);
        }
        if (!empty($this->data['Application']['approval_date'])) {
            $this->data['Application']['approval_date'] = $this->dateFormatBeforeSave($this->data['Application']['approval_date']);
        }
        if (!empty($this->data['Application']['date_submitted'])) {
            $this->data['Application']['date_submitted'] = $this->dateFormatBeforeSave($this->data['Application']['date_submitted']);
        }
        if (!empty($this->data['Application']['declaration_date1'])) {
            $this->data['Application']['declaration_date1'] = $this->dateFormatBeforeSave($this->data['Application']['declaration_date1']);
        }
        if (!empty($this->data['Application']['declaration_date2'])) {
            $this->data['Application']['declaration_date2'] = $this->dateFormatBeforeSave($this->data['Application']['declaration_date2']);
        }

        if (empty($this->data['Application']['ecct_ref_number'])) {
            $this->data['Application']['ecct_ref_number'] = '';
        }
        return true;
    }


    function afterFind($results)
    {
        foreach ($results as $key => $val) {
            if (isset($val['Application']['date_of_protocol'])) {
                $results[$key]['Application']['date_of_protocol'] = $this->dateFormatAfterFind($val['Application']['date_of_protocol']);
            }
            if (isset($val['Application']['approval_date'])) {
                $results[$key]['Application']['approval_date'] = $this->dateFormatAfterFind($val['Application']['approval_date']);
            }
            if (isset($val['Application']['date_submitted'])) {
                $results[$key]['Application']['date_submitted'] = $this->dateFormatAfterFind($val['Application']['date_submitted']);
            }
            if (isset($val['Application']['initial_date_submitted'])) {
                $results[$key]['Application']['initial_date_submitted'] = $this->dateFormatAfterFind($val['Application']['initial_date_submitted']);
            }
            if (isset($val['Application']['declaration_date1'])) {
                $results[$key]['Application']['declaration_date1'] = $this->dateFormatAfterFind($val['Application']['declaration_date1']);
            }
            if (isset($val['Application']['declaration_date2'])) {
                $results[$key]['Application']['declaration_date2'] = $this->dateFormatAfterFind($val['Application']['declaration_date2']);
            }
        }
        return $results;
    }

    // UTILITY METHODS
    /*public function isOwnedBy($application, $user) {
                // $my_app = $this->read(array('id', 'user_id', 'submitted'), $application);
                // $this->recursive = -1;
                $my_app = $this->find('first', array(
                    'fields' => array('Application.id', 'Application.submitted'),
                    'contain' => array('Reviewer'),
                    'conditions' => array('Application.id' => $application)));
                // pr($my_app);
                $response['true_user'] = false;  $response['app_submitted'] = false;
       if($my_app['Reviewer'][0]['user_id'] === $user)  $response['true_user'] = true;
                if($my_app['Application']['submitted'] == 1)  $response['app_submitted'] = true;
                return $response;
    }*/

    /*public function isOwnedBy($amendment, $user) {
                // $this->recursive = -1;
                  $my_amndt = $this->find('first', array('conditions' => array('Amendment.id' => $amendment)));
                  $response['Amendment'] = $my_amndt;
                  // $this->Application->recursive = -1;
                  $my_app = $this->Application->read(null, $my_amndt['Amendment']['application_id']);
                  $response['Application'] = $my_app;

                  $response['true_user'] = false; $response['amndt_submitted'] = false; $response['app_submitted'] = false;
                  if($my_app['Application']['user_id'] == $user)  $response['true_user'] = true;
                  if($my_amndt['Amendment']['submitted'] == 1)  $response['amndt_submitted'] = true;
                  if($my_app['Application']['submitted'] == 1)  $response['app_submitted'] = true;
                  // pr($response);
                   return $response;
            }*/
}

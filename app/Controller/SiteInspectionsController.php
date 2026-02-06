<?php
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

/**
 * SiteInspections Controller
 *
 * @property SiteInspection $SiteInspection
 */
class SiteInspectionsController extends AppController
{
    public $paginate = array();
    public $components = array('Search.Prg');
    public $presetVars = true; // using the model configuration
    public $uses = array('SiteInspection', 'Application', 'SiteQuestion');


    // public function index() {
    //     $this->SiteInspection->recursive = 0;
    //     $this->set('siteInspections', $this->paginate());
    // }
    /**
     * index method
     *
     * @return void
     */
    public function applicant_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->SiteInspection->parseCriteria($this->passedArgs);
        $criteria['Application.user_id'] = $this->Auth->User('id');
        $criteria['SiteInspection.approved'] = 2;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('SiteInspection.created' => 'desc');
        $this->paginate['contain'] = array('Application');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->SiteInspection->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export
        $this->set('page_options', $page_options);
        $this->set('siteInspections', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->SiteInspection->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['approved'])) $criteria['SiteInspection.approved'] = array(0, 1, 2); #todo: remove option 0
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('SiteInspection.created' => 'desc');
        $this->paginate['contain'] = array('Application' => array('InvestigatorContact', 'Sponsor'), 'User');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->SiteInspection->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('siteInspections', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function manager_index()
    {
        $this->index();
    }
    public function inspector_index()
    {
        $this->index();
    }

    private function csv_export($csiteInspections = '')
    {
        //todo: check if data exists in $csiteInspections
        $_serialize = 'csiteInspections';
        $_header = array(
            '#', 'Reference No.', 'Protocol No', 'PACTR No.',
            'Study Title', 'Short Title', 'Study Site', 'Co-ordinating Investigator Name', 'Co-ordinating Investigator Qualification', 'Co-ordinating Investigator Telephone', 'Co-ordinating Investigator Email',
            'Principal Investigator Name', 'Principal Investigator Qualification', 'Principal Investigator Telephone', 'Principal Investigator Email', 'Sponsor Name', 'Sponsor Phone', 'Sponsor Email',
            'Trial Phase', 'Inspector', 'Inspection date', 'Inspection outcome', 'Inspection conclusion', 'Inspection summary',
            'Created'
        );
        $_extract = array(
            'SiteInspection.id', 'SiteInspection.reference_no', 'Application.protocol_no', 'SiteInspection.pactr_no',
            'Application.study_title', 'Application.short_title', 'Application.single_site_member_state_f', 'Application.investigator1_given_name',
            'Application.investigator1_qualification', 'Application.investigator1_telephone', 'Application.investigator1_email', 'InvestigatorContact.0.given_name',
            'Application.InvestigatorContact.0.qualification', 'Application.InvestigatorContact.0.telephone', 'Application.InvestigatorContact.0.email', 'Application.Sponsor.0.sponsor', 'Application.Sponsor.0.cell_number', 'Application.Sponsor.0.email_address',
            'trial_phase', 'SiteInspection.User.name', 'SiteInspection.inspection_dates', 'SiteInspection.outcome', 'SiteInspection.conclusion', 'SiteInspection.summary_report',
            'SiteInspection.created'
        );

        $this->response->download('Site_Inspection_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('csiteInspections', '_serialize', '_header', '_extract'));
    }
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        $this->set('siteInspection', $this->SiteInspection->read(null, $id));
    }

    public function inspector_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array(
                'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                'AnnualApproval', 'Document', 'Review', 'SiteInspection', 'SiteInspection' => array('SiteAnswer')
                // => array('conditions' => array('Review.type' => 'response'))
            )
        ));
        $this->set('application', $application);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SiteInspection_' . $this->params['named']['inspection_id'],  'orientation' => 'portrait');
        }
    }

    public function manager_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array(
                'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                'AnnualApproval', 'Document', 'Review', 'SiteInspection', 'SiteInspection' => array('SiteAnswer')
                // => array('conditions' => array('Review.type' => 'response'))
            )
        ));
        $this->set('application', $application);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SiteInspection_' . $this->params['named']['inspection_id'],  'orientation' => 'portrait');
        }
    }
    /**
     * add method
     *
     * @return void
     */
    private function add($application_id = null)
    {
        $this->SiteInspection->create();
        // $application = $this->SiteInspection->Application->read(null, $application_id);
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id)
        ));
        $all_questions = $this->SiteQuestion->find('all');
        $answers = [];
        foreach ($all_questions as $question) {
            $dpoint = [
                'question_type' => $question['SiteQuestion']['question_type'], 'question_number' => $question['SiteQuestion']['question_number'],
                'question' => $question['SiteQuestion']['question']
            ];
            $answers[] = $dpoint;
        }
        $trial = "";
        $inspection_country = "";
        $investigators = "";
        $co_investigators = "";
        $trial .= ($application['Application']['trial_human_pharmacology'] == 1) ? "Human pharmacology (Phase I) : \n" : null;
        $trial .= ($application['Application']['trial_administration_humans'] == 1) ? "First administration to humans\n" : null;
        $trial .= ($application['Application']['trial_bioequivalence_study'] == 1) ? "Bioequivalence study\n" : null;
        $trial .= ($application['Application']['trial_other'] == 1) ? "Other : " . $application['Application']['trial_other_specify'] : null;
        $trial .= ($application['Application']['trial_therapeutic_exploratory'] == 1) ? "Therapeutic exploratory (Phase II)\n" : null;
        $trial .= ($application['Application']['trial_therapeutic_confirmatory'] == 1) ? "Therapeutic confirmatory (Phase III)\n" : null;
        $trial .= ($application['Application']['trial_therapeutic_use'] == 1) ? "Therapeutic use (Phase IV)\n" : null;
        $inspection_country .= ($application['Application']['single_site_member_state'] == 'Yes') ? "Kenya\n" : null;
        $inspection_country .= ($application['Application']['multiple_sites_member_state'] == 'Yes') ? "Kenya\n" : null;
        $inspection_country .= $application['Application']['multi_country_list'];
        $investigators = $application['Application']['investigator1_given_name'] . " " . $application['Application']['investigator1_family_name'] .
            "\nTel: " . $application['Application']['investigator1_telephone'] .
            "\nEmail: " . $application['Application']['investigator1_email'];
        foreach ($application['InvestigatorContact'] as $key => $value) {
            $co_investigators .= ($key + 1) . " " . $value['given_name'] . " " . $value['family_name'] . " Email: " . $value['email'] . "\n";
        }
        $count = $this->SiteInspection->find('count',  array('conditions' => array(
            'SiteInspection.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $data = array(
            'application_id' => $application_id, 'user_id' => $this->Auth->User('id'), 'study_title' => $application['Application']['study_title'],
            'protocol_no' => $application['Application']['protocol_no'],
            'reference_no' => 'SI/' . date('Y') . '/' . $count,
            'trial_phase' => $trial, 'investigators' => $investigators, 'co_investigators' => $co_investigators, 'inspection_country' => $inspection_country
        );
        if ($this->SiteInspection->saveAssociated(array('SiteInspection' => $data, 'SiteAnswer' => $answers))) {
            #Send notification and email
            $datum = array(
                'id' => $this->SiteInspection->id,
                'application_id' => $application['Application']['id'],
                'group_id' => $this->Auth->User('group_id'),
                'protocol_no' => ($application['Application']['protocol_no']) ? $application['Application']['protocol_no'] : $application['Application']['short_title'],
                'user_id' => $this->Auth->User('id')
            );

            //Audit trail
            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application['Application']['id'],
                    'model' => 'Application',
                    'message' => 'Site Inspection  has been created for report with protocol number ' .  $application['Application']['protocol_no'] . ' by ' . $this->Auth->User('username'),
                    'ip' =>  $application['Application']['protocol_no']
                )
            );
            $this->AuditTrail->Create();
            if ($this->AuditTrail->save($audit)) {
                $this->log($this->args[0], 'audit_success');
            } else {
                $this->log('Error creating an audit trail', 'notifications_error');
                $this->log($this->args[0], 'notifications_error');
            }



            CakeResque::enqueue('default', 'InspectionShell', array('newInspectionNotifyInspector', $datum));
            $this->Session->setFlash(__('The site inspection has been saved'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'inspection_id' => $this->SiteInspection->id));
        } else {
            $this->Session->setFlash(__('The site inspection could not be saved. Please, try again.'), 'alerts/flash_error');
        }
    }
    public function manager_add($application_id = null)
    {
        $this->add($application_id);
    }

    public function inspector_add($application_id = null)
    {
        $this->add($application_id);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    private function edit($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            // debug($this->request->data);
            // exit;
            if ($this->SiteInspection->saveMany($this->request->data['SiteInspection'], array('deep' => true))) {
                // if (isset($this->request->data['submitReport'])) {
                $this->SiteInspection->saveField('approved', 1);
                $results = Hash::extract($this->request->data['SiteInspection'], '{n}.SiteAnswer.{n}.finding');
                if (isset(array_count_values($results)['Major']) && array_count_values($results)['Major'] > 4 || array_count_values($results)['Critical'] > 0) {
                    $this->SiteInspection->saveField('conclusion', 'Site did not meet criteria!');
                    $this->SiteInspection->saveField('outcome', 'Failed Inspection');
                }
                $this->loadModel('AuditTrail');
                $audit = array(
                    'AuditTrail' => array(
                        'foreign_key' => $application_id,
                        'model' => 'Application',
                        'message' => 'Site Inspection  has been submitted for report with protocol number ' . $this->Application->field('protocol_no', array('id' => $application_id)) . ' by ' . $this->Auth->User('username'),
                        'ip' =>  $this->Application->field('protocol_no', array('id' => $application_id))
                    )
                );
                $this->AuditTrail->Create();
                if ($this->AuditTrail->save($audit)) {
                    $this->log($application_id, 'audit_success');
                } else {
                    $this->log('Error creating an audit trail', 'audit_error');
                    $this->log($application_id, 'audit_error');
                }


                // }
                $this->Session->setFlash(__('The site inspection has been saved'), 'alerts/flash_success');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'inspection_id' => $id));
            } else {
                $this->Session->setFlash(__('The site inspection could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $application_id),
                'contain' => array(
                    'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                    'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                    'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                    'AnnualApproval', 'Document', 'Review', 'SiteInspection' => array('SiteAnswer')
                )
            ));
            $this->request->data = $application;
        }
    }
    public function manager_edit($id = null, $application_id = null)
    {
        $this->edit($id, $application_id);
    }

    public function inspector_edit($id = null, $application_id = null)
    {
        $si = $this->SiteInspection->read(null, $id);
        if ($si['SiteInspection']['user_id'] != $this->Auth->User('id')) {
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'inspection_id' => $site_inspection['id']));
        }
        $this->edit($id, $application_id);
    }

    public function summary($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->SiteInspection->saveMany($this->request->data['SiteInspection'], array('deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->SiteInspection->saveField('summary_approved', 1);
                }
                $this->Session->setFlash(__('The site inspection has been saved'), 'alerts/flash_success');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'inspection_id' => $id));
            } else {
                $this->Session->setFlash(__('The site inspection could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'rreview_view' => $id));
        }
    }
    public function manager_summary($id = null, $application_id = null)
    {
        $this->summary($id, $application_id);
    }
    public function inspector_summary($id = null, $application_id = null)
    {
        $this->summary($id, $application_id);
    }

    public function send_to_pi($id = null)
    {
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        $si = $this->SiteInspection->read(null, $id);
        $this->SiteInspection->saveField('sent_to_pi', 1);

        //******************       Send Email and Notifications to Applicant and Managers          *****************************
        $this->loadModel('Message');
        $html = new HtmlHelper(new ThemeView());
        $message = $this->Message->find('first', array('conditions' => array('name' => 'manager_send_summary')));

        $users = $this->SiteInspection->User->find('all', array(
            'contain' => array(),
            'conditions' => array('OR' => array('User.id' => $si['SiteInspection']['user_id'], 'User.group_id' => array(2, 6)))
        ));
        foreach ($users as $user) {
            if ($user['User']['group_id'] == 2) $actioner =  'manager';
            if ($user['User']['group_id'] == 6) $actioner =  'inspector';
            if ($user['User']['group_id'] == 5) $actioner =  'applicant';

            $variables = array(
                'name' => $user['User']['name'],
                'protocol_no' => $si['Application']['protocol_no'],
                'reference_no' => $si['SiteInspection']['reference_no'],
                'outcome' => $si['SiteInspection']['outcome'],
                'conclusion' => $si['SiteInspection']['conclusion'],
                'summary_report' => $si['SiteInspection']['summary_report'],
                'reference_link' => $html->link(
                    $si['SiteInspection']['reference_no'],
                    array(
                        'controller' => 'applications', 'action' => 'view', $si['SiteInspection']['application_id'], $actioner => true,
                        'inspection_id' => $id,  'full_base' => true
                    ),
                    array('escape' => false)
                ),
            );
            $datum = array(
                'email' => $user['User']['email'],
                'id' => $si['SiteInspection']['id'], 'user_id' => $user['User']['id'], 'type' => 'manager_send_summary', 'model' => 'SiteInspection',
                'subject' => String::insert($message['Message']['subject'], $variables),
                'message' => String::insert($message['Message']['content'], $variables)
            );
            CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
            CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
        }
        //**********************************    END   *********************************
        $this->loadModel('AuditTrail');
        $this->loadModel('Application');
        $protocol = $this->Application->field('protocol_no', array('id' => $si['SiteInspection']['application_id']));
        $audit = array(
            'AuditTrail' => array(
                'foreign_key' => $si['SiteInspection']['application_id'],
                'model' => 'Application',
                'message' => 'Site Inspection summary for the protocol ' . $protocol . ' has been sent to the PI by ' . $this->Auth->user('username'),
                'ip' => $protocol
            )
        ); 
        $this->AuditTrail->Create();
        if ($this->AuditTrail->save($audit)) {
            $this->log($protocol, 'audit_success');
        } else {
            $this->log('Error creating an audit trail', 'audit_error');
            $this->log($protocol, 'audit_error');
        }
        $this->Session->setFlash(__('The site inspection summary has been sent to the PI'), 'alerts/flash_success');
        $this->redirect($this->referer());
    }
    public function manager_send_to_pi($id = null, $application_id = null)
    {
        $this->send_to_pi($id, $application_id);
    }
    public function inspector_send_to_pi($id = null, $application_id = null)
    {
        $this->send_to_pi($id, $application_id);
    }
    /**
     * approve method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    private function approve($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'), 'alerts/flash_error');
        }
        $site_inspection = $this->SiteInspection->read(null, $id);
        if ($this->SiteInspection->save(array('approved' => 2, 'summary_approved' => 2, 'approved_by' => $this->Session->read('Auth.User.id')))) {
            $this->Session->setFlash(__('Site inspection approved'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Site inspection was not approved'), 'alerts/flash_error');
        $this->redirect(array('controller' => 'site_inspections', 'action' => 'view', $site_inspection['application_id']));
    }
    public function manager_approve($id = null)
    {
        $this->approve($id);
    }
    public function inspector_approve($id = null)
    {
        $this->approve($id);
    }

    /**
     * download methods
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    private function download_assessment($id = null)
    {
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        $site_inspection = $this->SiteInspection->find(
            'first',
            array(
                'conditions' => array('SiteInspection.id' => $id),
                'contain' => array('SiteAnswer', 'Attachment')
            )
        );
        $disp  = $site_inspection['SiteInspection'];
        $disp['SiteAnswer'] = $site_inspection['SiteAnswer'];
        $disp['Attachment'] = $site_inspection['Attachment'];
        // $site_inspection  = $this->SiteInspection->read(null, $id);
        $this->set('site_inspection', $disp);
        $this->set('akey', $site_inspection['SiteInspection']['application_id']);
        $this->pdfConfig = array('filename' => 'Site_Inspection_Assessment_' . $id,  'orientation' => 'portrait');
        $this->render('download_assessment');
    }
    public function manager_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }
    public function inspector_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }

    private function download_summary($id = null)
    {
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        $site_inspection = $this->SiteInspection->find(
            'first',
            array(
                'conditions' => array('SiteInspection.id' => $id),
                'contain' => array('SiteAnswer', 'Attachment')
            )
        );
        $disp  = $site_inspection['SiteInspection'];
        $disp['SiteAnswer'] = $site_inspection['SiteAnswer'];
        $disp['Attachment'] = $site_inspection['Attachment'];
        // $site_inspection  = $this->SiteInspection->read(null, $id);
        $this->set('site_inspection', $disp);
        $this->set('akey', $site_inspection['SiteInspection']['application_id']);
        $this->pdfConfig = array('filename' => 'Site_Inspection_Summary_' . $id,  'orientation' => 'portrait');
        $this->render('download_summary');
    }
    public function manager_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function inspector_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function applicant_download_summary($id = null)
    {
        $this->download_summary($id);
    }

    private function download_inspection($id = null)
    {
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'));
        }
        $site_inspection = $this->SiteInspection->find(
            'first',
            array(
                'conditions' => array('SiteInspection.id' => $id),
                'contain' => array('SiteAnswer', 'Attachment')
            )
        );
        $disp  = $site_inspection['SiteInspection'];
        $disp['SiteAnswer'] = $site_inspection['SiteAnswer'];
        $disp['Attachment'] = $site_inspection['Attachment'];
        // $site_inspection  = $this->SiteInspection->read(null, $id);
        $this->set('site_inspection', $disp);
        $this->set('akey', $site_inspection['SiteInspection']['application_id']);
        $this->pdfConfig = array('filename' => 'Site_Inspection_' . $id,  'orientation' => 'portrait');
        $this->render('download_inspection');
    }
    public function manager_download_inspection($id = null)
    {
        $this->download_inspection($id);
    }
    public function inspector_download_inspection($id = null)
    {
        $this->download_inspection($id);
    }
    public function applicant_download_inspection($id = null)
    {
        $this->download_summary($id);
    }
    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->SiteInspection->id = $id;
        if (!$this->SiteInspection->exists()) {
            throw new NotFoundException(__('Invalid site inspection'), 'alerts/flash_error');
        }
        if ($this->SiteInspection->delete()) {
            $this->Session->setFlash(__('Site inspection deleted'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
        // $this->Session->setFlash(__('Site inspection was not deleted'), 'alerts/flash_error');
        // $this->redirect('/');
        $this->redirect($this->referer());
    }
    public function manager_delete($id = null)
    {
        $this->delete($id);
    }
    public function inspector_delete($id = null)
    {
        $this->delete($id);
    }
}

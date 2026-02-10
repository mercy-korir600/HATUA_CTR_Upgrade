<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Deviations Controller
 *
 * @property Deviation $Deviation
 */
class DeviationsController extends AppController
{

    public $paginate = array();
    public $presetVars = true; // using the model configuration
    public $uses = array('Deviation', 'Application');
    public $components = array('Search.Prg');
    /**
     * index method
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('inspector_add','outsource_edit', 'inspector_edit', 'inspector_download_deviation');
    }




    public function applicant_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Deviation->parseCriteria($this->passedArgs);
        $criteria['Deviation.user_id'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Deviation.created' => 'desc');
        $this->paginate['contain'] = array('Application');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Deviation->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('deviations', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function monitor_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Deviation->parseCriteria($this->passedArgs);
        // $criteria['Deviation.user_id'] = array($this->Auth->User('id'), $this->Auth->User('sponsor'));
        $sars = $this->Application->StudyMonitor->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('StudyMonitor.user_id' => $this->Auth->User('id'))));
        $criteria['Deviation.application_id'] = $sars;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Deviation.created' => 'desc');
        $this->paginate['contain'] = array('Application');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Deviation->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('deviations', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function outsource_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Deviation->parseCriteria($this->passedArgs);
        // $criteria['Deviation.user_id'] = array($this->Auth->User('id'), $this->Auth->User('sponsor'));
        $sars = $this->Application->ProtocolOutsource->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('ProtocolOutsource.user_id' => $this->Auth->User('id'))));
        $criteria['Deviation.application_id'] = $sars;
        $criteria['Deviation.user_id']=$this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Deviation.created' => 'desc');
        $this->paginate['contain'] = array('Application');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Deviation->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('deviations', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function index()
    {
        // $this->Deviation->recursive = 0;
        // $this->set('deviations', $this->paginate());

        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Deviation->parseCriteria($this->passedArgs);
        // $criteria['Deviation.status'] = 'Submitted';
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Deviation.created' => 'desc');
        $this->paginate['contain'] = array('Application');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Deviation->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('deviations', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function manager_index()
    {
        $this->index();
    }
    public function inspector_index()
    {
        $this->index();
    }

    private function csv_export($pdeviations = '')
    {
        $_serialize = 'pdeviations';
        $_header = array(
            '#', 'Protocol No', 'Reference No', 'Deviation Type', 'PI Name', 'Date of deviation',
            'Study participant number', 'Treating physician', 'Description of deviation', 'Explanation', 'Measures taken',
            'Measure to preclude', 'Sponsor notified', 'Impact on study', 'Created date'
        );
        $_extract = array(
            'Deviation.id', 'Application.protocol_no', 'Deviation.reference_no', 'Deviation.deviation_type', 'Deviation.pi_name', 'Deviation.deviation_date',
            'Deviation.participant_number', 'Deviation.treating_physician', 'Deviation.deviation_description', 'Deviation.deviation_explanation', 'Deviation.deviation_measures',
            'Deviation.deviation_preclude', 'Deviation.sponsor_notified', 'Deviation.study_impact', 'Deviation.created'
        );

        $this->response->download('Deviations_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('pdeviations', '_serialize', '_header', '_extract'));
    }

    private function normalizeSponsorNotificationDates($deviationRows = array())
    {
        if (empty($deviationRows) || !is_array($deviationRows)) {
            return $deviationRows;
        }

        foreach ($deviationRows as $idx => $row) {
            if (!is_array($row)) {
                continue;
            }

            $notified = !empty($row['sponsor_notified']);

            if (!$notified) {
                $deviationRows[$idx]['sponsor_notification_date'] = '';
                continue;
            }

            if (!array_key_exists('sponsor_notification_date', $row)) {
                continue;
            }

            $value = trim((string)$row['sponsor_notification_date']);
            if ($value === '') {
                continue;
            }

            $normalized = '';
            $formats = array('d-m-Y', 'Y-m-d', 'm/d/Y', 'd/m/Y');
            foreach ($formats as $format) {
                $dt = DateTime::createFromFormat('!' . $format, $value);
                if ($dt && $dt->format($format) === $value) {
                    $normalized = $dt->format('m/d/Y');
                    break;
                }
            }

            if ($normalized !== '') {
                $deviationRows[$idx]['sponsor_notification_date'] = $normalized;
            }
        }

        return $deviationRows;
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
        // $this->Deviation->id = $id;
        // if (!$this->Deviation->exists()) {
        //     throw new NotFoundException(__('Invalid deviation'));
        // }
        // $this->set('deviation', $this->Deviation->read(null, $id));
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        $deviation = $this->Deviation->read(null, $id);
        if ($deviation['Deviation']['status'] !== 'Submitted') {
            $this->Session->setFlash(__('The deviation has not been submitted'), 'alerts/flash_info');
        }

        $this->set('deviation', $this->Deviation->find(
            'first',
            array(
                'contain' => array('Application', 'ExternalComment' => array('Attachment')),
                'conditions' => array('Deviation.id' => $id)
            )
        ));
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'DEV_' . $id,  'orientation' => 'portrait');
        }
    }
    public function manager_view($id = null)
    {
        $this->view($id);
    }
    public function inspector_view($id = null)
    {
        $this->view($id);
    }

    /**
     * download methods
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function download_deviation($id = null)
    {
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid protocol deviation'));
        }
        $deviation = $this->Deviation->find(
            'first',
            array(
                'conditions' => array('Deviation.id' => $id),
                'contain' => array('Attachment')
            )
        );
        $disp  = $deviation['Deviation'];
        $disp['Attachment'] = $deviation['Attachment'];
        // $deviation  = $this->Deviation->read(null, $id);
        $this->set('deviation', $disp);
        $this->set('akey', $deviation['Deviation']['application_id']);
        $this->pdfConfig = array('filename' => 'Protocol_Deviation_' . $id,  'orientation' => 'portrait');
        $this->render('download_deviation');
    }
    public function applicant_download_deviation($id = null)
    {
        $this->download_deviation($id);
    }
    public function monitor_download_deviation($id = null)
    {
        $this->download_deviation($id);
    }
    public function outsource_download_deviation($id = null)
    {
        $this->download_deviation($id);
    }
    public function manager_download_deviation($id = null)
    {
        $this->download_deviation($id);
    }
    public function inspector_download_deviation($id = null)
    {
        $this->download_deviation($id);
    }

    /**
     * add method
     *
     * @return void
     */
    public function applicant_add($application_id = null)
    {
        $this->Deviation->create();
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('InvestigatorContact')
        ));
        $count = $this->Deviation->find('count',  array('conditions' => array(
            'Deviation.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
        $piName = $this->getInvestigatorFullName($principalInvestigator);
        $data = array(
            'application_id' => $application_id,  'study_title' => $application['Application']['study_title'], 'user_id' => $this->Auth->User('id'),
            'reference_no' => 'DEV/' . date('Y') . '/' . $count,
            'pi_name' => $piName
        );
        if ($this->Deviation->saveAssociated(array('Deviation' => $data))) {
            $this->Session->setFlash(__('The protocol deviation has been created. Kindly update the details and submit!!'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $this->Deviation->id));
        } else {
            $this->Session->setFlash(__('The protocol deviation could not be created. Please notify the administrator.'), 'alerts/flash_error');
        }
    }
    public function monitor_add($application_id = null)
    {
        $this->Deviation->create();
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('InvestigatorContact')
        ));
        $count = $this->Deviation->find('count',  array('conditions' => array(
            'Deviation.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
        $piName = $this->getInvestigatorFullName($principalInvestigator);
        $data = array(
            'application_id' => $application_id,  'study_title' => $application['Application']['study_title'], 'user_id' => $this->Auth->User('id'),
            'reference_no' => 'DEV/' . date('Y') . '/' . $count,
            'pi_name' => $piName
        );
        if ($this->Deviation->saveAssociated(array('Deviation' => $data))) {
            $this->Session->setFlash(__('The protocol deviation has been created. Kindly update the details and submit!!'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $this->Deviation->id));
        } else {
            $this->Session->setFlash(__('The protocol deviation could not be created. Please notify the administrator.'), 'alerts/flash_error');
        }
    }
    public function outsource_add($application_id = null)
    {
        $this->Deviation->create();
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('InvestigatorContact')
        ));
        $count = $this->Deviation->find('count',  array('conditions' => array(
            'Deviation.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
        $piName = $this->getInvestigatorFullName($principalInvestigator);
        $data = array(
            'application_id' => $application_id,
            'study_title' => $application['Application']['study_title'],
            'user_id' => $this->Auth->User('id'),
            'reference_no' => 'DEV/' . date('Y') . '/' . $count,
            'pi_name' => $piName
        );
        if ($this->Deviation->saveAssociated(array('Deviation' => $data))) {
            $this->Session->setFlash(__('The protocol deviation has been created. Kindly update the details and submit!!'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $this->Deviation->id));
        } else {
            $this->Session->setFlash(__('The protocol deviation could not be created. Please notify the administrator.'), 'alerts/flash_error');
        }
    }
    public function inspector_add($application_id = null)
    {
        $this->Deviation->create();
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('InvestigatorContact')
        ));
        $count = $this->Deviation->find('count',  array('conditions' => array(
            'Deviation.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
        $piName = $this->getInvestigatorFullName($principalInvestigator);
        $data = array(
            'application_id' => $application_id,  'study_title' => $application['Application']['study_title'], 'user_id' => $this->Auth->User('id'),
            'reference_no' => 'DEV/' . date('Y') . '/' . $count,
            'pi_name' => $piName
        );
        if ($this->Deviation->saveAssociated(array('Deviation' => $data))) {
            $this->Session->setFlash(__('The protocol deviation has been created. Kindly update the details and submit!!'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $this->Deviation->id));
        } else {
            $this->Session->setFlash(__('The protocol deviation could not be created. Please notify the administrator.'), 'alerts/flash_error');
        }
    }


    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function applicant_edit($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Deviation']) && is_array($this->request->data['Deviation'])) {
                $this->request->data['Deviation'] = $this->normalizeSponsorNotificationDates($this->request->data['Deviation']);
            }
            if ($this->Deviation->saveMany($this->request->data['Deviation'], array('deep' => true))) {
                // debug($this->request->data);
                if (isset($this->request->data['submitReport'])) {
                    $this->Deviation->saveField('status', 'Submitted');
                    $this->Session->setFlash(__('The protocol deviation has been submitted!!'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_view' => $id));
                } else {
                    $this->Session->setFlash(__('The protocol deviation has been saved. Please submit when complete!!'), 'alerts/flash_info');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $id));
                }
            } else {
                $this->Session->setFlash(__('The protocol deviation could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $application_id),
                'contain' => array(
                    'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                    'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                    'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                    'AnnualApproval', 'Document', 'Review', 'Deviation'
                )
            ));
            $this->request->data = $application;
        }
    }
 

        public function outsource_edit($id = null, $application_id = null)
        {
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Deviation']) && is_array($this->request->data['Deviation'])) {
                $this->request->data['Deviation'] = $this->normalizeSponsorNotificationDates($this->request->data['Deviation']);
            }
            if ($this->Deviation->saveMany($this->request->data['Deviation'], array('deep' => true))) {
                // debug($this->request->data);
                if (isset($this->request->data['submitReport'])) {
                    $this->Deviation->saveField('status', 'Submitted');
                    $this->Session->setFlash(__('The protocol deviation has been submitted'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_view' => $id));
                } else {
                    $this->Session->setFlash(__('The protocol deviation has been saved'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $id));
                }
            } else {
                $this->Session->setFlash(__('The protocol deviation could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $application_id),
                'contain' => array(
                    'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                    'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                    'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                    'AnnualApproval', 'Document', 'Review', 'Deviation'
                )
            ));
            $this->request->data = $application;
        }
	}
    public function monitor_edit($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Deviation']) && is_array($this->request->data['Deviation'])) {
                $this->request->data['Deviation'] = $this->normalizeSponsorNotificationDates($this->request->data['Deviation']);
            }
            if ($this->Deviation->saveMany($this->request->data['Deviation'], array('deep' => true))) {
                // debug($this->request->data);
                if (isset($this->request->data['submitReport'])) {
                    $this->Deviation->saveField('status', 'Submitted');
                    $this->Session->setFlash(__('The protocol deviation has been submitted'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_view' => $id));
                } else {
                    $this->Session->setFlash(__('The protocol deviation has been saved'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $id));
                }
            } else {
                $this->Session->setFlash(__('The protocol deviation could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $application_id),
                'contain' => array(
                    'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                    'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                    'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                    'AnnualApproval', 'Document', 'Review', 'Deviation'
                )
            ));
            $this->request->data = $application;
        }
    }
    public function inspector_edit($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!empty($this->request->data['Deviation']) && is_array($this->request->data['Deviation'])) {
                $this->request->data['Deviation'] = $this->normalizeSponsorNotificationDates($this->request->data['Deviation']);
            }
            if ($this->Deviation->saveMany($this->request->data['Deviation'], array('deep' => true))) {
                // debug($this->request->data);
                if (isset($this->request->data['submitReport'])) {
                    $this->Deviation->saveField('status', 'Submitted');
                    $this->Session->setFlash(__('The protocol deviation has been submitted'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_view' => $id));
                } else {
                    $this->Session->setFlash(__('The protocol deviation has been saved'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'deviation_edit' => $id));
                }
            } else {
                $this->Session->setFlash(__('The protocol deviation could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $application_id),
                'contain' => array(
                    'Amendment', 'PreviousDate', 'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo',
                    'Attachment', 'CoverLetter', 'Protocol', 'PatientLeaflet', 'Brochure', 'GmpCertificate', 'Cv', 'Finance', 'Declaration',
                    'IndemnityCover', 'OpinionLetter', 'ApprovalLetter', 'Statement', 'ParticipatingStudy', 'Addendum', 'Registration', 'Fee',
                    'AnnualApproval', 'Document', 'Review', 'Deviation'
                )
            ));
            $this->request->data = $application;
        }
    }

    public function manager_unsubmit($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid protocol deviation'), 'alerts/flash_error');
        }
        $deviation = $this->Deviation->read(null, $id);
        if ($this->Deviation->saveField('status', 'Unsubmitted')) {
            $this->Session->setFlash(__('Protocol deviation unsubmitted'), 'alerts/flash_success');
            //TODO: notify applicant the protocol deviation can be edited
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Site inspection was not unsubmitted'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }
    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function applicant_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Deviation->id = $id;
        $deviation = $this->Deviation->read(null, $id);
        if ($deviation['Deviation']['user_id'] != $this->Auth->User('id') && !$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'), 'alerts/flash_error');
        }
        if ($this->Deviation->delete()) {
            $this->Session->setFlash(__('Deviation deleted'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Deviation was not deleted'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }

    public function monitor_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Deviation->id = $id;
        $deviation = $this->Deviation->read(null, $id);
        if ($deviation['Deviation']['user_id'] != $this->Auth->User('id') && !$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'), 'alerts/flash_error');
        }
        if ($this->Deviation->delete()) {
            $this->Session->setFlash(__('Deviation deleted'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
        $this->Session->setFlash(__('Deviation was not deleted'), 'alerts/flash_error');
        $this->redirect($this->referer());
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Deviation->id = $id;
        if (!$this->Deviation->exists()) {
            throw new NotFoundException(__('Invalid deviation'));
        }
        if ($this->Deviation->delete()) {
            $this->Session->setFlash(__('Deviation deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Deviation was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

<?php
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

/**
 * Saes Controller
 *
 * @property Sae $Sae
 */
class SaesController extends AppController
{
    public $paginate = array();
    public $uses = array('Sae', 'Application');
    public $components = array('Search.Prg');
    public $presetVars = true; // using the model configuration

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('fetch', 'submit');
    }
    /**
     * index method
     *
     * @return void
     */
    public function applicant_index()
    {
        // $this->Sae->recursive = 0;
        // $this->paginate['contain'] = array('Application', 'Country');
        // $this->paginate['conditions'] = array('Sae.user_id' => $this->Auth->User('id'));
        // $this->set('saes', $this->paginate());

        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Sae->parseCriteria($this->passedArgs);
        $criteria['Sae.user_id'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sae.created' => 'desc');
        $this->paginate['contain'] = array('Application', 'Country', 'SuspectedDrug', 'ConcomittantDrug');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sae->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('countries'));
        $this->set('page_options', $page_options);
        $this->set('saes', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function monitor_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Sae->parseCriteria($this->passedArgs);
        $sars = $this->Application->StudyMonitor->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('StudyMonitor.user_id' => $this->Auth->User('id'))));
        $criteria['Sae.application_id'] = $sars;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sae.created' => 'desc');
        $this->paginate['contain'] = array('Application', 'Country', 'SuspectedDrug', 'ConcomittantDrug');

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sae->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('countries'));
        $this->set('page_options', $page_options);
        $this->set('saes', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function outsource_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Sae->parseCriteria($this->passedArgs);
        $sars = $this->Application->ProtocolOutsource->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('ProtocolOutsource.user_id' => $this->Auth->User('id'))));
        $criteria['Sae.application_id'] = $sars;
        $criteria['Sae.user_id']=$this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sae.created' => 'desc');
        $this->paginate['contain'] = array('Application', 'Country', 'SuspectedDrug', 'ConcomittantDrug');
        

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sae->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('countries'));
        $this->set('page_options', $page_options);
        $this->set('saes', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function index()
    {
        // $this->Sae->recursive = 0;
        // $this->paginate['contain'] = array('Application', 'Country');
        // $this->paginate['conditions'] = array('Sae.approved' => array(1, 2));
        // $this->set('saes', $this->paginate());

        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Sae->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['approved'])) $criteria['Sae.approved'] = array(0, 1, 2);
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Sae.created' => 'desc');
        $this->paginate['contain'] = array('Application', 'Country', 'SuspectedDrug', 'ConcomittantDrug');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Sae->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
        }
        //end pdf export

        $countries = $this->Sae->Country->find('list');
        $this->set(compact('countries'));
        $this->set('page_options', $page_options);
        $this->set('saes', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function manager_index()
    {
        $this->index();
    }
    public function inspector_index()
    {
        $this->index();
    }


    /*private function csv_export($saes = '') {
        //todo: check if data exists in $saes
        $_serialize = 'saes';
        $_header = array('Reference No.', 'Protocol No', 'Patient Initials', 'Date of birth', 'Created');
        $_extract = array('Sae.reference_no' , 'Application.protocol_no', 'Sae.patient_initials', 'Sae.date_of_birth', 'Sae.created');

        $this->response->download('SAEs_'.date('Ymd_Hi').'.csv'); // <= setting the file name
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('saes', '_serialize', '_header', '_extract'));
    }*/
    private function csv_export($csaes = '')
    {
        $this->response->download('SAEs_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('csaes'));
        $this->layout = false;
        $this->render('csv_export');
    }

    /*public function fetch($id = null){
        $this->layout = false;
        //set default response
        $response = array('status'=>'failed', 'message'=>'Failed to process request');
        
        //check if ID was passed
        if(!empty($id)){
            
            //find data by ID
            $result = $this->Sae->find('all', array('contain' => array('SuspectedDrug', 'ConcomittantDrug'), 'conditions' => array('Sae.id >' => $id, 'Sae.approved >' => 0)));
            if(!empty($result)){
                $response = array('status'=>'success','data'=>$result);  
            } else {
                $response['message'] = 'Found no matching data';
            }  
        } else {
            $response['message'] = "Please provide ID";
        }
            
        $this->response->type('application/json');
        $this->response->body(json_encode($response));
        $this->autoRender = false ;
        return $this->response->send();
    }*/
    public function fetch($id = null)
    {
        // $this->layout = false;
        //set default response
        $response = array('status' => 'failed', 'message' => 'Failed to process request');

        //check if ID was passed
        if (!empty($id)) {

            //find data by ID
            $result = $this->Sae->find('all', array('contain' => array('SuspectedDrug', 'ConcomittantDrug'), 'conditions' => array('Sae.id >' => $id, 'Sae.approved >' => 0, 'NOT' => array('Sae.causality' => array('Unlikely', 'Not related')))));
            if (!empty($result)) {
                $response = array('status' => 'success', 'data' => $result);
            } else {
                $response['message'] = 'Found no matching data';
            }
        } else {
            $response['message'] = "Please provide ID";
        }

        $this->set(compact('response'));
        $this->set('_serialize', array('response'));
        // $this->response->type('application/json');
        // $this->response->body(json_encode($response));
        // $this->autoRender = false ;
        // return $this->response->send();
    }

    public function submit()
    {
        if (!$this->request->is('post')) {
            return $this->_jsonErrorResponse(405, 'Only POST requests are allowed for SAE submission.');
        }

        $user = $this->_getAuthenticatedApiUser();
        if (empty($user['id'])) {
            $this->_logSaeApiAttempt('failed', 'Unauthenticated SAE submission attempt.', array(
                'status_code' => 401
            ));

            return $this->_jsonErrorResponse(401, 'Authentication required. Provide a valid Bearer token in the Authorization header or an access_token in the request.');
        }

        $rawBody = trim((string)$this->request->input());
        if ($rawBody === '') {
            $this->_logSaeApiAttempt('failed', 'Empty SAE submission payload.', array(
                'status_code' => 400,
                'user_id' => $user['id']
            ));

            return $this->_jsonErrorResponse(400, 'The JSON request body is required.');
        }

        $payload = json_decode($rawBody, true);
        if (!is_array($payload)) {
            $this->_logSaeApiAttempt('failed', 'Invalid SAE submission payload.', array(
                'status_code' => 400,
                'user_id' => $user['id']
            ));

            return $this->_jsonErrorResponse(400, 'The JSON request body is invalid.');
        }

        $data = $this->_normalizeSubmissionPayload($payload, $user);
        $applicationResolution = $this->_resolveSubmissionApplication($data, $user);
        if (empty($applicationResolution['application'])) {
            $statusCode = !empty($applicationResolution['status_code']) ? (int)$applicationResolution['status_code'] : 422;
            $message = !empty($applicationResolution['message']) ? $applicationResolution['message'] : 'Please correct the highlighted errors.';
            $errors = !empty($applicationResolution['errors']) ? $applicationResolution['errors'] : array(
                'reference_no' => array('Please provide a valid application reference number.')
            );

            $this->_logSaeApiAttempt('failed', 'SAE submission application resolution failed.', array(
                'status_code' => $statusCode,
                'user_id' => $user['id'],
                'application_id' => isset($data['Sae']['application_id']) ? $data['Sae']['application_id'] : null,
                'application_reference_no' => $this->_extractApplicationReference($data),
                'errors' => array_keys($errors)
            ));

            return $this->_jsonErrorResponse($statusCode, $message, $errors);
        }

        $application = $applicationResolution['application'];
        $data = $this->_applyApplicationContext($data, $application);
        $validationErrors = $this->_validateSubmissionPayload($data);

        if (!empty($validationErrors)) {
            $this->_logSaeApiAttempt('failed', 'SAE submission validation failed.', array(
                'status_code' => 422,
                'user_id' => $user['id'],
                'application_id' => isset($data['Sae']['application_id']) ? $data['Sae']['application_id'] : null,
                'application_reference_no' => $this->_extractApplicationReference($data),
                'errors' => array_keys($validationErrors)
            ));

            return $this->_jsonErrorResponse(422, 'Please correct the highlighted errors.', $validationErrors);
        }

        $duplicate = $this->_findDuplicateSubmittedSae($data, $user);
        if (!empty($duplicate)) {
            $message = 'A matching SAE has already been submitted.';
            $this->_logSaeApiAttempt('failed', 'Duplicate SAE submission detected.', array(
                'status_code' => 409,
                'user_id' => $user['id'],
                'application_id' => $data['Sae']['application_id'],
                'duplicate_sae_id' => $duplicate['Sae']['id'],
                'duplicate_reference_no' => $duplicate['Sae']['reference_no']
            ));

            return $this->_jsonErrorResponse(409, $message, array(
                'duplicate' => array($message . ' Existing reference: ' . $duplicate['Sae']['reference_no'])
            ));
        }

        $submittedAt = date('Y-m-d H:i:s');
        $data['Sae']['reference_no'] = $this->Sae->generateReferenceNumber($data['Sae']['form_type'], $submittedAt);
        $data['Sae']['approved'] = 1;
        $data['Sae']['date_submitted'] = $submittedAt;
        unset($data['Sae']['application_reference_no'], $data['Sae']['protocol_no']);

        $dataSource = $this->Sae->getDataSource();
        $dataSource->begin();

        try {
            $this->Sae->create();
            if (!$this->Sae->saveAssociated($data, array('validate' => false, 'deep' => true))) {
                $dataSource->rollback();
                $this->_logSaeApiAttempt('failed', 'SAE submission could not be persisted.', array(
                    'status_code' => 500,
                    'user_id' => $user['id'],
                    'application_id' => $data['Sae']['application_id'],
                    'reference_no' => $data['Sae']['reference_no']
                ));

                return $this->_jsonErrorResponse(500, 'The SAE could not be submitted. Please try again.');
            }

            $saeId = $this->Sae->id;
            $dataSource->commit();
        } catch (Exception $exception) {
            $dataSource->rollback();
            $this->_logSaeApiAttempt('failed', 'SAE submission raised an exception.', array(
                'status_code' => 500,
                'user_id' => $user['id'],
                'application_id' => $data['Sae']['application_id'],
                'reference_no' => $data['Sae']['reference_no'],
                'exception' => $exception->getMessage()
            ));

            return $this->_jsonErrorResponse(500, 'The SAE could not be submitted. Please try again.');
        }

        $sae = $this->Sae->find('first', array(
            'contain' => array('Application'),
            'conditions' => array('Sae.id' => $saeId)
        ));

        $this->_queueSaeSubmissionNotifications($sae, $user);
        $this->_logSaeApiAttempt('success', 'SAE submitted successfully.', array(
            'status_code' => 200,
            'user_id' => $user['id'],
            'application_id' => $data['Sae']['application_id'],
            'sae_id' => $saeId,
            'reference_no' => $data['Sae']['reference_no']
        ));

        return $this->_jsonSuccessResponse('SAE submitted successfully', array(
            'sae_id' => $saeId,
            'submitted_at' => $submittedAt
        ));
    }

    private function _normalizeSubmissionPayload($payload = array(), $user = array())
    {
        $data = array(
            'Sae' => array(),
            'SuspectedDrug' => array(),
            'ConcomittantDrug' => array(),
        );

        if (isset($payload['Sae']) || isset($payload['SuspectedDrug']) || isset($payload['ConcomittantDrug'])) {
            $data['Sae'] = (!empty($payload['Sae']) && is_array($payload['Sae'])) ? $payload['Sae'] : array();
            $data['SuspectedDrug'] = (!empty($payload['SuspectedDrug']) && is_array($payload['SuspectedDrug'])) ? $payload['SuspectedDrug'] : array();
            $data['ConcomittantDrug'] = (!empty($payload['ConcomittantDrug']) && is_array($payload['ConcomittantDrug'])) ? $payload['ConcomittantDrug'] : array();

            foreach (array('reference_no', 'protocol_no', 'application_reference_no') as $referenceField) {
                if (empty($data['Sae'][$referenceField]) && array_key_exists($referenceField, $payload)) {
                    $data['Sae'][$referenceField] = $payload[$referenceField];
                }
            }
        } else {
            $saeFields = array(
                'application_id',
                'reference_no',
                'protocol_no',
                'application_reference_no',
                'patient_initials',
                'country_id',
                'date_of_birth',
                'age_years',
                'enrollment_date',
                'administration_date',
                'latest_date',
                'reaction_onset',
                'reaction_end_date',
                'patient_died',
                'prolonged_hospitalization',
                'incapacity',
                'life_threatening',
                'reaction_other',
                'gender',
                'causality',
                'reaction_description',
                'relevant_history',
                'manufacturer_name',
                'mfr_no',
                'manufacturer_date',
                'source_study',
                'source_literature',
                'source_health_professional',
                'reporter_name',
                'reporter_phone',
                'reporter_email',
                'email_address',
                'form_type',
            );

            foreach ($saeFields as $field) {
                if (array_key_exists($field, $payload)) {
                    $data['Sae'][$field] = $payload[$field];
                }
            }

            if (!empty($payload['suspected_drugs']) && is_array($payload['suspected_drugs'])) {
                $data['SuspectedDrug'] = $payload['suspected_drugs'];
            }

            if (!empty($payload['concomittant_drugs']) && is_array($payload['concomittant_drugs'])) {
                $data['ConcomittantDrug'] = $payload['concomittant_drugs'];
            } elseif (!empty($payload['concomitant_drugs']) && is_array($payload['concomitant_drugs'])) {
                $data['ConcomittantDrug'] = $payload['concomitant_drugs'];
            }
        }

        foreach ($data['Sae'] as $field => $value) {
            if (is_string($value)) {
                $data['Sae'][$field] = trim($value);
            }
        }

        if (empty($data['Sae']['application_reference_no']) && !empty($data['Sae']['reference_no'])) {
            $data['Sae']['application_reference_no'] = $data['Sae']['reference_no'];
        }

        if (empty($data['Sae']['application_reference_no']) && !empty($data['Sae']['protocol_no'])) {
            $data['Sae']['application_reference_no'] = $data['Sae']['protocol_no'];
        }

        if (isset($data['Sae']['application_id']) && trim((string)$data['Sae']['application_id']) === '') {
            unset($data['Sae']['application_id']);
        }

        unset(
            $data['Sae']['id'],
            $data['Sae']['reference_no'],
            $data['Sae']['approved'],
            $data['Sae']['approved_by'],
            $data['Sae']['date_submitted'],
            $data['Sae']['user_id']
        );

        $data['Sae']['form_type'] = !empty($data['Sae']['form_type']) ? strtoupper($data['Sae']['form_type']) : 'SAE';
        $data['Sae']['user_id'] = $user['id'];

        if (empty($data['Sae']['reporter_email']) && !empty($user['email'])) {
            $data['Sae']['reporter_email'] = $user['email'];
        }

        if (empty($data['Sae']['email_address']) && !empty($data['Sae']['reporter_email'])) {
            $data['Sae']['email_address'] = $data['Sae']['reporter_email'];
        }

        foreach (array(
            'patient_died',
            'prolonged_hospitalization',
            'incapacity',
            'life_threatening',
            'reaction_other',
            'source_study',
            'source_literature',
            'source_health_professional',
        ) as $booleanField) {
            if (array_key_exists($booleanField, $data['Sae'])) {
                $data['Sae'][$booleanField] = $this->_normalizeBooleanValue($data['Sae'][$booleanField]);
            }
        }

        $data['SuspectedDrug'] = $this->_normalizeAssociatedRows($data['SuspectedDrug']);
        $data['ConcomittantDrug'] = $this->_normalizeAssociatedRows($data['ConcomittantDrug']);

        return $data;
    }

    private function _normalizeAssociatedRows($rows = array())
    {
        $normalized = array();

        if (!is_array($rows)) {
            return $normalized;
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            unset($row['id'], $row['sae_id'], $row['created'], $row['modified']);

            foreach ($row as $field => $value) {
                if (is_string($value)) {
                    $row[$field] = trim($value);
                }
            }

            if ($this->_associatedRowHasData($row)) {
                $normalized[] = $row;
            }
        }

        return array_values($normalized);
    }

    private function _associatedRowHasData($row = array())
    {
        foreach ($row as $field => $value) {
            if (in_array($field, array('deleted', 'deleted_date'))) {
                continue;
            }

            if ($value === null || $value === '' || $value === false) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function _normalizeBooleanValue($value)
    {
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        if (is_numeric($value)) {
            return ((int)$value === 1) ? 1 : 0;
        }

        $normalized = strtolower(trim((string)$value));

        if (in_array($normalized, array('1', 'true', 'yes', 'on'))) {
            return 1;
        }

        return 0;
    }

    private function _validateSubmissionPayload($data = array())
    {
        $errors = array();

        if (empty($data['SuspectedDrug'])) {
            $errors['suspected_drugs'] = array('Please provide at least one suspected drug.');
        }

        $this->Sae->create();
        $this->Sae->set($data);
        if (!$this->Sae->validates()) {
            $errors = array_merge($errors, $this->_formatValidationErrors($this->Sae->validationErrors));
        }

        foreach ($data['SuspectedDrug'] as $index => $suspectedDrug) {
            $this->Sae->SuspectedDrug->create();
            $this->Sae->SuspectedDrug->set(array('SuspectedDrug' => $suspectedDrug));
            if (!$this->Sae->SuspectedDrug->validates()) {
                $errors = array_merge($errors, $this->_formatValidationErrors(
                    $this->Sae->SuspectedDrug->validationErrors,
                    'suspected_drugs.' . $index . '.'
                ));
            }
        }

        foreach ($data['ConcomittantDrug'] as $index => $concomittantDrug) {
            $this->Sae->ConcomittantDrug->create();
            $this->Sae->ConcomittantDrug->set(array('ConcomittantDrug' => $concomittantDrug));
            if (!$this->Sae->ConcomittantDrug->validates()) {
                $errors = array_merge($errors, $this->_formatValidationErrors(
                    $this->Sae->ConcomittantDrug->validationErrors,
                    'concomittant_drugs.' . $index . '.'
                ));
            }
        }

        return $errors;
    }

    private function _resolveSubmissionApplication($data = array(), $user = array())
    {
        $referenceNo = $this->_extractApplicationReference($data);

        if ($referenceNo !== '') {
            $application = $this->_findApplicationByReference($referenceNo);
            if (empty($application)) {
                return array(
                    'status_code' => 422,
                    'message' => 'Please correct the highlighted errors.',
                    'errors' => array(
                        'reference_no' => array('The supplied application reference number was not found.')
                    )
                );
            }

            $accessibleApplication = $this->_findAccessibleSubmissionApplication($application['Application']['id']);
            if (!empty($accessibleApplication)) {
                return array('application' => $accessibleApplication);
            }

            return array(
                'status_code' => 422,
                'message' => 'Please correct the highlighted errors.',
                'errors' => array(
                    'reference_no' => array('The supplied application reference number is not eligible for SAE submission.')
                )
            );
        }

        if (!empty($data['Sae']['application_id'])) {
            $application = $this->_findAccessibleSubmissionApplication($data['Sae']['application_id']);
            if (!empty($application)) {
                return array('application' => $application);
            }

            return array(
                'status_code' => 422,
                'message' => 'Please correct the highlighted errors.',
                'errors' => array(
                    'application_id' => array('The supplied application could not be found or is not eligible for SAE submission.')
                )
            );
        }

        return array(
            'status_code' => 422,
            'message' => 'Please correct the highlighted errors.',
            'errors' => array(
                'reference_no' => array('Please provide the application reference number.')
            )
        );
    }

    private function _formatValidationErrors($validationErrors = array(), $prefix = '')
    {
        $errors = array();

        foreach ($validationErrors as $field => $messages) {
            if (!is_array($messages)) {
                $messages = array($messages);
            }

            $messages = array_values(array_unique(array_filter($messages)));
            if (empty($messages)) {
                continue;
            }

            $errors[$prefix . $field] = $messages;
        }

        return $errors;
    }

    private function _extractApplicationReference($data = array())
    {
        if (!empty($data['Sae']['application_reference_no'])) {
            return trim((string)$data['Sae']['application_reference_no']);
        }

        if (!empty($data['Sae']['protocol_no'])) {
            return trim((string)$data['Sae']['protocol_no']);
        }

        return '';
    }

    private function _findApplicationByReference($referenceNo = '')
    {
        $referenceNo = trim((string)$referenceNo);
        if ($referenceNo === '') {
            return array();
        }

        return $this->Application->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Application.protocol_no' => $referenceNo
            )
        ));
    }

    private function _applyApplicationContext($data = array(), $application = array())
    {
        if (!empty($application['Application']['id'])) {
            $data['Sae']['application_id'] = $application['Application']['id'];
        }

        if (empty($data['Sae']['email_address']) && !empty($application['Application']['email_address'])) {
            $data['Sae']['email_address'] = $application['Application']['email_address'];
        }

        return $data;
    }

    private function _findAccessibleSubmissionApplication($applicationId = null)
    {
        if (empty($applicationId)) {
            return array();
        }

        return $this->Application->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'Application.id' => $applicationId,
                'Application.approved' => array(1, 2)
            )
        ));
    }

    private function _findDuplicateSubmittedSae($data = array(), $user = array())
    {
        if (empty($user['id'])) {
            return array();
        }

        $reactionOnset = !empty($data['Sae']['reaction_onset']) ? date('Y-m-d', strtotime($data['Sae']['reaction_onset'])) : null;
        if (empty($reactionOnset)) {
            return array();
        }

        return $this->Sae->find('first', array(
            'contain' => array(),
            'fields' => array('Sae.id', 'Sae.reference_no', 'Sae.date_submitted'),
            'conditions' => array(
                'Sae.application_id' => $data['Sae']['application_id'],
                'Sae.form_type' => $data['Sae']['form_type'],
                'Sae.patient_initials' => $data['Sae']['patient_initials'],
                'Sae.reaction_onset' => $reactionOnset,
                'Sae.reaction_description' => $data['Sae']['reaction_description'],
                'Sae.approved >' => 0
            )
        ));
    }

    private function _queueSaeSubmissionNotifications($sae = array(), $submitter = array())
    {
        if (empty($sae['Sae']['id']) || empty($submitter['id'])) {
            return;
        }

        try {
            $this->loadModel('Message');
            $this->loadModel('User');

            $message = $this->Message->find('first', array(
                'contain' => array(),
                'conditions' => array('Message.name' => 'applicant_sae_submit')
            ));

            if (empty($message['Message']['subject']) || empty($message['Message']['content'])) {
                $this->_logSaeApiAttempt('warning', 'SAE submission notification template was not found.', array(
                    'sae_id' => $sae['Sae']['id']
                ));
                return;
            }

            $submitterPrefix = $this->_userPrefix($submitter['group_id']);
            $html = new HtmlHelper(new ThemeView());
            $recipients = $this->User->find('all', array(
                'contain' => array(),
                'conditions' => array(
                    'OR' => array(
                        'User.id' => $submitter['id'],
                        'User.group_id' => 2
                    )
                )
            ));

            foreach ($recipients as $recipient) {
                $actionPrefix = ($recipient['User']['id'] == $submitter['id'] && !empty($submitterPrefix)) ? $submitterPrefix : 'manager';
                $variables = array(
                    'name' => $recipient['User']['name'],
                    'reference_no' => $sae['Sae']['reference_no'],
                    'protocol_no' => $sae['Application']['protocol_no'],
                    'reference_link' => $html->link(
                        $sae['Sae']['reference_no'],
                        array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], $actionPrefix => true, 'full_base' => true),
                        array('escape' => false)
                    ),
                    'protocol_link' => $html->link(
                        $sae['Application']['protocol_no'],
                        array('controller' => 'applications', 'action' => 'view', $sae['Application']['id'], $actionPrefix => true, 'full_base' => true),
                        array('escape' => false)
                    ),
                    'modified' => $sae['Sae']['modified']
                );

                $datum = array(
                    'email' => !empty($recipient['User']['email']) ? $recipient['User']['email'] : $sae['Sae']['reporter_email'],
                    'id' => $sae['Sae']['id'],
                    'user_id' => $recipient['User']['id'],
                    'type' => 'applicant_sae_submit',
                    'model' => 'Sae',
                    'subject' => String::insert($message['Message']['subject'], $variables),
                    'message' => String::insert($message['Message']['content'], $variables)
                );

                CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
            }
        } catch (Exception $exception) {
            $this->_logSaeApiAttempt('warning', 'SAE submission notifications could not be queued.', array(
                'sae_id' => isset($sae['Sae']['id']) ? $sae['Sae']['id'] : null,
                'exception' => $exception->getMessage()
            ));
        }
    }

    private function _userPrefix($groupId = null)
    {
        $map = array(
            '1' => 'admin',
            '2' => 'manager',
            '5' => 'applicant',
            '7' => 'monitor',
            '8' => 'outsource',
        );

        $groupId = (string)$groupId;

        return isset($map[$groupId]) ? $map[$groupId] : null;
    }

    private function _logSaeApiAttempt($status = 'info', $message = '', $context = array())
    {
        $entry = array(
            'status' => $status,
            'message' => $message,
            'request_url' => $this->request->here(),
            'ip' => env('REMOTE_ADDR'),
            'context' => $context
        );

        $logType = ($status === 'success') ? 'sae_api_success' : 'sae_api_error';
        if ($status === 'warning') {
            $logType = 'sae_api_warning';
        }

        $this->log($entry, $logType);
    }

    private function _jsonSuccessResponse($message = '', $data = array())
    {
        return $this->_jsonResponse(200, array(
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ));
    }

    private function _jsonErrorResponse($statusCode = 400, $message = '', $errors = array())
    {
        if (empty($errors)) {
            $errors = new stdClass();
        }

        return $this->_jsonResponse($statusCode, array(
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ));
    }

    private function _jsonResponse($statusCode = 200, $payload = array())
    {
        $this->autoRender = false;
        $this->_setApiResponseStatusCode($statusCode);
        $this->response->type('json');
        $this->response->body(json_encode($payload));

        return $this->response;
    }
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function applicant_view($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] < 1) {
            $this->Session->setFlash(__('The sae has not been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $this->Sae->id));
        }
        if ($sae['Sae']['user_id'] !== $this->Auth->User('id')) {
            $this->Session->setFlash(__('You don\'t have permission to access!!'), 'alerts/flash_error');
            $this->redirect('/');
        }
        $this->set('sae', $this->Sae->find(
            'first',
            array(
                'contain' => array('Application', 'Country', 'SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route'), 'Comment' => array('Attachment')),
                'conditions' => array('Sae.id' => $id)
            )
        ));
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAE_' . $id,  'orientation' => 'portrait');
        }
    }
    public function monitor_view($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] < 1) {
            $this->Session->setFlash(__('The sae has not been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $this->Sae->id));
        }
        // if ($sae['Sae']['user_id'] !== $this->Auth->User('id')) {
        if (!in_array($sae['Sae']['user_id'], array($this->Auth->User('id'), $sae['Sae']['user_id']))) {
            $this->Session->setFlash(__('You don\'t have permission to access!!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('sae', $this->Sae->find(
            'first',
            array(
                'contain' => array('Application', 'Country', 'SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route'), 'Comment' => array('Attachment')),
                'conditions' => array('Sae.id' => $id)
            )
        ));
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAE_' . $id,  'orientation' => 'portrait');
        }
    }
    public function outsource_view($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] < 1) {
            $this->Session->setFlash(__('The sae has not been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $this->Sae->id));
        }
        // if ($sae['Sae']['user_id'] !== $this->Auth->User('id')) {
        if (!in_array($sae['Sae']['user_id'], array($this->Auth->User('id'), $sae['Sae']['user_id']))) {
            $this->Session->setFlash(__('You don\'t have permission to access!!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('sae', $this->Sae->find(
            'first',
            array(
                'contain' => array('Application', 'Country', 'SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route'), 'Comment' => array('Attachment')),
                'conditions' => array('Sae.id' => $id)
            )
        ));
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAE_' . $id,  'orientation' => 'portrait');
        }
    }
    public function aview($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] < 1) {
            $this->Session->setFlash(__('The sae has not been submitted'), 'alerts/flash_info');
        }

        $this->set('sae', $this->Sae->find(
            'first',
            array(
                'contain' => array('Application', 'Country', 'SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route'), 'Comment' => array('Attachment')),
                'conditions' => array('Sae.id' => $id)
            )
        ));
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'SAE_' . $id,  'orientation' => 'portrait');
        }
    }
    public function manager_view($id = null)
    {
        $this->aview($id);
    }
    public function inspector_view($id = null)
    {
        $this->aview($id);
    }
    /**
     * add method
     *
     * @return void
     */
    public function applicant_add($id = null, $type = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        if ($type == 'sae') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SAE',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            // $this->Sae->saveField('reference_no', 'SAE/'.date('Y').'/'.$count);
            // $this->Sae->saveField('form_type', 'SAE');
            $this->Sae->create();
            $this->Sae->save(['Sae' => [
                'application_id' => $id,
                'user_id' => $this->Auth->User('id'),
                'reporter_email' => $this->Auth->User('email'),
                'email_address' => $this->Auth->User('email'),
                'reference_no' => 'SAE/' . date('Y') . '/' . $count,
                'form_type' => 'SAE'
            ]], false);
            $this->Session->setFlash(__('The SAE has been created'), 'alerts/flash_success');
        } elseif ($type == 'susar') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SUSAR',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            // $this->Sae->saveField('reference_no', 'SUSAR/'.date('Y').'/'.$count);
            // $this->Sae->saveField('form_type', 'SUSAR');
            $this->Sae->create();
            $this->Sae->save(['Sae' => [
                'application_id' => $id,
                'user_id' => $this->Auth->User('id'),
                'email_address' => $this->Auth->User('email'),
                'reporter_email' => $this->Auth->User('email'),
                'reference_no' => 'SUSAR/' . date('Y') . '/' . $count,
                'form_type' => 'SUSAR'
            ]], false);
            $this->Session->setFlash(__('The SUSAR has been created'), 'alerts/flash_success');
        }
        $this->redirect(array('action' => 'edit', $this->Sae->id));
    }
    public function applicant_followup($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sae->id = $id;
            if (!$this->Sae->exists()) {
                throw new NotFoundException(__('Invalid sae'));
            }
            $sae = Hash::remove($this->Sae->find(
                'first',
                array(
                    'contain' => array('SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route')),
                    'conditions' => array('Sae.id' => $id)
                )
            ), 'Sae.id');

            $sae = Hash::remove($sae, 'SuspectedDrug.{n}.id');
            $sae = Hash::remove($sae, 'ConcomittantDrug.{n}.id');
            $data_save = $sae['Sae'];
            $data_save['SuspectedDrug'] = $sae['SuspectedDrug'];
            if (isset($sae['ConcomittantDrug'])) $data_save['ConcomittantDrug'] = $sae['ConcomittantDrug'];
            $data_save['sae_id'] = $id;

            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.reference_no LIKE' => $sae['Sae']['reference_no'] . '%',
            )));
            $count = ($count < 10) ? "0$count" : $count;
            $data_save['reference_no'] = $sae['Sae']['reference_no'] . '_F' . $count;
            $data_save['report_type'] = 'Followup';
            $data_save['approved'] = 0;

            if ($this->Sae->saveAssociated($data_save, array('deep' => true, 'validate' => false))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Sae->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }

    public function monitor_add($id = null, $type = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        if ($type == 'sae') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SAE',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            // $this->Sae->saveField('reference_no', 'SAE/'.date('Y').'/'.$count);
            // $this->Sae->saveField('form_type', 'SAE');
            $this->Sae->create();
            $this->Sae->save([
                'Sae' => [
                    'application_id' => $id,
                    'user_id' => $this->Auth->User('id'),
                    'reporter_email' => $this->Auth->User('email'),
                    'reference_no' => 'SAE/' . date('Y') . '/' . $count,
                    'form_type' => 'SAE'
                ]
            ], false);
            $this->Session->setFlash(__('The SAE has been created'), 'alerts/flash_success');
        } elseif ($type == 'susar') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SUSAR',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            // $this->Sae->saveField('reference_no', 'SUSAR/'.date('Y').'/'.$count);
            // $this->Sae->saveField('form_type', 'SUSAR');
            $this->Sae->create();
            $this->Sae->save(['Sae' => [
                'application_id' => $id, 'user_id' => $this->Auth->User('id'), 'reporter_email' => $this->Auth->User('email'), 'reference_no' => 'SUSAR/' . date('Y') . '/' . $count,
                'form_type' => 'SUSAR'
            ]], false);
            $this->Session->setFlash(__('The SUSAR has been created'), 'alerts/flash_success');
        }
        $this->redirect(array('action' => 'edit', $this->Sae->id));
    }



    public function outsource_add($id = null, $type = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        if ($type == 'sae') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SAE',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            $this->Sae->create();
            $this->Sae->save([
                'Sae' => [
                    'application_id' => $id,
                    'user_id' => $this->Auth->User('id'),
                    'reporter_email' => $this->Auth->User('email'),
                    'email_address' => $this->Auth->User('email'),
                    'reference_no' => 'SAE/' . date('Y') . '/' . $count,
                    'form_type' => 'SAE'
                ]
            ], false);
            $this->Session->setFlash(__('The SAE has been created'), 'alerts/flash_success');
        } elseif ($type == 'susar') {
            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.form_type' => 'SUSAR',
                'Sae.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
            )));
            $count++;
            $count = ($count < 10) ? "0$count" : $count;
            $this->Sae->create();
            $this->Sae->save(['Sae' => [
                'application_id' => $id,
                'user_id' => $this->Auth->User('id'),
                'reporter_email' => $this->Auth->User('email'),
                'email_address' => $this->Auth->User('email'),
                'reference_no' => 'SUSAR/' . date('Y') . '/' . $count,
                'form_type' => 'SUSAR'
            ]], false);
            $this->Session->setFlash(__('The SUSAR has been created'), 'alerts/flash_success');
        }
        $this->redirect(array('action' => 'edit', $this->Sae->id));
    }
    public function monitor_followup($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sae->id = $id;
            if (!$this->Sae->exists()) {
                throw new NotFoundException(__('Invalid sae'));
            }
            $sae = Hash::remove($this->Sae->find(
                'first',
                array(
                    'contain' => array('SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route')),
                    'conditions' => array('Sae.id' => $id)
                )
            ), 'Sae.id');

            $sae = Hash::remove($sae, 'SuspectedDrug.{n}.id');
            $sae = Hash::remove($sae, 'ConcomittantDrug.{n}.id');
            $data_save = $sae['Sae'];
            $data_save['SuspectedDrug'] = $sae['SuspectedDrug'];
            if (isset($sae['ConcomittantDrug'])) $data_save['ConcomittantDrug'] = $sae['ConcomittantDrug'];
            $data_save['sae_id'] = $id;

            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.reference_no LIKE' => $sae['Sae']['reference_no'] . '%',
            )));
            $count = ($count < 10) ? "0$count" : $count;
            $data_save['reference_no'] = $sae['Sae']['reference_no'] . '_F' . $count;
            $data_save['report_type'] = 'Followup';
            $data_save['approved'] = 0;
            $data_save['user_id'] = $this->Auth->User('id');

            if ($this->Sae->saveAssociated($data_save, array('deep' => true))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Sae->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }
    public function outsource_followup($id = null)
    {
        if ($this->request->is('post')) {
            $this->Sae->id = $id;
            if (!$this->Sae->exists()) {
                throw new NotFoundException(__('Invalid sae'));
            }
            $sae = Hash::remove($this->Sae->find(
                'first',
                array(
                    'contain' => array('SuspectedDrug' => array('Route'), 'ConcomittantDrug' => array('Route')),
                    'conditions' => array('Sae.id' => $id)
                )
            ), 'Sae.id');

            $sae = Hash::remove($sae, 'SuspectedDrug.{n}.id');
            $sae = Hash::remove($sae, 'ConcomittantDrug.{n}.id');
            $data_save = $sae['Sae'];
            $data_save['SuspectedDrug'] = $sae['SuspectedDrug'];
            if (isset($sae['ConcomittantDrug'])) $data_save['ConcomittantDrug'] = $sae['ConcomittantDrug'];
            $data_save['sae_id'] = $id;

            $count = $this->Sae->find('count',  array('conditions' => array(
                'Sae.reference_no LIKE' => $sae['Sae']['reference_no'] . '%',
            )));
            $count = ($count < 10) ? "0$count" : $count;
            $data_save['reference_no'] = $sae['Sae']['reference_no'] . '_F' . $count;
            $data_save['report_type'] = 'Followup';
            $data_save['approved'] = 0;
            $data_save['user_id'] = $this->Auth->User('id');

            if ($this->Sae->saveAssociated($data_save, array('deep' => true))) {
                $this->Session->setFlash(__('Follow up ' . $data_save['reference_no'] . ' has been created'), 'alerts/flash_info');
                $this->redirect(array('action' => 'edit', $this->Sae->id));
            } else {
                $this->Session->setFlash(__('The followup could not be saved. Please, try again.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function applicant_edit($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] > 0) {
            $this->Session->setFlash(__('The sae has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Sae->id));
        }
        if ($sae['Sae']['user_id'] !== $this->Session->read('Auth.User.id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this SAE!!'), 'alerts/flash_warning');
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Sae->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sae->saveField('approved', 1);
                    $this->Sae->saveField('date_submitted', date('Y-m-d H:i:s'));
                    $sae = $this->Sae->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'applicant_sae_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'), 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                        'reference_link' => $html->link(
                            $sae['Sae']['reference_no'],
                            array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'applicant' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'protocol_link' => $html->link(
                            $sae['Application']['protocol_no'],
                            array(
                                'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'applicant' => true,
                                'full_base' => true
                            ),
                            array('escape' => false)
                        ),
                        'modified' => $sae['Sae']['modified']
                    );
                    $datum = array(
                        'email' => $sae['Sae']['reporter_email'],
                        'id' => $id, 'user_id' => $this->Auth->User('id'), 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                        'subject' => String::insert($message['Message']['subject'], $variables),
                        'message' => String::insert($message['Message']['content'], $variables)
                    );
                    // CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                    // CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    $users = $this->Sae->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('User.group_id' => 2)
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'], 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                            'reference_link' => $html->link(
                                $sae['Sae']['reference_no'],
                                array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'protocol_link' => $html->link(
                                $sae['Application']['protocol_no'],
                                array(
                                    'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'manager' => true,
                                    'full_base' => true
                                ),
                                array('escape' => false)
                            ),
                            'modified' => $sae['Sae']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id, 'user_id' => $user['User']['id'], 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                            'subject' => String::insert($message['Message']['subject'], $variables),
                            'message' => String::insert($message['Message']['content'], $variables)
                        );
                        // CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                        // CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    }
                    //**********************************    END   *********************************

                    $this->Session->setFlash(__('The sae has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Sae->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The sae has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The sae could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sae->read(null, $id);
        }

        //$sae = $this->request->data;

        $applications = $this->Sae->Application->find('list', array(
            'fields' => array('Application.id', 'Application.protocol_no'),
            'conditions' => array('Application.user_id' => $this->Session->read('Auth.User.id'), 'Application.approved' => array(1, 2))
        ));
        $routes = $this->Sae->SuspectedDrug->Route->find('list');
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('sae', 'routes', 'countries', 'applications'));
    }

    public function monitor_edit($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] > 0) {
            $this->Session->setFlash(__('The sae has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Sae->id));
        }
        if ($sae['Sae']['user_id'] !== $this->Session->read('Auth.User.id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this SAE!!'), 'alerts/flash_warning');
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Sae->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sae->saveField('approved', 1);
                    $this->Sae->saveField('date_submitted', date('Y-m-d H:i:s'));
                    $sae = $this->Sae->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'applicant_sae_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'), 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                        'reference_link' => $html->link(
                            $sae['Sae']['reference_no'],
                            array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'applicant' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'protocol_link' => $html->link(
                            $sae['Application']['protocol_no'],
                            array(
                                'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'applicant' => true,
                                'full_base' => true
                            ),
                            array('escape' => false)
                        ),
                        'modified' => $sae['Sae']['modified']
                    );
                    $datum = array(
                        'email' => $sae['Sae']['reporter_email'],
                        'id' => $id, 'user_id' => $this->Auth->User('id'), 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                        'subject' => String::insert($message['Message']['subject'], $variables),
                        'message' => String::insert($message['Message']['content'], $variables)
                    );
                    // CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                    // CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    $users = $this->Sae->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('OR' => array('User.id' => $this->Auth->User('id'), 'User.group_id' => 2))
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'], 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                            'reference_link' => $html->link(
                                $sae['Sae']['reference_no'],
                                array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'protocol_link' => $html->link(
                                $sae['Application']['protocol_no'],
                                array(
                                    'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'manager' => true,
                                    'full_base' => true
                                ),
                                array('escape' => false)
                            ),
                            'modified' => $sae['Sae']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id, 'user_id' => $user['User']['id'], 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                            'subject' => String::insert($message['Message']['subject'], $variables),
                            'message' => String::insert($message['Message']['content'], $variables)
                        );
                        // CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                        // CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    }
                    //**********************************    END   *********************************

                    $this->Session->setFlash(__('The sae has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Sae->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The sae has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The sae could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sae->read(null, $id);
        }

        //$sae = $this->request->data;
        $aids = $this->Sae->Application->StudyMonitor->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('StudyMonitor.user_id' => $this->Auth->User('id'))));
        $applications = $this->Sae->Application->find('list', array(
            'fields' => array('Application.id', 'Application.protocol_no'),
            'conditions' => array('Application.id' => $aids, 'Application.approved' => array(1, 2), 'Application.submitted' => array(1))
        ));
        $routes = $this->Sae->SuspectedDrug->Route->find('list');
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('sae', 'routes', 'countries', 'applications'));
    }
    public function outsource_edit($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        $sae = $this->Sae->read(null, $id);
        if ($sae['Sae']['approved'] > 0) {
            $this->Session->setFlash(__('The sae has been submitted'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $this->Sae->id));
        }
        if ($sae['Sae']['user_id'] !== $this->Session->read('Auth.User.id')) {
            $this->Session->setFlash(__('You don\'t have permission to edit this SAE!!'), 'alerts/flash_warning');
            $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
            }
            if ($this->Sae->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Sae->saveField('approved', 1);
                    $this->Sae->saveField('date_submitted', date('Y-m-d H:i:s'));
                    $sae = $this->Sae->read(null, $id);

                    //******************       Send Email and Notifications to Applicant and Managers          *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'applicant_sae_submit')));
                    $variables = array(
                        'name' => $this->Auth->User('name'), 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                        'reference_link' => $html->link(
                            $sae['Sae']['reference_no'],
                            array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'applicant' => true, 'full_base' => true),
                            array('escape' => false)
                        ),
                        'protocol_link' => $html->link(
                            $sae['Application']['protocol_no'],
                            array(
                                'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'applicant' => true,
                                'full_base' => true
                            ),
                            array('escape' => false)
                        ),
                        'modified' => $sae['Sae']['modified']
                    );
                    $datum = array(
                        'email' => $sae['Sae']['reporter_email'],
                        'id' => $id, 'user_id' => $this->Auth->User('id'), 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                        'subject' => String::insert($message['Message']['subject'], $variables),
                        'message' => String::insert($message['Message']['content'], $variables)
                    );
                    CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                    CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    $users = $this->Sae->User->find('all', array(
                        'contain' => array(),
                        'conditions' => array('OR' => array('User.id' => $this->Auth->User('id'), 'User.group_id' => 2))
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'], 'reference_no' => $sae['Sae']['reference_no'], 'protocol_no' => $sae['Application']['protocol_no'],
                            'reference_link' => $html->link(
                                $sae['Sae']['reference_no'],
                                array('controller' => 'saes', 'action' => 'view', $sae['Sae']['id'], 'manager' => true, 'full_base' => true),
                                array('escape' => false)
                            ),
                            'protocol_link' => $html->link(
                                $sae['Application']['protocol_no'],
                                array(
                                    'controller' => 'applications', 'action' => 'view', $sae['Application']['id'], 'manager' => true,
                                    'full_base' => true
                                ),
                                array('escape' => false)
                            ),
                            'modified' => $sae['Sae']['modified']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id, 'user_id' => $user['User']['id'], 'type' => 'applicant_sae_submit', 'model' => 'Sae',
                            'subject' => String::insert($message['Message']['subject'], $variables),
                            'message' => String::insert($message['Message']['content'], $variables)
                        );
                        CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                        CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    }
                    //**********************************    END   *********************************

                    $this->Session->setFlash(__('The sae has been submitted to PPB'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'view', $this->Sae->id));
                }
                // debug($this->request->data);
                $this->Session->setFlash(__('The sae has been saved'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The sae could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $this->Sae->read(null, $id);
        }

        //$sae = $this->request->data;
        $aids = $this->Sae->Application->ProtocolOutsource->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('ProtocolOutsource.user_id' => $this->Auth->User('id'))));
        $applications = $this->Sae->Application->find('list', array(
            'fields' => array('Application.id', 'Application.protocol_no'),
            'conditions' => array('Application.id' => $aids, 'Application.approved' => array(1, 2), 'Application.submitted' => array(1))
        ));
        $routes = $this->Sae->SuspectedDrug->Route->find('list');
        $countries = $this->Sae->Country->find('list');
        $this->set(compact('sae', 'routes', 'countries', 'applications'));
    }
    public function manager_unsubmit($id = null)
    {
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            $this->Session->setFlash(__('SAE does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Sae->saveField('approved', 0)) {
            $this->Session->setFlash(__('The SAE/SUSAR has been successfully Unsubmitted. The user is now able to edit the SAE.'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
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
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        if ($this->Sae->delete()) {
            $this->Session->setFlash(__('Sae deleted'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Sae was not deleted'), 'alerts/flash_error');
        $this->redirect(array('action' => 'index'));
    }
    public function monitor_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        if ($this->Sae->delete()) {
            $this->Session->setFlash(__('Sae deleted'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Sae was not deleted'), 'alerts/flash_error');
        $this->redirect(array('action' => 'index'));
    }
    public function outsource_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Sae->id = $id;
        if (!$this->Sae->exists()) {
            throw new NotFoundException(__('Invalid sae'));
        }
        if ($this->Sae->delete()) {
            $this->Session->setFlash(__('Sae deleted'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Sae was not deleted'), 'alerts/flash_error');
        $this->redirect(array('action' => 'index'));
    }
}

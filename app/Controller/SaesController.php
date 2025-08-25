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
        $this->Auth->allow('fetch');
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

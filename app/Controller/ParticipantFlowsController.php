<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * ParticipantFlows Controller
 *
 * @property ParticipantFlow $ParticipantFlow
 */
class ParticipantFlowsController extends AppController {
    public $paginate = array();
    public $components = array('Search.Prg');
    public $presetVars = true; // using the model configuration
/**
 * index method
 *
 * @return void
 */
    // public function index() {
    //     $this->ParticipantFlow->recursive = 0;
    //     $this->set('participantFlows', $this->paginate());
    // }
    public function index() {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '20' => '20');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
            else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->ParticipantFlow->parseCriteria($this->passedArgs);
        $conditions = array(
            $this->ParticipantFlow->latestPerApplicationYearCondition('ParticipantFlow')
        );
        if (!empty($criteria)) $conditions[] = $criteria;
        $this->paginate['conditions'] = $conditions;
        $this->paginate['order'] = array(
            'ParticipantFlow.year' => 'desc',
            'ParticipantFlow.created' => 'desc',
            'ParticipantFlow.id' => 'desc',
        );
        $this->paginate['contain'] = array('Application');
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
          $this->csv_export($this->ParticipantFlow->find('all', 
                  array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
              ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('participantFlows', Sanitize::clean($this->paginate(), array('encode' => false)));
    }
    public function manager_index() {
        $this->index();
    }
    public function inspector_index() {
        $this->index();
    }

    private function csv_export($participants = ''){
        $_serialize = 'participants';
        $_header = array('#','Protocol No', 'Year', 'Original Subjects', 'Consented', 'Screened', 'Enrolled', 'Lost', 'Withdrawn', 'Self withdrawal', 'Active subjects', 'Completed number',
            'Created', 'Reasons for lost', 'Withdrawn reasons', 'Self Withdrawal reasons');
        $_extract = array('ParticipantFlow.id', 'Application.protocol_no', 'ParticipantFlow.year', 'ParticipantFlow.original_subjects', 'ParticipantFlow.consented', 'ParticipantFlow.screened', 'ParticipantFlow.enrolled', 'ParticipantFlow.lost', 'ParticipantFlow.withdrawn', 'ParticipantFlow.self_withdrawal', 'ParticipantFlow.active_subjects', 'ParticipantFlow.completed_number', 
            'ParticipantFlow.created', 'ParticipantFlow.lost_reason', 'ParticipantFlow.withdrawal_reason', 'ParticipantFlow.self_withdrawal_reasons');

        $this->response->download('Participant_Flow_'.date('Ymd_Hi').'.csv'); // <= setting the file name
        $this->viewClass = 'CsvView.Csv';
        $this->set(compact('participants', '_serialize', '_header', '_extract'));
    }
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        $this->ParticipantFlow->id = $id;
        if (!$this->ParticipantFlow->exists()) {
            throw new NotFoundException(__('Invalid participant flow'));
        }
        $this->set('participantFlow', $this->ParticipantFlow->read(null, $id));
    }

/**
 * add method
 *
 * @return void
 */
    public function applicant_add() {
        if ($this->request->is('post')) {
            $year = isset($this->request->data['ParticipantFlow']['year']) ? $this->request->data['ParticipantFlow']['year'] : null;
            $applicationId = isset($this->request->data['ParticipantFlow']['application_id']) ? $this->request->data['ParticipantFlow']['application_id'] : null;
            $existingYearEntries = 0;
            if (!empty($applicationId) && !empty($year)) {
                $existingYearEntries = $this->ParticipantFlow->find('count', array(
                    'conditions' => array(
                        'ParticipantFlow.application_id' => $applicationId,
                        'ParticipantFlow.year' => $year,
                    ),
                    'recursive' => -1,
                ));
            }

            $this->ParticipantFlow->create();
            if ($this->ParticipantFlow->save($this->request->data)) {
                if ($existingYearEntries > 0) {
                    $this->Session->setFlash(__('The participant flow has been saved. '), 'alerts/flash_success');
                } else {
                    $this->Session->setFlash(__('The participant flow has been saved'), 'alerts/flash_success');
                }
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash(__('The participant flow could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        }
        $this->redirect($this->referer());
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        $this->ParticipantFlow->id = $id;
        if (!$this->ParticipantFlow->exists()) {
            throw new NotFoundException(__('Invalid participant flow'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ParticipantFlow->save($this->request->data)) {
                $this->Session->setFlash(__('The participant flow has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The participant flow could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->ParticipantFlow->read(null, $id);
        }
        $applications = $this->ParticipantFlow->Application->find('list');
        $this->set(compact('applications'));
    }

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->ParticipantFlow->id = $id;
        if (!$this->ParticipantFlow->exists()) {
            throw new NotFoundException(__('Invalid participant flow'));
        }
        if ($this->ParticipantFlow->delete()) {
            $this->Session->setFlash(__('Participant flow deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Participant flow was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

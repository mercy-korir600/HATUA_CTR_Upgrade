<?php
App::uses('AppController', 'Controller');

class AuditorProtocolsController extends AppController
{
    public $paginate = array();
    public $uses = array('AuditorProtocol', 'User', 'Application');
    public $components = array('Search.Prg');
    public $presetVars = true;

    // List all Auditors (Accessible by Admins and Managers)
    public function admin_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('10' => '10', '20' => '20');
        
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) {
            $this->paginate['User']['limit'] = $this->passedArgs['pages'];
        } else {
            $this->paginate['User']['limit'] = reset($page_options);
        }

        $criteria = $this->User->parseCriteria($this->passedArgs);
        $criteria['User.group_id'] = 10; // Group ID 10 is Auditor
        $this->paginate['User']['conditions'] = $criteria;
        $this->paginate['User']['order'] = array('User.created' => 'desc');
        $this->paginate['User']['contain'] = array('Group', 'AuditorProtocol' => array('Application'));

        $this->set('page_options', $page_options);
        $this->set('users', $this->paginate('User'));
    }

    // View specific auditor's assignments and search/assign new protocols
    public function admin_view($user_id = null, $application_id = null)
    {
        if (empty($user_id)) {
            $this->Session->setFlash(__('Auditor does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $user = $this->User->find('first', array(
            'contain' => array('AuditorProtocol' => array('Application')),
            'conditions' => array('User.id' => $user_id, 'User.group_id' => 10)
        ));
        
        if (!$user) {
            $this->Session->setFlash(__('User is not an Auditor.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('user', $user);

        $this->Prg->commonProcess();
        $criteria = $this->Application->parseCriteria($this->passedArgs);
        // Exclude protocols already assigned to this auditor
        $criteria['NOT'] = array('Application.id' => Hash::extract($user['AuditorProtocol'], '{n}.application_id'));
        $this->paginate['Application']['conditions'] = $criteria;
        $this->paginate['Application']['order'] = array('Application.created' => 'desc');
        $this->paginate['Application']['contain'] = array('User');

        $this->set('applications', $this->paginate('Application'));

        // Handle assignment POST request
        if ($this->request->is('post') && !empty($application_id)) {
            $this->AuditorProtocol->create();
            $ownerId = $this->Application->field('user_id', array('Application.id' => $application_id));
            $saveData = array(
                'application_id' => $application_id, 
                'user_id' => $user_id, 
                'owner_id' => $ownerId
            );
            
            if ($this->AuditorProtocol->save($saveData)) {
                $this->Session->setFlash(__('The protocol has been successfully assigned to the auditor.'), 'alerts/flash_success');
            } else {
                $this->Session->setFlash(__('The protocol could not be assigned. Please try again.'), 'alerts/flash_error');
            }
            $this->redirect($this->referer());
        }
    }

    // Revoke protocol assignment
    public function admin_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        
        $this->AuditorProtocol->id = $id;
        if (!$this->AuditorProtocol->exists()) {
            throw new NotFoundException(__('Invalid assignment record.'));
        }

        if ($this->AuditorProtocol->delete()) {
            $this->Session->setFlash(__('Protocol assignment has been revoked.'), 'alerts/flash_success');
        } else {
            $this->Session->setFlash(__('Assignment could not be revoked.'), 'alerts/flash_error');
        }
        $this->redirect($this->referer());
    }
}
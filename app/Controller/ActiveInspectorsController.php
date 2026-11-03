<?php
App::uses('AppController', 'Controller');
/**
 * ActiveInspectors Controller
 *  @property ActiveInspector $ActiveInspector
 */
class ActiveInspectorsController extends AppController
{

    /**
     * Scaffold
     *
     * @var mixed
     */
    public $scaffold;

    public $uses = array('ActiveInspector', 'Application', 'AuditTrail', 'User', 'SiteInspection', 'SiteQuestion');

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow('manager_assign', 'manager_revoke', 'inspector_respond');
    }
    public function inspector_respond()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->data['ActiveInspector']['accepted'])) {
                $this->Session->setFlash(__('Please accept / decline the offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['ActiveInspector']['application_id']));
            }
            if (empty($this->request->data['ActiveInspector']['conflict'])) {
                $this->Session->setFlash(__('Please declare your conflict of interest with this offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['ActiveInspector']['application_id']));
            }


            $review = $this->ActiveInspector->find('first', array('conditions' => array(
                'ActiveInspector.application_id' => $this->request->data['ActiveInspector']['application_id'],
                'ActiveInspector.user_id' => $this->Auth->User('id'),
                'ActiveInspector.type' => 'request'
            ), 'contain' => array()));
            if (!empty($review)) {
                $this->ActiveInspector->create();
                $review['ActiveInspector']['accepted'] = $this->request->data['ActiveInspector']['accepted'];
                $review['ActiveInspector']['recommendation'] = $this->request->data['ActiveInspector']['recommendation'];
                $review['ActiveInspector']['conflict'] = $this->request->data['ActiveInspector']['conflict'];
                if ($this->Auth->password($this->request->data['ActiveInspector']['password']) === $this->Auth->User('confirm_password')) {
                    if ($this->ActiveInspector->save($review)) {


                        //end stages
                        // Create a Audit Trail
                        $this->loadModel('AuditTrail');
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $this->request->data['ActiveInspector']['application_id'],
                                'model' => 'ActiveInspector',
                                'message' => 'Inspector ' . $this->Auth->User('username') . ' ' . $this->request->data['ActiveInspector']['accepted'] . ' a site inspection request for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $this->request->data['ActiveInspector']['application_id'])),
                                'ip' =>  $this->Application->field('protocol_no', array('id' => $this->request->data['ActiveInspector']['application_id']))
                            )
                        );
                        $this->AuditTrail->Create();
                        if ($this->AuditTrail->save($audit)) {
                            $this->log($this->request->data['ActiveInspector'], 'audit_success');
                        } else {
                            $this->log('Error creating an audit trail', 'audit_error');
                            $this->log($this->request->data['ActiveInspector'], 'audit_error');
                        }

                        CakeResque::enqueue('default', 'NotificationShell', array('ppbRequestReviewResponse', $review));

                        if ($review['ActiveInspector']['accepted'] == 'accepted') {
                            $this->Session->setFlash(__('Thank you. Your response has been sent to PPB. You may proceed to review the application'),  'alerts/flash_success');
                            $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['ActiveInspector']['application_id']));
                        } elseif ($review['ActiveInspector']['accepted'] == 'declined') {
                            $this->Session->setFlash(__('Thank you for your prompt response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'applications', 'action' => 'index'));
                        } else {
                            $this->Session->setFlash(__('NO response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        }
                    } else {
                        $this->Session->setFlash(__('The review could not be saved. Please, try again.'));
                        $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['ActiveInspector']['application_id']));
                    }
                } else {
                    $this->Session->setFlash(__('The password you have entered is not correct! Please enter your correct password
                    and try again.'), 'alerts/flash_error');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['ActiveInspector']['application_id']));
                }
            } else {
                $this->Session->setFlash(__('Seems the request review is empty!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
        } else {
            $this->Session->setFlash(__('Invalid method signature!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }

    public function manager_assign($id = null)
    {
        if ($this->request->is('post')) {
            $this->ActiveInspector->create();
            $message = $this->request->data['Message'];
            unset($this->request->data['Message']);
            if (!empty($this->request->data)) {
                foreach ($this->request->data as $key => $value) {
                    $this->request->data[$key]['ActiveInspector']['application_id'] = $id;
                    $this->request->data[$key]['ActiveInspector']['type'] = 'request';
                    $this->request->data[$key]['ActiveInspector']['title'] = 'PPB request';
                    $this->request->data[$key]['ActiveInspector']['text'] = $message['text'];
                }

                if ($this->ActiveInspector->saveMany($this->request->data)) {

                    // Auto-generate inspection checklist if none exists yet for this protocol
                    $existingInspection = $this->SiteInspection->find('first', array(
                        'conditions' => array('SiteInspection.application_id' => $id),
                        'contain' => array()
                    ));
                    if (empty($existingInspection)) {
                        $this->_autoGenerateInspectionChecklist($id);
                    }

                    //end stages

                    CakeResque::enqueue('default', 'NotificationShell', array('newAppNotifyInspector', $this->request->data));


                    $this->Session->setFlash(__('The inspectors have been notified'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                } else {
                    $this->Session->setFlash(__('The inspectors could not be notified. Please, try again.'));
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                }
            } else {
                $this->Session->setFlash(__('Please select at least one inspector'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }
        }
        $users = $this->ActiveInspector->User->find('list');
        $applications = $this->ActiveInspector->Application->find('list');
        $this->set(compact('users', 'applications'));
    }

    private function _autoGenerateInspectionChecklist($application_id)
    {
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('InvestigatorContact')
        ));
        if (empty($application)) {
            return false;
        }

        $all_questions = $this->SiteQuestion->find('all');
        $answers = array();
        foreach ($all_questions as $question) {
            $answers[] = array(
                'question_type' => $question['SiteQuestion']['question_type'],
                'question_number' => $question['SiteQuestion']['question_number'],
                'question' => $question['SiteQuestion']['question']
            );
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
        if (!empty($application['InvestigatorContact'])) {
            foreach ($application['InvestigatorContact'] as $key => $value) {
                $co_investigators .= ($key + 1) . " " . $value['given_name'] . " " . $value['family_name'] . " Email: " . $value['email'] . "\n";
            }
        }
        $count = $this->SiteInspection->find('count', array('conditions' => array(
            'SiteInspection.created BETWEEN ? and ?' => array(date("Y-01-01 00:00:00"), date("Y-m-d H:i:s"))
        )));
        $count++;
        $count = ($count < 10) ? "0$count" : $count;
        $data = array(
            'application_id' => $application_id,
            'user_id' => $this->Auth->User('id'),
            'study_title' => $application['Application']['study_title'],
            'protocol_no' => $application['Application']['protocol_no'],
            'reference_no' => 'SI/' . date('Y') . '/' . $count,
            'trial_phase' => $trial,
            'investigators' => $investigators,
            'co_investigators' => $co_investigators,
            'inspection_country' => $inspection_country
        );

        $this->SiteInspection->create();
        if ($this->SiteInspection->saveAssociated(array('SiteInspection' => $data, 'SiteAnswer' => $answers))) {
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application_id,
                    'model' => 'SiteInspection',
                    'message' => 'Inspection checklist auto-generated for protocol ' . $application['Application']['protocol_no'] . ' on inspector assignment by ' . $this->Auth->User('username'),
                    'ip' => $application['Application']['protocol_no']
                )
            );
            $this->AuditTrail->create();
            $this->AuditTrail->save($audit);
            return true;
        }
        return false;
    }

    public function manager_revoke($id = null, $application_id)
    {
        $this->ActiveInspector->id = $id;
        if (!$this->ActiveInspector->exists()) {
            throw new NotFoundException(__('Invalid Assignment'));
        }

        $app = $this->ActiveInspector->find('first', array(
            'conditions' => array('ActiveInspector.id' => $id),
        ));
        // debug($app);
        // exit;
        if ($this->ActiveInspector->delete()) {

            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application_id,
                    'model' => 'Application',
                    'message' => 'Site inspection assigned to ' . $app['User']['username'] . ' for the protocol with reference number ' . $app['Application']['protocol_no'] . ' has been revorked by ' . $this->Auth->user('name'),
                    'ip' => $app['Application']['protocol_no']
                )
            );
            $this->AuditTrail->Create();
            if ($this->AuditTrail->save($audit)) {
                $this->log($app['Application']['protocol_no'], 'audit_success');
            } else {
                $this->log('Error creating an audit trail', 'audit_error');
                $this->log($app['Application']['protocol_no'], 'audit_error');
            }
            $this->Session->setFlash(__('The inspector request has been revoked'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id));
        }
        $this->Session->setFlash(__('Site assignment was not deleted'));
        $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id));
    }
}

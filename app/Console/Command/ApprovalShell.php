<?php
App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
config('routes');
App::uses('Shell', 'Console');
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');

class ApprovalShell extends Shell {
    public $uses = array('User', 'Application', 'Amendment','Review', 'Notification', 'Message', 'Pocket', 'AnnualLetter');

    private function getPrimaryInvestigatorContact($application = array()) {
        if (empty($application['InvestigatorContact']) || !is_array($application['InvestigatorContact'])) {
            return array();
        }

        foreach ($application['InvestigatorContact'] as $contact) {
            if (!empty($contact['investigator_role']) && strtolower($contact['investigator_role']) === 'principal') {
                return $contact;
            }
        }

        return reset($application['InvestigatorContact']);
    }

    private function getInvestigatorFullName($investigator = array()) {
        if (empty($investigator) || !is_array($investigator)) {
            return null;
        }

        $given = isset($investigator['given_name']) ? trim($investigator['given_name']) : '';
        $middle = isset($investigator['middle_name']) ? trim($investigator['middle_name']) : '';
        $family = isset($investigator['family_name']) ? trim($investigator['family_name']) : '';
        $fullName = trim($given . ' ' . $middle . ' ' . $family);

        return ($fullName !== '') ? $fullName : null;
    }
    
    //send email method
    private function  sendEmail($datum = null) {        
        $Email = new CakeEmail();
        //Send Email
        $Email->config('gmail');
        $Email->template('default');
        $Email->emailFormat('html');
        $Email->to($datum['email']);

        // $Email->subject(String::insert($messages['Message']['subject'], $variables));
        $Email->subject($datum['subject']);
        // $Email->viewVars(array('message' => String::insert($messages['Message']['content'], $variables)));
        $Email->viewVars(array('message' => $datum['message']));
        $this->log($Email, 'approval-email');
        if(!$Email->send()) {
            $this->log($Email, 'approval-email');
        }        
    }
    
    public function  sendNotification($datum = null) {
        $save_data = array('Notification' => array(
           'user_id' => $datum['user_id'],
           'type' => $datum['type'],
           'model' => $datum['model'],
           'foreign_key' => $datum['id'],
           'title' => $datum['subject'],
           'system_message' => $datum['message'],
           ),
        );

        $this->log($save_data, 'generic-notification-success');
        //Send notification
        $this->Notification->Create();
        if (!$this->Notification->save($save_data)) {
                 $this->log('Notification '.$save_data['type'].' could not be saved', 'generic-notification-error');
                 $this->log($save_data, 'generic-notification-error');
        }
    }

    public function approval_reminder() {
        // fetch all submitted and approved protocols with annual letter 30 days to expiry and reminder not sent
        //send email and notification to renew application and persist reminder
        //Reminders should not be sent to completed studies
    	$this->out('Starting...');

        $options['conditions'] = array(
                'Application.submitted' => 1, 
                'Application.approved' => 2, 
                'Application.final_date IS ' => null, //Final report not completed
                // 'Application.trial_status_id' => 5,
                'Application.deactivated' => 0,
                ''
        );
        $options['contain'] = array('AnnualLetter' => array('limit' => 1, 'order' => array('AnnualLetter.id DESC')));
        $applications = $this->Application->find('all', $options);

        // Remind applicants and managers approval pending        
        $html = new HtmlHelper(new ThemeView());
        $type = 'reminder_approval_letter ';
        $message = $this->Message->find('first', array('conditions' => array('name' => $type)));
        foreach ($applications as $application) {  
            //***************************       Send Email and Notifications Managers    *****************************
        		$this->out('Application: ' . $application['Application']['user_id']);
            
            $users = $this->User->find('all', array(
                'contain' => array('Group'),
                'conditions' => array('OR' => array('User.id' => $application['Application']['user_id'], 'User.group_id' => 2)) //Applicant and managers
            ));
            foreach ($users as $user) {
            	$this->out('User: ' . $user['User']['name']);
              if (isset($application['AnnualLetter'][0])) {
              	$this->out('AnnualLetter ' . $application['AnnualLetter'][0]['expiry_date']);
              	$variables = array(
		                'name' => $user['User']['name'], 'protocol_no' => $application['Application']['protocol_no'],
		                'protocol_link' => $html->link($application['Application']['protocol_no'], array('controller' => 'applications', 'action' => 'view', $application['Application']['id'], $user['Group']['redir'] => true, 
		                    'full_base' => true), array('escape' => false)),
		                'approval_date' => $application['Application']['approval_date'], 'expiry_date' => $application['AnnualLetter'][0]['expiry_date']
		              );
	              $datum = array(
	                'email' => $user['User']['email'],
	                'id' => $application['Application']['id'], 'user_id' => $user['User']['id'], 'type' => $type, 'model' => 'AnnaulLetter',
	                'subject' => String::insert($message['Message']['subject'], $variables),
	                'message' => String::insert($message['Message']['content'], $variables)
	              );
	              $this->sendEmail($datum);
	              $this->sendNotification($datum);
	              $this->log($datum, 'approval_reminder');
              }              
            }
            //**********************************    END   *********************************
            //end
        }
    }

    public function approval_generate() {
        // fetch all submitted and approved protocols with annual letter period exceeding and 
        //send email and notification to renew application and persist reminder
        //Reminders and approval letters should not be sent to completed studies
        
        $this->out('Starting...');

        $options['conditions'] = array(
                'Application.submitted' => 1, 
                // 'Application.trial_status_id' => 5,
                'Application.deactivated' => 0,
        );
        $options['contain'] = array(
                'AnnualLetter' => array('conditions' => array('expiry_date >' => date('Y-m-d'))), 
                'InvestigatorContact', 
                'AnnualApproval' => array('conditions' => array('year' => date('Y'))), 
                'ApplicationStage');
        $applications = $this->Application->find('all', $options);

        // Notify managers approval generated awaiting approval
        $html = new HtmlHelper(new ThemeView());
        $type = 'manager_approve_letter';
        $message = $this->Message->find('first', array('conditions' => array('name' => $type)));
        foreach ($applications as $application) {
            //Create  annual approval letter
            $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'annual_approval_letter')));

            $checklist = array();

            //check if Application is candidate for annual approval automatic generation
            //1. No active annual letter generated
            //2. All required files uploaded
            $ck = null;
            if (empty($application['AnnualLetter']) && count(array_unique(Hash::extract($application['AnnualApproval'], '{n}.group'))) >= 14) {
                # code...
            } else {                
                //If not candidate, check if active annual letter exists: do nothing
                //if no letter exists, set application stage to expired and remove from public view. Mark as red in Applications and Workflow tables
            
            }


            foreach ($application['AnnualApproval'] as $formdata) {            
                $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
                (isset($checklist[$formdata['pocket_name']])) ? 
                  $checklist[$formdata['pocket_name']] .= $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>' : 
                  $checklist[$formdata['pocket_name']] = $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>';
            }
              $deeds = $this->Pocket->find('list', array(
                'fields' => array('Pocket.name', 'Pocket.content'),
                'conditions' => array('Pocket.type' => 'annual'),
                'recursive' => 0
              ));
              // debug($deeds);
              $checkstring='';
              $cnt = 0;
              foreach ($checklist as $kech => $check) {
                $cnt++;
                $checkstring .= $cnt.'. '.$deeds[$kech].'<br>'.$check;
              }

              $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $application['Application']['id'])));
              $cnt++;
              $year = date('Y', strtotime($this->Application->field('approval_date')));
              $approval_no = 'APL/'.$cnt.'/'.$year.'-'.$application['Application']['protocol_no'];
              $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
              $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
              $qualification = $names = $professional_address = $telephone = null;
              if (!empty($principalInvestigator)) {
                  $qualification = $principalInvestigator['qualification'];
                  $names = $this->getInvestigatorFullName($principalInvestigator);
                  $professional_address = $principalInvestigator['professional_address'];
                  $telephone = $principalInvestigator['telephone'];
              }

              $variables = array(
                  'approval_no' => $approval_no, 'protocol_no' => $application['Application']['protocol_no'], 
                  'letter_date' => date('jS F Y', strtotime($application['Application']['approval_date'])),
                  'qualification' => $qualification,
                  'names' => $names,
                  'professional_address' => $professional_address,
                  'telephone' => $telephone,
                  'study_title' => $application['Application']['short_title'],
                  'checklist' => $checkstring,
                  'status' => $application['TrialStatus']['name'], 
                  'expiry_date' => $expiry_date
              );
              $save_data = array('AnnualLetter' => array(
                      'application_id' => $application['Application']['id'],
                      'approval_no' => $approval_no,
                      'approver' => $this->Session->read('Auth.User.name'),
                      'approval_date' => date('Y-m-d H:i:s'),
                      'expiry_date' => $expiry_date,
                      'status' => 'AnnualApprovalLetter',
                      'content' => String::insert($approval_letter['Pocket']['content'], $variables)
                    ),
                  );
              $this->set('save_data', $save_data);

            //***************************       Send Email and Notifications Managers    *****************************
                $this->out('Application: ' . $application['Application']['user_id']);
            
            $users = $this->User->find('all', array(
                'contain' => array('Group'),
                'conditions' => array('User.group_id' => 2) //Managers
            ));
            foreach ($users as $user) {
                $this->out('User: ' . $user['User']['name']);
              if (isset($application['AnnualLetter'][0])) {
                $this->out('AnnualLetter ' . $application['AnnualLetter'][0]['expiry_date']);
                $variables = array(
                        'name' => $user['User']['name'], 'protocol_no' => $application['Application']['protocol_no'],
                        'protocol_link' => $html->link($application['Application']['protocol_no'], array('controller' => 'applications', 'action' => 'view', $application['Application']['id'], $user['Group']['redir'] => true, 
                            'full_base' => true), array('escape' => false)),
                        'approval_date' => $application['Application']['approval_date'], 'expiry_date' => $application['AnnualLetter'][0]['expiry_date']
                      );
                  $datum = array(
                    'email' => $user['User']['email'],
                    'id' => $application['Application']['id'], 'user_id' => $user['User']['id'], 'type' => $type, 'model' => 'AnnaulLetter',
                    'subject' => String::insert($message['Message']['subject'], $variables),
                    'message' => String::insert($message['Message']['content'], $variables)
                  );
                  $this->sendEmail($datum);
                  $this->sendNotification($datum);
                  $this->log($datum, 'approval_reminder');
              }              
            }
            //**********************************    END   *********************************
            //end
        }
    }

    public function approval_generate_old() {
        // fetch all submitted and approved protocols with annual letter period exceeding and 
        //send email and notification to renew application and persist reminder
        //Reminders should not be sent to completed studies
    	$this->out('Starting...');
        $options['joins'] = array(
            array('table' => 'annual_letters',
                'alias' => 'AnnualLetter',
                'type' => 'INNER',
                'conditions' => array(
                    'Application.id = AnnualLetter.application_id',
                )
            )
        );
        $options['conditions'] = array(
                'Application.submitted' => 1, 
                // 'Application.trial_status_id' => 5,
                'Application.deactivated' => 0,
        );
        $options['contain'] = array('AnnualLetter' => array('limit' => 1, 'order' => array('AnnualLetter.id DESC')), 'InvestigatorContact', 'AnnualApproval');
        $applications = $this->Application->find('all', $options);

        // Notify managers approval generated awaiting approval
        $html = new HtmlHelper(new ThemeView());
        $type = 'manager_approve_letter';
        $message = $this->Message->find('first', array('conditions' => array('name' => $type)));
        foreach ($applications as $application) {
        		//Create  annual approval letter
	          $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'annual_approval_letter')));

	          $checklist = array();
	          foreach ($application['AnnualApproval'] as $formdata) {            
	            $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
	            (isset($checklist[$formdata['pocket_name']])) ? 
	              $checklist[$formdata['pocket_name']] .= $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>' : 
	              $checklist[$formdata['pocket_name']] = $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>';
	          }
	          $deeds = $this->Pocket->find('list', array(
	            'fields' => array('Pocket.name', 'Pocket.content'),
	            'conditions' => array('Pocket.type' => 'annual'),
	            'recursive' => 0
	          ));
	          // debug($deeds);
	          $checkstring='';
	          $cnt = 0;
	          foreach ($checklist as $kech => $check) {
	            $cnt++;
	            $checkstring .= $cnt.'. '.$deeds[$kech].'<br>'.$check;
	          }

	          $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $application['Application']['id'])));
	          $cnt++;
	          $year = date('Y', strtotime($this->Application->field('approval_date')));
	          $approval_no = 'APL/'.$cnt.'/'.$year.'-'.$application['Application']['protocol_no'];
	          $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
	          $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
	          $qualification = $names = $professional_address = $telephone = null;
	          if (!empty($principalInvestigator)) {
	              $qualification = $principalInvestigator['qualification'];
	              $names = $this->getInvestigatorFullName($principalInvestigator);
	              $professional_address = $principalInvestigator['professional_address'];
	              $telephone = $principalInvestigator['telephone'];
	          }

	          $variables = array(
	              'approval_no' => $approval_no, 'protocol_no' => $application['Application']['protocol_no'], 
	              'letter_date' => date('jS F Y', strtotime($application['Application']['approval_date'])),
	              'qualification' => $qualification,
	              'names' => $names,
	              'professional_address' => $professional_address,
	              'telephone' => $telephone,
	              'study_title' => $application['Application']['short_title'],
	              'checklist' => $checkstring,
	              'status' => $application['TrialStatus']['name'], 
	              'expiry_date' => $expiry_date
	          );
	          $save_data = array('AnnualLetter' => array(
	                  'application_id' => $application['Application']['id'],
	                  'approval_no' => $approval_no,
	                  'approver' => $this->Session->read('Auth.User.name'),
	                  'approval_date' => date('Y-m-d H:i:s'),
	                  'expiry_date' => $expiry_date,
	                  'status' => 'AnnualApprovalLetter',
	                  'content' => String::insert($approval_letter['Pocket']['content'], $variables)
	                ),
	              );
	          $this->set('save_data', $save_data);

            //***************************       Send Email and Notifications Managers    *****************************
        		$this->out('Application: ' . $application['Application']['user_id']);
            
            $users = $this->User->find('all', array(
                'contain' => array('Group'),
                'conditions' => array('User.group_id' => 2) //Managers
            ));
            foreach ($users as $user) {
            	$this->out('User: ' . $user['User']['name']);
              if (isset($application['AnnualLetter'][0])) {
              	$this->out('AnnualLetter ' . $application['AnnualLetter'][0]['expiry_date']);
              	$variables = array(
		                'name' => $user['User']['name'], 'protocol_no' => $application['Application']['protocol_no'],
		                'protocol_link' => $html->link($application['Application']['protocol_no'], array('controller' => 'applications', 'action' => 'view', $application['Application']['id'], $user['Group']['redir'] => true, 
		                    'full_base' => true), array('escape' => false)),
		                'approval_date' => $application['Application']['approval_date'], 'expiry_date' => $application['AnnualLetter'][0]['expiry_date']
		              );
	              $datum = array(
	                'email' => $user['User']['email'],
	                'id' => $application['Application']['id'], 'user_id' => $user['User']['id'], 'type' => $type, 'model' => 'AnnaulLetter',
	                'subject' => String::insert($message['Message']['subject'], $variables),
	                'message' => String::insert($message['Message']['content'], $variables)
	              );
	              $this->sendEmail($datum);
	              $this->sendNotification($datum);
	              $this->log($datum, 'approval_reminder');
              }              
            }
            //**********************************    END   *********************************
            //end
        }
    }


}

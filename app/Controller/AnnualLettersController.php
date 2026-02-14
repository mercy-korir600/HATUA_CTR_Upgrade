<?php

 
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');
App::uses('HttpSocket', 'Network/Http');

/**
 * AnnualLetters Controller
 *
 * @property AnnualLetter $AnnualLetter
 */
class AnnualLettersController extends AppController
{
    public $uses = array('User', 'Application', 'Message', 'Pocket', 'AnnualLetter');
     


    public function beforeFilter()
    {
        parent::beforeFilter();
        // $this->Auth->allow();
        $this->Auth->allow('verify','genereateQRCode','manager_download');
    }

    public function manager_download($id=null)
    {
        
    }
    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->AnnualLetter->recursive = 0;
        $this->set('AnnualLetters', $this->paginate());
    }

    public function genereateQRCode($id = null)
    {


        $currentId = base64_encode($id);

        $currentUrl = Router::url('/annual_letters/verify/' . $id, true);

        // debug($currentUrl);
        // exit;
        //   $base64EncodedUrl = $$currentUrl;//base64_encode($currentUrl);

        //    $base64EncodedUrl;
        $options = array(
            'ssl_verify_peer' => false
        );
        $HttpSocket = new HttpSocket($options);

        //Request Access Token
        $initiate = $HttpSocket->post(
            'https://smp.imeja.co.ke/api/qr/generate',
            array('url' => $currentUrl),
            array('header' => array())
        );

        // debug($initiate);
        // exit;
        if ($initiate->isOk()) {

            $body = $initiate->body;
            $resp = json_decode($body, true);
            $this->AnnualLetter->id = $id;
            if (!$this->AnnualLetter->exists()) {
                throw new NotFoundException(__('Invalid annual approval letter'));
            }
            $qr_code = $resp['data']['qr_code'];
            $data = $this->AnnualLetter->read(null, $id);
            $data['AnnualLetter']['qrcode'] = $qr_code;

            $this->AnnualLetter->Create();
            if ($this->AnnualLetter->save($data)) {
            }
        } else {
            $body = $initiate->body;
        }
    }
    public function verify($id = null)
    {
    //    $id =base64_decode($id);
        $this->AnnualLetter->id = $id;
        if (!$this->AnnualLetter->exists()) {
            throw new NotFoundException(__('Invalid annual approval letter'));
        }

        $data = $this->AnnualLetter->read(null, $id);
        $this->pdfConfig = array(
            'filename' => 'ApprovalLetter_' . $id,
            'orientation' => 'portrait',
        );
        $this->set('AnnualLetter', $data);

        $this->render('pdf/download');
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
        $this->AnnualLetter->id = $id;
        if (!$this->AnnualLetter->exists()) {
            throw new NotFoundException(__('Invalid annual approval letter'));
        }
        if (strpos($this->request->url, 'pdf') !== false) {

            $this->pdfConfig = array(
                'filename' => 'ApprovalLetter_' . $id,
                'orientation' => 'portrait',
            );
        } 

        $data = $this->AnnualLetter->find('first',array(
                'contain' => array(),
                'conditions' => array('AnnualLetter.id' => $id)
            )
        ); 
        $this->set('AnnualLetter', $data);
        $this->render('download');
    }
    private function removeAddressLenanaSection($htmlContent)
    {
        $patterns = [
            '/<h3[^>]*>.*?<\/h3>/s', // Remove <h3> section
            '/<p style="text-align: center;">.*?<\/p>/s', // Remove additional section
            '/<address>.*?<\/address>/s',
        ];

        // Remove both specified sections using regular expressions
        foreach ($patterns as $pattern) {
            $htmlContent = preg_replace($pattern, '', $htmlContent);
        }

        return $htmlContent;
    }

    public function applicant_view($id = null)
    {
        $this->view($id);
    }

    public function manager_view($id = null)
    {
        $this->view($id);
    }

    public function admin_view($id = null)
    {
        $this->view($id);
    }

    /**
     * add method
     *
     * @return void
     */
    public function manager_initial($application_id = null)
    {
        //Create  annual approval letter

        $html = new HtmlHelper(new ThemeView());
        $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'initial_approval_letter')));

        $application = $this->Application->find('first', array('conditions' => array('Application.id' => $application_id)));
        //check if application is approved
        if ($application['Application']['approved'] != 2) {
            $this->Session->setFlash(__('Only for approved protocols!!'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        //
        $checklist = array();
        foreach ($application['Checklist'] as $formdata) {
            $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
            (isset($checklist[$formdata['pocket_name']])) ?
                $checklist[$formdata['pocket_name']] .= $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>' :
                $checklist[$formdata['pocket_name']] = $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>';
        }
        $deeds = $this->Pocket->find('list', array(
            'fields' => array('Pocket.name', 'Pocket.content'),
            'conditions' => array('Pocket.type' => 'protocol'),
            'recursive' => 0
        ));
        // debug($deeds);
        $checkstring = '';
        $cnt = 0;
        foreach ($checklist as $kech => $check) {
            $cnt++;
            $checkstring .= $cnt . '. ' . $deeds[$kech] . '<br>' . $check;
        }

        $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $application_id)));
        $cnt++;
        $year = date('Y', strtotime($application['Application']['approval_date']));
        $approval_no = 'APL/' . $cnt . '/' . $year . '-' . $application['Application']['protocol_no'];
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
            'letter_date' => date('j M Y', strtotime($application['Application']['approval_date'])),
            'qualification' => $qualification,
            'names' => $names,
            'professional_address' => $professional_address,
            'telephone' => $telephone,
            'study_title' => $application['Application']['short_title'],
            'checklist' => $checkstring,
            'status' => $application['TrialStatus']['name'],
            'expiry_date' => $expiry_date
        );

        $save_data = array(
            'AnnualLetter' => array(
                'application_id' => $application['Application']['id'],
                'approval_no' => $approval_no,
                'approver' => $this->Session->read('Auth.User.name'),
                'approval_date' => date('d-m-Y'),
                'expiry_date' => date('d-m-Y', strtotime('+1 year')),
                'status' => 'submitted',
                'content' => String::insert($approval_letter['Pocket']['content'], $variables)
            ),
        );

        $this->AnnualLetter->Create();
        if (!$this->AnnualLetter->save($save_data)) {
            $this->Session->setFlash(__('The approval letter could not be saved.'), 'alerts/flash_error');
        } else {

            //**********************  Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission,Final,AnnualApproval stages if not exists
            $id = $application_id;
            $stages = $this->Application->ApplicationStage->find('all', array(
                'contain' => array(),
                'conditions' => array('ApplicationStage.application_id' => $id)
            ));

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array(
                        'ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'Screening', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')
                        )
                    )
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Screening]');
                if (!empty($var)) {
                    $s1['ApplicationStage'] = min($var);
                    if (empty($s1['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s1['ApplicationStage']['status'] = 'Complete';
                        $s1['ApplicationStage']['comment'] = 'Manager final decision';
                        $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s1);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=ScreeningSubmission].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'ScreeningSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ScreeningSubmission]');
                if (!empty($var)) {
                    $s2['ApplicationStage'] = min($var);
                    if (empty($s2['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s2['ApplicationStage']['status'] = 'Complete';
                        $s2['ApplicationStage']['comment'] = 'Manager final decision';
                        $s2['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s2);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'Assign', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                if (!empty($var)) {
                    $s3['ApplicationStage'] = min($var);
                    if (empty($s3['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s3['ApplicationStage']['status'] = 'Complete';
                        $s3['ApplicationStage']['comment'] = 'Manager final decision';
                        $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s3);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'Review', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Review]');
                if (!empty($var)) {
                    $s4['ApplicationStage'] = min($var);
                    if (empty($s4['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s4['ApplicationStage']['status'] = 'Complete';
                        $s4['ApplicationStage']['comment'] = 'Manager final decision';
                        $s4['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s4);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=ReviewSubmission].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'ReviewSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ReviewSubmission]');
                if (!empty($var)) {
                    $s5['ApplicationStage'] = min($var);
                    if (empty($s5['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s5['ApplicationStage']['status'] = 'Complete';
                        $s5['ApplicationStage']['comment'] = 'Manager final decision';
                        $s5['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s5);
                    }
                }
            }


            if (!Hash::check($stages, '{n}.ApplicationStage[stage=FinalDecision].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'FinalDecision', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=FinalDecision]');
                if (!empty($var)) {
                    $s6['ApplicationStage'] = min($var);
                    if (empty($s6['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s6['ApplicationStage']['status'] = 'Complete';
                        $s6['ApplicationStage']['comment'] = $application['Application']['approved'];
                        $s6['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s6);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=AnnualApproval].id')) {
                //create only if approved
                if ($application['Application']['approved'] == 2) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'AnnualApproval', 'status' => 'Current', 'comment' => 'Manager approve', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d', strtotime('+1 year')),
                        ))
                    );
                }
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=AnnualApproval]');
                if (!empty($var)) {
                    $s7['ApplicationStage'] = min($var);
                    if (empty($s7['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s7['ApplicationStage']['status'] = 'Current';
                        $s7['ApplicationStage']['comment'] = 'Manager approve';
                        $s7['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s7);
                    }
                }
            }

            //end stages
            //**********************        end

               // Create a Audit Trail
               $this->loadModel('Application');
               $this->loadModel('AuditTrail');
               $audit = array(
                   'AuditTrail' => array(
                       'foreign_key' => $application_id,
                       'model' => 'Application',
                       'message' => 'An initial annual approval letter for the report with protocol number ' .  $this->Application->field('protocol_no',array('id'=>$application_id)) . ' has been  generated by ' . $this->Session->read('Auth.User.username'),
                       'ip' =>  $this->Application->field('protocol_no',array('id'=>$application_id))
                   )
               );
               $this->AuditTrail->Create();
               if ($this->AuditTrail->save($audit)) {
                   $this->log($this->args[0], 'audit_success');
               } else {
                   $this->log('Error creating an audit trail', 'notifications_error');
                   $this->log($this->args[0], 'notifications_error');
               }

            $this->genereateQRCode($this->AnnualLetter->id);

            $this->Session->setFlash(__('The approval letter has been saved.'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'ane' => $this->AnnualLetter->id));
        }
    }

    public function manager_generate($application_id = null)
    {
        // Notify managers approval generated awaiting approval
        $html = new HtmlHelper(new ThemeView());
        $type = 'manager_approve_letter';
        $message = $this->Message->find('first', array('conditions' => array('name' => $type)));
        $application = $this->Application->find('first', array('conditions' => array('Application.id' => $application_id)));
        //check if application is approved
        if ($application['Application']['approved'] != 2) {
            $this->Session->setFlash(__('Only for approved protocols!!'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        //
        //Create  annual approval letter
        $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'annual_approval_letter')));

        $checklist = array();

        //check if Application is candidate for annual approval automatic generation
        //1. No active annual letter generated
        //2. All required files uploaded
        // $ck = null;
        // if (empty($application['AnnualLetter']) && count(array_unique(Hash::extract($application['AnnualApproval'], '{n}.group'))) >= 14) {
        //     # code...
        // } else {                
        //     //If not candidate, check if active annual letter exists: do nothing
        //     //if no letter exists, set application stage to expired and remove from public view. Mark as red in Applications and Workflow tables

        // }


        foreach ($application['AnnualApproval'] as $formdata) {
            if ($formdata['year'] == date('Y')) {
                $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
                (isset($checklist[$formdata['pocket_name']])) ?
                    $checklist[$formdata['pocket_name']] .= $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>' :
                    $checklist[$formdata['pocket_name']] = $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>';
            }
        }
        $deeds = $this->Pocket->find('list', array(
            'fields' => array('Pocket.name', 'Pocket.content'),
            'conditions' => array('Pocket.type' => 'annual'),
            'recursive' => 0
        ));
        // debug($deeds);
        $checkstring = '';
        $cnt = 0;
        foreach ($checklist as $kech => $check) {
            $cnt++;
            $checkstring .= $cnt . '. ' . $deeds[$kech] . '<br>' . $check;
        }

        $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $application['Application']['id'])));
        $cnt++;
        $year = date('Y', strtotime($application['Application']['approval_date']));
        $approval_no = 'APL/' . $cnt . '/' . $year . '-' . $application['Application']['protocol_no'];
        // $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
        $expiry_date = date('jS F Y', strtotime('+1 year'));
        $principalInvestigator = $this->getPrimaryInvestigatorContact($application);
        $qualification = $names = $professional_address = $telephone = null;
        if (!empty($principalInvestigator)) {
            $qualification = $principalInvestigator['qualification'];
            $names = $this->getInvestigatorFullName($principalInvestigator);
            $professional_address = $principalInvestigator['professional_address'];
            $telephone = $principalInvestigator['telephone'];
        }

        $variables = array(
            'approval_no' => $approval_no, 
            'protocol_no' => $application['Application']['protocol_no'],
            'letter_date' => date('j M Y', strtotime($application['Application']['approval_date'])),
            'qualification' => $qualification,
            'names' => $names,
            'professional_address' => $professional_address,
            'telephone' => $telephone,
            'study_title' => $application['Application']['short_title'],
            'checklist' => $checkstring,
            'status' => $application['TrialStatus']['name'],
            'expiry_date' => $expiry_date
        );
        $save_data = array(
            'AnnualLetter' => array(
                'application_id' => $application['Application']['id'],
                'approval_no' => $approval_no,
                'approver' => $this->Session->read('Auth.User.name'),
                'approval_date' => date('d-m-Y'),
                'expiry_date' => date('d-m-Y', strtotime('+1 year')),
                'status' => 'submitted',
                'content' => String::insert($approval_letter['Pocket']['content'], $variables)
            ),
        );
        // $this->set('save_data', $save_data);

        //***************************       Send Email and Notifications Managers    *****************************

        $users = $this->User->find('all', array(
            'contain' => array('Group'),
            'conditions' => array('User.group_id' => 2) //Managers
        ));
        foreach ($users as $user) {
            if (isset($application['AnnualLetter'][0])) {
                $variables = array(
                    'name' => $user['User']['name'], 'protocol_no' => $application['Application']['protocol_no'],
                    'protocol_link' => $html->link($application['Application']['protocol_no'], array(
                        'controller' => 'applications', 'action' => 'view', $application['Application']['id'], $user['Group']['redir'] => true,
                        'full_base' => true
                    ), array('escape' => false)),
                    'approval_date' => $application['Application']['approval_date'], 'expiry_date' => $application['AnnualLetter'][0]['expiry_date']
                );
                $datum = array(
                    'email' => $user['User']['email'],
                    'id' => $application['Application']['id'], 'user_id' => $user['User']['id'], 'type' => $type, 'model' => 'AnnaulLetter',
                    'subject' => String::insert($message['Message']['subject'], $variables),
                    'message' => String::insert($message['Message']['content'], $variables)
                );
                // $this->sendEmail($datum);
                // $this->sendNotification($datum);
                // $this->log($datum, 'approval_reminder');
                CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
            }
        }
        //**********************************    END   *********************************
        //end
        $this->AnnualLetter->Create();
        if (!$this->AnnualLetter->save($save_data)) {
            $this->Session->setFlash(__('The annual approval letter could not be saved.'), 'alerts/flash_error');
        } else {

            //**********************  Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission,Final,AnnualApproval stages if not exists
            $id = $application_id;
            $stages = $this->Application->ApplicationStage->find('all', array(
                'contain' => array(),
                'conditions' => array('ApplicationStage.application_id' => $id)
            ));

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array(
                        'ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'Screening', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')
                        )
                    )
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Screening]');
                if (!empty($var)) {
                    $s1['ApplicationStage'] = min($var);
                    if (empty($s1['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s1['ApplicationStage']['status'] = 'Complete';
                        $s1['ApplicationStage']['comment'] = 'Manager final decision';
                        $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s1);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=ScreeningSubmission].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'ScreeningSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ScreeningSubmission]');
                if (!empty($var)) {
                    $s2['ApplicationStage'] = min($var);
                    if (empty($s2['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s2['ApplicationStage']['status'] = 'Complete';
                        $s2['ApplicationStage']['comment'] = 'Manager final decision';
                        $s2['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s2);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'Assign', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                if (!empty($var)) {
                    $s3['ApplicationStage'] = min($var);
                    if (empty($s3['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s3['ApplicationStage']['status'] = 'Complete';
                        $s3['ApplicationStage']['comment'] = 'Manager final decision';
                        $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s3);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'Review', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Review]');
                if (!empty($var)) {
                    $s4['ApplicationStage'] = min($var);
                    if (empty($s4['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s4['ApplicationStage']['status'] = 'Complete';
                        $s4['ApplicationStage']['comment'] = 'Manager final decision';
                        $s4['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s4);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=ReviewSubmission].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'ReviewSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ReviewSubmission]');
                if (!empty($var)) {
                    $s5['ApplicationStage'] = min($var);
                    if (empty($s5['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s5['ApplicationStage']['status'] = 'Complete';
                        $s5['ApplicationStage']['comment'] = 'Manager final decision';
                        $s5['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s5);
                    }
                }
            }


            if (!Hash::check($stages, '{n}.ApplicationStage[stage=FinalDecision].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array('ApplicationStage' => array(
                        'application_id' => $id, 'stage' => 'FinalDecision', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                    ))
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=FinalDecision]');
                if (!empty($var)) {
                    $s6['ApplicationStage'] = min($var);
                    if (empty($s6['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s6['ApplicationStage']['status'] = 'Complete';
                        $s6['ApplicationStage']['comment'] = $application['Application']['approved'];
                        $s6['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s6);
                    }
                }
            }

            if (!Hash::check($stages, '{n}.ApplicationStage[stage=AnnualApproval].id')) {
                //create only if approved
                if ($application['Application']['approved'] == 2) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'AnnualApproval', 'status' => 'Current', 'comment' => 'Manager approve', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d', strtotime('+1 year')),
                        ))
                    );
                }
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=AnnualApproval]');
                if (!empty($var)) {
                    $s7['ApplicationStage'] = min($var);
                    if (empty($s7['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s7['ApplicationStage']['status'] = 'Current';
                        $s7['ApplicationStage']['comment'] = 'Manager approve';
                        $s7['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s7);
                    }
                }
            }

            // Create a Audit Trail
            $this->loadModel('Application');
            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application_id,
                    'model' => 'Application',
                    'message' => 'An annual approval letter for the report with protocol number ' .  $this->Application->field('protocol_no',array('id'=>$application_id)) . ' has been  generated by ' . $this->Session->read('Auth.User.username'),
                    'ip' =>  $this->Application->field('protocol_no',array('id'=>$application_id))
                )
            );
            $this->AuditTrail->Create();
            if ($this->AuditTrail->save($audit)) {
                $this->log($this->args[0], 'audit_success');
            } else {
                $this->log('Error creating an audit trail', 'notifications_error');
                $this->log($this->args[0], 'notifications_error');
            }

            //end stages
            //**********************        end
            $this->genereateQRCode($this->AnnualLetter->id);

            $this->Session->setFlash(__('The approval letter has been saved.'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'ane' => $this->AnnualLetter->id));
        }
    }

    public function admin_letter_upload($application_id = null)
    {
        $this->AnnualLetter->create();
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
            'contain' => array('AnnualLetter')
        ));


        if ($this->request->is('post') || $this->request->is('put')) {
            $cnt = $this->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $application['Application']['id'])));
            $cnt++;
            $year = date('Y', strtotime($application['Application']['approval_date']));

            $this->request->data['AnnualLetter']['approval_no'] = 'APL/' . $cnt . '/' . $year . '-' . $application['Application']['protocol_no'];
            $this->request->data['AnnualLetter']['application_id'] = $application['Application']['id'];
            // debug($this->request->data);
            // exit;
            if ($this->AnnualLetter->save($this->request->data)) {


                //**********************  Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission,Final,AnnualApproval stages if not exists      
                $id = $application_id;
                $stages = $this->Application->ApplicationStage->find('all', array(
                    'contain' => array(),
                    'conditions' => array('ApplicationStage.application_id' => $id)
                ));

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array(
                            'ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'Screening', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')
                            )
                        )
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Screening]');
                    if (!empty($var)) {
                        $s1['ApplicationStage'] = min($var);
                        if (empty($s1['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s1['ApplicationStage']['status'] = 'Complete';
                            $s1['ApplicationStage']['comment'] = 'Manager final decision';
                            $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s1);
                        }
                    }
                }

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=ScreeningSubmission].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'ScreeningSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                        ))
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ScreeningSubmission]');
                    if (!empty($var)) {
                        $s2['ApplicationStage'] = min($var);
                        if (empty($s2['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s2['ApplicationStage']['status'] = 'Complete';
                            $s2['ApplicationStage']['comment'] = 'Manager final decision';
                            $s2['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s2);
                        }
                    }
                }

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'Assign', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                        ))
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                    if (!empty($var)) {
                        $s3['ApplicationStage'] = min($var);
                        if (empty($s3['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s3['ApplicationStage']['status'] = 'Complete';
                            $s3['ApplicationStage']['comment'] = 'Manager final decision';
                            $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s3);
                        }
                    }
                }

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'Review', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                        ))
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Review]');
                    if (!empty($var)) {
                        $s4['ApplicationStage'] = min($var);
                        if (empty($s4['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s4['ApplicationStage']['status'] = 'Complete';
                            $s4['ApplicationStage']['comment'] = 'Manager final decision';
                            $s4['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s4);
                        }
                    }
                }

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=ReviewSubmission].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'ReviewSubmission', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                        ))
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ReviewSubmission]');
                    if (!empty($var)) {
                        $s5['ApplicationStage'] = min($var);
                        if (empty($s5['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s5['ApplicationStage']['status'] = 'Complete';
                            $s5['ApplicationStage']['comment'] = 'Manager final decision';
                            $s5['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s5);
                        }
                    }
                }


                if (!Hash::check($stages, '{n}.ApplicationStage[stage=FinalDecision].id')) {
                    $this->Application->ApplicationStage->create();
                    $this->Application->ApplicationStage->save(
                        array('ApplicationStage' => array(
                            'application_id' => $id, 'stage' => 'FinalDecision', 'status' => 'Complete', 'comment' => 'Manager final decision', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                        ))
                    );
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=FinalDecision]');
                    if (!empty($var)) {
                        $s6['ApplicationStage'] = min($var);
                        if (empty($s6['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s6['ApplicationStage']['status'] = 'Complete';
                            $s6['ApplicationStage']['comment'] = $application['Application']['approved'];
                            $s6['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s6);
                        }
                    }
                }

                if (!Hash::check($stages, '{n}.ApplicationStage[stage=AnnualApproval].id')) {
                    //create only if approved
                    if ($application['Application']['approved'] == 2) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'AnnualApproval', 'status' => 'Current', 'comment' => 'Manager approve', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d', strtotime('+1 year')),
                            ))
                        );
                    }
                } else {
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=AnnualApproval]');
                    if (!empty($var)) {
                        $s7['ApplicationStage'] = min($var);
                        if (empty($s7['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s7['ApplicationStage']['status'] = 'Current';
                            $s7['ApplicationStage']['comment'] = 'Admin approve';
                            $s7['ApplicationStage']['end_date'] = date('Y-m-d', strtotime($this->request->data['AnnualLetter']['expiry_date']));
                            $this->Application->ApplicationStage->save($s7);
                        } elseif (strtotime($this->request->data['AnnualLetter']['expiry_date']) > strtotime($s7['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s7['ApplicationStage']['status'] = 'Current';
                            $s7['ApplicationStage']['comment'] = 'Admin approve';
                            $s7['ApplicationStage']['end_date'] = date('Y-m-d', strtotime($this->request->data['AnnualLetter']['expiry_date']));
                            $this->Application->ApplicationStage->save($s7);
                        }
                    }
                }

                //end stages
                //**********************        end

                $this->Session->setFlash(__('The annual approval letter has been saved'), 'alerts/flash_success');
                $this->redirect(array('action' => 'letter_upload', $application['Application']['id'], 'anl' => $this->AnnualLetter->id, 'admin' => true));
            } else {
                $this->Session->setFlash(__('The annual approval letter could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        }
        $this->set(compact('application'));
    }

    public function manager_add($application_id = null, $type = null)
    {
        // $this->AnnualLetter->create();
        // if ($this->AnnualLetter->save($this->request->data)) {
        // 	$this->Session->setFlash(__('The annual approval letter has been saved'));
        // 	$this->redirect(array('action' => 'index'));
        // } else {
        // 	$this->Session->setFlash(__('The annual approval letter could not be saved. Please, try again.'));
        // }
        //Create  annual approval letter                 
        $this->loadModel('Pocket');
        $this->loadModel('Application');
        $html = new HtmlHelper(new ThemeView());
        $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'initial_approval_letter')));

        $application = $this->Application->find('first', array('conditions' => array('Application.id' => $application_id)));
        $checklist = array();
        foreach ($application['Checklist'] as $formdata) {
            $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
            (isset($checklist[$formdata['pocket_name']])) ?
                $checklist[$formdata['pocket_name']] .= $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>' :
                $checklist[$formdata['pocket_name']] = $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>';
        }
        $deeds = $this->Pocket->find('list', array(
            'fields' => array('Pocket.name', 'Pocket.content'),
            'conditions' => array('Pocket.type' => 'protocol'),
            'recursive' => 0
        ));
        $checkstring = '';
        $num = 0;
        foreach ($checklist as $kech => $check) {
            $num++;
            $checkstring .= $num . '. ' . $deeds[$kech] . '<br>' . $check;
        }

        $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('date_format(AnnualLetter.created, "%Y")' => date("Y"))));
        $cnt++;
        $year = date('Y', strtotime($this->Application->field('approval_date')));
        $approval_no = 'PPB/' . $application['Application']['protocol_no'] . "/$year" . "($cnt)";
        $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
        $expiry_date_s = date('Y-m-d', strtotime($application['Application']['approval_date'] . " +1 year"));
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
            'letter_date' => date('j M Y', strtotime($application['Application']['approval_date'])),
            'qualification' => $qualification,
            'names' => $names,
            'professional_address' => $professional_address,
            'telephone' => $telephone,
            'study_title' => $application['Application']['short_title'],
            'checklist' => $checkstring,
            'expiry_date' => $expiry_date
        );

        $save_data = array(
            'AnnualLetter' => array(
                'application_id' => $application['Application']['id'],
                'approval_no' => $approval_no,
                'approver' => $this->Session->read('Auth.User.name'),
                'approval_date' => date('Y-m-d H:i:s'),
                'expiry_date' => $expiry_date_s,
                'status' => 'submitted',
                'content' => String::insert($approval_letter['Pocket']['content'], $variables)
            ),
        );
        $this->AnnualLetter->Create();
        if (!$this->AnnualLetter->save($save_data)) {
            $this->log('Annual approval letter was not saved!!', 'annual_letter_error');
            $this->log($save_data, 'annual_letter_error');
        }
        $this->redirect(array('controller' => 'applications', 'action' => 'view', 1,));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->AnnualLetter->id = $id;
        if (!$this->AnnualLetter->exists()) {
            throw new NotFoundException(__('Invalid annual approval letter'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->AnnualLetter->save($this->request->data)) {
                $this->Session->setFlash(__('The annual approval letter has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The annual approval letter could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->AnnualLetter->read(null, $id);
        }
        $applications = $this->AnnualLetter->Application->find('list');
        $this->set(compact('applications'));
    }

    public function manager_approve($id = null)
    {
        $this->AnnualLetter->id = $id;
        if (!$this->AnnualLetter->exists()) {
            throw new NotFoundException(__('Invalid annual approval letter'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $anl = $this->AnnualLetter->find('first', array('contain' => array('Application'), 'conditions' => array('AnnualLetter.id' => $id)));
            $wasApproved = ($anl['AnnualLetter']['status'] === 'approved');
            $isDraftSave = isset($this->request->data['saveDraft']);
            $targetStatus = ($isDraftSave && !$wasApproved) ? 'draft' : 'approved';

            $this->request->data['AnnualLetter']['id'] = $id;
            $this->request->data['AnnualLetter']['status'] = $targetStatus;

            if ($this->AnnualLetter->save($this->request->data)) {
                if ($targetStatus !== 'approved') {
                    $this->Session->setFlash(__('The annual approval letter draft has been saved'), 'alerts/flash_success');
                    return $this->redirect(array('controller' => 'applications', 'action' => 'view', $anl['Application']['id'], 'ane' => $id, 'manager' => true));
                }

                // Skip notifications and workflow updates when editing an already submitted letter.
                if (!$wasApproved) {
                    //******************       Send Email and Notifications to Applicant and Managers    *****************************
                    $this->loadModel('Message');
                    $html = new HtmlHelper(new ThemeView());
                    $message = $this->Message->find('first', array('conditions' => array('name' => 'annual_approval_letter')));
                    $anl = $this->AnnualLetter->find('first', array('contain' => array('Application'), 'conditions' => array('AnnualLetter.id' => $this->AnnualLetter->id)));

                    $users = $this->AnnualLetter->Application->User->find('all', array(
                        'contain' => array('Group'),
                        'conditions' => array('OR' => array('User.id' => $this->AnnualLetter->Application->field('user_id'), 'User.group_id' => 2)) //Applicant and managers
                    ));
                    foreach ($users as $user) {
                        $variables = array(
                            'name' => $user['User']['name'], 'approval_no' => $anl['AnnualLetter']['approval_no'], 'protocol_no' => $anl['Application']['protocol_no'],
                            'protocol_link' => $html->link($anl['Application']['protocol_no'], array(
                                'controller' => 'applications', 'action' => 'view', $anl['Application']['id'], $user['Group']['redir'] => true,
                                'full_base' => true
                            ), array('escape' => false)),
                            'expiry_date' => $anl['AnnualLetter']['expiry_date'],
                            'approval_date' => $anl['AnnualLetter']['approval_date']
                        );
                        $datum = array(
                            'email' => $user['User']['email'],
                            'id' => $id, 'user_id' => $user['User']['id'], 'type' => 'annual_approval_letter', 'model' => 'AnnaulLetter',
                            'subject' => String::insert($message['Message']['subject'], $variables),
                            'message' => String::insert($message['Message']['content'], $variables)
                        );
                        CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                        CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                    }
                    //**********************************    END   *********************************

                    //**********************  Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission,Final,AnnualApproval stages if not exists
                    $stages = $this->Application->ApplicationStage->find('all', array(
                        'contain' => array(),
                        'conditions' => array('ApplicationStage.application_id' => $anl['Application']['id'])
                    ));

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=AnnualApproval].id')) {
                        //create only if approved
                        if ($anl['Application']['approved'] == 2) {
                            $this->Application->ApplicationStage->create();
                            $this->Application->ApplicationStage->save(
                                array('ApplicationStage' => array(
                                    'application_id' => $anl['Application']['id'], 'stage' => 'AnnualApproval', 'status' => 'Current', 'comment' => 'Manager approve', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d', strtotime('+1 year')),
                                ))
                            );
                        }
                    } else {
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=AnnualApproval]');
                        if (!empty($var)) {
                            $s7['ApplicationStage'] = min($var);
                            $this->Application->ApplicationStage->create();
                            $s7['ApplicationStage']['status'] = 'Current';
                            $s7['ApplicationStage']['comment'] = 'Manager letter approve';
                            $s7['ApplicationStage']['end_date'] = date('Y-m-d', strtotime($anl['AnnualLetter']['expiry_date']));
                            $this->Application->ApplicationStage->save($s7);
                        }
                    }
                    //end stages
                    //**********************        end

                    // Create a Audit Trail
                    $this->loadModel('Application');
                    $this->loadModel('AuditTrail');
                    $audit = array(
                        'AuditTrail' => array(
                            'foreign_key' => $anl['Application']['id'],
                            'model' => 'Application',
                            'message' => 'An annual approval letter for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $anl['Application']['id'])) . ' has been  approved by ' . $this->Session->read('Auth.User.username'),
                            'ip' =>  $this->Application->field('protocol_no', array('id' => $anl['Application']['id']))
                        )
                    );
                    $this->AuditTrail->Create();
                    if ($this->AuditTrail->save($audit)) {
                        $this->log($this->args[0], 'audit_success');
                    } else {
                        $this->log('Error creating an audit trail', 'notifications_error');
                        $this->log($this->args[0], 'notifications_error');
                    }

                    $this->Session->setFlash(__('The annual approval letter has been saved'), 'alerts/flash_success');
                } else {
                    $this->Session->setFlash(__('The submitted annual approval letter has been updated'), 'alerts/flash_success');
                }
                return $this->redirect(array('controller' => 'applications', 'action' => 'view', $anl['Application']['id'], 'anl' => $id, 'manager' => true));
            } else {
                $this->Session->setFlash(__('The annual approval letter could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        }
        return $this->redirect($this->referer());
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
        $this->AnnualLetter->id = $id;
        if (!$this->AnnualLetter->exists()) {
            throw new NotFoundException(__('Invalid annual approval letter'));
        }
        if ($this->AnnualLetter->delete()) {
            $this->Session->setFlash(__('annual approval letter deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('annual approval letter was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}

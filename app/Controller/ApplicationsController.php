<?php
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('HttpSocket', 'Network/Http');


/**
 * Applications Controller
 *
 * @property Application $Application
 */
class ApplicationsController extends AppController
{

    public $paginate = array();
    public $components = array('Search.Prg');
    public $presetVars = true;

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow('index', 'admin_extra', 'report_invoice', 'applicant_submitall', 'admin_suspend', 'manager_amendment_summary', 'genereateQRCode', 'manager_stages_summary', 'view', 'view.pdf', 'apl',  'study_title', 'myindex', 'download_invoice');

        // $this->Security->unlockedFields = array('submit_type');
    }
    public function admin_extra($id = null)
    {

        $data = $this->request->data['Application'];
        $total_sites = $data['total_sites'];

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('SiteDetail', 'User', 'InvestigatorContact')
        ));
        if ($application) {
            // debug($application); 
            $invoice = array(
                'function' => 'ppbNewApplication',
                'Application' => array(
                    'id' => $application['Application']['id'],
                    'name' => $application['User']['name'],
                    'email' => $application['User']['email'],
                    'total_sites' => $total_sites,
                    'protocol_no' =>  $application['Application']['protocol_no']
                )
            );

            CakeResque::enqueue('default', 'NotificationShell', array('generate_report_invoice', $invoice));
        }
        $this->Session->setFlash(__('An additional site has been created and invoice sent'), 'alerts/flash_success');
        $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
    }

    public function generateMissedInvoice($id)
    {

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('SiteDetail', 'User', 'InvestigatorContact')
        ));
        if ($application) {
            // debug($application);
            $invoice = array(
                'function' => 'ppbNewApplication',
                'Application' => array(
                    'id' => $application['Application']['id'],
                    'name' => $application['User']['name'],
                    'email' => $application['User']['email'],
                    'protocol_no' =>  $application['Application']['protocol_no']
                )
            );

            CakeResque::enqueue('default', 'NotificationShell', array('generate_report_invoice', $invoice));
        }
        $this->Session->setFlash(__('The outsourced request has been revoked'), 'alerts/flash_success');
        $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
    }

    public function report_invoice($id = null)
    {
        // $id = 1434;//  
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('SiteDetail', 'User', 'InvestigatorContact')
        ));
        if ($application) {
            $options = array('ssl_verify_peer' => false);
            $HttpSocket = new HttpSocket($options);

            $user = $application['User'];
            //PRINCIPAL INVESTIGATOR
            $multiArray = $application['InvestigatorContact'];
            $firstEntry = reset($multiArray);
            $name = $firstEntry['given_name'] . ' ' . $firstEntry['family_name'];
            $billDesc = "Principal Investigator: " . $name . "<br>Study Title: " . $application['Application']['short_title'];
            $this->log('initiated report', $user, 'e-citizen-initiate-user');



            $header_options = array(
                'header' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded'
                )
            );
            $postDataToken = array(
                'APPID' => 'e4da3b7fbbce2345d7772b0674a318d5',
                'APIKEY' => 'MjU0Yjg5ZmRiYzkyNTMwN2UyZWIyZjI3ZTI0NmRiMmU1NmU4NmMzYQ==',
            );

            $formDataToken = http_build_query($postDataToken);
            // //Request Access Token
            $initiate = $HttpSocket->post(
                'https://invoices.pharmacyboardkenya.org/token',
                $formDataToken,
                $header_options
            );
            $this->log('process initiation' . $initiate, 'e-citizen-initiate-token');
            if ($initiate->isOk()) {
                $body = $initiate->body;
                $resp = json_decode($body, true);
                $this->log($resp, 'e-citizen-token-success');
                $session_token = $resp['session_token'];
                // $total_sites= $application['Application']['total_sites']; 

                $total_sites = isset($application['Application']['total_sites']) && $application['Application']['total_sites'] !== null
                    ? $application['Application']['total_sites']
                    : 1; // Default to 1 if total_sites is null
                $invoice_total = 1000 *  $total_sites; /// calculated based on the number of sites::::
                $postData = array(
                    'payment_type' => 'Clinical_Trials', // Types are issued e.g. Clinical_Trials  
                    'session_token' => $session_token, // from above  $application['Application']['short_title']
                    'billDesc' => $billDesc,
                    'currency' => 'USD',
                    'clientMSISDN' => $user['phone_no'],
                    'clientName' => $user['name'],
                    'clientIDNumber' => $user['national_id_number'],
                    'clientEmail' => $user['email'],
                    'amountExpected' => $invoice_total
                );
                $header_options = array(
                    'header' => array(
                        'Content-Type' => 'application/x-www-form-urlencoded'
                    )
                );
                $formData = http_build_query($postData);

                // $next = $HttpSocket->post('https://invoices.pharmacyboardkenya.org/ct_invoice/generate', $formData, $header_options);


                $next = $HttpSocket->post('https://invoices.pharmacyboardkenya.org/ecitizen_invoice/generate', $formData, $header_options);

                if ($next->isOk()) {
                    $body1 = $next->body;
                    $resp1 = json_decode($body1, true);
                    $invoice_id = $resp1['invoice_id']; //[0]; //Default test:::: 285251
                    // debug($invoice_id);
                    // exit;
                    $payment_code = $resp1['ppb_reference_code']; //[1];
                    $this->Application->id = $id;
                    if ($this->Application->saveField('ecitizen_invoice', $invoice_id)) {
                        $raw_id = base64_encode($invoice_id);
                        $prims = $HttpSocket->get('https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=' . $invoice_id, false, $options);


                        // debug($prims);
                        // exit;
                        if ($prims->isOk()) {
                            $body2 = $prims->body;
                            $resp2 = json_decode($body2, true);
                            $data = array(
                                'secureHash' => $resp2["secureHash"],
                                'apiClientID' => 42,
                                'serviceID' => $resp2["serviceID"],
                                'notificationURL' => 'https://practice.pharmacyboardkenya.org/ipn?id=' . $raw_id,
                                'callBackURLOnSuccess' => 'https://practice.pharmacyboardkenya.org/callback?id=' . $raw_id,
                                'billRefNumber' => $resp2["billRefNumber"],
                                'currency' => $resp2["currency"],
                                'amountExpected' => $resp2["amountExpected"],
                                'billDesc' => $resp2["billDesc"],
                                'pictureURL' => '', //$resp2["pictureURL"],
                                'clientName' => $resp2["clientName"],
                                'clientEmail' => $resp2["clientEmail"],
                                'clientMSISDN' => $resp2["clientMSISDN"],
                                'clientIDNumber' => $resp2["clientIDNumber"],
                            );

                            $payload = http_build_query($data);
                            $ecitizen = $HttpSocket->post('https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php', $payload, $header_options);
                            //   debug($ecitizen);
                            // exit;
                            if ($ecitizen->isOk()) {
                            }
                        }

                        //<!-- Send email to applicant -->
                        debug($raw_id);
                        exit;
                        $variables = array(
                            'protocol_link' => '<a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $raw_id . '">Click here to view invoice</a>',
                            'protocol_no' => $application['Application']['protocol_no'],
                            'name' => $user['name']
                        );

                        // $messages = $this->Message->find('list', array(
                        //     'conditions' => array('Message.name' => array(
                        //         'applicant_invoice_email',
                        //         'applicant_invoice_email_subject'
                        //     )),
                        //     'fields' => array('Message.name', 'Message.content')
                        // ));
                        // $message = String::insert($messages['applicant_invoice_email'], $variables);
                        // $email = new CakeEmail();
                        // $email->config('gmail');
                        // $email->template('default');
                        // $email->emailFormat('html');
                        // $email->to($user['email']);
                        // $email->bcc(array('itsjkiprotich@gmail.com'));
                        // $email->subject(Sanitize::html(String::insert($messages['applicant_invoice_email_subject'], $variables), array('remove' => true)));
                        // $email->viewVars(array('message' => $message));
                        //   if (!$email->send()) {
                        //     $this->log($email, 'submit_email');
                        //   }
                        debug('success');
                        exit;
                    } else {
                        $this->log('saved application', 'e-citizen-error_saved');
                    }
                } else {
                    $this->log('Failed to generate invoice', 'e-citizen-error');
                }
            } else {
                $this->log('Failed to retrive token', 'e-citizen-error');
            }
        } else {
            $this->log('sample application', 'test-app-error');
        }
    }

    public function applicant_create()
    {
        $this->loadModel('User');
        if ($this->request->is('post')) {
            $user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->User('id'))));
            if (!empty($user['User']['sponsor_email'])) {
                $this->request->data['Application']['user_id'] = $this->Auth->User('id');


                if (!isset($this->request->data['Application']['total_sites']) || empty($this->request->data['Application']['total_sites'])) {
                    $this->Session->setFlash(__('Please provide  number of sites.'), 'alerts/flash_error');
                    $this->redirect($this->referer());
                }
                if (!isset($this->request->data['Application']['short_title']) || empty($this->request->data['Application']['short_title'])) {
                    $this->Session->setFlash(__('Please provide  short title for the study.'), 'alerts/flash_error');
                    $this->redirect($this->referer());
                }
                // Extract the parent data
                $parentData = $this->request->data['Application'];

                $total_sites = $this->request->data['Application']['total_sites'];

                // Extract the associated data
                $associatedData = $this->request->data;
                unset($associatedData['Application']);

                // Temporarily save the parent data without validation
                $this->Application->create();
                if ($this->Application->save($parentData, array('validate' => false))) {
                    $parentId = $this->Application->id;

                    // Attach the parent ID to the associated data
                    foreach ($associatedData as $model => $modelData) {
                        if (!empty($modelData)) {
                            foreach ($modelData as $data) {
                                $data['application_id'] = $parentId; // Assuming the foreign key is 'application_id'
                            }
                        }
                    }

                    // Validate each associated model individually
                    $isValid = true;
                    foreach ($associatedData as $model => $modelData) {
                        if (!empty($modelData)) {
                            foreach ($modelData as $data) {
                                $this->Application->$model->create($data);
                                if (!$this->Application->$model->validates()) {
                                    $isValid = false;
                                    break 2; // Exit both foreach loops
                                }
                            }
                        }
                    }

                    if ($isValid) {
                        // Save each associated model individually
                        $success = true;
                        foreach ($associatedData as $model => $modelData) {
                            if (!empty($modelData)) {
                                foreach ($modelData as $data) {
                                    $this->Application->$model->create($data);
                                    if (!$this->Application->$model->save(null, array('validate' => false))) {
                                        $success = false;
                                        break 2; // Exit both foreach loops
                                    }
                                }
                            }
                        }

                        if ($success) {

                            // Generate Invoice in the Background
                            // if(empty($this->request->data['Application']['total_sites']))
                            // {
                            //     $this->Session->setFlash(__('Please enter number of sites. Please, correct the errors.'), 'alerts/flash_warning');

                            // }

                            $invoice = array(
                                'function' => 'ppbNewApplication',
                                'Application' => array(
                                    'id' => $this->Application->id,
                                    'total_sites' => $total_sites,
                                    'name' => $this->Auth->user('name'),
                                    'email' => $this->Auth->user('email'),
                                    'protocol_no' =>  $this->Application->protocol_no
                                )
                            );
                            $result =  CakeResque::enqueue('default', 'NotificationShell', array('generate_report_invoice', $invoice));

// debug($result);
// exit;
                            $this->Session->setFlash(__('The application has been created, An Invoice has been sent to your email with the invoice details'), 'alerts/flash_success');
                            $this->redirect(array('controller' => 'applications', 'action' => 'applicant_edit', $this->Application->id));
                        } else {
                            $this->Session->setFlash(__('The associated data could not be saved. Please, try again.'), 'alerts/flash_warning');
                        }
                    } else {
                        $this->Session->setFlash(__('The associated data validation failed. Please, correct the errors.'), 'alerts/flash_warning');
                    }
                } else {
                    $this->Session->setFlash(__('The application could not be saved. Please, try again.'), 'alerts/flash_warning');
                }
            } else {
                $this->Session->setFlash(__('Please update the sponsor\'s email before creating an application.'), 'alerts/flash_warning');
                $this->redirect(array('controller' => 'users', 'action' => 'edit', 'admin' => false));
            }
        }
    }
    public function applicant_revoke_assignment($id = null, $application_id)
    {

        $this->loadModel('Outsource');
        $this->loadModel('AuditTrail');


        $this->Outsource->id = $id;
        if (!$this->Outsource->exists()) {
            throw new NotFoundException(__('Invalid Assignment'));
        }
        $app = $this->Outsource->read(null, $id);
        if ($this->Outsource->delete()) {

            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application_id,
                    'model' => 'Application',
                    'message' => 'Outsourced assigned to ' . $app['User']['username'] . ' for the protocol with reference number ' . $app['Application']['protocol_no'] . ' has been revorked by ' . $this->Auth->user('name'),
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
            $this->Session->setFlash(__('The outsourced request has been revoked'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id));
        }
        $this->Session->setFlash(__('outsourced assignment was not revorked'));
        $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id));
    }

    public function applicant_assign_other_protocol($id = null)
    {
        $this->loadModel('OutsourceRequest');
        $this->loadModel('User');
        if (!isset($this->request->data['Attachment']) || empty($this->request->data['Attachment'])) {
            $this->Session->setFlash(__('Please upload at least one file.'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }

        if ($this->request->is('post')) {
        }
        $this->OutsourceRequest->Create();
        // if ($this->Outsoerererrrurce->save($this->request->data['Outsource'], array('validate' => true, 'deep' => true))) {
        if ($this->OutsourceRequest->saveAssociated($this->request->data, array('validate' => true, 'deep' => true))) {
        }


        $this->Session->setFlash(__('Request submitted for further processing'), 'alerts/flash_success');
        $this->redirect($this->referer());
    }
    public function applicant_assign_protocol($id)
    {
        $this->loadModel('Outsource');
        $this->loadModel('User');

        if ($this->request->is('post')) {

            if (isset($this->request->data['Outsource']['email']) && !empty($this->request->data['Outsource']['email'])) {
                $parts = explode('@', $this->request->data['Outsource']['email']);
                $this->request->data['Outsource']['username'] = $parts[0];
            }

            if (!isset($this->request->data['Attachment']) || empty($this->request->data['Attachment'])) {
                $this->Session->setFlash(__('Please upload at least one file.'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
            $this->Outsource->Create();
            // if ($this->Outsoerererrrurce->save($this->request->data['Outsource'], array('validate' => true, 'deep' => true))) {
            if ($this->Outsource->saveAssociated($this->request->data, array('validate' => true, 'deep' => true))) {

                // Notify the Admins

                $app = $this->Application->read(null, $id);
                $data = array(
                    'function' => 'outsourceApplication',
                    'Application' => array(
                        'id' => $this->Outsource->id,
                        'protocol_no' => $app['Application']['protocol_no'],

                    )
                );
                CakeResque::enqueue('default', 'NotificationShell', array('outsourceApplication', $data));

                // Create a Audit Trail
                $audit = array(
                    'AuditTrail' => array(
                        'foreign_key' => $id,
                        'model' => 'Application',
                        'message' => 'New outsource request for the Protocol with protocol number ' . $app['Application']['protocol_no'] . ' has been submitted by ' . $this->Auth->user('username'),
                        'ip' => $app['Application']['protocol_no']
                    )
                );
                $this->loadModel('AuditTrail');
                $this->AuditTrail->Create();
                if ($this->AuditTrail->save($audit)) {
                    $this->log($this->request->data, 'audit_success');
                } else {
                    $this->log('Error creating an audit trail', 'notifications_error');
                    $this->log($this->request->data, 'notifications_error');
                }

                // End of Notification
                $this->Session->setFlash(__('Request submitted for further processing'), 'alerts/flash_success');
                $this->redirect($this->referer());
            } else {
                // $this->Session->setFlash(__('Failed to submit the request, please try again'), 'alerts/flash_error');
                $validationErrors = $this->Outsource->validationErrors;

                // Concatenate validation errors into a single string
                $errorMessage = 'Failed to submit the request. Please correct the following errors: <br>';
                foreach ($validationErrors as $field => $errors) {
                    foreach ($errors as $error) {
                        $errorMessage .= $error . ' <br>';
                    }
                }

                // Set flash message with validation errors
                $this->Session->setFlash(__($errorMessage), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
            // }
        }
    }

    public function manager_verify_invoice($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $application = $this->Application->read(null, $id);
        $invoice_id = $application['Application']['ecitizen_invoice'];

        $options = array(
            'ssl_verify_peer' => false
        );
        $raw_id = base64_encode($invoice_id);

        $HttpSocket = new HttpSocket($options);
        $prims = $HttpSocket->get('https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=' . $invoice_id, false, $options);

        if ($prims->isOk()) {
            $body2 = $prims->body;
            $resp2 = json_decode($body2, true);

            $this->Session->setFlash(__('Invoice Details Retrieved Successfully.'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $id, 'invoice' => $raw_id, 'data' => $resp2));
        } else {

            $this->Session->setFlash(__('Experience problems connecting to remote server.'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
    }
    public function download_invoice($id) {}

    public function applicant_submitall($id, $year)
    {
        // debug($year);
        // exit;

        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        // get latest AmendmentChecklist

        $this->loadModel('Attachment');
        $latest = $this->Attachment->find('all', array(
            'fields' => array('Attachment.year', 'Attachment.foreign_key', 'Attachment.version_no', 'Attachment.file_date'),
            'conditions' => array('Attachment.foreign_key' => $id, 'Attachment.model' => 'AmendmentChecklist', 'Attachment.year' => $year),
            'recursive' => 0
        ));

        // debug($latest);
        // exit;
        if (!empty($latest)) {
            $allFilesHaveDate = true;
            foreach ($latest as $attachment) {
                // If any attachment has an empty file_date, set the flag to false
                if (empty($attachment['Attachment']['file_date'])) {
                    $allFilesHaveDate = false;
                    break; // No need to continue if one is empty
                }
            }

            if (!$allFilesHaveDate) {
                $response = $this->Application->find('first', array(
                    'conditions' => array('Application.id' => $id),
                    'contain' => array(
                        'Amendment',
                        'EthicalCommittee',
                        'AmendmentChecklist' => array(
                            'conditions' => array('AmendmentChecklist.year' => $year) // Filter by year here
                        ),
                        'InvestigatorContact',
                        'Pharmacist',
                        'Sponsor',
                        'SiteDetail',
                        'Organization',
                        'Placebo',
                        'Budget',
                        'Attachment',
                        'CoverLetter',
                        'Protocol',
                        'PatientLeaflet',
                        'Brochure',
                        'GmpCertificate',
                        'Cv',
                        'Finance',
                        'Declaration',
                        'AnnualLetter',
                        'StudyRoute',
                        'Manufacturer',
                        'IndemnityCover',
                        'OpinionLetter',
                        'ApprovalLetter',
                        'Statement',
                        'ParticipatingStudy',
                        'Addendum',
                        'Registration',
                        'Fee',
                        'Checklist'
                    )
                ));
                $this->request->data = $response;
                // debug($response);
                // exit;
                $this->Session->setFlash(__('Please provide file date for each amendment attached. '), 'alerts/flash_error');
                $this->redirect(array('action' => 'view', $this->Application->id));
            }


            $this->loadModel('Message');
            $this->loadModel('User');
            $html = new HtmlHelper(new ThemeView());
            $message = $this->Message->find('first', array('conditions' => array('name' => 'amendment_submission')));

            $users = $this->Application->User->find('all', array(
                'contain' => array('Group'),
                'conditions' => array('OR' => array('User.id' => $this->Application->field('user_id'), 'User.group_id' => 2)) //Applicant and managers
                // 'conditions' => array('User.group_id' => 2) //Applicant and managers
            ));
            foreach ($users as $user) {
                $variables = array(
                    'name' => $user['User']['name'],
                    'protocol_no' => $this->Application->field('protocol_no'),
                    'protocol_link' => $html->link($this->Application->field('protocol_no'), array(
                        'controller' => 'applications',
                        'action' => 'view',
                        $this->Application->id,
                        $user['Group']['redir'] => true,
                        'full_base' => true
                    ), array('escape' => false)),
                    'approval_date' => $this->Application->field('approval_date')
                );
                $datum = array(
                    'email' => $user['User']['email'],
                    'id' => $id,
                    'user_id' => $user['User']['id'],
                    'type' => 'amendment_submission',
                    'model' => 'AnnaulLetter',
                    'subject' => String::insert($message['Message']['subject'], $variables),
                    'message' => String::insert($message['Message']['content'], $variables)
                );
                CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
            }
            //**********************************    END   *********************************
            //end
            // Create a Audit Trail

            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $this->Application->field('id'),
                    'model' => 'Application',
                    'message' => 'An amendment for the report with protocol number ' .  $this->Application->field('protocol_no') . ' has been successfully submitted by ' . $this->User->field('username', array('id' => $this->Application->field('user_id'))),
                    'ip' =>  $this->Application->field('protocol_no')
                )
            );
            $this->AuditTrail->Create();
            if ($this->AuditTrail->save($audit)) {
                $this->log($this->args[0], 'audit_success');
            } else {
                $this->log('Error creating an audit trail', 'notifications_error');
                $this->log($this->args[0], 'notifications_error');
            }
        }
        $this->Session->setFlash(__('Successfully submitted the protocol amendment. '), 'alerts/flash_success');
        $this->redirect(array('action' => 'view', $id));
    }
    public function applicant_invoice($id)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $options = array(
            'ssl_verify_peer' => false
        );
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('SiteDetail', 'User', 'InvestigatorContact')
        ));

        $HttpSocket = new HttpSocket($options);

        $header_options = array(
            'header' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        );
        $postDataToken = array(
            'APPID' => 'e4da3b7fbbce2345d7772b0674a318d5',
            'APIKEY' => 'MjU0Yjg5ZmRiYzkyNTMwN2UyZWIyZjI3ZTI0NmRiMmU1NmU4NmMzYQ==',
        );

        $formDataToken = http_build_query($postDataToken);
        // //Request Access Token
        $initiate = $HttpSocket->post(
            'https://invoices.pharmacyboardkenya.org/token',
            $formDataToken,
            $header_options
        );

        // debug($initiate);
        // exit;
        $user = $application['User'];
        $multiArray = $application['InvestigatorContact'];
        $firstEntry = reset($multiArray);
        $name = $firstEntry['given_name'] . ' ' . $firstEntry['family_name'];
        $billDesc = $name . "\n" . $application['Application']['short_title'];

        if ($initiate->isOk()) {
            $body = $initiate->body;
            $resp = json_decode($body, true);
            if ($resp['success'] === false) {

                $this->Session->setFlash(__($resp['status']), 'alerts/flash_error');
                $this->redirect($this->referer());
            }
            $session_token = $resp['session_token'];
            $invoice_total = 1000;

            $postData = array(
                'payment_type' => 'Clinical_Trials', // Types are issued e.g. Clinical_Trials  
                'session_token' => $session_token, // from above  $application['Application']['short_title']
                'billDesc' => $billDesc,
                'currency' => 'USD',
                'clientMSISDN' => $user['phone_no'],
                'clientName' => $user['name'],
                'clientIDNumber' => $user['national_id_number'],
                'clientEmail' => $user['email'],
                'amountExpected' => $invoice_total
            );
            $header_options = array(
                'header' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded'
                )
            );
            $formData = http_build_query($postData);

            // $next = $HttpSocket->post('https://invoices.pharmacyboardkenya.org/ct_invoice/generate', $formData, $header_options);
            $next = $HttpSocket->post('https://invoices.pharmacyboardkenya.org/ecitizen_invoice/generate', $formData, $header_options);
            // debug($next);
            // exit;
            if ($next->isOk()) {
                $body1 = $next->body;
                $resp1 = json_decode($body1, true);
                $invoice_id = $resp1['invoice_id'];
                $payment_code = $resp1['ppb_reference_code'];

                $raw_id = base64_encode($invoice_id);
                $this->Application->saveField('ecitizen_invoice', $invoice_id);

                $prims = $HttpSocket->get('https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=' . $invoice_id, false, $options);

                if ($prims->isOk()) {
                    $body2 = $prims->body;
                    $resp2 = json_decode($body2, true);
                    // debug($resp2);
                    // exit;
                    $data = array(
                        'secureHash' => $resp2["secureHash"],
                        'apiClientID' => 42,
                        'serviceID' => $resp2["serviceID"],
                        'notificationURL' => 'https://practice.pharmacyboardkenya.org/ipn?id=' . $raw_id,
                        'callBackURLOnSuccess' => 'https://practice.pharmacyboardkenya.org/callback?id=' . $raw_id,
                        'billRefNumber' => $resp2["billRefNumber"],
                        'currency' => $resp2["currency"],
                        'amountExpected' => $resp2["amountExpected"],
                        'billDesc' => $resp2["billDesc"],
                        'pictureURL' => '', //$resp2["pictureURL"],
                        'clientName' => $resp2["clientName"],
                        'clientEmail' => $resp2["clientEmail"],
                        'clientMSISDN' => $resp2["clientMSISDN"],
                        'clientIDNumber' => $resp2["clientIDNumber"],
                    );

                    $payload = http_build_query($data);
                    $ecitizen = $HttpSocket->post('https://payments.ecitizen.go.ke/PaymentAPI/iframev2.1.php', $payload, $header_options);

                    if ($ecitizen->isOk()) {
                        $this->Session->setFlash(__('Invoice Generated Successfully.'), 'alerts/flash_success');
                        $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                    } else {
                        $this->Session->setFlash(__('Experience problems connecting to remote server.'), 'alerts/flash_error');
                        $this->redirect($this->referer());
                    }
                }
            }
        }
    }
    public function genereateQRCode($id = null)
    {

        $this->loadModel('AnnualLetter');

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
    public function manager_stages_summary()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10', '50' => '50', '100' => '100', '500' => '500', '1000' => '1000');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $limit = isset($this->paginate['limit']) ? $this->paginate['limit'] : 1000;


        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $applications = $this->Application->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'limit' => 10000,
                    'contain' => array()
                )
            );
            $this->response->download('applications_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
            $this->set(compact('applications'));
            $this->layout = false;
            $this->render('stage_csv_export');
        }
        //end csv export


        //in case of pdf export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'pdf') {

            $applications = $this->Application->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'limit' => $limit,
                    'contain' => $this->a_contain
                )
            );
            $this->set(compact('applications'));
            // $this->layout = false;
            // $this->render('csv_export');
            $this->pdfConfig = array('filename' => 'Applications',  'orientation' => 'portrait');
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));
        $this->loadModel('Erc');
        $this->set('ercs', $this->Erc->find('list', array('fields' => array('Erc.name', 'Erc.name'),)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function manager_amendment_summary()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');

        $this->paginate['contain'] = array(
            'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted'), 'User'),
            'TrialStatus',
            'InvestigatorContact',
            'Sponsor',
            'Amendment',
            'AmendmentChecklist',
            'AmendmentApproval',
            'AmendmentApprovalSummary',
            'AmendmentLetter',
            'SiteDetail' => array('County')
        );

        $exportLimit = 10000;

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {

            $applications = array();
            $apps = $this->Application->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'contain' => $this->paginate['contain'],
                    'limit' => $exportLimit
                )
            );

            foreach ($apps as $app) {
                if ($this->_applicationHasAmendmentData($app)) {
                    $applications[] = $app;
                }
            }
            $amendmentTimelineSummary = $this->_buildAmendmentTimelineSummary($applications);

            $this->response->download('applications_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
            $this->set(compact('applications', 'amendmentTimelineSummary'));
            $this->layout = false;
            $this->render('amendment_csv_export');
            return;
        }
        //end csv exports


        //in case of pdf export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'pdf') {
            $apps = $this->Application->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'contain' => $this->paginate['contain'],
                    'limit' => $exportLimit
                )
            );
            $applications = array();
            foreach ($apps as $app) {
                if ($this->_applicationHasAmendmentData($app)) {
                    $applications[] = $app;
                }
            }
            $amendmentTimelineSummary = $this->_buildAmendmentTimelineSummary($applications);
            $this->set(compact('applications', 'amendmentTimelineSummary'));
            $this->pdfConfig = array('filename' => 'Applications_Amendment_Summary',  'orientation' => 'landscape');
            return;
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => array(3, 9), 'User.is_active' => 1))));
        $this->loadModel('Erc');
        $this->set('ercs', $this->Erc->find('list', array('fields' => array('Erc.name', 'Erc.name'),)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    private function _applicationHasAmendmentData($application)
    {
        return !empty($application['Amendment'])
            || !empty($application['AmendmentChecklist'])
            || !empty($application['AmendmentApproval'])
            || !empty($application['AmendmentApprovalSummary'])
            || !empty($application['AmendmentLetter']);
    }

    private function _normalizeAmendmentTimelineKey($value)
    {
        $normalized = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
        $normalized = str_replace(array("\xC2\xA0", "\xA0"), ' ', $normalized);
        $normalized = preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $normalized);
        $normalized = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2212}]/u', '-', $normalized);
        $normalized = strtolower(trim((string)$normalized));
        $normalized = preg_replace('/^[\-\s_]+/', '', $normalized);
        $normalized = preg_replace('/^\-+/', '', $normalized);
        $normalized = preg_replace('/^amd[\s_-]*/u', '', $normalized);
        if ($normalized === '') {
            return '';
        }

        if (is_numeric($normalized)) {
            $numericValue = (float)$normalized;
            if (floor($numericValue) == $numericValue) {
                $normalized = (string)(int)$numericValue;
            } else {
                $normalized = rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
            }
        } else {
            $normalized = preg_replace('/[^a-z0-9.-]/', '', $normalized);
        }

        return trim((string)$normalized);
    }

    private function _formatAmendmentTimelineLabel($value)
    {
        $raw = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
        $raw = str_replace(array("\xC2\xA0", "\xA0"), ' ', $raw);
        $raw = preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $raw);
        $raw = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2212}]/u', '-', $raw);

        if (preg_match('/([0-9]+(?:\.[0-9]+)?)/', (string)$raw, $matches)) {
            $number = trim((string)$matches[1]);
            if (is_numeric($number)) {
                $numericValue = (float)$number;
                if (floor($numericValue) == $numericValue) {
                    $number = (string)(int)$numericValue;
                } else {
                    $number = rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
                }
            }
            return 'AMD-' . strtoupper((string)$number);
        }

        $key = $this->_normalizeAmendmentTimelineKey($raw);
        if ($key === '') {
            return 'AMD';
        }

        return 'AMD-' . strtoupper((string)$key);
    }

    private function _extractAmendmentTimelineNumber($value)
    {
        if (preg_match('/([0-9]+(?:\.[0-9]+)?)/', (string)$value, $matches)) {
            return (float)$matches[1];
        }
        return null;
    }

    private function _compareAmendmentTimelineKeys($left, $right)
    {
        $leftNumber = $this->_extractAmendmentTimelineNumber($left);
        $rightNumber = $this->_extractAmendmentTimelineNumber($right);

        if ($leftNumber === $rightNumber) {
            return strnatcasecmp((string)$left, (string)$right);
        }
        if ($leftNumber === null) {
            return 1;
        }
        if ($rightNumber === null) {
            return -1;
        }

        return ($leftNumber < $rightNumber) ? -1 : 1;
    }

    private function _compareAmendmentRowsById($left, $right)
    {
        $leftId = !empty($left['id']) ? (int)$left['id'] : 0;
        $rightId = !empty($right['id']) ? (int)$right['id'] : 0;

        if ($leftId === $rightId) {
            $leftCreated = !empty($left['created']) ? strtotime($left['created']) : 0;
            $rightCreated = !empty($right['created']) ? strtotime($right['created']) : 0;
            if ($leftCreated === $rightCreated) {
                return 0;
            }
            return ($leftCreated < $rightCreated) ? -1 : 1;
        }

        return ($leftId < $rightId) ? -1 : 1;
    }

    private function _timestampFromDateValue($value)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return 0;
        }

        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return (int)$timestamp;
        }

        $formats = array('d-m-Y', 'd/m/Y', 'Y-m-d', 'd-m-Y H:i:s', 'Y-m-d H:i:s');
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date instanceof DateTime) {
                return (int)$date->getTimestamp();
            }
        }

        return 0;
    }

    private function _newAmendmentTimelineEntry($key)
    {
        return array(
            'key' => (string)$key,
            'label' => $this->_formatAmendmentTimelineLabel($key),
            'sort_ts' => 0,
            'stages' => array(
                'created' => array('ts' => 0, 'date' => '-'),
                'submitted' => array('ts' => 0, 'date' => '-'),
                'review' => array('ts' => 0, 'date' => '-'),
                'approved' => array('ts' => 0, 'date' => '-'),
            )
        );
    }

    private function _setAmendmentTimelineStage(&$timelineMap, $key, $stage, $timestamp, $mode = 'earliest')
    {
        if (empty($timelineMap[$key]) || $timestamp <= 0) {
            return;
        }

        $currentTs = !empty($timelineMap[$key]['stages'][$stage]['ts']) ? (int)$timelineMap[$key]['stages'][$stage]['ts'] : 0;
        $shouldUpdate = false;

        if ($currentTs <= 0) {
            $shouldUpdate = true;
        } elseif ($mode === 'latest' && $timestamp > $currentTs) {
            $shouldUpdate = true;
        } elseif ($mode !== 'latest' && $timestamp < $currentTs) {
            $shouldUpdate = true;
        }

        if ($shouldUpdate) {
            $timelineMap[$key]['stages'][$stage]['ts'] = (int)$timestamp;
            $timelineMap[$key]['stages'][$stage]['date'] = date('d-M-Y', (int)$timestamp);
        }

        if ((int)$timestamp > (int)$timelineMap[$key]['sort_ts']) {
            $timelineMap[$key]['sort_ts'] = (int)$timestamp;
        }
    }

    private function _buildAmendmentTimelineEntries($application)
    {
        $timelineMap = array();
        $aliases = array();

        $amendments = !empty($application['Amendment']) && is_array($application['Amendment']) ? array_values($application['Amendment']) : array();
        if (!empty($amendments)) {
            usort($amendments, array($this, '_compareAmendmentRowsById'));
        }

        $sequence = 0;
        foreach ($amendments as $amendmentRow) {
            $sequence++;
            $sequenceKey = $this->_normalizeAmendmentTimelineKey('amd-' . $sequence);
            if ($sequenceKey === '') {
                continue;
            }

            if (empty($timelineMap[$sequenceKey])) {
                $timelineMap[$sequenceKey] = $this->_newAmendmentTimelineEntry($sequenceKey);
            }
            $aliases[$sequenceKey] = $sequenceKey;

            $ecctRefKey = $this->_normalizeAmendmentTimelineKey(!empty($amendmentRow['ecct_ref_number']) ? $amendmentRow['ecct_ref_number'] : '');
            if ($ecctRefKey !== '') {
                $aliases[$ecctRefKey] = $sequenceKey;
            }

            $createdTs = $this->_timestampFromDateValue(!empty($amendmentRow['created']) ? $amendmentRow['created'] : '');
            $this->_setAmendmentTimelineStage($timelineMap, $sequenceKey, 'created', $createdTs, 'earliest');
        }

        $checklists = !empty($application['AmendmentChecklist']) && is_array($application['AmendmentChecklist']) ? $application['AmendmentChecklist'] : array();
        foreach ($checklists as $checklist) {
            $yearKey = $this->_normalizeAmendmentTimelineKey(!empty($checklist['year']) ? $checklist['year'] : '');
            if ($yearKey === '') {
                continue;
            }
            if (!empty($aliases[$yearKey])) {
                $yearKey = $aliases[$yearKey];
            }
            if (empty($timelineMap[$yearKey])) {
                $timelineMap[$yearKey] = $this->_newAmendmentTimelineEntry($yearKey);
            }

            $checklistTs = $this->_timestampFromDateValue(!empty($checklist['created']) ? $checklist['created'] : '');
            if ($checklistTs <= 0) {
                $checklistTs = $this->_timestampFromDateValue(!empty($checklist['file_date']) ? $checklist['file_date'] : '');
            }

            $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'created', $checklistTs, 'earliest');
            $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'submitted', $checklistTs, 'latest');
        }

        $summaries = !empty($application['AmendmentApprovalSummary']) && is_array($application['AmendmentApprovalSummary']) ? $application['AmendmentApprovalSummary'] : array();
        foreach ($summaries as $summary) {
            $yearKey = $this->_normalizeAmendmentTimelineKey(!empty($summary['amendment']) ? $summary['amendment'] : '');
            if ($yearKey === '') {
                continue;
            }
            if (!empty($aliases[$yearKey])) {
                $yearKey = $aliases[$yearKey];
            }
            if (empty($timelineMap[$yearKey])) {
                $timelineMap[$yearKey] = $this->_newAmendmentTimelineEntry($yearKey);
            }

            $reviewTs = $this->_timestampFromDateValue(!empty($summary['created']) ? $summary['created'] : '');
            if ($reviewTs <= 0) {
                $reviewTs = $this->_timestampFromDateValue(!empty($summary['approval_date']) ? $summary['approval_date'] : '');
            }
            $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'review', $reviewTs, 'earliest');
        }

        $approvals = !empty($application['AmendmentApproval']) && is_array($application['AmendmentApproval']) ? $application['AmendmentApproval'] : array();
        foreach ($approvals as $approval) {
            $yearKey = $this->_normalizeAmendmentTimelineKey(!empty($approval['amendment']) ? $approval['amendment'] : '');
            if ($yearKey === '') {
                continue;
            }
            if (!empty($aliases[$yearKey])) {
                $yearKey = $aliases[$yearKey];
            }
            if (empty($timelineMap[$yearKey])) {
                $timelineMap[$yearKey] = $this->_newAmendmentTimelineEntry($yearKey);
            }

            $decisionTs = $this->_timestampFromDateValue(!empty($approval['created']) ? $approval['created'] : '');
            if ($decisionTs <= 0) {
                $decisionTs = $this->_timestampFromDateValue(!empty($approval['approval_date']) ? $approval['approval_date'] : '');
            }
            $decisionStatus = strtolower(trim((string) (!empty($approval['status']) ? $approval['status'] : '')));

            if ($decisionStatus !== 'summary') {
                $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'review', $decisionTs, 'earliest');
            }
            if ($decisionStatus === 'approved' || $decisionStatus === 'rejected') {
                $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'approved', $decisionTs, 'latest');
            }
        }

        $letters = !empty($application['AmendmentLetter']) && is_array($application['AmendmentLetter']) ? $application['AmendmentLetter'] : array();
        foreach ($letters as $letter) {
            $isSubmittedLetter = ((string) (!empty($letter['submitted']) ? $letter['submitted'] : '') === '1' || (int) (!empty($letter['submitted']) ? $letter['submitted'] : 0) === 1);
            if (!$isSubmittedLetter) {
                continue;
            }

            $yearKey = $this->_normalizeAmendmentTimelineKey(!empty($letter['status']) ? $letter['status'] : '');
            if ($yearKey === '') {
                continue;
            }
            if (!empty($aliases[$yearKey])) {
                $yearKey = $aliases[$yearKey];
            }
            if (empty($timelineMap[$yearKey])) {
                $timelineMap[$yearKey] = $this->_newAmendmentTimelineEntry($yearKey);
            }

            $letterTs = $this->_timestampFromDateValue(!empty($letter['created']) ? $letter['created'] : '');
            if ($letterTs <= 0) {
                $letterTs = $this->_timestampFromDateValue(!empty($letter['approval_date']) ? $letter['approval_date'] : '');
            }
            $this->_setAmendmentTimelineStage($timelineMap, $yearKey, 'approved', $letterTs, 'latest');
        }

        foreach ($timelineMap as $yearKey => $entry) {
            $submittedTs = !empty($entry['stages']['submitted']['ts']) ? (int)$entry['stages']['submitted']['ts'] : 0;
            if ($submittedTs > 0) {
                continue;
            }

            $fallbackTs = !empty($entry['stages']['review']['ts']) ? (int)$entry['stages']['review']['ts'] : 0;
            if ($fallbackTs <= 0) {
                $fallbackTs = !empty($entry['stages']['approved']['ts']) ? (int)$entry['stages']['approved']['ts'] : 0;
            }
            if ($fallbackTs > 0) {
                $timelineMap[$yearKey]['stages']['submitted']['ts'] = $fallbackTs;
                $timelineMap[$yearKey]['stages']['submitted']['date'] = date('d-M-Y', $fallbackTs);
            }
        }

        $orderedKeys = array_keys($timelineMap);
        if (!empty($orderedKeys)) {
            usort($orderedKeys, array($this, '_compareAmendmentTimelineKeys'));
        }

        $entries = array();
        foreach ($orderedKeys as $orderedKey) {
            $entry = $timelineMap[$orderedKey];
            $createdDate = !empty($entry['stages']['created']['date']) ? $entry['stages']['created']['date'] : '-';
            $submittedDate = !empty($entry['stages']['submitted']['date']) ? $entry['stages']['submitted']['date'] : '-';
            $reviewDate = !empty($entry['stages']['review']['date']) ? $entry['stages']['review']['date'] : '-';
            $approvedDate = !empty($entry['stages']['approved']['date']) ? $entry['stages']['approved']['date'] : '-';

            $entry['timeline_text'] = 'Created: ' . $createdDate
                . ' | Submitted: ' . $submittedDate
                . ' | Review: ' . $reviewDate
                . ' | Approval: ' . $approvedDate;

            $entries[] = $entry;
        }

        return $entries;
    }

    private function _buildAmendmentTimelineSummary($applications)
    {
        $summary = array(
            'rows' => array(),
            'max_amendments' => 0
        );

        if (empty($applications) || !is_array($applications)) {
            return $summary;
        }

        foreach ($applications as $application) {
            if (!$this->_applicationHasAmendmentData($application)) {
                continue;
            }

            $timelines = $this->_buildAmendmentTimelineEntries($application);
            if (empty($timelines)) {
                continue;
            }

            $summary['rows'][] = array(
                'application' => $application,
                'timelines' => $timelines
            );

            $timelineCount = count($timelines);
            if ($timelineCount > $summary['max_amendments']) {
                $summary['max_amendments'] = $timelineCount;
            }
        }

        return $summary;
    }

    /**
     * stages method
     *
     * @return array
     */
    private function diff_wdays($start, $end)
    {
        $weekdays = array('1', '2', '3', '4', '5'); //this i think monday-saturday
        $end2 = clone $end; //add one day so as to include the end date of our range
        $end2 = ($start->diff($end)->format('%a') != '0') ? $end2->modify('+1 day') : $end2; //add one day so as to include the end date of our range
        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($start, $interval, $end2);

        $total_days = 0;
        //this will calculate total days from monday to saturday in above date range
        foreach ($dateRange as $date) {
            if (in_array($date->format("N"), $weekdays)) {
                $total_days++;
            }
        }
        return $total_days;
    }

    public function stages($id = null)
    {
        $stages = [];
        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array('ApplicationStage', 'Review')
        ));
        if ($application) {
            if ($application['Application']['protocol_no']) {
                $application_name = $application['Application']['protocol_no'];
            } elseif ($application['Application']['short_title']) {
                $application_name = $application['Application']['short_title'];
            } else {
                $application_name = $application['Application']['created'];
            }

            //creation
            $csd = new DateTime($application['Application']['created']);
            $ccolor = 'success';
            $stages['Creation'] = ['application_name' => $application_name, 'label' => 'Application <br>Creation', 'days' => '0', 'start_date' => $csd->format('d-M-Y'), 'color' => $ccolor];

            //Submisssion
            // if ($application['Application']['submitted']) {
            //     $csd = new DateTime($application['Application']['date_submited']);
            //     $ccolor = 'success';
            //     $stages['Submission'] = ['application_name' => $application_name, 'label' => 'Application <br>Submission', 'days' => '', 'start_date' => $csd->format('d-M-Y'), 'color' => $ccolor];
            // }
            //Screening for Completeness
            $stages['Screening'] = ['label' => 'Screening', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=Screening].id')) {
                $scr = min(Hash::extract($application['ApplicationStage'], '{n}[stage=Screening]'));
                $scr_s = new DateTime($scr['start_date']);
                $scr_e = new DateTime($scr['end_date']);
                $stages['Creation']['end_date'] = $scr_s->format('d-M-Y');
                // $stages['Creation']['days'] = $scr_s->diff($csd)->format('%a');
                $stages['Creation']['days'] = $this->diff_wdays($csd, $scr_s);

                $stages['Screening']['start_date'] = $scr_s->format('d-M-Y');
                // $stages['Screening']['days'] = $scr_s->diff($scr_e)->format('%a');                
                $stages['Screening']['days'] = $this->diff_wdays($scr_s, $scr_e);
                $stages['Screening']['end_date'] = $scr_e->format('d-M-Y');

                if ($scr['status'] == 'Current' && $stages['Screening']['days'] > 0 && $stages['Screening']['days'] <= 5) {
                    $stages['Screening']['color'] = 'warning';
                } elseif ($scr['status'] == 'Current' && $stages['Screening']['days'] > 5) {
                    $stages['Screening']['color'] = 'danger';
                } else {
                    $stages['Screening']['color'] = 'success';
                }
            }
            // Incase It was unsubmitted Unsubmitted
            if ($application['Application']['unsubmitted']) {
                $stages['Unsubmitted'] = ['label' => 'Unsubmitted', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
                if (Hash::check($application['ApplicationStage'], '{n}[stage=Unsubmitted].id')) {
                    $scr = min(Hash::extract($application['ApplicationStage'], '{n}[stage=Unsubmitted]'));
                    $scr_s = new DateTime($scr['start_date']);
                    $scr_e = new DateTime($scr['end_date']);
                    $stages['Creation']['end_date'] = $scr_s->format('d-M-Y');
                    $stages['Creation']['end_date'] = $scr_e->format('d-M-Y');
                    // $stages['Creation']['days'] = $scr_s->diff($csd)->format('%a');
                    $stages['Creation']['days'] = $this->diff_wdays($csd, $scr_s);

                    $stages['Unsubmitted']['start_date'] = $scr_s->format('d-M-Y');
                    $stages['Unsubmitted']['end_date'] = $scr_e->format('d-M-Y');
                    // $stages['Unsubmitted']['days'] = $scr_s->diff($scr_e)->format('%a');                
                    $stages['Unsubmitted']['days'] = $this->diff_wdays($scr_s, $scr_e);

                    if ($scr['status'] == 'Current' && $stages['Unsubmitted']['days'] > 0 && $stages['Unsubmitted']['days'] <= 5) {
                        $stages['Unsubmitted']['color'] = 'warning';
                    } elseif ($scr['status'] == 'Current' && $stages['Unsubmitted']['days'] > 5) {
                        $stages['Unsubmitted']['color'] = 'danger';
                    } else {
                        $stages['Unsubmitted']['color'] = 'success';
                    }
                }
            }
            //Submission by sponsor
            $stages['ScreeningSubmission'] = ['label' => 'Response to <br>Queries', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=ScreeningSubmission].id')) {
                $ssb = min(Hash::extract($application['ApplicationStage'], '{n}[stage=ScreeningSubmission]'));
                $ssb_s = new DateTime($ssb['start_date']);
                $ssb_e = new DateTime($ssb['end_date']);

                $stages['ScreeningSubmission']['start_date'] = $ssb_s->format('d-M-Y');
                $stages['ScreeningSubmission']['end_date'] = $ssb_e->format('d-M-Y');
                // $stages['ScreeningSubmission']['days'] = $ssb_s->diff($ssb_e)->format('%a');  
                $stages['ScreeningSubmission']['days'] = $this->diff_wdays($ssb_s, $ssb_e);

                if ($ssb['status'] == 'Current' && $stages['ScreeningSubmission']['days'] > 0 && $stages['ScreeningSubmission']['days'] <= 10) {
                    $stages['ScreeningSubmission']['color'] = 'warning';
                } elseif ($ssb['status'] == 'Current' && $stages['ScreeningSubmission']['days'] > 10) {
                    $stages['ScreeningSubmission']['color'] = 'danger';
                } else {
                    $stages['ScreeningSubmission']['color'] = 'success';
                }
            }

            //Assign reviewers
            $stages['Assign'] = ['label' => 'Assigned to <br>Reviewers', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=Assign].id')) {
                $asn = min(Hash::extract($application['ApplicationStage'], '{n}[stage=Assign]'));
                $asn_s = new DateTime($asn['start_date']);
                $asn_e = new DateTime($asn['end_date']);

                $stages['Assign']['start_date'] = $asn_s->format('d-M-Y');
                $stages['Assign']['end_date'] = $asn_e->format('d-M-Y');
                // $stages['Assign']['days'] = $asn_s->diff($asn_e)->format('%a'); 
                $stages['Assign']['days'] = $this->diff_wdays($asn_s, $asn_e);

                if ($asn['status'] == 'Current' && $stages['Assign']['days'] > 0 && $stages['Assign']['days'] <= 5) {
                    $stages['Assign']['color'] = 'warning';
                } elseif ($asn['status'] == 'Current' && $stages['Assign']['days'] > 5) {
                    $stages['Assign']['color'] = 'danger';
                } else {
                    $stages['Assign']['color'] = 'success';
                }
            }

            //PPB Review
            $stages['Review'] = ['label' => 'Review <br>Comments', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=Review].id')) {
                $rev = min(Hash::extract($application['ApplicationStage'], '{n}[stage=Review]'));
                $rev_s = new DateTime($rev['start_date']);
                $rev_e = new DateTime($rev['end_date']);

                $stages['Review']['start_date'] = $rev_s->format('d-M-Y');
                $stages['Review']['end_date'] = $rev_e->format('d-M-Y');
                // $stages['Review']['days'] = $rev_s->diff($rev_e)->format('%a');  
                $stages['Review']['days'] = $this->diff_wdays($rev_s, $rev_e);

                if ($rev['status'] == 'Current' && $stages['Review']['days'] > 0 && $stages['Review']['days'] <= 30) {
                    $stages['Review']['color'] = 'warning';
                } elseif ($rev['status'] == 'Current' && $stages['Review']['days'] > 30) {
                    $stages['Review']['color'] = 'danger';
                } else {
                    $stages['Review']['color'] = 'success';
                }
            }

            //Review submission
            $stages['ReviewSubmission'] = ['label' => 'Sponsor <br>Feedback', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=ReviewSubmission].id')) {
                $rsb = min(Hash::extract($application['ApplicationStage'], '{n}[stage=ReviewSubmission]'));
                $rsb_s = new DateTime($rsb['start_date']);
                $rsb_e = new DateTime($rsb['end_date']);

                $stages['ReviewSubmission']['start_date'] = $rsb_s->format('d-M-Y');
                $stages['ReviewSubmission']['end_date'] = $rsb_e->format('d-M-Y');
                // $stages['ReviewSubmission']['days'] = $rsb_s->diff($rsb_e)->format('%a'); 
                $stages['ReviewSubmission']['days'] = $this->diff_wdays($rsb_s, $rsb_e);

                if ($rsb['status'] == 'Current' && $stages['ReviewSubmission']['days'] > 0 && $stages['ReviewSubmission']['days'] <= 90) {
                    $stages['ReviewSubmission']['color'] = 'warning';
                } elseif ($rsb['status'] == 'Current' && $stages['ReviewSubmission']['days'] > 90) {
                    $stages['ReviewSubmission']['color'] = 'danger';
                } else {
                    $stages['ReviewSubmission']['color'] = 'success';
                }
            }

            //Final Decision
            $stages['FinalDecision'] = ['label' => 'Final <br>Decision', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=FinalDecision].id')) {
                $fin = min(Hash::extract($application['ApplicationStage'], '{n}[stage=FinalDecision]'));
                $fin_s = new DateTime($fin['start_date']);
                $fin_e = new DateTime($fin['end_date']);

                $stages['FinalDecision']['start_date'] = $fin_s->format('d-M-Y');
                $stages['FinalDecision']['end_date'] = $fin_e->format('d-M-Y');
                // $stages['FinalDecision']['days'] = $fin_s->diff($fin_e)->format('%a');  
                $stages['FinalDecision']['days'] = $this->diff_wdays($fin_s, $fin_e);

                if ($fin['status'] == 'Current' && $stages['FinalDecision']['days'] > 0 && $stages['FinalDecision']['days'] <= 15) {
                    $stages['FinalDecision']['color'] = 'warning';
                } elseif ($fin['status'] == 'Current' && $stages['FinalDecision']['days'] > 15) {
                    $stages['FinalDecision']['color'] = 'danger';
                } else {
                    $stages['FinalDecision']['color'] = 'success';
                }
            }


            //Annual Approval. Shows remaining days
            $stages['AnnualApproval'] = ['label' => 'Annual <br>Approval', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'status' => ''];
            if (Hash::check($application['ApplicationStage'], '{n}[stage=AnnualApproval].id')) {
                $ann = min(Hash::extract($application['ApplicationStage'], '{n}[stage=AnnualApproval]'));
                $ann_s = new DateTime($ann['start_date']);
                $ann_e = new DateTime($ann['end_date']);

                $stages['AnnualApproval']['start_date'] = $ann_s->format('d-M-Y');
                $stages['AnnualApproval']['end_date'] = $ann_e->format('d-M-Y');

                $ann_now = new DateTime();

                if ($ann_now > $ann_e) {
                    $stages['AnnualApproval']['days'] = 0; // Set remaining days to 0
                } else {

                    $stages['AnnualApproval']['days'] = $ann_now->diff($ann_e)->format('%a');
                }

                if ($ann['status'] == 'Current') {
                    $stages['AnnualApproval']['color'] = 'success';
                } elseif ($ann['status'] == 'Pending') {
                    $stages['AnnualApproval']['color'] = 'warning';
                } elseif ($ann['status'] == 'Expired') {
                    $stages['AnnualApproval']['color'] = 'danger';
                }
            }

            //Completion


        } else {
            $stages['Creation'] = ['application_name' => '<< protocol no. >>', 'label' => 'Start', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'state' => 'default'];
            $stages['Submit'] = ['label' => 'Submit', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'state' => 'default'];
            $stages['Review'] = ['label' => 'Review', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'state' => 'default'];
            $stages['Feedback'] = ['label' => 'Feedback', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'state' => 'default'];
            $stages['Approval'] = ['label' => 'Approval', 'start_date' => '', 'end_date' => '', 'days' => '', 'color' => 'default', 'state' => 'default'];
        }

        $this->set('stages', $stages);
        $this->set('_serialize', 'stages');
        if ($this->request->is('requested')) {
            return $stages;
        }
    }


    /**
     * index method
     *
     * @return void
     */

    public function index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.submitted'] = 1;
        $criteria['Application.approved'] = 2;
        $criteria['Application.deactivated'] = 0;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'));

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function myindex()
    {
        // $this->response->download("export.csv");
        $applications = $this->Application->find('all');
        $this->set(compact('applications'));
        $this->layout = false;
        return;
    }

    public function applicant_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.user_id'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'), 'Review' => array('User'));

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Application->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->a_contain)
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }


    public function monitor_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        // $user = $this->Application->User->find('first', array(
        //     'contain' => array('StudyMonitor'=> array('Application')),
        //     'conditions' => array('User.id' => $this->Auth->User('id'))
        //     )
        // );
        // $criteria['Application.id'] = Hash::extract($user['StudyMonitor'], '{n}.application_id');
        $criteria['Application.id'] = $this->Application->StudyMonitor->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('StudyMonitor.user_id' => $this->Auth->User('id'))));
        $criteria['Application.submitted'] = 1;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'), 'Review' => array('User'));

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Application->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->a_contain)
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }
    public function outsource_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.id'] = $this->Application->ProtocolOutsource->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('ProtocolOutsource.user_id' => $this->Auth->User('id'))));
        $criteria['Application.submitted'] = 1;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'), 'Review' => array('User'));

        //  if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
        //     $this->csv_export($this->Application->find(
        //         'all',
        //         array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->a_contain)
        //     ));
        // } 

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function outsource_view($id)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        // $response = $this->_isOwnedBy($id);
        $contains = $this->a_contain;
        $response = $this->Application->find(
            'first',
            array(
                'conditions' => array('Application.id' => $id, 'Application.submitted' => 1),
                'contain' => $contains,
            )
        );
        $aids = $this->Application->ProtocolOutsource->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('ProtocolOutsource.user_id' => $this->Auth->User('id'))));
        // if($response['Application']['id'] != $this->Auth->user('sponsor')) {
        if (!in_array($response['Application']['id'], $aids)) {
            // $this->log("_isOwnedBy: application id = ".$response['Application']['id']." User = ".$this->Auth->user('sponsor'),'debug');
            $this->Session->setFlash(__('You do not have permission to access this resource.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $this->set('application', $response);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => $this->a_contain
        ));

        // Get the specific assigned section 
        // $sections = array_map(function ($item) {
        //     return [
        //         'assigned_user_id' => $item['user_id'],
        //         'model_sae' => $item['model_sae'],
        //         'model_ciom' => $item['model_ciom'],
        //         'model_dev' => $item['model_dev']
        //     ];
        // }, $application['Outsource']);
        // $current_user_id = $this->Auth->User('id');

        // // Find the record matching the current user_id
        // $matched_record = array_filter($sections, function ($section) use ($current_user_id) {
        //     return $section['assigned_user_id'] == $current_user_id;
        // });

        // // If a matching record is found, append it to the application
        // if (!empty($matched_record)) {
        //     // Since array_filter returns an array, we need to get the first element
        //     $matched_record = array_shift($matched_record);

        //     // Append the matched record to the application
        //     $application[]['MatchedOutsource'] = $matched_record;
        // }

        // debug($application['MatchedOutsource']); 
        // exit;


        $this->request->data = $application;
    }
    public function manager_index()
    {
        $this->Prg->commonProcess();

        $page_options = array( '10' => '10', '50' => '50', '100' => '100', '500' => '500', '1000' => '1000');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            if (!empty($this->passedArgs['approved']) && $this->passedArgs['approved'] == '2') {
                $this->passedArgs['approvedrange'] = true;
            } else {
                $this->passedArgs['range'] = true;
            }
        }
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');

        $this->paginate['contain'] = array(
            'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted'), 'User'),
            'TrialStatus',
            'InvestigatorContact',
            'Sponsor',
            'SiteDetail' => array('County')
        );

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            set_time_limit(600);
            $this->csv_export($this->Application->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'], 
                'order' => $this->paginate['order'], 
                'limit' => 10000,
               'contain' => $this->paginate['contain']
                )//$this->a_contain)
            ));
        }
        //end csv export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));
        $this->loadModel('Erc');
        $this->set('ercs', $this->Erc->find('list', array('fields' => array('Erc.name', 'Erc.name'),)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    //workflow
    public function manager_workflow()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        // debug($this->params['named']['stages']);

        if (!empty($this->passedArgs['stages'])) $this->passedArgs['stages'] = $this->params['named']['stages'];
        if (!empty($this->passedArgs['status'])) $this->passedArgs['status'] = $this->params['named']['status'];
        else $this->passedArgs['status'] = 'Current';
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');

        $this->paginate['contain'] = array(
            'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted'), 'User'),
            'TrialStatus',
            'ApplicationStage',
            'InvestigatorContact',
            'Sponsor',
            'SiteDetail' => array('County')
        );

        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->response->download('applications_' . date('Ymd_Hi') . '.csv');
            $this->set('applications', $this->Application->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->paginate['contain'])
            ));
            // $this->set(compact('applications'));
            $this->layout = false;
            $this->render('workflow');
            // $this->csv_export($this->Application->find('all', 
            //         array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->a_contain)
            //     ));
        }
        //end csv export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function inspector_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10','50'=>'50','100'=>'100');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;
        $my_applications = $this->Application->ActiveInspector->find('list', array(
            'conditions' => array('ActiveInspector.user_id' => $this->Auth->User('id')),
            'fields' => array('ActiveInspector.application_id')
        ));
        $criteria['Application.id'] = $my_applications;

        $my_applications = $this->Application->ActiveInspector->find('list', array(
            'conditions' => array('ActiveInspector.user_id' => $this->Auth->User('id'), 'ActiveInspector.type' => 'request', 'ActiveInspector.accepted' => 'accepted'),
            'fields' => array('ActiveInspector.application_id')
        ));

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.submitted'] = 1;
        $criteria['Application.id'] = $my_applications;

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');

        $this->paginate['contain'] = array(
            'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted')),
            'InvestigatorContact',
            'Sponsor',
            'SiteDetail' => array('County')
        );

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function internalreviewer_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $my_applications = $this->Application->Review->find('list', array(
            'conditions' => array('Review.user_id' => $this->Auth->User('id'), 'Review.type' => 'request', 'Review.accepted' => 'accepted'),
            'fields' => array('Review.application_id')
        ));

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.submitted'] = 1;
        $criteria['Application.id'] = $my_applications;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'));

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function reviewer_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $my_applications = $this->Application->Review->find('list', array(
            'conditions' => array('Review.user_id' => $this->Auth->User('id'), 'Review.type' => 'request', 'Review.accepted' => 'accepted'),
            'fields' => array('Review.application_id')
        ));

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.submitted'] = 1;
        $criteria['Application.id'] = $my_applications;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'));

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    public function partner_index()
    {
        $this->Prg->commonProcess();
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        $criteria['Application.user_id'] = $this->Auth->User('id');
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');
        $this->paginate['contain'] = array('InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'));

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
    }

    public function admin_index()
    {
        $this->Prg->commonProcess();
        // $this->Application->softDelete(false);
        $page_options = array('5' => '5', '10' => '10');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (!empty($this->passedArgs['month_year'])) $this->passedArgs['mode'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->Application->parseCriteria($this->passedArgs);
        // if (!isset($this->passedArgs['submitted'])) $criteria['Application.submitted'] = 1;
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Application.created' => 'desc');

        // $this->paginate['contain'] = array(
        //     'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted')),
        //     'InvestigatorContact', 'Sponsor', 'SiteDetail' => array('County'));
        $this->paginate['contain'] = array(
            'Review' => array('conditions' => array('Review.type' => 'request', 'Review.accepted' => 'accepted'), 'User'),
            'TrialStatus',
            'InvestigatorContact',
            'Sponsor',
            'SiteDetail' => array('County')
        );
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Application->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'], 'contain' => $this->a_contain)
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('applications', Sanitize::clean($this->paginate(), array('encode' => false)));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));
        $this->loadModel('Erc');
        $this->set('ercs', $this->Erc->find('list', array('fields' => array('Erc.name', 'Erc.name'),)));

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function study_title($id = null)
    {
        $study_title = $this->Application->field(
            'study_title',
            array('id' => $id)
        );
        if ($this->request->is('requested')) {
            return $study_title;
        }
    }

    public function view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }
        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
        $this->set('application', $this->Application->read(null, $id));
    }

    public function applicant_view($id = null)
    {
        $this->loadModel('Country');
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $response = $this->_isOwnedBy($id);

        $this->set('application', $response);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));
        $countries = $this->Country->find('list', array('order' => 'Country.name ASC'));
        $this->set(compact('countries'));

        if ($response['Application']['deactivated'] || $response['Application']['approved'] == 1) {
            $this->render('applicant_minimal_view');
        }

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));

        if ($this->request->is('post')) {

            $this->Application->create();
            // if ($this->Application->save($this->request->data, true, array('id', 'trial_status_id'))) {
            if (!isset($this->request->data['Application']['id']) || empty($this->request->data['Application']['id'])) {
                $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
            // elseif (
            //     isset($this->request->data['Application']['trial_status_id']) ||
            //     isset($this->request->data['Application']['final_report'])
            // ) {
            // first check if stopped/suspended by admin 
            // $app = $this->Application->find('first', array(
            //     'conditions' => array('Application.id' => $id),
            // ));
            // if ($app['Application']['admin_stopped']) {
            //     $this->request->data['Application']['trial_status_id'] = $app['Application']['trial_status_id'];
            // }
            //} 
            elseif (empty($this->request->data)) {
                $this->set('response', array('message' => 'Failure', 'errors' => 'The file you provided could not be saved. Kindly ensure that the file is less than
                    20 MB in size. <small>If it is larger, compress (zip,tar...) it to the required size first</small>'));
            } elseif (!$this->Application->saveAll($this->request->data, array(
                'validate' => 'only',
                'fieldList' => array(
                    'Attachment' => 'file'
                )
            ))) {
                $this->set('response', array('message' => 'Failure', 'errors' => 'The file(s) is not valid. If the file(s) are more than
                    20 MB in size please compress them to below 20 7MB first.'));
            } else {

                if ($this->Application->saveAssociated($this->request->data, array('validate' => false))) {
                    // $this->log($this->Application->Document->id,'debug');

                    if (
                        isset($this->request->data['Application']['trial_status_id']) ||
                        isset($this->request->data['Application']['final_report'])
                    ) {
                        //Only updating trial_status_id i.e. Current status of the trial
                        $this->set('response', array('message' => 'Success'));
                    } else {
                        // -- Changed from
                        /*$this->set('response', array(
                        'message' => 'Success',
                        'content' => $this->Application->AnnualApproval->find('first',
                            array('conditions' =>array('Attachment.id' => $this->Application->AnnualApproval->id),
                                   'contain' => array()))));*/
                        // -- to
                        if (isset($this->request->data['AnnualApproval']))
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array('Attachment.id' => $this->Application->AnnualApproval->id),
                                        'contain' => array()
                                    )
                                )
                            ));


                        if (isset($this->request->data['AmendmentChecklist']))
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array('Attachment.id' => $this->Application->AmendmentChecklist->id),
                                        'contain' => array()
                                    )
                                )
                            ));
                        // CakeResque::enqueue('default', 'ManagerShell', array('newAnnualApproval', $response));
                        if (isset($this->request->data['Document'])) {
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array('Attachment.id' => $this->Application->Document->id),
                                        'contain' => array()
                                    )
                                )
                            ));
                            CakeResque::enqueue('default', 'ManagerShell', array('newFinalReport', $response));
                        }
                        if (isset($this->request->data['Attachment'])) {
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array('Attachment.id' => $this->Application->Attachment->id),
                                        'contain' => array()
                                    )
                                )
                            ));
                        }
                    }
                } else {
                    // $this->log($this->Application->validationErrors,'debug');
                    $this->set('response', array('message' => 'Failure', 'errors' => $this->Application->validationErrors));
                }
            }
            $this->set('_serialize', 'response');
        }

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => $this->a_contain
        ));
        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') === false && !$response['Application']['submitted'] && !$response['Application']['deactivated']) {
            $this->Session->setFlash('This application is not yet submitted', 'alerts/flash_info');
            $this->redirect(array('action' => 'edit', $response['Application']['id']));
        }
    }

    public function monitor_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        // $response = $this->_isOwnedBy($id);
        $contains = $this->a_contain;
        $response = $this->Application->find(
            'first',
            array(
                'conditions' => array('Application.id' => $id, 'Application.submitted' => 1),
                'contain' => $contains,
            )
        );
        $aids = $this->Application->StudyMonitor->find('list', array('fields' => array('application_id', 'application_id'), 'conditions' => array('StudyMonitor.user_id' => $this->Auth->User('id'))));
        // if($response['Application']['id'] != $this->Auth->user('sponsor')) {
        if (!in_array($response['Application']['id'], $aids)) {
            // $this->log("_isOwnedBy: application id = ".$response['Application']['id']." User = ".$this->Auth->user('sponsor'),'debug');
            $this->Session->setFlash(__('You do not have permission to access this resource.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $this->set('application', $response);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => $this->a_contain
        ));
        $this->request->data = $application;
    }

    public function applicant_final_report($id = null)
    {
        // if (!$this->request->is('post') || !$this->request->is('put')) {
        //     throw new MethodNotAllowedException();
        // } else {
        $this->request->data['Application']['final_date'] = date('Y-m-d H:i:s');
        if ($this->Application->save($this->request->data, true, array(
            'id',
            'final_report',
            'laymans_summary',
            'implication_results',
            'quantity_imported',
            'quantity_dispensed',
            'quantity_destroyed',
            'quantity_exported',
            'balance_site',
            'final_date'
        ))) {
            $this->Session->setFlash(__('Final report successfully submitted.'), 'alerts/flash_success');
            $this->redirect(array('action' => 'view', $id));
        } else {
            $this->Session->setFlash(__('Error. Unable to submit final report.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'view', $id));
        }
        // }
    }

    private function aview($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }


        // $this->loadModel('ApplicationStage');
        //           $stage = $this->ApplicationStage->read(null, 4);
        //           debug($stage);


        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => $this->a_contain
        ));

        $this->set('application', $application);
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => array(3, 2, 6), 'User.is_active' => 1))));
        $this->set('inspectors', $this->Application->User->find('list', array('conditions' => array('User.group_id' => array(2, 6), 'User.is_active' => 1))));
        $this->set('external', $this->Application->User->find('list', array('conditions' => array('User.group_id' => array(9), 'User.is_active' => 1))));
       

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
    }
    public function manager_view($id = null)
    {
        $this->aview($id);
    }
    public function inspector_view($id = null)
    {

        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        #TODO: in this condition, add the search for if I have accepted to review the app
        $my_applications = $this->Application->ActiveInspector->find('list', array(
            'conditions' => array('ActiveInspector.user_id' => $this->Auth->User('id'), 'ActiveInspector.type' => 'request',  'ActiveInspector.application_id' => $id),
            'fields' => array('ActiveInspector.id', 'ActiveInspector.accepted')
        ));
        // debug($my_applications);
        $accept = array_search('accepted', $my_applications);
        $declined = array_search('declined', $my_applications);
        // if (isset($my_applications[$id])) {
        // if ($my_applications[$id] == 'accepted') {
        if ($accept) {
            $contains = $this->a_contain;
            $contains['Review']['conditions'] = array('Review.user_id' => $this->Auth->User('id'),  'Review.type' => 'reviewer_comment');
            $contains['ManagerReview'] = array('conditions' => array('ManagerReview.type' => 'ppb_comment'), 'InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User');
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => $contains
            ));
            $this->set('counties', $this->Application->SiteDetail->County->find('list'));
            $this->set('application', $application);
            if ($application['Application']['deactivated']) {
                $this->render('inspector_minimal_view');
            }
        } elseif ($declined) {
            $this->Session->setFlash(__('You have declined to review this protocol.'), 'alerts/flash_info');
            $this->redirect(array('action' => 'index'));
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => array('Review' => array('conditions' => array('Review.user_id' => $this->Auth->User('id')))),
            ));
            $this->set('application', $application);
            $this->render('inspector_minimal_view');
        }

        if ($application['Application']['deactivated'] || $application['Application']['approved'] == 1) {
            $this->render('applicant_minimal_view');
        }

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
    }



    public function inspector_view_alt($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        #TODO: in this condition, add the search for if I have accepted to review the app
        $my_applications = $this->Application->Review->find('list', array(
            'conditions' => array('Review.user_id' => $this->Auth->User('id'), 'Review.type' => 'request',  'Review.application_id' => $id),
            'fields' => array('Review.id', 'Review.accepted')
        ));
        // debug($my_applications);
        $accept = array_search('accepted', $my_applications);
        $declined = array_search('declined', $my_applications);
        // if (isset($my_applications[$id])) {
        // if ($my_applications[$id] == 'accepted') {
        if ($accept) {
            $contains = $this->a_contain;
            $contains['Review']['conditions'] = array('Review.user_id' => $this->Auth->User('id'),  'Review.type' => 'reviewer_comment');
            $contains['ManagerReview'] = array('conditions' => array('ManagerReview.type' => 'ppb_comment'), 'InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User');
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => $contains
            ));
            $this->set('counties', $this->Application->SiteDetail->County->find('list'));
            $this->set('application', $application);
            if ($application['Application']['deactivated']) {
                $this->render('reviewer_minimal_view');
            }
        } elseif ($declined) {
            $this->Session->setFlash(__('You have declined to review this protocol.'), 'alerts/flash_info');
            $this->redirect(array('action' => 'index'));
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => array('Review' => array('conditions' => array('Review.user_id' => $this->Auth->User('id')))),
            ));
            $this->set('application', $application);
            $this->render('reviewer_minimal_view');
        }

        if ($application['Application']['deactivated'] || $application['Application']['approved'] == 1) {
            $this->render('applicant_minimal_view');
        }

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
    }

    public function reviewer_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }

        #TODO: in this condition, add the search for if I have accepted to review the app
        $my_applications = $this->Application->Review->find('list', array(
            'conditions' => array('Review.user_id' => $this->Auth->User('id'), 'Review.type' => 'request',  'Review.application_id' => $id),
            'fields' => array('Review.id', 'Review.accepted')
        ));
        // debug($my_applications);
        $accept = array_search('accepted', $my_applications);
        $declined = array_search('declined', $my_applications);
        // if (isset($my_applications[$id])) {
        // if ($my_applications[$id] == 'accepted') {
        if ($accept) {
            $contains = $this->a_contain;
            $contains['Review']['conditions'] = array('Review.user_id' => $this->Auth->User('id'),  'Review.type' => 'reviewer_comment');
            $contains['ManagerReview'] = array('conditions' => array('ManagerReview.type' => 'ppb_comment'), 'InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User');
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => $contains
            ));
            $this->set('counties', $this->Application->SiteDetail->County->find('list'));
            $this->set('application', $application);
            if ($application['Application']['deactivated']) {
                $this->render('reviewer_minimal_view');
            }
        } elseif ($declined) {
            $this->Session->setFlash(__('You have declined to review this protocol.'), 'alerts/flash_info');
            $this->redirect(array('action' => 'index'));
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => array('Review' => array('conditions' => array('Review.user_id' => $this->Auth->User('id')))),
            ));
            $this->set('application', $application);
            $this->render('reviewer_minimal_view');
        }

        if ($application['Application']['deactivated'] || $application['Application']['approved'] == 1) {
            $this->render('applicant_minimal_view');
        }

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
    }

    public function admin_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $trial_statuses = $this->Application->TrialStatus->find('list');
        $this->set(compact('trial_statuses'));

        $this->set('application', $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array(
                'Amendment',
                'EthicalCommittee',
                'InvestigatorContact',
                'Pharmacist',
                'Sponsor',
                'SiteDetail',
                'Organization',
                'Placebo',
                'Attachment',
                'CoverLetter',
                'Protocol',
                'PatientLeaflet',
                'Brochure',
                'GmpCertificate',
                'Cv',
                'Finance',
                'Declaration',
                'IndemnityCover',
                'OpinionLetter',
                'ApprovalLetter',
                'Statement',
                'ParticipatingStudy',
                'Addendum',
                'Registration',
                'Fee',
                'Checklist',
                'AnnualLetter',
                'StudyRoute',
                'Manufacturer',
                'AnnualApproval',
                'ParticipantFlow',
                'Budget',
                'Deviation',
                'Document',
                'Review' => array('ReviewAnswer')
            )
        )));
        $this->set('counties', $this->Application->SiteDetail->County->find('list'));
        $this->set('users', $this->Application->User->find('list', array('conditions' => array('User.group_id' => 3, 'User.is_active' => 1))));

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
        /*+++++++++++++++++++ADMIN UPDATE FIELDS++++++++++++++++++++*/
        if ($this->request->is('post')) {
            $this->Application->create();
            if (empty($this->request->data['Application']['id'])) $this->request->data['Application']['id'] = $id;

            $this->request->data['Application']['id'] = $id;
            $fieldList = array('id');
            $temp = array();
            foreach ($this->request->data as $key => $value) {
                if ($key == 'Application') $temp[$key] = array_keys($value);
                else {
                    $temp[$key] = array_keys(current($value));
                }
            }
            // $this->log($temp,'debug');
            /*if(isset($this->request->data['Application']['approval_date'])) $fieldList[] = 'approval_date';
            if(isset($this->request->data['Application']['protocol_no'])) $fieldList[] = 'protocol_no';
            if(isset($this->request->data['Application']['investigator1_telephone'])) $fieldList[] = 'investigator1_telephone';
            if(isset($this->request->data['Application']['investigator1_email'])) $fieldList[] = 'investigator1_email';*/
            if (
                $this->request->data['Application']['id'] &&
                $this->Application->saveAssociated($this->request->data, array('fieldList' => $temp))
            ) {
                $message = array('message' => 'Success');
            } else {
                $message = array('message' => 'Failure');
            }
            $errors = $this->Application->validationErrors;
            $this->set(compact('message', 'errors'));
            $this->set('_serialize', array('message', 'errors'));
            // $this->set('_serialize', 'message');
        }
    }

    public function manager_approve($id = null)
    {
        if ($this->request->is('post')) {
            // pr($this->request->data);
            if ($this->request->data['Application']['approved'] == null) {
                $this->Session->setFlash(__('Please select if approved or not.'), 'alerts/flash_error');
                $this->redirect(array('action' => 'view', $id));
            } else {
                if ($this->Auth->password($this->request->data['Application']['password']) === $this->Auth->User('confirm_password')) {
                    $this->Application->create();
                    // $this->request->data['Application']['approved_date']= date('Y-m-d');
                    // debug($this->request->data);
                    // exit;
                    if ($this->Application->save($this->request->data, true, array('id', 'approved', 'approved_reason', 'approval_date'))) {
                        $data = array(
                            'application_id' => $this->Application->id,
                            'message' => $this->request->data['Application']['approved_reason'],
                            'manager' => $this->Auth->User('id')
                        );
                        CakeResque::enqueue('default', 'NotificationShell', array('managerApproveApplication', $data));

                        //Create  annual approval letter                 
                        $this->loadModel('Pocket');
                        $this->loadModel('AnnualLetter');
                        $html = new HtmlHelper(new ThemeView());
                        $this->Application->read();
                        $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'initial_approval_letter')));

                        $application = $this->Application->find('first', array('conditions' => array('Application.id' => $this->Application->id)));
                        $checklist = array();
                        foreach ($application['Checklist'] as $formdata) {
                            $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false, 'full_base' => true));
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

                        $qualification = $names = $professional_address = $telephone = null;
                        if (isset($application['InvestigatorContact'][0])) {
                            $qualification = $application['InvestigatorContact'][0]['qualification'];
                            $names = $application['InvestigatorContact'][0]['given_name'] . ' ' . $application['InvestigatorContact'][0]['middle_name'] . ' ' . $application['InvestigatorContact'][0]['family_name'];
                            $professional_address = $application['InvestigatorContact'][0]['professional_address'];
                            $telephone = $application['InvestigatorContact'][0]['telephone'];
                        }
                        $variables = array(
                            'approval_no' => $approval_no,
                            'protocol_no' => $application['Application']['protocol_no'],
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
                        // end 


                        //**********************  Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission,Final,AnnualApproval stages if not exists
                        $stages = $this->Application->ApplicationStage->find('all', array(
                            'contain' => array(),
                            'conditions' => array('ApplicationStage.application_id' => $id)
                        ));

                        if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                            $this->Application->ApplicationStage->create();
                            $this->Application->ApplicationStage->save(
                                array(
                                    'ApplicationStage' => array(
                                        'application_id' => $id,
                                        'stage' => 'Screening',
                                        'status' => 'Complete',
                                        'comment' => 'Manager final decision',
                                        'start_date' => date('Y-m-d'),
                                        'end_date' => date('Y-m-d')
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
                                    'application_id' => $id,
                                    'stage' => 'ScreeningSubmission',
                                    'status' => 'Complete',
                                    'comment' => 'Manager final decision',
                                    'start_date' => date('Y-m-d'),
                                    'end_date' => date('Y-m-d'),
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
                                    'application_id' => $id,
                                    'stage' => 'Assign',
                                    'status' => 'Complete',
                                    'comment' => 'Manager final decision',
                                    'start_date' => date('Y-m-d'),
                                    'end_date' => date('Y-m-d'),
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
                                    'application_id' => $id,
                                    'stage' => 'Review',
                                    'status' => 'Complete',
                                    'comment' => 'Manager final decision',
                                    'start_date' => date('Y-m-d'),
                                    'end_date' => date('Y-m-d'),
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
                                    'application_id' => $id,
                                    'stage' => 'ReviewSubmission',
                                    'status' => 'Complete',
                                    'comment' => 'Manager final decision',
                                    'start_date' => date('Y-m-d'),
                                    'end_date' => date('Y-m-d'),
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
                                    'application_id' => $id,
                                    'stage' => 'FinalDecision',
                                    'status' => 'Complete',
                                    'comment' => 'Manager final decision',
                                    'start_date' => date('Y-m-d'),
                                    'end_date' => date('Y-m-d'),
                                ))
                            );
                        } else {
                            $var = Hash::extract($stages, '{n}.ApplicationStage[stage=FinalDecision]');
                            if (!empty($var)) {
                                $s6['ApplicationStage'] = min($var);
                                if (empty($s6['ApplicationStage']['end_date'])) {
                                    $this->Application->ApplicationStage->create();
                                    $s6['ApplicationStage']['status'] = 'Complete';
                                    $s6['ApplicationStage']['comment'] = $this->request->data['Application']['approved'];
                                    $s6['ApplicationStage']['end_date'] = date('Y-m-d');
                                    $this->Application->ApplicationStage->save($s6);
                                }
                            }
                        }

                        if (!Hash::check($stages, '{n}.ApplicationStage[stage=AnnualApproval].id')) {
                            //create only if approved
                            if ($this->request->data['Application']['approved'] == 2) {
                                $this->Application->ApplicationStage->create();
                                $this->Application->ApplicationStage->save(
                                    array('ApplicationStage' => array(
                                        'application_id' => $id,
                                        'stage' => 'AnnualApproval',
                                        'status' => 'Current',
                                        'comment' => 'Manager approve',
                                        'start_date' => date('Y-m-d'),
                                        'end_date' => date('Y-m-d', strtotime('+1 year')),
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

                        //******************       Send Email and Notifications Managers    *****************************
                        $this->loadModel('Message');
                        $html = new HtmlHelper(new ThemeView());
                        $message = $this->Message->find('first', array('conditions' => array('name' => 'manager_approve_letter')));

                        $users = $this->Application->User->find('all', array(
                            'contain' => array('Group'),
                            'conditions' => array('OR' => array('User.id' => $this->Application->field('user_id'), 'User.group_id' => 2)) //Applicant and managers
                            // 'conditions' => array('User.group_id' => 2) //Applicant and managers
                        ));
                        foreach ($users as $user) {
                            $variables = array(
                                'name' => $user['User']['name'],
                                'approval_no' => $approval_no,
                                'protocol_no' => $this->Application->field('protocol_no'),
                                'protocol_link' => $html->link($this->Application->field('protocol_no'), array(
                                    'controller' => 'applications',
                                    'action' => 'view',
                                    $this->Application->id,
                                    $user['Group']['redir'] => true,
                                    'full_base' => true
                                ), array('escape' => false)),
                                'approval_date' => $this->Application->field('approval_date')
                            );
                            $datum = array(
                                'email' => $user['User']['email'],
                                'id' => $id,
                                'user_id' => $user['User']['id'],
                                'type' => 'manager_approve_letter',
                                'model' => 'AnnaulLetter',
                                'subject' => String::insert($message['Message']['subject'], $variables),
                                'message' => String::insert($message['Message']['content'], $variables)
                            );
                            CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $datum));
                            CakeResque::enqueue('default', 'GenericNotificationShell', array('sendNotification', $datum));
                        }
                        //**********************************    END   *********************************
                        //end
                        // Create a Audit Trail
                        $this->loadModel('User');
                        $this->loadModel('AuditTrail');
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $this->Application->field('id'),
                                'model' => 'Application',
                                'message' => 'Report with protocol number ' .  $this->Application->field('protocol_no') . ' has been successfully approved by ' .  $this->Auth->user('username'),
                                'ip' =>  $this->Application->field('protocol_no')
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
                        $this->Session->setFlash(__('Successfully approved the protocol. '), 'alerts/flash_success');
                        $this->redirect(array('action' => 'view', $id));
                    } else {
                        $this->Session->setFlash(__('Error. Unable to update protocol.'), 'alerts/flash_error');
                        $this->redirect(array('action' => 'view', $id));
                    }
                } else {
                    $this->Session->setFlash(__('The password you have entered is not correct! Please enter the correct password
                        and try again.'), 'alerts/flash_error');
                    $this->redirect(array('action' => 'view', $id));
                }
            }
        } else {
            $this->Session->setFlash(__('Not post.'), 'alerts/flash_info');
            $this->redirect(array('action' => 'view', $id));
        }
    }

    public function apl()
    {
        //Create  annual approval letter
        $users = $this->Application->User->find('all', array(
            'contain' => array('Group'),
            'conditions' => array('OR' => array('User.id' => 6, 'User.group_id' => 2)) //Applicant and managers //Applicant and managers
        ));
        // foreach ($users as $user) {
        //     $this->out('User: ' . $user['User']['name']);
        //   if (isset($application['AnnualLetter'][0])) {
        //     $this->out('AnnualLetter ' . $application['AnnualLetter'][0]);
        //     $variables = array(
        //             'name' => $user['User']['name'], 'protocol_no' => $application['Application']['protocol_no'],
        //             'protocol_link' => $html->link($application['Application']['protocol_no'], array('controller' => 'applications', 'action' => 'view', $application['Application']['id'], $user['Group']['redir'] => true, 
        //                 'full_base' => true), array('escape' => false)),
        //             'approval_date' => $application['Application']['approval_date'], 'expiry_date' => $application['AnnualLetter'][0]['expiry_date']
        //           );
        //       $datum = array(
        //         'email' => $user['User']['email'],
        //         'id' => $id, 'user_id' => $user['User']['id'], 'type' => $type, 'model' => 'AnnaulLetter',
        //         'subject' => String::insert($message['Message']['subject'], $variables),
        //         'message' => String::insert($message['Message']['content'], $variables)
        //       );
        //       $this->sendEmail($datum);
        //       $this->sendNotification($datum);
        //       $this->log($datum, 'approval_reminder');
        //   }              
        // }
        debug($users);

        // $this->loadModel('Pocket');
        // $this->loadModel('AnnualLetter');
        // $html = new HtmlHelper(new ThemeView());
        // $this->Application->read(null, 46);
        // $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'initial_approval_letter'), 'contain' => array('InvestigatorContact')));

        // $application = $this->Application->find('first', array('conditions' => array('Application.id' => $this->Application->id)));
        // $checklist = array();
        // foreach ($application['Checklist'] as $formdata) {            
        //   $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false));
        //   (isset($checklist[$formdata['pocket_name']])) ? 
        //     $checklist[$formdata['pocket_name']] .= $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>' : 
        //     $checklist[$formdata['pocket_name']] = $file_link.' dated '.date('jS F Y', strtotime($formdata['file_date'])).' Version '.$formdata['version_no'].'<br>';
        // }
        // $deeds = $this->Pocket->find('list', array(
        //   'fields' => array('Pocket.name', 'Pocket.content'),
        //   'conditions' => array('Pocket.type' => 'protocol'),
        //   'recursive' => 0
        // ));
        // // debug($deeds);
        // $checkstring='';
        // $cnt = 0;
        // foreach ($checklist as $kech => $check) {
        //   $cnt++;
        //   $checkstring .= $cnt.'. '.$deeds[$kech].'<br>'.$check;
        // }

        // $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $this->Application->id)));
        // $cnt++;
        // $year = date('Y', strtotime($this->Application->field('approval_date')));
        // $approval_no = 'APL/'.$cnt.'/'.$year.'-'.$application['Application']['protocol_no'];
        // $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
        // $variables = array(
        //     'approval_no' => $approval_no, 'protocol_no' => $application['Application']['protocol_no'], 
        //     'letter_date' => date('jS F Y', strtotime($application['Application']['approval_date'])),
        //     'qualification' => $application['InvestigatorContact'][0]['qualification'],
        //     'names' => $application['InvestigatorContact'][0]['given_name'].' '.$application['InvestigatorContact'][0]['middle_name'].' '.$application['InvestigatorContact'][0]['family_name'],
        //     'professional_address' => $application['InvestigatorContact'][0]['professional_address'],
        //     'telephone' => $application['InvestigatorContact'][0]['telephone'],
        //     'study_title' => $application['Application']['short_title'],
        //     'checklist' => $checkstring,
        //     'expiry_date' => $expiry_date
        // );

        // $save_data = array('AnnualLetter' => array(
        //         'application_id' => $application['Application']['id'],
        //         'approval_no' => $approval_no,
        //         'approver' => $this->Session->read('Auth.User.name'),
        //         'approval_date' => date('Y-m-d H:i:s'),
        //         'expiry_date' => $expiry_date,
        //         'status' => 'AnnualApprovalLetter',
        //         'content' => String::insert($approval_letter['Pocket']['content'], $variables)
        //       ),
        //     );
        // $this->layout = false;
        // $this->set('save_data', $save_data);
    }

    public function apn($id)
    {
        //Create  annual approval letter
        $this->loadModel('Pocket');
        $this->loadModel('AnnualLetter');
        $html = new HtmlHelper(new ThemeView());
        $this->Application->read(null, $id);
        $approval_letter = $this->Pocket->find('first', array('conditions' => array('Pocket.name' => 'annual_approval_letter'), 'contain' => array()));

        $application = $this->Application->find('first', array('conditions' => array('Application.id' => $this->Application->id)));
        $checklist = array();
        foreach ($application['AnnualApproval'] as $formdata) {
            $file_link = $html->link(__($formdata['basename']), array('controller' => 'attachments',   'action' => 'download', $formdata['id'], 'admin' => false, 'full_base' => true));
            (isset($checklist[$formdata['pocket_name']])) ?
                $checklist[$formdata['pocket_name']] .= $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>' :
                $checklist[$formdata['pocket_name']] = $file_link . ' dated ' . date('jS F Y', strtotime($formdata['file_date'])) . ' Version ' . $formdata['version_no'] . '<br>';
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

        $cnt = $this->Application->AnnualLetter->find('count', array('conditions' => array('AnnualLetter.application_id' => $this->Application->id)));
        $cnt++;
        $year = date('Y', strtotime($this->Application->field('approval_date')));
        $approval_no = 'APL/' . $cnt . '/' . $year . '-' . $application['Application']['protocol_no'];
        $expiry_date = date('jS F Y', strtotime($application['Application']['approval_date'] . " +1 year"));
        $variables = array(
            'approval_no' => $approval_no,
            'protocol_no' => $application['Application']['protocol_no'],
            'letter_date' => date('jS F Y', strtotime($application['Application']['approval_date'])),
            'qualification' => $application['InvestigatorContact'][0]['qualification'],
            'names' => $application['InvestigatorContact'][0]['given_name'] . ' ' . $application['InvestigatorContact'][0]['middle_name'] . ' ' . $application['InvestigatorContact'][0]['family_name'],
            'professional_address' => $application['InvestigatorContact'][0]['professional_address'],
            'telephone' => $application['InvestigatorContact'][0]['telephone'],
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
                'approval_date' => date('Y-m-d H:i:s'),
                'expiry_date' => $expiry_date,
                'status' => 'AnnualApprovalLetter',
                'content' => String::insert($approval_letter['Pocket']['content'], $variables)
            ),
        );
        $this->layout = false;
        $this->set('save_data', $save_data);
    }

    public function manager_view_notification($id = null, $notification = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists() || empty($notification)) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        } else {
            $this->loadModel('Notification');
            $this->Notification->id = $notification;
            if ($this->Notification->delete()) {
                // $this->Session->setFlash(__('Click the assigned reviewers tab to view response.'), 'alerts/flash_success');
                $this->redirect(array('action' => 'view', $id));
            } else {
                // $this->Session->setFlash(__('Click the assigned reviewers tab to view response.'), 'alerts/flash_info');
                $this->redirect(array('action' => 'view', $id));
            }
        }
    }

    public function inspector_view_notification($id = null, $notification = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists() || empty($notification)) {
            $this->Session->setFlash(__('No Protocol with given ID.'), 'alerts/flash_info');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        } else {
            $this->loadModel('Notification');
            $this->Notification->id = $notification;
            if ($this->Notification->delete()) {
                // $this->Session->setFlash(__('Click the assigned reviewers tab to view response.'), 'alerts/flash_success');
                $this->redirect(array('action' => 'view', $id));
            } else {
                // $this->Session->setFlash(__('Click the assigned reviewers tab to view response.'), 'alerts/flash_info');
                $this->redirect(array('action' => 'view', $id));
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

    public function clear_all_other_stages($id = null)
    {
        $stages = $this->Application->ApplicationStage->find('all', array(
            'contain' => array(),
            'conditions' => array('ApplicationStage.application_id' => $id)
        ));
        // debug($stages);
        // exit;
        if ($stages) {
            foreach ($stages as $stage) {
                if ($stage['ApplicationStage']['stage'] !== 'Unsubmitted') {
                    $this->Application->ApplicationStage->delete($stage['ApplicationStage']['id']);
                }
            }
        }
    }

    public function applicant_edit($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application not found.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $response = $this->_isApplicant($id);



        if ($response['Application']['deactivated']) {
            $this->redirect(array('action' => 'view', $id));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            //For you to edit, you have to be the owner
            if ($response['Application']['user_id'] != $this->Auth->user('id')) {
                $this->Session->setFlash(__('Please contact the Site Owner for submission'), 'alerts/flash_error');
                $this->redirect($this->referer());
            }

            if (isset($this->request->data['cancelReport'])) {
                $this->Session->setFlash(__('Form cancelled.'), 'alerts/flash_info');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
                $filedata = $this->request->data;
                unset($filedata['Checklist']);
                // Check if previously unsubmitted
                // if (!$response['Application']['unsubmitted']) {
                $this->request->data['Application']['date_submitted'] = date('Y-m-d H:i:s');
                // }
                $this->request->data['Application']['submitted'] = 1;
                //Start application stage 
                if ($response['Application']['unsubmitted']) {
                    $stages = $this->Application->ApplicationStage->find('all', array(
                        'contain' => array(),
                        'conditions' => array('ApplicationStage.application_id' => $id)
                    ));
                    $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Unsubmitted]');
                    if (!empty($var)) {
                        $s1['ApplicationStage'] = min($var);
                        if (empty($s1['ApplicationStage']['end_date'])) {
                            $this->Application->ApplicationStage->create();
                            $s1['ApplicationStage']['status'] = 'Complete';
                            $s1['ApplicationStage']['comment'] = 'Principal Investigator Re Submission';
                            $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                            $this->Application->ApplicationStage->save($s1);
                        }
                    }
                } else {
                    $this->request->data['ApplicationStage'][0]['stage'] = 'Screening';
                    $this->request->data['ApplicationStage'][0]['start_date'] = date('Y-m-d');
                    $this->request->data['ApplicationStage'][0]['status'] = 'Current';
                }
                // 

                if (empty($response['Application']['protocol_no'])) {
                    $count = $this->Application->find('count',  array('conditions' => array(
                        'Application.date_submitted BETWEEN ? and ?' => array(date("Y-m-01 00:00:00"), date("Y-m-d H:i:s"))
                    )));
                    $count++;
                    $count = ($count < 10) ? "0$count" : $count;
                    $this->request->data['Application']['protocol_no'] = 'ECCT/' . date('y/m') . '/' . $count;
                }

                // Check number of Sites

                // if (count($this->request->data['SiteDetail']) > $response['Application']['total_sites']) {
                //     $this->Session->setFlash(__('You\'ve exceeded the maximum number of sites!, ' . $response['Application']['total_sites'] . ' sites allowed!'), 'alerts/flash_error');
                //     $this->redirect($this->referer());
                // }
            }

            $filedata = $this->request->data;
            if (isset($this->request->data['saveChanges'])) {
                unset($filedata['Checklist']);
            }
            unset($filedata['Application']);

            if (empty($this->request->data)) {
                $message = 'The file you provided could not be saved. Kindly ensure that the file is less than
                        18 MB in size. <small>If it is larger, compress (zip,tar...) it to the required size first</small>';
                if ($this->RequestHandler->isAjax()) {
                    $this->set('response', array('message' => 'Failure', 'errors' => $message));
                } else {
                    $this->Session->setFlash(__($message), 'alerts/flash_error');
                    $this->redirect(array('action' => 'edit', $id));
                }
            } elseif (!$this->Application->saveAll($filedata, array(
                'validate' => 'only',
                'fieldList' => array(
                    'Attachment' => 'file'
                )
            ))) {
                $message = 'The file is not valid. If the file is more than 18 MB in size please compress it to below 18 MB first.
                If the file is an image file, ensure the image resolution is within 1600X1600 pixels.';
                if ($this->RequestHandler->isAjax()) $this->set('response', array('message' => 'Failure', 'errors' => $message));
                else $this->Session->setFlash(__($message), 'alerts/flash_error');
            } else {
                if ($this->Application->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                    if ($validate) {
                        $data = array(
                            'function' => 'ppbNewApplication',
                            'Application' => array(
                                'id' => $this->request->data['Application']['id'],
                                'name' => $this->Auth->user('name'),
                                'email' => $this->Auth->user('email'),
                                'protocol_no' => (!empty($response['Application']['protocol_no'])) ?  $response['Application']['protocol_no'] : $this->request->data['Application']['protocol_no']
                            )
                        );
                        CakeResque::enqueue('default', 'NotificationShell', array('ppbNewApplication', $data));
                        $protocol_no =  (!empty($response['Application']['protocol_no'])) ?  $response['Application']['protocol_no'] : $this->request->data['Application']['protocol_no'];
                        // Create a Audit Trail
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $response['Application']['id'],
                                'model' => 'Application',
                                'message' => 'New Report with protocol number ' . $protocol_no . ' has been submitted by ' . $this->Auth->user('username'),
                                'ip' => $protocol_no
                            )
                        );
                        $this->loadModel('AuditTrail');
                        $this->AuditTrail->Create();
                        if ($this->AuditTrail->save($audit)) {
                            $this->log($this->request->data, 'audit_success');
                        } else {
                            $this->log('Error creating an audit trail', 'notifications_error');
                            $this->log($this->request->data, 'notifications_error');
                        }

                        $this->Session->setFlash(__('You have successfully submitted the application to PPB.
                            Your assigned protocol number is ' . $data['Application']['protocol_no'] . '. PPB will review
                            this application and notify you on the progress. You can view the progress of the application by clicking on
                            &lsquo;my applications&rsquo; on the dashboard menu. Thank you.'), 'alerts/flash_success');
                        $this->redirect(array('action' => 'view', $this->Application->id));
                    } else {
                        $message = 'The change to the application has been saved. You may continue editing the report. Remember to submit the report when you are done.';
                        if ($this->RequestHandler->isAjax()) {
                            // $this->set('response', array('message' => 'Success', 'content' => $message));
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array(
                                            'Attachment.id' => $this->Application->{array_pop(array_keys($this->request->data))}->id
                                        ),
                                        'contain' => array()
                                    )
                                )
                            ));
                        } else {
                            $this->Session->setFlash(__($message), 'alerts/flash_success');
                            $this->redirect(array('action' => 'edit', $this->Application->id));
                        }
                    }
                } else {
                    $message = 'The application was not successfully submitted. Please correct the errors below...';
                    if ($this->RequestHandler->isAjax()) {
                        $this->set('response', array('message' => 'Failure', 'errors' => $message));
                    } else {
                        $this->Session->setFlash(__($message), 'alerts/flash_error');
                    }
                }
            }
            if ($this->RequestHandler->isAjax()) $this->set('_serialize', 'response');
        } else {
            $this->request->data = $response;
        }
        $counties = $this->Application->SiteDetail->County->find('list', array('order' => 'County.county_name ASC'));
        $this->set(compact('counties'));
    }

    public function applicant_edit_latest($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application not found.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $response = $this->_isApplicant($id);

        if ($response['Application']['deactivated']) {
            $this->redirect(array('action' => 'view', $id));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['cancelReport'])) {
                $this->Session->setFlash(__('Form cancelled.'), 'alerts/flash_info');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
            // debug($this->request->data);
            // exit;
            $validate = false;
            // if (isset($this->request->data['submit_type'])) {
            if (!empty($this->request->data['Application']['submit_type'])) {
                if ($this->request->data['Application']['submit_type'] === 'submitReport') {

                    $validate = 'first';
                    $filedata = $this->request->data;
                    unset($filedata['Checklist']);
                    // Check if previously unsubmitted
                    if (!$response['Application']['unsubmitted']) {
                        $this->request->data['Application']['date_submitted'] = date('Y-m-d H:i:s');
                    }
                    $this->request->data['Application']['submitted'] = 1;
                    //Start application stage 
                    if ($response['Application']['unsubmitted']) {
                        $stages = $this->Application->ApplicationStage->find('all', array(
                            'contain' => array(),
                            'conditions' => array('ApplicationStage.application_id' => $id)
                        ));
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Unsubmitted]');
                        if (!empty($var)) {
                            $s1['ApplicationStage'] = min($var);
                            if (empty($s1['ApplicationStage']['end_date'])) {
                                $this->Application->ApplicationStage->create();
                                $s1['ApplicationStage']['status'] = 'Complete';
                                $s1['ApplicationStage']['comment'] = 'Principal Investigator Re Submission';
                                $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s1);
                            }
                        }
                    } else {
                        $this->request->data['ApplicationStage'][0]['stage'] = 'Screening';
                        $this->request->data['ApplicationStage'][0]['start_date'] = date('Y-m-d');
                        $this->request->data['ApplicationStage'][0]['status'] = 'Current';
                    }
                    // 

                    if (empty($response['Application']['protocol_no'])) {
                        $count = $this->Application->find('count',  array('conditions' => array(
                            'Application.date_submitted BETWEEN ? and ?' => array(date("Y-m-01 00:00:00"), date("Y-m-d H:i:s"))
                        )));
                        $count++;
                        $count = ($count < 10) ? "0$count" : $count;
                        $this->request->data['Application']['protocol_no'] = 'ECCT/' . date('y/m') . '/' . $count;
                    }

                    // Check number of Sites

                    // if (count($this->request->data['SiteDetail']) > $response['Application']['total_sites']) {
                    //     $this->Session->setFlash(__('You\'ve exceeded the maximum number of sites!, ' . $response['Application']['total_sites'] . ' sites allowed!'), 'alerts/flash_error');
                    //     $this->redirect($this->referer());
                    // }
                }
            }

            $filedata = $this->request->data;
            if (!empty($this->request->data['Application']['submit_type'])) {
                if ($this->request->data['Application']['submit_type'] === 'saveChanges') {
                    unset($filedata['Checklist']);
                }
            }
            unset($filedata['Application']);
            if (empty($this->request->data)) {
                $message = 'The file you provided could not be saved. Kindly ensure that the file is less than
                        18 MB in size. <small>If it is larger, compress (zip,tar...) it to the required size first</small>';
                if ($this->RequestHandler->isAjax()) {
                    $this->set('response', array('message' => 'Failure', 'errors' => $message));
                } else {
                    $this->Session->setFlash(__($message), 'alerts/flash_error');
                    $this->redirect(array('action' => 'edit', $id));
                }
            } elseif (!$this->Application->saveAll($filedata, array(
                'validate' => 'only',
                'fieldList' => array(
                    'Attachment' => 'file'
                )
            ))) {
                $message = 'The file is not valid. If the file is more than 18 MB in size please compress it to below 18 MB first.
                If the file is an image file, ensure the image resolution is within 1600X1600 pixels.';
                if ($this->RequestHandler->isAjax()) $this->set('response', array('message' => 'Failure', 'errors' => $message));
                else $this->Session->setFlash(__($message), 'alerts/flash_error');
            } else {
                if ($this->Application->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                    if ($validate) {
                        $data = array(
                            'function' => 'ppbNewApplication',
                            'Application' => array(
                                'id' => $this->request->data['Application']['id'],
                                'name' => $this->Auth->user('name'),
                                'email' => $this->Auth->user('email'),
                                'protocol_no' => (!empty($response['Application']['protocol_no'])) ?  $response['Application']['protocol_no'] : $this->request->data['Application']['protocol_no']
                            )
                        );
                        CakeResque::enqueue('default', 'NotificationShell', array('ppbNewApplication', $data));

                        // Create a Audit Trail
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $response['Application']['id'],
                                'model' => 'Application',
                                'message' => 'New Report with protocol number ' . $response['Application']['protocol_no'] . ' has been submitted by ' . $this->Auth->user('username'),
                                'ip' => $response['Application']['protocol_no']
                            )
                        );
                        $this->loadModel('AuditTrail');
                        $this->AuditTrail->Create();
                        if ($this->AuditTrail->save($audit)) {
                            $this->log($this->request->data, 'audit_success');
                        } else {
                            $this->log('Error creating an audit trail', 'notifications_error');
                            $this->log($this->request->data, 'notifications_error');
                        }

                        $this->Session->setFlash(__('You have successfully submitted the application to PPB.
                            Your assigned protocol number is ' . $data['Application']['protocol_no'] . '. PPB will review
                            this application and notify you on the progress. You can view the progress of the application by clicking on
                            &lsquo;my applications&rsquo; on the dashboard menu. Thank you.'), 'alerts/flash_success');
                        $this->redirect(array('action' => 'view', $this->Application->id));
                    } else {
                        $message = 'The change to the application has been saved. You may continue editing the report. Remember to submit the report when you are done.';
                        if ($this->RequestHandler->isAjax()) {
                            // $this->set('response', array('message' => 'Success', 'content' => $message));
                            $this->set('response', array(
                                'message' => 'Success',
                                'content' => $this->Application->Attachment->find(
                                    'first',
                                    array(
                                        'conditions' => array(
                                            'Attachment.id' => $this->Application->{array_pop(array_keys($this->request->data))}->id
                                        ),
                                        'contain' => array()
                                    )
                                )
                            ));
                        } else {
                            $this->Session->setFlash(__($message), 'alerts/flash_success');
                            $this->redirect(array('action' => 'edit', $this->Application->id));
                        }
                    }
                } else {
                    $this->request->data = $response;
                    $message = 'The application was not successfully submitted. Please correct the errors below...';
                    if ($this->RequestHandler->isAjax()) {
                        $this->set('response', array('message' => 'Failure', 'errors' => $message));
                    } else {
                        $this->Session->setFlash(__($message), 'alerts/flash_error');
                    }
                    $this->request->data = $response;
                }
            }
            if ($this->RequestHandler->isAjax()) $this->set('_serialize', 'response');
        } else {
            $this->request->data = $response;
        }
        $counties = $this->Application->SiteDetail->County->find('list', array('order' => 'County.county_name ASC'));
        $this->set(compact('counties'));
    }

    public function partner_edit($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application not found.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }

        $response = $this->_isApplicant($id);

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['cancelReport'])) {
                $this->Session->setFlash(__('Form cancelled.'), 'alerts/flash_info');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }

            $validate = false;
            if (isset($this->request->data['submitReport'])) {
                $validate = 'first';
                $this->request->data['Application']['submitted'] = 1;
                $this->request->data['Application']['date_submitted'] = date('Y-m-d H:i:s');
                if (empty($response['Application']['protocol_no'])) {
                    $count = $this->Application->find('count',  array('conditions' => array(
                        'Application.submitted' => 1,
                        'Application.date_submitted BETWEEN ? and ?' => array(date("Y-m-01 H:i:s"), date("Y-m-d H:i:s"))
                    )));
                    $count++;
                    $count = ($count < 10) ? "0$count" : $count;
                    $this->request->data['Application']['protocol_no'] = 'ECCT/' . date('y/m') . '/' . $count;
                }
            }
            // $this->data = Sanitize::clean($this->data, array('encode' => false));
            if ($this->Application->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
                if ($validate) {
                    $this->Session->setFlash(__('You have successfully submitted the application to PPB. PPB will review
                        this application and notify you on the progress. You can view the progress of the application by clicking on
                        &#39;my applications&#39; on the dashboard menu. Thank you.'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The application has been saved'), 'alerts/flash_success');
                    $this->redirect(array('action' => 'edit', $this->Application->id));
                }
            } else {
                $this->Session->setFlash(__('The application was not successfully submitted. Please correct the errors below.'), 'alerts/flash_error');
            }
        } else {
            $this->request->data = $response;
        }
        $counties = $this->Application->SiteDetail->County->find('list', array('order' => 'County.county_name ASC'));
        $this->set(compact('counties'));
        $this->set('protocol_no', $response['Application']['protocol_no']);
        // $trial_statuses = $this->Application->TrialStatus->find('list');
        // $this->set(compact('trial_statuses'));
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
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }
        if ($this->Application->delete()) {
            $this->Session->setFlash(__('Application deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Application was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    public function applicant_delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }
        $this->_isApplicant($id);
        if (!$this->Application->delete()) {
            $this->Session->setFlash(__('Application deleted'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        $this->Session->setFlash(__('Application was not deleted'), 'alerts/flash_error');
        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
    }

    public function manager_delete($id = null)
    {
        if (!$this->request->is('post')) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }
        if (!$this->Application->delete()) {
            $this->Session->setFlash(__('Application deleted'), 'alerts/flash_success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Application was not deleted'), 'alerts/flash_error');
        $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
    }

    public function admin_delete($id = null, $delete = true)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }

        if ($delete) {
            if (!$this->Application->delete()) {
                $this->Session->setFlash(__('Application deleted'), 'alerts/flash_success');
            }
        } else {
            if ($this->Application->saveField('deleted', $delete)) {
                $this->Session->setFlash(__('The application has been successfully Undeleted.'), 'alerts/flash_success');
            }
        }
        $this->redirect($this->referer());
    }

    public function manager_deactivate($id = null, $activate = true)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->Application->saveField('deactivated', $activate)) {
            if ($activate) $this->Session->setFlash(
                __('The application has been successfully Deactivated.'),
                'alerts/flash_success'
            );
            else $this->Session->setFlash(
                __('The application has been successfully Reactivated.'),
                'alerts/flash_success'
            );
            $this->redirect(array('action' => 'view', $id));
        }
    }

    public function admin_unsubmit($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $app = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
        ));
        // debug($app['Application']['date_submitted']);
        // exit;
        $current = $app['Application']['date_submitted'];
        if ($this->Application->saveField('submitted', 0)) {

            $formattedDate  = date('Y-m-d H:i:s', strtotime($current));
            $this->Application->saveField('unsubmitted', 1);
            $this->Application->saveField('initial_date_submitted', $formattedDate);

            $stages = $this->Application->ApplicationStage->find('all', array(
                'contain' => array(),
                'conditions' => array('ApplicationStage.application_id' => $id)
            ));
            if (!Hash::check($stages, '{n}.ApplicationStage[stage=Unsubmitted].id')) {
                $this->Application->ApplicationStage->create();
                $this->Application->ApplicationStage->save(
                    array(
                        'ApplicationStage' => array(
                            'application_id' => $id,
                            'stage' => 'Unsubmitted',
                            'status' => 'Current',
                            'comment' => 'Admin unsubmission',
                            'start_date' => date('Y-m-d'),
                        )
                    )
                );
            } else {
                $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Unsubmitted]');
                if (!empty($var)) {
                    $s1['ApplicationStage'] = min($var);
                    if (empty($s1['ApplicationStage']['end_date'])) {
                        $this->Application->ApplicationStage->create();
                        $s1['ApplicationStage']['status'] = 'Current';
                        $s1['ApplicationStage']['comment'] = 'Admin unsubmission';
                        $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                        $this->Application->ApplicationStage->save($s1);
                    }
                }
            }
            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $id,
                    'model' => 'Application',
                    'message' => 'A Report with protocol number ' . $app['Application']['protocol_no'] . ' has been unsubmitted by ' . $this->Auth->user('name'),
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
            $this->Session->setFlash(__('The application has been successfully Unsubmitted.
                The user is now able to edit the application.'), 'alerts/flash_success');
            $this->redirect($this->referer());
        }
    }


    /**
     * Utility Methods
     */
    protected function _isApplicant($id)
    {
        // $response = $this->Application->isOwnedBy($id, $this->Auth->user('id'));
        $response = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
            'contain' => array(
                'Amendment',
                'EthicalCommittee',
                'InvestigatorContact',
                'Pharmacist',
                'Sponsor',
                'SiteDetail',
                'Organization',
                'Placebo',
                'Budget',
                'Attachment',
                'CoverLetter',
                'Protocol',
                'PatientLeaflet',
                'Brochure',
                'GmpCertificate',
                'Cv',
                'Finance',
                'Declaration',
                'AnnualLetter',
                'StudyRoute',
                'Manufacturer',
                'IndemnityCover',
                'OpinionLetter',
                'ApprovalLetter',
                'Statement',
                'ParticipatingStudy',
                'Addendum',
                'Registration',
                'Fee',
                'Checklist'
            )
        ));
        if ($response['Application']['user_id'] != $this->Auth->user('id')) {
            $this->log("_isOwnedBy: application id = " . $response['Application']['id'] . " User = " . $this->Auth->user('id'), 'debug');
            $this->Session->setFlash(__('You do not have permission to access this resource'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        } elseif ($response['Application']['submitted']) {
            $this->Session->setFlash(__('You cannot edit this application because it has been submitted to PPB.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }
        return $response;
    }


    private function _isOwnedBy($id)
    {
        // $response = $this->Application->isOwnedBy($id, $this->Auth->user('id'));
        $contains = $this->a_contain;
        $contains['SiteInspection']['conditions'] = array('SiteInspection.summary_approved' => 2);
        $contains['Deviation']['conditions'] = array('Deviation.user_id' => $this->Auth->user('id'));
        $contains['Review']['conditions'] = array('Review.type' => 'ppb_comment');

        // debug($contains);
        $response = $this->Application->find(
            'first',
            array(
                'conditions' => array('Application.id' => $id),
                'contain' => $contains,
            )
        );
        if ($response['Application']['user_id'] != $this->Auth->user('id')) {
            $this->log("_isOwnedBy: application id = " . $response['Application']['id'] . " User = " . $this->Auth->user('id'), 'debug');
            $this->Session->setFlash(__('You do not have permission to access this resource.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }
        return $response;
    }


    private function csv_export($applications = '')
    {
        
        $this->response->download('applications_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('applications'));
        $this->layout = false;
        $this->render('csv_export');
    }



    public function admin_suspend($id = null)
    {

        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            $this->Session->setFlash(__('Application does not exist!'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index'));
        }

        $data = $this->request->data;

        if (empty($this->request->data['Application']['status'])) {
            $this->Session->setFlash(__('Please select status'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }


        if (empty($this->request->data['Application']['admin_stopped_reason'])) {
            $this->Session->setFlash(__('Please provide reason'), 'alerts/flash_error');
            $this->redirect($this->referer());
        }

        $trial_statuses = "";
        $stopped = 0;
        if ($data['Application']['status'] == 3) {
            $trial_statuses = "Suspended";
            $stopped = 1;
        } else  if ($data['Application']['status'] == 4) {
            $trial_statuses =  "Stopped";
            $stopped = 1;
        } else {
            $trial_statuses =  $data['Application']['status'];
        }
        $app = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $id),
        ));
        if ($this->Application->saveField('admin_stopped', $stopped)) {
            $this->Application->saveField('trial_status_id', $this->request->data['Application']['status']);
            $this->Application->saveField('admin_stopped_reason', $this->request->data['Application']['admin_stopped_reason']);
            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $id,
                    'model' => 'Application',
                    'message' => 'A Report with protocol number ' . $app['Application']['protocol_no'] . ' has been ' . $trial_statuses . ' by ' . $this->Auth->user('name'),
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
            $this->Session->setFlash(__('The application status has been successfully updated'), 'alerts/flash_success');
            $this->redirect($this->referer());
        } else {
            
            $this->Session->setFlash(__('Failed to update the application status: '), 'alerts/flash_error'); // Displaying application save errors
            $this->redirect($this->referer());
        }
    }


    public function internalreviewer_view($id = null)
    {
        $this->Application->id = $id;
        if (!$this->Application->exists()) {
            throw new NotFoundException(__('Invalid application'));
        }
        $this->set('priorInternalFeedback', array());
 
        $my_applications = $this->Application->Review->find('list', array(
            'conditions' => array('Review.user_id' => $this->Auth->User('id'), 'Review.type' => 'request',  'Review.application_id' => $id),
            'fields' => array('Review.id', 'Review.accepted')
        ));
        
        $accept = array_search('accepted', $my_applications);
        $declined = array_search('declined', $my_applications);
      
        if ($accept) {
            $contains = $this->a_contain;
            $contains['Review']['conditions'] = array('Review.user_id' => $this->Auth->User('id'),  'Review.type' => 'reviewer_comment');
            $contains['ManagerReview'] = array('conditions' => array('ManagerReview.type' => 'ppb_comment'), 'InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User');
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => $contains
            ));
            $priorInternalFeedback = $this->_buildPriorInternalFeedback($id, (int) $this->Auth->User('id'));
            $this->set('counties', $this->Application->SiteDetail->County->find('list'));
            $this->set('application', $application);
            $this->set('priorInternalFeedback', $priorInternalFeedback);
            if ($application['Application']['deactivated']) {
                $this->render('reviewer_minimal_view');
            }
        } elseif ($declined) {
            $this->Session->setFlash(__('You have declined to review this protocol.'), 'alerts/flash_info');
            $this->redirect(array('action' => 'index'));
        } else {
            $application = $this->Application->find('first', array(
                'conditions' => array('Application.id' => $id),
                'contain' => array('Review' => array('conditions' => array('Review.user_id' => $this->Auth->User('id')))),
            ));
            $this->set('application', $application);
            $this->render('reviewer_minimal_view');
        }

        if ($application['Application']['deactivated'] || $application['Application']['approved'] == 1) {
            $this->render('applicant_minimal_view');
        }

        $this->request->data = $application;

        if (strpos($this->request->url, 'pdf') !== false) {
            $this->pdfConfig = array('filename' => 'Application_' . $id,  'orientation' => 'portrait');
        }
    }

    private function _buildPriorInternalFeedback($applicationId, $currentUserId)
    {
        $this->loadModel('Review');
        $priorFeedback = array();

        $currentInternalRequest = $this->Review->find('first', array(
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.type' => 'request',
                'Review.category' => 1,
                'Review.user_id' => $currentUserId
            ),
            'fields' => array('Review.id'),
            'order' => array('Review.created' => 'ASC', 'Review.id' => 'ASC'),
            'contain' => array()
        ));
        if (empty($currentInternalRequest['Review']['id'])) {
            return $priorFeedback;
        }

        $previousInternalUsers = $this->Review->find('list', array(
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.type' => 'request',
                'Review.category' => 1,
                'Review.id <' => $currentInternalRequest['Review']['id']
            ),
            'fields' => array('Review.user_id', 'Review.user_id'),
            'contain' => array()
        ));
        if (empty($previousInternalUsers)) {
            return $priorFeedback;
        }

        $reviewResponses = $this->Review->find('all', array(
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.user_id' => array_values(array_unique($previousInternalUsers)),
                'Review.type' => 'reviewer_comment'
            ),
            'fields' => array('Review.id', 'Review.user_id', 'Review.assessment_type', 'Review.recommendation', 'Review.status', 'Review.summary', 'Review.created'),
            'contain' => array(
                'User' => array('fields' => array('User.id', 'User.name', 'User.username')),
                'ReviewAnswer' => array(
                    'fields' => array(
                        'ReviewAnswer.id',
                        'ReviewAnswer.question_type',
                        'ReviewAnswer.review_type',
                        'ReviewAnswer.question_number',
                        'ReviewAnswer.question',
                        'ReviewAnswer.answer',
                        'ReviewAnswer.workspace',
                        'ReviewAnswer.comment'
                    ),
                    'order' => array('ReviewAnswer.question_number' => 'ASC', 'ReviewAnswer.id' => 'ASC')
                ),
                'InternalComment' => array('Attachment')
            ),
            'order' => array('Review.created' => 'ASC', 'Review.id' => 'ASC')
        ));

        // Ensure comment attachments are always hydrated for modal rendering.
        // Some environments return InternalComment without nested Attachment.
        $internalCommentIds = array();
        foreach ($reviewResponses as $reviewResponse) {
            if (!empty($reviewResponse['InternalComment'])) {
                foreach ((array) $reviewResponse['InternalComment'] as $internalComment) {
                    if (!empty($internalComment['id'])) {
                        $internalCommentIds[] = (int) $internalComment['id'];
                    }
                }
            }
        }

        $attachmentsByCommentId = array();
        if (!empty($internalCommentIds)) {
            $internalCommentIds = array_values(array_unique($internalCommentIds));
            $attachmentRows = $this->Review->InternalComment->Attachment->find('all', array(
                'conditions' => array(
                    'Attachment.model' => 'Comments',
                    'Attachment.foreign_key' => $internalCommentIds
                ),
                'fields' => array('Attachment.id', 'Attachment.foreign_key', 'Attachment.basename', 'Attachment.dirname', 'Attachment.model', 'Attachment.group', 'Attachment.created'),
                'order' => array('Attachment.id' => 'ASC'),
                'contain' => array()
            ));

            foreach ($attachmentRows as $attachmentRow) {
                $commentId = !empty($attachmentRow['Attachment']['foreign_key']) ? (int) $attachmentRow['Attachment']['foreign_key'] : 0;
                if ($commentId > 0) {
                    if (empty($attachmentsByCommentId[$commentId])) {
                        $attachmentsByCommentId[$commentId] = array();
                    }
                    $attachmentsByCommentId[$commentId][] = $attachmentRow['Attachment'];
                }
            }
        }

        if (!empty($attachmentsByCommentId)) {
            foreach ($reviewResponses as $reviewIndex => $reviewResponse) {
                if (empty($reviewResponse['InternalComment']) || !is_array($reviewResponse['InternalComment'])) {
                    continue;
                }

                foreach ($reviewResponse['InternalComment'] as $commentIndex => $internalComment) {
                    $commentId = !empty($internalComment['id']) ? (int) $internalComment['id'] : 0;
                    if ($commentId > 0 && !empty($attachmentsByCommentId[$commentId])) {
                        $reviewResponses[$reviewIndex]['InternalComment'][$commentIndex]['Attachment'] = $attachmentsByCommentId[$commentId];
                    }
                }
            }
        }

        $missingUserIds = array();
        foreach ($reviewResponses as $reviewResponse) {
            if (empty($reviewResponse['User']['name']) && !empty($reviewResponse['Review']['user_id'])) {
                $missingUserIds[] = (int) $reviewResponse['Review']['user_id'];
            }
        }

        $reviewerNameLookup = array();
        if (!empty($missingUserIds)) {
            $missingUserIds = array_values(array_unique($missingUserIds));
            $userRows = $this->Review->User->find('all', array(
                'conditions' => array('User.id' => $missingUserIds),
                'fields' => array('User.id', 'User.name', 'User.username'),
                'contain' => array()
            ));
            foreach ($userRows as $userRow) {
                $displayName = trim((string) $userRow['User']['name']);
                if ($displayName === '') {
                    $displayName = trim((string) $userRow['User']['username']);
                }
                if ($displayName !== '') {
                    $reviewerNameLookup[(int) $userRow['User']['id']] = $displayName;
                }
            }
        }

        foreach ($reviewResponses as $reviewResponse) {
            $reviewUserId = !empty($reviewResponse['Review']['user_id']) ? (int) $reviewResponse['Review']['user_id'] : 0;
            if ($reviewUserId > 0 && empty($reviewResponse['User']['name']) && !empty($reviewerNameLookup[$reviewUserId])) {
                $reviewResponse['User']['name'] = $reviewerNameLookup[$reviewUserId];
            }

            $feedbackAnswers = array();
            foreach ($reviewResponse['ReviewAnswer'] as $answer) {
                $hasAnswer = trim((string) $answer['answer']) !== '';
                $hasWorkspace = trim((string) $answer['workspace']) !== '';
                $hasComment = trim((string) $answer['comment']) !== '';
                if ($hasAnswer || $hasWorkspace || $hasComment) {
                    $feedbackAnswers[] = $answer;
                }
            }

            if (!empty($feedbackAnswers) || trim((string) $reviewResponse['Review']['summary']) !== '') {
                $reviewResponse['FeedbackAnswer'] = $feedbackAnswers;
                $priorFeedback[] = $reviewResponse;
            }
        }

        return $priorFeedback;
    }
}

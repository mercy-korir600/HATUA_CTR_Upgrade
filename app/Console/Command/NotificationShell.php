<?php
App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
App::uses('HttpSocket', 'Network/Http');
config('routes');
App::uses('Shell', 'Console');
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');

class NotificationShell extends Shell
{
  public $uses = array('User', 'Application', 'Amendment', 'AuditTrail', 'Review', 'Notification', 'Message');

  public function perform()
  {
    $this->initialize();
    $this->{array_shift($this->args)}();
  }

  public function sendEmail()
  {
    $messages = array();
    $message = array();
    $email = new CakeEmail();
    $email->config('gmail');
    $email->template('default');
    $email->emailFormat('html');
    $email->to($this->args[0]['User']['email']);
    // $email->subject(Configure::read('Emails.registration.subject'));
    $email->subject($messages['registration_email_subject']);
    $email->viewVars(array('message' => $message));
    if (!$email->send()) {
      $this->log($email, 'registration_email');
    }
  }

  public function registrationEmail()
  {
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array(
        'registration_email_subject',
        'registration_email',
        'registration_welcome_subject',
        'registration_welcome_message'
      )),
      'fields' => array('Message.name', 'Message.content')
    ));
    $variables = array(
      'name' => $this->args[0]['User']['name'],
      'full_base_url' => FULL_BASE_URL,
      'ppb_ctr' => 'Pharmacy and Poisons Board Kenya Clinical Trials Registry',
      'username' => $this->args[0]['User']['username'],
      'activation_link' => Router::url(
        array('controller' => 'users', 'action' => 'activate_account', $this->args[0]['User']['activation_key']),
        true
      )
    );

    $message = String::insert($messages['registration_email'], $variables);
    $email = new CakeEmail();
    $email->config('gmail');
    $email->template('default');
    $email->emailFormat('html');
    $email->to($this->args[0]['User']['email']);
    $sponsor_email = $this->args[0]['User']['email'];
    if (!empty($this->args[0]['User']['sponsor_email'])) $sponsor_email = $this->args[0]['User']['sponsor_email'];
    // $email->cc(array('pv@pharmacyboardkenya.org', 'info@pharmacyboardkenya.org', $sponsor_email));
    $email->bcc(array('kiprotich.japheth19@gmail.com'));
    // $email->subject(Configure::read('Emails.registration.subject'));
    $email->subject($messages['registration_email_subject']);
    $email->viewVars(array('message' => $message));
    if (!$email->send()) {
      $this->log($email, 'registration_email');
    }

    // Create a Audit Trail
    $audit = array(
      'AuditTrail' => array(
        'foreign_key' => $this->args[0]['User']['id'],
        'model' => 'User',
        'message' => 'A new user account with the email ' . $this->args[0]['User']['email'] . ' has been created',
        'ip' => $this->args[0]['User']['id']
      )
    );
    $this->AuditTrail->Create();
    if ($this->AuditTrail->save($audit)) {
      $this->log($this->args[0], 'audit_success');
    } else {
      $this->log('Error creating user signup audit trail', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }

    $save_data = array(
      'Notification' => array(
        'user_id' => $this->args[0]['User']['id'],
        'title' => String::insert($messages['registration_welcome_subject'], $variables),
        'type' => 'registration_welcome',
        'model' => 'User',
        'foreign_key' => $this->args[0]['User']['id'],
        'system_message' => String::insert($messages['registration_welcome_message'], $variables),
      ),
    );

    $this->Notification->Create();
    if (!$this->Notification->save($save_data)) {
      $this->log('The Notifications were not sent at registrationEmail.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
  }

  public function generate_report_invoice()
  {
    // $id = 1434;//
    $id = $this->args[0]['Application']['id'];
    $added = $this->args[0]['Application']['total_sites'];
    $added = isset($this->args[0]['Application']['total_sites']) ? $this->args[0]['Application']['total_sites'] : 1;

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
      if ($initiate->isOk()) {
        $body = $initiate->body;
        $resp = json_decode($body, true);
        $session_token = $resp['session_token'];
        $invoice_total = 1000 *  $added; //$application['Application']['total_sites'];;  /// calculated based on the number of sites::::
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
          $payment_code = $resp1['ppb_reference_code']; //[1];
          $this->Application->id = $id;
          if ($this->Application->saveField('ecitizen_invoice', $invoice_id)) {
            $raw_id = base64_encode($invoice_id);
            $prims = $HttpSocket->get('https://prims.pharmacyboardkenya.org/scripts/get_status?invoice=' . $invoice_id, false, $options);
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
              if ($ecitizen->isOk()) {
              }
            }

            //<!-- Send email to applicant -->

            $variables = array(
              'protocol_link' => '<a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $raw_id . '">Click here to view invoice</a>',
              'protocol_no' => $application['Application']['short_title'],
              'name' => $user['name']
            );

            $messages = $this->Message->find('list', array(
              'conditions' => array('Message.name' => array(
                'applicant_invoice_email',
                'applicant_invoice_email_subject'
              )),
              'fields' => array('Message.name', 'Message.content')
            ));
            $message = String::insert($messages['applicant_invoice_email'], $variables);
            $email = new CakeEmail();
            $email->config('gmail');
            $email->template('default');
            $email->emailFormat('html');
            $email->to($user['email']);
            $email->bcc(array('itsjkiprotich@gmail.com'));
            $email->subject(Sanitize::html(String::insert($messages['applicant_invoice_email_subject'], $variables), array('remove' => true)));
            $email->viewVars(array('message' => $message));
            if (!$email->send()) {
              $this->log($email, 'submit_email');
            }
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

  public function ppbNewApplication()
  {
    $managers = $this->User->find('all', array('conditions' => array('group_id' => 2, 'is_active' => 1), 'contain' => array()));
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array(
        'manager_new_application_subject',
        'manager_new_application',
        'applicant_submit_email',
        'applicant_submit_email_subject'
      )),
      'fields' => array('Message.name', 'Message.content')
    ));
    $save_data = array();
    $variables = array(
      'protocol_link' => Router::url(array(
        'controller' => 'applications',
        'action' => 'view',
        $this->args[0]['Application']['id'],
        'manager' => true
      ), true),
      'protocol_no' => $this->args[0]['Application']['protocol_no'],
      'name' => $this->args[0]['Application']['name']
    );
    foreach ($managers as $manager) {
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'type' => 'manager_new_application',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['Application']['id'],
          'title' => $messages['manager_new_application_subject'],
          'system_message' => String::insert($messages['manager_new_application'], $variables),
        ),
      );
    }

    // Handle eCitizen Integration



    // TODO : Set email accounts to cc notification
    $this->Notification->Create();
    if ($this->Notification->saveMany($save_data)) {
      $this->log($this->args[0], 'notifications_success');
    } else {
      $this->log('The Notifications were not sent at ppbNewApplication.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }

    //<!-- Send email to applicant -->
    $message = String::insert($messages['applicant_submit_email'], $variables);
    $email = new CakeEmail();
    $email->config('gmail');
    $email->template('default');
    $email->emailFormat('html');
    $email->to($this->args[0]['Application']['email']);
    // $email->cc(array('pv@pharmacyboardkenya.org', 'info@pharmacyboardkenya.org'));
    $email->bcc(array('kiprotich.japheth19@gmail.com'));
    $email->subject(Sanitize::html(String::insert($messages['applicant_submit_email_subject'], $variables), array('remove' => true)));
    $email->viewVars(array('message' => $message));
    if (!$email->send()) {
      $this->log($email, 'submit_email');
    }
  }

  public function alertOutsourced()
  {
    $managers = $this->User->find('all', array('conditions' => array('id' => $this->args[0]['Application']['user_id'], 'is_active' => 1), 'contain' => array()));
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array(
        'outsource_user_receive_email_subject',
        'outsource_user_receive_email',
        'outsource_user_receive_credentials_email'
      )),
      'fields' => array('Message.name', 'Message.content')
    ));
    $save_data = array();

    foreach ($managers as $manager) {

      $variables = array(
        'protocol_link' => Router::url(array(
          'controller' => 'applications',
          'action' => 'view',
          $this->args[0]['Application']['id'],
          'outsource' => true
        ), true),
        'protocol_no' => $this->args[0]['Application']['protocol_no'],
        'password' => $this->args[0]['Application']['password'],
        'username' => $this->args[0]['Application']['username'],
        'name' => $manager['User']['name']
      );
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'type' => 'outsource_user_receive_email',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['Application']['id'],
          'title' => $messages['outsource_user_receive_email_subject'],
          'system_message' => String::insert($messages['outsource_user_receive_email'], $variables),
        ),
      );
      //<!-- Send email to admin -->
      $bodymessages = $this->args[0]['Application']['new_user'] ? $messages['outsource_user_receive_credentials_email'] : $messages['outsource_user_receive_email'];
      $message = String::insert($bodymessages, $variables);
      $email = new CakeEmail();
      $email->config('gmail');
      $email->template('default');
      $email->emailFormat('html');
      $email->to($manager['User']['email']);
      $email->bcc(array('kiprotich.japheth19@gmail.com'));
      $email->subject(Sanitize::html(String::insert($messages['outsource_user_receive_email_subject'], $variables), array('remove' => true)));
      $email->viewVars(array('message' => $message));
      if (!$email->send()) {
        $this->log($email, 'submit_email');
      }
    }
    $this->Notification->Create();
    if ($this->Notification->saveMany($save_data)) {
      $this->log($this->args[0], 'notifications_success');
    } else {
      $this->log('The Notifications were not sent at ppbNewApplication.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
  }
  public function outsourceApplication()
  {
    $managers = $this->User->find('all', array('conditions' => array('group_id' => 1, 'is_active' => 1), 'contain' => array()));
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array(
        'outsource_email_subject',
        'outsource_email'
      )),
      'fields' => array('Message.name', 'Message.content')
    ));
    $save_data = array();

    foreach ($managers as $manager) {

      $variables = array(
        'protocol_link' => Router::url(array(
          'controller' => 'outsources',
          'action' => 'view',
          $this->args[0]['Application']['id'],
          'admin' => true
        ), true),
        'protocol_no' => $this->args[0]['Application']['protocol_no'],
        'name' => $manager['User']['name']
      );
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'type' => 'outsource_email',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['Application']['id'],
          'title' => $messages['outsource_email_subject'],
          'system_message' => String::insert($messages['outsource_email'], $variables),
        ),
      );
      //<!-- Send email to admin -->
      $message = String::insert($messages['outsource_email'], $variables);
      $email = new CakeEmail();
      $email->config('gmail');
      $email->template('default');
      $email->emailFormat('html');
      $email->to($manager['User']['email']);
      $email->bcc(array('kiprotich.japheth19@gmail.com'));
      $email->subject(Sanitize::html(String::insert($messages['outsource_email_subject'], $variables), array('remove' => true)));
      $email->viewVars(array('message' => $message));
      if (!$email->send()) {
        $this->log($email, 'submit_email');
      }
    }
    $this->Notification->Create();
    if ($this->Notification->saveMany($save_data)) {
      $this->log($this->args[0], 'notifications_success');
    } else {
      $this->log('The Notifications were not sent at ppbNewApplication.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
  }

  public function  newAppNotifyReviewer()
  {
    $reviews = $this->args[0];
    $doer = $this->args[1];
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('reviewer_new_application_subject', 'reviewer_new_application')),
      'fields' => array('Message.name', 'Message.content')
    ));

    $save_data = array();
    if (count($reviews) > 0) {
      foreach ($reviews as $review) {
        $variables = array(
          'protocol_link' => Router::url(array(
            'controller' => 'applications',
            'action' => 'view',
            $review['Review']['application_id'],
            'reviewer' => true
          ), true),
          'protocol_no' => $this->Application->field('protocol_no', array('id' => $review['Review']['application_id']))
        );
        $save_data[] = array(
          'Notification' => array(
            'user_id' => $review['Review']['user_id'],
            'type' => 'reviewer_new_application',
            'model' => 'Application',
            'foreign_key' => $review['Review']['application_id'],
            'title' => $messages['reviewer_new_application_subject'] . ' ' . $variables['protocol_no'],
            'system_message' => String::insert($messages['reviewer_new_application'], $variables),
            'user_message' => $review['Review']['text'],
          ),
        );
        // Create a Audit Trail
        $audit = array(
          'AuditTrail' => array(
            'foreign_key' => $review['Review']['application_id'],
            'model' => 'Application',
            'message' => 'A Report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $review['Review']['application_id'])) . ' has been assigned to ' . $this->User->field('username', array('id' => $review['Review']['user_id'])) . ' for review  by ' . $doer,
            'ip' =>  $this->Application->field('protocol_no', array('id' => $review['Review']['application_id']))
          )
        );
        $this->AuditTrail->Create();
        if ($this->AuditTrail->save($audit)) {
          $this->log($this->args[0], 'audit_success');
        } else {
          $this->log('Error creating an audit trail', 'notifications_error');
          $this->log($this->args[0], 'notifications_error');
        }
        //<!-- Send email to reviewers -->
        $revs = $this->User->find('all', array('conditions' => array('id' => $review['Review']['user_id'], 'is_active' => 1, 'group_id' => 3), 'contain' => array()));

        foreach ($revs as $manager) {
          $message = String::insert($messages['reviewer_new_application_subject'], $variables);
          $email = new CakeEmail();
          $email->config('gmail');
          $email->template('default');
          $email->emailFormat('html');
          $email->to($manager->email);
          $email->bcc(array('kiprotich.japheth19@gmail.com'));
          $email->subject(Sanitize::html(String::insert($messages['reviewer_new_application_subject'], $variables), array('remove' => true)));
          $email->viewVars(array('message' => $message));
          if (!$email->send()) {
            $this->log($email, 'submit_email');
          }
        }
      }

      $this->Notification->Create();
      if ($this->Notification->saveMany($save_data)) {
        $this->log($save_data, 'notifications_success');
      } else {
        $this->log('The application could not be saved at newAppNotifyReviewer. Please, try again.', 'notifications_error');
        $this->log($reviews, 'notifications_error');
        $this->log($save_data, 'notifications_error');
      }
    }
  }

  public function  newAppNotifyInspector()
  {
    $reviews = $this->args[0];
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('inspector_new_application_subject', 'reviewer_new_application')),
      'fields' => array('Message.name', 'Message.content')
    ));

    $save_data = array();
    if (count($reviews) > 0) {
      foreach ($reviews as $review) {
        $variables = array(
          'protocol_link' => Router::url(array(
            'controller' => 'applications',
            'action' => 'view',
            $review['ActiveInspector']['application_id'],
            'reviewer' => true
          ), true),
          'protocol_no' => $this->Application->field('protocol_no', array('id' => $review['ActiveInspector']['application_id']))
        );
        $save_data[] = array(
          'Notification' => array(
            'user_id' => $review['ActiveInspector']['user_id'],
            'type' => 'reviewer_new_application',
            'model' => 'Application',
            'foreign_key' => $review['ActiveInspector']['application_id'],
            'title' => $messages['inspector_new_application_subject'] . ' ' . $variables['protocol_no'],
            'system_message' => String::insert($messages['reviewer_new_application'], $variables),
            'user_message' => $review['Review']['text'],
          ),
        );
        // Create a Audit Trail
        $audit = array(
          'AuditTrail' => array(
            'foreign_key' => $review['ActiveInspector']['application_id'],
            'model' => 'Application',
            'message' => 'A Report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $review['ActiveInspector']['application_id'])) . ' has been assigned to ' . $this->User->field('username', array('id' => $review['ActiveInspector']['user_id'])) . ' for a site inspection ',
            'ip' =>  $this->Application->field('protocol_no', array('id' => $review['ActiveInspector']['application_id']))
          )
        );
        $this->loadModel('AuditTrail');
        $this->AuditTrail->Create();
        if ($this->AuditTrail->save($audit)) {
          $this->log($this->args[0], 'audit_success');
        } else {
          $this->log('Error creating an audit trail', 'audit_error');
          $this->log($this->args[0], 'audit_error');
        }
        //<!-- Send email to reviewers -->
        $revs = $this->User->find('all', array('conditions' => array('id' => $review['ActiveInspector']['user_id'], 'is_active' => 1, 'group_id' => 6), 'contain' => array()));

        foreach ($revs as $manager) {
          $message = String::insert($messages['inspector_new_application_subject'], $variables);
          $email = new CakeEmail();
          $email->config('gmail');
          $email->template('default');
          $email->emailFormat('html');
          $email->to($manager->email);
          $email->bcc(array('kiprotich.japheth19@gmail.com'));
          $email->subject(Sanitize::html(String::insert($messages['inspector_new_application_subject'], $variables), array('remove' => true)));
          $email->viewVars(array('message' => $message));
          if (!$email->send()) {
            $this->log($email, 'submit_email');
          }
        }
      }

      $this->Notification->Create();
      if ($this->Notification->saveMany($save_data)) {
        $this->log($save_data, 'notifications_success');
      } else {
        $this->log('The application could not be saved at newAppNotifyReviewer. Please, try again.', 'notifications_error');
        $this->log($reviews, 'notifications_error');
        $this->log($save_data, 'notifications_error');
      }
    }
  }

  public function ppbRequestReviewerResponse()
  {
    $reviewer = $this->args[0];
    $this->Notification->id = $this->Notification->field('id', array(
      'Notification.user_id' => $reviewer['Review']['user_id'],
      'Notification.type' => 'reviewer_new_application',
      'Notification.foreign_key' => $reviewer['Review']['application_id']
    ));
    if (!empty($this->Notification->id) && $this->Notification->delete()) {
      $this->log($reviewer, 'notifications_success');
    } else {
      $this->log('The application could not be saved at ppbRequestReviewerResponse 1. Please, try again.', 'notifications_error');
      $this->log($reviewer, 'notifications_error');
      $this->log($this->Notification->id, 'notifications_error');
    }

    //Notify all managers that reviewer has responded
    $managers = $this->User->find('all', array('conditions' => array('group_id' => 2, 'is_active' => 1), 'contain' => array()));
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('reviewer_response_subject', 'manager_reviewer_response')),
      'fields' => array('Message.name', 'Message.content')
    ));
    $save_data = array();
    foreach ($managers as $manager) {
      $variables = array('user' => $this->User->field('name', array('id' => $reviewer['Review']['user_id'])));
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'type' => 'manager_reviewer_response',
          'model' => 'Application',
          'foreign_key' => $reviewer['Review']['application_id'],
          'title' => $this->Application->field('protocol_no', array('id' => $reviewer['Review']['application_id'])),
          // 'title' => $reviewer['Review']['user_id'],
          'system_message' => String::insert($messages['manager_reviewer_response'], $variables),
          'user_message' => $reviewer['Review']['recommendation'],
        ),
      );
    }

    $this->Notification->Create();
    if (!$this->Notification->saveMany($save_data)) {
      $this->log('The Notifications were not sent at ppbRequestReviewerResponse 2.', 'notifications_error');
      // $this->log($reviews, 'notifications_error');
    }
  }

  public function  reviewerCommentNotifyManagers()
  {
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('new_reviewer_comment')),
      'fields' => array('Message.name', 'Message.content')
    ));
    $managers = $this->User->find('all', array(
      'conditions' => array('group_id' => 2, 'is_active' => 1),
      'fields' => array('User.id'),
      'contain' => array()
    ));

    $save_data = array();
    foreach ($managers as $manager) {
      $variables = array('user' => $this->User->field('name', array('id' => $this->args[1])));
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'title' => $this->Application->field('protocol_no', array('id' => $this->args[0])),
          'type' => 'new_reviewer_comment',
          'model' => 'Application',
          'foreign_key' => $this->args[0],
          'system_message' => String::insert($messages['new_reviewer_comment'], $variables),
        ),
      );
    }

    $this->Notification->Create();
    if (!$this->Notification->saveMany($save_data)) {
      $this->log('The Notifications were not sent at reviewerCommentNotifyManagers.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
  }

  public function  managerCommentNotifyApplicant()
  {
    $this->Application->id = $this->args[0]['application_id'];
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('manager_comment_applicant')),
      'fields' => array('Message.name', 'Message.content')
    ));
    // $save_data = array();
    $variables = array(
      'protocol_link' => Router::url(array(
        'controller' => 'applications',
        'action' => 'view',
        $this->args[0]['application_id'],
        'applicant' => true
      ), true),
      'protocol_no' => $this->Application->field('protocol_no', array('id' => $this->args[0]['application_id']))
    );
    $save_data = array(
      'Notification' => array(
        'user_id' => $this->Application->field('user_id'),
        'title' => $this->args[0]['manager'],
        'type' => 'manager_comment_applicant',
        'model' => 'Application',
        'foreign_key' => $this->args[0]['application_id'],
        'system_message' => String::insert($messages['manager_comment_applicant'], $variables),
      ),
    );

    $this->Notification->Create();
    if (!$this->Notification->save($save_data)) {
      $this->log('The Notifications were not sent at managerCommentNotifyApplicant.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
    // Create a Audit Trail
    $audit = array(
      'AuditTrail' => array(
        'foreign_key' => $this->args[0]['application_id'],
        'model' => 'Application',
        'message' => 'Manager comments has been sent for report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $this->args[0]['application_id'])) . ' by ' . $this->User->field('username', array('id' => $this->Application->field('user_id'))),
        'ip' =>  $this->Application->field('protocol_no', array('id' => $this->args[0]['application_id']))
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

  public function managerApproveApplication()
  {
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array(
        'approve_message_subject',
        'applicant_approve_message',
        'reviewers_approve_message',
        'managers_approve_message'
      )),
      'fields' => array('Message.name', 'Message.content')
    ));

    $my_reviewers = $this->Review->find('list', array(
      'conditions' => array(
        'Review.application_id' => $this->args[0]['application_id'],
        'Review.type' => 'request',
        'Review.accepted' => 'accepted'
      ),
      'fields' => array('Review.user_id'),
      'contain' => array()
    ));
    $reviewers = $this->User->find('all', array(
      'conditions' => array('group_id' => 3, 'is_active' => 1, 'id' => $my_reviewers),
      'fields' => array('User.id'),
      'contain' => array()
    ));

    $managers = $this->User->find('all', array(
      'conditions' => array('group_id' => 2, 'is_active' => 1),
      'fields' => array('User.id'),
      'contain' => array()
    ));

    $save_data = array();
    //Notify applicant
    $variables = array(
      'protocol_no' => $this->Application->field('protocol_no', array('id' => $this->args[0]['application_id'])),
      'approved' => ($this->Application->field('approved', array('id' => $this->args[0]['application_id'])) == 2) ? 'Approved' : 'Rejected',
    );
    $variables['protocol_link'] = Router::url(array(
      'controller' => 'applications',
      'action' => 'view',
      $this->args[0]['application_id'],
      'applicant' => true
    ), true);
    $save_data[] = array(
      'Notification' => array(
        'user_id' => $this->Application->field('user_id', array('id' => $this->args[0]['application_id'])),
        'title' => String::insert($messages['approve_message_subject'], $variables),
        'type' => 'applicant_approve_message',
        'model' => 'Application',
        'foreign_key' => $this->args[0]['application_id'],
        'system_message' => String::insert($messages['applicant_approve_message'], $variables),
        'user_message' => $this->args[0]['message'],
      ),
    );

    //Notify reviewers
    $variables['protocol_link'] = Router::url(array(
      'controller' => 'applications',
      'action' => 'view',
      $this->args[0]['application_id'],
      'reviewer' => true
    ), true);
    foreach ($reviewers as $reviewer) {
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $reviewer['User']['id'],
          'title' => String::insert($messages['approve_message_subject'], $variables),
          'type' => 'reviewers_approve_message',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['application_id'],
          'system_message' => String::insert($messages['reviewers_approve_message'], $variables),
          'user_message' => $this->args[0]['message'],
        ),
      );
    }

    //Notify managers
    $variables['protocol_link'] = Router::url(array(
      'controller' => 'applications',
      'action' => 'view',
      $this->args[0]['application_id'],
      'manager' => true
    ), true);
    $variables['name'] = $this->User->field('name', array('id' => $this->args[0]['manager']));
    foreach ($managers as $manager) {
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'title' => String::insert($messages['approve_message_subject'], $variables),
          'type' => 'managers_approve_message',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['application_id'],
          'system_message' => String::insert($messages['managers_approve_message'], $variables),
          'user_message' => $this->args[0]['message'],
        ),
      );
    }

    $this->Notification->Create();
    if (!$this->Notification->saveMany($save_data)) {
      $this->log('The Notifications were not sent at managerApproveApplication.', 'notifications_error');
      $this->log($this->args[0], 'notifications_error');
    }
  }

  public function  newAmndtNotifyApplicant()
  {
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('applicant_new_amendment')),
      'fields' => array('Message.name', 'Message.content')
    ));

    $save_data = array();
    $variables = array(
      'amendment_link' => Router::url(array(
        'controller' => 'amendments',
        'action' => 'edit',
        $this->args[0]['id'],
        'applicant' => true
      ), true),
      'protocol_link' => Router::url(array(
        'controller' => 'applications',
        'action' => 'view',
        $this->args[0]['application_id'],
        'applicant' => true
      ), true),
      'protocol_no' => $this->args[0]['protocol_no']
    );
    $save_data = array(
      'Notification' => array(
        'user_id' => $this->args[0]['user_id'],
        'type' => 'applicant_new_amendment',
        'model' => 'Amendment',
        'foreign_key' => $this->args[0]['id'],
        'system_message' => String::insert($messages['applicant_new_amendment'], $variables),
      ),
    );

    $this->Notification->Create();
    if ($this->Notification->save($save_data)) {
      $this->log('The application could not be saved at newAmndtNotifyApplicant. Please, try again.', 'notifications_error');
      $this->log($save_data, 'notifications_error');
    }
  }

  public function  submitAmndtNotifyManagersReviewers()
  {
    // To Complete
    $messages = $this->Message->find('list', array(
      'conditions' => array('Message.name' => array('applicant_new_amendment')),
      'fields' => array('Message.name', 'Message.content')
    ));
    $managers = $this->User->find('all', array('conditions' => array('group_id' => 2, 'is_active' => 1), 'contain' => array()));

    $variables = array();
    foreach ($managers as $manager) {
      $save_data[] = array(
        'Notification' => array(
          'user_id' => $manager['User']['id'],
          'type' => 'manager_new_application',
          'model' => 'Application',
          'foreign_key' => $this->args[0]['Application']['id'],
          'title' => $messages['manager_new_application_subject'],
          'system_message' => String::insert($messages['manager_new_application'], $variables),
        ),
      );
    }

    $save_data = array();
    $variables = array(
      'amendment_link' => Router::url(array(
        'controller' => 'amendments',
        'action' => 'edit',
        $this->args[0]['id'],
        'applicant' => true
      ), true),
      'protocol_link' => Router::url(array(
        'controller' => 'applications',
        'action' => 'view',
        $this->args[0]['application_id'],
        'applicant' => true
      ), true),
      'protocol_no' => $this->args[0]['protocol_no']
    );
    $save_data = array(
      'Notification' => array(
        'user_id' => $this->args[0]['user_id'],
        'type' => 'applicant_new_amendment',
        'model' => 'Amendment',
        'foreign_key' => $this->args[0]['id'],
        'system_message' => String::insert($messages['applicant_new_amendment'], $variables),
      ),
    );

    $this->Notification->Create();
    if ($this->Notification->save($save_data)) {
      $this->log('The application could not be saved at newAmndtNotifyApplicant. Please, try again.', 'notifications_error');
      $this->log($save_data, 'notifications_error');
    }
  }
}

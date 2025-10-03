<?php
App::uses('Shell', 'Console');
App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
App::uses('CakeEmail', 'Network/Email');

class WeeklyReviewerReminderTaskShell extends AppShell
{

    public $uses = array('Review', 'User', 'AuditTrail', 'Application', 'Message'); // specify the models


    public function main()
    {
        $this->out("Running weekly task...");
        // Your custom logic here
        $this->out('Fetching accepted review requests...');

        $reviews = $this->Review->find('all', array(
            'contain' => array('User', 'Application'),
            'order' => array('Review.created' => 'desc'),
            'conditions' => array(
                'Review.type' => 'request',
                'Review.accepted' => 'accepted'
            )
        ));

        $count = count($reviews);
        $this->out("Found {$count} accepted review requests.");


        $messages = $this->Message->find('list', array(
            'conditions' => array('Message.name' => array(
                'reviewer_reminder_email',
                'reviewer_reminder_email_subject'
            )),
            'fields' => array('Message.name', 'Message.content')
        ));



        foreach ($reviews as $request) {
            $userId = $request['Review']['user_id'];
            $applicationId = $request['Review']['application_id'];

            $variables = array(
                'protocol_link' => Router::url(array(
                    'controller' => 'applications',
                    'action' => 'view',
                    $applicationId,
                    'reviewer' => true
                ), true),
                'protocol_no' => $request['Application']['protocol_no'],
                'name' => $request['User']['name'],
                'email' => $request['User']['email'],
                'study_title' => $request['Application']['short_title'],
            );

            // Step 2: Check if a reviewer_comment with status=submitted exists for same user & app
            $submittedReview = $this->Review->find('first', array(
                'conditions' => array(
                    'Review.user_id' => $userId,
                    'Review.application_id' => $applicationId,
                    'Review.type' => 'reviewer_comment',
                    'Review.status' => 'submitted'
                )
            ));

            if ($submittedReview) {
                $this->out("✔ Reviewer comment found for User ID {$userId}, Application ID {$applicationId}");
            } else {
                $this->out("✘ No reviewer comment found for User ID {$userId}, Application ID {$applicationId}");

                $message = String::insert($messages['reviewer_reminder_email'], $variables);
                $email = new CakeEmail();
                $email->config('gmail');
                $email->template('default');
                $email->emailFormat('html');
                $email->to($request['User']['email']);
                $email->bcc(array('itsjkiprotich@gmail.com'));
                $email->subject(Sanitize::html(String::insert($messages['reviewer_reminder_email_subject'], $variables), array('remove' => true)));
                $email->viewVars(array('message' => $message));
                if (!$email->send()) {
                    $this->log($email, 'submit_email');
                }

                $audit = array(
                    'AuditTrail' => array(
                        'foreign_key' => $applicationId,
                        'model' => 'Review Reminder',
                        'message' => 'A reminder email sent to the reviewer ' . $request['User']['name'] . ' to submit their review for application ID ' . $applicationId . ' with the study title: ' . $request['Application']['short_title'],
                        'ip' => $request['Application']['protocol_no'],
                    )
                );
                $this->AuditTrail->Create();
                if ($this->AuditTrail->save($audit)) {
                    $this->log('Audit trail created for review reminder email', 'notifications_success');
                } else {
                    $this->log('Error creating an audit trail', 'notifications_error');
                }
            }
        }

        $this->out('--- Weekly Review Processor Completed ---');
    }
}

<?php
App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
config('routes');
App::uses('Shell', 'Console');
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('CakeResque', 'CakeResque.Lib');

class TestShell extends AppShell {
    public $uses = array('User', 'Application', 'Amendment','Review', 'Notification', 'Message');
    
    public function main() {
       $this->out('Hello world.');
       $email = new CakeEmail();
       $email->config('gmail');
       //$email->template('default');
       //$email->emailFormat('html');
       //$email->from(array('eddyokemwa@gmail.com' => 'My test'));
       $email->to('jkiprotich@intellisoftkenya.com');
    //    $email->to('cgichuki@intellisoftkenya.com');
       // $email->subject(Configure::read('Emails.registration.subject'));
       $email->subject('test email');
       //$email->viewVars(array('message' => 'This is a dummy message. seen'));
       if(!$email->send('My message to you')) {
           $this->log($email, 'test_email');
       }
    }
    public function que(){
    $recipient = !empty($this->args[0]) ? $this->args[0] : 'jkiprotich@intellisoftkenya.com';
    $payload = array(
      'email' => $recipient,
      'subject' => 'CTR Queue Test ' . date('Y-m-d H:i:s'),
      'message' => 'Queue email test from TestShell::que'
    );

    $data = CakeResque::enqueue('default', 'GenericEmailShell', array('sendEmail', $payload));
    $this->out('Enqueued job id: ' . $data);
    $this->log($data, 'test_queue');
    // Wait for the job to be processed
    // $processed = false;
    // $start_time = time();
    // while (!$processed && time() - $start_time < 10) {
    //   $status = CakeResque::status();
    //   $processed = $status['default']['processed'] > 0;
    //   sleep(1);
    // }
    
    // Verify that the job was processed
    // $this->assertTrue($processed, 'Job was not processed');
    
    // Check the output of the job
    // $expected_output = 'Email sent to test@example.com';
    // $this->assertContains($expected_output, file_get_contents(LOGS . 'resque.log'), 'Expected output was not found in log');
 
    }
}

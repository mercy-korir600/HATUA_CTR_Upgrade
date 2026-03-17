<?php
App::uses('AppController', 'Controller');
/**
 * Reviews Controller
 *
 * @property Review $Review
 */
class ReviewsController extends AppController
{
    public $uses = array('Review', 'Application', 'AuditTrail', 'User');

    public function beforeFilter()
    {
        parent::beforeFilter();
        // $this->Auth->allow('*');
        $this->Auth->allow('reviewer_test');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Review->recursive = 0;
        $this->set('reviews', $this->paginate());
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
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review'));
        }
        $this->set('review', $this->Review->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    /*  public function add() {
        if ($this->request->is('post')) {
            $this->Review->create();
            if ($this->Review->save($this->request->data)) {
                $this->Session->setFlash(__('The review has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The review could not be saved. Please, try again.'));
            }
        }
        $reviewers = $this->Review->Reviewer->find('list');
        $this->set(compact('reviewers'));
    }*/

    public function manager_edit($id = null)
    {
        if ($this->request->is('post')) {
            $this->Review->create();
            $message = $this->request->data['Message'];
            unset($this->request->data['Message']);
            if (!empty($this->request->data)) {
                foreach ($this->request->data as $key => $value) {
                    $this->request->data[$key]['Review']['application_id'] = $id;
                    $this->request->data[$key]['Review']['type'] = 'request';
                    $this->request->data[$key]['Review']['title'] = 'PPB request';
                    $this->request->data[$key]['Review']['text'] = $message['text'];
                }

                if ($this->Review->saveMany($this->request->data)) {
                    // Create object of Gearman client
                    // $client = new GearmanClient();
                    // Add Gearman server
                    // $client->addServer("127.0.0.1", 4730);
                    // Process job through worker
                    // $data = array(
                    //   'function' => 'newAppNotifyReviewer',
                    //   'Reviews' => $this->request->data
                    // );
                    // $client->doBackground('sendnotification', serialize($data));
                    $doer = $this->Auth->user('name');
                    CakeResque::enqueue('default', 'NotificationShell', array('newAppNotifyReviewer', $this->request->data,$doer));

                    $this->Session->setFlash(__('The reviewers have been notified'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                } else {
                    $this->Session->setFlash(__('The reviewers could not be notified. Please, try again.'));
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                }
            } else {
                $this->Session->setFlash(__('Please select at least one reviewer'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }
        }
        $users = $this->Review->User->find('list');
        $applications = $this->Review->Application->find('list');
        $this->set(compact('users', 'applications'));
    }

    public function manager_comment($id = null)
    {
        if ($this->request->is('post')) {
            $this->Review->create();
            $this->request->data['Review']['user_id'] = $this->Auth->User('id');
            $this->request->data['Review']['type'] = 'ppb_comment';

            if ($this->Auth->password($this->request->data['Review']['password']) === $this->Auth->User('confirm_password')) {
                if ($this->Review->save($this->request->data)) {

                    //Create new Screening,ScreeningSubmission,Assign,Review,ReviewSubmission stages if not exists
                    $stages = $this->Application->ApplicationStage->find('all', array(
                        'contain' => array(),
                        'conditions' => array('ApplicationStage.application_id' => $id)
                    ));

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array(
                                'ApplicationStage' => array(
                                    'application_id' => $id, 'stage' => 'Screening', 'status' => 'Complete', 'comment' => 'Manager review comment', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')
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
                                $s1['ApplicationStage']['comment'] = 'Manager review comment';
                                $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s1);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=ScreeningSubmission].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'ScreeningSubmission', 'status' => 'Complete', 'comment' => 'Manager review comment', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                            ))
                        );
                    } else {
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ScreeningSubmission]');
                        if (!empty($var)) {
                            $s2['ApplicationStage'] = min($var);
                            if (empty($s2['ApplicationStage']['end_date'])) {
                                $this->Application->ApplicationStage->create();
                                $s2['ApplicationStage']['status'] = 'Complete';
                                $s2['ApplicationStage']['comment'] = 'Manager review comment';
                                $s2['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s2);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'Assign', 'status' => 'Complete', 'comment' => 'Manager review comment', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                            ))
                        );
                    } else {
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                        if (!empty($var)) {
                            $s3['ApplicationStage'] = min($var);
                            if (empty($s3['ApplicationStage']['end_date'])) {
                                $this->Application->ApplicationStage->create();
                                $s3['ApplicationStage']['status'] = 'Complete';
                                $s3['ApplicationStage']['comment'] = 'Manager review comment';
                                $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s3);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'Review', 'status' => 'Complete', 'comment' => 'Manager review comment', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                            ))
                        );
                    } else {
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Review]');
                        if (!empty($var)) {
                            $s4['ApplicationStage'] = min($var);
                            if (empty($s4['ApplicationStage']['end_date'])) {
                                $this->Application->ApplicationStage->create();
                                $s4['ApplicationStage']['status'] = 'Complete';
                                $s4['ApplicationStage']['comment'] = 'Manager review comment';
                                $s4['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s4);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=ReviewSubmission].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'ReviewSubmission', 'status' => 'Current', 'comment' => 'Manager review comment', 'start_date' => date('Y-m-d')
                            ))
                        );
                    } else {
                        // Re-open stage
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ReviewSubmission]');
                        if (!empty($var)) {
                            $s5['ApplicationStage'] = min($var);
                            $this->Application->ApplicationStage->create();
                            $s5['ApplicationStage']['status'] = 'Current';
                            $s5['ApplicationStage']['comment'] = 'Manager new comment';
                            $s5['ApplicationStage']['end_date'] = null;  //re-open stage
                            $this->Application->ApplicationStage->save($s5);
                        }
                    }

                    //end stages


                    $data = array(
                        'application_id' => $this->request->data['Review']['application_id'],
                        'manager' => $this->Auth->User('id'),
                        'approver' => $this->Auth->User('username')
                    );
                    CakeResque::enqueue('default', 'NotificationShell', array('managerCommentNotifyApplicant', $data));
                    $this->Session->setFlash(
                        __('Thank you. Your comments have been saved. They are now visible to the applicant and reviewers'),
                        'alerts/flash_success'
                    );
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                } else {
                    $this->Session->setFlash(__('Your comments could not be saved. Please, try again.'));
                }
            } else {
                $this->Session->setFlash(__('The password you have entered is not correct! Please enter the correct password
                    and try again.'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }

            // if ($this->Review->saveMany($this->request->data)) {
            //  $this->Session->setFlash(__('The reviewers have been saved'), 'alerts/flash_success');
            //  $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            // } else {
            //  $this->Session->setFlash(__('The reviewers could not be saved. Please, try again.'));
            // }
        }
        $users = $this->Review->User->find('list');
        $applications = $this->Review->Application->find('list');
        $this->set(compact('users', 'applications'));
    }

    private function _buildApplicationViewRedirect($applicationId, $reviewId = null)
    {
        $redirect = array(
            'controller' => 'applications',
            'action' => 'view',
            $applicationId
        );

        if (!empty($this->request->params['prefix'])) {
            $redirect[$this->request->params['prefix']] = true;
        }

        if ($reviewId !== null) {
            $redirect['rreview_view'] = $reviewId;
        }

        return $redirect;
    }

    public function add($application_id = null, $review_type = null, $source_review_id = null)
    {
        $this->Review->create();
        $sourceReviewId = null;
        if (!empty($source_review_id)) {
            $sourceReviewId = (int) $source_review_id;
        } elseif (!empty($this->request->params['named']['source_review'])) {
            $sourceReviewId = (int) $this->request->params['named']['source_review'];
        } elseif (!empty($this->request->query['source_review'])) {
            $sourceReviewId = (int) $this->request->query['source_review'];
        }

        if ($sourceReviewId > 0) {
            $sourceReview = $this->Review->find('first', array(
                'conditions' => array(
                    'Review.id' => $sourceReviewId,
                    'Review.application_id' => $application_id,
                    'Review.type' => 'reviewer_comment'
                ),
                'fields' => array('Review.id', 'Review.user_id', 'Review.application_id', 'Review.assessment_type'),
                'contain' => array(
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
                        'order' => array('ReviewAnswer.question_number' => 'asc', 'ReviewAnswer.id' => 'asc')
                    )
                )
            ));

            if (empty($sourceReview)) {
                $this->Session->setFlash(__('The selected internal reviewer assessment could not be found.'), 'alerts/flash_error');
                $this->redirect($this->_buildApplicationViewRedirect($application_id));
            }

            if (!empty($sourceReview['Review']['assessment_type'])) {
                $review_type = $sourceReview['Review']['assessment_type'];
            }

            if (empty($review_type)) {
                $this->Session->setFlash(__('Assessment type is missing for the selected source review.'), 'alerts/flash_error');
                $this->redirect($this->_buildApplicationViewRedirect($application_id));
            }

            $linkToken = 'internal_source_review:' . (int) $sourceReview['Review']['id'];
            $existingLinkedReview = $this->Review->find('first', array(
                'conditions' => array(
                    'Review.application_id' => $application_id,
                    'Review.user_id' => $this->Auth->User('id'),
                    'Review.type' => 'reviewer_comment',
                    'Review.assessment_type' => $review_type,
                    'Review.title' => $linkToken
                ),
                'fields' => array('Review.id', 'Review.status'),
                'order' => array('Review.modified' => 'desc', 'Review.id' => 'desc'),
                'contain' => array()
            ));

            if (!empty($existingLinkedReview['Review']['id'])) {
                $this->Session->setFlash(__('Opened your existing linked response form for this internal review.'), 'alerts/flash_info');
                $this->redirect($this->_buildApplicationViewRedirect($application_id, $existingLinkedReview['Review']['id']));
            }

            $answers = array();
            foreach ((array) $sourceReview['ReviewAnswer'] as $answer) {
                $answers[] = array(
                    'question_type' => $answer['question_type'],
                    'review_type' => !empty($answer['review_type']) ? $answer['review_type'] : $review_type,
                    'question_number' => $answer['question_number'],
                    'question' => $answer['question'],
                    'answer' => '',
                    'workspace' => '',
                    'comment' => ''
                );
            }

            if (empty($answers)) {
                $this->loadModel('ReviewQuestion');
                $all_questions = $this->ReviewQuestion->find('all', array(
                    'conditions' => array('ReviewQuestion.review_type' => $review_type),
                    'order' => array('ReviewQuestion.question_number' => 'asc')
                ));

                foreach ($all_questions as $question) {
                    $answers[] = array(
                        'question_type' => $question['ReviewQuestion']['question_type'],
                        'question_number' => $question['ReviewQuestion']['question_number'],
                        'review_type' => $question['ReviewQuestion']['review_type'],
                        'question' => $question['ReviewQuestion']['question']
                    );
                }
            }

            $data = array(
                'application_id' => $application_id,
                'user_id' => $this->Auth->User('id'),
                'type' => 'reviewer_comment',
                'assessment_type' => $review_type,
                'title' => $linkToken
            );
            if ($this->Review->saveAssociated(array('Review' => $data, 'ReviewAnswer' => $answers))) {
                $this->Session->setFlash(__('A linked copy has been created. You can now update each section for your own response.'), 'alerts/flash_success');
                $this->redirect($this->_buildApplicationViewRedirect($application_id, $this->Review->id));
            } else {
                $this->Session->setFlash(__('The linked internal review copy could not be created. Please contact the administrator.'), 'alerts/flash_error');
                $this->redirect($this->_buildApplicationViewRedirect($application_id));
            }
        }

        $application = $this->Application->find('first', array(
            'conditions' => array('Application.id' => $application_id),
        ));

        $this->loadModel('ReviewQuestion');
        $all_questions = $this->ReviewQuestion->find('all', array('conditions' => array('ReviewQuestion.review_type' => $review_type), 'order' => array('ReviewQuestion.question_number' => 'asc')));
        $answers = [];
        foreach ($all_questions as $question) {
            $dpoint = [
                'question_type' => $question['ReviewQuestion']['question_type'], 'question_number' => $question['ReviewQuestion']['question_number'],
                'review_type' => $question['ReviewQuestion']['review_type'], 'question' => $question['ReviewQuestion']['question']
            ];
            $answers[] = $dpoint;
        }

        $data = array(
            'application_id' => $application_id, 'user_id' => $this->Auth->User('id'), 'type' => 'reviewer_comment',
            'assessment_type' => $review_type
        );
        if ($this->Review->saveAssociated(array('Review' => $data, 'ReviewAnswer' => $answers))) {
            $this->Session->setFlash(__('The review assessment form has been created. Kindly complete review'), 'alerts/flash_success');
            $this->redirect($this->_buildApplicationViewRedirect($application_id, $this->Review->id));
        } else {
            $this->Session->setFlash(__('The review assessment could not be created. Please contact the administrator.'), 'alerts/flash_error');
        }
    }
    public function manager_add($application_id = null, $review_type = null)
    {
        $this->add($application_id, $review_type);
    }
    public function reviewer_add($application_id = null, $review_type = null)
    {
        $this->add($application_id, $review_type);
    }

    public function manager_assign($id = null)
    {
        if ($this->request->is('post')) {
            $this->Review->create();
            $message = $this->request->data['Message'];
            unset($this->request->data['Message']);
            if (!empty($this->request->data)) {
                foreach ($this->request->data as $key => $value) {
                    $this->request->data[$key]['Review']['application_id'] = $id;
                    $this->request->data[$key]['Review']['type'] = 'request';
                    $this->request->data[$key]['Review']['title'] = 'PPB request';
                    $this->request->data[$key]['Review']['text'] = $message['text'];
                }

                if ($this->Review->saveMany($this->request->data)) {

                    //Create new Screening,ScreeningSubmission,Assign stages if not exists
                    $stages = $this->Application->ApplicationStage->find('all', array(
                        'contain' => array(),
                        'conditions' => array('ApplicationStage.application_id' => $id)
                    ));

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=Screening].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array(
                                'ApplicationStage' => array(
                                    'application_id' => $id, 'stage' => 'Screening', 'status' => 'Complete', 'comment' => 'From Manager assign', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')
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
                                $s1['ApplicationStage']['comment'] = 'Manager assign reviewer';
                                $s1['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s1);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=ScreeningSubmission].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'ScreeningSubmission', 'status' => 'Complete', 'comment' => 'From Manager assign', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d'),
                            ))
                        );
                    } else {
                        $var = Hash::extract($stages, '{n}.ApplicationStage[stage=ScreeningSubmission]');
                        if (!empty($var)) {
                            $s2['ApplicationStage'] = min($var);
                            if (empty($s2['ApplicationStage']['end_date'])) {
                                $this->Application->ApplicationStage->create();
                                $s2['ApplicationStage']['status'] = 'Complete';
                                $s2['ApplicationStage']['comment'] = 'Manager assign reviewer';
                                $s2['ApplicationStage']['end_date'] = date('Y-m-d');
                                $this->Application->ApplicationStage->save($s2);
                            }
                        }
                    }

                    if (!Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                        $this->Application->ApplicationStage->create();
                        $this->Application->ApplicationStage->save(
                            array('ApplicationStage' => array(
                                'application_id' => $id, 'stage' => 'Assign', 'status' => 'Current', 'comment' => 'From Manager assign', 'start_date' => date('Y-m-d')
                            ))
                        );
                    }
                    //end stages
                    $doer = $this->Auth->user('name');
                    CakeResque::enqueue('default', 'NotificationShell', array('newAppNotifyReviewer', $this->request->data,$doer));

                    $this->Session->setFlash(__('The reviewers have been notified'), 'alerts/flash_success');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                } else {
                    $this->Session->setFlash(__('The reviewers could not be notified. Please, try again.'));
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
                }
            } else {
                $this->Session->setFlash(__('Please select at least one reviewer'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }
        }
        $users = $this->Review->User->find('list');
        $applications = $this->Review->Application->find('list');
        $this->set(compact('users', 'applications'));
    }

    public function manager_revoke($id=null,$application_id=null) {
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review id'));
        }
        $review = $this->Review->find('first', array('conditions' => array('Review.id' => $id), 'contain' => array()));
        // debug($review);exit;
        if ($this->Review->delete()) {
            // Let's create an audit trail

            $this->loadModel('AuditTrail');
            $audit = array(
                'AuditTrail' => array(
                    'foreign_key' => $application_id,
                    'model' => 'Review',
                    'message' => 'A review previously assigned to '.$review['User']['name'].' has been revoked for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $application_id)) . ' by ' . $this->Auth->User('username'),
                    'ip' =>  $this->Application->field('protocol_no', array('id' => $application_id))
                )
            );
            $this->AuditTrail->Create();
            if ($this->AuditTrail->save($audit)) {
                $this->log($this->args[0], 'audit_success');
            }
            else {
                $this->log('Error creating an audit trail', 'notifications_error');
                $this->log($this->args[0], 'notifications_error');
            }
            $this->Session->setFlash(__('The review request has been revoked'), 'alerts/flash_success');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $review['Review']['application_id']));
        } else {
            $this->Session->setFlash(__('The review request could not be revoked. Please, try again.'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'applications', 'action' => 'view', $review['Review']['application_id']));
        }


	}

    public function reviewer_test($id = null)
    {
        // if (isset($this->request->data['submitReport'])) 
        debug($this->request->data);
    }

    public function assess($id = null, $application_id = null)
    {
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review id'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Review->saveMany($this->request->data['Review'], array('deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Review->saveField('status', 'Submitted');
                    // $results = Hash::extract($this->request->data['Review'], '{n}.ReviewAnswer.{n}.comment');
                    $results = '';
                    $nyabola = $this->Review->ReviewAnswer->find('list', array('conditions' => array('review_id' => $this->Review->id), 'fields' => array('question', 'comment')));
                    foreach ($nyabola as $lenny => $timko) {
                        if (!empty($timko)) {
                            $results .= $lenny . "\n";
                            $results .= $timko . "\n\n";
                        }
                    }
                    // foreach ($this->request->data['Review'] as $ansa) {
                    //     foreach ($ansa as $key => $value) {
                    //         $results .= $value['question']."\n";
                    //         $results .= $value['comment']."\n\n";
                    //     }
                    // }
                    // $this->Review->saveField('summary', implode("\n\n",$results));
                    // Create a Audit Trail
                    $audit = array(
                        'AuditTrail' => array(
                            'foreign_key' => $application_id,
                            'model' => 'Application',
                            'message' => 'A Review has been sumitted for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $application_id)) . ' by ' . $this->Auth->User('username'),
                            'ip' =>  $this->Application->field('protocol_no', array('id' => $application_id))
                        )
                    );
                    $this->AuditTrail->Create();
                    if ($this->AuditTrail->save($audit)) {
                        $this->log($this->args[0], 'audit_success');
                    } else {
                        $this->log('Error creating an audit trail', 'notifications_error');
                        $this->log($this->args[0], 'notifications_error');
                    }
                    $this->Review->saveField('summary', $results);
                    $this->Session->setFlash(__('The review has been submitted'), 'alerts/flash_success');
                } else {
                    $this->Session->setFlash(__('The review has been saved'), 'alerts/flash_success');
                }
                $this->redirect($this->_buildApplicationViewRedirect($application_id, $id));
            } else {
                $this->Session->setFlash(__('The review could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->redirect($this->_buildApplicationViewRedirect($application_id, $id));
        }
    }
    public function manager_assess($id = null, $application_id = null)
    {
        $this->assess($id, $application_id);
    }
    public function reviewer_assess($id = null, $application_id = null)
    {
        $this->assess($id, $application_id);
    }
    public function internalreviewer_assess($id = null, $application_id = null)
    {
        $this->assess($id, $application_id);
    }

    public function summary($id = null, $application_id = null)
    {
        // debug($this->request);
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Review->saveMany($this->request->data['Review'], array('deep' => true))) {
                if (isset($this->request->data['submitReport'])) {
                    $this->Review->saveField('status', 'Summary');
                }
                $this->Session->setFlash(__('The review has been saved'), 'alerts/flash_success');
                $this->redirect($this->_buildApplicationViewRedirect($application_id, $id));
            } else {
                $this->Session->setFlash(__('The review could not be saved. Please, try again.'), 'alerts/flash_error');
            }
        } else {
            $this->redirect($this->_buildApplicationViewRedirect($application_id, $id));
        }
    }
    public function manager_summary($id = null, $application_id = null)
    {
        $this->summary($id, $application_id);
    }
    public function reviewer_summary($id = null, $application_id = null)
    {
        $this->summary($id, $application_id);
    }
    public function internalreviewer_summary($id = null, $application_id = null)
    {
        $this->summary($id, $application_id);
    }

    public function reviewer_edit()
    {
        if ($this->request->is('post')) {
            $this->Review->create();
            $this->request->data['Review']['user_id'] = $this->Auth->User('id');
            $this->request->data['Review']['type'] = 'reviewer_comment';

            if ($this->Auth->password($this->request->data['Review']['password']) === $this->Auth->User('confirm_password')) {
                if ($this->Review->save($this->request->data)) {
                    // Set job to notify managers
                    CakeResque::enqueue('default', 'NotificationShell', array(
                        'reviewerCommentNotifyManagers',
                        $this->request->data['Review']['application_id'], $this->Auth->User('id')
                    ));
                    $this->Session->setFlash(
                        __('Thank you. Your review comments have been sent to PPB'),
                        'alerts/flash_success'
                    );
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                } else {
                    $this->Session->setFlash(__('Your comments could not be saved. Please, try again.'));
                }
            } else {
                $this->Session->setFlash(__('The password you have entered is not correct! Please enter the correct password
                    and try again.'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }
        }
        $users = $this->Review->User->find('list');
        $applications = $this->Review->Application->find('list');
        $this->set(compact('users', 'applications'));
    }

    public function reviewer_respond()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->data['Review']['accepted'])) {
                $this->Session->setFlash(__('Please accept / decline the offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }
            if (empty($this->request->data['Review']['conflict'])) {
                $this->Session->setFlash(__('Please declare your conflict of interest with this offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }


            $review = $this->Review->find('first', array('conditions' => array(
                'Review.application_id' => $this->request->data['Review']['application_id'],
                'Review.user_id' => $this->Auth->User('id'),
                'Review.type' => 'request'
            ), 'contain' => array()));
            if (!empty($review)) {
                $this->Review->create();
                $review['Review']['accepted'] = $this->request->data['Review']['accepted'];
                $review['Review']['recommendation'] = $this->request->data['Review']['recommendation'];
                $review['Review']['conflict'] = $this->request->data['Review']['conflict'];
                if ($this->Auth->password($this->request->data['Review']['password']) === $this->Auth->User('confirm_password')) {
                    if ($this->Review->save($review)) {

                        //Complete assign phase if end date is null and create new Review stage if not exists
                        $stages = $this->Application->ApplicationStage->find('all', array(
                            'contain' => array(),
                            'conditions' => array('ApplicationStage.application_id' => $this->request->data['Review']['application_id'])
                        ));

                        if (Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                            $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                            if (!empty($var)) {
                                $s3['ApplicationStage'] = min($var);
                                if (empty($s3['ApplicationStage']['end_date'])) {
                                    $this->Application->ApplicationStage->create();
                                    $s3['ApplicationStage']['status'] = 'Complete';
                                    $s3['ApplicationStage']['comment'] = 'Reviewer accept';
                                    $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                                    $this->Application->ApplicationStage->save($s3);
                                }
                            }
                        }

                        if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                            $this->Application->ApplicationStage->create();
                            $this->Application->ApplicationStage->save(
                                array(
                                    'ApplicationStage' => array(
                                        'application_id' => $this->request->data['Review']['application_id'], 'stage' => 'Review', 'status' => 'Current', 'comment' => 'From Reviewer accept', 'start_date' => date('Y-m-d')
                                    )
                                )
                            );
                        }
                        //end stages
                        // Create a Audit Trail
                        $this->loadModel('AuditTrail');
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $this->request->data['Review']['application_id'],
                                'model' => 'Review',
                                'message' => 'Reviewer ' . $this->Auth->User('username') . ' ' . $this->request->data['Review']['accepted'] . ' a review request for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $this->request->data['Review']['application_id'])),
                                'ip' =>  $this->Application->field('protocol_no', array('id' => $this->request->data['Review']['application_id']))
                            )
                        );
                        $this->AuditTrail->Create();
                        if ($this->AuditTrail->save($audit)) {
                            $this->log($this->request->data['Review'], 'audit_success');
                        } else {
                            $this->log('Error creating an audit trail', 'audit_error');
                            $this->log($this->request->data['Review'], 'audit_error');
                        }

                        CakeResque::enqueue('default', 'NotificationShell', array('ppbRequestReviewerResponse', $review));

                        if ($review['Review']['accepted'] == 'accepted') {
                            $this->Session->setFlash(__('Thank you. Your response has been sent to PPB. You may proceed to review the application'),  'alerts/flash_success');
                            $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                        } elseif ($review['Review']['accepted'] == 'declined') {
                            $this->Session->setFlash(__('Thank you for your prompt response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'applications', 'action' => 'index'));
                        } else {
                            $this->Session->setFlash(__('NO response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        }
                    } else {
                        $this->Session->setFlash(__('The review could not be saved. Please, try again.'));
                        $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                    }
                } else {
                    $this->Session->setFlash(__('The password you have entered is not correct! Please enter your correct password
                        and try again.'), 'alerts/flash_error');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                }
            } else {
                $this->Session->setFlash(__('Seems the request review is empty!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
        } else {
            $this->Session->setFlash(__('Invalid method signature!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        // $users = $this->Review->User->find('list');
        // $applications = $this->Review->Application->find('list');
        // $this->set(compact('users', 'applications'));
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
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Review->save($this->request->data)) {
                $this->Session->setFlash(__('The review has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The review could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Review->read(null, $id);
        }
        $reviewers = $this->Review->Reviewer->find('list');
        $this->set(compact('reviewers'));
    }


    /**
     * download methods
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    private function download_assessment($id = null)
    {
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review assessment'));
        }
        $review = $this->Review->find(
            'first',
            array(
                'conditions' => array('Review.id' => $id),
                'contain' => array('ReviewAnswer', 'Application')
            )
        );

        // debug($review['Application']);
        $disp  = $review['Review'];
        $disp['ReviewAnswer'] = $review['ReviewAnswer'];
        // $review  = $this->Review->read(null, $id);
        $this->set('rreview', $disp);
        $this->set('application', $review);
        $this->set('akey', $review['Review']['application_id']);
        $this->pdfConfig = array('filename' => 'Review_Assessment_' . $id,  'orientation' => 'portrait');
        $this->render('download_assessment');
    }
    public function manager_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }
    public function inspector_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }
    public function reviewer_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }
    public function internalreviewer_download_assessment($id = null)
    {
        $this->download_assessment($id);
    }

    private function download_summary($id = null)
    {
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review summary'));
        }
        $review = $this->Review->find(
            'first',
            array(
                'conditions' => array('Review.id' => $id),
                'contain' => array('ReviewAnswer', 'Application')
            )
        );
        $disp  = $review['Review'];
        $disp['ReviewAnswer'] = $review['ReviewAnswer'];
        $this->set('rreview', $disp);
        $this->set('application', $review);
        $this->set('akey', $review['Review']['application_id']);
        $this->pdfConfig = array('filename' => 'review_Summary_' . $id,  'orientation' => 'portrait');
        $this->render('download_summary');
    }
    public function manager_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function inspector_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function applicant_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function reviewer_download_summary($id = null)
    {
        $this->download_summary($id);
    }
    public function internalreviewer_download_summary($id = null)
    {
        $this->download_summary($id);
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
        $this->Review->id = $id;
        if (!$this->Review->exists()) {
            throw new NotFoundException(__('Invalid review'));
        }
        if ($this->Review->delete()) {
            $this->Session->setFlash(__('Review deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Review was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    public function manager_assign_internal($id = null)
    {
        if ($this->request->is('post')) {
            $messageText = isset($this->request->data['Message']['text']) ? $this->request->data['Message']['text'] : '';
            $selectedUserIds = $this->_extractSelectedReviewerIds($this->request->data);

            if (count($selectedUserIds) !== 1) {
                $this->Session->setFlash(__('Assign one internal reviewer at a time.'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }

            $selectedUserId = (int) $selectedUserIds[0];
            $alreadyAssigned = $this->Review->find('count', array(
                'conditions' => array(
                    'Review.application_id' => $id,
                    'Review.user_id' => $selectedUserId,
                    'Review.category' => 1,
                    'Review.type' => 'request',
                ),
                'contain' => array()
            ));
            if ($alreadyAssigned > 0) {
                $this->Session->setFlash(__('This internal reviewer is already assigned to the protocol.'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }

            $blockingAssignment = $this->_findPendingInternalReviewAssignment($id);
            if (!empty($blockingAssignment)) {
                $blockingUserId = (int) $blockingAssignment['user_id'];
                $blockingUserName = $this->User->field('name', array('User.id' => $blockingUserId));
                if (empty($blockingUserName)) {
                    $blockingUserName = 'reviewer #' . $blockingUserId;
                }
                $this->Session->setFlash(
                    sprintf(__('Assign one internal reviewer at a time. Wait until %s submits assessment feedback.'), $blockingUserName),
                    'alerts/flash_error'
                );
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }

            $saveData = array(
                array(
                    'Review' => array(
                        'application_id' => $id,
                        'user_id' => $selectedUserId,
                        'type' => 'request',
                        'category' => '1',
                        'title' => 'PPB request',
                        'text' => $messageText,
                    )
                )
            );

            $this->Review->create();
            if ($this->Review->saveMany($saveData)) {
                //Create new Screening,ScreeningSubmission,Assign stages if not exists
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
                                'comment' => 'From Manager assign',
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
                            $s1['ApplicationStage']['comment'] = 'Manager assign reviewer';
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
                            'comment' => 'From Manager assign',
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
                            $s2['ApplicationStage']['comment'] = 'Manager assign reviewer';
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
                            'status' => 'Current',
                            'comment' => 'From Manager assign',
                            'start_date' => date('Y-m-d')
                        ))
                    );
                }
                //end stages
                $doer = $this->Auth->user('name');
                CakeResque::enqueue('default', 'NotificationShell', array('newAppNotifyReviewer', $saveData, $doer));

                $this->Session->setFlash(__('The reviewers have been notified'), 'alerts/flash_success');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            } else {
                $this->Session->setFlash(__('The reviewers could not be notified. Please, try again.'));
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $id));
            }
        }
        $users = $this->Review->User->find('list');
        $applications = $this->Review->Application->find('list');
        $this->set(compact('users', 'applications'));
    }


    private function _extractSelectedReviewerIds($payload = array())
    {
        $selected = array();
        if (isset($payload['Review']['user_id']) && $payload['Review']['user_id'] !== '') {
            $selected[] = (int) $payload['Review']['user_id'];
        }
        foreach ($payload as $row) {
            if (is_array($row) && isset($row['Review']['user_id']) && $row['Review']['user_id'] !== '') {
                $selected[] = (int) $row['Review']['user_id'];
            }
        }

        return array_values(array_unique(array_filter($selected)));
    }

    private function _findPendingInternalReviewAssignment($applicationId)
    {
        $requests = $this->Review->find('all', array(
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.category' => 1,
                'Review.type' => 'request'
            ),
            'fields' => array('Review.id', 'Review.user_id', 'Review.accepted', 'Review.created'),
            'order' => array('Review.created' => 'ASC', 'Review.id' => 'ASC'),
            'contain' => array()
        ));

        foreach ($requests as $request) {
            $accepted = strtolower(trim((string) $request['Review']['accepted']));
            if ($accepted === 'declined') {
                continue;
            }

            if (!$this->_internalReviewerHasAssessmentFeedback($applicationId, (int) $request['Review']['user_id'])) {
                return $request['Review'];
            }
        }

        return array();
    }

    private function _internalReviewerHasAssessmentFeedback($applicationId, $userId)
    {
        $submittedReviews = $this->Review->find('count', array(
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.user_id' => $userId,
                'Review.type' => 'reviewer_comment',
                'Review.status' => array('Submitted', 'Summary')
            ),
            'contain' => array()
        ));
        if ($submittedReviews > 0) {
            return true;
        }

        $answerCount = $this->Review->find('count', array(
            'joins' => array(
                array(
                    'table' => 'review_answers',
                    'alias' => 'ReviewAssessmentAnswer',
                    'type' => 'INNER',
                    'conditions' => array('ReviewAssessmentAnswer.review_id = Review.id')
                )
            ),
            'conditions' => array(
                'Review.application_id' => $applicationId,
                'Review.user_id' => $userId,
                'Review.type' => 'reviewer_comment',
                'OR' => array(
                    "TRIM(COALESCE(ReviewAssessmentAnswer.answer, '')) <> ''",
                    "TRIM(COALESCE(ReviewAssessmentAnswer.workspace, '')) <> ''",
                    "TRIM(COALESCE(ReviewAssessmentAnswer.comment, '')) <> ''"
                )
            ),
            'contain' => array()
        ));

        return ($answerCount > 0);
    }

    public function internalreviewer_respond()
    {
        $this->main_respond();
    }
    public function main_respond()
    {
        if ($this->request->is('post')) {
            if (empty($this->request->data['Review']['accepted'])) {
                $this->Session->setFlash(__('Please accept / decline the offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }
            if (empty($this->request->data['Review']['conflict'])) {
                $this->Session->setFlash(__('Please declare your conflict of interest with this offer!!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
            }


            $review = $this->Review->find('first', array('conditions' => array(
                'Review.application_id' => $this->request->data['Review']['application_id'],
                'Review.user_id' => $this->Auth->User('id'),
                'Review.type' => 'request'
            ), 'contain' => array()));
            
            if (!empty($review)) {
                $this->Review->create();
                $review['Review']['accepted'] = $this->request->data['Review']['accepted'];
                $review['Review']['recommendation'] = $this->request->data['Review']['recommendation'];
                $review['Review']['conflict'] = $this->request->data['Review']['conflict'];
                if ($this->Auth->password($this->request->data['Review']['password']) === $this->Auth->User('confirm_password')) {
                    if ($this->Review->save($review)) {

                        //Complete assign phase if end date is null and create new Review stage if not exists
                        $stages = $this->Application->ApplicationStage->find('all', array(
                            'contain' => array(),
                            'conditions' => array('ApplicationStage.application_id' => $this->request->data['Review']['application_id'])
                        ));

                        if (Hash::check($stages, '{n}.ApplicationStage[stage=Assign].id')) {
                            $var = Hash::extract($stages, '{n}.ApplicationStage[stage=Assign]');
                            if (!empty($var)) {
                                $s3['ApplicationStage'] = min($var);
                                if (empty($s3['ApplicationStage']['end_date'])) {
                                    $this->Application->ApplicationStage->create();
                                    $s3['ApplicationStage']['status'] = 'Complete';
                                    $s3['ApplicationStage']['comment'] = 'Reviewer accept';
                                    $s3['ApplicationStage']['end_date'] = date('Y-m-d');
                                    $this->Application->ApplicationStage->save($s3);
                                }
                            }
                        }

                        if (!Hash::check($stages, '{n}.ApplicationStage[stage=Review].id')) {
                            $this->Application->ApplicationStage->create();
                            $this->Application->ApplicationStage->save(
                                array(
                                    'ApplicationStage' => array(
                                        'application_id' => $this->request->data['Review']['application_id'],
                                        'stage' => 'Review',
                                        'status' => 'Current',
                                        'comment' => 'From Reviewer accept',
                                        'start_date' => date('Y-m-d')
                                    )
                                )
                            );
                        }
                        //end stages
                        // Create a Audit Trail
                        $this->loadModel('AuditTrail');
                        $audit = array(
                            'AuditTrail' => array(
                                'foreign_key' => $this->request->data['Review']['application_id'],
                                'model' => 'Review',
                                'message' => 'Reviewer ' . $this->Auth->User('username') . ' ' . $this->request->data['Review']['accepted'] . ' a review request for the report with protocol number ' .  $this->Application->field('protocol_no', array('id' => $this->request->data['Review']['application_id'])),
                                'ip' =>  $this->Application->field('protocol_no', array('id' => $this->request->data['Review']['application_id']))
                            )
                        );
                        $this->AuditTrail->Create();
                        if ($this->AuditTrail->save($audit)) {
                            $this->log($this->request->data['Review'], 'audit_success');
                        } else {
                            $this->log('Error creating an audit trail', 'audit_error');
                            $this->log($this->request->data['Review'], 'audit_error');
                        }

                        CakeResque::enqueue('default', 'NotificationShell', array('ppbRequestReviewerResponse', $review));

                        if ($review['Review']['accepted'] == 'accepted') {
                            $this->Session->setFlash(__('Thank you. Your response has been sent to PPB. You may proceed to review the application'),  'alerts/flash_success');
                            $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                        } elseif ($review['Review']['accepted'] == 'declined') {
                            $this->Session->setFlash(__('Thank you for your prompt response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'applications', 'action' => 'index'));
                        } else {
                            $this->Session->setFlash(__('NO response.'),  'alerts/flash_info');
                            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
                        }
                    } else {
                        $this->Session->setFlash(__('The review could not be saved. Please, try again.'));
                        $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                    }
                } else {
                    $this->Session->setFlash(__('The password you have entered is not correct! Please enter your correct password
                        and try again.'), 'alerts/flash_error');
                    $this->redirect(array('controller' => 'applications', 'action' => 'view', $this->request->data['Review']['application_id']));
                }
            } else {
                $this->Session->setFlash(__('Seems the request review is empty!'), 'alerts/flash_error');
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            }
        } else {
            $this->Session->setFlash(__('Invalid method signature!'), 'alerts/flash_error');
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        // $users = $this->Review->User->find('list');
        // $applications = $this->Review->Application->find('list');
        // $this->set(compact('users', 'applications'));
    }

    public function internalreviewer_add($application_id = null, $review_type = null, $source_review_id = null)
    {
        $this->add($application_id, $review_type, $source_review_id);
    }
}

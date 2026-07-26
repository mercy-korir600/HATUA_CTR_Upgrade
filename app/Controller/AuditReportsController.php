<?php
App::uses('AppController', 'Controller');

/**
 * AuditReports Controller
 *
 * @property AuditReport $AuditReport
 * @property AuditChecklist $AuditChecklist
 * @property Application $Application
 * @property StudyAuditor $StudyAuditor
 */
class AuditReportsController extends AppController {

    public $uses = array('AuditReport', 'AuditChecklist', 'Application', 'StudyAuditor');

    public function beforeFilter() {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('auditor_edit');
        }
    }

    /**
     * auditor_edit method
     *
     * Handles saving progress and submitting official audit reports for Auditors.
     *
     * @param string $id AuditReport ID or Application ID
     * @param string $application_id Application ID
     * @return void
     */
    public function auditor_edit($id = null, $application_id = null) {
        if (!$application_id) {
            $application_id = $id;
        }

        $user_id = $this->Auth->User('id');

        $isAssigned = $this->StudyAuditor->find('count', array(
            'conditions' => array(
                'StudyAuditor.user_id' => $user_id,
                'StudyAuditor.application_id' => $application_id
            )
        ));

        if ($isAssigned == 0 && $this->Auth->User('group_id') != '1' && $this->Auth->User('group_id') != '2') {
            $this->Session->setFlash(__('You are not authorized to perform audit actions on this protocol.'), 'alerts/flash_error');
            return $this->redirect(array('controller' => 'applications', 'action' => 'index', 'auditor' => true));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $reportData = $this->request->data;
            
            if (empty($reportData['AuditReport']['application_id'])) {
                $reportData['AuditReport']['application_id'] = $application_id;
            }
            $reportData['AuditReport']['user_id'] = $user_id;

            // Determine if submitting report or saving draft progress
            if (isset($this->request->data['submit_report'])) {
                $reportData['AuditReport']['submitted'] = 1;
            }

            if ($this->AuditReport->saveAssociated($reportData, array('deep' => true))) {
                if (isset($this->request->data['submit_report'])) {
                    $this->Session->setFlash(__('Official Audit Report submitted successfully.'), 'alerts/flash_success');
                } else {
                    $this->Session->setFlash(__('Audit findings and progress saved successfully.'), 'alerts/flash_success');
                }
                return $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'auditor' => true));
            } else {
                $this->Session->setFlash(__('Unable to save audit report. Please check required fields and try again.'), 'alerts/flash_error');
                return $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'auditor' => true));
            }
        } else {
            return $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'auditor' => true));
        }
    }
}

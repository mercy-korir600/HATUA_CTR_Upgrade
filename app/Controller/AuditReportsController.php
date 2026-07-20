<?php                                                                                                                  
    App::uses('AppController', 'Controller');                                                                              
                                                                                                                           
    class AuditReportsController extends AppController {                                                                   
                                                                                                                           
        public function auditor_edit($application_id = null) {                                                                                                                                  
            $report = $this->AuditReport->find('first', array(                                                             
                'conditions' => array(                                                                                     
                    'AuditReport.application_id' => $application_id,                                                       
                    'AuditReport.user_id' => $this->Auth->user('id')                                                       
                ),                                                                                                         
                'contain' => array('AuditChecklist')                                                                       
            ));                                                                                                            
                                                                                                                           
            if (empty($report)) {                                                                                          
                                                                              
                $this->AuditReport->create();                                                                              
                $this->AuditReport->save(array(                                                                            
                    'application_id' => $application_id,                                                                   
                    'user_id' => $this->Auth->user('id'),                                                                  
                    'submitted' => 0                                                                                       
                ));                                                                                                        
                $report_id = $this->AuditReport->id;                                                                       
                                                                                                                                                                                                
                $sections = array('Study Details', 'Reviewer Comments', 'Site Inspections', 'SAE Logs', 'Protocol          
  Deviations');                                                                                                            
                foreach ($sections as $section) {                                                                          
                    $this->AuditReport->AuditChecklist->create();                                                          
                    $this->AuditReport->AuditChecklist->save(array(                                                        
                        'audit_report_id' => $report_id,                                                                   
                        'section_name' => $section                                                                         
                    ));                                                                                                    
                }                                                                                                          
                return $this->redirect(array('action' => 'edit', $application_id, 'auditor' => true));                                        
            }                                                                                                              
                                                                                                                           
            if ($this->request->is(array('post', 'put'))) {                                                                                                                                                        
                if ($this->request->data['AuditReport']['submit_action'] === 'submit') {                                   
                    $this->request->data['AuditReport']['submitted'] = 1;                                    
                }                                                                                                          
                  if ($this->AuditReport->saveAssociated($this->request->data)) {
        $this->Session->setFlash(__('Audit draft updated successfully'), 'alerts/flash_success');
  
        // If submitting final report, return to application view. If draft, stay on edit page!
        if (isset($this->request->data['AuditReport']['submit_action']) && $this->request->data['AuditReport']['submit_action'] === 'submit') {
            return $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id, 'auditor' => true));
        } else {
            return $this->redirect(array('action' => 'edit', $application_id, 'auditor' => true));
        }

     } else {
 $this->Session->setFlash(__('The audit data could not be saved. Please check for missing fields and try again.'), 'alerts/flash_error');
    }                                                                                                      
                $this->request->data = $report;                                                                            
            }                                                                                                              
                                                                                                                           
            $this->set('report', $report);                                                                                 
            $this->set('application_id', $application_id);                                                                 
        }                                                                                                                  
    }                                                 
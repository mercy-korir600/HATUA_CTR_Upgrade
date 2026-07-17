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
                return $this->redirect(array('action' => 'edit', $application_id));                                        
            }                                                                                                              
                                                                                                                           
            if ($this->request->is(array('post', 'put'))) {                                                                                                                                                        
                if ($this->request->data['AuditReport']['submit_action'] === 'submit') {                                   
                    $this->request->data['AuditReport']['submitted'] = 1; // Finalized                                     
                }                                                                                                          
                                                                                                                           
                if ($this->AuditReport->saveAssociated($this->request->data)) {                                            
                    $this->Session->setFlash(__('Audit data updated successfully'), 'alerts/flash_success');               
                    return $this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id,      
  'auditor' => true));                                                                                                     
                }                                                                                                          
            } else {                                                                                                       
                $this->request->data = $report;                                                                            
            }                                                                                                              
                                                                                                                           
            $this->set('report', $report);                                                                                 
            $this->set('application_id', $application_id);                                                                 
        }                                                                                                                  
    }                                                 
 <?php                                                                                                                  
    App::uses('AppModel', 'Model');                                                                                        
                                                                                                                           
    class AuditChecklist extends AppModel {                                                                                
        public $belongsTo = array(                                                                                         
            'AuditReport' => array('className' => 'AuditReport', 'foreignKey' => 'audit_report_id')                        
        );                                                                                                                 
    }        
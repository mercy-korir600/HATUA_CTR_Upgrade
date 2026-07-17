 <?php                                                                                                                  
    App::uses('AppModel', 'Model');                                                                                        
                                                                                                                           
    class AuditReport extends AppModel {                                                                                   
        public $actsAs = array('Containable');                                                                             
                                                                                                                           
        public $belongsTo = array(                                                                                         
            'Application' => array('className' => 'Application', 'foreignKey' => 'application_id'),                        
            'User' => array('className' => 'User', 'foreignKey' => 'user_id')                                              
        );                                                                                                                 
                                                                                                                           
        public $hasMany = array(                                                                                           
            'AuditChecklist' => array(                                                                                     
                'className' => 'AuditChecklist',                                                                           
                'foreignKey' => 'audit_report_id',                                                                         
                'dependent' => true                                                                                        
            )                                                                                                              
        );                                                                                                                 
    }     
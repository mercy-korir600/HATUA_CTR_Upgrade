 <div class="row-fluid">                                                                                                
      <div class="span12">                                                                                                 
        <h3>Conduct Audit Report</h3>                                                                                      
        <hr>                                                                                                               
        <?php echo $this->Form->create('AuditReport', array('class' => 'form-horizontal')); ?>                             
        <?php echo $this->Form->input('id'); ?>                                                                            
        <?php echo $this->Form->input('application_id', array('type' => 'hidden')); ?>                                     
                                                                                                                           
        <h4>Structured Audit Checklist</h4>                                                                                
        <table class="table table-bordered table-striped">                                                                 
          <thead>                                                                                                          
            <tr>                                                                                                           
              <th>Section</th>                                                                                             
              <th>Compliance Status</th>                                                                                   
              <th>Observations</th>                                                                                        
              <th>Comments</th>                                                                                            
            </tr>                                                                                                          
          </thead>                                                                                                         
          <tbody>                                                                                                          
            <?php foreach ($this->request->data['AuditChecklist'] as $key => $checklist): ?>                               
              <tr>                                                                                                         
                <td>                                                                                                       
                  <?php echo h($checklist['section_name']); ?>                                                             
                  <?php echo $this->Form->input("AuditChecklist.{$key}.id", array('type' => 'hidden')); ?>                 
                </td>                                                                                                      
                <td>                                                                                                       
                  <?php echo $this->Form->input("AuditChecklist.{$key}.compliance_status", array(                          
                    'options' => array('Yes' => 'Yes', 'No' => 'No', 'N/A' => 'N/A'),                                      
                    'empty' => true, 'label' => false                                                                      
                  )); ?>                                                                                                   
                </td>                                                                                                      
                <td><?php echo $this->Form->input("AuditChecklist.{$key}.observation", array('type' => 'textarea', 'rows'  
  => 2, 'label' => false, 'class' => 'span12')); ?></td>                                                                   
                <td><?php echo $this->Form->input("AuditChecklist.{$key}.comments", array('type' => 'textarea', 'rows' => 2,
  'label' => false, 'class' => 'span12')); ?></td>                                                                         
              </tr>                                                                                                        
            <?php endforeach; ?>                                                                                           
          </tbody>                                                                                                         
        </table>                                                                                                           
                                                                                                                           
        <hr>                                                                                                               
        <h4>Final Report Details</h4>                                                                                      
        <?php                                                                                                              
          echo $this->Form->input('outcome', array(                                                                        
            'type' => 'select',                                                                                            
            'options' => array(                                                                                            
              'Compliant' => 'Compliant',                                                                                  
              'Compliant with Conditions (CAPA Required)' => 'Compliant with Conditions (CAPA Required)',                  
              'Non-Compliant (Suspension/Revocation Recommended)' => 'Non-Compliant (Suspension/Revocation Recommended)'   
            ),                                                                                                             
            'empty' => '-- Select Outcome --',                                                                             
            'label' => 'Audit Outcome *',                                                                                  
            'required' => true                                                                                             
          ));                                                                                                              
          echo $this->Form->input('overall_comments', array('type' => 'textarea', 'rows' => 4, 'label' => 'Overall         
  Comments'));                                                                                                             
          echo $this->Form->input('recommendations', array('type' => 'textarea', 'rows' => 4, 'label' =>                   
  'Recommendations'));                                                                                                     
        ?>                                                                                                                 
                                                                                                                           
        <div class="form-actions">                                                                                         
          <?php echo $this->Form->button('Save Progress', array('name' => 'data[AuditReport][submit_action]', 'value' =>   
  'save', 'class' => 'btn btn-info')); ?>                                                                                  
          <?php echo $this->Form->button('Submit Final Report', array('name' => 'data[AuditReport][submit_action]', 'value'
  => 'submit', 'class' => 'btn btn-success')); ?>                                                                          
        </div>                                                                                                             
        <?php echo $this->Form->end(); ?>                                                                                  
      </div>                                                                                                               
    </div>                              
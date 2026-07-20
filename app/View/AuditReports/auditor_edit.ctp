   <div class="row-fluid">                                                                                                
      <div class="span12">                                                                                                 
        <?php echo $this->Session->flash(); ?>
        <div class="well" style="background-color: #f5f5f5; border-left: 4px solid #006dcc;">                              
          <h4><i class="icon-file-text"></i> Clinical Trial Audit Report & Findings</h4>                                   
          <p class="muted">Record compliance observations, log findings, and file the official audit report outcome.</p>   
        </div>                                                                                                             
                                                                                                                           
        <?php echo $this->Form->create('AuditReport', array('class' => 'form-horizontal')); ?>                             
        <?php echo $this->Form->input('id'); ?>                                                                            
        <?php echo $this->Form->input('application_id', array('type' => 'hidden')); ?>                                     
                                                                                                                           
                                                          
        <legend><i class="icon-list-check"></i> Section 1: Structured Audit Checklist & Observations</legend>              
        <table class="table table-bordered table-striped" style="margin-bottom: 25px;">                                    
          <thead>                                                                                                          
            <tr style="background-color: #e9e9e9;">                                                                        
              <th style="width: 20%;">Audit Area / Section</th>                                                            
              <th style="width: 15%;">Compliance Status</th>                                                               
              <th style="width: 35%;">Compliance Observations & Findings</th>                                              
              <th style="width: 30%;">Recommendations / CAPA Requirements</th>                                             
            </tr>                                                                                                          
          </thead>                                                                                                         
          <tbody>                                                                                                          
            <?php foreach ($this->request->data['AuditChecklist'] as $key => $checklist): ?>                               
              <tr>                                                                                                         
                <td>                                                                                                       
                  <strong><?php echo h($checklist['section_name']); ?></strong>                                            
                  <?php echo $this->Form->input("AuditChecklist.{$key}.id", array('type' => 'hidden')); ?>                 
                  <?php echo $this->Form->input("AuditChecklist.{$key}.section_name", array('type' => 'hidden')); ?>       
                </td>                                                                                                      
                <td>                                                                                                       
                  <?php echo $this->Form->input("AuditChecklist.{$key}.compliance_status", array(                          
                    'options' => array(                                                                                    
                      'Compliant' => 'Compliant',                                                                          
                      'Non-Compliant' => 'Non-Compliant',                                                                  
                      'Needs Improvement' => 'Needs Improvement',                                                          
                      'N/A' => 'N/A'                                                                                       
                    ),                                                                                                     
                    'empty' => '-- Select --',                                                                             
                    'label' => false,                                                                                      
                    'class' => 'span12'                                                                                    
                  )); ?>                                                                                                   
                </td>                                                                                                      
                <td>                                                                                                       
                  <?php                                                                                                    
                    $placeholder = 'e.g., Investigator CV has expired';                                                    
                    if ($checklist['section_name'] == 'SAE / SUSAR Reporting Timelines') {                                 
                      $placeholder = 'e.g., SAE ID-203 was reported 12 days late';                                         
                    }                                                                                                      
                    echo $this->Form->input("AuditChecklist.{$key}.observation", array(                                    
                      'type' => 'textarea',                                                                                
                      'rows' => 3,                                                                                         
                      'label' => false,                                                                                    
                      'placeholder' => $placeholder,                                                                       
                      'class' => 'span12'                                                                                  
                    ));                                                                                                    
                  ?>                                                                                                       
                </td>                                                                                                      
                <td>                                                                                                       
                  <?php echo $this->Form->input("AuditChecklist.{$key}.comments", array(                                   
                    'type' => 'textarea',                                                                                  
                    'rows' => 3,                                                                                           
                    'label' => false,                                                                                      
                    'placeholder' => 'Enter specific recommendations or CAPA required for this finding...',                
                    'class' => 'span12'                                                                                    
                  )); ?>                                                                                                   
                </td>                                                                                                      
              </tr>                                                                                                        
            <?php endforeach; ?>                                                                                           
          </tbody>                                                                                                         
        </table>                                                                                                            
                                                                                                                           
                                                             
        <legend><i class="icon-gavel"></i> Section 2: Official Audit Report Submission</legend>                            
                                                                                                                           
        <div class="control-group">                                                                                        
          <label class="control-label" style="font-weight: bold;">Audit Outcome <span class="text-error">*</span></label>  
          <div class="controls">                                                                                           
            <?php echo $this->Form->input('outcome', array(                                                                
              'type' => 'select',                                                                                          
              'options' => array(                                                                                          
                'Compliant' => 'Compliant',                                                                                
                'Compliant with Conditions (CAPA Required)' => 'Compliant with Conditions (CAPA Required)',                
                'Non-Compliant (Suspension/Revocation Recommended)' => 'Non-Compliant (Suspension/Revocation Recommended)' 
              ),                                                                                                           
              'empty' => '-- Select Official Audit Outcome --',                                                            
              'label' => false,                                                                                            
              'class' => 'span8',                                                                                          
              'required' => false                                                                                         
            )); ?>                                                                                                         
          </div>                                                                                                           
        </div>                                                                                                             
                                                                                                                           
        <div class="control-group">                                                                                        
          <label class="control-label">Overall Audit Summary</label>                                                       
          <div class="controls">                                                                                           
            <?php echo $this->Form->input('overall_comments', array(                                                       
              'type' => 'textarea',                                                                                        
              'rows' => 4,                                                                                                 
              'label' => false,                                                                                            
              'placeholder' => 'Provide a comprehensive summary of the audit findings and observations...',                
              'class' => 'span8'                                                                                           
            )); ?>                                                                                                         
          </div>                                                                                                           
        </div>                                                                                                             
                                                                                                                           
        <div class="control-group">                                                                                        
          <label class="control-label">CAPA & Action Items</label>                                                         
          <div class="controls">                                                                                           
            <?php echo $this->Form->input('recommendations', array(                                                        
              'type' => 'textarea',                                                                                        
              'rows' => 4,                                                                                                 
              'label' => false,                                                                                            
              'placeholder' => 'Specify required Corrective and Preventive Actions (CAPA) or recommended regulatory        
  actions...',                                                                                                             
              'class' => 'span8'                                                                                           
            )); ?>                                                                                                         
          </div>                                                                                                           
        </div>                                                                                                             
                                                                                                                                                                                             
        <div class="form-actions" style="padding-left: 180px;">                                                            
          <?php                                                                                                            
            echo $this->Form->button('<i class="icon-save"></i> Save Progress (Draft)', array(                             
              'name' => 'data[AuditReport][submit_action]',                                                                
              'value' => 'save',                                                                                           
              'type' => 'submit',                                                                                          
              'class' => 'btn btn-info btn-large'                                                                          
            ));                                                                                                            
          ?>                                                                                                               
          &nbsp;&nbsp;                                                                                                     
          <?php                                                                                                            
            echo $this->Form->button('<i class="icon-check"></i> Submit Official Audit Report', array(                     
              'name' => 'data[AuditReport][submit_action]',                                                                
              'value' => 'submit',                                                                                         
              'type' => 'submit',
              'class' => 'btn btn-success btn-large',
              'confirm' => 'Are you sure you want to file this official audit report? Once submitted, the outcome decision 
  will be finalized.'
            )); 
          ?>
        </div>
  
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
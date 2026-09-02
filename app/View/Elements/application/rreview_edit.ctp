<?php 
  echo $this->Session->flash();
?>
<style>                                                                                                                                                                                                
      .tab-content, .tab-pane {                                                                                                                                                                            
        overflow: visible !important;                                                                                                                                                                      
      }                                                                                                                                                                                                    
    </style>
<h3 style="text-align: center;"> <?php echo ucfirst($rreview['assessment_type']); ?> Assessment Form</h3>   
<hr class="soften" style="margin: 10px 0px;">

  
<?php
  if ($this->Session->read('Auth.User.id') == $rreview['user_id'] and $rreview['status'] == 'Unsubmitted') {

    echo $this->Form->create('Review', array(
        'url' => array('controller' => 'reviews', 'action' => 'assess', $rreview['id'], $rreview['application_id']),
        'type' => 'file',
        'class' => 'form-inline',
        'inputDefaults' => array(
          // 'div' => array('class' => 'control-group'),
          'label' => array('class' => 'control-label'),
          // 'between' => '<div class="controls">',
          // 'after' => '</div>',
          'class' => '',
          'format' => array('before', 'label', 'between', 'input', 'after','error'),
          'error' => array('attributes' => array( 'class' => 'controls help-block')),
         ),
    ));
    echo $this->Form->input('Review.'.$akey.'.id', array('value' => $rreview['id'], 'type' => 'hidden'));
?>
  <div class="row-fluid" style="display: flex; align-items: stretch; overflow: visible;">                                                                                                                
      <div class="span10" style="float: none; width: 83%; margin-left: 0;">   


  <table class="table table-bordered table-condensed">
    <tbody>
      <tr>
        <th class="my-well" style="width: 45%">Study Title</th>
        <td><?php echo $application['Application']['study_title'];?></td>
      </tr>
      <tr>
        <th class="my-well">Short Title</th>
        <td><?php echo $application['Application']['short_title'];?></td>
      </tr>
      <tr>
        <th class="my-well">Protocol No</th>
        <td><?php echo $application['Application']['protocol_no'];?></td>
      </tr>
      <tr>
        <th class="my-well">Investigation medicinal product</th>
        <td><?php echo $application['Application']['study_drug'];?></td>
      </tr>
    </tbody>        
  </table>

  <?php
    // $questions = $this->requestAction('/review_questions/questions/'.$rreview['assessment_type']);
  ?>
  <table class="table table-bordered table-condensed">
    <thead><th></th><th width="35%"></th></thead>
    <tbody>
        <?php
        // foreach ($rreview['ReviewAnswer'] as $site_answer) { 
        // for ($i = 0; $i <= count($questions)-1; $i++) {
        for ($i = 0; $i <= count($rreview['ReviewAnswer'])-1; $i++) {
            echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.id', array('type' => 'hidden', 'value' => $rreview['ReviewAnswer'][$i]['id']));
            echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.rreview_id', array('type' => 'hidden', 'value' => $rreview['id']));
            if ($rreview['ReviewAnswer'][$i]['question_type'] == 'label') {
                 echo "<tr class='success'><td colspan='2'><strong>".$rreview['ReviewAnswer'][$i]['question']."</strong></td></tr>";                     
            }  elseif($rreview['ReviewAnswer'][$i]['question_type'] == 'yesno') {                    
                echo "<tr>";   
                    echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                    echo "<td>";
                    echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.answer', array(
                      'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                      'class' => 'answer'.$i.str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']),
                      'before' => '
                        <input type="hidden" value="" id="Review'.$akey.$i.'ReviewAnswer_" name="data[Review]['.$akey.'][ReviewAnswer]['.$i.'][answer]"> <label class="radio inline">',
                      'after' => '</label>',
                      'options' => array('Yes' => 'Yes'),
                    ));                                
                    echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.answer', array(
                      'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                      'class' => 'answer'.$i.str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']),
                      'before' => '<label class="radio inline">', 'after' => '</label>',
                      'options' => array('No' => 'No')
                    ));     
                    echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.answer', array(
                      'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 
                      'class' => 'answer'.$i.str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']),
                      'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                      'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                      'before' => '<label class="radio inline">',
                      'after' => '</label>
                            <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                            onclick="$(\'.answer'.$i.str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']).'\').removeAttr(\'checked disabled\')">
                            <em class="accordion-toggle"><i class="icon-remove-circle"></i> </em></a> </span>
                           ',
                      'options' => array('N/A' => 'N/A'),
                    ));
                    echo "</td>";
                  echo '</tr>';
            } elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'text') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.answer',
                    array('type' => 'textarea', 'label' => false, 'rows' => 3));
                echo "</td></tr>"; 
            }  elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'workspace') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.workspace',
                    array('type' => 'textarea', 'label' => false, 'rows' => 3));
                echo "</td></tr>"; 
            }  elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'comment') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.comment',
                    array('type' => 'textarea', 'label' => false, 'rows' => 2));
                echo "</td></tr>"; 
            } 
        }

        ?>     
     </tbody>        
    </table>

      </div>                                                                                                                              
        <div class="span2" style="float: none; width: 15%; margin-left: 2%;">                                                                                                                                
        <div style="position: -webkit-sticky; position: sticky; top: calc(50vh - 90px) !important; z-index: 100;">                                                                                                                 
          <div class="well" style="padding: 12px 10px; background-color: #f7f7f7; border: 1px solid #d5d5d5; box-shadow: 0 4px 10px rgba(0,0,0,0.12);">                                                                                                              
              <?php                                                                                                                                                        
                echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(                                                                                  
                  'name' => 'saveChanges',                                                                                                                                 
                  'class' => 'btn btn-success btn-block mapop',                                                                                                            
                  'id' => 'rreviewSaveChanges',                                                                                                                            
                  'title' => 'Save & continue editing',                                                                                                                    
                  'data-content' => 'Save changes to form without submitting it. The form will still be available for further editing.',                                   
                  'div' => false,                                                                                                                                          
                ));                                                                                                                                                        
              ?>                                                                                                                                                           
              <hr style="margin: 10px 0;">                                                                                                                                 
              <?php                                                                                                                                                        
                echo $this->Form->button('<i class="icon-rocket"></i> Submit', array(                                                                                      
                  'name' => 'submitReport',                                                                                                                                
                  'onclick' => "return confirm('Are you sure you wish to submit the protocol review report?');",                                                           
                  'class' => 'btn btn-primary btn-block mapop',                                                                                                            
                  'id' => 'rreviewSubmitReport',                                                                                                                           
                  'title' => 'Save and Submit Report',                                                                                                                     
                  'data-content' => 'Submit report for peer review and approval.',                                                                                         
                  'div' => false,                                                                                                                                          
                ));                                                                                                                                                        
              ?>                                                                                                                                                           
            </div>                                                                                                                                                         
          </div>                                                                                                                                                           
        </div>                                                                                                                                           
    <?php /* ?>
    <div class="span2">
      
        
        <div class="my-sidebar"  >
          <div data-spy="affix" class="well">
            <?php
              echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(
                  'name' => 'saveChanges',
                  'class' => 'btn btn-success btn-block mapop',
                  'id' => 'SiteInspectionSaveChanges', 'title'=>'Save & continue editing',
                  'data-content' => 'Save changes to form without submitting it.
                                              The form will still be available for further editing.',
                  'div' => false,
                ));
            ?>
            <br>
            <hr>
            <?php
              echo $this->Form->button('<i class="icon-rocket"></i> Submit', array(
                  'name' => 'submitReport',
                  'onclick'=>"return confirm('Are you sure you wish to submit the protocol review report?');",
                  'class' => 'btn btn-primary btn-block mapop',
                  'id' => 'SiteInspectionSubmitReport', 'title'=>'Save and Submit Report',
                  'data-content' => 'Submit report for peer review and approval.',
                  'div' => false,
                ));

            ?>
           </div>
         </div>
      <!-- </div> -->
      </div>
        <?php */ ?>
    </div>

<?php
    echo $this->Form->end();
  }
?>   

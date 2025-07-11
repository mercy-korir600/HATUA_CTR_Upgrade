<?php  if ($this->fetch('is-applicant') == 'true') { ?>
  <div class="row-fluid">
    <div class="span12">
    <?php 
        echo $this->Session->flash();
      ?>
      <h4>15. Final Study Report <small>For complete and approved trials</small></h4>
      <h5 class="text-warning">NB: Limit the font size to 26 and below</h5>
        <hr>
        <div class="row-fluid">
          <div class="span12">
            <?php          
              
                  echo $this->Form->create('Application', array(
                      'url' => array('controller' => 'applications', 'action' => 'final_report', $application['Application']['id']),
                      'class' => 'form-horizontal',
                      'inputDefaults' => array(
                      'div' => array('class' => 'control-group'),
                      'label' => array('class' => 'control-label'),
                      'between' => '<div class="controls">',
                      'after' => '</div>',
                      'class' => '',
                      'format' => array('before', 'label', 'between', 'input', 'after','error'),
                      'error' => array('attributes' => array('class' => 'controls help-block')),
                     ),
                  ));
                  echo $this->Form->input('id', array('type' => 'hidden' ,'value' => $application['Application']['id']));
                  echo $this->Form->input('final_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));
                  echo $this->Form->input('final_report', array(
                      'label' => array('class' => 'control-nolabel required', 'text' => 'Executive summary <span class="sterix">*</span>'),
                      'between'=>'<div class="nocontrols">', 'placeholder' => 'final report' , 'class' => 'input-large',
                      'value' => $application['Application']['final_report']
                    ));
                  echo $this->Form->input('implication_results', array(
                      'label' => array('class' => 'control-nolabel required', 'text' => 'Implication of the results on current practice  <span class="sterix">*</span>'),
                      'between'=>'<div class="nocontrols">', 'placeholder' => 'Implication of the Results on current practice' , 'class' => 'input-large',
                      'value' => $application['Application']['implication_results']
                    ));
                  echo $this->Form->input('laymans_summary', array(
                      'label' => array('class' => 'control-nolabel required', 'text' => 'Laymans summary <span class="sterix">*</span>'),
                      'between'=>'<div class="nocontrols">', 'placeholder' => 'final report' , 'class' => 'input-large',
                      'value' => $application['Application']['laymans_summary']
                    ));
                  
                  echo '<h4 style="text-align: center; text-decoration: underline;"> Product Accountability</h4>';
                  echo $this->Form->input('quantity_imported', array(
                    'label' => array('class' => 'control-label required', 'text' => 'Quantity imported <span class="sterix">*</span>'),
                    'placeholder' => ' ' , 'class' => 'input-xxlarge', 'value' => $application['Application']['quantity_imported']
                  ));
                  echo $this->Form->input('quantity_dispensed', array(
                    'label' => array('class' => 'control-label required', 'text' => 'Quantity dispensed <span class="sterix">*</span>'),
                    'placeholder' => ' ' , 'class' => 'input-xxlarge', 'value' => $application['Application']['quantity_dispensed']
                  ));
                  echo $this->Form->input('quantity_destroyed', array(
                    'label' => array('class' => 'control-label required', 'text' => 'Quantity destroyed <span class="sterix">*</span>'),
                    'placeholder' => ' ' , 'class' => 'input-xxlarge', 'value' => $application['Application']['quantity_destroyed']
                  ));
                  echo $this->Form->input('quantity_exported', array(
                    'label' => array('class' => 'control-label required', 'text' => 'Quantity exported <span class="sterix">*</span>'),
                    'placeholder' => ' ' , 'class' => 'input-xxlarge', 'value' => $application['Application']['quantity_exported']
                  ));
                  echo $this->Form->input('balance_site', array(
                    'label' => array('class' => 'control-label required', 'text' => 'Balance at site <span class="sterix">*</span>'),
                    'placeholder' => ' ' , 'class' => 'input-xxlarge', 'value' => $application['Application']['balance_site']
                  ));
                  echo $this->Form->end(array(
                    'label' => 'Save',
                    'escape' => false,
                    'value' => 'Save',
                    'class' => 'btn btn-primary',
                    'id' => 'EthicalSaveChanges',
                    'div' => array(
                      'class' => 'form-actions',
                    )
                  ));
               
            ?>
            <hr>        
            <?php
                echo $this->element('multi/documents');
            ?>
            <br>
            <?php
              
                // echo $this->Form->end('<i class="icon-save"></i> Save', array(
                //     'name' => 'submitReport',
                //     // 'onclick'=>"return confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.');",
                //     'class' => 'btn btn-info btn-block',
                //     'id' => 'ApplicationViewSaveReport', 
                //     'div' => false,
                //     'type' => 'button'
                //   ));
            ?>
          </div> <!-- span12 -->
      </div>
    </div>
  </div><!--/row-->
<?php } else { ?>
  <div class="row-fluid">
    <div class="span12">

    <?php      
               echo $application['Application']['final_date'];
    ?>
      <h4>15. Final Study Report <small>For complete and approved trials</small></h4>
      <h5>Executive summary</h5>
      <?php  echo $application['Application']['final_report']; ?>
      <hr>
      <h5>Implication of the results on current practice</h5>
       <?php  echo $application['Application']['implication_results']; ?>
      <hr>   
      <h5>Layman's Summary</h5>
       <?php  echo $application['Application']['laymans_summary']; ?>
      <hr>  
      <h4 style="text-align: center; text-decoration: underline;"> Product Accountability</h4>
  <table class="table table-bordered table-condensed">
    <tbody>
      <tr>
        <th class="my-well" style="width: 40%">Quantity imported</th>
        <td><?php echo $application['Application']['quantity_imported'];?></td>
      </tr>
      <tr>
        <th class="my-well">Quantity Dispensed</th>
        <td><?php echo $application['Application']['quantity_dispensed']; ?></td>
      </tr>        
      <tr>
        <th class="my-well">Quantity Destroyed</th>
        <td><?php echo $application['Application']['quantity_destroyed']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Quantity Exported </th>
        <td><?php echo $application['Application']['quantity_exported']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Balance at site </th>
        <td><?php echo $application['Application']['balance_site']; ?></td>
      </tr>
    </tbody>        
  </table>     
            <?php
                echo $this->element('multi/documents');
            ?>

    </div>
  </div><!--/row-->
<?php } ?>

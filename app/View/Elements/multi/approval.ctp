<!-- Trial Status -->
  <div class="row-fluid">
    <div class="span12">
      <?php 
        echo $this->Session->flash();
      ?>
      <br>
      <?php
      if ($this->fetch('is-applicant') == 'true') {
        echo $this->Form->input('trial_status_id', array(
          'options'=>$trial_statuses, 'empty' => true, 'selected' => $application['Application']['trial_status_id'],
          'label' => array('class' => 'control-label required', 'text' => 'Current status of the trial <span class="sterix">*</span>'),
        ));
      } else {
        $ts = (!empty($application['Application']['trial_status_id'])) ? $trial_statuses[$application['Application']['trial_status_id']] : '';
        echo '<p class="lead"><strong>Current status of the trial</strong>: '.$ts.'</p>';
      }
      ?>
    </div>
  </div>

<!-- Ethical Committees -->
  <div class="row-fluid">
   <div class="span12">
    <h4 style="background-color: #496060f2; color: #fff; text-align: center;">Ethics Review Committees (ERC)</h4>
      
      <?php        
        echo $this->Form->create('EthicalCommittee', array(
           'url' => array('controller' => 'ethical_committees', 'action' => 'add'),
           // 'class' => 'form-horizontal',
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
      ?>
      <table class="table table-condensed">
        <thead>
          <tr>
            <th>Ethics Review Committee (ERC)</th>
            <th>ERC Reference Number</th>
            <th>Date of initial complete submission <small class="muted"> (dd-mm-yyyy) </small></th>
            <th>Initial Approval Date <br><small class="muted"> (dd-mm-yyyy) </small></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($application['EthicalCommittee'] as $key => $date) { ?>
          <tr>
            <td><?php  echo "<p>". $date['ethical_committee']."</p>";  ?></td>   
            <td><?php  echo "<p>". $date['erc_number']."</p>";  ?></td>
            <td><?php  echo "<p>". $date['submission_date']."</p>";  ?></td>     
            <td><?php  echo "<p>". $date['initial_approval']."</p>";  ?></td>
            <td></td>
          </tr>
        <?php } ?>

        <?php 
          if ($redir == 'applicant') { 
        ?>
          <tr style="background-color: #638282;">
            <td>
              <?php
                echo $this->Form->input('application_id', array('type' => 'hidden' ,'value' => $application['Application']['id']));
                $ercs = $this->requestAction('/ercs/checklist');
                echo $this->Form->input('ethical_committee', array(
                  'type' => 'select', 'label' => false, 'class' => 'input-xlarge', 'options' => $ercs
                ));
              ?>
            </td>
            <td>
              <?php  echo $this->Form->input('erc_number', array('label' => false, 'class' => 'input-medium' )); ?>
            </td>
            <td>
              <?php
                echo $this->Form->input('submission_date', array(
                  'type' => 'text',
                  'label' => false,
                  'placeholder' => ' ' , 'class' => 'pickadate input-medium',
                ));
              ?>
            </td>
            <td>
              <?php
                echo $this->Form->input('initial_approval', array(
                        'type' => 'text', 'class' => 'pickadate input-medium',
                        'label' => false,
                ));
              ?>
            </td>
            <td><?php echo $this->Form->button('Save', array('type' => 'submit', 'class'=>'btn btn-inverse'));?></td>
          </tr>
        <?php 
          } 
        ?>
        </tbody>
      </table>
      <?php
        echo $this->Form->end();
      ?>
      <hr>
      </div>
  </div>
  
<!-- Annual Approval Checklists -->
  <h4 style="background-color: #37732c; color: #fff; text-align: center;">Annual Approval Checklist </h4>
  <p><small>All submitted documents should be version referenced and dated.</small></p>
  <table  class="table table-bordered table-condensed table-striped">
    <thead>
      <tr>
        <th>Year</th>
        <th class="actions"><?php echo __('Files'); ?></th>
      </tr>
    </thead>
    <tbody>
    <?php
    App::uses('Hash', 'Utility');
    $former = $this->requestAction('/pockets/checklist/annual');
    $years = array_unique(Hash::extract($application['AnnualApproval'], '{n}.year'));
    rsort($years);
    // debug(Hash::extract($application['AnnualApproval'], '{n}.year'));
    foreach ($years as $year): ?>
    <tr class="">
        <td><b><?php echo h($year); ?></b></td>
        <td>
          <?php 
            $f = 0;
            foreach ($former as $rem => $mer) {
              $f++;
              echo "<div id='$rem$year'>";
                echo "$f. ";
                echo "$mer<br/>";
                foreach ($application['AnnualApproval'] as $anc) {
                  if($anc['year'] == $year && $anc['pocket_name'] == $rem) {
                    $id = $anc['id'];
                    echo "&nbsp;&nbsp; <span id='$rem$id'> &nbsp;<i class='icon-file-text-alt'></i> ";
                    echo $this->Html->link(__($anc['basename']),
                      array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                      array('class' => '')
                    );
                    $version_no = $anc['version_no'];
                    $file_date = $anc['file_date'];
                    echo "</span>&nbsp;
                          <span id='version$id' style='margin-left:10px;'>Version: $version_no</span>
                          <span id='fileDate$id' style='margin-left:10px;'>Dated: $file_date</span>
                          <span id='AnnualApproval$id' style='margin-left:10px;' class='btn btn-mini'><i class='icon-remove'></i></span>
                          <br>";
                  }
                }
              echo "</div>";
            }
            
            
            // echo $this->Html->link(__('<label class="label label-info">View</label>'), array('controller' => 'attachments', 'action' => 'approvals', $year), array('escape' => false)); 
            /*foreach ($application['AnnualApproval'] as $anc) {
              if($anc['year'] == $year) {
                echo '<i class="icon-file-text-alt"></i> ';
                echo $former[$anc['pocket_name']].': ';
                echo $this->Html->link(__($anc['basename']),
                  array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                  array('class' => '')
                );
                echo "<br>";
              }
            }*/
          ?>                        
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <hr>
 
    <?php 
    if ($redir == 'applicant') {     
      if(empty($application['Application']['trial_status_id'])){
    ?>
  <div class="well">  
    <h4>Please Update Protocol's trial status to proceed</h4>
  </div>
  <?php
      }
  else{?>
   <h5>Checklist Form</h5>
      <div class="well">        
        <table id="pastyears"  class="table table-bordered  table-condensed table-striped">
          <thead>
            <tr id="approvalsTableHeader">
            <th>#</th>
            <th style="width: 10%;">   
              <small class="muted">Select year</small> 
              <?php
                  // $date = 2019;
                echo $this->Form->input('Fake.year', array(
                  'type' => 'text' , 
                  'label' => false, 
                  'between' => false, 
                  'after' => false, 
                  'div' => false, 
                  'value' => date('Y'), 'readonly' => 'readonly',
                  'data-original-title' => "Click here to change years",
                  'class' => 'span12 kayear tiptip'));
              ?>  
            </th>
            <th style="width: 40%;">Description</th>
            <th>File <span class="sterix">*</span></th>
            <th style="width: 7%">Version No.</th>
            <th style="width: 12%">Date <small class="muted">(dd-mm-yyyy)</small></th>
            <th style="width: 7%">Submit</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 0; $key = 0; 
                foreach ($former as $pos => $value) {
                  $i++;
            ?>
            <tr>
            <td><?php $key++; echo $i; ?></td>
            <td>
              <?php
                echo $this->Form->input('AnnualApproval.'.$key.'.model', array('type'=>'hidden', 'value'=>'AnnualApproval'));
                echo $this->Form->input('AnnualApproval.'.$key.'.group', array('type'=>'hidden', 'value'=>$pos));
                echo $this->Form->input('AnnualApproval.'.$key.'.filesize', array('type' => 'hidden'));
                echo $this->Form->input('AnnualApproval.'.$key.'.basename', array('type' => 'hidden'));
                echo $this->Form->input('AnnualApproval.'.$key.'.checksum', array('type' => 'hidden'));

                echo $this->Form->input('AnnualApproval.'.$key.'.year', array(
                  'type' => 'text' , 'label' => false, 'between' => false, 'after' => false, 'div' => false,
                  'readonly' => 'readonly', 'class' => 'span11 approvalyear'));
              ?>

            </td>
            <td>
              <?php
                  echo $this->Form->input('AnnualApproval.'.$key.'.description', array('type' => 'hidden', 'value' => $value));
                  echo $this->Form->input('AnnualApproval.'.$key.'.pocket_name', array('type' => 'hidden', 'value' => $pos));
                  echo '<p>'.$value.'</p>';
              ?>
            </td>
            <td class="files"><?php
                // echo $this->Form->input('AnnualApproval.'.$key.'.id');
                echo $this->Form->input('AnnualApproval.'.$key.'.file', array(
                  'label' => false, 'between' => false, 'after' => false, 'div' => false, 'class' => 'span12 input-file',
                  'error' => array('escape' => false, 'attributes' => array( 'class' => 'help-block')),
                  'type' => 'file',
                ));
              ?>
            </td>
            <td>
              <?php
                if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AnnualApproval.'.$key.'.version_no', array(
                  'label' => false, 'between' => false, 'after' => false, 'div' => false, 'placeholder' => 'Version', 'class' => 'span12 input-file',
                  'error' => array('escape' => false, 'attributes' => array( 'class' => 'help-block')),
                ));
              ?>
            </td>
            <td>
              <?php
                if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AnnualApproval.'.$key.'.file_date', array(
                  'type' => 'text','label' => false, 'between' => false, 'after' => false, 'div' => false, 'placeholder' => 'dd-mm-yyyy', 'class' => 'span12 input-file pickadate',
                  'error' => array('escape' => false, 'attributes' => array( 'class' => 'help-block')),
                ));
              ?>
            </td>
            <td>
                <?php
                  echo $this->Form->button('<i class="icon-save"></i> ', array(
                      'name' => 'addApproval',
                      'type' => 'button',
                      'class' => 'btn btn-primary add-approval tiptip',
                      'data-original-title' => "Add a file",
                      'div' => false,
                    ));
                ?>
            </td>
            </tr>
            <?php
              }
            ?>
          </tbody>
        </table>
      </div>
    <?php } } ?>
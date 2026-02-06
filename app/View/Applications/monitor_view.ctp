<?php
  $this->extend('/Elements/application/applicant_view');
?>

<!-- START AMENDMENT LEAD -->
<?php $this->start('amendment-lead'); ?>
<?php
      $this->assign('MyApplications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
      $this->Html->script('jUpload/vendor/jquery.ui.widget.js', array('inline' => false));
      $this->Html->script('jUpload/jquery.iframe-transport.js', array('inline' => false));
      $this->Html->script('jUpload/jquery.fileupload.js', array('inline' => false));
      $this->Html->script('jquery.blockUI.js', array('inline' => false));
      //Only meant for applicant
      $this->Html->script('multi/approval', array('inline' => false));
      $this->Html->script('multi/amendments-checklist', array('inline' => false));
      $this->Html->script('multi/documents', array('inline' => false));

      $this->assign('is-monitor', 'true');
    ?>

    <?php
        echo $this->Session->flash();
    ?>

    <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
          <li><a href="#tab7" data-toggle="tab">SAE/SUSAR (<?php echo count($application['Sae']) ?>)</a></li>
          <li><a href="#tab15" data-toggle="tab">CIOMS E2B (<?php echo count($application['Ciom']) ?>)</a></li>
          <li><a href="#tab13" data-toggle="tab">Protocol Deviations (<?php echo count($application['Deviation']) ?>)</a></li>
      </ul>
      <div class="tab-content my-tab-content">
        <div class="tab-pane active" id="tab1">
          <!-- content for tab1 comes here -->

  <div class="row-fluid">
    <?php if($application['Application']['submitted'] == 1 ) { ?>
      <h4 class="text-success">
       Submitted Application :  (<?php echo $application['Application']['protocol_no'];?>) &mdash;
       <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } else { ?>
      <h4 class="text-success">
        UnSubmitted Application :  &mdash; <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } ?>
    
  </div>
<?php $this->end();?>
<!-- ######## -->
<!-- START RIGHT BAR -->
<?php $this->start('view-rightbar'); ?>
</div>

  <?php $this->end();  ?>
<!-- ######## -->
<!-- FORM HEADER -->
<?php $this->start('form-header'); ?>
    <div class="span12">
  <?php
      echo $this->Form->create('Application', array(
            'type' => 'file',
            'class' => 'form-horizontal',
            'id' => 'fakeidshouldnotexist',
            'inputDefaults' => array(
              'div' => array('class' => 'control-group'),
              'label' => array('class' => 'control-label'),
              'between' => '<div class="controls">',
              'after' => '</div>',
              'class' => '',
              'format' => array('before', 'label', 'between', 'input', 'after','error'),
              'error' => array('attributes' => array( 'class' => 'controls help-block')),
             ),
          ));
        echo $this->Form->input('id', array('value' => $application['Application']['id']));
    ?>
<?php $this->end();?>

<?php
$this->start('form-actions');
  if($application['Application']['submitted'] == 1) {
?>
<div class="form-actions"  style="margin-top: 0px; padding-left: 10px;">
	<?php
          echo $this->Html->link(__('<i class="icon-download-alt"></i> Download PDF'),
            array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
            array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));

  ?>
</div>
<?php
  }
$this->end();
?>

<?php $this->start('tabs'); ?>
<ul>
  <li><a href="#tabs-1">1. Abstract</a></li>
</ul>
<?php $this->end(); ?>


<?php $this->start('endjs'); ?>
 </div> <!-- End or bootstrab tab1 -->

    <div class="tab-pane" id="tab7">   
      <div class="row-fluid">
        <div class="span12">
        <?php
          if ($application['Application']['submitted']) {
              if ($application['Application']['submitted']) {
                      echo "<br>";
                      echo $this->Html->link('<i class="icon-list-alt"></i> Create SAE', array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'sae'), 
                            array('escape' => false, 'class' => 'btn btn-success btn-mini')); 
                      echo "&nbsp;";
                      echo $this->Html->link('<i class="icon-credit-card"></i> Create SUSAR', array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'susar'), 
                            array('escape' => false, 'class' => 'btn btn-primary btn-mini'));   
                      echo "<br>";
                      echo "<br>";
              }
          }
        ?>
          <table  class="table  table-bordered table-striped">
             <thead>
                    <tr>
                <th>Id</th>
                <th>Reference No.</th>
                <th>Report Type</th>
                <th>Patient Initials</th>
                <th>Created</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
                  </tr>
               </thead>
              <tbody>
            <?php
            foreach ($application['Sae'] as $sae): ?>
            <tr class="">
                <td><?php echo h($sae['id']); ?>&nbsp;</td>
                <td><?php echo h($sae['reference_no']); ?>&nbsp;</td>
                <td><?php echo h($sae['report_type']); 
                          if($sae['report_type'] == 'Followup') {
                            echo "<br> Initial: ";
                            echo $this->Html->link(
                              '<label class="label label-info">'.substr($sae['reference_no'], 0, strpos($sae['reference_no'], '-')).'</label>', 
                              array('controller' => 'saes', 'action' => 'view', $sae['sae_id']), array('escape' => false));
                          }
                      ?>&nbsp;
                </td>
                <td><?php echo h($sae['patient_initials']); ?>&nbsp;</td>
                <td><?php echo h($sae['created']); ?>&nbsp;</td>
                <td class="actions">
                    <?php if($sae['approved'] > 0) echo $this->Html->link(__('<label class="label label-info">View</label>'), array('controller' => 'saes', 'action' => 'view', $sae['id']), 
                        array('target' => '_blank', 'escape' => false)); ?>
                    <?php if($redir === 'monitor' && $sae['approved'] < 1 && $sae['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('controller' => 'saes', 'action' => 'edit', $sae['id']), array('target' => '_blank', 'escape' => false)); ?>
                    <?php
                      if($sae['approved'] < 1 && $sae['user_id'] == $this->Session->read('Auth.User.id')) {
                        echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'saes', 'action' => 'delete', $sae['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['id']));
                      } 
                      if($redir === 'monitor' && $sae['approved'] > 0) echo $this->Form->postLink('<i class="icon-facebook"></i> Follow Up', array('controller' => 'saes', 'action' => 'followup', $sae['id']), array('class' => 'btn btn-mini btn-warning', 'escape' => false), __('Create followup for %s?', $sae['reference_no']));
                    ?>            
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>

          </div>
      </div>
    </div>

    <div class="tab-pane" id="tab15">   
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/ciom'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab13">   
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/deviation'); ?>
        </div>
      </div>
    </div>
</div>
</div>

<script text="type/javascript">
  $.expander.defaults.slicePoint = 170;
  $(function() {
    $(document).ajaxStop($.unblockUI);
    $( "#tabs" ).tabs({
        cookie: {
          expires: 1
        }
    });
    $(".morecontent").expander();

});
</script>
<?php $this->end();?>

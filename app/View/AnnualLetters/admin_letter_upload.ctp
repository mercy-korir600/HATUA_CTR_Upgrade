<?php 
  echo $this->Session->flash();
  $this->assign('Reports', 'active');
  $this->Html->script('ckeditor/ckeditor', array('inline' => false));
  $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
?>
<h4 class="text-success">Upload Past Approval Letters</h4>
<?php
  echo $this->Html->link(__('<i class="icon-file"></i> Add approval letter'),
                        array('controller' => 'annual_letters', 'action' => 'letter_upload', $application['Application']['id'], 'ane' => 'new'),
                        array('escape' => false, 'class' => 'btn btn-primary'));
?>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <dl class="dl-horizontal">
        <dt>ECCT Reference No.</dt>
        <dd><?php echo $application['Application']['protocol_no'];?></dd>
        <dt>Short Title</dt>
        <dd><?php echo $application['Application']['short_title'];?></dd>
      </dl>
    </div>
  </div>
  <br>

    <table  class="table  table-bordered table-striped">
     <thead>
        <tr>
            <th>Id</th>
            <th>Approval No.</th>
            <th>Approval date</th>
            <th>Expiry date</th>
            <th>Created</th>
            <th class="actions"><?php echo __('Actions'); ?></th>
         </tr>
       </thead>
      <tbody>
        <?php
        foreach ($application['AnnualLetter'] as $anl): ?>
        <tr class="">
            <td><?php echo h($anl['id']); ?>&nbsp;</td>
            <td><?php echo h($anl['approval_no']); ?>&nbsp;</td>
            <td><?php echo h($anl['approval_date']); ?>&nbsp;</td>
            <td><?php echo h($anl['expiry_date']); ?>&nbsp;</td>
            <td><?php echo h($anl['created']); ?>&nbsp;</td>
            <td class="actions">
              <?php 

                      echo $this->Html->link('<span class="label label-success"> Edit </span>', array('action' => 'letter_upload', $application['Application']['id'], 'ane' => $anl['id']), array('escape'=>false));
                  echo "&nbsp;";
                      echo $this->Html->link('<span class="label label-info"> View </span>', array('action' => 'letter_upload', $application['Application']['id'], 'anl' => $anl['id']), array('escape'=>false));

                  echo "&nbsp;";
                    echo $this->Html->link('<span class="label label-inverse"> Download PDF </span>', array('controller' => 'annual_letters', 'action' => 'view', $anl['id'], 'ext' => 'pdf',), array('escape'=>false));
              ?>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


 <br>
<hr>
  
  <!-- View approval letter -->
  <?php
    if(isset($this->params['named']['anl'])) {
        foreach ($application['AnnualLetter'] as $akey => $annual_letter) {
            if ($annual_letter['id'] == $this->params['named']['anl']) {               
  ?>
    <div class="ctr-groups">
        <?php   echo $anl["content"]; ?> &nbsp;
    </div>
  <?php } } } ?>

  <!-- Edit approval letter -->
  <?php
  $annual_letter = array('id' => null, 'application_id' => $application['Application']['id'], 'approval_no' => null, 'content' => null, 'approver' => null, 'approval_date' => null, 'expiry_date' => null, 'status' => 'approved');
  if(isset($this->params['named']['ane']) && $this->params['named']['ane'] !== 'new') {
      $annual_letter = min(Hash::extract($application['AnnualLetter'], '{n}[id='.$this->params['named']['ane'].']'));
  }
      
  ?>
    <div class="ctr-groups">
        <?php echo $this->Form->create('AnnualLetter', array(
              'url' => array('controller' => 'annual_letters', 'action' => 'letter_upload', $application['Application']['id']),
              'type' => 'file',
              'class' => 'form-horizontal',
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
        echo $this->Form->input('id'); ?>
        <fieldset>
            <legend>Approve</strong></legend>
        <?php
            echo $this->Form->input('id', array('type' => 'hidden', 'value' => $annual_letter['id']));
            echo $this->Form->input('status', array('type' => 'hidden', 'value' => 'approved'));

            echo $this->Form->input('approval_date', array(
              'div' => array('class' => 'control-group'), 'type' => 'text', 'value' => $annual_letter['approval_date'], 'class' => 'datepickers',
              'label' => array('class' => 'control-label required', 'text' => 'Approval date <span class="sterix">*</span>'),
              'after'=>'<span class="help-inline">  Date format (dd-mm-yyyy) </span></div>',
            ));
            echo $this->Form->input('expiry_date', array(
              'div' => array('class' => 'control-group'), 'type' => 'text', 'value' => $annual_letter['expiry_date'], 'class' => 'datepickers',
              'label' => array('class' => 'control-label required', 'text' => 'Expiry date <span class="sterix">*</span>'),
              'after'=>'<span class="help-inline">  Date format (dd-mm-yyyy) </span></div>',
            ));
            echo $this->Form->input('content', array(
                  'label' => false, 'value' => $annual_letter['content'],
                  'between'=>'<div class="controle">',  'class' => 'input-large',
                ));
        ?>
        </fieldset>
      <?php echo  $this->Form->end(array(
                'label' => 'Paste Signature and Upload',
                'value' => 'Approve',
                'class' => 'btn btn-success',
                'div' => array(
                    'class' => 'form-actions',
                )
            ));
            ?>
      <script type="text/javascript">
          (function( $ ) {

              $( ".datepickers" ).datepicker({
                  minDate:"-5Y", maxDate:"+999D", dateFormat:'dd-mm-yy', showButtonPanel:true, changeMonth:true, changeYear:true,
                  //yearRange: "-100Y:+0",
                  buttonImageOnly:true, showAnim:'show', showOn:'both', buttonImage:'/img/calendar.gif'
              });
            })( jQuery );

          CKEDITOR.replace( 'data[AnnualLetter][content]');
    </script>
    </div>
  <?php // } } } ?>
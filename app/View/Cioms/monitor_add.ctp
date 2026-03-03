<?php
  $this->assign('CIOM', 'active');
?>


<div class="row-fluid">
  <div class="span12">
      <h3 style="margin-left: 255px;">CIOMS E2B file</h3>
  <?php
    echo $this->Session->flash();

    echo $this->Form->create('Ciom', array(
    	'type' => 'file',
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
  ?>

  <div class="row-fluid">
    <div class="span12">
      <?php
      	echo $this->Form->input('application_id', array('type' => 'hidden', 'value' => $application_id));
      	echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->session->read('Auth.User.id')));
        echo $this->Form->input('file',
          array(
            'type' => 'file',
            'accept' => '.xml,application/xml,text/xml',
            'label' => array('class' => 'control-label required', 'text' => 'E2B File <span class="sterix">*</span>')
          ));
        echo $this->Form->input('description',
          array('label' => array('class' => 'control-label required', 'text' => 'Description'),));
        echo $this->Form->input('reporter_email', array(
            'label' => array('class' => 'control-label required', 'text' => 'Reporter email <span class="sterix">*</span>', 'value' => $this->session->read('Auth.User.email')), ));
        ?>
    </div><!--/span-->
  </div><!--/row-->

  <?php
    
    echo $this->Form->end(array(
      'label' => 'Submit',
      'value' => 'Save',
      'class' => 'btn btn-primary',
      'id' => 'CiomSaveChanges',
      'div' => array(
        'class' => 'form-actions',
      )
    ));
  ?>
  </div>
</div>

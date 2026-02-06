<?php
  $this->Html->script('conflict', array('inline' => false));
  $this->assign('Applications', 'active');
 ?>

<?php $this->start('form-actions'); ?>
  <div class="form-actions">
    <div class="row-fluid">
      <div class="span4">
      <?php
$prefix = null;

if (!empty($this->request->params['prefix'])) {
    $prefix = $this->request->params['prefix']; // reviewer | internalreviewer
}

$url = array(
    'action' => 'view',
    'ext' => 'pdf',
    $application['Application']['id'],
);

// Preserve prefix
if ($prefix) {
    $url[$prefix] = true;
}

echo $this->Html->link(
    'Download PDF',
    $url,
    array(
        'class' => 'btn btn-primary mapop',
        'title' => 'Download PDF',
        'data-content' => 'Download the pdf version of the report',
    )
);?>

      </div>
      <div class="span4">
        <?php
            // echo $this->Form->button('Print Report', array('type' => 'button', 'class'=>'btn btn-inverse btnPrint' , 'onclick' => '$(\'#applicationPrintArea\').jqprint(); '   ));
        ?>
      </div>
      <div class="span4">

      </div>
    </div>
  </div>
<?php $this->end(); ?>

<section>
  <div class="row-fluid">
  <div class="span12">
    <?php
    echo $this->Session->flash();
    ?>
    <div class="row-fluid">
	<?php
		echo $this->element('application/reviewer_minimal_view');
	?>

    <div class="span4">
      <?php if(!$application['Application']['deactivated']) { ?>
      <h4 class="text-success">Review Application?</h4>
      <hr>
      <?php
            echo $this->Form->create('Review',
                      array('url' => array('controller' => 'reviews', 'action' => 'respond')));
            $acceptedError = '';
            if($this->Form->isFieldError('title')) $acceptedError = 'error';
          
            
            echo $this->Form->input('conflict', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
              'class' => 'conflict ',
              'before' => '<div class="control-group">   <label class="control-label required">
              Do you have any conflict? <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ReviewApproved_" name="data[Review][conflict]"> <label class="radio inline">',
              'after' => '</label>',
              'options' => array('Yes' => 'Yes'),
            ));
            echo $this->Form->input('conflict', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'class' => 'conflict',
              'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
              'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
              'before' => '<label class="radio inline">',
              'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.conflict\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
              'options' => array('No' => 'No'),
            ));
            echo $this->Form->input('accepted', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
              'class' => 'accepted_ conflictStatus',
              'before' => '<div class="control-group '.$acceptedError.'">
                      <label class="control-label required"> </label> <div class="controls">
                      <input type="hidden" value="" id="ReviewAccepted_" name="data[Review][accepted]"> <label class="radio inline">',
              'after' => '</label>',
              'options' => array('accepted' => 'Accept'),
            ));
            echo $this->Form->input('accepted', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
              'class' => 'accepted_ conflictStatus',
              'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
              'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
              'before' => '<label class="radio inline">',
              'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_single_blind\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
              'options' => array('declined' => 'Decline'),
            ));
            echo $this->Form->input('password', array('type' => 'password', 'label' => 'Your Password *'));
            echo $this->Form->input('application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
            echo $this->Form->input('recommendation', array('type' => 'textarea', 'rows' => 3, 'label' => 'Message <small>(if any)</small>'));
                 echo $this->Form->end(array(
                    'label' => 'Submit',
                    'value' => 'Submit',
                    'class' => 'btn btn-success',
                 ));
      } else {
          echo "<p class='text-warning'> This Application has been deactivated by PPB. Please wait for further direction.</p>";
      }
      ?>
    </div>
    </div>
  </div>
  </div>
</section>


<?php
$this->assign('MyApplications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('multi/amendment_attachments', array('inline' => false));
echo $this->Session->flash();
?>

<style type="text/css">
  .amendment-step1-form .step1-section {
    margin-bottom: 18px;
  }
  .amendment-step1-form .step1-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
  }
  .amendment-step1-form .step1-editor {
    width: 100%;
    max-width: 100%;
  }
  .amendment-step1-main {
    padding-right: 10px;
  }
  .amendment-step1-actions .well {
    margin-top: 4px;
  }
  .amendment-step1-actions .btn {
    margin-bottom: 10px;
  }
  .amendment-step1-attachments {
    overflow-x: auto;
  }
  #buildattachmentsform {
    width: 100%;
    table-layout: fixed;
  }
  #buildattachmentsform th,
  #buildattachmentsform td {
    vertical-align: top;
    word-wrap: break-word;
  }
  #buildattachmentsform .input-file,
  #buildattachmentsform textarea {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
  }
  @media (max-width: 979px) {
    .amendment-step1-main {
      padding-right: 0;
    }
    .amendment-step1-actions {
      margin-top: 15px;
    }
  }
</style>

<div class="row-fluid">
  <div class="span12">
    <h3 class="text-info">Amendment Step 1 of 2</h3>
    <p class="muted">Complete all six free-text sections first, then upload the compulsory cover letter file and any additional attachments.</p>
    <p><strong>Application:</strong> <?php echo h($application['Application']['protocol_no']); ?></p>
    <hr>
  </div>
</div>

<?php
echo $this->Form->create('Amendment', array(
  'type' => 'file',
  'class' => 'amendment-step1-form',
  'novalidate' => true
));
echo $this->Form->input('Amend.application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
?>

<div class="row-fluid">
  <div class="span10 amendment-step1-main">
    <div class="control-group step1-section">
      <label class="step1-label" for="AmendCoverLetter">1. Cover letter</label>
      <?php
      echo $this->Form->input('Amend.cover_letter', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <div class="control-group step1-section">
      <label class="step1-label" for="AmendSummary">2. Summary of the proposed amendments</label>
      <?php
      echo $this->Form->input('Amend.summary', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <div class="control-group step1-section">
      <label class="step1-label" for="AmendReason">3. Reason for the amendment</label>
      <?php
      echo $this->Form->input('Amend.reason', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <div class="control-group step1-section">
      <label class="step1-label" for="AmendObjectivesImpacts">4. Impact of the amendment on the original study objectives</label>
      <?php
      echo $this->Form->input('Amend.objectives_impacts', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <div class="control-group step1-section">
      <label class="step1-label" for="AmendEndpointsImpacts">5. Impact of the amendments on the study endpoints and data generated</label>
      <?php
      echo $this->Form->input('Amend.endpoints_impacts', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <div class="control-group step1-section">
      <label class="step1-label" for="AmendSafetyImpacts">6. Impact of the proposed amendments on the safety and wellbeing of study participants</label>
      <?php
      echo $this->Form->input('Amend.safety_impacts', array(
        'type' => 'textarea',
        'rows' => 7,
        'class' => 'step1-editor',
        'label' => false,
        'div' => false
      ));
      ?>
    </div>

    <hr>
    <h4>Compulsory Cover Letter Upload</h4>
    <p class="muted">Upload the cover letter file after completing the text sections above.</p>

    <?php
    echo $this->Form->input('CoverLetter.0.model', array('type' => 'hidden', 'value' => 'Amendment'));
    echo $this->Form->input('CoverLetter.0.group', array('type' => 'hidden', 'value' => 'cover_letter'));
    echo $this->Form->input('CoverLetter.0.description', array('type' => 'hidden', 'value' => 'Cover letter'));
    echo $this->Form->input('CoverLetter.0.dirname', array('type' => 'hidden'));
    echo $this->Form->input('CoverLetter.0.basename', array('type' => 'hidden'));
    echo $this->Form->input('CoverLetter.0.checksum', array('type' => 'hidden'));
    echo $this->Form->input('CoverLetter.0.file', array(
      'type' => 'file',
      'label' => array('text' => 'Cover Letter File (Required)')
    ));
    ?>

    <hr>
    <h4>Additional Attachment Uploads</h4>
    <p class="muted">Use the standard attachment uploader to add any other supporting files.</p>

    <h5>
      Add Additional File:
      <button type="button" class="btn-mini" id="addAttachment">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
    </h5>
    <div class="amendment-step1-attachments">
      <table id="buildattachmentsform" class="table table-bordered table-condensed table-striped">
        <thead>
          <tr id="attachmentsTableHeader">
            <th style="width: 6%;">#</th>
            <th style="width: 44%;">File</th>
            <th style="width: 44%;">Text Description</th>
            <th style="width: 6%;"></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <div class="span2 amendment-step1-actions">
    <div data-spy="affix" class="my-sidebar">
      <div class="well">
        <?php
        echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit Step 1', array(
          'type' => 'submit',
          'class' => 'btn btn-info btn-block',
          'escape' => false
        ));
        echo $this->Html->link(
          '<i class="icon-remove-circle"></i> Cancel',
          array('controller' => 'applications', 'action' => 'view', $application['Application']['id']),
          array('class' => 'btn btn-block', 'escape' => false)
        );
     
        ?>
      </div>
    </div>
  </div>
</div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">
$(function () {
  $('#AmendCoverLetter').ckeditor();
  $('#AmendSummary').ckeditor();
  $('#AmendReason').ckeditor();
  $('#AmendObjectivesImpacts').ckeditor();
  $('#AmendEndpointsImpacts').ckeditor();
  $('#AmendSafetyImpacts').ckeditor();
});
</script>

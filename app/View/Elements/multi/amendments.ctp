<!-- Annual Approval Checklists -->
<?php

$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('multi/amendment_attachment', array('inline' => false));
$this->Html->script('summary/sum', array('inline' => false));
if ($redir === 'applicant') {
    // $this->Html->script('multi/amendment_checklist', array('inline' => false));
    $this->Html->script('multi/extrask', array('inline' => false));
}
?>

<h4 style="background-color: #37732c; color: #fff; text-align: center;">Amendments Checklist </h4>
<p><small>All submitted documents should be version referenced and dated.</small></p>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <tr>
            <th>Number</th>
            <th class="actions"><?php echo __('Files'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        App::uses('Hash', 'Utility');
        $former = $this->requestAction('/pockets/checklist/amendment');
        $years = array_unique(Hash::extract($application['AmendmentChecklist'], '{n}.year'));
        rsort($years);
        foreach ($years as $year) : ?>
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
                        foreach ($application['AmendmentChecklist'] as $anc) {
                            if ($anc['year'] == $year && $anc['pocket_name'] == $rem) {
                                $id = $anc['id'];
                                echo "&nbsp;&nbsp; <span id='$rem$id'> &nbsp;<i class='icon-file-text-alt'></i> ";
                                echo $this->Html->link(
                                    __($anc['basename']),
                                    array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                                    array('class' => '')
                                );
                                $version_no = $anc['version_no'];
                                $file_date = $anc['file_date'];
                                echo "</span>&nbsp;
                          <span id='version$id' style='margin-left:10px;'>Version: $version_no</span>
                          <span id='fileDate$id' style='margin-left:10px;'>Dated: $file_date</span>
                          <span id='AmendmentChecklist$id' style='margin-left:10px;' class='btn btn-mini'><i class='icon-remove'></i></span>
                          <br>";
                            }
                        }
                        echo "</div>";
                    }
                    echo "<h5>Additional Files</h5>";

                    $ccloop = 0;
                    foreach ($application['AmendmentChecklist'] as $anc) {

                        if ($anc['year'] == $year && $anc['pocket_name'] == '') {
                            $ccloop++;
                            $id = $anc['id'];
                            $version_no = $anc['version_no'];
                            $file_date = $anc['file_date'];
                            $description = $anc['description'];
                            echo "<br>" . $ccloop . ". " . $description . "<br>";
                            echo "&nbsp;&nbsp; <span id='$rem$id'> &nbsp;<i class='icon-file-text-alt'></i> ";
                            echo $this->Html->link(
                                __($anc['basename']),
                                array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                                array('class' => '')
                            );

                            echo "</span>&nbsp;
                      <span id='version$id' style='margin-left:10px;'>Version: $version_no</span>
                      <span id='fileDate$id' style='margin-left:10px;'>Dated: $file_date</span>
                      <span id='AmendmentChecklist$id' style='margin-left:10px;' class='btn btn-mini'><i class='icon-remove'></i></span>
                      <br>";
                        }
                    }

                    echo "<h5>Approval Letters</h5>";
                    if (!empty($year)) {
                        if ($redir === 'manager') { ?>

                            <a class="btn btn-link btn-comment" role="button" data-toggle="collapse" href="#amendment-summary" aria-controls="amendment-summary">Review & Approve</a>

                            <div id="amendment-summary" class="collapse show">
                                <div class="amend-form">
                                    <ul id="rreview_tab" class="nav nav-tabs">
                                        <li class="active"><a href="#amendment_approval">Approval</a></li>
                                        <li><a href="#amendment_summary">Summary Report</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="amendment_approval">
                                            <div class="row-fluid">
                                                <div class="span12">
                                                    <h4 class="text-success">Approve or Reject Amendment <span class="muted"></span></h4>
                                                    <hr>
                                                    <?php
                                                    echo $this->Form->create('AmendmentApproval', array(
                                                        'url' => array('action' => 'approve', $application['Application']['id']),
                                                        'type' => 'file',
                                                        'class' => 'form-horizontal',
                                                        'inputDefaults' => array(
                                                            'div' => array('class' => 'control-group'),
                                                            'label' => array('class' => 'control-label'),
                                                            'between' => '<div class="controls">',
                                                            'after' => '</div>',
                                                            'class' => '',
                                                            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                                                            'error' => array('attributes' => array('class' => 'controls help-block')),
                                                        ),
                                                    ));
                                                    echo $this->Form->input('application_id', array('value' => $application['Application']['id'], 'type' => 'hidden'));
                                                    echo $this->Form->input('approval_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));
                                                    echo $this->Form->input('amendment', array('value' =>  $year, 'type' => 'hidden'));
                                                    echo $this->Form->input('status', array(
                                                        'type' => 'radio',
                                                        'label' => false,
                                                        'legend' => false,
                                                        'div' => false,
                                                        'hiddenField' => false,
                                                        'error' => false,
                                                        'class' => 'approved',
                                                        'before' => '<div class="control-group">   <label class="control-label required">
                        Approve? <span class="sterix">*</span></label>  <div class="controls">
                        <input type="hidden" value="" id="ApplicationApproved_" name="data[AmendmentApproval][approved]"> <label class="radio inline">',
                                                        'after' => '</label>',
                                                        'options' => array('approved' => 'Yes'),
                                                    ));
                                                    echo $this->Form->input('status', array(
                                                        'type' => 'radio',
                                                        'label' => false,
                                                        'legend' => false,
                                                        'div' => false,
                                                        'hiddenField' => false,
                                                        'class' => 'approved',
                                                        'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                                                        'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                                                        'before' => '<label class="radio inline">',
                                                        'after' => '</label>
                            <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                            onclick="$(\'.approved\').removeAttr(\'checked disabled\')">
                            <em class="accordion-toggle">clear!</em></a> </span>
                            </div> </div>',
                                                        'options' => array('rejected' => 'No'),
                                                    ));

                                                    echo $this->Form->input('content', array(
                                                        'label' => array('class' => 'control-label', 'text' => 'Message'),
                                                        'placeholder' => ' ',
                                                        'class' => 'input-xlarge',
                                                        'rows' => '3'
                                                    ));
                                                    echo $this->Form->input('password', array(
                                                        'label' => array('class' => 'control-label', 'text' => 'Your Password <span class="sterix">*</span>'),
                                                        'placeholder' => 'password',
                                                        'class' => 'input-large',
                                                    ));
                                                    ?>

                                                    <!-- <div class="row-fluid">
                                                        <div class="span11">
                                                            <div class="uploadsTable">
                                                                <h6 class="muted"><b>Attach File(s) </b>
                                                                    <button type="button" class="btn btn-primary btn-small addUploadAmendmentApproval">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
                                                                </h6>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                    <div class="controls">
                                                        <?php
                                                        echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
                                                            'name' => 'submit',
                                                            'class' => 'btn btn-primary',
                                                            'id' => 'ApproveProtocol',
                                                        ));
                                                        ?>
                                                    </div>
                                                    <?php
                                                    echo $this->Form->end();

                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="amendment_summary">
                                            <div class="row-fluid">
                                                <div class="span12">


                                                    <!-- Start of the Form -->
                                                    <?php
                                                    echo $this->Form->create('AmendmentApproval', array(
                                                        'url' => array('action' => 'approve_amendment', $application['Application']['id']),
                                                        'type' => 'file',
                                                        'class' => 'form-horizontal',
                                                        'inputDefaults' => array(
                                                            'div' => array('class' => 'control-group'),
                                                            'label' => array('class' => 'control-label'),
                                                            'between' => '<div class="controls">',
                                                            'after' => '</div>',
                                                            'class' => '',
                                                            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                                                            'error' => array('attributes' => array('class' => 'controls help-block')),
                                                        ),
                                                    ));
                                                    echo $this->Form->input('application_id', array('value' => $application['Application']['id'], 'type' => 'hidden'));
                                                    echo $this->Form->input('approval_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));
                                                    echo $this->Form->input('amendment', array('value' =>  $year, 'type' => 'hidden'));
                                                    echo $this->Form->input('status', array('value' =>  'summary', 'type' => 'hidden'));
                                                    echo $this->Form->input('password', array('value' =>    $this->Session->read('Auth.User.confirm_password'), 'type' => 'hidden'));
                                                    ?>

                                                    <div class="row-fluid">
                                                        <div class="span11">
                                                            <div class="uploadsTable">
                                                                <h6 class="muted"><b>Attach File(s) </b>
                                                                    <button type="button" class="btn btn-primary btn-small addUploadAmendmentApproval">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
                                                                </h6>
                                                                <hr>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="controls">
                                                        <?php
                                                        echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
                                                            'name' => 'submit',
                                                            'class' => 'btn btn-primary',
                                                            'id' => 'ApproveProtocol',
                                                        ));
                                                        ?>
                                                    </div>
                                                    <?php
                                                    echo $this->Form->end();

                                                    ?>
                                                    <!-- End of the Form -->

                                                    <hr>

                                                    <?php
                                                    foreach ($application['AmendmentApprovalSummary'] as $key => $comment) { ?>
                                                        <table class="table table-condensed">
                                                            <tbody>

                                                                <!-- <tr>
                                                                    <th>
                                                                        <p><strong>Message</strong></p>
                                                                    </th>
                                                                    <td>
                                                                        <div>
                                                                            <p class="form-control-static">
                                                                                <?php
                                                                                debug($comment);
                                                                                // exit;
                                                                                echo $comment['content']
                                                                                ?>
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>
                                                                        <p><strong>Status</strong></p>
                                                                    </th>
                                                                    <td>
                                                                        <div>
                                                                            <p class="form-control-static">
                                                                                <?php
                                                                                echo $comment['status']
                                                                                ?>
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                </tr> -->
                                                                <tr>
                                                                    <th>
                                                                        <p> <strong>Attached File(s) </strong> </p>
                                                                    </th>
                                                                    <td>
                                                                        <?php

                                                                        if (isset($comment['AmendmentChecklist'])) {
                                                                            foreach ($comment['AmendmentChecklist'] as $key => $value) {
                                                                                echo '<p>';
                                                                                echo $this->Html->link(
                                                                                    __($value['basename']),
                                                                                    array(
                                                                                        'controller' => 'amendment_approvals',
                                                                                        'action' => 'file_download',
                                                                                        $value['id'],
                                                                                        'admin' => false
                                                                                    ),
                                                                                    array('class' => 'btn btn-link')
                                                                                );
                                                                                echo '</p>';
                                                                            }
                                                                        }

                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <?php  }
                    }

                    ?>
                    <hr>
                    <table class="table  table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Approval No.</th>
                                <th>Approval date</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $aml = 0;
                            foreach ($application['AmendmentLetter'] as $anl) {
                                $aml++;
                                if ($anl['status'] == $year) {
                            ?>
                                    <tr class="">
                                        <td><?php echo h($anl['id']); ?>&nbsp;</td>
                                        <td><?php echo h($anl['approval_no']); ?>&nbsp;</td>
                                        <td><?php echo h($anl['approval_date']); ?>&nbsp;</td>
                                        <td><?php echo h($anl['expiry_date']); ?>&nbsp;</td>
                                        <td><?php echo h($anl['created']); ?>&nbsp;</td>
                                        <td class="actions">
                                            <?php
                                            if ($anl['submitted'] == '0') {
                                                if ($redir == 'manager') {
                                                    echo $this->Html->link('<span class="label label-success"> Edit </span>', array('action' => 'view', $application['Application']['id'], 'ame' => $anl['id']), array('escape' => false));
                                                }
                                            } else {
                                                echo $this->Html->link('<span class="label label-info"> View </span>', array('action' => 'view', $application['Application']['id'], 'aml' => $anl['id']), array('escape' => false));
                                            }

                                            echo "&nbsp;";
                                            if ($anl['submitted'] == '0') {
                                                if ($redir == 'manager') {
                                                    echo $this->Html->link('<span class="label label-warning"> Approve </span>', array('controller' => 'amendment_letters', 'action' => 'capprove', $anl['id']), array('escape' => false));
                                                }
                                            }
                                            echo "&nbsp;";
                                            echo $this->Html->link('<span class="label label-inverse"> Download PDF </span>', array('controller' => 'amendment_letters', 'action' => 'download', $anl['id'], 'ext' => 'pdf',), array('escape' => false));

                                            ?>
                                        </td>
                                    </tr>

                            <?php }
                            } ?>
                        </tbody>
                    </table>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>

<!--  -->
<!-- View approval letter -->
<?php
if (isset($this->params['named']['aml'])) {
    foreach ($application['AmendmentLetter'] as $akey => $annual_letter) {
        if ($annual_letter['id'] == $this->params['named']['aml']) {
?>
            <div class="ctr-groups">
                <?php echo $anl["content"]; ?> &nbsp;

                <?php
                if (!empty($anl['qrcode'])) {
                    $decodedImage = base64_decode($anl['qrcode']);
                    echo $decodedImage;
                }
                ?>
            </div>
<?php }
    }
} ?>

<!-- Edit approval letter -->

<?php
if (isset($this->params['named']['ame'])) {
    $annual_letter = array('id' => null, 'application_id' => $application['Application']['id'], 'approval_no' => null, 'content' => null, 'approver' => null, 'approval_date' => null, 'expiry_date' => null, 'submitted' => '1');
    if (isset($this->params['named']['ame']) && $this->params['named']['ame'] !== 'new') {
        $annual_letter = min(Hash::extract($application['AmendmentLetter'], '{n}[id=' . $this->params['named']['ame'] . ']'));
    }

?>
    <div class="ctr-groups">
        <?php echo $this->Form->create('AmendmentLetter', array(
            'url' => array('controller' => 'amendment_letters', 'action' => 'approve', $annual_letter['id']),
            'type' => 'file',
            'class' => 'form-horizontal',
            'inputDefaults' => array(
                'div' => array('class' => 'control-group'),
                'label' => array('class' => 'control-label'),
                'between' => '<div class="controls">',
                'after' => '</div>',
                'class' => '',
                'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                'error' => array('attributes' => array('class' => 'controls help-block')),
            ),
        ));
        echo $this->Form->input('id'); ?>
        <fieldset>
            <legend>Approve</strong></legend>
            <?php
            echo $this->Form->input('id', array('type' => 'hidden', 'value' => $annual_letter['id']));
            echo $this->Form->input('submitted', array('type' => 'hidden', 'value' => '1'));
            echo $this->Form->input('content', array(
                'label' => false,
                'value' => $annual_letter['content'],
                'between' => '<div class="controle">',
                'class' => 'input-large',
            ));
            ?>
        </fieldset>
        <?php echo  $this->Form->end(array(
            'label' => 'Approve',
            'value' => 'Approve',
            'class' => 'btn btn-success',
            'div' => array(
                'class' => 'form-actions',
            )
        ));
        ?>
        <script type="text/javascript">
            (function($) {

                $(".datepickers").datepicker({
                    minDate: "-5Y",
                    maxDate: "+999D",
                    dateFormat: 'dd-mm-yy',
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    //yearRange: "-100Y:+0",
                    buttonImageOnly: true,
                    showAnim: 'show',
                    showOn: 'both',
                    buttonImage: '/img/calendar.gif'
                });
            })(jQuery);

            CKEDITOR.replace('data[AmendmentLetter][content]');
        </script>
    </div>


<?php
}
if ($redir == 'applicant') {
?>
    <h5>Checklist Form</h5>
    <ul id="amendment_tab" class="nav nav-tabs">
        <li class="active"><a href="#aaa">Checklist</a></li>
        <li><a href="#bbb">Additional Files</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="aaa">
            <div class="well">
                <table id="pastyears" class="table table-bordered  table-condensed table-striped">
                    <thead>
                        <tr id="approvalsTableHeader">
                            <th>#</th>
                            <th style="width: 10%;">
                                <small class="muted">Select year</small>
                                <?php
                                $options = array();
                                for ($i = 1; $i <= 10; $i++) {
                                    $options['amd-' . $i] = $i;
                                }
                                echo $this->Form->input('Fake.year', array(
                                    'type' => 'select', // Change the input type to select
                                    'label' => false,
                                    'between' => false,
                                    'after' => false,
                                    'div' => false,
                                    'options' => $options, // Use the manually created options array
                                    'default' => date('Y'), // Set default value to current year
                                    'data-original-title' => "Click here to change years",
                                    'class' => 'span12 amendmentyear tiptip'
                                ));
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
                        $i = 0;
                        $key = 0;
                        foreach ($former as $pos => $value) {
                            $i++;
                        ?>
                            <tr>
                                <td><?php $key++;
                                    echo $i; ?></td>
                                <td>
                                    <?php
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.model', array('type' => 'hidden', 'value' => 'AmendmentChecklist'));
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.group', array('type' => 'hidden', 'value' => $pos));
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.filesize', array('type' => 'hidden'));
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.basename', array('type' => 'hidden'));
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.checksum', array('type' => 'hidden'));

                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.year', array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'div' => false,
                                        'readonly' => 'readonly',
                                        'class' => 'span11 approvalyear'
                                    ));
                                    ?>

                                </td>
                                <td>
                                    <?php
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.description', array('type' => 'hidden', 'value' => $value));
                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.pocket_name', array('type' => 'hidden', 'value' => $pos));
                                    echo '<p>' . $value . '</p>';
                                    ?>
                                </td>
                                <td class="files"><?php
                                                    echo $this->Form->input('AmendmentChecklist.' . $key . '.file', array(
                                                        'label' => false,
                                                        'between' => false,
                                                        'after' => false,
                                                        'div' => false,
                                                        'class' => 'span12 input-file',
                                                        'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                                        'type' => 'file',
                                                    ));
                                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AmendmentChecklist.' . $key . '.version_no', array(
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'div' => false,
                                        'placeholder' => 'Version',
                                        'class' => 'span12 input-file',
                                        'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                    ));
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AmendmentChecklist.' . $key . '.file_date', array(
                                        'type' => 'text',
                                        'label' => false,
                                        'between' => false,
                                        'after' => false,
                                        'div' => false,
                                        'placeholder' => 'dd-mm-yyyy',
                                        'class' => 'span12 input-file pickadate',
                                        'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
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
        </div>
        <div class="tab-pane" id="bbb">
            <div class="span12">

                <p class="selected-year-name"></p>
                <h5><i class="icon-file"></i> Add additional files:
                    <button type="button" class="btn-mini" id="addAttachmentA">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
                </h5>
                <table id="buildamendmentform" class="table table-bordered  table-condensed table-striped">
                    <thead>
                        <tr id="amendmentsTableHeader">
                            <th>#</th>
                            <th width="30%">File</th>
                            <th width="40%">Description</th>
                            <th width="5%">Version</th>
                            <th width="10%">Date</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>

    <div class="ctr-groups">

        <?php

        echo $this->Html->link(
            __('<i class="icon-thumbs-up"></i> Submit All'),
            '#', // Temporary placeholder URL
            array(
                'escape' => false,
                'class' => 'btn btn-info',
                'id' => 'submit-all-button'
            )
        );


        ?>
    </div>

<?php }  ?>
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('submit-all-button').addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to submit all?\nPlease be sure to have uploaded the individual file?')) {
                event.preventDefault();
            }
        });
    });
</script> -->

<script text="type/javascript">
    $.expander.defaults.slicePoint = 170;
    $(function() {
        //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
        //from mcaz
        $('#amendment_tab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#amendment_tab a').on("shown", function(e) {
            var id = $(e.target).attr("href");
            localStorage.setItem('assessmentTab', id)
        });

        var assessmentTab = localStorage.getItem('assessmentTab');
        if (assessmentTab != null) {
            $('#amendment_tab a[href="' + assessmentTab + '"]').tab('show');
        }

        var hashTab = $('#amendment_tab a[href="' + location.hash + '"]');
        hashTab && hashTab.tab('show');
        //end mcaz
    });
</script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // Get the button element
        var submitButton = document.getElementById('submit-all-button');

        // Attach a click event listener to the button
        submitButton.addEventListener('click', function(event) {
            // Prevent the default action (stopping the link from navigating immediately)
            event.preventDefault();

            // Get the protocol ID (this value comes from PHP)
            var protocolId = '<?php echo $application['Application']['id']; ?>'; // Example: '77'

            // Get the value from the <p> element (the selected year)
            var selectedYear = document.querySelector('.selected-year-name').textContent.trim();

            // Confirm the submission
            var confirmation = confirm("Are you sure you want to submit all?\nPlease be sure to have uploaded the individual file");

            // Ensure both protocolId and selectedYear are present and confirmed by the user
            if (confirmation && selectedYear) {
                // Construct the correct URL format: /submitall/{protocolId}/{selectedYear}
                var newHref = '/applicant/applications/submitall/' + protocolId + '/' + encodeURIComponent(selectedYear);

                // Navigate to the constructed URL
                window.location.href = newHref;
            } else if (!selectedYear) {
                alert('Please select a year before submitting.');
            }
        });
    });
</script>
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
      $this->Html->script('bootstrap-editable', array('inline' => false));
      $this->Html->css('bootstrap-editable', null, array('inline' => false));
      //Only meant for applicant
      $this->Html->script('multi/amendments-checklist', array('inline' => false));
      $this->Html->script('multi/approvalyear', array('inline' => false));
      $this->Html->script('multi/documents', array('inline' => false));
      $this->Html->script('multi/afro_attachments', array('inline' => false));

      $reviewers_comments = 0;
     foreach ($application['Review'] as $review) {
          if($review['type'] == 'ppb_comment') {
            $reviewers_comments++;
          }
     }
     // debug(Hash::check($application['Review'], '{n}[type=ppb_comment].id'));
     $this->assign('is-applicant', 'true');
    ?>

    <?php
        echo $this->Session->flash();
    ?>

    <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
          <li><a href="#tab17" data-toggle="tab">Screening</a></li>
          <li><a href="#amendments" data-toggle="tab" style="color: #52A652;">Amendments</a></li>
          <li><a href="#tab2" data-toggle="tab">Reviewers&rsquo; Comments  <small>(<?php echo $reviewers_comments;?>)</small></a></li>          
          <li><a href="#tab6" data-toggle="tab">Site Inspections (<?php echo count($application['SiteInspection']) ?>)</a></li>
          <li><a href="#tab7" data-toggle="tab">SAE/SUSAR (<?php echo count($application['Sae']) ?>)</a></li>
          <li><a href="#tab15" data-toggle="tab">CIOMS E2B (<?php echo count($application['Ciom']) ?>)</a></li>
          <li><a href="#tab13" data-toggle="tab">Protocol Deviations (<?php echo count($application['Deviation']) ?>)</a></li>
          <li><a href="#tab8" data-toggle="tab" style="color: #52A652;">Annual Approval Checklist</a></li>
          <li><a href="#tab10" data-toggle="tab" style="color: #52A652;">Annual Participants Flow</a></li>
          <li><a href="#tab14" data-toggle="tab" style="color: #52A652;">Manufacturing Site(s)</a></li>
          <li><a href="#tab11" data-toggle="tab" style="color: #52A652;">Study Budget</a></li>    
          <li><a href="#tab12" data-toggle="tab" style="color: #5e3ed3;">Annual Approval Letter</a></li>    
          <!-- <li><a href="#tab9" data-toggle="tab" style="color: #52A652;">Final Study Report</a></li> -->
          <?php if ($application['Application']['approved'] == 2) { ?>
            <li><a href="#tab9"  data-toggle="tab" style="color: #15189d;">Final Study Report</a></li>
          <?php } ?>    
      </ul>
      <div class="tab-content my-tab-content">
        <div class="tab-pane active" id="tab1">
          <!-- content for tab1 comes here -->

          <div class="row-fluid">
        <?php if ($application['Application']['submitted'] == 1) { ?>
          <h4 class="text-success">
            Submitted Application : (<?php echo $application['Application']['protocol_no']; ?>) &mdash;
            <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } else { ?>
          <h4 class="text-success">
            UnSubmitted Application : &mdash; <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } ?>

      </div>
      <?php $this->end(); ?>
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
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('class' => 'controls help-block')),
        ),
      ));
      echo $this->Form->input('id', array('value' => $application['Application']['id']));
      ?>
      <?php $this->end(); ?>

      <?php
      $this->start('form-actions');
      if ($application['Application']['submitted'] == 1) {
      ?>
        <div class="form-actions" style="margin-top: 0px; padding-left: 10px;">
          <?php
          $openAmendment = false;
          foreach ($application['Amendment'] as $key => $value) {
            if ($value['submitted'] == 0) {
              $openAmendment = $value['id'];
              break;
            }
          }
          if ($openAmendment) {
            echo $this->Html->link(
              '<i class="icon-edit"></i> Edit Open Amendment',
              array('controller' => 'amendments', 'action' => 'edit', $openAmendment),
              array('escape' => false, 'class' => 'btn btn-success',  'style' => 'margin-right: 10px;')
            );
          } else {
            echo $this->Html->link(
              'New Amendment',
              array('controller' => 'amendments', 'action' => 'add', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn btn-primary', 'style' => 'margin-right: 10px;')
            );
          }
          if (isset($this->params['named']['invoice'])) {

            // echo '<button><a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $this->params['named']['invoice'] . '">Download Invoice</a></button>'; 
            // echo $this->Html->link(
            //   __('<i class="icon-download"></i> Download Invoice'),
            //   array('https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=',  $this->params['named']['invoice']),
            //   array('escape' => false, 'class' => 'btn pull-right btn-success save-attachment')
            // );
            // echo '<button class="btn pull-right btn-success save-attachment"><a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $this->params['named']['invoice'] . '"><i class="icon-download"></i> Download Invoice</a></button>';
          } else {
          }

          /**
           * Check if the current user is the owner and has a invoice_id
           * **/
          if (empty($application['Application']['ecitizen_invoice'])) {
            echo $this->Html->link(
              __('<i class="icon-download"></i> Generate Invoice'),
              array('controller' => 'applications', 'action' => 'invoice', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn pull-right btn-success save-attachment')
            );
          } else {
            $invoice=base64_encode($application['Application']['ecitizen_invoice']);
            echo '<button class="btn pull-right btn-success save-attachment"><a href="https://prims.pharmacyboardkenya.org/crunch?type=ecitizen_invoice&id=' . $invoice . '"><i class="icon-download"></i> Download Invoice</a></button>';
          }
          echo $this->Html->link(
            __('<i class="icon-download-alt"></i> Download PDF'),
            array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
            array('escape' => false, 'class' => 'btn pull-right', 'style' => 'margin-right: 10px;')
          );

          ?>
        </div>
      <?php
      }
      $this->end();
      ?>

      <?php $this->start('tabs'); ?>
      <ul>
        <li><a href="#tabs-1">1. Abstract</a></li>
        <li><a href="#tabs-2">2. Investigator &amp; Pharmacist</a></li>
        <li><a href="#tabs-3">3. Sponsor</a></li>
        <li><a href="#tabs-4">4. Participants</a></li>
        <li><a href="#tabs-5">5. Sites</a></li>
        <li><a href="#tabs-6">6. Placebo</a></li>
        <li><a href="#tabs-7">7. Criteria</a></li>
        <li><a href="#tabs-8">8. Scope</a></li>
        <li><a href="#tabs-9">9. Design</a></li>
        <li><a href="#tabs-15">10. Study Budget</a></li>
        <li><a href="#tabs-10">11. Organizations</a></li>
        <li><a href="#tabs-11">12. Other details</a></li>
        <li><a href="#tabs-12">13. Checklist </a></li>
        <li><a href="#tabs-13">14. Declaration</a></li>
        <li><a href="#tabs-14">15. Notifications</a></li>
      </ul>
      <?php $this->end(); ?>


      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrab tab1 -->

    <div class="tab-pane" id="tab17">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h3 class="text-info">Screening for completeness</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <div class="row-fluid">
        <div class="span12">
          <br>
          <div class="amend-form">
            <h5 class="text-center"><u>FEEDBACK/QUERIES</u></h5>
            <div class="row-fluid">
              <div class="span8">
                <?php
                // debug(min(Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]')));
                // $eid = min(Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]'));
                $var = Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]');
                $eid = null;
                if (!empty($var)) $eid = min($var);
                // debug($eid);
                if (!empty($eid)) echo $this->element('comments/list', ['comments' => $eid['Comment'], 'show' => true]);
                ?>
              </div>
              <div class="span4 lefty">
                <?php
                if (!empty($eid) && !empty($eid['Comment']))  echo $this->element('comments/add', [
                  'model' => [
                    'model_id' => $application['Application']['id'], 'foreign_key' => $eid['id'],
                    'model' => 'ApplicationStage', 'category' => 'external', 'url' => 'add_screening_query'
                  ]
                ])
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="outsourcing">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/outsourcing'); ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab2">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h2 class="text-info">The Expert Committee on Clinical Trials</h2>
            <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title']; ?></p>
      <div class="row-fluid">
        <div class="span12">
          <h4 class="text-success">Reviewer's Comments
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn pull-right', 'style' => 'margin-right: 10px;')
            );
            ?>
          </h4>
          <?php
          $counter = 0;
          foreach ($application['Review'] as $review) {
            $counter++;
            echo "<hr><span class=\"badge badge-success\">" . $counter . "</span> <small class='muted'>created on: " . date('d-m-Y H:i:s', strtotime($review['created'])) . "</small>";
            echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['text'] . "</div>";
            // echo "<br>";
            echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['recommendation'] . "</div>";
          }
          ?>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span12">
          <br>
          <div class="amend-form">
            <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
            <div class="row-fluid">
              <div class="span8">
                <?php
                //Reviews limited to ppb_comment already
                $var = Hash::extract($application, 'Review.{n}[type=ppb_comment]');
                $rid = null;
                if (!empty($var)) $rid = min($var);
                // debug($rid);
                if (!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['ExternalComment'], 'show' => false]);
                ?>
              </div>
              <div class="span4 lefty">
                <?php
                if (!empty($rid))  echo $this->element('comments/add', [
                  'model' => [
                    'model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],
                    'model' => 'Review', 'category' => 'external', 'url' => 'add_review_response'
                  ]
                ])
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="tab-pane" id="tab6">
      <div class="row-fluid">
        <div class="span12">

          <?php
          echo $this->element('/application/inspection_edit');
          ?>

        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab7">
      <div class="row-fluid">
        <div class="span12">
          <?php
          if ($application['Application']['submitted']) {
            if ($application['Application']['submitted']) {
              echo "<br>";
              echo $this->Html->link(
                '<i class="icon-list-alt"></i> Create SAE',
                array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'sae'),
                array('escape' => false, 'class' => 'btn btn-success btn-mini')
              );
              echo "&nbsp;";
              echo $this->Html->link(
                '<i class="icon-credit-card"></i> Create SUSAR',
                array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'susar'),
                array('escape' => false, 'class' => 'btn btn-primary btn-mini')
              );
              echo "<br>";
              echo "<br>";
            }
          }
          ?>
          <table class="table  table-bordered table-striped">
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
              foreach ($application['Sae'] as $sae) : ?>
                <tr class="">
                  <td><?php echo h($sae['id']); ?>&nbsp;</td>
                  <td><?php echo h($sae['reference_no']); ?>&nbsp;</td>
                  <td><?php echo h($sae['report_type']);
                      if ($sae['report_type'] == 'Followup') {
                        echo "<br> Initial: ";
                        echo $this->Html->link(
                          '<label class="label label-info">' . substr($sae['reference_no'], 0, strpos($sae['reference_no'], '-')) . '</label>',
                          array('controller' => 'saes', 'action' => 'view', $sae['sae_id']),
                          array('escape' => false)
                        );
                      }
                      ?>&nbsp;
                  </td>
                  <td><?php echo h($sae['patient_initials']); ?>&nbsp;</td>
                  <td><?php echo h($sae['created']); ?>&nbsp;</td>
                  <td class="actions"> 
                    <?php 
 
                    
                    
                    if ($sae['approved'] > 0) echo $this->Html->link(
                      __('<label class="label label-info">View</label>'),
                      array('controller' => 'saes', 'action' => 'view', $sae['id']),
                      array('target' => '_blank', 'escape' => false)
                    ); ?>
                    <?php if ($redir === 'applicant' && $sae['approved'] < 1) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('controller' => 'saes', 'action' => 'edit', $sae['id']), array('target' => '_blank', 'escape' => false)); ?>
                    <?php
                    if ($sae['approved'] < 1) {

                      //ensure they own 
                      if($sae['user_id'] == $this->Session->read('Auth.User.id')){
                      echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'saes', 'action' => 'delete', $sae['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['id']));
                    }}
                    if ($redir === 'applicant' && $sae['approved'] > 0) echo $this->Form->postLink('<i class="icon-facebook"></i> Follow Up', array('controller' => 'saes', 'action' => 'followup', $sae['id']), array('class' => 'btn btn-mini btn-warning', 'escape' => false), __('Create followup for %s?', $sae['reference_no']));
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

    <div class="tab-pane" id="tab8">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/approval'); ?>
        </div>
      </div>
    </div>


    <div class="tab-pane" id="amendments">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/amendments'); ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab9">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/final'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab10">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/approval_participants'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab14">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/annual_manufacturers'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab11">
      <?php echo $this->element('multi/approval_budget'); ?>
    </div>

    <div class="tab-pane" id="tab12">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/annual_letters'); ?>
        </div>
      </div>
    </div>


</div>
</div>

<script text="type/javascript">
  $.expander.defaults.slicePoint = 170;
  $(function() {
    $(document).ajaxStop($.unblockUI);
    $("#tabs").tabs({
      cookie: {
        expires: 1
      }
    });
    $(".morecontent").expander();

    // var editor = $('#ApplicationFinalReport').ckeditor();
    if ($('#ApplicationFinalReport').length) {
      var editor = CKEDITOR.editor.replace('ApplicationFinalReport');
      var editor = CKEDITOR.editor.replace('ApplicationImplicationResults');
      var editor = CKEDITOR.editor.replace('ApplicationLaymansSummary');
    }
    $(document).on('click', '#ApplicationViewSaveReport', function() {
      var data_save = $('#ApplicationId').serializeArray();
      //var value = $('#ApplicationFinalReport').val();
      data_save.push({
        name: $('#ApplicationFinalReport').attr('name'),
        value: editor.getData()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Executive Summary saved!');
        },
        error: function(xhr, err) {
          alert('Error: Could not save executive summary of the study. Kindly refresh the page.');
        }
      });
      return false;
    });
    $('#ApplicationTrialStatusId').on('change', function() {
      // console.log($(this).serialize())
      var data_save = $('#ApplicationId').serializeArray();
      data_save.push({
        name: $(this).attr('name'),
        value: $(this).val()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Status updated!');
        },
        error: function(xhr, err) {
          alert('Error: Could not update the status of the trial. Please logout and login again.');
        }
      });
      return false;
    });
    // CKEDITOR.replace( 'data[Application][final_report]');
    $(document).on('click', '#ApplicationViewSaveShortTitle', function() {
      var data_save = $('#ApplicationId').serializeArray();
      //var value = $('#ApplicationFinalReport').val();
      data_save.push({
        name: $('#ApplicationShortTitle').attr('name'),
        value: $('#ApplicationShortTitle').val()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Short title successfully saved!');
        },
        error: function(xhr, err) {
          alert('Error: Could not save short title of the study. Kindly refresh the page.');
        }
      });
      return false;
    });
  });
</script>
<?php $this->end(); ?>

<?php
$this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
<<<<<<< HEAD
$this->assign('Applications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));
?>
<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
    <?php
    $count_reviews = 0;
    $count_comments = 0;
    $my_reviews = 0;
    foreach ($application['Review'] as $review) {
      if ($review['type'] == 'request' && $review['accepted'] != 'declined') {
        $count_reviews++;
      }
      if ($review['type'] == 'reviewer_comment') {
        $count_comments++;
      }
      if ($review['type'] == 'ppb_comment') {
        $my_reviews++;
      }
    }
=======
      $this->assign('Applications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
      $this->Html->script('jquery.blockUI.js', array('inline' => false));
      $this->Html->script('multi/amendments-checklist', array('inline' => false));
>>>>>>> 123a14be9c332510d471ebbbbe868ade284b22e2
    ?>
    <?php
    echo  '<li><a href="#tab6" data-toggle="tab">Site Inspections (' . count($application['SiteInspection']) . ')</a></li>';
    echo  '<li><a href="#tab7" data-toggle="tab">SAE/SUSAR (' . count($application['Sae']) . ')</a></li>';
    echo '<li><a href="#tab13" data-toggle="tab">Protocol Deviations (' . count($application['Deviation']) . ')</a></li>';
    ?>
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


      <?php $this->start('form-header'); ?>
      <div class="span10">
        <?php
        echo $this->Form->create('Application', array(
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
        echo $this->Form->input('id');
        ?>
        <?php $this->end(); ?>

        <?php
        $this->start('form-actions');
        ?>

        <?php
        $this->end();
        ?>

        <?php $this->start('tabs'); ?>
        <ul>
          <li><a href="#tabs-1">1. Abstract</a></li>
          <li><a href="#tabs-2">2. Investigator</a></li>
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


        <?php $this->start('view-rightbar'); ?>
      </div>
      <div class="span2">
        <?php
        if ($application['Application']['submitted'] == 1) {
        ?>
          <div class="well">
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download PDF'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn')
            );
            echo "<hr>";
            if (!$application['Application']['deactivated']) echo $this->Form->postLink(__('Deactivate'), array('action' => 'deactivate', $application['Application']['id']), array('escape' => false, 'class' => 'btn btn-warning'), __('Are you sure you want to Deactivate Application # %s? The applicant will not be able to amend it.', $application['Application']['id']));
            else  echo $this->Form->postLink(__('Reactivate'), array('action' => 'deactivate', $application['Application']['id'], 0), array('escape' => false, 'class' => 'btn btn-success'), __('Are you sure you want to Reactivate Application # %s? The applicant will now be able to amend it.', $application['Application']['id']));
            echo "<hr>";

            echo $this->Form->postLink(__('<i class="icon-trash"></i> Delete'), array('action' => 'delete', $application['Application']['id']), array('escape' => false, 'class' => 'btn btn-danger'), __('Are you sure you want to delete Application # %s? You will not be able to recover it later.', $application['Application']['id']));

            echo "<hr>";
            echo $this->Html->link(
              __('<i class="icon-search"></i> Site Inspection'),
              array('controller' => 'site_inspections', 'action' => 'add', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn btn-info')
            );
            ?>
          </div>
        <?php
        }
        ?>
      </div>
      <?php $this->end();  ?>

      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab2">
      <p style="text-align: center;"><strong>Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <hr class="soften" style="margin: 10px 0px;">
      <div class="row-fluid">
        <h4 class="text-success">Assigned Reviewers (<?php echo $count_reviews; ?>)</h4>
        <hr>
        <div class="span12">
          <?php
          echo $this->Form->create(
            'Review',
            array('url' => array('controller' => 'reviews', 'action' => 'add', $application['Application']['id']))
          );
          $counter = 0;
          echo "<ol>";
          foreach ($users as $user_id => $user) {
            echo "<li>";
            $responded = false;
            foreach ($application['Review'] as $response) {
              if ($response['user_id'] == $user_id) {
                if ($response['type'] == 'request' && $response['accepted'] == '') {
                  $responded = true;
                  echo '<p class="text-info"><i class="icon-check-empty"> </i> ' . $user . '.
                               <small class="muted">(Notified but no response yet. 
                                <a class="ResendReview tiptip" href="#" id="' . $response['id'] . '" title="Resend Notification?">Resend?</a>)</small> </p>';
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'accepted') {
                  $responded = true;
                  echo '<p class="text-success"><i class="icon-check"> </i> ' . $user . ' <small class="muted">(Accepts)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                  // echo '<p><i class="icon-minus"> </i> '.$response['text'].'</p>';
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'declined') {
                  $responded = true;
                  echo '<p class="text-error"><i class="icon-remove"> </i> ' . $user . ' <small class="muted">(Declines)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                }
              }
            }

            if (!$responded) {
              echo '<label class="checkbox" style="color: #333333">';
              echo $this->Form->checkbox($counter . '.Review.user_id', array('hiddenField' => false, 'value' => $user_id));
              echo $user;
              echo '</label>';
              // echo $this->Form->input('Reviewer.'.$counter.'.application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
            }
            $counter++;
            echo "</li>";
          }
          echo "</ol>";
          echo $this->Form->input('Message.text', array('type' => 'textarea', 'rows' => 3, 'label' => 'Message'));
          echo $this->Form->end(array(
            'label' => 'Assign',
            'value' => 'Assign',
            'class' => 'btn btn-success',
          ));
          ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab3">
      <p style="text-align: center;"><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <hr class="soften" style="margin: 10px 0px;">
      <div class="row-fluid">
        <h4 class="text-success">Reviewer Comments (<?php echo $count_comments; ?>)</h4>
        <hr>
        <div class="span12">
          <?php
          $counter = 0;
          foreach ($users as $user_id => $user) {
            $responded = false;

            foreach ($application['Review'] as $response) {
              // pr($response);
              if ($response['user_id'] == $user_id) {
                if ($response['type'] == 'request' && $response['accepted'] == 'accepted') {
                  $responded = true;
                  echo '<h4 style="text-decoration: underline"><i class="icon-check"> </i> ' . $user . '</h4>';
                  echo '<p style="padding-left: 29px;"><i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                }

                if ($response['type'] == 'reviewer_comment') {
                  echo "<small  style='padding-left: 29px;' class='muted'>created on: " . date('d-m-Y H:i:s', strtotime($response['created'])) . "</small>";
                  echo "<div style='padding-left: 29px;' class='morecontent'> <i class='icon-comment-alt'></i>
                                          <strong>Comment</strong><br>" . $response['text'] . "</div>";
                  echo "<div style='padding-left: 29px;' class='morecontent'> <i class=\"icon-comment-alt\"></i>
                                          <strong>Recommendation</strong><br>" . $response['recommendation'] . "</div><hr>";
                }
              }
            }
            $counter++;
          }
          ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab4">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h2 class="text-info">The Expert Committee on Clinical Trials</h2>
            <h3 class="text-info" style="text-decoration: underline;">PPB Manager's Comments Form</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title']; ?></p>
      <div class="row-fluid">
        <div class="span8">
          <?php
          echo $this->Form->create('Review', array('url' => array(
            'controller' => 'reviews',
            'action' => 'comment', $application['Application']['id']
          )));


          echo $this->Form->input('application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
          echo $this->Form->input('text', array(
            'type' => 'textarea', 'rows' => 3,
            'label' => array('class' => 'required', 'text' => '3. Manager\'s Comment(s)')
          ));
          echo $this->Form->input('recommendation', array(
            'type' => 'textarea', 'rows' => 3,
            'label' => array('class' => 'required', 'text' => '4. Manager\'s recommendation(s)')
          ));
          echo $this->Form->input('password', array('type' => 'password', 'label' => 'Your Password *'));
          echo $this->Form->end(array(
            'label' => 'Submit Review',
            'value' => 'SubmitReviews',
            'class' => 'btn btn-success',
          ));
          ?>
        </div>
        <div class="span4">
          <h4 class="text-success">My Previous Comments <small>(<?php echo $my_reviews; ?>)</small></h4>
          <?php
          $counter = 0;
          // pr($this->Session->read('Auth.User.id'));
          foreach ($application['Review'] as $review) {
            // PPB Manager should be able to see all manager's comments
            if ($review['type'] == 'ppb_comment') {
              $counter++;
              echo "<hr><span class=\"badge badge-success\">" . $counter . "</span> <small class='muted'>
                                created on: " . date('d-m-Y H:i:s', strtotime($review['created'])) . "</small>";
              echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['text'] . "</div>";
              echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['recommendation'] . "</div>";
            }
          }
          ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab5">
      <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <p><strong>2. Study Title: </strong><?php echo $application['Application']['study_title']; ?></p>
      <hr class="soften" style="margin: 10px 0px;">
      <div class="row-fluid">
        <div class="span12">
          <?php
          if ($application['Application']['approved'] == 2) {
            echo "<h2> Approved <h2>";
          } elseif ($application['Application']['approved'] == 1) {
            echo "<h2>Rejected</h2>";
          } else {
          ?>
            <h4 class="text-success">Approve or Reject application <span class="muted">:if no, the application is rejected</span></h4>
            <hr>
            <?php
            echo $this->Form->create('Application', array(
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
            echo $this->Form->input('id', array('value' => $application['Application']['id'], 'type' => 'hidden'));
            echo $this->Form->input('approval_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));

            echo $this->Form->input('approved', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
              'class' => 'approved',
              'before' => '<div class="control-group">   <label class="control-label required">
                        Approve? <span class="sterix">*</span></label>  <div class="controls">
                        <input type="hidden" value="" id="ApplicationApproved_" name="data[Application][approved]"> <label class="radio inline">',
              'after' => '</label>',
              'options' => array(2 => 'Yes'),
            ));
            echo $this->Form->input('approved', array(
              'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'class' => 'approved',
              'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
              'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
              'before' => '<label class="radio inline">',
              'after' => '</label>
                            <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                            onclick="$(\'.approved\').removeAttr(\'checked disabled\')">
                            <em class="accordion-toggle">clear!</em></a> </span>
                            </div> </div>',
              'options' => array(1 => 'No'),
            ));

            echo $this->Form->input('approved_reason', array(
              'label' => array('class' => 'control-label', 'text' => 'Message'),
              'placeholder' => ' ', 'class' => 'input-xlarge',  'rows' => '3'
            ));
            echo $this->Form->input('password', array(
              'label' => array('class' => 'control-label', 'text' => 'Your Password <span class="sterix">*</span>'),
              'placeholder' => 'password', 'class' => 'input-large',
            ));
            ?>
            <div class="controls">
              <?php
              echo $this->Form->button('<i class="icon-save"></i> Submit', array(
                'name' => 'submit',
                'onclick' => '$(\'#ApplicationEditForm\').validate().cancelSubmit = true;',
                'class' => 'btn btn-primary',
                'id' => 'SadrSaveChanges',
              ));
              ?>
            </div>
          <?php
            echo $this->Form->end();
          }
          ?>
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
    <div class="tab-pane" id="tab13">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/deviation'); ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab7">
      <div class="row-fluid">
        <div class="span12">

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
                    <?php if ($sae['approved'] > 0) echo $this->Html->link(
                      __('<label class="label label-info">View</label>'),
                      array('controller' => 'saes', 'action' => 'view', $sae['id']),
                      array('target' => '_blank', 'escape' => false)
                    ); ?>
                    <?php if ($redir === 'applicant' && $sae['approved'] < 1) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('controller' => 'saes', 'action' => 'edit', $sae['id']), array('escape' => false)); ?>
                    <?php
                    if ($sae['approved'] < 1) {
                      echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'saes', 'action' => 'delete', $sae['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['id']));
                    }
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
    $('#ReviewText').ckeditor();
    $('#ReviewRecommendation').ckeditor();

    $('.ResendReview').on('click', function() {
      // console.log($(this).serialize())
      var data_save = new Array();
      var aindi = $(this).attr('id');
      data_save.push({
        name: "data[Review][id]",
        value: aindi
      });
      $.ajax({
        url: '/manager/notifications/resend/' + aindi,
        type: 'put',
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
          alert('Notification has been resent!');
        },
        error: function(xhr, err) {
          alert('Error: Could not resend notification. Contact administrator.');
        }
      });
      return false;
    });
  });
</script>
<?php $this->end(); ?>
<?php
$this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
$this->assign('Reports', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('bootstrap-editable', array('inline' => false));
$this->Html->css('bootstrap-editable', null, array('inline' => false));
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
    ?>
    <li><a href="#tab2" data-toggle="tab">Assigned Reviewers <small>(<?php echo $count_reviews; ?>)</small></a></li>
    <li><a href="#tab3" data-toggle="tab">Reviewer Comments <small>(<?php echo $count_comments; ?>)</small></a></li>
    <li><a href="#tab4" data-toggle="tab">Manager Reviews <small>(<?php echo $my_reviews; ?>)</small></a></li>
    <li><a href="#tab5" data-toggle="tab">Approval Status <small>(<?php
                                                                  if ($application['Application']['approved'] == 2) echo 'Approved';
                                                                  elseif ($application['Application']['approved'] == 1) echo 'Rejected';
                                                                  ?>)</small></a></li>
    <li><a href="#status" data-toggle="tab">Application Status <small></small></a></li>
    <li><a href="#invoice" data-toggle="tab">Additional Invoice <small></small></a></li>
  </ul>
  <div class="tab-content my-tab-content">
    <div class="tab-pane active" id="tab1">
      <!-- content for tab1 comes here -->

      <div class="row-fluid">
        <?php if ($application['Application']['submitted'] == 1) { ?>
          <h4 class="text-success">
            Submitted Application : (
            <span class="xeditable iseditable" id="data[Application][protocol_no]" data-type="text" data-pk="<?php echo $application['Application']['id']; ?>" data-original-title="Update protocol no">
              <?php echo $application['Application']['protocol_no']; ?></span>
            ) &mdash;
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
            ?>
          </div>
        <?php
        }
        ?>
      </div>
      <?php $this->end();  ?>

      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrap tab1 -->
    <div class="tab-pane" id="tab2">
      <p style="text-align: center;"><strong>Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <hr class="soften" style="margin: 10px 0px;">
      <div class="row-fluid">
        <h4 class="text-success">Assigned Reviewers (<?php echo $count_reviews; ?>)</h4>
        <hr>
        <div class="span12">
          <?php
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
                               <small class="muted">(Notified but no response yet.)</small></p>';
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
              echo '<p><i class="icon-check-empty"> </i> ' . $user . '.
                               <small class="muted">(Not requested.)</small></p>';
            }
            $counter++;
            echo "</li>";
          }
          echo "</ol>";
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
        <div class="span12">
          <p><strong>3. Manager's Comments <small>(<?php echo $my_reviews; ?>)</small></strong></p>
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
    <div class="tab-pane" id="status">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-info">Modify Application Status</h4>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>

      <div class="row-fluid">
        <div class="span4">
          <?php


          echo $this->Form->create('Application', array(
            'url' => array('controller' => 'applications', 'action' => 'suspend',$application['Application']['id']),
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
         
          echo $this->Form->input('status', array(
            'type' => 'select',
            'options' => array(
              '1'=>'Recruiting',
              '2'=>'Not yet recruiting',
              '3' => 'Suspended',
              '4' => 'Stopped',
              '5'=>'Completed',
              '6'=>'In follow-up',
              '7'=>'Analysing',
              '8'=>'Writing-up',
              '9'=>'Application withdrawn'
            ),
            'empty' => true,
            'label' => array('class' => 'control-nolabel required', 'text' => '<h5> Trial Status  <span class="sterix">*</span></h5>'),
            'placeholder' => ' ', 'class' => 'input-xxlarge', 'between' => '<div class="nocontrols">',
            'escape' => false,
          ));
          echo $this->Form->input('admin_stopped_reason', array(
            'label' => array('class' => 'control-nolabel required', 'text' => '<h5> Reason <span class="sterix">*</span></h5>'),
            'placeholder' => ' ', 'class' => 'input-xxlarge', 'between' => '<div class="nocontrols">',
            'escape' => false,
          ));

          echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
            'name' => 'submitReport',
            'formnovalidate' => 'formnovalidate',
            'onclick' => "return confirm('Are you sure you wish to change the status of the application?');",
            'class' => 'btn btn-info btn-block mapop',
            'id' => 'ApplicationSubmitReport', 'title' => 'Save and Submit Report',
            'data-content' => 'Save the report and submit it to the pharmacy and Poisons Board. You will also get a copy of this report.',
            'div' => false,
          ));

          ?>
          <hr>
          <?php
          echo $this->Form->end();
          ?>
        </div>
      </div>
    </div>

    <!-- Invoice Details -->

    <div class="tab-pane" id="invoice">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
             <h4 class="text-info">
            Invoice Generation : (
            <span class="xeditable iseditable" id="data[Application][protocol_no]" data-type="text" data-pk="<?php echo $application['Application']['id']; ?>" data-original-title="Update protocol no">
              <?php echo $application['Application']['protocol_no']; ?></span>
            ) &mdash;
            <small>Additional sites</small>
          </h4>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>

      <div class="row-fluid">
        <div class="span4">
          <?php


          echo $this->Form->create('Application', array(
            'url' => array('controller' => 'applications', 'action' => 'extra',$application['Application']['id']),
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
         
          echo $this->Form->input('total_sites', array(
            'type' => 'number', 
            'min'=>1,
            'label' => array('class' => 'control-nolabel required', 'text' => '<h5> Extra Site  <span class="sterix">*</span></h5>'),
            'placeholder' => ' ', 'class' => 'input-xxlarge', 'between' => '<div class="nocontrols">',
            'escape' => false,
          ));
          echo $this->Form->input('admin_stopped_reason', array(
            'label' => array('class' => 'control-nolabel required', 'text' => '<h5> Reason <span class="sterix">*</span></h5>'),
            'placeholder' => ' ', 'class' => 'input-xxlarge', 'between' => '<div class="nocontrols">',
            'escape' => false,
          ));

          echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
            'name' => 'submitReport',
            'formnovalidate' => 'formnovalidate',
            'onclick' => "return confirm('Are you sure you wish to generate an extra invoice to this protocol?');",
            'class' => 'btn btn-info btn-block mapop',
            'id' => 'ApplicationSubmitReport', 'title' => 'Save and Submit Report',
            'data-content' => 'Save the report and submit it to the pharmacy and Poisons Board. You will also get a copy of this report.',
            'div' => false,
          ));

          ?>
          <hr>
          <?php
          echo $this->Form->end();
          ?>
        </div>
      </div>
    </div>

    <!-- End of Invoice -->

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
            echo "<h4>Not yet set!</h4>";
          }
          ?>
        </div>
      </div>
    </div>

  </div>
</div>

<script text="type/javascript">
  $.expander.defaults.slicePoint = 170;
  $.fn.editable.defaults.mode = 'popup';
  $(function() {
    $('#data\\[Application\\]\\[approval_date\\]').editable({
      url: '/admin/applications/view/<?php echo $application["Application"]["id"]; ?>',
      ajaxOptions: {
        dataType: 'json' //assuming json response
      },
      params: function(params) {
        var data = {};
        data['_method'] = 'POST';
        data['data[Application][id]'] = params.pk;
        data[params.name] = params.value;
        return data;
      },
      format: 'dd-mm-yyyy',
      viewformat: 'dd-mm-yyyy',
      datepicker: {
        firstDay: 1
      }
    });

    function traverse_it(obj, enda) {
      for (var prop in obj) {
        if (typeof obj[prop] == 'object') {
          traverse_it(obj[prop], enda);
        } else {
          enda.push(obj[prop]);
        }
      }
      return enda;
    }
    $('.iseditable').editable({
      url: '/admin/applications/view/<?php echo $application["Application"]["id"]; ?>',
      ajaxOptions: {
        dataType: 'json' //assuming json response
      },
      params: function(params) {
        var data = {};
        data['_method'] = 'POST';
        // data['data[Application][id]'] = params.pk;
        fieldName = params.name.replace(/\[[\w]*\]$/g, '[id]');
        data[fieldName] = params.pk;
        data[params.name] = params.value;
        return data;
      },
      success: function(response, newValue) {
        if (response.message.message == 'Success') { //record created, response like {"id": 2}
          //proceed success...
        } else if (response.errors) {
          //server-side validation error, response like {"errors": {"username": "username already exist"} }
          //call error
          /*This if not nested error message*/
          /*for(var key in response.errors) {
              if(response.errors.hasOwnProperty(key)) {
                   return response.errors[key].join('<br>');
              }
          }*/
          return traverse_it(response.errors, []).join('\n');
          // return response.errors.investigator1_email;
          // config.error.call(this, data.errors);
        }
      },
      validate: function(value) {
        if ($.trim(value) == '') {
          return 'This field is required';
        }
      }
    });
    // $('.xeditable').editable();
    $("#tabs").tabs({
      cookie: {
        expires: 1
      }
    });
    $(".morecontent").expander();
    $('#ReviewText').ckeditor();
    $('#ReviewRecommendation').ckeditor();
  });
</script>
<?php $this->end(); ?>
<?php
  $this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
      $this->assign('Applications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));

      $formatFeedbackContent = function ($content) {
        $content = trim((string) $content);
        if ($content === '') {
          return '<span class="muted">No content provided.</span>';
        }

        // If rich text is present, keep only common formatting tags.
        if ($content !== strip_tags($content)) {
          return strip_tags(
            $content,
            '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><a><span><div>'
          );
        }

        return '<p>' . nl2br(h($content)) . '</p>';
      };
    ?>
    <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
          <li><a href="#tab2" data-toggle="tab">My Reviews <small>(<?php echo count($application['Review']);?>)</small></a></li>
          <li><a href="#tab3" data-toggle="tab">Manager Reviews <small>(<?php echo count($application['ManagerReview']);?>)</small></a></li>
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
              'format' => array('before', 'label', 'between', 'input', 'after','error'),
              'error' => array('attributes' => array( 'class' => 'controls help-block')),
             ),
          ));
      echo $this->Form->input('id');
    ?>
<?php $this->end();?>

<?php
  $this->start('form-actions');
?>
 <!-- content for form actions -->
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

<!-- START RIGHTBAR -->
<?php $this->start('view-rightbar'); ?>
  </div>
  <div class="span2">
    <div class="form-actions"  style="margin-top: 0px; margin-bottom: 0px; padding-left: 10px;">
    <?php
       echo $this->Html->link(__('<i class="icon-download-alt"></i> <br> <span><strong>Download PDF</strong></span>'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
    ?>
  </div>
</div>
<?php $this->end();  ?>
<!-- END RIGHTBAR -->

<?php $this->start('endjs'); ?>
  </div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab2">
      <div class="row-fluid">
        <div class="span12">
          <?php if (!empty($priorInternalFeedback)) { ?>
            <div class="alert alert-info">
              Previous internal reviewer feedback is shown below.
            </div>
            <table class="table table-bordered table-striped table-condensed">
              <thead>
                <tr>
                  <th style="width: 6%;">ID</th>
                  <th style="width: 18%;">Recommendation</th>
                  <th style="width: 28%;">Comments</th>
                  <th style="width: 14%;">Status &amp; Type</th>
                  <th style="width: 12%;">User</th>
                  <th style="width: 12%;">Created</th>
                  <th style="width: 10%;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($priorInternalFeedback as $feedbackIndex => $feedback) { ?>
                  <?php
                    $reviewerName = 'Reviewer';
                    if (!empty($feedback['User']['name'])) {
                      $reviewerName = $feedback['User']['name'];
                    } elseif (!empty($feedback['User']['username'])) {
                      $reviewerName = $feedback['User']['username'];
                    } elseif (!empty($feedback['Review']['user_id'])) {
                      $reviewerName = 'Reviewer #' . (int) $feedback['Review']['user_id'];
                    }

                    $feedbackReviewId = !empty($feedback['Review']['id']) ? (int) $feedback['Review']['id'] : 0;
                    $assessmentType = !empty($feedback['Review']['assessment_type']) ? ucfirst($feedback['Review']['assessment_type']) : 'Assessment';
                    $status = !empty($feedback['Review']['status']) ? $feedback['Review']['status'] : 'Unknown status';
                    $createdAt = !empty($feedback['Review']['created']) ? strtotime($feedback['Review']['created']) : false;
                    $createdLabel = $createdAt ? date('d-m-Y H:i', $createdAt) : 'Unknown time';
                    $recommendationRaw = !empty($feedback['Review']['recommendation']) ? (string) $feedback['Review']['recommendation'] : '';
                    $recommendationPlain = trim(preg_replace('/\s+/', ' ', strip_tags($recommendationRaw)));
                    $recommendationDisplay = ($recommendationPlain !== '')
                      ? h($this->Text->truncate($recommendationPlain, 90, array('ellipsis' => '...', 'exact' => false)))
                      : '<span class="muted">N/A</span>';

                    $internalComments = !empty($feedback['InternalComment']) ? (array) $feedback['InternalComment'] : array();
                    $commentPreviewItems = array();
                    foreach ($internalComments as $internalComment) {
                      $commentBodyRaw = !empty($internalComment['content']) ? (string) $internalComment['content'] : '';
                      $commentBodyPlain = trim(preg_replace('/\s+/', ' ', strip_tags($commentBodyRaw)));
                      if ($commentBodyPlain !== '') {
                        $commentPreviewItems[] = h($this->Text->truncate($commentBodyPlain, 90, array('ellipsis' => '...', 'exact' => false)));
                      }
                      if (count($commentPreviewItems) >= 2) {
                        break;
                      }
                    }
                    if (!empty($commentPreviewItems)) {
                      $commentsPreview = implode('<br>', $commentPreviewItems);
                      $remainingComments = count($internalComments) - count($commentPreviewItems);
                      if ($remainingComments > 0) {
                        $commentsPreview .= '<br><span class="muted">+' . (int) $remainingComments . ' more comment(s)</span>';
                      }
                    } else {
                      $commentsPreview = '<span class="muted">No comments yet.</span>';
                    }

                    $summaryModalId = 'priorInternalSummaryModal' . $feedbackReviewId . '_' . (int) $feedbackIndex;
                    $viewModalId = 'priorInternalViewModal' . $feedbackReviewId . '_' . (int) $feedbackIndex;
                  ?>
                  <tr>
                    <td><?php echo ((int) $feedbackIndex + 1); ?></td>
                    <td>
                      <?php echo $recommendationDisplay; ?>
                      <br>
                      <button type="button" class="btn btn-mini btn-info" data-toggle="modal" data-target="#<?php echo h($summaryModalId); ?>" style="margin-top: 5px;">
                        Summary
                      </button>
                    </td>
                    <td><?php echo $commentsPreview; ?></td>
                    <td><?php echo h($status . ' / ' . $assessmentType); ?></td>
                    <td><?php echo h($reviewerName); ?></td>
                    <td><?php echo h($createdLabel); ?></td>
                    <td>
                      <button type="button" class="btn btn-mini btn-primary" data-toggle="modal" data-target="#<?php echo h($viewModalId); ?>">
                        View
                      </button>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>

            <?php foreach ($priorInternalFeedback as $feedbackIndex => $feedback) { ?>
              <?php
                $feedbackReviewId = !empty($feedback['Review']['id']) ? (int) $feedback['Review']['id'] : 0;
                $summaryModalId = 'priorInternalSummaryModal' . $feedbackReviewId . '_' . (int) $feedbackIndex;
              ?>
              <div id="<?php echo h($summaryModalId); ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4>Summary #<?php echo $feedbackReviewId; ?></h4>
                </div>
                <div class="modal-body">
                  <?php if (!empty($feedback['Review']['summary'])) { ?>
                    <?php echo $formatFeedbackContent($feedback['Review']['summary']); ?>
                  <?php } else { ?>
                    <p class="muted">No summary available.</p>
                  <?php } ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal">Close</button>
                </div>
              </div>
            <?php } ?>
            <?php foreach ($priorInternalFeedback as $feedbackIndex => $feedback) { ?>
              <?php
                $selectedReviewId = !empty($feedback['Review']['id']) ? (int) $feedback['Review']['id'] : 0;
                $selectedAssessmentType = !empty($feedback['Review']['assessment_type']) ? ucfirst($feedback['Review']['assessment_type']) : 'Assessment';
                $viewModalId = 'priorInternalViewModal' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $assessmentTabId = 'priorInternalViewAssessmentTab' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $commentsTabId = 'priorInternalViewCommentsTab' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $modalReview = $feedback['Review'];
                $modalReview['ReviewAnswer'] = !empty($feedback['ReviewAnswer']) ? $feedback['ReviewAnswer'] : array();
                $modalComments = !empty($feedback['InternalComment']) ? $feedback['InternalComment'] : array();
              ?>
              <div id="<?php echo h($viewModalId); ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true" style="width: 900px; margin-left: -450px;">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4><?php echo h($selectedAssessmentType); ?> Assessment Form #<?php echo $selectedReviewId; ?></h4>
                </div>
                <div class="modal-body" style="max-height: 70vh;">
                  <ul class="nav nav-tabs" style="margin-bottom: 10px;">
                    <li class="active"><a href="#<?php echo h($assessmentTabId); ?>" data-toggle="tab">Assessment Form</a></li>
                    <li><a href="#<?php echo h($commentsTabId); ?>" data-toggle="tab">Comments (<?php echo count($modalComments); ?>)</a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="<?php echo h($assessmentTabId); ?>">
                      <h3 style="text-align: center;"><?php echo h($selectedAssessmentType); ?> Assessment Form</h3>
                      <hr class="soften" style="margin: 10px 0px;">

                      <table class="table table-bordered table-condensed">
                        <tbody>
                          <tr>
                            <th class="my-well" style="width: 45%">Study Title</th>
                            <td>
                              <div style="font-weight: 600; line-height: 1.5;">
                                <?php echo $formatFeedbackContent($application['Application']['study_title']); ?>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <th class="my-well">Short Title</th>
                            <td><?php echo h($application['Application']['short_title']); ?></td>
                          </tr>
                          <tr>
                            <th class="my-well">ECCT Reference No</th>
                            <td><?php echo h($application['Application']['protocol_no']); ?></td>
                          </tr>
                          <tr>
                            <th class="my-well">Investigation medicinal product</th>
                            <td><?php echo h($application['Application']['study_drug']); ?></td>
                          </tr>
                        </tbody>
                      </table>

                      <table class="table table-bordered table-condensed">
                        <thead>
                          <tr>
                            <th></th>
                            <th width="35%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ((array) $modalReview['ReviewAnswer'] as $answer) { ?>
                            <?php if ($answer['question_type'] == 'label') { ?>
                              <tr class="success">
                                <td colspan="2"><strong><?php echo h($answer['question']); ?></strong></td>
                              </tr>
                            <?php } elseif ($answer['question_type'] == 'yesno') { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td><?php echo h($answer['answer']); ?></td>
                              </tr>
                            <?php } elseif ($answer['question_type'] == 'text') { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td><?php echo $formatFeedbackContent($answer['answer']); ?></td>
                              </tr>
                            <?php } elseif ($answer['question_type'] == 'workspace') { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td><?php echo $formatFeedbackContent($answer['workspace']); ?></td>
                              </tr>
                            <?php } elseif ($answer['question_type'] == 'comment') { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td><?php echo $formatFeedbackContent($answer['comment']); ?></td>
                              </tr>
                            <?php } ?>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane" id="<?php echo h($commentsTabId); ?>">
                      <div class="row-fluid">
                        <div class="span7">
                          <?php if (!empty($modalComments)) { ?>
                            <?php echo $this->element('comments/list_expandable', ['comments' => $modalComments, 'category' => false]); ?>
                          <?php } else { ?>
                            <p class="muted">No comments available yet.</p>
                          <?php } ?>
                        </div>
                        <div class="span5">
                          <h5 class="text-info" style="margin-top: 0;"><u>Add Comment</u></h5>
                          <?php
                            echo $this->element('comments/add', [
                              'model' => [
                                'model_id' => $application['Application']['id'],
                                'foreign_key' => $selectedReviewId,
                                'model' => 'Review',
                                'type' => 51,
                                'category' => 'internal',
                                'url' => 'add_review_internal'
                              ]
                            ]);
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal">Close</button>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
          <?php echo $this->element('application/review'); ?>
        </div>
      </div>
    </div>


    <div class="tab-pane" id="tab3">
        <div class="marketing">
             <div class="row-fluid">
                <div class="span12">
                   <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
                   <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments</h3>
                </div>
             </div>
              <hr class="soften" style="margin: 10px 0px;">
        </div>
        <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no'];?></p>
        <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title'];?></p>
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-success">Reviewer's Comments
              <?php
                echo $this->Html->link(__('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
                  array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
                  array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
                ?>
              </h4>
            <?php
                $counter = 0;
                foreach ($application['ManagerReview'] as $review) {
                   $counter++;
                   echo "<hr><span class=\"badge badge-success\">".$counter."</span> <small class='muted'>created on: ".date('d-m-Y H:i:s', strtotime($review['created']))."</small>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['text']."</div>";
                   // echo "<br>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['recommendation']."</div>";
                }
            ?>
          </div>
       </div>


       <?php
          //Reviews limited to ppb_comment already
            $var = Hash::extract($application, 'ManagerReview.{n}[type=ppb_comment]');
            $rid = null;
            if(!empty($var)) $rid = min($var);
       ?>
        <ul id="reviewer_tab" class="nav nav-tabs">
          <li class="active"><a href="#external_rev_comments" data-toggle="tab">PI Comments (<?php echo count($rid['ExternalComment']); ?>)</a></li>
          <?php if($redir !== 'applicant') { ?><li><a href="#internal_rev_comments" data-toggle="tab">Internal Comments (<?php echo count($rid['InternalComment']); ?>)</a></li> <?php } ?>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="external_rev_comments">
              <div class="row-fluid">
                <div class="span12">
                  <br>
                    <div class="amend-form">
                      <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
                      <div class="row-fluid">
                        <div class="span8">    
                          <?php                       
                            // debug($rid);
                            if(!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['ExternalComment'],'show'=>false]);
                          ?> 
                        </div>
                        <div class="span4 lefty">
                        <?php  
                            //----------Manager can't respond directly to Applicant---------------------------------
                            // if(!empty($rid))  echo $this->element('comments/add', [
                            //              'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],   
                            //                          'model' => 'Review', 'category' => 'external', 'url' => 'add_review_response']]) 
                        ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>

          <div class="tab-pane" id="internal_rev_comments">
              <div class="row-fluid">
                <div class="span12">
                  <br>
                    <div class="amend-form">
                      <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
                      <div class="row-fluid">
                        <div class="span8">    
                          <?php                       
                            // debug($rid);
                            if(!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['InternalComment'],'show'=>false]);

                            //NEW*** Bring in all the assessment comments
                            $rcas = Hash::extract($application, 'Review.{n}[type=reviewer_comment]');
                            if(!empty($rcas)) {
                              echo "<hr>";
                              echo "<h4 class='text-success' style='text-align: center; text-decoration: underline'>Assessment comments</h4>";
                              foreach ($rcas as $rca) {
                                echo $this->element('comments/list', ['comments' => $rca['InternalComment'],'show'=>false]);
                              }
                            }
                            //end
                          ?> 
                        </div>
                        <div class="span4 lefty">
                        <?php  
                            if(!empty($rid))  echo $this->element('comments/add', [
                                         'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],   
                                                     'model' => 'Review', 'category' => 'internal', 'url' => 'add_internal_review_response']]) 
                        ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>         
        </div>

    </div>

</div>
</div>

<script text="type/javascript">
$.expander.defaults.slicePoint = 170;
$(function() {
  $( "#tabs" ).tabs({
      cookie: {
        expires: 1
      }
  });

  var $reviewerTabs = $('#reviewer_tab a[data-toggle="tab"]');
  $reviewerTabs.off('.assessmentTabs');
  $reviewerTabs.on('click.assessmentTabs', function (e) {
      e.preventDefault();
      $(this).tab('show');
  });

  $reviewerTabs.on("shown.assessmentTabs shown.bs.tab.assessmentTabs", function (e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('assessmentTab', id)
  });

  var assessmentTab = localStorage.getItem('assessmentTab');
  if (assessmentTab != null && $reviewerTabs.filter('[href="' + assessmentTab + '"]').length) {
      $reviewerTabs.filter('[href="' + assessmentTab + '"]').tab('show');
  }

  var hashaTab = $reviewerTabs.filter('[href="' + location.hash + '"]');
  hashaTab && hashaTab.tab('show');

  if ($.fn.expander) {
      $(".morecontent").expander();
  }
  if ($.fn.ckeditor) {
      $('#ReviewText').ckeditor();
      $('#ReviewRecommendation').ckeditor();
  }
});
</script>
<?php $this->end();?>

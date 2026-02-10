<?php
  $this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
      $this->assign('Applications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
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
              Previous internal reviewer feedback is shown below. Open one reviewer at a time before adding your own assessment comments.
            </div>
            <div class="accordion" id="priorInternalFeedbackAccordion">
              <?php foreach ($priorInternalFeedback as $feedbackIndex => $feedback) { ?>
                <?php
                  $panelId = 'priorInternalFeedback' . (int) $feedbackIndex;
                  $reviewerName = 'Reviewer';
                  if (!empty($feedback['User']['name'])) {
                    $reviewerName = $feedback['User']['name'];
                  } elseif (!empty($feedback['User']['username'])) {
                    $reviewerName = $feedback['User']['username'];
                  } elseif (!empty($feedback['Review']['user_id'])) {
                    $reviewerName = 'Reviewer #' . (int) $feedback['Review']['user_id'];
                  }
                  $assessmentType = !empty($feedback['Review']['assessment_type']) ? ucfirst($feedback['Review']['assessment_type']) : 'Assessment';
                  $status = !empty($feedback['Review']['status']) ? $feedback['Review']['status'] : 'Unknown status';
                  $createdAt = !empty($feedback['Review']['created']) ? strtotime($feedback['Review']['created']) : false;
                  $createdLabel = $createdAt ? date('d-m-Y H:i', $createdAt) : 'Unknown time';
                  $responseCount = !empty($feedback['FeedbackAnswer']) ? count($feedback['FeedbackAnswer']) : 0;
                ?>
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#priorInternalFeedbackAccordion" href="#<?php echo h($panelId); ?>">
                      <strong><?php echo h($reviewerName); ?></strong> -
                      <?php echo h($assessmentType); ?>
                      <small class="muted">
                        (<?php echo h($status); ?>, <?php echo h($createdLabel); ?>)
                      </small>
                      <?php if ($responseCount > 0) { ?>
                        <span class="badge pull-right"><?php echo (int) $responseCount; ?> responses</span>
                      <?php } ?>
                    </a>
                  </div>
                  <div id="<?php echo h($panelId); ?>" class="accordion-body collapse">
                    <div class="accordion-inner">
                      <?php if (!empty($feedback['Review']['summary'])) { ?>
                        <p>
                          <strong>Summary:</strong><br>
                          <?php echo nl2br(h($feedback['Review']['summary'])); ?>
                        </p>
                      <?php } ?>
                      <?php if (!empty($feedback['FeedbackAnswer'])) { ?>
                        <table class="table table-bordered table-condensed">
                          <thead>
                            <tr>
                              <th style="width: 45%;">Question</th>
                              <th>Response</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($feedback['FeedbackAnswer'] as $answer) { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td>
                                  <?php
                                    $responseParts = array();
                                    if (trim((string) $answer['answer']) !== '') $responseParts[] = '<strong>Answer:</strong> ' . h($answer['answer']);
                                    if (trim((string) $answer['workspace']) !== '') $responseParts[] = '<strong>Workspace:</strong> ' . nl2br(h($answer['workspace']));
                                    if (trim((string) $answer['comment']) !== '') $responseParts[] = '<strong>Comment:</strong> ' . nl2br(h($answer['comment']));
                                    echo implode('<br>', $responseParts);
                                  ?>
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      <?php } ?>
                      <?php
                        $feedbackComments = !empty($feedback['InternalComment']) ? $feedback['InternalComment'] : array();
                        $feedbackReviewId = !empty($feedback['Review']['id']) ? (int) $feedback['Review']['id'] : 0;
                        $addCommentCollapseId = 'priorFeedbackAddComment' . $feedbackReviewId;
                      ?>
                      <hr>
                      <h6 class="text-info">Comments/Queries (<?php echo count($feedbackComments); ?>)</h6>
                      <?php if (!empty($feedbackComments)) { ?>
                        <?php echo $this->element('comments/list_expandable', ['comments' => $feedbackComments, 'category' => false]); ?>
                      <?php } else { ?>
                        <p class="muted">No comments yet for this review.</p>
                      <?php } ?>

                      <?php if ($feedbackReviewId > 0) { ?>
                        <a class="btn btn-small btn-info" data-toggle="collapse" href="#<?php echo h($addCommentCollapseId); ?>">
                          Add Comment To This Review
                        </a>
                        <div id="<?php echo h($addCommentCollapseId); ?>" class="collapse" style="margin-top: 10px;">
                          <?php
                            echo $this->element('comments/add', [
                              'model' => [
                                'model_id' => $application['Application']['id'],
                                'foreign_key' => $feedbackReviewId,
                                'model' => 'Review',
                                'type' => 51,
                                'category' => 'internal',
                                'url' => 'add_review_internal'
                              ]
                            ]);
                          ?>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
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

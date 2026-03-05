<?php
  $this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
      $this->assign('Applications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));

      $formatManagerReviewContent = function ($content) {
        $content = trim((string)$content);
        if ($content === '') {
          return '<span class="muted">No details provided.</span>';
        }

        if ($content !== strip_tags($content)) {
          return $content;
        }

        return '<p>' . nl2br(h($content)) . '</p>';
      };

      $managerReviewComments = Hash::extract($application, 'ManagerReview.{n}[type=ppb_comment]');
      if (!empty($managerReviewComments)) {
        usort($managerReviewComments, function ($a, $b) {
          return strtotime($b['created']) - strtotime($a['created']);
        });
      } 

      $latestManagerReview = !empty($managerReviewComments) ? $managerReviewComments[0] : array();
      $latestManagerReviewExternal = !empty($latestManagerReview['ExternalComment']) ? $latestManagerReview['ExternalComment'] : array();
      $latestManagerReviewInternal = !empty($latestManagerReview['InternalComment']) ? $latestManagerReview['InternalComment'] : array();
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
    <?php if ($application['Application']['submitted'] == 1) { ?>
      <div class="well">
        <?php
          echo $this->Html->link(
            __('<i class="icon-download-alt"></i> Download PDF'),
            array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
            array('escape' => false, 'class' => 'btn')
          );
        ?>
      </div>
    <?php } ?>
  </div>
<?php $this->end();  ?>
<!-- END RIGHTBAR -->

<?php $this->start('endjs'); ?>
  </div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab2">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/review'); ?>
        </div>
      </div>
    </div>


    <div class="tab-pane" id="tab3">
        <div class="marketing">
             <div class="row-fluid">
                <div class="span12">
                   <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
                   <h3 class="text-info" style="text-decoration: underline;">Manager Reviews</h3>
                </div>
             </div>
              <hr class="soften" style="margin: 10px 0px;">
        </div>
        <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no'];?></p>
        <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title'];?></p>
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-success">Manager Review History
              <?php
                echo $this->Html->link(__('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
                  array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
                  array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
                ?>
            </h4>
            <?php if (empty($managerReviewComments)) { ?>
              <div class="alert alert-info" style="margin-top: 10px;">
                No manager reviews available yet.
              </div>
            <?php } else { ?>
              <table class="table table-bordered table-striped table-condensed" style="margin-top: 10px;">
                <thead>
                  <tr>
                    <th style="width: 6%;">#</th>
                    <th style="width: 22%;">Recommendation</th>
                    <th style="width: 56%;">Comments</th>
                    <th style="width: 16%;">Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($managerReviewComments as $index => $review) { ?>
                    <tr>
                      <td><?php echo $index + 1; ?></td>
                      <td><?php echo $formatManagerReviewContent($review['recommendation']); ?></td>
                      <td><?php echo $formatManagerReviewContent($review['text']); ?></td>
                      <td><?php echo !empty($review['created']) ? date('d-m-Y H:i:s', strtotime($review['created'])) : '<span class="muted">-</span>'; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } ?>
          </div>
       </div>


        <ul id="reviewer_tab" class="nav nav-tabs">
       <?php
      //Reviews limited to ppb_comment already
      $var = Hash::extract($application, 'ManagerReview.{n}[type=ppb_comment]');
      $rid = null;
      if (!empty($var)) $rid = min($var);
      ?>
          
          <li class="active"><a href="#external_rev_comments" data-toggle="tab">PI Comments (<?php echo count($rid['ExternalComment']); ?>)</a></li>
          <?php if($redir !== 'applicant') { ?><li><a href="#internal_rev_comments" data-toggle="tab">Internal Comments (<?php echo count($latestManagerReviewInternal); ?>)</a></li> <?php } ?>
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
                            if (!empty($rid)) echo $this->element('comments/list_expandable', ['comments' => $rid['ExternalComment'], 'show' => false, 'category' => true]);
                   
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
                    if (!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['InternalComment'], 'show' => false]);
 
                    $rcas = Hash::extract($application, 'Review.{n}[type=reviewer_comment]');
                    if (!empty($rcas)) {
                      echo "<hr>";
                      echo "<h4 class='text-success' style='text-align: center; text-decoration: underline'>Assessment comments</h4>";
                      foreach ($rcas as $rca) {
                        echo $this->element('comments/list', ['comments' => $rca['InternalComment'], 'show' => false]);
                      }
                    }
                    //end
                    ?>
                        </div>
                        <div class="span4 lefty">
                        <?php
                    if (!empty($rid))  echo $this->element('comments/add_plain', [
                      'model' => [
                        'model_id' => $application['Application']['id'],
                        'foreign_key' => $rid['id'],
                        'model' => 'Review',
                        'category' => 'internal',
                        'message_type' => 'review_response',
                        'type' => 50,
                        'url' => 'add_internal_review_response'
                      ]
                    ])
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
$(function() {
  if ($.expander && $.expander.defaults) {
    $.expander.defaults.slicePoint = 170;
  }

  //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
  //from mcaz
  var $reviewerTabLinks = $('#reviewer_tab a');
  $reviewerTabLinks.off('.reviewerTabs');
  $reviewerTabLinks.on('click.reviewerTabs', function (e) {
      e.preventDefault();
      $(this).tab('show');
  });

  $reviewerTabLinks.on("shown.reviewerTabs shown.bs.tab.reviewerTabs", function (e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('reviewerAssessmentTab', id);
  });

  var assessmentTab = localStorage.getItem('reviewerAssessmentTab');
  if (assessmentTab != null && $reviewerTabLinks.filter('[href="' + assessmentTab + '"]').length) {
      $reviewerTabLinks.filter('[href="' + assessmentTab + '"]').tab('show');
  }

  if (location.hash && $reviewerTabLinks.filter('[href="' + location.hash + '"]').length) {
      $reviewerTabLinks.filter('[href="' + location.hash + '"]').tab('show');
  }

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

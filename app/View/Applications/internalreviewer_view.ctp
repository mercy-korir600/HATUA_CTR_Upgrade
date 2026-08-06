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

      $extractLinkedSourceReviewId = function ($title) {
        $title = trim((string) $title);
        if ($title !== '' && preg_match('/^internal_source_review:(\d+)$/', $title, $matches)) {
          return (int) $matches[1];
        }
        return 0;
      };

      $buildAnswerComparisonKey = function ($questionType, $questionNumber, $questionText) {
        $questionType = strtolower(trim((string) $questionType));
        $questionNumber = trim((string) $questionNumber);
        if ($questionNumber !== '' && is_numeric($questionNumber)) {
          $questionNumber = number_format((float) $questionNumber, 2, '.', '');
        }
        $questionText = strtolower(preg_replace('/\s+/', ' ', trim((string) $questionText)));
        return $questionType . '|' . $questionNumber . '|' . $questionText;
      };

      $extractAnswerText = function ($answer) {
        $questionType = !empty($answer['question_type']) ? strtolower(trim((string) $answer['question_type'])) : '';
        if ($questionType === 'workspace') {
          return trim((string) $answer['workspace']);
        }
        if ($questionType === 'comment') {
          return trim((string) $answer['comment']);
        }
        if ($questionType === 'yesno' || $questionType === 'text') {
          return trim((string) $answer['answer']);
        }

        foreach (array('answer', 'workspace', 'comment') as $field) {
          if (!empty($answer[$field]) && trim((string) $answer[$field]) !== '') {
            return trim((string) $answer[$field]);
          }
        }
        return '';
      };

      $normalizeAnswerComparisonText = function ($value) {
        $value = trim((string) $value);
        if ($value === '') {
          return '';
        }

        $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        $value = strtolower(strip_tags($value));
        $value = preg_replace('/\s+/', ' ', $value);
        return trim((string) $value);
      };

      $allMyReviews = (!empty($application['Review']) && is_array($application['Review']))
        ? $application['Review']
        : array();
      $nonLinkedMyReviews = array();
      foreach ($allMyReviews as $myReviewEntry) {
        $sourceReviewId = $extractLinkedSourceReviewId(!empty($myReviewEntry['title']) ? $myReviewEntry['title'] : '');
        if ($sourceReviewId <= 0) {
          $nonLinkedMyReviews[] = $myReviewEntry;
        }
      }
      $displayMyReviewCount = count($nonLinkedMyReviews);
    ?>
    <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
          <li><a href="#tab2" data-toggle="tab">My Reviews <small>(<?php echo (int) $displayMyReviewCount; ?>)</small></a></li>
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
          <?php
            $myLinkedResponseBySource = array();
            foreach ($allMyReviews as $myReview) {
              $sourceReviewId = $extractLinkedSourceReviewId(!empty($myReview['title']) ? $myReview['title'] : '');
              if ($sourceReviewId <= 0) {
                continue;
              }

              $answerMap = array();
              foreach ((array) $myReview['ReviewAnswer'] as $myAnswer) {
                $answerValue = $extractAnswerText($myAnswer);
                if ($answerValue === '') {
                  continue;
                }
                $comparisonKey = $buildAnswerComparisonKey(
                  !empty($myAnswer['question_type']) ? $myAnswer['question_type'] : '',
                  !empty($myAnswer['question_number']) ? $myAnswer['question_number'] : '',
                  !empty($myAnswer['question']) ? $myAnswer['question'] : ''
                );
                $answerMap[$comparisonKey] = $answerValue;
              }

              $currentReviewId = !empty($myReview['id']) ? (int) $myReview['id'] : 0;
              if ($currentReviewId <= 0) {
                continue;
              }
              $existingReviewId = !empty($myLinkedResponseBySource[$sourceReviewId]['id']) ? (int) $myLinkedResponseBySource[$sourceReviewId]['id'] : 0;
              if ($existingReviewId >= $currentReviewId) {
                continue;
              }

              $linkedReviewerName = '2nd Reviewer';
              if (!empty($myReview['User']['name'])) {
                $linkedReviewerName = $myReview['User']['name'];
              } elseif (!empty($myReview['User']['username'])) {
                $linkedReviewerName = $myReview['User']['username'];
              }

              $myLinkedResponseBySource[$sourceReviewId] = array(
                'id' => $currentReviewId,
                'status' => !empty($myReview['status']) ? (string) $myReview['status'] : '',
                'reviewer_name' => $linkedReviewerName,
                'answer_map' => $answerMap
              );
            }
          ?>
          <?php if (!empty($priorInternalFeedback)) { ?>
            <div class="alert alert-info">
              Previous internal reviewer feedback is shown below.
            </div>
            <style type="text/css">
              .internal-modal-legend {
                margin-top: 8px;
              }
              .internal-modal-legend-item {
                display: inline-block;
                margin-right: 16px;
                font-weight: 600;
              }
              .internal-modal-legend-swatch {
                display: inline-block;
                width: 12px;
                height: 12px;
                margin-right: 6px;
                vertical-align: middle;
                border-radius: 2px;
              }
              .internal-modal-legend-swatch-reviewer1 {
                background: #1d5fbf;
              }
              .internal-modal-legend-swatch-reviewer2 {
                background: #b30000;
              }
              .internal-modal-reviewer1 {
                border-left: 4px solid #1d5fbf;
                background: #eef5ff !important;
                color: #1d5fbf !important;
                padding: 8px;
                margin-bottom: 8px;
              }
              .internal-modal-reviewer2 {
                border-left: 4px solid #b30000;
                background: #fff1f1 !important;
                color: #b30000 !important;
                padding: 8px;
                margin-top: 8px;
              }
              .internal-modal-reviewer1 p,
              .internal-modal-reviewer2 p {
                margin: 0;
              }
              .internal-modal-reviewer1 *,
              .internal-modal-reviewer1 a {
                color: #1d5fbf !important;
              }
              .internal-modal-reviewer2 *,
              .internal-modal-reviewer2 a {
                color: #b30000 !important;
              }
            </style>
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
                      <?php if ($feedbackReviewId > 0 && !empty($feedback['Review']['assessment_type'])) { ?>
                        <?php
                          echo '&nbsp;';
                          echo $this->Html->link(
                            'Respond',
                            array(
                              'internalreviewer' => true,
                              'controller' => 'reviews',
                              'action' => 'add',
                              $application['Application']['id'],
                              trim((string) $feedback['Review']['assessment_type']),
                              'source_review' => $feedbackReviewId
                            ),
                            array('class' => 'btn btn-mini btn-warning')
                          );
                        ?>
                      <?php } ?>
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
                $selectedAssessmentTypeRaw = !empty($feedback['Review']['assessment_type']) ? trim((string) $feedback['Review']['assessment_type']) : '';
                $selectedAssessmentType = ($selectedAssessmentTypeRaw !== '') ? ucfirst($selectedAssessmentTypeRaw) : 'Assessment';
                $viewModalId = 'priorInternalViewModal' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $assessmentTabId = 'priorInternalViewAssessmentTab' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $commentsTabId = 'priorInternalViewCommentsTab' . $selectedReviewId . '_' . (int) $feedbackIndex;
                $modalReview = $feedback['Review'];
                $modalReview['ReviewAnswer'] = !empty($feedback['ReviewAnswer']) ? $feedback['ReviewAnswer'] : array();
                $modalComments = !empty($feedback['InternalComment']) ? $feedback['InternalComment'] : array();
                $sourceReviewerName = 'Reviewer 1';
                if (!empty($feedback['User']['name'])) {
                  $sourceReviewerName = $feedback['User']['name'];
                } elseif (!empty($feedback['User']['username'])) {
                  $sourceReviewerName = $feedback['User']['username'];
                } elseif (!empty($feedback['Review']['user_id'])) {
                  $sourceReviewerName = 'Reviewer #' . (int) $feedback['Review']['user_id'];
                }
                $linkedReviewDetails = !empty($myLinkedResponseBySource[$selectedReviewId]) ? $myLinkedResponseBySource[$selectedReviewId] : array();
                $linkedReviewId = !empty($linkedReviewDetails['id']) ? (int) $linkedReviewDetails['id'] : 0;
                $linkedAnswerMap = !empty($linkedReviewDetails['answer_map']) ? (array) $linkedReviewDetails['answer_map'] : array();
                $linkedReviewerName = !empty($linkedReviewDetails['reviewer_name']) ? $linkedReviewDetails['reviewer_name'] : '2nd Reviewer';
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
                      <?php if ($selectedReviewId > 0 && $selectedAssessmentTypeRaw !== '') { ?>
                        <div class="alert alert-info" style="margin-bottom: 10px;">
                          <?php
                            echo $this->Html->link(
                              '<i class="icon-edit"></i> Create/Edit My Linked Response',
                              array(
                                'internalreviewer' => true,
                                'controller' => 'reviews',
                                'action' => 'add',
                                $application['Application']['id'],
                                $selectedAssessmentTypeRaw,
                                'source_review' => $selectedReviewId
                              ),
                              array('escape' => false, 'class' => 'btn btn-primary btn-small')
                            );
                          ?>
                          <span class="muted" style="margin-left: 8px;">
                          </span>
                        </div>
                      <?php } ?>
                      <?php if ($linkedReviewId > 0) { ?>
                        <div class="alert alert-success" style="margin-bottom: 10px;"> 
                          <div class="internal-modal-legend">
                            <span class="internal-modal-legend-item">
                              <span class="internal-modal-legend-swatch internal-modal-legend-swatch-reviewer1"></span>
                              <?php echo h($sourceReviewerName); ?>
                            </span>
                            <span class="internal-modal-legend-item">
                              <span class="internal-modal-legend-swatch internal-modal-legend-swatch-reviewer2"></span>
                              <?php echo h($linkedReviewerName); ?>
                            </span>
                          </div>
                          <small class="muted"></small>
                        </div>
                      <?php } ?>
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
                            <?php
                              $questionType = !empty($answer['question_type']) ? strtolower(trim((string) $answer['question_type'])) : '';
                              $reviewerOneAnswer = $extractAnswerText($answer);
                              $comparisonKey = $buildAnswerComparisonKey(
                                $questionType,
                                !empty($answer['question_number']) ? $answer['question_number'] : '',
                                !empty($answer['question']) ? $answer['question'] : ''
                              );
                              $reviewerTwoAnswer = !empty($linkedAnswerMap[$comparisonKey]) ? trim((string) $linkedAnswerMap[$comparisonKey]) : '';
                              $hasReviewerOneAnswer = ($reviewerOneAnswer !== '');
                              $hasReviewerTwoAnswer = ($reviewerTwoAnswer !== '');
                              $sameAnswer = false;
                              if ($hasReviewerOneAnswer && $hasReviewerTwoAnswer) {
                                $sameAnswer = (
                                  $normalizeAnswerComparisonText($reviewerOneAnswer) ===
                                  $normalizeAnswerComparisonText($reviewerTwoAnswer)
                                );
                              }
                            ?>
                            <?php if ($questionType == 'label') { ?>
                              <tr class="success">
                                <td colspan="2"><strong><?php echo h($answer['question']); ?></strong></td>
                              </tr>
                            <?php } else { ?>
                              <tr>
                                <td><?php echo h($answer['question']); ?></td>
                                <td>
                                  <?php if ($hasReviewerOneAnswer && $hasReviewerTwoAnswer && !$sameAnswer) { ?>
                                    <div class="internal-modal-reviewer1">
                                      <?php echo $formatFeedbackContent($reviewerOneAnswer); ?>
                                    </div>
                                    <div class="internal-modal-reviewer2">
                                      <?php echo $formatFeedbackContent($reviewerTwoAnswer); ?>
                                    </div>
                                  <?php } elseif ($hasReviewerOneAnswer && $hasReviewerTwoAnswer && $sameAnswer) { ?>
                                    <?php echo $formatFeedbackContent($reviewerOneAnswer); ?>
                                  <?php } elseif ($hasReviewerOneAnswer && $linkedReviewId > 0) { ?>
                                    <div class="internal-modal-reviewer1">
                                      <?php echo $formatFeedbackContent($reviewerOneAnswer); ?>
                                    </div>
                                  <?php } elseif ($hasReviewerTwoAnswer && $linkedReviewId > 0) { ?>
                                    <div class="internal-modal-reviewer2">
                                      <?php echo $formatFeedbackContent($reviewerTwoAnswer); ?>
                                    </div>
                                  <?php } elseif ($hasReviewerOneAnswer) { ?>
                                    <?php echo $formatFeedbackContent($reviewerOneAnswer); ?>
                                  <?php } elseif ($hasReviewerTwoAnswer) { ?>
                                    <?php echo $formatFeedbackContent($reviewerTwoAnswer); ?>
                                  <?php } else { ?>
                                    <span class="muted">No response provided.</span>
                                  <?php } ?>
                                </td>
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
          <?php
            $priorInternalFeedbackLookup = array();
            if (!empty($priorInternalFeedback) && is_array($priorInternalFeedback)) {
              foreach ($priorInternalFeedback as $feedbackEntry) {
                $feedbackReviewId = !empty($feedbackEntry['Review']['id']) ? (int) $feedbackEntry['Review']['id'] : 0;
                if ($feedbackReviewId > 0) {
                  $priorInternalFeedbackLookup[$feedbackReviewId] = $feedbackEntry;
                }
              }
            }
            echo $this->element('application/internal_review', array(
              'priorInternalFeedbackLookup' => $priorInternalFeedbackLookup,
              'reviewList' => $nonLinkedMyReviews,
              'allReviewList' => $allMyReviews
            ));
          ?>
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
                            // if(!empty($rid)) 
                               echo $this->element('comments/add', [
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

  var $mainTabs = $('.tabbable.tabs-left > .nav.nav-tabs a[data-toggle="tab"]');
  $mainTabs.off('.internalMainTabs');
  $mainTabs.on('click.internalMainTabs', function (e) {
      e.preventDefault();
      $(this).tab('show');
  });

  $mainTabs.on("shown.internalMainTabs shown.bs.tab.internalMainTabs", function (e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('internalReviewerMainTab', id);
  });

  var defaultMainTab = null;
  <?php if (isset($this->params['named']['rreview_view'])) { ?>
    defaultMainTab = '#tab2';
  <?php } ?>

  if (location.hash && /^#rreview_/.test(location.hash)) {
      defaultMainTab = '#tab2';
  }

  if (defaultMainTab && $mainTabs.filter('[href="' + defaultMainTab + '"]').length) {
      $mainTabs.filter('[href="' + defaultMainTab + '"]').tab('show');
  } else {
      var savedMainTab = localStorage.getItem('internalReviewerMainTab');
      if (savedMainTab && $mainTabs.filter('[href="' + savedMainTab + '"]').length) {
          $mainTabs.filter('[href="' + savedMainTab + '"]').tab('show');
      }
  }

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

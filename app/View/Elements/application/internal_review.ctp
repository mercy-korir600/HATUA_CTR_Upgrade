<?php
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));

$formatReviewSummary = function ($content) {
  $content = trim((string) $content);
  if ($content === '') {
    return '<span class="muted"></span>';
  }

  if (preg_match('/<\s*(p|ul|ol|li|br|div|strong|em|span|h[1-6]|table|blockquote)\b/i', $content)) {
    return $content;
  }

  $lines = preg_split('/\r\n|\r|\n/', $content);
  $lines = array_values(array_filter(array_map('trim', $lines), 'strlen'));
  if (count($lines) > 1) {
    return '<ul><li>' . implode('</li><li>', array_map('h', $lines)) . '</li></ul>';
  }

  return '<p>' . h($content) . '</p>';
};

$formatReviewComments = function ($comments) {
  if (empty($comments)) {
    return '<span class="muted"></span>';
  }

  $items = array();
  foreach ((array) $comments as $comment) {
    $subject = !empty($comment['subject']) ? trim((string) $comment['subject']) : '';
    $content = !empty($comment['content']) ? trim((string) $comment['content']) : '';
    $sender = !empty($comment['sender']) ? trim((string) $comment['sender']) : '';

    $segments = array();
    if ($subject !== '') {
      $segments[] = '<strong>' . h($subject) . '</strong>';
    }
    if ($content !== '') {
      $segments[] = nl2br(h($content));
    }
    if ($sender !== '') {
      $segments[] = '<small class="muted">By: ' . h($sender) . '</small>';
    }

    if (!empty($segments)) {
      $items[] = '<div style="margin-bottom: 8px;">' . implode('<br>', $segments) . '</div>';
    }
  }

  if (empty($items)) {
    return '<span class="muted"></span>';
  }

  return implode('', $items);
};

$priorInternalFeedbackLookup = (!empty($priorInternalFeedbackLookup) && is_array($priorInternalFeedbackLookup))
  ? $priorInternalFeedbackLookup
  : array();

$extractLinkedSourceReviewId = function ($title) {
  $title = trim((string) $title);
  if ($title !== '' && preg_match('/^internal_source_review:(\d+)$/', $title, $matches)) {
    return (int) $matches[1];
  }
  return 0;
};

$buildComparisonKey = function ($questionType, $questionNumber, $questionText) {
  $questionType = strtolower(trim((string) $questionType));
  $questionNumber = trim((string) $questionNumber);
  if ($questionNumber !== '' && is_numeric($questionNumber)) {
    $questionNumber = number_format((float) $questionNumber, 2, '.', '');
  }
  $questionText = strtolower(preg_replace('/\s+/', ' ', trim((string) $questionText)));
  return $questionType . '|' . $questionNumber . '|' . $questionText;
};

$extractAnswerValue = function ($answer) {
  foreach (array('answer', 'workspace', 'comment') as $field) {
    if (!empty($answer[$field]) && trim((string) $answer[$field]) !== '') {
      return trim((string) $answer[$field]);
    }
  }
  return '';
};

$allReviewList = (!empty($allReviewList) && is_array($allReviewList))
  ? array_values($allReviewList)
  : array_values((!empty($application['Review']) && is_array($application['Review'])) ? $application['Review'] : array());

$reviewList = (!empty($reviewList) && is_array($reviewList))
  ? array_values($reviewList)
  : $allReviewList;

$linkedComparisonBySource = array();
foreach ($allReviewList as $reviewEntry) {
  $reviewId = !empty($reviewEntry['id']) ? (int) $reviewEntry['id'] : 0;
  $reviewType = !empty($reviewEntry['type']) ? trim((string) $reviewEntry['type']) : '';
  $sourceReviewId = $extractLinkedSourceReviewId(!empty($reviewEntry['title']) ? $reviewEntry['title'] : '');

  if ($reviewType !== 'reviewer_comment' || $sourceReviewId <= 0) {
    continue;
  }

  $answerMap = array();
  foreach ((array) $reviewEntry['ReviewAnswer'] as $answer) {
    $answerValue = $extractAnswerValue($answer);
    if ($answerValue === '') {
      continue;
    }
    $comparisonKey = $buildComparisonKey(
      !empty($answer['question_type']) ? $answer['question_type'] : '',
      !empty($answer['question_number']) ? $answer['question_number'] : '',
      !empty($answer['question']) ? $answer['question'] : ''
    );
    $answerMap[$comparisonKey] = $answerValue;
  }

  $linkedReviewerName = '2nd Reviewer';
  if (!empty($reviewEntry['User']['name'])) {
    $linkedReviewerName = $reviewEntry['User']['name'];
  } elseif (!empty($reviewEntry['User']['username'])) {
    $linkedReviewerName = $reviewEntry['User']['username'];
  }

  $existingReviewId = !empty($linkedComparisonBySource[$sourceReviewId]['id'])
    ? (int) $linkedComparisonBySource[$sourceReviewId]['id']
    : 0;
  if ($reviewId > $existingReviewId) {
    $linkedComparisonBySource[$sourceReviewId] = array(
      'id' => $reviewId,
      'reviewer_name' => $linkedReviewerName,
      'answer_map' => $answerMap
    );
  }
}

$filteredReviewList = array();
foreach ($reviewList as $reviewEntry) {
  $sourceReviewId = $extractLinkedSourceReviewId(!empty($reviewEntry['title']) ? $reviewEntry['title'] : '');
  if ($sourceReviewId > 0) {
    continue;
  }
  $filteredReviewList[] = $reviewEntry;
}
$reviewList = $filteredReviewList;

$buildComparisonPayloadFromSource = function ($sourceReviewId) use ($priorInternalFeedbackLookup, $buildComparisonKey) {
  $payload = array(
    'map' => array(),
    'reviewer_name' => 'Reviewer 1',
    'source_review_id' => 0
  );

  if ($sourceReviewId <= 0 || empty($priorInternalFeedbackLookup[$sourceReviewId])) {
    return $payload;
  }

  $feedback = $priorInternalFeedbackLookup[$sourceReviewId];
  $payload['source_review_id'] = (int) $sourceReviewId;

  if (!empty($feedback['User']['name'])) {
    $payload['reviewer_name'] = $feedback['User']['name'];
  } elseif (!empty($feedback['User']['username'])) {
    $payload['reviewer_name'] = $feedback['User']['username'];
  }

  foreach ((array) $feedback['ReviewAnswer'] as $answer) {
    $value = '';
    if (trim((string) $answer['answer']) !== '') {
      $value = trim((string) $answer['answer']);
    } elseif (trim((string) $answer['workspace']) !== '') {
      $value = trim((string) $answer['workspace']);
    } elseif (trim((string) $answer['comment']) !== '') {
      $value = trim((string) $answer['comment']);
    }
    if ($value === '') {
      continue;
    }

    $comparisonKey = $buildComparisonKey($answer['question_type'], $answer['question_number'], $answer['question']);
    $payload['map'][$comparisonKey] = $value;
  }

  return $payload;
};

$buildComparisonPayloadForReview = function ($reviewEntry) use (
  $extractLinkedSourceReviewId,
  $buildComparisonPayloadFromSource,
  $linkedComparisonBySource
) {
  $sourceReviewId = $extractLinkedSourceReviewId(!empty($reviewEntry['title']) ? $reviewEntry['title'] : '');
  if ($sourceReviewId > 0) {
    return $buildComparisonPayloadFromSource($sourceReviewId);
  }

  $reviewId = !empty($reviewEntry['id']) ? (int) $reviewEntry['id'] : 0;
  if ($reviewId > 0 && !empty($linkedComparisonBySource[$reviewId])) {
    $linked = $linkedComparisonBySource[$reviewId];
    return array(
      'map' => !empty($linked['answer_map']) ? (array) $linked['answer_map'] : array(),
      'reviewer_name' => !empty($linked['reviewer_name']) ? (string) $linked['reviewer_name'] : '2nd Reviewer',
      'source_review_id' => $reviewId
    );
  }

  return array(
    'map' => array(),
    'reviewer_name' => 'Reviewer 1',
    'source_review_id' => 0
  );
};
?>
<div class="marketing">
  <div class="row-fluid">
    <div class="span12">
      <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
    </div>
  </div>
  <hr class="soften" style="margin: 10px 0px;">
</div>

 <?php                                                                                                                           
        $myAssessments = array();                                                                                                   
        $sourceReviewList = !empty($allReviewList) ? $allReviewList : (!empty($application['Review']) ? $application['Review'] :    
  array());                                                                                                                         
        foreach ($sourceReviewList as $rev) {                                                                                       
          if (!empty($rev['assessment_type']) && $rev['type'] === 'reviewer_comment') {                                             
            $t = $rev['assessment_type'];                                                                                           
            if (!isset($myAssessments[$t]) || $rev['status'] === 'Unsubmitted') {                                                   
              $myAssessments[$t] = $rev;                                                                                            
            }                                                                                                                       
          }                                                                                                                         
        }                                                                                                                           
                                                                                                                                    
        $assessmentButtons = array(                                                                                                 
          'clinical' => array(                                                                                                      
            'label' => 'Clinical Assessment',                                                                                       
            'icon'  => 'icon-stethoscope',                                                                                          
            'class' => 'btn-primary'                                                                                                
          ),                                                                                                                        
          'non-clinical' => array(                                                                                                  
            'label' => 'Non-Clinical Assessment',                                                                                   
            'icon'  => 'icon-tint',                                                                                                 
            'class' => 'btn-success'                                                                                                
          ),                                                                                                                        
          'quality' => array(                                                                                                       
            'label' => 'Quality Assessment',                                                                                        
            'icon'  => 'icon-medkit',                                                                                               
            'class' => 'btn-info'                                                                                                   
          ),                                                                                                                        
          'statistical' => array(                                                                                                   
            'label' => 'Statistical Assessment',                                                                                    
            'icon'  => 'icon-list-ol',                                                                                              
            'class' => 'btn-warning'                                                                                                
          )                                                                                                                         
        );                                                                                                                          
        ?>                                                                                                                          
                                                                                                                                    
        <div class="row-fluid">                                                                                                     
          <?php foreach ($assessmentButtons as $typeKey => $btnConfig): ?>                                                          
            <div class="span3">                                                                                                     
              <?php                                                                                                                 
              if (isset($myAssessments[$typeKey])) {                                                                                
                $existing = $myAssessments[$typeKey];                                                                               
                if ($existing['status'] === 'Unsubmitted') {                                                                        
                  echo $this->Html->link(                                                                                           
                    __('<i class="icon-edit"></i> Edit %s ', $btnConfig['label']),    
                    array('internalreviewer' => true, 'action' => 'view', $application['Application']['id'], 'rreview_view' =>      
  $existing['id']),                                                                                                                 
                    array('escape' => false, 'class' => 'btn ' . $btnConfig['class'] . ' btn-block')                                
                  );                                                                                                                
                } else {                                                                                                            
                  echo $this->Html->link(                                                                                           
                    __('<i class="icon-check"></i> View %s', $btnConfig['label']),
                    array('internalreviewer' => true, 'action' => 'view', $application['Application']['id'], 'rreview_view' =>      
  $existing['id']),                                                                                                                 
                    array('escape' => false, 'class' => 'btn btn-block')                                                            
                  );                                                                                                                
                }                                                                                                                   
              } else {                                                                                                              
                echo $this->Html->link(                                                                                             
                  __('<i class="%s"></i> Add %s', $btnConfig['icon'], $btnConfig['label']),                                         
                  array('internalreviewer' => true, 'controller' => 'reviews', 'action' => 'add', $application['Application']['id'],
  $typeKey),
                  array('escape' => false, 'class' => 'btn ' . $btnConfig['class'] . ' btn-block btn-add-assessment')
                );
              }
              ?>
            </div>
          <?php endforeach; ?>
        </div>                       
<br>

<br>
<div class="row-fluid">
  <div class="span12">
    <table class="table table-bordered table-striped table-condensed" style="margin-bottom: 1px;">
      <thead>
        <tr>
          <th style="width: 6%;">#</th>
          <th style="width: 20%;">Recommendation</th>
          <th style="width: 30%;">Comments</th>
          <th style="width: 14%;">Status &amp; Type</th>
          <th style="width: 12%;">User</th>
          <th style="width: 12%;">Created</th>
          <th style="width: 6%;"><?php echo __('Actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reviewList as $akey => $rreview) { ?>
          <tr>
            <td><?php echo $akey + 1; ?></td>
            <td>
              <?php
              if ($rreview['type'] == 'request') {
                echo 'Assigned: ' . $rreview['accepted'] . '<br/>';
              }
              echo $formatReviewSummary($rreview['recommendation']);
              if (!empty($rreview['summary'])) {
              ?>
                <button type="button" class="btn btn-small btn-info" data-toggle="modal" data-target="#myModal_<?php echo $rreview['id']; ?>">
                  View Summary
                </button>

                <div class="modal hide fade" id="myModal_<?php echo $rreview['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Clinical Summary</h4>
                  </div>
                  <div class="modal-body">
                    <?php echo $formatReviewSummary($rreview['summary']); ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Close</button>
                  </div>
                </div>
              <?php } ?>
            </td>
            <td><?php echo $formatReviewComments($rreview['InternalComment']); ?></td>
            <td>
              <?php
                echo h($rreview['status']) . "<br>" . h($rreview['type']);
                $currentReviewId = !empty($rreview['id']) ? (int) $rreview['id'] : 0;
                if ($currentReviewId > 0 && !empty($linkedComparisonBySource[$currentReviewId]['id'])) {
                  echo '<br><small class="text-info">Linked response: #' . (int) $linkedComparisonBySource[$currentReviewId]['id'] . '</small>';
                }
              ?>
            </td>
            <td>
              <?php
                if (!empty($rreview['User']['name'])) {
                  echo h($rreview['User']['name']);
                } elseif (!empty($rreview['User']['username'])) {
                  echo h($rreview['User']['username']);
                } elseif (!empty($rreview['user_id'])) {
                  echo 'Reviewer #' . (int) $rreview['user_id'];
                } else {
                  echo 'Reviewer';
                }
              ?>
            </td>
            <td><?php echo $rreview['created'] ?></td>
            <td>
              <?php
              if ($rreview['type'] != 'request' && $rreview['type'] != 'ppb_comment') {
                if ($rreview['status'] == 'Unsubmitted') {
                  echo $this->Html->link(
                    '<span class="label label-success"> Edit </span>',
                    array('internalreviewer' => true, 'action' => 'view', $application['Application']['id'], 'rreview_view' => $rreview['id']),
                    array('escape' => false)
                  );
                  echo "&nbsp;";
                } else {
                  echo $this->Html->link(
                    '<span class="label label-info"> View </span>',
                    array('internalreviewer' => true, 'action' => 'view', $application['Application']['id'], 'rreview_view' => $rreview['id']),
                    array('escape' => false)
                  );
                  echo "&nbsp;";
                }
              }
              ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<br>
<hr>

<?php
if (isset($this->params['named']['rreview_view'])) {
  $cid = $this->params['named']['rreview_view'];
}

if (isset($this->params['named']['rreview_view'])) {
  foreach ($allReviewList as $akey => $rreview) {
    if ($rreview['id'] == $cid) {
?>
      <ul id="rreview_tab" class="nav nav-tabs">
        <li class="active"><a href="#rreview_form" data-toggle="tab">Assessment Form</a></li>
        <li><a href="#rreview_summary" data-toggle="tab">Summary report</a></li>
        <li><a href="#rreview_comments" data-toggle="tab">Comments (<?php echo count($rreview['InternalComment']); ?>)</a></li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="rreview_form">
          <div style="position: relative; border-top: 1px solid #ddd;">
            <?php
            $comparisonPayload = $buildComparisonPayloadForReview($rreview);
            if ($rreview['status'] == 'Unsubmitted') {
              echo $this->element('/application/internal_review_edit', array(
                'rreview' => $rreview,
                'akey' => $akey,
                'comparisonReviewAnswerMap' => $comparisonPayload['map'],
                'comparisonReviewerName' => $comparisonPayload['reviewer_name'],
                'comparisonSourceReviewId' => $comparisonPayload['source_review_id']
              ));
            } else {
              echo $this->Html->link(
                __('<i class="icon-download-alt"></i> Download PDF'),
                array('internalreviewer' => true, 'controller' => 'reviews', 'ext' => 'pdf', 'action' => 'download_assessment', $rreview['id']),
                array('escape' => false, 'class' => 'btn btn-small btn-info topright')
              );
              echo $this->element('/application/internal_review_view', array(
                'rreview' => $rreview,
                'akey' => $akey,
                'comparisonReviewAnswerMap' => $comparisonPayload['map'],
                'comparisonReviewerName' => $comparisonPayload['reviewer_name'],
                'comparisonSourceReviewId' => $comparisonPayload['source_review_id']
              ));
            }
            ?>
          </div>
        </div>

        <div class="tab-pane" id="rreview_summary">
          <div style="position: relative; border-top: 1px solid #ddd;">
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download PDF'),
              array('internalreviewer' => true, 'controller' => 'reviews', 'ext' => 'pdf', 'action' => 'download_summary', $rreview['id']),
              array('escape' => false, 'class' => 'btn btn-small btn-info topright')
            );
            echo $this->element('/application/rreview_summary', array('rreview' => $rreview, 'akey' => $akey));
            ?>
          </div>
        </div>

        <div class="tab-pane" id="rreview_comments">
          <div class="row-fluid">
            <div class="span12">
              <br>
              <div class="amend-form">
                <ul id="rreview_comments_tab" class="nav nav-tabs">
                  <li class="active"><a href="#rreview_comment_list" data-toggle="tab">COMMENTS/QUERIES</a></li>
                  <li><a href="#rreview_comments_add" data-toggle="tab">Add Comment</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="rreview_comment_list">
                    <div class="row-fluid">
                      <div class="span12">
                        <?php echo $this->element('comments/list_expandable', ['comments' => $rreview['InternalComment'], 'category' => false]); ?>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="rreview_comments_add">
                    <div class="row-fluid">
                      <div class="span12">
                        <?php
                        echo $this->element('comments/add', [
                          'model' => [
                            'model_id' => $application['Application']['id'],
                            'foreign_key' => $rreview['id'],
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
            </div>
          </div>
        </div>
      </div>
<?php
    }
  }
}
?>

<script type="text/javascript">
  $(function() {
    if ($.expander && $.expander.defaults) {
      $.expander.defaults.slicePoint = 170;
    }

    function bindTabGroup(navSelector, storageKey) {
      var $links = $(navSelector).find('a[data-toggle="tab"]');
      if (!$links.length) {
        return;
      }

      $links.off('.persistTabs');
      $links.on('click.persistTabs', function(e) {
        e.preventDefault();
        $(this).tab('show');
      });

      $links.on('shown.persistTabs shown.bs.tab.persistTabs', function(e) {
        var id = $(e.target).attr('href');
        localStorage.setItem(storageKey, id);
      });

      var saved = localStorage.getItem(storageKey);
      if (saved && $links.filter('[href="' + saved + '"]').length) {
        $links.filter('[href="' + saved + '"]').tab('show');
      }

      if (location.hash && $links.filter('[href="' + location.hash + '"]').length) {
        $links.filter('[href="' + location.hash + '"]').tab('show');
      }
    }

    bindTabGroup('#rreview_tab', 'internalRreviewTab');
    bindTabGroup('#rreview_comments_tab', 'internalRreviewCommentTab');
  });
</script>

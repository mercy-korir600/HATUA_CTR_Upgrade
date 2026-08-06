<?php
$this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
$this->assign('Applications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));
$this->Html->script('invoice', array('inline' => false));
?>

<?php
echo $this->Session->flash();

$normalizeAmendmentKey = function ($value) {
  $normalized = strtolower(trim((string)$value));
  $normalized = preg_replace('/^\-+/', '', $normalized);
  $normalized = preg_replace('/^amd[\s_-]*/', '', $normalized);
  if ($normalized === '') {
    return '';
  }
  return $normalized;
};

$formatAmendmentLabel = function ($value) use ($normalizeAmendmentKey) {
  $normalized = $normalizeAmendmentKey($value);
  if ($normalized === '') {
    return 'Unknown Amendment';
  }
  return 'AMD-' . strtoupper($normalized);
};

$amendmentTimelineMap = array();

$ensureTimelineRecord = function ($yearRaw) use (&$amendmentTimelineMap, $normalizeAmendmentKey, $formatAmendmentLabel) {
  $yearKey = $normalizeAmendmentKey($yearRaw);
  if ($yearKey === '') {
    return '';
  }
  if (empty($amendmentTimelineMap[$yearKey])) {
    $amendmentTimelineMap[$yearKey] = array(
      'year_raw' => (string)$yearRaw,
      'label' => $formatAmendmentLabel($yearRaw),
      'sort_ts' => 0,
      'stages' => array(
        'created' => array('ts' => 0, 'date' => '', 'status' => 'Pending'),
        'submitted' => array('ts' => 0, 'date' => '', 'status' => 'Pending'),
        'review' => array('ts' => 0, 'date' => '', 'status' => 'Pending'),
        'approved' => array('ts' => 0, 'date' => '', 'status' => 'Pending')
      )
    );
  }
  return $yearKey;
};

foreach ((array)$application['AmendmentChecklist'] as $checkItem) {
  $yearKey = $ensureTimelineRecord(!empty($checkItem['year']) ? $checkItem['year'] : '');
  if ($yearKey === '') {
    continue;
  }
  $createdTs = !empty($checkItem['created']) ? strtotime($checkItem['created']) : false;
  if ($createdTs) {
    if (empty($amendmentTimelineMap[$yearKey]['stages']['created']['ts']) || $createdTs < $amendmentTimelineMap[$yearKey]['stages']['created']['ts']) {
      $amendmentTimelineMap[$yearKey]['stages']['created'] = array(
        'ts' => $createdTs,
        'date' => date('d-M-Y H:i', $createdTs),
        'status' => 'Created'
      );
    }
    if (empty($amendmentTimelineMap[$yearKey]['stages']['submitted']['ts']) || $createdTs > $amendmentTimelineMap[$yearKey]['stages']['submitted']['ts']) {
      $amendmentTimelineMap[$yearKey]['stages']['submitted'] = array(
        'ts' => $createdTs,
        'date' => date('d-M-Y H:i', $createdTs),
        'status' => 'Submitted'
      );
    }
    if ($createdTs > $amendmentTimelineMap[$yearKey]['sort_ts']) {
      $amendmentTimelineMap[$yearKey]['sort_ts'] = $createdTs;
    }
  }
}

foreach ((array)$application['AmendmentApprovalSummary'] as $summaryItem) {
  $yearKey = $ensureTimelineRecord(!empty($summaryItem['amendment']) ? $summaryItem['amendment'] : '');
  if ($yearKey === '') {
    continue;
  }
  $reviewDate = !empty($summaryItem['created']) ? $summaryItem['created'] : (!empty($summaryItem['approval_date']) ? $summaryItem['approval_date'] : '');
  $reviewTs = $reviewDate !== '' ? strtotime($reviewDate) : false;
  if ($reviewTs) {
    if (empty($amendmentTimelineMap[$yearKey]['stages']['review']['ts']) || $reviewTs < $amendmentTimelineMap[$yearKey]['stages']['review']['ts']) {
      $amendmentTimelineMap[$yearKey]['stages']['review'] = array(
        'ts' => $reviewTs,
        'date' => date('d-M-Y H:i', $reviewTs),
        'status' => 'Review Started'
      );
    }
    if ($reviewTs > $amendmentTimelineMap[$yearKey]['sort_ts']) {
      $amendmentTimelineMap[$yearKey]['sort_ts'] = $reviewTs;
    }
  }
}

foreach ((array)$application['AmendmentApproval'] as $decisionItem) {
  $yearKey = $ensureTimelineRecord(!empty($decisionItem['amendment']) ? $decisionItem['amendment'] : '');
  if ($yearKey === '') {
    continue;
  }
  $decisionStatus = strtolower(trim((string)$decisionItem['status']));
  $decisionDate = !empty($decisionItem['created']) ? $decisionItem['created'] : (!empty($decisionItem['approval_date']) ? $decisionItem['approval_date'] : '');
  $decisionTs = $decisionDate !== '' ? strtotime($decisionDate) : false;
  if (!$decisionTs) {
    continue;
  }

  if ($decisionStatus !== 'summary') {
    if (empty($amendmentTimelineMap[$yearKey]['stages']['review']['ts']) || $decisionTs < $amendmentTimelineMap[$yearKey]['stages']['review']['ts']) {
      $amendmentTimelineMap[$yearKey]['stages']['review'] = array(
        'ts' => $decisionTs,
        'date' => date('d-M-Y H:i', $decisionTs),
        'status' => 'Reviewed'
      );
    }
  }

  if ($decisionStatus === 'approved' || $decisionStatus === 'rejected') {
    if (empty($amendmentTimelineMap[$yearKey]['stages']['approved']['ts']) || $decisionTs > $amendmentTimelineMap[$yearKey]['stages']['approved']['ts']) {
      $amendmentTimelineMap[$yearKey]['stages']['approved'] = array(
        'ts' => $decisionTs,
        'date' => date('d-M-Y H:i', $decisionTs),
        'status' => ucfirst($decisionStatus)
      );
    }
  }

  if ($decisionTs > $amendmentTimelineMap[$yearKey]['sort_ts']) {
    $amendmentTimelineMap[$yearKey]['sort_ts'] = $decisionTs;
  }
}

foreach ((array)$application['AmendmentLetter'] as $letterItem) {
  $yearKey = $ensureTimelineRecord(!empty($letterItem['status']) ? $letterItem['status'] : '');
  if ($yearKey === '') {
    continue;
  }
  $isSubmittedLetter = ((string)$letterItem['submitted'] === '1' || (int)$letterItem['submitted'] === 1);
  if (!$isSubmittedLetter) {
    continue;
  }
  $letterTs = !empty($letterItem['created']) ? strtotime($letterItem['created']) : false;
  if (!$letterTs) {
    continue;
  }
  if (empty($amendmentTimelineMap[$yearKey]['stages']['approved']['ts']) || $letterTs > $amendmentTimelineMap[$yearKey]['stages']['approved']['ts']) {
    $amendmentTimelineMap[$yearKey]['stages']['approved'] = array(
      'ts' => $letterTs,
      'date' => date('d-M-Y H:i', $letterTs),
      'status' => 'Approved Letter Issued'
    );
  }
  if ($letterTs > $amendmentTimelineMap[$yearKey]['sort_ts']) {
    $amendmentTimelineMap[$yearKey]['sort_ts'] = $letterTs;
  }
}

foreach ($amendmentTimelineMap as $yearKey => $timelineEntry) {
  if (!empty($timelineEntry['stages']['submitted']['ts'])) {
    continue;
  }
  if (!empty($timelineEntry['stages']['review']['ts'])) {
    $submittedTs = $timelineEntry['stages']['review']['ts'];
    $amendmentTimelineMap[$yearKey]['stages']['submitted'] = array(
      'ts' => $submittedTs,
      'date' => date('d-M-Y H:i', $submittedTs),
      'status' => 'Submitted'
    );
  } elseif (!empty($timelineEntry['stages']['approved']['ts'])) {
    $submittedTs = $timelineEntry['stages']['approved']['ts'];
    $amendmentTimelineMap[$yearKey]['stages']['submitted'] = array(
      'ts' => $submittedTs,
      'date' => date('d-M-Y H:i', $submittedTs),
      'status' => 'Submitted'
    );
  }
}

if (!empty($amendmentTimelineMap)) {
  uasort($amendmentTimelineMap, function ($left, $right) {
    $leftTs = !empty($left['sort_ts']) ? (int)$left['sort_ts'] : 0;
    $rightTs = !empty($right['sort_ts']) ? (int)$right['sort_ts'] : 0;
    if ($leftTs === $rightTs) {
      return strcmp((string)$right['label'], (string)$left['label']);
    }
    return ($leftTs < $rightTs) ? 1 : -1;
  });
}

$amendmentSectionOneMap = array();
if (!empty($application['Amendment']) && is_array($application['Amendment'])) {
  $amendmentRows = array_values($application['Amendment']);
  usort($amendmentRows, function ($left, $right) {
    $leftId = !empty($left['id']) ? (int)$left['id'] : 0;
    $rightId = !empty($right['id']) ? (int)$right['id'] : 0;
    if ($leftId === $rightId) {
      $leftCreated = !empty($left['created']) ? strtotime($left['created']) : 0;
      $rightCreated = !empty($right['created']) ? strtotime($right['created']) : 0;
      if ($leftCreated === $rightCreated) {
        return 0;
      }
      return ($leftCreated < $rightCreated) ? -1 : 1;
    }
    return ($leftId < $rightId) ? -1 : 1;
  });

  foreach ($amendmentRows as $index => $amendmentRow) {
    $sequence = $index + 1;
    $sequenceKey = $normalizeAmendmentKey('amd-' . $sequence);
    if ($sequenceKey === '') {
      continue;
    }

    $sectionOne = !empty($amendmentRow['Amend']) ? (array)$amendmentRow['Amend'] : array();
    $coverLetters = !empty($amendmentRow['CoverLetter']) && is_array($amendmentRow['CoverLetter']) ? $amendmentRow['CoverLetter'] : array();
    $coverFileName = '';
    if (!empty($coverLetters)) {
      $latestCover = end($coverLetters);
      $coverFileName = !empty($latestCover['basename']) ? trim((string)$latestCover['basename']) : '';
      reset($coverLetters);
    }

    $snapshot = array(
      'cover_letter' => !empty($sectionOne['cover_letter']) ? trim((string)$sectionOne['cover_letter']) : '',
      'summary' => !empty($sectionOne['summary']) ? trim((string)$sectionOne['summary']) : '',
      'reason' => !empty($sectionOne['reason']) ? trim((string)$sectionOne['reason']) : '',
      'objectives_impacts' => !empty($sectionOne['objectives_impacts']) ? trim((string)$sectionOne['objectives_impacts']) : '',
      'endpoints_impacts' => !empty($sectionOne['endpoints_impacts']) ? trim((string)$sectionOne['endpoints_impacts']) : '',
      'safety_impacts' => !empty($sectionOne['safety_impacts']) ? trim((string)$sectionOne['safety_impacts']) : '',
      'cover_file' => $coverFileName,
      'created' => !empty($amendmentRow['created']) ? date('d-M-Y H:i', strtotime($amendmentRow['created'])) : ''
    );
    $amendmentSectionOneMap[$sequenceKey] = $snapshot;

    $ecctRefKey = $normalizeAmendmentKey(!empty($amendmentRow['ecct_ref_number']) ? $amendmentRow['ecct_ref_number'] : '');
    if ($ecctRefKey !== '' && empty($amendmentSectionOneMap[$ecctRefKey])) {
      $amendmentSectionOneMap[$ecctRefKey] = $snapshot;
    }
  }
}

foreach ($amendmentTimelineMap as $yearKey => $timelineEntry) {
  $amendmentTimelineMap[$yearKey]['section_one'] = !empty($amendmentSectionOneMap[$yearKey]) ? $amendmentSectionOneMap[$yearKey] : array();
}

$amendmentStageOrder = array('created', 'submitted', 'review', 'approved');
$amendmentStageGapLabels = array(
  'created' => 'to Submitted',
  'submitted' => 'to Review',
  'review' => 'to Approval'
);
$amendmentCurrentStageDangerThresholds = array(
  'created' => 5,
  'submitted' => 10,
  'review' => 30
);
$timelineNowTs = time();
foreach ($amendmentTimelineMap as $yearKey => $timelineEntry) {
  foreach ($amendmentStageOrder as $stageIndex => $stageKey) {
    $stageTs = !empty($timelineEntry['stages'][$stageKey]['ts']) ? (int)$timelineEntry['stages'][$stageKey]['ts'] : 0;
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['color'] = 'default';
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_days'] = 0;
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_text'] = '';
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_color'] = 'default';

    if ($stageTs <= 0) {
      continue;
    }

    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['color'] = 'success';
    if (empty($amendmentStageGapLabels[$stageKey])) {
      continue;
    }

    $nextStageKey = !empty($amendmentStageOrder[$stageIndex + 1]) ? $amendmentStageOrder[$stageIndex + 1] : '';
    $nextStageTs = ($nextStageKey !== '' && !empty($timelineEntry['stages'][$nextStageKey]['ts'])) ? (int)$timelineEntry['stages'][$nextStageKey]['ts'] : 0;

    if ($nextStageTs > 0 && $nextStageTs >= $stageTs) {
      $gapDays = (int) floor(($nextStageTs - $stageTs) / 86400);
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_days'] = $gapDays;
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_text'] = $amendmentStageGapLabels[$stageKey];
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_color'] = 'info';
      continue;
    }

    $gapDays = (int) floor(max(0, $timelineNowTs - $stageTs) / 86400);
    $dangerThreshold = !empty($amendmentCurrentStageDangerThresholds[$stageKey]) ? (int)$amendmentCurrentStageDangerThresholds[$stageKey] : 10;
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_days'] = $gapDays;
    $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_text'] = 'current stage';

    if ($gapDays > $dangerThreshold) {
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['color'] = 'danger';
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_color'] = 'danger';
    } elseif ($gapDays > 0) {
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['color'] = 'warning';
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_color'] = 'warning';
    } else {
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['color'] = 'success';
      $amendmentTimelineMap[$yearKey]['stages'][$stageKey]['gap_color'] = 'success';
    }
  }
}

$normalizeTimelineColor = function ($color) {
  $normalized = strtolower(trim((string)$color));
  if ($normalized === 'danger') {
    $normalized = 'important';
  }
  $allowed = array('default', 'success', 'warning', 'info', 'inverse', 'important');
  if (!in_array($normalized, $allowed, true)) {
    return 'default';
  }
  return $normalized;
};

$resolveLabelClass = function ($prefix, $color) use ($normalizeTimelineColor) {
  $normalized = $normalizeTimelineColor($color);
  if ($normalized === 'default') {
    return $prefix;
  }
  return $prefix . ' ' . $prefix . '-' . $normalized;
};

$hasAmendmentTimeline = !empty($amendmentTimelineMap);

$renderTimelineStrip = function ($timelineEntry) use ($amendmentStageOrder, $resolveLabelClass) {
  $stageDisplayLabels = array(
    'created' => 'Amendment Creation',
    'submitted' => 'Submitted',
    'review' => 'Review',
    'approved' => 'Approval'
  );

  $output = '<div style="white-space:normal;">';
  foreach ($amendmentStageOrder as $stageKey) {
    $stage = !empty($timelineEntry['stages'][$stageKey]) ? (array)$timelineEntry['stages'][$stageKey] : array();
    $label = !empty($stageDisplayLabels[$stageKey]) ? $stageDisplayLabels[$stageKey] : ucfirst($stageKey);
    $color = !empty($stage['color']) ? $stage['color'] : 'default';
    $dateText = !empty($stage['date']) ? $stage['date'] : '-';
    $gapDays = !empty($stage['gap_days']) ? (int)$stage['gap_days'] : 0;

    $output .= '<span style="display:inline-block;margin:0 8px 8px 0;vertical-align:top;white-space:nowrap;">';
    $output .= '<span class="' . h($resolveLabelClass('label', $color)) . '">' . h($label) . '<br><small style="color:#f9ef9c;">' . h($dateText) . '</small></span>';
    $output .= '<span class="badge" style="margin-left:4px;">' . h($gapDays) . '</span>';
    $output .= '</span>';
  }
  $output .= '</div>';

  return $output;
};
?>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
    <li><a href="#tab17" data-toggle="tab">Screening</a></li>
    <li><a href="#amendments" data-toggle="tab">Amendments</a></li>
    <?php if ($hasAmendmentTimeline) { ?>
      <li><a href="#amendment_timelines" data-toggle="tab">Amendment Timelines <small>(<?php echo count($amendmentTimelineMap); ?>)</small></a></li>
    <?php } ?>
    <?php
    $count_reviews = 0;
    $count_internal_reviews = 0;
    $count_inspectors = 0;
    $count_comments = 0;
    $my_reviews = 0;
    $isLinkedInternalReviewCopy = function ($review) {
      if (empty($review['type']) || $review['type'] !== 'reviewer_comment') {
        return false;
      }
      $title = !empty($review['title']) ? trim((string)$review['title']) : '';
      return ($title !== '' && preg_match('/^internal_source_review:\d+$/', $title));
    };
    foreach ($application['ActiveInspector'] as $review) {
      $count_inspectors++;
    }
    foreach ($application['Review'] as $review) {
      if ($review['type'] == 'request' && $review['accepted'] != 'declined') {
        $count_reviews++;
      }
      if ($review['type'] == 'reviewer_comment' && !$isLinkedInternalReviewCopy($review)) {
        $count_comments++;
      }
      if ($review['type'] == 'ppb_comment') {
        $my_reviews++;
      }
    }

    foreach ($application['InternalReview'] as $review) {
      if ($review['type'] == 'request' && $review['accepted'] != 'declined') {
        $count_internal_reviews++;
      }
      if ($review['type'] == 'reviewer_comment' && !$isLinkedInternalReviewCopy($review)) {
        $count_comments++;
      }
      if ($review['type'] == 'ppb_comment') {
        $my_reviews++;
      }
    }
    $count_assigned_reviewers = $count_reviews + $count_internal_reviews;
    ?>
    <li><a href="#tab2" data-toggle="tab">Assigned Reviewers <small>(<?php echo $count_assigned_reviewers; ?>)</small></a></li>
    <li><a href="#tab3" data-toggle="tab">Reviewer Comments <small>(<?php echo $count_comments; ?>)</small></a></li>
    <li><a href="#tab4" data-toggle="tab">My Reviews <small>(<?php echo $my_reviews; ?>)</small></a></li>
    <li><a href="#tab5" data-toggle="tab">Approve / Reject <small>(<?php
                                                                    if ($application['Application']['approved'] == 2) echo 'Approved';
                                                                    elseif ($application['Application']['approved'] == 1) echo 'Rejected';
                                                                    ?>)</small></a>
    </li>
    <li><a href="#tab6" data-toggle="tab">Site Inspections (<?php echo count($application['SiteInspection']) ?>)</a></li>
    <li><a href="#tab7" data-toggle="tab">SAE/SUSAR (<?php echo count($application['Sae']) ?>)</a></li>
    <li><a href="#tab15" data-toggle="tab">CIOMS E2B (<?php echo count($application['Ciom']) ?>)</a></li>
    <li><a href="#tab13" data-toggle="tab">Protocol Deviations (<?php echo count($application['Deviation']) ?>)</a></li>
    <li><a href="#tab8" data-toggle="tab" style="color: #52A652;">Annual Approval Checklist</a></li>
    <li><a href="#tab10" data-toggle="tab" style="color: #52A652;">Annual Participants Flow</a></li>
    <li><a href="#tab14" data-toggle="tab" style="color: #52A652;">Manufacturing Site(s)</a></li>
    <!-- <li><a href="#tab11" data-toggle="tab" style="color: #52A652;">Study Budget</a></li> -->
    <li><a href="#tab12" data-toggle="tab" style="color: #5e3ed3;">Approval Letters</a></li>
    <?php if ($application['Application']['approved'] == 2) { ?>
      <li><a href="#tab9" data-toggle="tab" style="color: #52A652;">Final Study Report</a></li>
    <?php } ?>
    <?php if (!empty($application['Application']['ecitizen_invoice'])) { ?>
      <li><a href="#ecitizen_invoice" data-toggle="tab" style="color: #52A652;">eCitizen Invoice</a></li>
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

            // if ($application['Application']['approved'] == 2) {
            echo "<hr>";
            echo $this->Html->link(
              __('<i class="icon-skype"></i> Site Inspection'),
              array('controller' => 'site_inspections', 'action' => 'add', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn btn-info')
            );

            echo "<hr>"; 
            ?>
               
          </div>
        <?php
        }
        ?>
      </div>
      <?php $this->end();  ?>

      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrab tab1 -->

    <div class="tab-pane" id="tab17">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h3 class="text-info">Screening for completeness</h3>
            <?php
            $eid = null;
            $var = Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]');
            if (!empty($var)) $eid = min($var);

            if (!empty($eid) && empty($eid['end_date'])) echo $this->Form->postLink(
              __('<button class="btn btn-primary active" type="button">Complete screening</button>'),
              array('controller' => 'application_stages', 'action' => 'complete_screening', $eid['id']),
              array('escape' => false),
              __('Are you sure you want to complete screening ?')
            );
            else echo '<button class="btn btn-success  disabled" type="button">Screening completed!!</button>';
            ?>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <div class="row-fluid">

        <div class="span12">
          <br>

          <div class="amend-form">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#list">FEEDBACK/QUERIES</a></li>
              <li><a href="#comm">Add Comment</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="feedback_list">
                <div class="row-fluid">
                  <div class="span12">
                    <?php if (!empty($eid)) echo $this->element('comments/list_expandable', ['comments' => $eid['Comment'], 'category' => true]) ?>
                  </div>
                </div>
              </div>

              <div class="tab-pane active" id="comm">
                <div class="row-fluid">
                  <div class="span12">
                    <?php
                    if (!empty($eid))   echo $this->element('comments/add_editor', [
                      'model' => [
                        'model_id' => $application['Application']['id'],
                        'foreign_key' => $eid['id'],
                        'model' => 'ApplicationStage',
                        'category' => 'external',
                        'url' => 'add_screening_query',
                        'type' => 50
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

    <div class="tab-pane" id="tab2">
      <p style="text-align: center;"><strong>ECCT Reference Number: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <hr class="soften" style="margin: 10px 0px;">
      <div class="row-fluid">
       
        <div class="span6">
        <h4 class="text-success">Assigned ECCT Reviewers (<?php echo $count_reviews; ?>)</h4>
        <hr>
          <?php
          echo $this->Form->create(
            'Review',
            array('url' => array('controller' => 'reviews', 'action' => 'assign', $application['Application']['id']))
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
                  if ($response['conflict'] != '') {
                    echo '<p>Has Conflict of interest? <b>' . $response['conflict'] . '</b> </p>';
                  }
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'accepted') {
                  $responded = true;
                  echo '<p class="text-success"><i class="icon-check"> </i> ' . $user . ' <small class="muted">(Accepts)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                  // echo '<p><i class="icon-minus"> </i> '.$response['text'].'</p>';
                  echo '<p>Has Conflict of interest? ' . $response['conflict'] . ' </p>';
                  echo '<p><i class="icon-time"> </i> Date Assigned: ' . date('d-m-Y H:i:s', strtotime($response['created'])) . '</p>';
                  $assignedBy = !empty($response['assigned_by_name']) ? $response['assigned_by_name'] : 'N/A';
                  echo '<p><i class="icon-user"> </i> Assigned By: ' . h($assignedBy) . '</p>';
                  // ask if the user has showned conflict of interest with an link to revoke access with a confirmation dialog
                  echo $this->Html->link(
                    __('<small class="muted"> Shown Interest? Revoke Access</small>'),
                    array('controller' => 'reviews', 'action' => 'revoke', $response['id'], $application['Application']['id']),
                    array('escape' => false),
                    __('Are you sure you want to revoke access for %s?', $user)
                  );
                  echo '<hr>';
                
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'declined') {
                  $responded = true;
                  echo '<p class="text-error"><i class="icon-remove"> </i> ' . $user . ' <small class="muted">(Declines)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                  echo '<p>Has Conflict of interest? ' . $response['conflict'] . ' </p>';
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

        <!-- INTERNAL REVIEWERS -->


        <div class="span6">
        <h4 class="text-success">Assigned Internal Reviewers (<?php echo $count_internal_reviews; ?>)</h4>
        <p><small class="muted">Assign one internal reviewer at a time. If the current reviewer cannot proceed, use Revoke Access before response and assign the next reviewer.</small></p>
        <hr>
          <?php
          echo $this->Form->create(
            'Review',
            array('url' => array('controller' => 'reviews', 'action' => 'manager_assign_internal', $application['Application']['id']))
          );
          $counter = 0;
          echo "<ol>";
          foreach ($external as $user_id => $user) {
            echo "<li>";
            $responded = false;
            foreach ($application['InternalReview'] as $response) {

              if ($response['user_id'] == $user_id) {
                if ($response['type'] == 'request' && $response['accepted'] == '') {
                  $responded = true;
                  echo '<p class="text-info"><i class="icon-check-empty"> </i> ' . $user . '.
                               <small class="muted">(Notified but no response yet. 
                                <a class="ResendReview tiptip" href="#" id="' . $response['id'] . '" title="Resend Notification?">Resend?</a>)</small> </p>';
                  if ($response['conflict'] != '') {
                    echo '<p>Has Conflict of interest? <b>' . $response['conflict'] . '</b> </p>';
                  }
                  echo '<p><i class="icon-time"> </i> Date Assigned: ' . date('d-m-Y H:i:s', strtotime($response['created'])) . '</p>';
                  $assignedBy = !empty($response['assigned_by_name']) ? $response['assigned_by_name'] : 'N/A';
                  echo '<p><i class="icon-user"> </i> Assigned By: ' . h($assignedBy) . '</p>';
                  echo $this->Html->link(
                    __('<small class="muted"> No response yet? Revoke Access</small>'),
                    array('controller' => 'reviews', 'action' => 'revoke', $response['id'], $application['Application']['id']),
                    array('escape' => false),
                    __('Are you sure you want to revoke access for %s?', $user)
                  );
                  echo '<hr>';
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'accepted') {
                  $responded = true;
                  echo '<p class="text-success"><i class="icon-check"> </i> ' . $user . ' <small class="muted">(Accepts)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                  // echo '<p><i class="icon-minus"> </i> '.$response['text'].'</p>';
                  echo '<p>Has Conflict of interest? ' . $response['conflict'] . ' </p>';
                  echo '<p><i class="icon-time"> </i> Date Assigned: ' . date('d-m-Y H:i:s', strtotime($response['created'])) . '</p>';
                  $assignedBy = !empty($response['assigned_by_name']) ? $response['assigned_by_name'] : 'N/A';
                  echo '<p><i class="icon-user"> </i> Assigned By: ' . h($assignedBy) . '</p>';
                  // ask if the user has showned conflict of interest with an link to revoke access with a confirmation dialog
                  echo $this->Html->link(
                    __('<small class="muted"> Shown Interest? Revoke Access</small>'),
                    array('controller' => 'reviews', 'action' => 'revoke', $response['id'], $application['Application']['id']),
                    array('escape' => false),
                    __('Are you sure you want to revoke access for %s?', $user)
                  );
                  echo '<hr>';
                
                } elseif ($response['type'] == 'request' && $response['accepted'] == 'declined') {
                  $responded = true;
                  echo '<p class="text-error"><i class="icon-remove"> </i> ' . $user . ' <small class="muted">(Declines)</small> <i class="icon-minus"> </i> ' . $response['recommendation'] . '</p>';
                  echo '<p>Has Conflict of interest? ' . $response['conflict'] . ' </p>';
                }
              }
            }

            if (!$responded) {
              echo '<label class="radio" style="color: #333333">';
              echo '<input type="radio" name="data[Review][user_id]" value="' . (int) $user_id . '"> ';
              echo h($user);
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
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/review'); ?>
        </div>
      </div>
     
    </div>

    <div class="tab-pane" id="tab4">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <div class="row-fluid">
        <div class="span12">
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

      <h4 class="text-info" style="text-decoration: underline;">PPB Manager's Comments Form</h4>
      <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title']; ?></p>

      <div class="row-fluid">
        <div class="span12">
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
      </div>

      <?php
      //Reviews limited to ppb_comment already
      $var = Hash::extract($application, 'Review.{n}[type=ppb_comment]');
      $rid = null;
      if (!empty($var)) $rid = min($var); 
      ?>
      <ul id="reviewer_tab" class="nav nav-tabs">
        <li class="active"><a href="#external_rev_comments">PI Comments (<?php echo count($rid['ExternalComment']); ?>)</a></li>
        <?php if ($redir !== 'applicant') { ?><li><a href="#internal_rev_comments">Internal Comments (<?php echo count($rid['InternalComment']); ?>)</a></li> <?php } ?>
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
                    if (!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['InternalComment'], 'show' => false]);

                    //NEW*** Bring in all the assessment comments
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
                    // if (!empty($rid)) 
                       echo $this->element('comments/add', [
                      'model' => [
                        'model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],
                        'model' => 'Review', 'category' => 'internal', 'url' => 'add_internal_review_response'
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

    <div class="tab-pane" id="tab5">
      <?php echo $this->element('multi/manager_approve'); ?>
    </div>

    <div class="tab-pane" id="ecitizen_invoice">
      <?php echo $this->element('application/invoice'); ?>
    </div>

    <div class="tab-pane" id="tab6">
      <div class="row-fluid">
        <div class="span12">

          <h4 class="text-success">Assigned Inspectors (<?php echo $count_inspectors; ?>)</h4>
          <hr>

          <!-- start -->

          <?php
          echo $this->Form->create(
            'ActiveInspector',
            array('url' => array('controller' => 'active_inspectors', 'action' => 'assign', $application['Application']['id']))
          );
          $counter = 0;
          echo "<ol>";
          foreach ($inspectors as $user_id => $user) {
            echo "<li>";
            $responded = false;
            foreach ($application['ActiveInspector'] as $response) {

              if ($response['user_id'] == $user_id) {
                $responded = true;
                echo '<p class="text-success"><i class="icon-check"> </i> ' . $user . '<small class="muted">
                     ' . $this->Html->link(__('<small class="muted primary">Revoke</small>'), array('controller' => 'active_inspectors', 'action' => 'revoke', $response['id'], $application['Application']['id']), array('escape' => false)) . '
                    </small></p>';
              }
            }

            if (!$responded) {
              echo '<label class="checkbox" style="color: #333333">';
              echo $this->Form->checkbox($counter . '.ActiveInspector.user_id', array('hiddenField' => false, 'value' => $user_id));
              echo $user;
              echo '</label>';
            }
            $counter++;
            echo "</li>";
          }
          echo "</ol>";
          echo $this->Form->input('Message.text', array('type' => 'textarea', 'rows' => 3, 'label' => 'Message'));
          echo $this->Form->end(array('label' => 'Assign', 'value' => 'Assign', 'class' => 'btn btn-success'));
          ?>

          <!-- end -->

          <hr>

          <?php
          echo $this->element('/application/inspection_edit');
          ?>
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

    <!-- Amendments Section -->
    <div class="tab-pane" id="amendments">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/amendments'); ?>
        </div>
      </div>
    </div>

    <?php if ($hasAmendmentTimeline) { ?>
      <div class="tab-pane" id="amendment_timelines">
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-info">Amendment Timelines</h4> 
            <div style="overflow-x:auto;">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                  <tr>
                    <th style="min-width: 110px;">Amendment</th>
                    <?php
                    echo $this->element('amendments/section_one_snapshot', array(
                      'format' => 'column_headers'
                    ));
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($amendmentTimelineMap as $timelineEntry) { ?>
                    <tr>
                      <td><strong><?php echo h($timelineEntry['label']); ?></strong></td>
                      <?php
                      echo $this->element('amendments/section_one_snapshot', array(
                        'sectionOne' => !empty($timelineEntry['section_one']) ? $timelineEntry['section_one'] : array(),
                        'format' => 'column_values',
                        'emptyText' => ''
                      ));
                      ?>
                    </tr>
                    <tr>
                      <td colspan="7" style="background-color: azure;">
                        <?php echo $renderTimelineStrip($timelineEntry); ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

  </div>
</div>

<script text="type/javascript">
  if ($.expander && $.expander.defaults) {
    $.expander.defaults.slicePoint = 170;
    $.expander.defaults.expandText = 'show more';
    $.expander.defaults.userCollapseText = 'show less';
  }
  $(function() {
    $(document).ajaxStop($.unblockUI);
    $("#tabs").tabs({
      cookie: {
        expires: 1
      }
    });

    //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
    //from mcaz
    $('#reviewer_tab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $('#reviewer_tab a').on("shown", function(e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('assessmentTab', id)
    });

    var assessmentTab = localStorage.getItem('assessmentTab');
    if (assessmentTab != null) {
      // console.log("select tab");
      // console.log($('#reviewer_tab a[href="' + assessmentTab + '"]'));
      $('#reviewer_tab a[href="' + assessmentTab + '"]').tab('show');
    }

    var hashaTab = $('#reviewer_tab a[href="' + location.hash + '"]');
    hashaTab && hashaTab.tab('show');

    if ($.fn.expander) {
      $(".morecontent").expander();
    }
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

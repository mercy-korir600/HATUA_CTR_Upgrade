<?php
echo $this->Session->flash();

$comparisonReviewAnswerMap = (!empty($comparisonReviewAnswerMap) && is_array($comparisonReviewAnswerMap))
  ? $comparisonReviewAnswerMap
  : array();
$hasComparison = !empty($comparisonReviewAnswerMap);
$comparisonSourceReviewId = !empty($comparisonSourceReviewId) ? (int) $comparisonSourceReviewId : 0;
$comparisonReviewerName = !empty($comparisonReviewerName)
  ? trim((string) $comparisonReviewerName)
  : 'Reviewer 1';

$currentReviewerName = 'Reviewer 2';
if (!empty($rreview['User']['name'])) {
  $currentReviewerName = trim((string) $rreview['User']['name']);
} elseif (!empty($rreview['User']['username'])) {
  $currentReviewerName = trim((string) $rreview['User']['username']);
} elseif (!empty($rreview['user_id'])) {
  $currentReviewerName = 'Reviewer #' . (int) $rreview['user_id'];
}

$buildComparisonKey = function ($questionType, $questionNumber, $questionText) {
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

$formatAnswerText = function ($value) {
  $value = trim((string) $value);
  if ($value === '') {
    return '<span class="muted">No response provided.</span>';
  }

  if (preg_match('/<\s*(p|ul|ol|li|br|div|strong|em|span|h[1-6]|table|blockquote)\b/i', $value)) {
    return $value;
  }

  return nl2br(h($value));
};
?>

<h3 style="text-align: center;"> <?php echo ucfirst($rreview['assessment_type']); ?> Assessment Form</h3>
<hr class="soften" style="margin: 10px 0px;">

<?php if ($hasComparison) { ?>
  <style type="text/css">
    .internal-review-legend {
      margin-top: 8px;
    }
    .internal-review-legend-item {
      display: inline-block;
      margin-right: 16px;
      font-weight: 600;
    }
    .internal-review-legend-swatch {
      display: inline-block;
      width: 12px;
      height: 12px;
      margin-right: 6px;
      vertical-align: middle;
      border-radius: 2px;
    }
    .internal-review-legend-swatch-reviewer1 {
      background: #1d5fbf;
    }
    .internal-review-legend-swatch-reviewer2 {
      background: #b30000;
    }
    .internal-reviewer1-block {
      border-left: 4px solid #1d5fbf;
      background: #eef5ff;
      color: #1d5fbf;
      padding: 8px;
      margin-bottom: 8px;
    }
    .internal-reviewer2-block {
      border-left: 4px solid #b30000;
      background: #fff1f1;
      color: #b30000;
      padding: 8px;
    }
    .internal-reviewer1-block p,
    .internal-reviewer2-block p {
      margin: 0;
    }
  </style>
  <div class="alert alert-info" style="margin-bottom: 10px;">
    <strong>Legend:</strong>
    <div class="internal-review-legend">
      <span class="internal-review-legend-item">
        <span class="internal-review-legend-swatch internal-review-legend-swatch-reviewer1"></span>
        <?php echo h($comparisonReviewerName); ?>
      </span>
      <span class="internal-review-legend-item">
        <span class="internal-review-legend-swatch internal-review-legend-swatch-reviewer2"></span>
        <?php echo h($currentReviewerName); ?>
      </span>
    </div>
    <small class="muted"></small>
    <?php if ($comparisonSourceReviewId > 0) { ?>
      <br><small class="muted"></small>
    <?php } ?>
  </div>
<?php } ?>

<table class="table table-bordered table-condensed">
  <tbody>
    <tr>
      <th class="my-well" style="width: 45%">Study Title</th>
      <td><?php echo $application['Application']['study_title']; ?></td>
    </tr>
    <tr>
      <th class="my-well">Short Title</th>
      <td><?php echo $application['Application']['short_title']; ?></td>
    </tr>
    <tr>
      <th class="my-well">Protocol No</th>
      <td><?php echo $application['Application']['protocol_no']; ?></td>
    </tr>
    <tr>
      <th class="my-well">Investigation medicinal product</th>
      <td><?php echo $application['Application']['study_drug']; ?></td>
    </tr>
  </tbody>
</table>

<table class="table table-bordered table-condensed">
  <thead><th></th><th width="35%"></th></thead>
  <tbody>
    <?php
    for ($i = 0; $i <= count($rreview['ReviewAnswer']) - 1; $i++) {
      echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.id', array('type' => 'hidden', 'value' => $rreview['ReviewAnswer'][$i]['id']));
      echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.rreview_id', array('type' => 'hidden', 'value' => $rreview['id']));

      $questionType = !empty($rreview['ReviewAnswer'][$i]['question_type']) ? strtolower(trim((string) $rreview['ReviewAnswer'][$i]['question_type'])) : '';
      $currentAnswerText = $extractAnswerText($rreview['ReviewAnswer'][$i]);
      $comparisonKey = $buildComparisonKey(
        $questionType,
        !empty($rreview['ReviewAnswer'][$i]['question_number']) ? $rreview['ReviewAnswer'][$i]['question_number'] : '',
        !empty($rreview['ReviewAnswer'][$i]['question']) ? $rreview['ReviewAnswer'][$i]['question'] : ''
      );
      $previousAnswerText = !empty($comparisonReviewAnswerMap[$comparisonKey]) ? trim((string) $comparisonReviewAnswerMap[$comparisonKey]) : '';

      if ($questionType == 'label') {
        echo "<tr class='success'><td colspan='2'><strong>" . $rreview['ReviewAnswer'][$i]['question'] . "</strong></td></tr>";
        continue;
      }

      echo "<tr>";
      echo "<td>" . $rreview['ReviewAnswer'][$i]['question'] . "</td>";
      echo "<td>";

      if ($hasComparison && $previousAnswerText !== '' && $currentAnswerText !== '') {
        echo '<div class="internal-reviewer1-block">' . $formatAnswerText($previousAnswerText) . '</div>';
        echo '<div class="internal-reviewer2-block">' . $formatAnswerText($currentAnswerText) . '</div>';
      } elseif ($hasComparison && $previousAnswerText !== '') {
        echo '<div class="internal-reviewer1-block">' . $formatAnswerText($previousAnswerText) . '</div>';
      } elseif ($hasComparison && $currentAnswerText !== '') {
        echo '<div class="internal-reviewer2-block">' . $formatAnswerText($currentAnswerText) . '</div>';
      } elseif ($currentAnswerText !== '') {
        echo $formatAnswerText($currentAnswerText);
      } elseif ($previousAnswerText !== '') {
        echo $formatAnswerText($previousAnswerText);
      } else {
        echo '<span class="muted">No response provided.</span>';
      }

      echo "</td>";
      echo "</tr>";
    }
    ?>
  </tbody>
</table>

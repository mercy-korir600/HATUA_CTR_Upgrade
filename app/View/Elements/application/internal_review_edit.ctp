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

$buildComparisonKey = function ($questionType, $questionNumber, $questionText) {
  $questionType = strtolower(trim((string) $questionType));
  $questionNumber = trim((string) $questionNumber);
  if ($questionNumber !== '' && is_numeric($questionNumber)) {
    $questionNumber = number_format((float) $questionNumber, 2, '.', '');
  }
  $questionText = strtolower(preg_replace('/\s+/', ' ', trim((string) $questionText)));
  return $questionType . '|' . $questionNumber . '|' . $questionText;
};

$renderComparisonBlock = function ($comparisonText) {
  $comparisonText = trim((string) $comparisonText);
  if ($comparisonText === '') {
    return '';
  }

  return '<div class="well well-small internal-review-previous" style="margin-top: 8px; margin-bottom: 8px;">'
    . nl2br(h($comparisonText))
    . '</div>';
};
?>
<h3 style="text-align: center;"><?php echo ucfirst($rreview['assessment_type']); ?> Assessment Form</h3>
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
    .internal-review-previous {
      border-left: 4px solid #1d5fbf;
      background: #eef5ff !important;
      color: #1d5fbf !important;
    }
    .internal-review-row-updated {
      background: #fff4f4;
    }
    .internal-review-row-updated textarea {
      color: #b30000;
      font-weight: 600;
    }
    .internal-review-row-updated .radio.inline {
      color: #b30000;
      font-weight: 600;
    }
    .internal-review-current-preview {
      margin-top: 6px;
      border-left: 4px solid #b30000;
      background: #fff1f1 !important;
      color: #b30000 !important;
      font-weight: 600;
      padding: 8px;
    }
    .internal-review-previous *,
    .internal-review-previous a {
      color: #1d5fbf !important;
    }
    .internal-review-current-preview *,
    .internal-review-current-preview a {
      color: #b30000 !important;
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
        Your response
      </span>
    </div>
    <small class="muted">Empty fields are prefilled from the linked source so you can edit only sections you need.</small>
    <?php if ($comparisonSourceReviewId > 0) { ?>
      <br><small class="muted">Linked source review: #<?php echo (int) $comparisonSourceReviewId; ?></small>
    <?php } ?>
  </div>
<?php } ?>

<?php
if ($this->Session->read('Auth.User.id') == $rreview['user_id'] and $rreview['status'] == 'Unsubmitted') {
  echo $this->Form->create('Review', array(
    'url' => array('internalreviewer' => true, 'controller' => 'reviews', 'action' => 'assess', $rreview['id'], $rreview['application_id']),
    'type' => 'file',
    'class' => 'form-inline',
    'inputDefaults' => array(
      'label' => array('class' => 'control-label'),
      'class' => '',
      'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
      'error' => array('attributes' => array('class' => 'controls help-block')),
    ),
  ));
  echo $this->Form->input('Review.' . $akey . '.id', array('value' => $rreview['id'], 'type' => 'hidden'));
?>
  <div class="row-fluid assessment-form-row">
        <div class="span10">
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
        <thead>
          <tr>
            <?php if ($hasComparison) { ?>
              <th>Question and Linked Reviewer Response</th>
              <th width="35%">Your Response</th>
            <?php } else { ?>
              <th></th>
              <th width="35%"></th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <?php
          for ($i = 0; $i <= count($rreview['ReviewAnswer']) - 1; $i++) {
            $questionType = trim((string) $rreview['ReviewAnswer'][$i]['question_type']);
            $comparisonKey = $buildComparisonKey(
              $questionType,
              $rreview['ReviewAnswer'][$i]['question_number'],
              $rreview['ReviewAnswer'][$i]['question']
            );
            $comparisonResponse = !empty($comparisonReviewAnswerMap[$comparisonKey]) ? $comparisonReviewAnswerMap[$comparisonKey] : '';
            $comparisonNormalized = trim((string) $comparisonResponse);

            $currentResponse = '';
            if ($questionType === 'yesno' || $questionType === 'text') {
              $currentResponse = trim((string) $rreview['ReviewAnswer'][$i]['answer']);
            } elseif ($questionType === 'workspace') {
              $currentResponse = trim((string) $rreview['ReviewAnswer'][$i]['workspace']);
            } elseif ($questionType === 'comment') {
              $currentResponse = trim((string) $rreview['ReviewAnswer'][$i]['comment']);
            }

            $effectiveResponse = $currentResponse;
            if ($hasComparison && $effectiveResponse === '' && $comparisonNormalized !== '' && $questionType !== 'label') {
              $effectiveResponse = $comparisonNormalized;
            }

            $isUpdated = false;
            if ($hasComparison && $questionType !== 'label') {
              $isUpdated = (trim((string) $effectiveResponse) !== $comparisonNormalized);
            }

            $rowClasses = array('internal-review-row');
            if ($isUpdated) {
              $rowClasses[] = 'internal-review-row-updated';
            }
            $rowAttrs = ' class="' . h(implode(' ', $rowClasses)) . '" data-field-type="' . h($questionType) . '" data-comparison="' . h($comparisonNormalized) . '"';

            echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.id', array('type' => 'hidden', 'value' => $rreview['ReviewAnswer'][$i]['id']));
            echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.rreview_id', array('type' => 'hidden', 'value' => $rreview['id']));

            if ($questionType == 'label') {
              echo "<tr class='success'><td colspan='2'><strong>" . $rreview['ReviewAnswer'][$i]['question'] . "</strong></td></tr>";
            } elseif ($questionType == 'yesno') {
              echo "<tr" . $rowAttrs . ">";
              echo "<td>" . $rreview['ReviewAnswer'][$i]['question'] . $renderComparisonBlock($comparisonResponse) . "</td>";
              echo "<td>";
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.answer', array(
                'type' => 'radio',
                'label' => false,
                'legend' => false,
                'div' => false,
                'hiddenField' => false,
                'error' => false,
                'class' => 'answer' . $i . str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']) . ' js-reviewer2-input',
                'value' => $effectiveResponse,
                'before' => '
                        <input type="hidden" value="" id="Review' . $akey . $i . 'ReviewAnswer_" name="data[Review][' . $akey . '][ReviewAnswer][' . $i . '][answer]"> <label class="radio inline">',
                'after' => '</label>',
                'options' => array('Yes' => 'Yes'),
              ));
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.answer', array(
                'type' => 'radio',
                'label' => false,
                'legend' => false,
                'div' => false,
                'hiddenField' => false,
                'error' => false,
                'class' => 'answer' . $i . str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']) . ' js-reviewer2-input',
                'value' => $effectiveResponse,
                'before' => '<label class="radio inline">',
                'after' => '</label>',
                'options' => array('No' => 'No')
              ));
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.answer', array(
                'type' => 'radio',
                'label' => false,
                'legend' => false,
                'div' => false,
                'hiddenField' => false,
                'class' => 'answer' . $i . str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']) . ' js-reviewer2-input',
                'value' => $effectiveResponse,
                'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                'before' => '<label class="radio inline">',
                'after' => '</label>
                            <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                            onclick="$(\'.answer' . $i . str_replace('.', '_', $rreview['ReviewAnswer'][$i]['question_number']) . '\').removeAttr(\'checked disabled\')">
                            <em class="accordion-toggle"><i class="icon-remove-circle"></i> </em></a> </span>
                           ',
                'options' => array('N/A' => 'N/A'),
              ));
              if ($hasComparison) {
                $previewStyle = $isUpdated ? '' : ' style="display:none;"';
                echo '<div class="internal-review-current-preview js-reviewer2-preview"' . $previewStyle . '>'
                  . '<span class="js-reviewer2-preview-value">' . h($effectiveResponse) . '</span>'
                  . '</div>';
              }
              echo "</td>";
              echo '</tr>';
            } elseif ($questionType == 'text') {
              echo "<tr" . $rowAttrs . ">";
              echo "<td>" . $rreview['ReviewAnswer'][$i]['question'] . $renderComparisonBlock($comparisonResponse) . "</td>";
              echo "<td>";
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.answer', array(
                'type' => 'textarea',
                'label' => false,
                'rows' => 3,
                'class' => 'js-reviewer2-input',
                'value' => $effectiveResponse
              ));
              if ($hasComparison) {
                $previewStyle = $isUpdated ? '' : ' style="display:none;"';
                echo '<div class="internal-review-current-preview js-reviewer2-preview"' . $previewStyle . '>'
                  . '<span class="js-reviewer2-preview-value">' . h($effectiveResponse) . '</span>'
                  . '</div>';
              }
              echo "</td></tr>";
            } elseif ($questionType == 'workspace') {
              echo "<tr" . $rowAttrs . ">";
              echo "<td>" . $rreview['ReviewAnswer'][$i]['question'] . $renderComparisonBlock($comparisonResponse) . "</td>";
              echo "<td>";
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.workspace', array(
                'type' => 'textarea',
                'label' => false,
                'rows' => 3,
                'class' => 'js-reviewer2-input',
                'value' => $effectiveResponse
              ));
              if ($hasComparison) {
                $previewStyle = $isUpdated ? '' : ' style="display:none;"';
                echo '<div class="internal-review-current-preview js-reviewer2-preview"' . $previewStyle . '>'
                  . '<span class="js-reviewer2-preview-value">' . h($effectiveResponse) . '</span>'
                  . '</div>';
              }
              echo "</td></tr>";
            } elseif ($questionType == 'comment') {
              echo "<tr" . $rowAttrs . ">";
              echo "<td>" . $rreview['ReviewAnswer'][$i]['question'] . $renderComparisonBlock($comparisonResponse) . "</td>";
              echo "<td>";
              echo $this->Form->input('Review.' . $akey . '.ReviewAnswer.' . $i . '.comment', array(
                'type' => 'textarea',
                'label' => false,
                'rows' => 2,
                'class' => 'js-reviewer2-input',
                'value' => $effectiveResponse
              ));
              if ($hasComparison) {
                $previewStyle = $isUpdated ? '' : ' style="display:none;"';
                echo '<div class="internal-review-current-preview js-reviewer2-preview"' . $previewStyle . '>'
                  . '<span class="js-reviewer2-preview-value">' . h($effectiveResponse) . '</span>'
                  . '</div>';
              }
              echo "</td></tr>";
            }
          }
          ?>
        </tbody>
      </table>

       </div>                                                                                                                     
       <div class="span2">
          <div class="assessment-sticky-sidebar">                                                                                    
            <div class="well" style="padding: 12px 10px; background-color: #f7f7f7; border: 1px solid #d5d5d5; box-shadow: 0 4px 10px rgba(0,0,0,0.12);">                  
              <?php                                                                                                                                                        
              echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(                                                                                    
                'name' => 'saveChanges',                                                                                                                                   
                'class' => 'btn btn-success btn-block mapop',                                                                                                              
                'id' => 'rreviewSaveChanges',                                                                                                                              
                'title' => 'Save & continue editing',                                                                                                                      
                'data-content' => 'Save changes to form without submitting it. The form will still be available for further editing.',                                     
                'div' => false,                                                                                                                                            
              ));                                                                                                                                                          
              ?>                                                                                                                                                           
              <hr style="margin: 10px 0;">                                                                                                                                  
              <?php                                                                                                                                                        
              echo $this->Form->button('<i class="icon-rocket"></i> Submit', array(                                                                                        
                'name' => 'submitReport',                                                                                                                                  
                'onclick' => "return confirm('Are you sure you wish to submit the protocol review report?');",                                                             
                'class' => 'btn btn-primary btn-block mapop',                                                                                                              
                'id' => 'rreviewSubmitReport',                                                                                                                             
                'title' => 'Save and Submit Report',
                'data-content' => 'Submit report for peer review and approval.',
                'div' => false,
              ));
              ?>
            </div>
          </div>
        </div>
      </div> 

<?php
  echo $this->Form->end();
?>
<script type="text/javascript">
$(function() {
  function normalizeReviewValue(value) {
    value = value || '';
    return $.trim(String(value).replace(/\r\n/g, '\n').replace(/\r/g, '\n'));
  }

  function getCurrentRowValue($row) {
    var fieldType = String($row.attr('data-field-type') || '');
    if (fieldType === 'yesno') {
      return normalizeReviewValue($row.find('input[type="radio"]:checked').val() || '');
    }
    return normalizeReviewValue($row.find('textarea.js-reviewer2-input').val() || '');
  }

  function refreshRowState($row) {
    var baseline = normalizeReviewValue($row.attr('data-comparison') || '');
    var current = getCurrentRowValue($row);
    var changed = (baseline !== current);

    $row.toggleClass('internal-review-row-updated', changed);

    var $preview = $row.find('.js-reviewer2-preview');
    if ($preview.length) {
      if (changed && current !== '') {
        $preview.find('.js-reviewer2-preview-value').text(current);
        $preview.show();
      } else {
        $preview.hide();
      }
    }
  }

  $('.internal-review-row[data-field-type]').each(function() {
    refreshRowState($(this));
  });

  $(document).on('change keyup', '.internal-review-row .js-reviewer2-input', function() {
    refreshRowState($(this).closest('.internal-review-row'));
  });
});
</script>
<?php
}
?>

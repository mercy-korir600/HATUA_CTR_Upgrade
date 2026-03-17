<?php
$sectionOne = (!empty($sectionOne) && is_array($sectionOne)) ? $sectionOne : array();
$format = !empty($format) ? (string)$format : 'compact';
$title = isset($title) ? trim((string)$title) : '';
$emptyText = isset($emptyText) ? trim((string)$emptyText) : '';
$allowHtml = !empty($allowHtml);

$fieldConfig = array(
  array('key' => 'cover_letter', 'table_label' => '1. Cover letter', 'compact_label' => 'Cover Letter'),
  array('key' => 'summary', 'table_label' => '2. Summary of the proposed amendments', 'compact_label' => 'Summary'),
  array('key' => 'reason', 'table_label' => '3. Reason for the amendment', 'compact_label' => 'Reason'),
  array('key' => 'objectives_impacts', 'table_label' => '4. Impact on the original study objectives', 'compact_label' => 'Objectives Impact'),
  array('key' => 'endpoints_impacts', 'table_label' => '5. Impact on study endpoints and data generated', 'compact_label' => 'Endpoints Impact'),
  array('key' => 'safety_impacts', 'table_label' => '6. Impact on the safety and wellbeing of participants', 'compact_label' => 'Safety Impact')
);

$renderFieldValue = function ($value) use ($allowHtml) {
  if ($allowHtml) {
    return (string)$value;
  }
  $text = html_entity_decode((string)$value, ENT_QUOTES, 'UTF-8');
  $text = str_replace("\xC2\xA0", ' ', $text);
  $text = strip_tags($text);
  $text = preg_replace('/\s+/', ' ', $text);
  return h(trim((string)$text));
};

$hasMeaningfulText = function ($value) {
  $text = trim((string)$value);
  if ($text === '') {
    return false;
  }
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  $text = str_replace("\xC2\xA0", ' ', $text);
  $text = strip_tags($text);
  $text = preg_replace('/\s+/', ' ', $text);
  return trim($text) !== '';
};
?>

<?php if ($format === 'table_rows') { ?>
  <?php foreach ($fieldConfig as $field) { ?>
    <?php $value = !empty($sectionOne[$field['key']]) ? $sectionOne[$field['key']] : ''; ?>
    <tr>
      <td class="table-label"><strong><?php echo h($field['table_label']); ?></strong></td>
      <td>
        <?php if ($hasMeaningfulText($value)) { ?>
          <div class="morecontent"><?php echo $renderFieldValue($value); ?></div>
        <?php } else { ?>
          <span class="muted"><?php echo h($emptyText); ?></span>
        <?php } ?>
      </td>
    </tr>
  <?php } ?>
<?php } elseif ($format === 'column_headers') { ?>
  <?php foreach ($fieldConfig as $field) { ?>
    <th style="min-width: 180px;"><?php echo h($field['compact_label']); ?></th>
  <?php } ?>
<?php } elseif ($format === 'column_values') { ?>
  <?php foreach ($fieldConfig as $field) { ?>
    <?php $value = !empty($sectionOne[$field['key']]) ? $sectionOne[$field['key']] : ''; ?>
    <td style="vertical-align: top; min-width: 180px;">
      <?php if ($hasMeaningfulText($value)) { ?>
        <div class="morecontent"><?php echo $renderFieldValue($value); ?></div>
      <?php } else { ?>
        <span class="muted"><?php echo h($emptyText); ?></span>
      <?php } ?>
    </td>
  <?php } ?>
<?php } else { ?>
  <div class="well well-small" style="margin:0 0 8px 0;padding:8px;">
    <?php if ($title !== '') { ?>
      <strong><?php echo h($title); ?></strong>
    <?php } ?>
    <?php
    $hasContent = false;
    foreach ($fieldConfig as $field) {
      $value = !empty($sectionOne[$field['key']]) ? $sectionOne[$field['key']] : '';
      if (!$hasMeaningfulText($value)) {
        continue;
      }
      $hasContent = true;
      echo '<div style="margin-top:4px;"><small><strong>' . h($field['compact_label']) . ':</strong></small>';
      echo '<div class="morecontent">' . $renderFieldValue($value) . '</div></div>';
    }

    if (!empty($sectionOne['cover_file'])) {
      $hasContent = true;
      echo '<div style="margin-top:4px;"><small><strong>File:</strong> ' . h($sectionOne['cover_file']) . '</small></div>';
    }
    if (!empty($sectionOne['created'])) {
      $hasContent = true;
      echo '<div style="margin-top:4px;"><small><strong>Created:</strong> ' . h($sectionOne['created']) . '</small></div>';
    }
    if (!$hasContent) {
      echo '<small class="muted">' . h($emptyText) . '</small>';
    }
    ?>
  </div>
<?php } ?>

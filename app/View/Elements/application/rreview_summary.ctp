<?php
$canEditSummary = (($this->Session->read('Auth.User.id') == $rreview['user_id']) and $rreview['status'] != 'Summary');
$formatReviewContent = function ($content) {
  $content = trim((string)$content);
  if ($content === '') {
    return '<span class="muted">No content provided.</span>';
  }

  // Keep WYSIWYG HTML as-is when present.
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
?>

<?php if ($canEditSummary) { ?>
  <div class="page-header">
    <div class="styled_title"><h3>Summary Report</h3></div>
  </div>
    <?php
      echo $this->Form->create('Review', array(
            'url' => array('controller' => 'reviews','action' => 'summary', $rreview['id'], $rreview['application_id']),
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
      echo $this->Form->input('Review.'.$akey.'.id', array('value' => $rreview['id'], 'type' => 'hidden'));

      echo $this->Form->input('Review.'.$akey.'.summary',
        array(
          'type' => 'textarea',
          'rows' => 10,
          'class' => 'input-xxlarge rreview-summary-editor',
          'label' => array('class' => 'control-label required', 'text' => 'Summary of comments <span class="sterix">*</span>')
        ));
      echo $this->Form->input('Review.'.$akey.'.recommendation',
        array(
          'type' => 'textarea',
          'rows' => 6,
          'class' => 'input-xxlarge rreview-summary-editor',
          'label' => array('class' => 'control-label required', 'text' => 'Recommendation <span class="sterix">*</span>')
        ));
      ?>

    <div class="row-fluid">
      <div class="span10">
        <div class="well">
          <?php
            echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
                'name' => 'submitReport',
                'onclick'=>"return confirm('Are you sure you wish to submit the report?');",
                'class' => 'btn btn-info  mapop',
                'id' => 'LeloSubmitReport', 'title'=>'Save and Submit Report',
                'data-content' => 'Save the report and submit.',
                'div' => false,
              ));

          ?>
        </div>
      </div>
    </div>

    <?php
    echo $this->Form->end();
  ?>

  <?php } else { ?>
  <div class="page-header">
    <div class="styled_title"><h3>Summary Report</h3></div>
  </div>
    <table class="table  table-condensed">
      <tbody>
       <tr>
        <td class="table-label required"><p>Summary of comments: <span class="sterix">*</span></p></td>
        <td><div class="well well-small"><?php echo $formatReviewContent($rreview['summary']); ?></div></td>
       </tr>
       <tr>
        <td class="table-label required"><p>Reccomendation: <span class="sterix">*</span></p></td>
        <td><div class="well well-small"><?php echo $formatReviewContent($rreview['recommendation']); ?></div></td>
       </tr>
      </tbody>
    </table>
  <?php } ?>

<?php if ($canEditSummary) { ?>
<script text="type/javascript">
$(function() {
  if (!window.CKEDITOR) {
    return;
  }

  $('.rreview-summary-editor').each(function() {
    if (this.id && !CKEDITOR.instances[this.id]) {
      CKEDITOR.replace(this.id);
    }
  });
});
</script>
<?php } ?>

<?php
echo $this->Session->flash();
?>
<h3>Approval Letters</h3>
<?php if ($redir == 'manager') { ?>
  <br>
  <div class="row-fluid">
    <div class="span6">
      <?php
      if ($redir == 'manager') {
        echo $this->Html->link(
          __('<i class="icon-file"></i> Generate initial approval letter'),
          array('controller' => 'annual_letters', 'action' => 'initial', $application['Application']['id']),
          array('escape' => false, 'class' => 'btn btn-primary')
        );
      }
      ?>
    </div>
    <div class="span6">
      <?php
      if ($redir == 'manager') {
        echo $this->Html->link(
          __('<i class="icon-file-alt"></i> Generate annual approval letter'),
          array('controller' => 'annual_letters', 'action' => 'generate', $application['Application']['id']),
          array('escape' => false, 'class' => 'btn btn-info')
        );
      }
      ?>
    </div>
  </div>
  <br>
<?php } ?>

<table class="table  table-bordered table-striped">
  <thead>
    <tr>
      <th>Id</th>
      <th>Approval No.</th>
      <th>Approval date</th>
      <th>Expiry date</th>
      <th>Created</th>
      <th class="actions"><?php echo __('Actions'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($application['AnnualLetter'] as $anl) : ?>
      <?php
      $show = false;
      if ($redir == 'manager') $show = true;
      if ($redir == 'applicant' && $anl['status'] == 'approved') $show = true;
      if ($show) {
      ?>
        <tr class="">
          <td><?php echo h($anl['id']); ?>&nbsp;</td>
          <td><?php echo h($anl['approval_no']); ?>&nbsp;</td>
          <td><?php echo h($anl['approval_date']); ?>&nbsp;</td>
          <td><?php echo h($anl['expiry_date']); ?>&nbsp;</td>
          <td><?php echo h($anl['created']); ?>&nbsp;</td>
          <td class="actions">
            <?php
            if ($anl['status'] == 'submitted') {
              echo $this->Html->link('<span class="label label-success"> Edit </span>', array('action' => 'view', $application['Application']['id'], 'ane' => $anl['id']), array('escape' => false));
            } else {
              echo $this->Html->link('<span class="label label-info"> View </span>', array('action' => 'view', $application['Application']['id'], 'anl' => $anl['id']), array('escape' => false));
            }

            echo "&nbsp;";
            if ($anl['status'] == 'submitted')
              echo $this->Html->link('<span class="label label-warning"> Approve </span>', array('action' => 'view', $application['Application']['id'], 'ane' => $anl['id']), array('escape' => false));
            echo "&nbsp;";
            // if($anl['status'] == 'submitted') 
            echo $this->Html->link('<span class="label label-inverse"> Download PDF </span>', array('controller' => 'annual_letters', 'action' => 'view', $anl['id'], 'ext' => 'pdf',), array('escape' => false));
            ?>
          </td>
        </tr>
      <?php } ?>
    <?php endforeach; ?>
  </tbody>
</table>

<br>
<hr>

<!-- View approval letter -->
<?php
// if (isset($this->params['named']['anl'])) {
  // foreach ($application['AnnualLetter'] as $akey => $annual_letter) {
    // if (($annual_letter['id'] == $this->params['named']['anl'] && $annual_letter['status'] == 'approved') or
      // ($annual_letter['id'] == $this->params['named']['anl'])
    // ) {

     
if (isset($this->params['named']['anl'])) {
  foreach ($application['AnnualLetter'] as $akey => $annual_letter) {
    if (
      $annual_letter['id'] == $this->params['named']['anl'] &&
      $annual_letter['status'] == 'approved'
    ) {
      $anl = $annual_letter;

?>
      <div class="ctr-fluid">
        <div class="span12">
          <br>
          <?php if ($redir != 'applicant') { ?>
            <div class="amend-form">
              <ul id="rreview_tab" class="nav nav-tabs">
                <li class="active"><a href="#feedback_list_letters">Letter</a></li>
                <li><a href="#comment_query_letters">Comment</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="feedback_list_letters">
                  <div class="row-fluid">
                    <div class="span12">
                      <?php echo $anl["content"]; ?> &nbsp;

                      <?php
                      if (!empty($anl['qrcode'])) {
                        $decodedImage = base64_decode($anl['qrcode']);
                        echo $decodedImage;
                      }
                      ?>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="comment_query_letters">
                  <div class="row-fluid">
                    <div class="span12">
                      <div class="amend-form">
                        <ul id="rreview_tab" class="nav nav-tabs">
                          <li class="active"><a href="#rreview_comment_list">COMMENTS/QUERIES</a></li>
                          <li><a href="#rreview_comments_add">Add Comment</a></li>
                        </ul>
                        <div class="tab-content">
                          <div class="tab-pane active" id="rreview_comment_list">
                            <div class="row-fluid">
                              <div class="span12">
                                <?php echo $this->element('comments/list_expandable', ['comments' => $anl['ExternalComment'], 'category' => false]) ?>
                              </div>

                            </div>
                          </div>
                          <div class="tab-pane " id="rreview_comments_add">
                            <div class="row-fluid">
                              <div class="span12">
                                <?php
                                echo $this->element('comments/add', [
                                  'model' => [
                                    'model_id' => $application['Application']['id'],
                                    'foreign_key' => $anl['id'],
                                    'model' => 'AnnualLetter',
                                    'type' => 0,
                                    'category' => 'annual',
                                    'url' => 'add_annual_letter'
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
          <?php } else { ?>
            <div class="amend-form">
              <div class="row-fluid">
                <div class="span12">
                  <?php echo $anl["content"]; ?> &nbsp;

                  <?php
                  if (!empty($anl['qrcode'])) {
                    $decodedImage = base64_decode($anl['qrcode']);
                    echo $decodedImage;
                  }
                  ?>
                </div>
              </div>
              <div class="amend-form">
              <div class="row-fluid">
                <h5 class="text-center"><u>FEEDBACK/QUERIES</u></h5>
                <div class="row-fluid">
                  <div class="span8">
                    <?php echo $this->element('comments/list_expandable', ['comments' => $anl['ExternalComment'], 'category' => false]) ?>

                  </div>
                  <div class="span4">
                    <?php
                    echo $this->element('comments/add', [
                      'model' => [
                        'model_id' => $application['Application']['id'],
                        'foreign_key' => $anl['id'],
                        'model' => 'AnnualLetter',
                        'type' => 0,
                        'category' => 'annual',
                        'url' => 'add_annual_letter'
                      ]
                    ])
                    ?>
                  </div>
                </div>
              </div>
              </div>
            <?php } ?>
            </div>
        </div>
  <?php }
  }
} ?>

  <!-- Edit approval letter -->
  <?php
  if (isset($this->params['named']['ane'])) {
    foreach ($application['AnnualLetter'] as $akey => $annual_letter) {
      if ($annual_letter['id'] == $this->params['named']['ane'] && $annual_letter['status'] != 'approved') {
        // debug($annual_letter['status'] == 'submitted');         
  ?>
        <div class="ctr-groups">
          <?php echo $this->Form->create('AnnualLetter', array(
            'url' => array('controller' => 'annual_letters', 'action' => 'approve', $annual_letter['id']),
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
          echo $this->Form->input('id'); ?>
          <fieldset>
            <legend>Approve</strong></legend>
            <?php
            echo $this->Form->input('id', array('type' => 'hidden', 'value' => $annual_letter['id']));
            echo $this->Form->input('status', array('type' => 'hidden', 'value' => 'approved'));
            echo $this->Form->input('approval_date', array(
              'div' => array('class' => 'control-group'), 'type' => 'text', 'value' => $annual_letter['approval_date'], 'class' => 'datepickers',
              'label' => array('class' => 'control-label required', 'text' => 'Approval date <span class="sterix">*</span>'),
              'after' => '<span class="help-inline">  Date format (dd-mm-yyyy) </span></div>',
            ));
            echo $this->Form->input('expiry_date', array(
              'div' => array('class' => 'control-group'), 'type' => 'text', 'value' => $annual_letter['expiry_date'], 'class' => 'datepickers',
              'label' => array('class' => 'control-label required', 'text' => 'Expiry date <span class="sterix">*</span>'),
              'after' => '<span class="help-inline">  Date format (dd-mm-yyyy) </span></div>',
            ));
            echo $this->Form->input('content', array(
              'label' => false, 'value' => $annual_letter['content'],
              'between' => '<div class="controle">',  'class' => 'input-large',
            ));
            ?>
          </fieldset>
          <?php echo  $this->Form->end(array(
            'label' => 'Paste Signature and Approve',
            'value' => 'Approve',
            'class' => 'btn btn-success',
            'div' => array(
              'class' => 'form-actions',
            )
          ));
          ?>
          <script type="text/javascript">
            (function($) {

              $(".datepickers").datepicker({
                minDate: "-5Y",
                maxDate: "+999D",
                dateFormat: 'dd-mm-yy',
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                //yearRange: "-100Y:+0",
                buttonImageOnly: true,
                showAnim: 'show',
                showOn: 'both',
                buttonImage: '/img/calendar.gif'
              });
            })(jQuery);

            CKEDITOR.replace('data[AnnualLetter][content]');
          </script>
        </div>
  <?php
      }
    }
  }
  ?>
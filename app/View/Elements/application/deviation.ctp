<?php
echo $this->Session->flash();

$selectedDeviationId = null;
$selectedDeviationMode = null;
if (isset($this->params['named']['deviation_edit'])) {
  $selectedDeviationId = (int)$this->params['named']['deviation_edit'];
  $selectedDeviationMode = 'edit';
} elseif (isset($this->params['named']['deviation_view'])) {
  $selectedDeviationId = (int)$this->params['named']['deviation_view'];
  $selectedDeviationMode = 'view';
}
?>

<br>
<div class="row-fluid">
  <div class="span12">
    <?php
    if ($redir == 'applicant' or $redir == 'monitor' or $redir == 'outsource') {
      echo $this->Html->link(
        __('<i class="icon-random"></i> Add Protocol Deviation'),
        array('controller' => 'deviations', 'action' => 'add', $application['Application']['id']),
        array('escape' => false, 'class' => 'btn btn-info')
      );
    }
    ?>
  </div>
</div>
<br>
<table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Reference No</th>
      <th>Deviation Date</th>
      <th>Deviation Type</th>
      <th>Status</th>
      <th>Created</th>
      <th><?php echo __('Actions'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($application['Deviation'] as $akey => $deviation) {
      $deviationId = (int)$deviation['id'];
      $isSelected = ($selectedDeviationId === $deviationId);
      $rowAnchor = 'deviation-row-' . $deviationId;
    ?>
      <tr id="<?php echo h($rowAnchor); ?>" class="<?php echo $isSelected ? 'info' : ''; ?>">
        <td><?php echo $deviation['id'] ?></td>
        <td><?php echo $deviation['reference_no'] ?></td>
        <td><?php echo $deviation['deviation_date'] ?></td>
        <td><?php echo $deviation['deviation_type'] ?></td>
        <td><?php echo $deviation['status'] ?></td>
        <td><?php echo $deviation['created'] ?></td>
        <td>
          <?php
          if ($deviation['status'] === 'Unsubmitted') {
            if ($redir === 'applicant' && $deviation['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(
              '<label class="label label-success">Edit</label>',
              array('action' => 'view', $application['Application']['id'], 'deviation_edit' => $deviation['id'], '#' => $rowAnchor),
              array('escape' => false)
            );
            if ($redir === 'monitor' && $deviation['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(
              '<label class="label label-success">Edit</label>',
              array('action' => 'view', $application['Application']['id'], 'deviation_edit' => $deviation['id'], '#' => $rowAnchor),
              array('escape' => false)
            );
            if ($redir === 'inspector' && $deviation['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(
              '<label class="label label-success">Edit</label>',
              array('action' => 'view', $application['Application']['id'], 'deviation_edit' => $deviation['id'], '#' => $rowAnchor),
              array('escape' => false)
            );
            if ($redir === 'outsource' && $deviation['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(
              '<label class="label label-success">Edit</label>',
              array('action' => 'view', $application['Application']['id'], 'deviation_edit' => $deviation['id'], '#' => $rowAnchor),
              array('escape' => false)
            );

            echo "&nbsp;";

            if ($redir == 'applicant' && $deviation['user_id'] == $this->Session->read('Auth.User.id')) {
              echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'deviations', 'action' => 'delete', $deviation['id']), array('escape' => false), __('Are you sure you want to delete deviation # %s?', $deviation['id']));
            }
          } else {
            echo $this->Html->link(
              '<span class="label label-info"> View </span>',
              array('action' => 'view', $application['Application']['id'], 'deviation_view' => $deviation['id'], '#' => $rowAnchor),
              array('escape' => false)
            );
            echo "&nbsp;";

            if (($redir == 'manager')) {
              echo $this->Form->postLink(__('<label class="label label-inverse">Unsubmit</label>'), array('controller' => 'deviations', 'action' => 'unsubmit', $deviation['id']), array('escape' => false), __('Are you sure you want to unsubmit the deviation # %s? The applicant will be able to edit it.', $deviation['id']));
            }
          }
          ?>
        </td>
      </tr>
      <?php if ($isSelected) {
        $tabId = 'deviation_tab_' . $deviationId;
        $formPaneId = 'deviation_form_' . $deviationId;
        $commentsPaneId = 'deviation_comments_' . $deviationId;
      ?>
        <tr class="deviation-inline-row">
          <td colspan="7" style="padding: 0;">
            <div style="padding: 10px; border: 1px solid #ddd; border-top: 0; background: #fff;">
              <ul id="<?php echo h($tabId); ?>" class="nav nav-tabs deviation-tab">
                <li class="active"><a href="#<?php echo h($formPaneId); ?>">Deviation Form</a></li>
                <li><a href="#<?php echo h($commentsPaneId); ?>">PI Comments (<?php echo count($deviation['ExternalComment']); ?>)</a></li>
              </ul>

              <div class="tab-content">
                <div class="tab-pane active" id="<?php echo h($formPaneId); ?>">
                  <div style="position: relative; border-top: 1px solid #ddd;">
                    <?php
                    if ($selectedDeviationMode === 'edit') {
                      echo $this->element('/application/deviation_edit', array('deviation' => $deviation, 'akey' => $akey));
                    } elseif ($selectedDeviationMode === 'view') {
                      echo $this->Html->link(
                        __('<i class="icon-download-alt"></i> Download PDF'),
                        array('controller' => 'deviations', 'ext' => 'pdf', 'action' => 'download_deviation', $deviation['id']),
                        array('escape' => false, 'class' => 'btn btn-small btn-info topright')
                      );
                      echo $this->element('/application/deviation_view', array('deviation' => $deviation, 'akey' => $akey));
                    }
                    ?>
                  </div>
                </div>

                <div class="tab-pane" id="<?php echo h($commentsPaneId); ?>">
                  <div class="row-fluid">
                    <div class="span12">
                      <br>
                      <div class="amend-form">
                        <h5 class="text-center"><u>COMMENTS/QUERIES</u></h5>
                        <div class="row-fluid">
                          <div class="span8">
                            <?php echo $this->element('comments/list', ['comments' => $deviation['ExternalComment'], 'show' => false]) ?>
                          </div>
                          <div class="span4 lefty">
                            <?php
                            echo $this->element('comments/add', [
                              'model' => [
                                'model_id' => $application['Application']['id'], 'foreign_key' => $deviation['id'],
                                'model' => 'Deviation', 'category' => 'external', 'url' => 'add_dev_external'
                              ]
                            ])
                            ?>
                          </div>
                        </div>
                      </div>
                    </div><!--/span-->
                  </div><!--/row-->
                </div>
              </div>
            </div>
          </td>
        </tr>
      <?php } ?>
    <?php } ?>
  </tbody>
</table>

<script type="text/javascript">
  $.expander.defaults.slicePoint = 170;
  $(function() {
    var $tabLinks = $('.deviation-tab a');
    if (!$tabLinks.length) return;

    $tabLinks.click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $tabLinks.on("shown", function(e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('deviationTab', id)
    });

    var deviationTab = localStorage.getItem('deviationTab');
    if (deviationTab != null) {
      $tabLinks.filter('[href="' + deviationTab + '"]').tab('show');
    }

    var hashTab = $tabLinks.filter('[href="' + location.hash + '"]');
    if (hashTab.length) {
      hashTab.tab('show');
    }
  });
</script>

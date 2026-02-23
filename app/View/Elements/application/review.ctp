<?php
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));

$formatReviewSummary = function ($content) {
  $content = trim((string)$content);
  if ($content === '') {
    return '<span class="muted">No summary provided.</span>';
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
    return '<span class="muted">No comments available.</span>';
  }

  $items = array();
  foreach ((array)$comments as $comment) {
    $subject = !empty($comment['subject']) ? trim((string)$comment['subject']) : '';
    $content = !empty($comment['content']) ? trim((string)$comment['content']) : '';
    $sender = !empty($comment['sender']) ? trim((string)$comment['sender']) : '';

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
    return '<span class="muted">No comments available.</span>';
  }

  return implode('', $items);
};
?>
<div class="marketing">
  <div class="row-fluid">
    <div class="span12">
      <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
      <!-- <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments Form</h3> -->
    </div>
  </div>
  <hr class="soften" style="margin: 10px 0px;">
</div>

<div class="row-fluid">
  <div class="span3">
    <?php
    echo $this->Html->link(
      __('<i class="icon-stethoscope"></i> Add Clinical Assessment'),
      array('controller' => 'reviews', 'action' => 'add', $application['Application']['id'], 'clinical'),
      array('escape' => false, 'class' => 'btn btn-primary')
    );
    ?>
  </div>
  <div class="span3">
    <?php
    echo $this->Html->link(
      __('<i class="icon-tint"></i> Add Non-Clinical Assessment'),
      array('controller' => 'reviews', 'action' => 'add', $application['Application']['id'], 'non-clinical'),
      array('escape' => false, 'class' => 'btn btn-success')
    );
    ?>
  </div>
  <div class="span3">
    <?php
    echo $this->Html->link(
      __('<i class="icon-medkit"></i> Add Quality Assessment'),
      array('controller' => 'reviews', 'action' => 'add', $application['Application']['id'], 'quality'),
      array('escape' => false, 'class' => 'btn btn-info')
    );
    ?>
  </div>
  <div class="span3">
    <?php
    echo $this->Html->link(
      __('<i class="icon-list-ol"></i> Add Statistical Assessment'),
      array('controller' => 'reviews', 'action' => 'add', $application['Application']['id'], 'statistical'),
      array('escape' => false, 'class' => 'btn btn-warning')
    );
    ?>
  </div>
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
        <?php
        foreach ($application['Review'] as $akey => $rreview) {
        ?>
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

                <!-- Start -->
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

                <!-- End -->
              <?php } ?>
            </td>
            <td><?php echo $formatReviewComments($rreview['InternalComment']); ?></td>
            <td><?php echo $rreview['status'] . "<br>" . $rreview['type'] ?></td>
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
                    array('action' => 'view', $application['Application']['id'], 'rreview_view' => $rreview['id']),
                    array('escape' => false)
                  );
                  echo "&nbsp;";
                } else {
                  echo $this->Html->link(
                    '<span class="label label-info"> View </span>',
                    array('action' => 'view', $application['Application']['id'], 'rreview_view' => $rreview['id']),
                    array('escape' => false)
                  );
                  echo "&nbsp;";
                }
              }


              if (($redir == 'manager')) {
                // echo $this->Form->postLink(__('<label class="label label-inverse">Unsubmit</label>'), array('controller' => 'rreviews', 'action' => 'unsubmit', $rreview['id']), array('escape' => false), __('Are you sure you want to unsubmit the rreview # %s? The applicant will be able to edit it.', $rreview['id']));
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
if (isset($this->params['named']['rreview_view']))  $cid = $this->params['named']['rreview_view'];

if (isset($this->params['named']['rreview_view'])) {
  foreach ($application['Review'] as $akey => $rreview) {
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
            if ($rreview['status'] == 'Unsubmitted') {
              echo $this->element('/application/rreview_edit', array('rreview' => $rreview, 'akey' => $akey));
            } else {
              echo $this->Html->link(
                __('<i class="icon-download-alt"></i> Download PDF'),
                array('controller' => 'reviews', 'ext' => 'pdf', 'action' => 'download_assessment', $rreview['id']),
                array('escape' => false, 'class' => 'btn btn-small btn-info topright')
              );
              echo $this->element('/application/rreview_view', array('rreview' => $rreview, 'akey' => $akey));
            }
            ?>
          </div>
        </div>
       
        <div class="tab-pane" id="rreview_summary">
          <div style="position: relative; border-top: 1px solid #ddd;">
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download PDF'),
              array('controller' => 'reviews', 'ext' => 'pdf', 'action' => 'download_summary', $rreview['id']),
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
                        <?php echo $this->element('comments/list_expandable', ['comments' => $rreview['InternalComment'], 'category' => false]) ?>
                      </div>

                    </div>
                  </div>
                  <div class="tab-pane " id="rreview_comments_add">
                    <div class="row-fluid">
                      <div class="span12">
                        <?php
                        echo $this->element('comments/add', [
                          'model' => [
                            'model_id' => $application['Application']['id'], 'foreign_key' => $rreview['id'],
                            'model' => 'Review', 'type' => 51, 'category' => 'internal', 'url' => 'add_review_internal'
                          ]
                        ])
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div><!--/span-->
          </div><!--/row-->
        </div>

      </div>

<?php
    }
  }
}
?>

<script text="type/javascript">
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

    bindTabGroup('#rreview_tab', 'rreviewTab');
    bindTabGroup('#rreview_comments_tab', 'rreviewCommentTab');
  });
</script>

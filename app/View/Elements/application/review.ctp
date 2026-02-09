<?php
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));
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
    <table class="table  table-bordered" style="margin-bottom: 1px;">

      <thead>
        <tr>
          <th style="width:3%">ID</th>
          <th style="width:3%">Recommendation</th>
          <th style="width: 40%;">Comments</th>
          <th style="width:3%">Status &amp; Type</th>
          <th style="width:3%">User</th>
          <th style="width:3%">Created</th>
          <th style="width:3%"><?php echo __('Actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($application['Review'] as $akey => $rreview) {
        ?>
          <tr>
            <td><?php echo $rreview['id'] ?></td>
            <td>
              <?php
              if ($rreview['type'] == 'request') {
                echo 'Assigned: ' . $rreview['accepted'] . '<br/>';
              }
              // echo $rreview['summary'] . '<br/>';
              echo $rreview['recommendation'];
              if (!empty($rreview['summary'])) {
              ?>

                <button type="button" class="btn btn-small btn-info" data-toggle="modal" data-target="#myModal_<?php echo $rreview['id']; ?>">
                  View Summary
                </button>

                <!-- Start -->
                <div class="modal fade" id="myModal_<?php echo $rreview['id']; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">

                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Clinical Summary</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <!-- Modal Body -->
                      <div class="modal-body">
                        <?php 
                        
                        echo $rreview['summary'];
                        ?>

                      </div>

                      <!-- Modal Footer -->
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>

                    </div>
                  </div>
                </div>

                <!-- End -->
              <?php } ?>
            </td>
            <td><?php
                foreach ($rreview['InternalComment'] as $iComment) {
                  echo   $iComment['subject'] . "<br>";
                  echo   $iComment['content'] . "<br>";
                  echo   $iComment['sender'] . "<br>";
                  echo "<br>";
                }
                ?></td>
            <td><?php echo $rreview['status'] . "<br>" . $rreview['type'] ?></td>
            <td><?php echo $rreview['User']['name']; ?></td>
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
        <li class="active"><a href="#rreview_form">Assessment Form</a></li>
        <li><a href="#exit_summary">Exit report</a></li>
        <li><a href="#rreview_summary">Summary report</a></li>
        <li><a href="#rreview_comments">Comments (<?php echo count($rreview['InternalComment']); ?>)</a></li>
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
        <div class="tab-pane" id="exit_summary">
          <div style="position: relative; border-top: 1px solid #ddd;">
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download PDF'),
              array('controller' => 'reviews', 'ext' => 'pdf', 'action' => 'download_exit_summary', $rreview['id']),
              array('escape' => false, 'class' => 'btn btn-small btn-info topright')
            );
            echo $this->element('/application/exit_summary', array('rreview' => $rreview, 'akey' => $akey));
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
                <ul id="rreview_tab" class="nav nav-tabs">
                  <li class="active"><a href="#rreview_comment_list">COMMENTS/QUERIES</a></li>
                  <li><a href="#rreview_comments_add">Add Comment</a></li>
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
  $.expander.defaults.slicePoint = 170;
  $(function() {
    //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
    //from mcaz
    $('#rreview_tab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $('#rreview_tab a').on("shown", function(e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('rreviewTab', id)
    });

    var rreviewTab = localStorage.getItem('rreviewTab');
    if (rreviewTab != null) {
      // console.log("select tab");
      // console.log($('#rreview_tab a[href="' + rreviewTab + '"]'));
      $('#rreview_tab a[href="' + rreviewTab + '"]').tab('show');
    }

    var hashTab = $('#rreview_tab a[href="' + location.hash + '"]');
    hashTab && hashTab.tab('show');
    //end mcaz
  });
</script>
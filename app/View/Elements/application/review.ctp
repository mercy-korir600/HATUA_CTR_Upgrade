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

<?php                                                                                                                                                                              
    $myAssessments = array();                                                                                                         
    if (!empty($application['Review'])) {                                                                                             
      foreach ($application['Review'] as $rev) {                                                                                      
        if (!empty($rev['assessment_type']) && $rev['type'] === 'reviewer_comment') {                                                 
          $t = $rev['assessment_type'];                                                                          
          if (!isset($myAssessments[$t]) || $rev['status'] === 'Unsubmitted') {                                                       
            $myAssessments[$t] = $rev;                                                                                                
          }                                                                                                                           
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
                array('action' => 'view', $application['Application']['id'], 'rreview_view' => $existing['id']),                      
                array('escape' => false, 'class' => 'btn ' . $btnConfig['class'] . ' btn-block')                                      
              );                                                                                                                      
            } else {                                                                      
              echo $this->Html->link(                                                                                                 
                __('<i class="icon-check"></i> View %s', $btnConfig['label']),     
                array('action' => 'view', $application['Application']['id'], 'rreview_view' => $existing['id']),                      
                array('escape' => false, 'class' => 'btn btn-block')                                                                  
              );                                                                                                                      
            }                                                                                                                         
          } else {                                                     
            echo $this->Html->link(                                                                                                   
              __('<i class="%s"></i> Add %s', $btnConfig['icon'], $btnConfig['label']),                                               
              array('controller' => 'reviews', 'action' => 'add', $application['Application']['id'], $typeKey),                       
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
  $('a.btn-add-assessment').on('click', function(e) {                                                                           
          var $btn = $(this);                                                                                                         
          if ($btn.hasClass('disabled') || $btn.data('submitted')) {                                                                  
            e.preventDefault();                                                                                                       
            return false;                                                                                                             
          }                                                                                                                           
          $btn.data('submitted', true).addClass('disabled').css('pointer-events', 'none');                                            
                                                                       
          if ($.blockUI) {                                                                                                            
            $.blockUI({ message: '<h5><i class="icon-spinner icon-spin"></i> Initializing assessment form...</h5>' });                
          }                                                                                                                           
        });       
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
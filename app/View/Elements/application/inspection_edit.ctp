<?php
$formatExitReportContent = function ($content) {
  $content = trim((string)$content);
  if ($content === '') {
    return '<span class="muted">No details provided.</span>';
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
?>

<br>
  <div class="row-fluid">
    <div class="span12">      
        <?php     
          if($redir !== 'applicant') {
              echo $this->Html->link(__('<i class="icon-skype"></i> Add Site Inspection'),
                        array('controller' => 'site_inspections', 'action' => 'add', $application['Application']['id']),
                        array('escape' => false, 'class' => 'btn btn-info'));
          }
        ?>
    </div>
  </div>
  
<?php
    if(!empty($application['SiteInspection'])) {
  ?>
  <br>
    <table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Reference No</th>
          <?php if($redir !== 'applicant') { ?>
          <th>Inspector</th>
          <th>Status</th>
          <?php } ?>
          <th>Created</th>
          <th><?php echo __('Actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($application['SiteInspection'] as $akey => $site_inspection) {
        ?>
          <tr>
            <td><?php echo $site_inspection['id'] ?></td>
            <td><?php echo $site_inspection['reference_no'] ?></td>
          <?php if($redir !== 'applicant') { ?>
            <td><?php if(!empty($site_inspection['User']['name']))echo $site_inspection['User']['name'] ?></td>
            <td><p><?php 
                    if($site_inspection['approved'] == 0) echo 'Unsubmitted';
                    elseif($site_inspection['approved'] == 1) echo 'Awaiting Peer Review';
                    elseif($site_inspection['approved'] == 2) { 
                        echo "Approved"." <small class='muted'> by ".$users[($site_inspection['approved_by']) ? $site_inspection['approved_by'] : $site_inspection['user_id']]; 
                      }
                    ?></p>
            </td>
          <?php } ?>
            <td><?php echo $site_inspection['created'] ?></td> 
            <td>
              <?php
                if ($site_inspection['approved'] >= 1) {
                  echo $this->Html->link('<label class="label label-info">View</label>',
                                   array('action' => 'view', $application['Application']['id'], 'inspection_id' => $site_inspection['id']), array('escape'=>false));
                  echo "&nbsp;";
                  echo $this->Html->link(__('<label class="label">PDF</label>'),
                    array('controller' => 'site_inspections', 'ext' => 'pdf', 'action' => 'download_inspection', $site_inspection['id']),
                    array('escape' => false));
                  echo "&nbsp;";

                  if (($this->Session->read('Auth.User.group_id') === '2' or $this->Session->read('Auth.User.group_id') === '6') and 
                          ($site_inspection['user_id'] !== $this->Session->read('Auth.User.id')) and
                          $site_inspection['approved'] !== '2'
                      ) {
                      echo $this->Form->postLink(__('<label class="label label-success">Approve</label>'), array('controller' => 'site_inspections', 'action' => 'approve', $site_inspection['id']), array('escape' => false), __('Are you sure you want to approve site inspection # %s?', $site_inspection['id']));
                  }
                  
                } else {
                  echo $this->Html->link('<span class="label label-success"> Edit </span>',
                     array('action' => 'view', $application['Application']['id'], 'inspection_id' => $site_inspection['id']), array('escape'=>false));
                  echo "&nbsp;";
                  if (($this->Session->read('Auth.User.group_id') === '2' or $site_inspection['user_id'] == $this->Session->read('Auth.User.id'))) {                    
                      echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'site_inspections', 'action' => 'delete', $site_inspection['id']), array('escape' => false), __('Are you sure you want to delete site inspection # %s?', $site_inspection['id']));
                  }
                }
              ?>                      
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php
    }
?>

  <br>
  <hr>

  <?php
  if(isset($this->params['named']['inspection_id'])) {
    foreach ($application['SiteInspection'] as $akey => $site_inspection) {
      if ($site_inspection['id'] == $this->params['named']['inspection_id']) {               
  ?>

  <ul id="assessment_tab" class="nav nav-tabs">
    <?php if($redir !== 'applicant') { ?><li class="active"><a href="#assessment_form" data-toggle="tab">Assessment Form</a></li> <?php } ?>
    <li><a href="#summary_report" data-toggle="tab">Summary Report</a></li>
    <?php if($redir !== 'applicant') { ?><li><a href="#inspector_exit_report" data-toggle="tab">Inspector Exit Report</a></li> <?php } ?>
    <?php if($redir !== 'applicant') { ?><li><a href="#internal_comments" data-toggle="tab">Internal Comments (<?php echo count($site_inspection['InternalComment']); ?>)</a></li> <?php } ?>
    <li><a href="#external_comments" data-toggle="tab">PI Comments (<?php echo count($site_inspection['ExternalComment']); ?>)</a></li>
  </ul>

  <div class="tab-content">
    <?php if($redir !== 'applicant') { ?>
    <div class="tab-pane active" id="assessment_form">
      <div style="position: relative; border-top: 1px solid #ddd;">        
        <?php
          echo $this->Html->link(__('<i class="icon-download-alt"></i> Download PDF'),
                  array('controller' => 'site_inspections', 'ext' => 'pdf', 'action' => 'download_assessment', $site_inspection['id']),
                  array('escape' => false, 'class' => 'btn btn-small btn-info topright'));
          echo $this->element('/application/inspection_edit_form', array('site_inspection' => $site_inspection, 'akey' => $akey));
        ?>
      </div>
    </div>
    <?php } ?>
    <div class="tab-pane" id="summary_report">
      <div style="position: relative; border-top: 1px solid #ddd;">  
        <?php
          echo $this->Html->link(__('<i class="icon-download-alt"></i> Download PDF'),
                  array('controller' => 'site_inspections', 'ext' => 'pdf', 'action' => 'download_summary', $site_inspection['id']),
                  array('escape' => false, 'class' => 'btn btn-small btn-info topright'));
          
          if ($site_inspection['approved'] == 2 && $site_inspection['sent_to_pi'] == 0 && ($this->Session->read('Auth.User.group_id') === '2' or $this->Session->read('Auth.User.group_id') === '6')) {
            echo $this->Html->link(__('<i class="icon-envelope-alt"></i> Send Report to PI'),
                  array('controller' => 'site_inspections', 'action' => 'send_to_pi', $site_inspection['id']),
                  array('escape' => false, 'class' => 'btn btn-small btn-warning'));
          } elseif ($site_inspection['sent_to_pi'] == 1 && $redir !== 'applicant') {
            echo "<p class='text-success'>Email sent to PI</p>";
          }
          
          echo $this->element('/application/inspection_summary', array('site_inspection' => $site_inspection, 'akey' => $akey));
        ?>
      </div>
    </div>

    <?php if($redir !== 'applicant') { ?>
    <div class="tab-pane" id="inspector_exit_report">
      <div style="position: relative; border-top: 1px solid #ddd; padding-top: 10px;">
        <table class="table table-bordered table-condensed">
          <tbody>
            <tr>
              <td class="table-label required" style="width: 25%;"><p>Inspector</p></td>
              <td>
                <?php
                if (!empty($site_inspection['User']['name'])) {
                  echo h($site_inspection['User']['name']);
                } elseif (!empty($site_inspection['User']['username'])) {
                  echo h($site_inspection['User']['username']);
                } elseif (!empty($site_inspection['user_id'])) {
                  echo 'Inspector #' . (int)$site_inspection['user_id'];
                } else {
                  echo '<span class="muted">-</span>';
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="table-label required"><p>Recommendation(s)</p></td>
              <td><?php echo $formatExitReportContent($site_inspection['conclusion']); ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Exit Report</p></td>
              <td><?php echo $formatExitReportContent($site_inspection['summary_report']); ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Attachment(s)</p></td>
              <td>
                <?php if (!empty($site_inspection['Attachment'])) { ?>
                  <?php foreach ($site_inspection['Attachment'] as $attachment) { ?>
                    <p>
                      <?php
                      echo $this->Html->link(
                        __($attachment['basename']),
                        array(
                          'controller' => 'attachments',
                          'action' => 'download',
                          $attachment['id'],
                          'full_base' => true
                        ),
                        array('class' => 'btn btn-info btn-small')
                      );
                      ?>
                      <?php if (!empty($attachment['created'])) { ?>
                        <small class="muted"> - <?php echo h($attachment['created']); ?></small>
                      <?php } ?>
                    </p>
                  <?php } ?>
                <?php } else { ?>
                  <span class="muted">No attachment uploaded.</span>
                <?php } ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php } ?>

    <?php if($redir !== 'applicant') { ?>
    <div class="tab-pane" id="internal_comments">
      <div class="row-fluid">
          <div class="span12">
          <br>
            <div class="amend-form">
              <h5 class="text-center"><u>COMMENTS/QUERIES</u></h5>
              <div class="row-fluid">
                <div class="span8">    
                  <?php echo $this->element('comments/list', ['comments' => $site_inspection['InternalComment'],'show'=>false]) ?> 
                </div>
                <div class="span4 lefty">
                  <?php  
                       echo $this->element('comments/add', [
                                'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $site_inspection['id'], 
                                            'model' => 'SiteInspection', 'category' => 'internal', 'url' => 'add_si_internal']]) 
                  ?>
                </div>
              </div>
            </div>
          </div><!--/span-->
      </div><!--/row-->
    </div>
    <?php } ?>

    <div class="tab-pane" id="external_comments">

      <div class="row-fluid">
          <div class="span12">
          <br>
            <div class="amend-form">
              <h5 class="text-center"><u>COMMENTS/QUERIES</u></h5>
              <div class="row-fluid">
                <div class="span8">    
                  <?php echo $this->element('comments/list', ['comments' => $site_inspection['ExternalComment'],'show'=>false]) ?> 
                </div>
                <div class="span4 lefty">
                  <?php  
                       echo $this->element('comments/add', [
                                // 'model' => ['model_id' => $site_inspection['id'], 'foreign_key' => $site_inspection['id'], 
                                'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $site_inspection['id'], 
                                            'model' => 'SiteInspection', 'category' => 'external', 'url' => 'add_si_external']]) 
                  ?>
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

    //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
    //from mcaz
    var $assessmentTabLinks = $('#assessment_tab a');
    $assessmentTabLinks.off('.inspectionTabs');
    $assessmentTabLinks.on('click.inspectionTabs', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $assessmentTabLinks.on("shown.inspectionTabs shown.bs.tab.inspectionTabs", function (e) {
        var id = $(e.target).attr("href");
        localStorage.setItem('inspectionAssessmentTab', id);
    });

    var assessmentTab = localStorage.getItem('inspectionAssessmentTab');
    if (assessmentTab != null && $assessmentTabLinks.filter('[href="' + assessmentTab + '"]').length) {
        $assessmentTabLinks.filter('[href="' + assessmentTab + '"]').tab('show');
    }

    if (location.hash && $assessmentTabLinks.filter('[href="' + location.hash + '"]').length) {
        $assessmentTabLinks.filter('[href="' + location.hash + '"]').tab('show');
    }
    //end mcaz
});
</script>

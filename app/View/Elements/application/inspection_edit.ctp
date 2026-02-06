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
                  echo $this->Html->link('<span class="label label-success"> View </span>',
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
    <?php if($redir !== 'applicant') { ?><li class="active"><a href="#assessment_form">Assessment Form</a></li> <?php } ?>
    <li><a href="#summary_report">Summary Report</a></li>
    <?php if($redir !== 'applicant') { ?><li><a href="#internal_comments">Internal Comments (<?php echo count($site_inspection['InternalComment']); ?>)</a></li> <?php } ?>
    <li><a href="#external_comments">PI Comments (<?php echo count($site_inspection['ExternalComment']); ?>)</a></li>
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
          echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(
            'name' => 'saveChanges',
            'class' => 'btn btn-success btn-block mapop',
            'id' => 'SiteInspectionSaveChanges', 'title'=>'Save & continue editing',
            'data-content' => 'Save changes to form without submitting it.
                                        The form will still be available for further editing.',
            'div' => false,
          ));
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
$.expander.defaults.slicePoint = 170;
$(function() {
    //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
    //from mcaz
    $('#assessment_tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('#assessment_tab a').on("shown", function (e) {
        var id = $(e.target).attr("href");
        localStorage.setItem('assessmentTab', id)
    });

    var assessmentTab = localStorage.getItem('assessmentTab');
    if (assessmentTab != null) {
        // console.log("select tab");
        // console.log($('#assessment_tab a[href="' + assessmentTab + '"]'));
        $('#assessment_tab a[href="' + assessmentTab + '"]').tab('show');
    }

    var hashTab = $('#assessment_tab a[href="' + location.hash + '"]');
    hashTab && hashTab.tab('show');
    //end mcaz
});
</script>
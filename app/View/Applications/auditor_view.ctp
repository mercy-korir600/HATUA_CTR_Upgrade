<?php
  $this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
  $this->assign('MyApplications', 'active');
  $this->Html->script('ckeditor/ckeditor', array('inline' => false));
  $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
  $this->Html->script('jquery.blockUI.js', array('inline' => false));
?>
<div class="tabbable tabs-left">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Application Overview</a></li>
    <li><a href="#tab-timeline" data-toggle="tab">Study Timeline</a></li>
     <li><a href="#tab-inspections" data-toggle="tab">Site Inspections (<?php echo count($application['SiteInspection']); ?>)</a></li>                                                                                                                    
        <li><a href="#tab-saes" data-toggle="tab">SAE/SUSAR (<?php echo count($application['Sae']); ?>)</a></li>                                                                                                                                             
        <li><a href="#tab-deviations" data-toggle="tab">Protocol Deviations (<?php echo count($application['Deviation']); ?>)</a></li>  
    <li><a href="#tab-inspector" data-toggle="tab">Reviewer Comments</a></li>
    <li><a href="#tab-documents" data-toggle="tab">Supporting Documents</a></li>
  </ul>
  
                                                                                                                                                                                                                                                          
      

  <div class="tab-content my-tab-content">
    <div class="tab-pane active" id="tab1">
      <div class="row-fluid">
        <h4 class="text-success">
          Submitted Application: <?php echo $application['Application']['protocol_no']; ?> &mdash;
          <small>Created on: <?php echo date('d-m-Y h:i a', strtotime($application['Application']['created'])); ?></small>
        </h4>
      </div>
<?php $this->end(); ?>

<?php $this->start('form-header'); ?>
  <div class="span12">
  <?php
    echo $this->Form->create('Application', array(
      'class' => 'form-horizontal',
      'id' => 'auditorFakeForm',
      'inputDefaults' => array('disabled' => true) 
    ));
    echo $this->Form->input('id', array('value' => $application['Application']['id']));
  ?>
<?php $this->end(); ?>

<?php $this->start('form-actions'); ?>
<div class="form-actions" style="margin-top: 0px; padding-left: 10px;">
  <?php
    echo $this->Html->link(__('<i class="icon-download-alt"></i> Download Application PDF'),
      array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
      array('escape' => false, 'class' => 'btn btn-primary pull-right', 'style'=>'margin-right: 10px;'));
  ?>
</div>
<?php $this->end(); ?>                                                                                                                                                                                                                          
    <?php $this->start('tabs'); ?>                                                                                                                                                                                                                           
    <ul>                                                                                                                                                                                                                                                     
      <li><a href="#tabs-1">1. Abstract & Title</a></li>                                                                                                                                                                                                     
      <li><a href="#tabs-2">2. Investigators</a></li>                                                                                                                                                                                                        
      <li><a href="#tabs-3">3. Sponsors</a></li>                                                                                                                                                                                                             
      <li><a href="#tabs-4">4. Participants</a></li>                                                                                                                                                                                                         
      <li><a href="#tabs-5">5. Sites</a></li>                                                                                                                                                                                                                
      <li><a href="#tabs-6">6. Placebo</a></li>                                                                                                                                                                                                              
      <li><a href="#tabs-7">7. Criteria</a></li>                                                                                                                                                                                                             
      <li><a href="#tabs-8">8. Scope</a></li>                                                                                                                                                                                                                
      <li><a href="#tabs-9">9. Design</a></li>                                                                                                                                                                                                               
      <li><a href="#tabs-15">10. Study Budget</a></li>                                                                                                                                                                                                       
      <li><a href="#tabs-10">11. Organizations</a></li>                                                                                                                                                                                                      
      <li><a href="#tabs-11">12. Other details</a></li>                                                                                                                                                                                                      
      <li><a href="#tabs-12">13. Checklist </a></li>                                                                                                                                                                                                         
      <li><a href="#tabs-13">14. Declaration</a></li>                                                                                                                                                                                                        
      <li><a href="#tabs-14">15. Notifications</a></li>                                                                                                                                                                                                      
    </ul>                                                                                                                                                                                                                                                    
    <?php $this->end(); ?>  

 <?php $this->start('view-rightbar'); ?>
      </div>
    <?php $this->end(); ?>
  
    <?php $this->start('endjs'); ?>
    </div> 
    <div class="tab-pane" id="tab-timeline">
      <h4 class="text-info">Study Timeline & Current Status</h4>
      <hr>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Stage</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($application['ApplicationStage'] as $stage): ?>
            <tr>
              <td><strong><?php echo h($stage['stage']); ?></strong></td>
              <td>
                <span class="label <?php echo $stage['status'] === 'Complete' ? 'label-success' : 'label-warning'; ?>">
                  <?php echo h($stage['status']); ?>
                </span>
              </td>
              <td><?php echo !empty($stage['start_date']) ? date('d-m-Y', strtotime($stage['start_date'])) : '-'; ?></td>
              <td><?php echo !empty($stage['end_date']) ? date('d-m-Y', strtotime($stage['end_date'])) : '-'; ?></td>
              <td><?php echo h($stage['comment']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
                                                                                                                                                                                                                  
        <div class="tab-pane" id="tab-inspections">                                                                                                                                                                                                          
          <h4 class="text-info">Site Inspections</h4>                                                                                                                                                                                                        
          <hr>                                                                                                                                                                                                                                               
          <div class="row-fluid">                                                                                                                                                                                                                            
            <div class="span12">                                                                                                                                                                                                                             
              <?php echo $this->element('/application/inspection_edit'); ?>                                                                                                                                                                                  
            </div>                                                                                                                                                                                                                                           
          </div>                                                                                                                                                                                                                                             
        </div>                                                                                                                                                                                                                                               
                                                                                                                                                                                                                                                             
        <!-- READ-ONLY SAE LOGS TAB -->                                                                                                                                                                                                                      
        <div class="tab-pane" id="tab-saes">                                                                                                                                                                                                                 
          <h4 class="text-info">SAE/SUSAR Logs</h4>                                                                                                                                                                                                          
          <hr>                                                                                                                                                                                                                                               
          <div class="row-fluid">                                                                                                                                                                                                                            
            <div class="span12">                                                                                                                                                                                                                             
              <table class="table table-bordered table-striped">                                                                                                                                                                                             
                <thead>                                                                                                                                                                                                                                      
                  <tr>                                                                                                                                                                                                                                       
                    <th>Id</th>                                                                                                                                                                                                                              
                    <th>Reference No.</th>                                                                                                                                                                                                                   
                    <th>Report Type</th>                                                                                                                                                                                                                     
                    <th>Patient Initials</th>                                                                                                                                                                                                                
                    <th>Created Date</th>                                                                                                                                                                                                                    
                    <th class="actions">Actions</th>                                                                                                                                                                                                         
                  </tr>                                                                                                                                                                                                                                      
                </thead>                                                                                                                                                                                                                                     
                <tbody>                                                                                                                                                                                                                                      
                  <?php if (!empty($application['Sae'])): ?>                                                                                                                                                                                                 
                    <?php foreach ($application['Sae'] as $sae): ?>                                                                                                                                                                                          
                      <tr>                                                                                                                                                                                                                                   
                        <td><?php echo h($sae['id']); ?></td>                                                                                                                                                                                                
                        <td><?php echo h($sae['reference_no']); ?></td>                                                                                                                                                                                      
                        <td><?php echo h($sae['report_type']); ?></td>                                                                                                                                                                                       
                        <td><?php echo h($sae['patient_initials']); ?></td>                                                                                                                                                                                  
                        <td><?php echo date('d-M-Y', strtotime($sae['created'])); ?></td>                                                                                                                                                                    
                        <td class="actions">                                                                                                                                                                                                                 
                          <?php if ($sae['approved'] > 0): ?>                                                                                                                                                                                                
                               <?php echo $this->Html->link(__('<label class="label label-info">View</label>'), array('controller' => 'saes', 'action' => 'view', $sae['id'], 'inspector' => true, 'auditor' => false), array('target' => '_blank', 'escape' => false)); ?>                             
                          <?php endif; ?>                                                                                                                                                                                                                    
                        </td>                                                                                                                                                                                                                                
                      </tr>                                                                                                                                                                                                                                  
                    <?php endforeach; ?>                                                                                                                                                                                                                     
                  <?php else: ?>                                                                                                                                                                                                                             
                    <tr>                                                                                                                                                                                                                                     
                      <td colspan="6" class="text-center"><em>No SAE logs found for this protocol.</em></td>                                                                                                                                                 
                    </tr>                                                                                                                                                                                                                                    
                  <?php endif; ?>                                                                                                                                                                                                                            
                </tbody>                                                                                                                                                                                                                                     
              </table>                                                                                                                                                                                                                                       
            </div>                                                                                                                                                                                                                                           
          </div>                                                                                                                                                                                                                                             
        </div>                    
                                                                                                                                                                                                     
        <div class="tab-pane" id="tab-deviations">                                                                                                                                                                                                           
          <h4 class="text-info">Protocol Deviations</h4>                                                                                                                                                                                                     
          <hr>                                                                                                                                                                                                                                               
          <div class="row-fluid">                                                                                                                                                                                                                            
            <div class="span12">                                                                                                                                                                                                                             
              <?php echo $this->element('application/deviation'); ?>                                                                                                                                                                                         
            </div>                                                                                                                                                                                                                                           
          </div>                                                                                                                                                                                                                                             
        </div>              

    <div class="tab-pane" id="tab-inspector">
      <h4 class="text-info">Reviewer Comments & Notes</h4>
      <hr>
      <?php 
        $inspector_reviews = array();
        foreach ($application['Review'] as $rev) {
          if ($rev['type'] === 'reviewer_comment') {
            $inspector_reviews[] = $rev;
          }
        }
      ?>
      <?php if (!empty($inspector_reviews)): ?>
        <?php foreach ($inspector_reviews as $idx => $review): ?>
          <div class="well">
            <h5>
              Comment #<?php echo $idx + 1; ?> &mdash; 
              <small class="muted">Submitted by <?php echo h($review['User']['name']); ?> on <?php echo date('d-m-Y H:i', strtotime($review['created'])); ?></small>
            </h5>
            <p><strong>Recommendation:</strong> <?php echo h($review['recommendation']); ?></p>
            <div class="comment-text">
              <?php echo $review['text']; ?>
            </div>
            
            <?php if (!empty($review['ExternalComment'])): ?>
              <h6>PI Response:</h6>
              <ul>
                <?php foreach ($review['ExternalComment'] as $comment): ?>
                  <li>
                    <strong><?php echo h($comment['sender']); ?></strong>: <?php echo h($comment['comment']); ?> 
                    <small class="muted">(<?php echo date('d-m-Y', strtotime($comment['created'])); ?>)</small>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-info">No reviewer comments found for this protocol.</div>
      <?php endif; ?>
    </div>

    <div class="tab-pane" id="tab-documents">
      <h4 class="text-info">Supporting Documents (Read-Only)</h4>
      <hr>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Document Type / Name</th>
            <th>Description</th>
            <th>Uploaded Date</th>
            <th class="actions">Actions</th>
          </tr>
        </thead>
        <tbody>
     
          <?php if (!empty($application['Protocol'])): ?>
            <?php foreach ($application['Protocol'] as $doc): ?>
              <tr>
                <td><strong>Protocol Version</strong>: <?php echo h($doc['file_name']); ?></td>
                <td>Version No: <?php echo h($doc['version_no']); ?>, Date: <?php echo h($doc['date_of_protocol']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($doc['created'])); ?></td>
                <td>
                  <?php echo $this->Html->link('<i class="icon-download-alt"></i> Download', array('controller' => 'attachments', 'action' => 'download', $doc['id'], 'auditor' => false), array('escape' => false, 'class' => 'btn btn-mini btn-info')); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($application['Cv'])): ?>
            <?php foreach ($application['Cv'] as $doc): ?>
              <tr>
                <td><strong>Investigator CV</strong>: <?php echo h($doc['file_name']); ?></td>
                <td>CV of: <?php echo h($doc['name']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($doc['created'])); ?></td>
                <td>
                  <?php echo $this->Html->link('<i class="icon-download-alt"></i> Download', array('controller' => 'attachments', 'action' => 'download', $doc['id'], 'auditor' => false), array('escape' => false, 'class' => 'btn btn-mini btn-info')); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($application['Finance'])): ?>
            <?php foreach ($application['Finance'] as $doc): ?>
              <tr>
                <td><strong>Financial Declaration</strong>: <?php echo h($doc['file_name']); ?></td>
                <td><?php echo h($doc['description']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($doc['created'])); ?></td>
                <td>
                  <?php echo $this->Html->link('<i class="icon-download-alt"></i> Download', array('controller' => 'attachments', 'action' => 'download', $doc['id'], 'auditor' => false), array('escape' => false, 'class' => 'btn btn-mini btn-info')); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (!empty($application['Attachment'])): ?>
            <?php foreach ($application['Attachment'] as $doc): ?>
              <tr>
                <td><strong>Attachment</strong>: <?php echo h($doc['file_name']); ?></td>
                <td>Category: <?php echo h($doc['category']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($doc['created'])); ?></td>
                <td>
                  <?php echo $this->Html->link('<i class="icon-download-alt"></i> Download', array('controller' => 'attachments', 'action' => 'download', $doc['id'], 'auditor' => false), array('escape' => false, 'class' => 'btn btn-mini btn-info')); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function() {
  $("#tabs").tabs();
});
</script>
<?php $this->end(); ?>
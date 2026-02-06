<?php
  $this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
      $this->assign('Applications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
    ?>
    <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
          <li><a href="#tab2" data-toggle="tab">My Reviews <small>(<?php echo count($application['Review']);?>)</small></a></li>
          <li><a href="#tab3" data-toggle="tab">Manager Reviews <small>(<?php echo count($application['ManagerReview']);?>)</small></a></li>
      </ul>
      <div class="tab-content my-tab-content">
        <div class="tab-pane active" id="tab1">
          <!-- content for tab1 comes here -->

  <div class="row-fluid">
    <?php if($application['Application']['submitted'] == 1 ) { ?>
      <h4 class="text-success">
       Submitted Application :  (<?php echo $application['Application']['protocol_no'];?>) &mdash;
       <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } else { ?>
      <h4 class="text-success">
        UnSubmitted Application :  &mdash; <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } ?>
  </div>
<?php $this->end();?>


<?php $this->start('form-header'); ?>
  <div class="span10">
  <?php
      echo $this->Form->create('Application', array(
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
      echo $this->Form->input('id');
    ?>
<?php $this->end();?>

<?php
  $this->start('form-actions');
?>
 <!-- content for form actions -->
<?php
  $this->end();
?>

<?php $this->start('tabs'); ?>
<ul>
  <li><a href="#tabs-1">1. Abstract</a></li>
  <li><a href="#tabs-2">2. Investigator</a></li>
  <li><a href="#tabs-3">3. Sponsor</a></li>
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

<!-- START RIGHTBAR -->
<?php $this->start('view-rightbar'); ?>
  </div>
  <div class="span2">
    <div class="form-actions"  style="margin-top: 0px; margin-bottom: 0px; padding-left: 10px;">
    <?php
       echo $this->Html->link(__('<i class="icon-download-alt"></i> <br> <span><strong>Download PDF</strong></span>'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
    ?>
  </div>
</div>
<?php $this->end();  ?>
<!-- END RIGHTBAR -->

<?php $this->start('endjs'); ?>
  </div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab2">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/review'); ?>
        </div>
      </div>
    </div>


    <div class="tab-pane" id="tab3">
        <div class="marketing">
             <div class="row-fluid">
                <div class="span12">
                   <h3 class="text-info">The Expert Committee on Clinical Trials</h3>
                   <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments</h3>
                </div>
             </div>
              <hr class="soften" style="margin: 10px 0px;">
        </div>
        <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no'];?></p>
        <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title'];?></p>
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-success">Reviewer's Comments
              <?php
                echo $this->Html->link(__('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
                  array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
                  array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
                ?>
              </h4>
            <?php
                $counter = 0;
                foreach ($application['ManagerReview'] as $review) {
                   $counter++;
                   echo "<hr><span class=\"badge badge-success\">".$counter."</span> <small class='muted'>created on: ".date('d-m-Y H:i:s', strtotime($review['created']))."</small>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['text']."</div>";
                   // echo "<br>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['recommendation']."</div>";
                }
            ?>
          </div>
       </div>


       <?php
          //Reviews limited to ppb_comment already
            $var = Hash::extract($application, 'ManagerReview.{n}[type=ppb_comment]');
            $rid = null;
            if(!empty($var)) $rid = min($var);
       ?>
        <ul id="reviewer_tab" class="nav nav-tabs">
          <li class="active"><a href="#external_rev_comments">PI Comments (<?php echo count($rid['ExternalComment']); ?>)</a></li>
          <?php if($redir !== 'applicant') { ?><li><a href="#internal_rev_comments">Internal Comments (<?php echo count($rid['InternalComment']); ?>)</a></li> <?php } ?>
        </ul>

        <div class="tab-content">
          <div class="tab-pane active" id="external_rev_comments">
              <div class="row-fluid">
                <div class="span12">
                  <br>
                    <div class="amend-form">
                      <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
                      <div class="row-fluid">
                        <div class="span8">    
                          <?php                       
                            // debug($rid);
                            if(!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['ExternalComment'],'show'=>false]);
                          ?> 
                        </div>
                        <div class="span4 lefty">
                        <?php  
                            //----------Manager can't respond directly to Applicant---------------------------------
                            // if(!empty($rid))  echo $this->element('comments/add', [
                            //              'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],   
                            //                          'model' => 'Review', 'category' => 'external', 'url' => 'add_review_response']]) 
                        ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
          </div>

          <div class="tab-pane" id="internal_rev_comments">
              <div class="row-fluid">
                <div class="span12">
                  <br>
                    <div class="amend-form">
                      <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
                      <div class="row-fluid">
                        <div class="span8">    
                          <?php                       
                            // debug($rid);
                            if(!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['InternalComment'],'show'=>false]);

                            //NEW*** Bring in all the assessment comments
                            $rcas = Hash::extract($application, 'Review.{n}[type=reviewer_comment]');
                            if(!empty($rcas)) {
                              echo "<hr>";
                              echo "<h4 class='text-success' style='text-align: center; text-decoration: underline'>Assessment comments</h4>";
                              foreach ($rcas as $rca) {
                                echo $this->element('comments/list', ['comments' => $rca['InternalComment'],'show'=>false]);
                              }
                            }
                            //end
                          ?> 
                        </div>
                        <div class="span4 lefty">
                        <?php  
                            if(!empty($rid))  echo $this->element('comments/add', [
                                         'model' => ['model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],   
                                                     'model' => 'Review', 'category' => 'internal', 'url' => 'add_internal_review_response']]) 
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

<script text="type/javascript">
$.expander.defaults.slicePoint = 170;
$(function() {
  $( "#tabs" ).tabs({
      cookie: {
        expires: 1
      }
  });

  //https://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh
  //from mcaz
  $('#reviewer_tab a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
  });

  $('#reviewer_tab a').on("shown", function (e) {
      var id = $(e.target).attr("href");
      localStorage.setItem('assessmentTab', id)
  });

  var assessmentTab = localStorage.getItem('assessmentTab');
  if (assessmentTab != null) {
      // console.log("select tab");
      // console.log($('#reviewer_tab a[href="' + assessmentTab + '"]'));
      $('#reviewer_tab a[href="' + assessmentTab + '"]').tab('show');
  }

  var hashaTab = $('#reviewer_tab a[href="' + location.hash + '"]');
  hashaTab && hashaTab.tab('show');

  $(".morecontent").expander();
  $('#ReviewText').ckeditor();
  $('#ReviewRecommendation').ckeditor();
});
</script>
<?php $this->end();?>

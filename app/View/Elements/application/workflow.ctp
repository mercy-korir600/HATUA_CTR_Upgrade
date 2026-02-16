<div class="row-fluid">
  <div class="span12">

    <?php echo $this->fetch('header'); ?>
    <?php
          $s1 = $s2 = $s3 = $s4 =  $s5 =  $s6 =  $s7 =  $s8 =  $s9 =  '';
          if (isset($this->request->params['named']['stages'])) {
            if($this->request->params['named']['stages'] == 'Screening') {
              $s1 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'ScreeningSubmission') {
              $s2 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'Assign') {
              $s3 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'Review') {
              $s4 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'ReviewSubmission') {
              $s5 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'FinalDecision') {
              $s6 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'AnnualApproval' && $this->request->params['named']['status'] == 'Current') {
              $s7 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'AnnualApproval' && $this->request->params['named']['status'] == 'Pending') {
              $s8 = 'btn-primary';
            } elseif ($this->request->params['named']['stages'] == 'AnnualApproval' && $this->request->params['named']['status'] == 'Expired') {
              $s9 = 'btn-primary';
            }
          } 
    ?>

    <div class="row-fluid">       
      <div class="span12">
      <?php
        echo $this->Form->create('Application', array(
          'url' => array_merge(array('action' => 'workflow'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F59E'),
        ));
      ?>
      <div class="row-fluid">
        <div class="span12">
          <table class="table table-condensed" style="margin-bottom: 2px;">
          <tbody>
            <tr>
              <td colspan="6" style="text-align: center;"><h4 class="text-warning">Application Stages</h4></td>
            </tr>
            <tr>
              <td width="17%">
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s1.'">Screening </h5><br>', array('action' => 'workflow', 'stages'=>'Screening'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s1));
                ?>
              </td>
              <td width="17%">
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s2.'">Sponsor <br>Submission</h5>', array('action' => 'workflow', 'stages'=>'ScreeningSubmission'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s2));
                ?>
              </td>
              <td width="17%">
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s3.'">Assign <br>Reviewers</h5>', array('action' => 'workflow', 'stages'=>'Assign'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s3));
                ?>
              </td>
              <td width="17%">
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s4.'">PPB Review</h5><br>', array('action' => 'workflow', 'stages'=>'Review'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s4));
                ?>
              </td>
              <td width="17%">
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s5.'">Sponsor <br>Feedback</h5>', array('action' => 'workflow', 'stages'=>'ReviewSubmission'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s5));
                ?>
              </td>
              <td>
                <?php
                  echo $this->Html->link('<h5 class="text-info'.$s6.'">Final <br>Decision</h5>', array('action' => 'workflow', 'stages'=>'FinalDecision'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s6));
                ?>
              </td>              
            </tr>
            <tr>
              <td colspan="6" style="text-align: center;"><h4 class="text-warning">Annual Approval Stages</h4></td>
            </tr>
            <tr>
              <td colspan="2">
                  <?php
                    echo $this->Html->link('<h5 class="text-info'.$s7.'">Active </h5>', array('action' => 'workflow', 'stages'=>'AnnualApproval', 'status'=>'Current'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s7));
                  ?>
              </td>
              <td colspan="2">
                  <?php
                    echo $this->Html->link('<h5 class="text-info'.$s8.'">Pending <small>(30 days)</small></h5>', array('action' => 'workflow', 'stages'=>'AnnualApproval', 'status'=>'Pending'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s8));
                  ?>
              </td>
              <td colspan="2">
                  <?php
                    echo $this->Html->link('<h5 class="text-info'.$s9.'">Expired</h5>', array('action' => 'workflow', 'stages'=>'AnnualApproval', 'status'=>'Expired'), array('escape' => false, 'class' => 'btn btn-default btn-block '.$s9));
                  ?>
              </td>      
            </tr>
            <tr>
              <td colspan="2">
              <?php
                if($s1) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'Screening'));
                if($s2) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'ScreeningSubmission'));
                if($s3) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'Assign'));
                if($s4) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'Review'));
                if($s5) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'ReviewSubmission'));
                if($s6) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'FinalDecision'));
                //AnnualApprovals
                if($s7) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'AnnualApproval'));
                if($s8) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'AnnualApproval'));
                if($s9) echo $this->Form->input('Application.stages', array('type' => 'hidden', 'value' => 'AnnualApproval'));
                if($s7) echo $this->Form->input('Application.status', array('type' => 'hidden', 'value' => 'Current'));
                if($s8) echo $this->Form->input('Application.status', array('type' => 'hidden', 'value' => 'Pending'));
                if($s9) echo $this->Form->input('Application.status', array('type' => 'hidden', 'value' => 'Expired'));

                echo $this->Form->input('protocol_no',
                    array('div' => false, 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'ECCT Reference No.')));

                echo $this->Form->input('disease_condition', array('type' => 'hidden'));
                echo $this->Form->input('ercs', array('type' => 'hidden'));
                echo $this->Form->input('investigator', array('type' => 'hidden'));
                echo $this->Form->input('sites', array('type' => 'hidden'));
                echo $this->Form->input('users', array('type' => 'hidden'));
                echo $this->Form->input('stages', array('type' => 'hidden'));
                echo $this->Form->input('phase', array('type' => 'hidden'));
              ?>
              </td>
              <td>
              <?php
                echo $this->Form->input('filter', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'Study Title'),
                  'type' => 'text',
                  ));
             
              ?>
              </td>
              <td colspan="2">
              <?php                  
                echo $this->Form->input('start_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                      'label' => array('class' => 'required', 'text' => 'Application Submission Dates'), 'placeHolder' => 'Start Date'));
                echo $this->Form->input('end_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                       'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                            <em class="accordion-toggle">clear!</em></a>',
                      'label' => false, 'placeHolder' => 'End Date'));
              ?>
              </td>
              <td>
                <?php
                  echo $this->Form->input('pages', array(
                    'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['Application']['limit'],
                    'empty' => true,
                    'options' => $page_options,
                    'label' => array('class' => 'required', 'text' => 'Pages'),
                  ));
                ?>
              </td>
            </tr>

            <tr>

            <td colspan="2">
              <?php
                 echo $this->Form->input('estimated_duration', array(
                  'type' => 'number',
                  'min' => 0,
                  'step' => 1,
                  'div' => false,
                  'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'Estimated Duration (Months)'),
                ));
                ?></td>
                <td><?php
                echo $this->Form->input('sponsors', array(
                  'type' => 'select',
                  'div' => false,
                  'class' => 'span12 unauthorized_index',
                  'empty' => true,
                  'options' => isset($sponsor_options) ? $sponsor_options : array(),
                  'label' => array('class' => 'required', 'text' => 'Sponsors'),
                ));  ?></td>
                <td><?php
                echo $this->Form->input('trial_status_id', array(
                  'type' => 'select',
                  'div' => false,
                  'class' => 'span12 unauthorized_index',
                  'empty' => true,
                  'options' => isset($trial_statuses) ? $trial_statuses : array(),
                  'label' => array('class' => 'required', 'text' => 'Trial Status'),
                ));
                ?>
            </td>
            </tr>
            <tr class="warning">
              <td colspan="2" style="text-align: center;">
                <?php
                  echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                      'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                      'style' => array('margin-bottom: 5px')
                  ));
                ?>
              </td>
              <td colspan="2" style="text-align: center;">
                <?php
                  echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'workflow'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
                ?>
              </td>
              <td colspan="2" style="text-align: center;">
                <?php
                  if($redir) echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array_merge(array('action' => 'workflow', 'ext' => 'csv'), $this->request->named), array('class' => 'btn btn-success', 'escape' => false));
                ?>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php echo $this->Form->end(); ?>

      <div class="pagination">
        <ul>
        <?php
          echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
          echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active'));
          echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false ));
        ?>
        </ul>
      </div>

      <p style="text-align: center;">
        <?php
          echo $this->Paginator->counter(array(
          'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                  showing <span class="badge">{:current}</span> Applications out of
                  <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                  ending on <span class="badge">{:end}</span>')
          ));
        ?>
      </p>

      <div class="row-fluid">
        <?php
        // pr($applications);
          if(count($applications) >  0) { ?>
            <table  class="table  table-bordered">
            <thead>
              <tr>
                <th style="width:3%">#</th>
                <th style="width: 13%"><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
                <th style="width: 26%;"><?php echo $this->Paginator->sort('study_title'); ?></th>
                <th style="width: 22%;">Investigator(s) &amp; Site(s)</th>
                <th style="width: 36%">Application Stages </th>
              </tr>
            </thead>
            <tbody>
            <?php
            $counder = ($this->request->paging['Application']['page'] - 1) * $this->request->paging['Application']['limit'];
            foreach ($applications as $application):
            ?>
              <tr class="<?php
                      $stages = $this->requestAction('applications/stages/'.$application['Application']['id']);
                      if(Hash::check($stages, '{s}[color!=success]')) {
                          $var = Hash::extract($stages, '{s}[color!=success].color');
                          if(in_array('warning', $var)) echo 'warning';
                          if(in_array('danger', $var)) echo 'error';
                      }
                   ?>">
                <td><p class="tablenums"><?php $counder++; echo $counder;?>.</p></td>
                <td>
                  <?php
                        if($application['Application']['submitted']) echo $this->Html->link($application['Application']['protocol_no'],
                                         array('action' => 'view', $application['Application']['id']), array('escape'=>false));
                        else echo $this->Html->link($application['Application']['protocol_no'],
                                         array('action' => 'edit', $application['Application']['id']), array('escape'=>false));
                  ?>
                  &nbsp;
                </td>
                <td> 
                  <b><?php   echo $application["Application"]["short_title"]; ?> </b> </br>&nbsp; 
                  <?php   echo strip_tags(str_replace("\\n", "&nbsp;", $application["Application"]["study_title"])); ?> &nbsp; 
                </td>
                <td>
                  <small><strong style="text-decoration:underline;">Principal Investigator(s)</strong></small><br>
                  <?php
                        $cound = 1;
                        foreach ($application['InvestigatorContact'] as $investigator) {
                          echo $cound.'. '.$investigator['given_name'].' '.$investigator['middle_name'].' '.$investigator['family_name'];
                          echo "<br>";
                          $cound++;
                        }
                  ?>
                  <small><strong style="text-decoration:underline;">Site(s) in Kenya</strong></small><br>
                 <?php
                        $cound = 1;
                        if(!empty($application['Application']['location_of_area'])) echo $application['Application']['location_of_area']."<br>";
                        else foreach ($application['SiteDetail'] as $site) {
                          echo $cound.'. '.$site['site_name'];
                          if(!empty($site['County'])) echo ' <small class="muted">('.$site['County']['county_name'].' county)</small>';
                          echo "<br>";
                          $cound++;
                        }?> &nbsp; 
                </td>
                
                <td>  
                  <!-- In table start -->
                  <table class="table table-condensed table-intable" style="margin: 0px">
                    <tbody>
                      <tr>
                        <td><p class="text-warning"><strong>Stage</strong></p></td>
                        <td><p class="text-warning"><strong>Start Date</strong></p></td>
                        <td><p class="text-warning"><strong>Days</strong></p></td>
                      </tr>
                      <?php
                        $cound = 0;
                        foreach ($stages as $sk => $stage) {
                          $cound++;
                          echo "<tr>";
                            echo "<td>".$cound.'. '.strip_tags($stage['label']).(($sk == 'AnnualApproval') ? ' (to expiry)' : '');
                            echo "</td>";
                            echo "<td>".$stage['start_date'];
                            echo "</td>";
                            echo "<td>".$stage['days'];
                            echo "</td>";
                          echo "</tr>";
                        }
                      ?> 
                    </tbody>
                  </table>
                  <!-- In table end -->
                </td>
              </tr>

            <?php endforeach; ?>
            </tbody>
            </table>
        <?php } else { ?>
          <p>There were no reports that met your search criteria.</p>
        <?php } ?>
      </div> <!-- /row-fluid -->
        </div>
      </div>

  </div>
</div>
<script type="text/javascript">
$.expander.defaults.slicePoint = 220;
$(function() {
  $(".morecontent").expander();
  var adates = $('#ApplicationStartDate, #ApplicationEndDate').datepicker({
          minDate:"-100Y",
          maxDate:"-0D",
          dateFormat:'dd-mm-yy',
          format: 'dd-mm-yyyy',
          endDate: '-0d',
          showButtonPanel:true,
          changeMonth:true,
          changeYear:true,
          showAnim:'show',
          onSelect: function( selectedDate ) {
            var option = this.id == "ApplicationStartDate" ? "minDate" : "maxDate",
              instance = $( this ).data( "datepicker" ),
              date = $.datepicker.parseDate(
                instance.settings.dateFormat ||
                $.datepicker._defaults.dateFormat,
                selectedDate, instance.settings );
            adates.not( this ).datepicker( "option", option, date );
          }
        });
});
</script>

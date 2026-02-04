<?php
    $this->assign('SAE', 'active');
    
?>

<div class="row-fluid">
    <div class="span12">

        <?php
    echo $this->Session->flash();
    if ($redir == 'applicant') {
  ?>
        <div class="row-fluid">
            <div class="span12">
                <?php
      echo $this->Html->link('<i class="icon-file"></i> New SAE/SUSAR',
               array('controller' => 'applications', 'action' => 'index'),
               array('escape' => false, 'class' => 'btn btn-success',  'style'=>'margin-right: 10px;'));
    ?>
            </div>
        </div>
        <hr>
        <?php } ?>

        <div class="marketing">
            <div class="row-fluid">
                <div class="span12">
                    <h3>SAE/SUSARs:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i
                                class="icon-eye-open"></i> view reports</small></h3>
                    <hr class="soften" style="margin: 7px 0px;">
                </div>
            </div>
        </div>

        <?php
        echo $this->Form->create('Sae', array(
          'url' => array_merge(array('action' => 'index'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
        <table class="table table-condensed" style="margin-bottom: 2px;">
            <tbody>
                <tr>
                    <td>
                        <?php
                echo $this->Form->input('reference_no',
                    array(
                      'div' => false,
                      'placeholder' => 'SAE/2020..',
                      'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Reference No.'))
                );
              ?>
                    </td>
                    <td>
                        <?php
                echo $this->Form->input('protocol_no',
                    array(
                      'div' => false,
                      'placeholder' => 'ECCT/20..',
                      'class' => 'unauthorized_index span10', 'label' => array('class' => 'required', 'text' => 'Protocol No.'))
                );
              ?>
                    </td>
                    <td colspan="2">
                        <?php
                  echo $this->Form->input('start_date',
                    array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                        'label' => array('class' => 'required', 'text' => 'Report Dates'), 'placeHolder' => 'Start Date'));
                  echo $this->Form->input('end_date',
                    array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                         'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                              <em class="accordion-toggle">clear!</em></a>',
                        'label' => false, 'placeHolder' => 'End Date'));
                ?>
                    </td>
                    <td>
                        <?php
                echo $this->Form->input('drug_name',
                      array('div' => false, 'placeholder' => 'drug name',
                        'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Drug Name')));
              ?>
                    </td>
                    <td>
                        <label class="required">Gender</label>
                        <?php
                  echo $this->Form->input('gender', array(
                      'options' => array('Male' => 'Male', 'Female' => 'Female'), 'legend' => false,
                      'type' => 'radio'
                  ));
                ?>
                    </td>
                    <td>
                        <?php
                echo $this->Form->input('country_id',
                    array(
                      'div' => false, 'empty' => true,
                      'class' => 'input-small', 'label' => array('class' => 'required', 'text' => 'Country'))
                );
              ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                  echo $this->Form->input('indication',
                      array('div' => false, 'placeholder' => 'indication', 'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Indication')));
                ?>
                    </td>
                    <td>
                        <label class="required">Report Type?</label>
                        <?php
                  echo $this->Form->input('report_type', array(
                      'options' => array('Initial' => 'Initial', 'Followup' => 'Followup'), 'legend' => false,
                      'type' => 'radio'
                  ));
                ?>
                    </td>
                    <td colspan="2">
                        <label class="required">Outcome:</label>
                        <?php
                    echo $this->Form->input('patient_died', array('label' => array('text' => 'Patient Died'), 'hiddenField' => false, ));
                    echo $this->Form->input('prolonged_hospitalization', array('label' => array('text' => 'Prolonged Hospitalization'), 'hiddenField' => false, ));                    
                ?>
                    </td>
                    <td>
                        <?php
                    echo $this->Form->input('incapacity', array('label' => array('text' => 'Disability or Incapacity'), 'hiddenField' => false, ));
                    echo $this->Form->input('life_threatening', array('label' => array('text' => 'Life Threatening'), 'hiddenField' => false, ));
                    echo $this->Form->input('reaction_other', array('label' => array('text' => 'Other'), 'hiddenField' => false, ));
                ?>
                    </td>
                    <td>
                        <label class="required">Report Source</label>
                        <?php
                  echo $this->Form->input('source_study', array('label' => array('text' => 'Study'), 'hiddenField' => false, ));
                  echo $this->Form->input('source_literature', array('label' => array('text' => 'Literature'), 'hiddenField' => false, ));
                  echo $this->Form->input('source_health_professional', array('label' => array('text' => 'Health Professional'), 'hiddenField' => false, ));
                ?>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                  echo $this->Form->input('patient_initials',
                      array('div' => false, 'placeholder' => 'Patient initials',
                        'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Patient Initials')));
              ?>
                    </td>
                    <td>
                        <?php
                echo $this->Form->input('reporter',
                      array('div' => false, 'class' => 'input unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Reporter'), 'placeholder' => 'Name/Email'));
              ?>
                    </td>
                    <td>
                        <?php
                echo $this->Form->input('causality', array('type' => 'select',
                  'empty' => true, 'class' => 'span12',
                  'options' => array('Certain' => 'Certain',
                    'Probable/Likely' => 'Probable/Likely', 
                    'Possible' => 'Possible',
                    'Unlikely' => 'Unlikely',
                    'Conditional/Unclassified' => 'Conditional/Unclassified',
                    'Not related' => 'Not related',
                    'Unassessable/Unclassifiable' => 'Unassessable/Unclassifiable'),
                  'label' => array('class' => 'required', 'text' => 'Causality of the reaction')
                  ));
              ?>
                    </td>
                    <td colspan="2">
                        <?php                
                  echo $this->Form->input('age_start',
                    array('div' => false, 'type' => 'text', 'class' => 'span3', 'after' => '-to-',
                        'label' => array('class' => 'required', 'text' => 'Age'), 'placeHolder' => '0 years'));
                  echo $this->Form->input('age_end',
                    array('div' => false, 'type' => 'text', 'class' => 'span3',
                        'label' => false, 'placeHolder' => '140 years'));
              ?>
                    </td>
                    <td>
                        <?php
                
              ?>
                    </td>
                    <td>
                        <?php
                  
                ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="SaePages" class="required">Pages</label></td>
                    <td>
                        <?php
                  echo $this->Form->input('pages', array(
                    'type' => 'select', 'div' => false, 'class' => 'input-small', 'selected' => $this->request->params['paging']['Sae']['limit'],
                    'empty' => true,
                    'options' => $page_options,
                    'label' => false,
                  ));
                ?>
                    </td>
                    <td>
                        <?php
                  // echo $this->Form->checkbox('submitted', array('hiddenField' => false, 'label' => 'Submitted'));
                  echo $this->Form->input('submit', array('type' => 'checkbox', 'hiddenField' => false, 
                      'label' => array('class' => '', 'text' => 'Include Unsubmitted?')));
                ?>
                    </td>
                    <td></td>
                    <td>
                        <?php
                  echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                      'class' => 'btn btn-primary', 'div' => 'control-group', 'div' => false,
                      'formnovalidate' => 'formnovalidate',
                      'style' => array('margin-bottom: 5px')
                  ));
                ?>
                    </td>
                    <td>
                        <?php
                  echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
                ?>
                    </td>
                    <td>
                        <?php
                  echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array_merge(array('action' => 'index', 'ext' => 'csv'), $this->request->named), array('class' => 'btn btn-success', 'escape' => false));
                ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p>
            <?php
        echo $this->Paginator->counter(array(
        'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> SAEs out of
                <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                ending on <span class="badge">{:end}</span>')
        ));
      ?>
        </p>
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

        <table class="table  table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                    <th><?php echo $this->Paginator->sort('reference_no'); ?></th>
                    <th><?php echo $this->Paginator->sort('report_type'); ?></th>
                    <th><?php echo $this->Paginator->sort('application_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('patient_initials'); ?></th>
                    <th><?php echo $this->Paginator->sort('country_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('created'); ?></th>
                    <th><?php echo $this->Paginator->sort('date_submitted'); ?></th>
                    <th class="actions"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
    foreach ($saes as $sae): ?>
                <?php
 
$daysDifference = 0;
$bgColor = '';
// Ensure values exist
if (!empty($sae['Sae']['date_submitted']) && !empty($sae['Sae']['reaction_onset'])) {
  $submittedDateStr = date('Y-m-d', strtotime($sae['Sae']['date_submitted']));
    $onsetDateStr     = date('Y-m-d', strtotime($sae['Sae']['reaction_onset'])); // normalize too

    $submittedDate = new DateTime($submittedDateStr);
    $onsetDate     = new DateTime($onsetDateStr);

    $daysDifference = (int) $submittedDate->diff($onsetDate)->days;

    if ($daysDifference < 7) {
        $bgColor = '#E8F5E9';
    } elseif ($daysDifference <= 30) {
        $bgColor = '#FFF8E1';
    } else {
        $bgColor = '#FDECEA';
    }
 
}
$cellStyle = $bgColor ? 'background-color:' . $bgColor . ' !important;' : '';
?>
              <tr>
                    <td style="<?= h($cellStyle) ?>"><?php echo $daysDifference .'--<br>'. h($sae['Sae']['id']); ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>">

                        <?php  
            
            echo   $this->Html->link($sae['Sae']['reference_no'], array('action' => 'view', $sae['Sae']['id']), array('escape'=>false));
        ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php echo h($sae['Sae']['report_type']); 
                  if($sae['Sae']['report_type'] == 'Followup') {
                    echo "<br> Initial: ";
                    echo $this->Html->link(
                      '<label class="label label-info">'.substr($sae['Sae']['reference_no'], 0, strpos($sae['Sae']['reference_no'], '_')).'</label>', 
                      array('action' => 'view', $sae['Sae']['sae_id']), array('escape' => false));
                  }
              ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php 
          // echo h($sae['Application']['protocol_no']); 
        echo $this->Html->link($sae['Application']['protocol_no'], array('controller' => 'applications' , 'action' => 'view', $sae['Application']['id']), array('escape' => false));
        ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php echo h($sae['Sae']['patient_initials']); ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php echo h($sae['Country']['name']); ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php echo h($sae['Sae']['created']); ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>"><?php echo h($sae['Sae']['date_submitted']); ?>&nbsp;</td>
                    <td style="<?= h($cellStyle) ?>" class="actions">
                        <?php if($sae['Sae']['approved'] > 0) echo $this->Html->link(__('<label class="label label-info">View</label>'), array('action' => 'view', $sae['Sae']['id']), array('escape' => false)); ?>
                        <?php if($redir === 'applicant' && $sae['Sae']['approved'] < 1) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('action' => 'edit', $sae['Sae']['id']), array('escape' => false)); ?>
                        <?php if($redir === 'monitor' && $sae['Sae']['approved'] < 1 && $sae['Sae']['user_id'] == $this->Session->read('Auth.User.id')) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('action' => 'edit', $sae['Sae']['id']), array('escape' => false)); ?>
                        <?php if($redir === 'manager' && $sae['Sae']['approved'] > 0) echo $this->Form->postLink('<span class="badge badge-inverse">Unsubmit</span>', array('action' => 'unsubmit', $sae['Sae']['id']), array('escape' => false), __('Unsubmit %s?', $sae['Sae']['reference_no'])); ?>
                        <?php
              if($sae['Sae']['approved'] < 1 && $redir === 'applicant') {
                // ensure it belongs to the user
                
                echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('action' => 'delete', $sae['Sae']['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['Sae']['id']));
              } 
              if($redir === 'applicant' && $sae['Sae']['approved'] > 0) echo $this->Form->postLink('<i class="icon-facebook"></i> Follow Up', array('action' => 'followup', $sae['Sae']['id']), array('class' => 'btn btn-mini btn-warning', 'escape' => false), __('Create followup for %s?', $sae['Sae']['reference_no']));
            ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $(".morecontent").expander();
    var adates = $('#SaeStartDate, #SaeEndDate').datepicker({
        minDate: "-100Y",
        maxDate: "-0D",
        dateFormat: 'dd-mm-yy',
        format: 'dd-mm-yyyy',
        endDate: '-0d',
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        showAnim: 'show',
        onSelect: function(selectedDate) {
            var option = this.id == "SaeStartDate" ? "minDate" : "maxDate",
                instance = $(this).data("datepicker"),
                date = $.datepicker.parseDate(
                    instance.settings.dateFormat ||
                    $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings);
            adates.not(this).datepicker("option", option, date);
        }
    });

});
</script>
<?php
    $this->assign('DEV', 'active');
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
      echo $this->Html->link('<i class="icon-file"></i> New Deviation',
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
              <h3>Deviations:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small></h3>
              <hr class="soften" style="margin: 7px 0px;">
            </div>
        </div>
    </div>

    <?php
        echo $this->Form->create('Deviation', array(
          'url' => array_merge(array('action' => 'index'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
      <table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
        <thead>
          <tr>
            <th style="width: 15%;">
              <?php
                echo $this->Form->input('reference_no',
                    array('div' => false, 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Reference No.')));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('protocol_no', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'ECCT Reference No.'),
                  'type' => 'text',
                  ));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('start_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                      'label' => array('class' => 'required', 'text' => 'Deviation Create Dates'), 'placeHolder' => 'Start Date'));
                echo $this->Form->input('end_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                       'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                            <em class="accordion-toggle">clear!</em></a>',
                      'label' => false, 'placeHolder' => 'End Date'));
              ?>
              </th>
              <th>
                <?php
                  echo $this->Form->input('pages', array(
                    'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['Deviation']['limit'],
                    'empty' => true,
                    'options' => $page_options,
                    'label' => array('class' => 'required', 'text' => 'Pages'),
                  ));
                ?>
              </th>
              <th rowspan="2" style="width: 14%;">
                <?php
                  echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                      'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                      'style' => array('margin-bottom: 5px')
                  ));

                  echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));echo "<br>";
                  // echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv'), array('class' => 'btn btn-success', 'escape' => false));
                  echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array_merge(array('action' => 'index', 'ext' => 'csv'), $this->request->named), array('class' => 'btn btn-success', 'escape' => false));
                ?>
              </th>
          </tr>
        </thead>
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

    <table  class="table  table-bordered table-striped">
     <thead>
            <tr>
        <th><?php echo $this->Paginator->sort('id'); ?></th>
        <th><?php echo $this->Paginator->sort('reference_no'); ?></th>
        <th><?php echo $this->Paginator->sort('deviation_type', 'Type'); ?></th>
        <th><?php echo $this->Paginator->sort('application_id'); ?></th>
        <th><?php echo $this->Paginator->sort('pi_name'); ?></th>
        <th><?php echo $this->Paginator->sort('deviation_date', 'Date'); ?></th>
        <th><?php echo $this->Paginator->sort('status'); ?></th>
        <th><?php echo $this->Paginator->sort('created'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
          </tr>
       </thead>
      <tbody>
    <?php
    foreach ($deviations as $deviation): ?>
    <?php
      $daysDifference = 0;
      $bgColor = '';
      $submissionDateRaw = '';

      if (!empty($deviation['Deviation']['status']) && $deviation['Deviation']['status'] === 'Submitted') {
        if (!empty($deviation['Deviation']['date_submitted'])) {
          $submissionDateRaw = $deviation['Deviation']['date_submitted'];
        } elseif (!empty($deviation['Deviation']['modified'])) {
          $submissionDateRaw = $deviation['Deviation']['modified'];
        } elseif (!empty($deviation['Deviation']['created'])) {
          $submissionDateRaw = $deviation['Deviation']['created'];
        }
      }

      $deviationDate = null;
      if (!empty($deviation['Deviation']['deviation_date'])) {
        $deviationDate = DateTime::createFromFormat('d-m-Y', $deviation['Deviation']['deviation_date']);
        if (!$deviationDate) {
          $deviationDate = DateTime::createFromFormat('Y-m-d', $deviation['Deviation']['deviation_date']);
        }
      }

      if (!empty($submissionDateRaw) && $deviationDate instanceof DateTime) {
        $submissionDate = new DateTime(date('Y-m-d', strtotime($submissionDateRaw)));
        $daysDifference = (int)$submissionDate->diff($deviationDate)->days;

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
    <tr class="">
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['id']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>">
          <?php 
            // echo h($deviation['Deviation']['reference_no']); 
            //echo $this->Html->link($deviation['Deviation']['reference_no'], array('action' => 'view', $deviation['Deviation']['id']), array('escape'=>false));
            echo $this->Html->link($deviation['Deviation']['reference_no'],
               array('controller' => 'applications', 'action' => 'view', $deviation['Application']['id'], 'deviation_view' => $deviation['Deviation']['id']), array('escape'=>false));
        ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['deviation_type']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php 
          // echo h($deviation['Application']['protocol_no']); 
        echo $this->Html->link($deviation['Application']['protocol_no'], array('controller' => 'applications' , 'action' => 'view', $deviation['Application']['id']), array('escape' => false));
        ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['pi_name']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['deviation_date']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['status']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>"><?php echo h($deviation['Deviation']['created']); ?>&nbsp;</td>
        <td style="<?= h($cellStyle) ?>" class="actions">     
            <?php echo $this->Html->link('<span class="label label-info"> View </span>',
                     array('controller' => 'applications', 'action' => 'view', $deviation['Application']['id'], 'deviation_view' => $deviation['Deviation']['id']), array('escape'=>false)); ?>  
            <?php //echo $this->Html->link(__('<label class="label label-info">View</label>'), array('action' => 'view', $deviation['Deviation']['id']), array('escape' => false)); ?> 
            <?php if($redir === 'manager' && $deviation['Deviation']['status'] == 'Submitted') echo $this->Form->postLink('<span class="badge badge-inverse">Unsubmit</span>', array('action' => 'unsubmit', $deviation['Deviation']['id']), array('escape' => false), __('Unsubmit %s?', $deviation['Deviation']['reference_no'])); ?>
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
  var adates = $('#DeviationStartDate, #DeviationEndDate').datepicker({
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
            var option = this.id == "DeviationStartDate" ? "minDate" : "maxDate",
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

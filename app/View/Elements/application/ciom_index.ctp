<?php
    $this->assign('CIOM', 'active');
?>

<div class="row-fluid">
  <div class="span12">
    
    <div class="marketing">
      <div class="row-fluid">
            <div class="span12">
              <h3>CIOMs:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view reports</small></h3>
              <hr class="soften" style="margin: 7px 0px;">
            </div>
        </div>
    </div>

    <?php
        echo $this->Form->create('Ciom', array(
          'url' => array_merge(array('action' => 'index'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
      <table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
        <thead>
          <tr>
            
              <th>
              <?php
                echo $this->Form->input('reference_no', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'ECCT Reference No.'),
                  'type' => 'text',
                  ));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('start_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                      'label' => array('class' => 'required', 'text' => 'CIOMs Create Dates'), 'placeHolder' => 'Start Date'));
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
                    'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['Ciom']['limit'],
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
                  echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv'), array('class' => 'btn btn-success', 'escape' => false));
                ?>
              </th>
          </tr>
        </thead>
      </table>
    <p>
      <?php
        echo $this->Paginator->counter(array(
        'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> CIOMs out of
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
        <th>#</th>
        <th>ECCT Reference No.</th>
        <th><?php echo $this->Paginator->sort('basename', 'Filename'); ?></th>
        <th><?php echo $this->Paginator->sort('created'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
          </tr>
       </thead>
      <tbody>
    <?php
    $startNo = 1;
    if (!empty($this->request->params['paging']['Ciom'])) {
      $paging = $this->request->params['paging']['Ciom'];
      $startNo = ((int) $paging['page'] - 1) * (int) $paging['limit'] + 1;
    }
    $rowNo = $startNo;
    foreach ($cioms as $ciom): ?>
    <tr class="">
        <td><?php echo $rowNo++; ?>&nbsp;</td>
        <td><?php echo !empty($ciom['Application']['protocol_no']) ? h($ciom['Application']['protocol_no']) : '<span class="muted">N/A</span>'; ?>&nbsp;</td>
        <td>
          <?php 
            // echo h($ciom['Ciom']['basename']); 
            // echo $this->Html->link(
            //     $ciom['Ciom']['basename'],
            //     str_replace('/var/www/html/ctr/app/webroot', '', $ciom['Ciom']['file']),
            //     array('class' => 'button', 'target' => '_blank')
            // );
          echo $this->Html->link(__($ciom['Ciom']['basename']), array('controller' => 'cioms', 'action' => 'download', $ciom['Ciom']['id'], 'admin' => false), array('escape' => false));
            // echo $this->Html->link($ciom['Ciom']['basename'],
            //         array('controller' => 'cioms', 'action' => 'download', $ciom['Ciom']['id'], 'admin' => false),
            //         );
          ?>
          &nbsp;
        </td>
        <td><?php echo h($ciom['Ciom']['created']); ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(__('<label class="label label-info">View</label>'), array('action' => 'view', $ciom['Ciom']['id']), array('escape' => false)); ?>
            <?php echo $this->Form->postLink(
              __('<label class="label label-important">Delete</label>'),
              array('action' => 'delete', $ciom['Ciom']['id']),
              array('escape' => false),
              __('Are you sure you want to delete this CIOM file?')
            ); ?>
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
  var adates = $('#CiomStartDate, #CiomEndDate').datepicker({
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
            var option = this.id == "CiomStartDate" ? "minDate" : "maxDate",
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

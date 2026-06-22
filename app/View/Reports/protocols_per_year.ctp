                                                                                                                                                                                                                                                          
     <?php
      $this->assign('Reports', 'active');        
      echo $this->Session->flash();
      $this->Html->script('highcharts/highcharts', array('inline' => false));
      $this->Html->script('highcharts/modules/data', array('inline' => false));
      $this->Html->script('highcharts/modules/exporting', array('inline' => false));
      // Add the line below to enable local client-side exporting:
      $this->Html->script('highcharts/modules/offline-exporting', array('inline' => false));
      $this->Html->script('highcharts/modules/export-data', array('inline' => false));
    ?>                                                                                                                                                                                                                                               
                                                                                                                                                                                                                                                             
    <?php                                                                                                                                                                                                                                                    
    echo $this->Form->create('Application', array(                                                                                                                                                                                                           
        'url' => array_merge(array('controller'=>'reports','action' => 'protocols_per_year')),                                                                                                                                                               
        'class' => 'ctr-groups', 'style' => array('padding:9px;', 'background-color: #F5F5F5'),                                                                                                                                                              
    ));                                                                                                                                                                                                                                                      
    ?>                                                                                                                                                                                                                                                       
     <div class="row-fluid">
        <div class="span4">
            <?php
            echo $this->Form->input(
                'start_date',
                array(
                    'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                    'label' => array('class' => 'required', 'text' => 'Created Dates'), 'placeHolder' => 'Start Date'
                )
            );
            echo $this->Form->input(
                'end_date',
                array(
                    'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                    'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                          <em class="accordion-toggle">clear!</em></a>',
                    'label' => false, 'placeHolder' => 'End Date'
                )
            );
            ?>
        </div>
        
        <!-- Add the status filter column here -->
        <?php if (!$isManager): ?>
        <div class="span3" style="margin-top: -5px;">
            <?php
            echo $this->Form->input(
                'filter_target',
                array(
                    'label' => 'Filter Target Status',
                    'type' => 'select',
                    'options' => array(
                        'all' => 'All (Submitted & Unsubmitted)',
                        'submitted' => 'Submitted Only',
                        'unsubmitted' => 'Unsubmitted Only'
                    ),
                    'value' => isset($filter) ? $filter : 'all',
                    'class' => 'input-medium'
                )
            );
            ?>
        </div>
        <?php endif; ?>
        
        <div class="span2">
            <?php
            echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                'style' => array('margin-top: 25px')
            ));
            ?>
        </div>
    </div>                                                                                                                                                                                                                                                 
    <?php echo $this->Form->end(); ?>                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                             
    <!-- Highcharts Column Chart Container -->                                                                                                                                                                                                               
    <div id="protocols-per-year"></div>                                                                                                                                                                                                                      
                                                                                                                                                                                                                                                             
    <hr>                                                                                                                                                                                                                                                     
                                                                                                                                                                                                                                                             
    <!-- Display Average Applications Alert Box -->                                                                                                                                                                                                          
    <div class="alert alert-info" style="font-size: 16px; font-weight: bold; margin-bottom: 20px;">                                                                                                                                                          
        <i class="icon-info-sign"></i> Average Applications per Year: <?php echo number_format($average, 2); ?>                                                                                                                                              
    </div>                                                                                                                                                                                                                                                   
                                                                                                                                                                                                                                                             
    <h4>Raw Data</h4>                                                                                                                                                                                                                                        
    <table class="table table-condensed table-bordered" id="datatable_year">                                                                                                                                                                                 
        <thead>                                                                                                                                                                                                                                              
            <tr>                                                                                                                                                                                                                                             
                <th>Year</th>                                                                                                                                                                                                                                
                <th>Applications</th>                                                                                                                                                                                                                        
            </tr>                                                                                                                                                                                                                                            
        </thead>                                                                                                                                                                                                                                             
        <tbody>                                                                                                                                                                                                                                              
          <?php                                                                                                                                                                                                                                              
              foreach ($data as $key => $value) {                                                                                                                                                                                                            
                  echo "<tr>";                                                                                                                                                                                                                               
                    echo "<th>".$value[0]['year']."</th>";                                                                                                                                                                                                   
                    echo "<td>".$value[0]['cnt']."</td>";                                                                                                                                                                                                    
                  echo "</tr>";                                                                                                                                                                                                                              
              }                                                                                                                                                                                                                                              
          ?>                                                                                                                                                                                                                                                 
        </tbody>                                                                                                                                                                                                                                             
    </table>                                                                                                                                                                                                                                                 
                                                                                                                                                                                                                                                             
    <script type="text/javascript">                                                                                                                                                                                                                          
    Highcharts.chart('protocols-per-year', {                                                                                                                                                                                                                 
        data: {                                                                                                                                                                                                                                              
            table: 'datatable_year'                                                                                                                                                                                                                          
        },                                                                                                                                                                                                                                                   
        chart: {                                                                                                                                                                                                                                             
            type: 'column'                                                                                                                                                                                                                                   
        },                                                                                                                                                                                                                                                   
        title: {                                                                                                                                                                                                                                             
            text: 'Applications Count Per Year'                                                                                                                                                                                                              
        },                                                                                                                                                                                                                                                   
        yAxis: {                                                                                                                                                                                                                                             
            allowDecimals: false,                                                                                                                                                                                                                            
            title: {                                                                                                                                                                                                                                         
                text: 'Number of Applications'                                                                                                                                                                                                               
            }                                                                                                                                                                                                                                                
        },                                                                                                                                                                                                                                                   
        tooltip: {                                                                                                                                                                                                                                           
            formatter: function () {                                                                                                                                                                                                                         
                return '<b>' + this.point.name + '</b><br/>' +                                                                                                                                                                                               
                    this.point.y + ' application(s)';                                                                                                                                                                                                        
            }                                                                                                                                                                                                                                                
        }                                                                                                                                                                                                                                                    
    });                                                                                                                                                                                                                                                      
    </script>                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                             
    <script type="text/javascript">                                                                                                                                                                                                                          
    $.expander.defaults.slicePoint = 70;                                                                                                                                                                                                                     
    $(function() {                                                                                                                                                                                                                                           
      var adates = $('#ApplicationStartDate, #ApplicationEndDate').datepicker({                                                                                                                                                                              
          minDate:"-100Y",                                                                                                                                                                                                                                   
          maxDate:"-0D",                                                                                                                                                                                                                                     
          dateFormat:'dd-mm-yy',                                                                                                                                                                                                                             
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
      $(".morecontent").expander();
    });
    </script>
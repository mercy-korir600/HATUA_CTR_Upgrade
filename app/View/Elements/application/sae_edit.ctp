
<div class="sae-form">
  <div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="span6">
               <h5> <?php echo $sae['Sae']['reference_no']; ?> </h5>
            </div>
            <div class="span6">
                <h5 style="text-align: right;"> <span>CIOMS FORM</span></h5>
            </div>
        </div>
        <hr>
    <?php
        echo $this->Form->create('Sae', array(
            'class' => 'form-horizontal',
             'inputDefaults' => array(
                'div' => array('class' => 'control-group'),
                'label' => array('class' => 'control-label'),
                'between' => '<div class="controls">',
                'after' => '</div>',
                'class' => '',
                'format' => array('before', 'label', 'between', 'input', 'after','error'),
                'error' => array('attributes' => array('class' => 'controls help-block')),
             ),
        ));
    ?>

    <div class="row-fluid">
        <div class="span2"></div>
        <div class="span6">
        <?php
            echo $this->Form->input('id');
            echo $this->Form->input('application_id', array('label' => array('class' => 'control-label required', 'text' => 'Protocol <span class="sterix">*</span>')));
        ?>
        </div>
        <div class="span4"></div>
    </div>
    <h4 class="text-center"  style="text-align: center; text-decoration: underline;">REACTION INFORMATION</h4>
    <div class="row-fluid">
        <div class="span6">
            <?php
                // echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
                echo $this->Form->input('patient_initials',
                    array('label' => array('class' => 'control-label required', 'text' => 'Patient Initials <small class="muted">(first, last)</small> <span class="sterix">*</span>'),));
                echo $this->Form->input('enrollment_date', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Date of enrollment into the study </small> <span class="sterix">*</span>')
                ));
                echo $this->Form->input('administration_date', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Date of initial administration of investigational product </small> <span class="sterix">*</span>')
                ));
                echo $this->Form->input('latest_date', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Date of the latest administration of the investigational product </small> <span class="sterix">*</span>')
                ));
                echo $this->Form->input('reaction_onset', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Reaction Onset <span class="sterix">*</span>')
                ));
                echo $this->Form->input('reaction_end_date', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Reaction end date')
                ));
                echo $this->Form->input('gender', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'gender',
                  'before' => '<div class="control-group ">   <label class="control-label required">
                    Gender <span class="sterix">*</span></label>  <div class="controls">
                    <input type="hidden" value="" id="SaeGender_" name="data[Sae][gender]"> <label class="radio inline">',
                  'after' => '</label>',
                  'options' => array('Male' => 'Male'),
                ));
                echo $this->Form->input('gender', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'class' => 'gender',
                  'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                  'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                  'before' => '<label class="radio inline">',
                  'after' => '</label>
                        <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                        onclick="$(\'.gender\').removeAttr(\'checked disabled\')">
                        <em class="accordion-toggle">clear!</em></a> </span>

                        </div> </div>',
                  'options' => array('Female' => 'Female'),
                ));
                ?>
        </div><!--/span-->
        <div class="span6">
            <?php
                  echo $this->Form->input('country_id', array(
                      'empty' => true,
                      'label' => array('class' => 'control-label required', 'text' => 'Country <span class="sterix">*</span>') ));
                  echo $this->Form->input('date_of_birth', array('type' => 'text', 'class' => 'datepickers',
                          'label' => array('class' => 'control-label required', 'text' => 'Date of Birth'), ));
                  echo $this->Form->input('age_years', array('label' => array('class' => 'control-label', 'text' => 'Years'),));
                    
            ?>
            <h5>Check All Appropriate to Adverse Reaction <span class="sterix">*</span></h5>
            <?php                
                echo $this->Form->input('patient_died', array('label' => array('class' => 'control-label', 'text' => 'Patient Died')));
                echo $this->Form->input('prolonged_hospitalization', array('label' => array('class' => 'control-label', 'text' => 'Involved or Prolonged Inpatient Hospitalization')));
                echo $this->Form->input('incapacity', array('label' => array('class' => 'control-label', 'text' => 'Involved Persistence or Significant Disability or Incapacity')));
                echo $this->Form->input('life_threatening', array('label' => array('class' => 'control-label', 'text' => 'Life Threatening')));
                echo $this->Form->input('reaction_other', array('label' => array('class' => 'control-label', 'text' => 'Other')));
            ?>
        </div><!--/span-->
    </div><!--/row-->
    <div class="row-fluid">
        <div class="span12">

            <div class="accordion" id="accordion2" style="margin-left: 20px;margin-right: 20px; margin-bottom: 5px;">
              <div class="accordion-group">
                <div class="accordion-heading">
                  <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" style="color: #0088cc;">
                    WHO-UMC Causality Assessment Scale
                  </a>
                </div>
                <div id="collapseOne" class="accordion-body collapse">
                  <div class="accordion-inner">
                   
                    <table class="table table-bordered  table-condensed">
                        <thead>
                          <tr>
                            <th>Causality Term</th>
                            <th>Assessment</th>
                          </tr>
                        </thead>
                        <tbody>
                         <tr>
                           <td><strong>Certain</strong></td>
                           <td>
                            <ul>
                                <li>Event of laboratory test abnormality, with plausible time relationship to drug intake</li>
                                <li>Cannot be explained by disease or other drugs </li>
                                <li>Response to withdrawal plausible (pharmacologically, pathologically)</li>
                                <li>Event definitive pharmacologically or phenomenologically <em>(i.e an objective and specific medical disorder or a recognized pharmacological phenomenon)</em> </li>
                                <li>Rechallenge satisfactory , if necessary.</li>
                            </ul>
                           </td>
                         </tr>
                         <tr>
                           <td><strong>Probable / Likely</strong></td>
                           <td>
                            <ul>
                                <li>Event or laboratory test abnormality, with reasonble time relationship to drug intake.</li>
                                <li>Unlikely to be attributed to disease or other drugs</li>
                                <li>Response to withdrawal clinically reasonable</li>
                                <li>Rechallenge not required </li>
                            </ul>
                           </td>
                         </tr>
                         <tr>
                           <td><strong>Possible</strong></td>
                           <td>
                            <ul>
                                <li>Event or laboratory test abnormality, with reasonable time relationship to drug intake</li>
                                <li>Could also be explained by disease or other drugs</li>
                                <li>Information on drugs withdrawal lacking or unclear</li>
                            </ul>
                           </td>
                         </tr>

                         <tr>
                           <td><strong>Unlikely</strong></td>
                           <td>
                            <ul>
                                <li>Event or laboratory tests abnormality, with a time to drug intake that makes a relationship improbable (but not impossible)</li>
                                <li>Disease or other drugs provide plausible explanations</li>
                            </ul>
                           </td>
                         </tr>

                         <tr>
                           <td><strong>Conditional / Unclassified</strong></td>
                           <td>
                            <ul>
                                <li>Event or laboratory test abnormality </li>
                                <li>More data for proper, assessment needed or </li>
                                <li>Additional data under examination </li>
                            </ul>
                           </td>
                         </tr>

                         <tr>
                           <td><strong>Unassessable / unclassifiable</strong></td>
                           <td>
                            <ul>
                                <li>Report suggesting an adverse reaction </li>
                                <li>Cannot be judged because of insufficient or contradictory information </li>
                                <li>Data cannot be supplemented or verified </li>
                            </ul>
                           </td>
                         </tr>

                        </tbody>
                      </table>

                  </div>
                </div>
              </div>
            </div>

            <?php 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<div class="control-group ">   <label class="control-label required">
                    Causality of the reaction <span class="sterix">*</span></label>  <div class="controls">
                    <input type="hidden" value="" id="SaeGender_" name="data[Sae][causality]"> <label class="radio inline">',
                  'after' => '</label>',
                  'options' => array('Certain' => 'Certain'),
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<label class="radio inline">', 'after' => '</label>',
                  'options' => array('Probable/Likely' => 'Probable/Likely')
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<label class="radio inline">', 'after' => '</label>',
                  'options' => array('Possible' => 'Possible')
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<label class="radio inline">', 'after' => '</label>',
                  'options' => array('Unlikely' => 'Unlikely')
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<label class="radio inline">', 'after' => '</label>',
                  'options' => array('Conditional/Unclassified' => 'Conditional/Unclassified')
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                  'class' => 'causality',
                  'before' => '<label class="radio inline">', 'after' => '</label>',
                  'options' => array('Not related' => 'Not related')
                )); 
                echo $this->Form->input('causality', array(
                  'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'class' => 'causality',
                  'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                  'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                  'before' => '<label class="radio inline">',
                  'after' => '</label>
                        <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                        onclick="$(\'.causality\').removeAttr(\'checked disabled\')">
                        <em class="accordion-toggle">clear!</em></a> </span>

                        </div> </div>',
                  'options' => array('Unassessable/Unclassifiable' => 'Unassessable/Unclassifiable'),
                ));
            ?>

            <?php
                echo $this->Form->input('reaction_description',
                    array('class' => 'span9',
                        'label' => array('class' => 'control-label required', 'text' => 'Describe Reaction(s) <small class="muted">(including relevant test, lab data)</small> <span class="sterix">*</span>'),));
            ?>
        </div>
    </div>

    <h4 style="text-align: center; text-decoration: underline;">SUSPECTED DRUG(S) INFORMATION</h4>
    <?php echo $this->element('multi/suspected_drugs'); ?>
    <h4 style="text-align: center; text-decoration: underline;">CONCOMITANT DRUG(S) AND HISTORY</h4>
    <?php echo $this->element('multi/concomittant_drugs'); ?>

    <h4 style="text-align: center; text-decoration: underline;">MANUFACTURER INFORMATION</h4>
    <div class="row-fluid">
        <div class="span6">
            <?php
                echo $this->Form->input('manufacturer_name', array('label' => array('class' => 'control-label required', 'text' => 'Name and Address of Manufacturer')));
                echo $this->Form->input('mfr_no', array('label' => array('class' => 'control-label required', 'text' => 'MFR Control No.')));
                echo $this->Form->input('manufacturer_date', array('type' => 'text', 'class' => 'datepickers',
                    'label' => array('class' => 'control-label required', 'text' => 'Date Received by Manufacturer')));
            ?>
        </div>
        <div class="span6">
            <h5>Report Source <span class="sterix">*</span></h5><br>
            <?php
                echo $this->Form->input('source_study', array('label' => array('class' => 'control-label', 'text' => 'Study')));
                echo $this->Form->input('source_literature', array('label' => array('class' => 'control-label', 'text' => 'Literature')));
                echo $this->Form->input('source_health_professional', array('label' => array('class' => 'control-label', 'text' => 'Health Professional')));
            ?>
        </div>
    </div>

    <h4 style="text-align: center; text-decoration: underline;">REPORTER</h4>
    <div class="row-fluid">
        <div class="span6">
            <?php
                echo $this->Form->input('reporter_name', array('label' =>  array('class' => 'control-label', 'text' => 'Name')));
            ?>
        </div>
        <div class="span6">
            <?php                
                echo $this->Form->input('reporter_phone', array('label' => array('class' => 'control-label', 'text' => 'Phone')));
                echo $this->Form->input('reporter_email', array('type' => 'email', 'label' => array('class' => 'control-label', 'text' => 'Email')));
            ?>
        </div>
    </div>
     <hr>

    <div class="controls">
      <?php
        echo $this->Form->button('<i class="icon-list-alt"></i> Save Changes', array(
            'name' => 'saveChanges',
            'class' => 'btn btn-success mapop',
            'id' => 'SaeSaveChanges', 'title'=>'Save & continue editing',
            'data-content' => 'Save changes to form without submitting it.
                                        The form will still be available for further editing.',
            'div' => false,
          ));
      ?>
      <?php
        echo $this->Form->button('<i class="icon-credit-card"></i> Submit', array(
            'name' => 'submitReport',
            'onclick'=>"return confirm('Are you sure you wish to submit the SAE/SUSAR report? It will not be editable.');",
            'class' => 'btn btn-primary mapop',
            'id' => 'SaeSubmitReport', 'title'=>'Save and Submit Report',
            'data-content' => 'Submit report to PPB for review.',
            'div' => false,
          ));

      ?>
     </div>

    <?php
        echo $this->Form->end();
        echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Sae.id')), 
            array('class' => 'btn btn-danger'), 
            __('Are you sure you want to delete # %s?', $this->Form->value('Sae.id')));
    ?>

    </div>
  </div>
</div>

<script>
    (function( $ ) {

        $( ".datepickers" ).datepicker({
            minDate:"-100Y", maxDate:"-0D", dateFormat:'dd-mm-yy', showButtonPanel:true, changeMonth:true, changeYear:true,
            yearRange: "-100Y:+0",
            buttonImageOnly:true, showAnim:'show', showOn:'both', buttonImage:'/img/calendar.gif'
        });

        $.widget( "ui.combobox", {
            _create: function() {
                var input,
                    that = this,
                    select = this.element.hide(),
                    selected = select.children( ":selected" ),
                    value = selected.val() ? selected.text() : "",
                    wrapper = this.wrapper = $( "<span>" )
                        .addClass( "ui-combobox" )
                        .insertAfter( select );

                function removeIfInvalid(element) {
                    var value = $( element ).val(),
                        matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( value ) + "$", "i" ),
                        valid = false;
                    select.children( "option" ).each(function() {
                        if ( $( this ).text().match( matcher ) ) {
                            this.selected = valid = true;
                            return false;
                        }
                    });
                    if ( !valid ) {
                        // remove invalid value, as it didn't match anything
                        $( element )
                            .val( "" )
                            .attr( "title", value + " didn't match any item" )
                            .tooltip( "open" );
                        select.val( "" );
                        setTimeout(function() {
                            input.tooltip( "close" ).attr( "title", "" );
                        }, 2500 );
                        input.data( "autocomplete" ).term = "";
                        return false;
                    }
                }

                input = $( "<input>" )
                    .appendTo( wrapper )
                    .val( value )
                    .attr( "title", "" )
                    .addClass( "ui-state-default ui-combobox-input" )
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: function( request, response ) {
                            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                            response( select.children( "option" ).map(function() {
                                var text = $( this ).text();
                                if ( this.value && ( !request.term || matcher.test(text) ) )
                                    return {
                                        label: text.replace(
                                            new RegExp(
                                                "(?![^&;]+;)(?!<[^<>]*)(" +
                                                $.ui.autocomplete.escapeRegex(request.term) +
                                                ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                            ), "<strong>$1</strong>" ),
                                        value: text,
                                        option: this
                                    };
                            }) );
                        },
                        select: function( event, ui ) {
                            ui.item.option.selected = true;
                            that._trigger( "selected", event, {
                                item: ui.item.option
                            });
                        },
                        change: function( event, ui ) {
                            if ( !ui.item )
                                return removeIfInvalid( this );
                        }
                    })
                    .addClass( "ui-widget ui-widget-content ui-corner-left" );

                input.data( "autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                        .data( "item.autocomplete", item )
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
                };

                $( "<a>" )
                    .attr( "tabIndex", -1 )
                    .attr( "title", "Show All Items" )
                    .tooltip()
                    .appendTo( wrapper )
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass( "ui-corner-all" )
                    .addClass( "ui-corner-right ui-combobox-toggle" )
                    .click(function() {
                        // close if already visible
                        if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                            input.autocomplete( "close" );
                            removeIfInvalid( input );
                            return;
                        }

                        // work around a bug (likely same cause as #5265)
                        $( this ).blur();

                        // pass empty string as value to search for, displaying all results
                        input.autocomplete( "search", "" );
                        input.focus();
                    });

                    input
                        .tooltip({
                            position: {
                                of: this.button
                            },
                            tooltipClass: "ui-state-highlight"
                        });
            },

            destroy: function() {
                this.wrapper.remove();
                this.element.show();
                $.Widget.prototype.destroy.call( this );
            }
        });
    })( jQuery );

    $(function() {
        $( "#SaeCountryId" ).combobox();
        $('.causality').click(function(){            
            if ($(this).val() == 'Certain' || $(this).val() == 'Probable/Likely' || $(this).val() == 'Possible') {
                if($("#suspected-drugs .suspected-group").length == 0) {
                    $('#addSuspectedDrug').click();
                }
            } 
        });
        // function causality_click() {
        //    console.log($(this).val());
        // }
    });
    </script>

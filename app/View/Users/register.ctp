<?php
  $this->assign('Register', 'active');
?>

<div class="row-fluid">
  <div class="span12">
    <div class="page-header">
      <div class="styled_title"><h1>Register <?php  echo $this->element('google-recommend');?></h1></div>
    </div>
  <?php
    echo $this->Session->flash();

    echo $this->Form->create('User', array(
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
    <div class="span6">
      <?php
        echo $this->Form->input('username',
          array('label' => array('class' => 'control-label required', 'text' => 'Username <span class="sterix">*</span>'),));
        echo $this->Form->input('group_id', array(
          'type' => 'select',
          'options' => isset($registrationRoles) ? $registrationRoles : array(
            5 => 'Principal Investigator',
            7 => 'Study Monitor',
            11 => 'Sponsor',
            12 => 'Contract Research Organization (CRO)',
            13 => 'Pharmacovigilance (PV)',
            14 => 'Pharmaceutical Industry'
          ),
          'empty' => false,
          'default' => 5,
          'label' => array('class' => 'control-label required', 'text' => 'Registration Role <span class="sterix">*</span>'),
        ));
        echo $this->Form->input('password',
          array('label' => array('class' => 'control-label required', 'text' => 'Password <span class="sterix">*</span>'),));
        echo $this->Form->input('confirm_password', array(
            'type' => 'password',
            'label' => array('class' => 'control-label required', 'text' => 'Confirm Password <span class="sterix">*</span>'), ));
        echo $this->Form->input('name', array('label' => array('class' => 'control-label', 'text' => 'Name'),));
        echo $this->Form->input('email', array(
          'type' => 'email',
          'div' => array('class' => 'control-group required'),
          'label' => array('class' => 'control-label required', 'text' => 'E-MAIL ADDRESS <span class="sterix">*</span>')
        ));
        echo $this->Form->input('sponsor_email', array(
          'type' => 'email',
          'div' => array('class' => 'control-group required'),
          'label' => array('class' => 'control-label required', 'text' => 'Sponsor\'s E-MAIL <span class="sterix">*</span>')
        ));
        echo $this->Form->input('qualification',
          array('label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="muted">(specialization/expertise)</span> <span class="sterix">*</span>'),));

        ?>
    </div><!--/span-->
    <div class="span6">
      <?php
        echo $this->Form->input('phone_no',
          array('label' => array('class' => 'control-label required', 'text' => 'Phone Number <span class="sterix">*</span>'),));
        echo $this->Form->input('name_of_institution', array(
          'label' => array('class' => 'control-label', 'text' => 'Name of Institution'),
        ));
        echo $this->Form->input('institution_physical', array(
          'label' => array('class' => 'control-label', 'text' => 'Physical Address'),
          'after'=>'<p class="help-block"> Road, street.. </p></div>',
        ));
        echo $this->Form->input('institution_address', array('label' => array('class' => 'control-label', 'text' => 'Institution Address'),));
        echo $this->Form->input('institution_contact', array('label' => array('class' => 'control-label', 'text' => 'Institution Contacts'),));
        echo $this->Form->input('county_id', array(
                  'label' => array('class' => 'control-label required', 'text' => 'County'),
                  'empty' => true, 'between' => '<div class="controls ui-widget">',
                ));
        echo $this->Form->input('country_id', array(
          'empty' => true,
          'label' => array('class' => 'control-label required', 'text' => 'Country <span class="sterix">*</span>') ));

      echo $this->Captcha->input('User', array('label' => false, 'type' => 'number'));
        ?>
    </div><!--/span-->
  </div><!--/row-->
   <hr>

  <?php
    echo $this->Form->input('bot_stop', array(
                'div' => array('style' => 'display:none')
              ));
    echo $this->Form->end(array(
      'label' => 'Submit',
      'value' => 'Save',
      'class' => 'btn btn-primary',
      'id' => 'ApplicationSaveChanges',
      'div' => array(
        'class' => 'form-actions',
      )
    ));
  ?>
  </div>
</div>

<script>
  (function( $ ) {
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
    $( "#UserCountyId" ).combobox();
    $( "#UserCountryId" ).combobox();
    // $( "#toggle" ).click(function() {
    //  $( "#combobox" ).toggle();
    // });
  });
  </script>

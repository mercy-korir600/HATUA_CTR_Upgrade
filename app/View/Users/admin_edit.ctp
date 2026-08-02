<?php
	$this->assign('Users', 'active');
?>

<div class="row-fluid">
	<div class="span12">
		<div class="page-header">
			<div class="styled_title"><h3>Edit <?php echo $this->request->data['User']['name'];?></h3></div>
		</div>
  <style>                                                                                                                                                                                                                                                                                
      .edit-user-section .well {                                                                                                                                             
        box-sizing: border-box;                                                                                                                                              
        margin-bottom: 20px;                                                                                                                                                 
        border-radius: 6px;                                                                                                                                                  
        background-color: #fdfdfd;                                                                                                                                           
        padding: 15px;                                                                                                                                                       
      }                
	   .edit-user-section legend {                                                                                                                                        
            border-bottom: none !important;                                                                                                                                  
            margin-bottom: 15px !important;                                                                                                                                  
            padding-bottom: 0 !important;                                                                                                                                    
          }                                                                                                                                                         
      .edit-user-section .control-group {                                                                                                                                    
        display: flex !important;                                                                                                                                            
        align-items: center !important;                                                                                                                                      
        margin-bottom: 12px !important;                                                                                                                                      
      }                                                                                                                                                                      
      .edit-user-section .control-label {                                                                                                                                    
        float: none !important;                                                                                                                                              
        width: 38% !important;                                                                                                             
        text-align: right !important;                                                                                                        
        padding-right: 10px !important;                                                                                                                                      
        margin-bottom: 0 !important;                                                                                                                                         
        font-weight: 600;                                                                                                                                                    
        box-sizing: border-box !important;                                                                                                                                   
      }                                                                                                                                                                      
      .edit-user-section .controls {                                                                                                                                         
        margin-left: 0 !important;                                                                                                                                           
        width: 62% !important;                                                                                                            
        box-sizing: border-box !important;                                                                                                                                   
      }                                                                                                                                                                      
      .edit-user-section input[type="text"],                                                                                                                                 
      .edit-user-section input[type="password"],                                                                                                                             
      .edit-user-section input[type="email"],                                                                                                                                
      .edit-user-section select,                                                                                                                                             
      .edit-user-section textarea,                                                                                                                                           
      .edit-user-section .ui-combobox-input {                                                                                                                                
        width: 100% !important;                                                                                                                                              
        box-sizing: border-box !important;                                                                                                                                   
        height: 32px !important;                                                                                                                                             
      }                                                                                                                                                                      
       .edit-user-section .form-actions {
            padding-top: 10px !important;
            margin-top: 15px;
            background: transparent !important;
            border-top: none !important;
            text-align: center;
          }                                                                                                                                                                   
      .edit-user-section .btn {                                                                                                                                              
        width: 100% !important;                                                                                                                                              
        box-sizing: border-box !important;                                                                                                                                   
      }                                                                                                                                                                      
    </style>                                                                                                                                                                
                                                                                                                                                                             
    <?php                                                                                                                                                                                                                                                                                                          
  $formDefaults = array(                                                                                                                                                   
        'class' => 'form-horizontal',
        'inputDefaults' => array(
            'div' => array('class' => 'control-group'),
            'label' => array('class' => 'control-label'),
            'between' => '<div class="controls">',
            'after' => '</div>',
            'class' => '',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('class' => 'controls help-block')),
        ),
    );                                                                                                                                                                     
    ?>                                                                                                                                                                       
                                                                                                                                                                             
    <div class="row-fluid edit-user-section">                                                                                                                                
                                                                                                                                                                                                                                                                                                     
        <div class="span4">                                                                                                                                                  
            <?php echo $this->Form->create('User', $formDefaults); ?>                                                                                                        
            <fieldset class="well">                                                                                                                                          
                <legend> Personal Information</legend>                                                                                                                     
                <?php    
				 echo $this->Form->input('id');                                                                                                                           
                    echo $this->Form->input('username', array(                                                                                                               
                        'label' => array('class' => 'control-label required', 'text' => 'Username <span class="sterix">*</span>')                                            
                    ));                                                                                                                                                      
                    echo $this->Form->input('id');                                                                                                                           
                    echo $this->Form->input('name', array(                                                                                                                   
                        'label' => array('class' => 'control-label', 'text' => 'Name')                                                                                       
                    ));                                                                                                                                                      
                    echo $this->Form->input('email', array(                                                                                                                  
                        'type' => 'email',                                                                                                                                   
                        'div' => array('class' => 'control-group required'),                                                                                                 
                        'label' => array('class' => 'control-label required', 'text' => 'E-MAIL ADDRESS <span class="sterix">*</span>')                                      
                    ));                                                                                                                                                      
                    echo $this->Form->input('phone_no', array(                                                                                                               
                        'label' => array('class' => 'control-label required', 'text' => 'Phone Number <span class="sterix">*</span>')                                        
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
                <?php                                                                                                                                                        
                    echo $this->Form->end(array(                                                                                                                             
                        'label' => 'Submit',                                                                                                                     
                        'class' => 'btn btn-primary',                                                                                                                        
                        'div' => array('class' => 'form-actions')                                                                                                            
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
            </fieldset>                                                                                                                                                      
        </div>                                                                                                                                    
                                                                                                        
        <div class="span4">                                                                                                                                                  
            <?php echo $this->Form->create('User', $formDefaults); ?>                                                                                                        
            <fieldset class="well">                                                                                                                                          
                <legend> Access & Institution</legend>                                                                                                                     
                <?php                                                                                                                                                        
                    echo $this->Form->input('id');                                                                                                                           
                                                                                                                                                    
                    echo $this->Form->input('group_id', array(                                                                                                               
                        'label' => array('class' => 'control-label required', 'text' => 'Group / Role <span class="sterix">*</span>'),                                       
                        'empty' => true                                                                                                                                      
                    ));                                                                                                                                                      
                                                                                                                                                                             
                    if (isset($this->request->data['User']['user_type'])) {                                                                                                  
                        echo $this->Form->input('user_type', array(                                                                                                          
                            'label' => array('class' => 'control-label', 'text' => 'User Type')                                                                              
                        ));                                                                                                                                                  
                    }                                                                                                                                                        
                                                                                                                                                                             
                    if (isset($this->request->data['User']['public_health_program'])) {                                                                                      
                        echo $this->Form->input('public_health_program', array(                                                                                              
                            'label' => array('class' => 'control-label', 'text' => 'Public Health Program')                                                                  
                        ));                                                                                                                                                  
                    }                                                                                                                                                        
                                                                                                                                                                             
                                                                                                                                                                    
                                                                                                                                                                             
                    echo $this->Form->input('is_active', array(                                                                                                              
                        'label' => array('class' => 'control-label', 'text' => 'Account Active')                                                                             
                    ));                                                                                                                                                      
                                                                                                                                                                             
                    echo '<hr>';                                                                                                                                                                                                                                                                         
                    echo $this->Form->input('name_of_institution', array(                                                                                                    
                        'label' => array('class' => 'control-label', 'text' => 'Name of Institution')                                                                        
                    ));                                                                                                                                                      
                    echo $this->Form->input('institution_physical', array(                                                                                                   
                        'label' => array('class' => 'control-label', 'text' => 'Physical Address'),                                                                          
                        'after' => '<p class="help-block"> Road, street.. </p></div>'                                                                                        
                    ));                                                                                                                                                      
                    echo $this->Form->input('institution_address', array(                                                                                                    
                        'label' => array('class' => 'control-label', 'text' => 'Institution Address')                                                                        
                    ));                                                                                                                                                      
                    echo $this->Form->input('institution_contact', array(                                                                                                    
                        'label' => array('class' => 'control-label', 'text' => 'Institution Contacts')                                                                       
                    ));    
					 echo $this->Form->input('county_id', array(                                                                                                              
                        'label' => array('class' => 'control-label required', 'text' => 'County'),                                                                           
                        'empty' => true,                                                                                                                                     
                        'between' => '<div class="controls ui-widget">'                                                                                                      
                    ));                                                                                                                                                       
                    echo $this->Form->input('country_id', array(                                                                                                             
                        'empty' => true,                                                                                                                                     
                        'label' => array('class' => 'control-label required', 'text' => 'Country <span class="sterix">*</span>')                                             
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
                <?php                                                                                                                                                        
                    echo $this->Form->end(array(                                                                                                                             
                        'label' => 'Submit',                                                                                                                  
                        'class' => 'btn btn-success',                                                                                                                        
                        'div' => array('class' => 'form-actions')                                                                                                            
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
            </fieldset>                                                                                                                                                      
        </div>                                                                                                                                     
                                                                                                                                                                                                                                                                                               
        <div class="span4">                                                                                                                                                  
            <?php echo $this->Form->create('User', $formDefaults); ?>                                                                                                        
            <fieldset class="well">                                                                                                                                          
                <legend> Change Password</legend>                                                                                                                                                                                                                                                                         
                <?php                                                                                                                                                        
                                                                                                                                                                       
                    echo $this->Form->input('password', array(                                                                                                               
                        'value' => '',                                                                                                                                       
                        'required' => false,                                                                                                                                 
                        'label' => array('class' => 'control-label', 'text' => 'New Password')                                                                               
                    ));                                                                                                                                                      
                    echo $this->Form->input('confirm_password', array(                                                                                                       
                        'type' => 'password',                                                                                                                                
                        'required' => false,                                                                                                                                 
                        'label' => array('class' => 'control-label', 'text' => 'Confirm New Password')                                                                       
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
                <?php                                                                                                                                                        
                    echo $this->Form->end(array(                                                                                                                             
                        'label' => 'Update Password',                                                                                                                        
                        'class' => 'btn btn-warning',                                                                                                                        
                        'div' => array('class' => 'form-actions')                                                                                                            
                    ));                                                                                                                                                      
                ?>                                                                                                                                                           
            </fieldset>                                                                                                                                                      
        </div>
    </div>                                                                                                                                                                            
        
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
		// 	$( "#combobox" ).toggle();
		// });
	});
	</script>

<?php
 	$this->Html->script('multi/protocol_sites', array('inline' => false));
?>
<div class="ctr-groups">
	<?php
		echo $this->Form->input('multiple_sites_member_state', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'multiple_sites_member_state',
			'before' => '<div class="control-group"> <div class="required">
							<label class="control-label required">Multiple sites in Kenya <span class="sterix">*</span></label> </div> <div class="controls">
							<input type="hidden" value="" id="ApplicationMultiplemultipleSitesMemberState_" name="data[Application][multiple_sites_member_state]"> <label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('multiple_sites_member_state', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'class' => 'multiple_sites_member_state',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'span', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.multiple_sites_member_state\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('number_of_sites', array(
			'label' => array('class' => 'control-label required', 'text' => 'Number of sites anticipated in Kenya'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f',
		));
		// echo $this->Form->input('multi_site_list', array(
			// 'label' => array('class' => 'control-label', 'text' => 'If yes, list the sites'),
			// 'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f', 'rows' => '3'
		// ));
	?>
	<h5 class="controls">Details of Site(s) (<small>Repeat as necessary
		<button title="add site" id="addSiteDetail" class="btn-mini multiple_sites_member_state_f" type="button">Add Site</button></small>)</h5>
	<hr>
	<div id="site_primary_detail">
	<?php
		echo $this->Form->input('SiteDetail.0.id');
		echo $this->Form->input('SiteDetail.0.site_name', array(
			'label' => array('class' => 'control-label required', 'text' => 'Name of site'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
		));
		echo $this->Form->input('SiteDetail.0.physical_address', array(
			'label' => array('class' => 'control-label required', 'text' => 'Physical address'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
		));
		echo $this->Form->input('SiteDetail.0.contact_details', array(
			'label' => array('class' => 'control-label required',
			  'text' => 'Contact details <small style="font-weight:normal;"><em>(tel.no, p.o box..) </em></small>'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
		));
		echo $this->Form->input('SiteDetail.0.contact_person', array(
			'label' => array('class' => 'control-label required', 'text' => 'Contact person'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
		));
		// echo $this->Form->input('SiteDetail.0.county_id', array('type' => 'hidden', 'value' => ''));
		echo $this->Form->input('SiteDetail.0.county_id', array(
			'empty' => true, 'class' => 'multiple_sites_member_state_s',
			'label' => array('class' => 'control-label required', 'text' => 'County'),
		));
		// echo $this->Form->input('SiteDetail.0.site_capacity', array(
			// 'label' => array('class' => 'control-label required', 'text' => 'Capacity of Site <span class="sterix">*</span>'),
			// 'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
		// ));
		// echo $this->Html->tag('div', '<button id="SiteDetailButton0" type="button">Add Site</button>', array(
					// 'class' => 'controls', 'escape' => false));
		echo $this->Html->tag('hr', '', array('id' => 'SiteDetailHr0'));
	?>
	</div>
	<div id="site_details">
	<?php
		if (!empty($this->request->data['SiteDetail'])) {
			for ($i = 1; $i <= count($this->request->data['SiteDetail'])-1; $i++) {
			?>
			<div class="site-group">
			<?php
				echo $this->Html->tag('p', $i.' additional sites', array('class' => 'topper'));
				echo $this->Form->input('SiteDetail.'.$i.'.id');
				echo $this->Form->input('SiteDetail.'.$i.'.site_name', array(
					'label' => array('class' => 'control-label required', 'text' => 'Name of site'),
					'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
				));
				echo $this->Form->input('SiteDetail.'.$i.'.physical_address', array(
					'label' => array('class' => 'control-label required', 'text' => 'Physical address'),
					'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
				));
				echo $this->Form->input('SiteDetail.'.$i.'.contact_details', array(
					'label' => array('class' => 'control-label required', 'text' => 'Contact details'),
					'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
				));
				echo $this->Form->input('SiteDetail.'.$i.'.contact_person', array(
					'label' => array('class' => 'control-label required', 'text' => 'Contact person '),
					'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
				));
				// echo $this->Form->input('SiteDetail.'.$i.'.county_id', array('type' => 'hidden', 'value' => ''));
				echo $this->Form->input('SiteDetail.'.$i.'.county_id', array(
					'empty' => true, 'class' => 'multiple_sites_member_state_s',
					'label' => array('class' => 'control-label required', 'text' => 'County'),
				));
				// echo $this->Form->input('SiteDetail.'.$i.'.site_capacity', array(
					// 'label' => array('class' => 'control-label required', 'text' => 'Capacity of Site <span class="sterix">*</span>'),
					// 'placeholder' => ' ' , 'class' => 'input-xxlarge multiple_sites_member_state_f'
				// ));
				echo $this->Html->tag('div', '<button id="SiteDetailButton'.$i.'" class="btn btn-mini btn-danger removeSiteDetail" type="button">
												 Remove Site</button>', array(
							'class' => 'controls', 'escape' => false));
				echo $this->Html->tag('hr', '', array('id' => 'SiteDetailHr'.$i));
			?>
			</div>
			<?php
			}
		}
	?>
	</div>
</div>

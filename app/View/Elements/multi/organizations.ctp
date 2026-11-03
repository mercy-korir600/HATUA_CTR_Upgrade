<?php
 	$this->Html->script('multi/organizations', array('inline' => false));
?>
<h5>11.0 ORGANISATIONS TO WHOM THE SPONSOR HAS TRANSFERRED TRIAL RELATED DUTIES AND FUNCTIONS (<small>repeat as needed for multiple organisations
					- <button type="button" class="btn-mini" id="addOrganization" title="add organization">Add Organization</button></small>) </h5>
<div class="ctr-groups">
	<?php
		$organizationsError = '';
		if($this->Form->isFieldError('organisations_transferred_')) $organizationsError = 'error';
		echo $this->Form->input('organisations_transferred_', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'organisations_transferred_',
			'before' => '<div class="control-group '.$organizationsError.'" style="padding-left:10px">
					<label class="nocontrols control-nolabel required">Has the sponsor transferred any major or all the sponsor&rsquo;s
					trial related duties and functions to another organisation or third party? <span class="sterix">*</span> </label>
					<div class="nocontrols">
				<input type="hidden" value="" id="ApplicationOrganisationsTransferred__" name="data[Application][organisations_transferred_]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('organisations_transferred_', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'organisations_transferred_',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'span', 'class' => 'required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.organisations_transferred_\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));
	?>
	<small><em>Repeat as necessary for multiple organizations</em></small>
	<div id="organization_primary">
	<?php
		echo $this->Form->input('Organization.0.id');
		echo $this->Form->input('Organization.0.organization', array(
			'label' => array('class' => 'control-label required', 'text' => 'Organization'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.0.contact_person', array(
			'label' => array('class' => 'control-label required', 'text' => 'Name of contact person'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.0.physical_address', array(
			'label' => array('class' => 'control-label required', 'text' => 'Physical Address <span class="sterix">*</span>'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.0.postal_address', array(
			'label' => array('class' => 'control-label', 'text' => 'Postal Address'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.0.email_address', array(
			'type' => 'email',
			'label' => array('class' => 'control-label', 'text' => 'Email Address'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.0.telephone_number', array(
			'label' => array('class' => 'control-label required', 'text' => 'Telephone Number'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));

		$allTasksError = $monitoringError = $regulatoryError = $investigatorRecruitmentError = $ivrsTreatmentRandomisationError =
		$dataManagementError = $eDataCaptureError = $susarReportingError = $qualityAssuranceAuditingError = $statisticalAnalysisError =
		$medicalWritingError = $otherDutiesError = '';
		if($this->Form->isFieldError('Organization.0.all_tasks')) $allTasksError = 'error';
		if($this->Form->isFieldError('Organization.0.monitoring')) $monitoringError = 'error';
		if($this->Form->isFieldError('Organization.0.regulatory')) $regulatoryError = 'error';
		if($this->Form->isFieldError('Organization.0.investigator_recruitment')) $investigatorRecruitmentError = 'error';
		if($this->Form->isFieldError('Organization.0.ivrs_treatment_randomisation')) $ivrsTreatmentRandomisationError = 'error';
		if($this->Form->isFieldError('Organization.0.data_management')) $dataManagementError = 'error';
		if($this->Form->isFieldError('Organization.0.e_data_capture')) $eDataCaptureError = 'error';
		if($this->Form->isFieldError('Organization.0.susar_reporting')) $susarReportingError = 'error';
		if($this->Form->isFieldError('Organization.0.quality_assurance_auditing')) $qualityAssuranceAuditingError = 'error';
		if($this->Form->isFieldError('Organization.0.statistical_analysis')) $statisticalAnalysisError = 'error';
		if($this->Form->isFieldError('Organization.0.medical_writing')) $medicalWritingError = 'error';
		if($this->Form->isFieldError('Organization.0.other_duties')) $otherDutiesError = 'error';

		echo $this->Form->input('Organization.0.all_tasks', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'all_tasks0 organizations_o',
			'before' => '<div class="control-group '.$allTasksError.'"> <label class="control-label required">All tasks of the sponsor
				</label> <div class="controls"> <input type="hidden" value="" id="Organisation0AllTasks_" name="data[Organization][0][all_tasks]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.all_tasks', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'all_tasks0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.all_tasks0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.monitoring', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'monitoring0 organizations_o',
			'before' => '<div class="control-group '.$monitoringError.'">
					<label class="control-label required">Monitoring </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0Monitoring_" name="data[Organization][0][monitoring]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.monitoring', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'monitoring0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.monitoring0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.regulatory', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'regulatory0 organizations_o',
			'before' => '<div class="control-group '.$regulatoryError.'">
				<label class="control-label required">Regulatory (e.g. preparation of applications to CA and ethics committee)
				</label>  <div class="controls">
				<input type="hidden" value="" id="Organisation0Regulatory_" name="data[Organization][0][regulatory]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.regulatory', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'regulatory0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.regulatory0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.investigator_recruitment', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'investigator_recruitment0 organizations_o',
			'before' => '<div class="control-group '.$investigatorRecruitmentError.'">
					<label class="control-label required">Investigator Recruitment </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0InvestigatorRectruitment_" name="data[Organization][0][investigator_recruitment]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.investigator_recruitment', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'investigator_recruitment0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.investigator_recruitment0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.ivrs_treatment_randomisation', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'ivrs_treatment_randomisation0 organizations_o',
			'before' => '<div class="control-group '.$ivrsTreatmentRandomisationError.'">
							<label class="control-label required">IVRS &mdash; treatment randomisation </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0IvrsTreatmentRandomisation_" name="data[Organization][0][ivrs_treatment_randomisation]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.ivrs_treatment_randomisation', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'ivrs_treatment_randomisation0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.ivrs_treatment_randomisation0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.data_management', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'data_management0 organizations_o',
			'before' => '<div class="control-group '.$dataManagementError.'">
					<label class="control-label required">Data management </label>  <div class="controls">
				<input type="hidden" value="" id="Organisation0DataManagement_" name="data[Organization][0][data_management]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.data_management', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'data_management0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.data_management0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.e_data_capture', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'e_data_capture0 organizations_o',
			'before' => '<div class="control-group '.$eDataCaptureError.'">
					<label class="control-label required">E-data capture </label>  <div class="controls">
				<input type="hidden" value="" id="Organisation0EDataCapture_" name="data[Organization][0][e_data_capture]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.e_data_capture', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'e_data_capture0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.e_data_capture0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.susar_reporting', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'susar_reporting0 organizations_o',
			'before' => '<div class="control-group '.$susarReportingError.'">
					<label class="control-label required">SUSAR reporting </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0SusarReporting_" name="data[Organization][0][susar_reporting]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.susar_reporting', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'susar_reporting0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.susar_reporting0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.quality_assurance_auditing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'quality_assurance_auditing0 organizations_o',
			'before' => '<div class="control-group '.$qualityAssuranceAuditingError.'">
					<label class="control-label required">Quality assurance auditing </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0QualityAssuranceAuditing_" name="data[Organization][0][quality_assurance_auditing]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.quality_assurance_auditing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'quality_assurance_auditing0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.quality_assurance_auditing0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.statistical_analysis', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'statistical_analysis0 organizations_o',
			'before' => '<div class="control-group '.$statisticalAnalysisError.'">
					<label class="control-label required">Statistical analysis </label><div class="controls">
				<input type="hidden" value="" id="Organisation0StatisticalAnalysis_" name="data[Organization][0][statistical_analysis]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.statistical_analysis', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'statistical_analysis0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.statistical_analysis0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.medical_writing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'medical_writing0 organizations_o',
			'before' => '<div class="control-group '.$medicalWritingError.'">
							<label class="control-label required">Medical Writing </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0MedicalWriting_" name="data[Organization][0][medical_writing]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.medical_writing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'medical_writing0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.medical_writing0\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.0.other_duties', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'other_duties0 organizations_o',
			'before' => '<div class="control-group '.$otherDutiesError.'">
							<label class="control-label required">Other duties subcontracted </label> <div class="controls">
				<input type="hidden" value="" id="Organisation0OtherDuties_" name="data[Organization][0][other_duties]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.0.other_duties', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'other_duties0 organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.other_duties0\').removeAttr(\'checked disabled\');">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));
		// echo $this->Html->tag('p', 'howdy neighbour??', array('class' => 'controls'));

		echo $this->Form->input('Organization.0.other_duties_specify', array(
			'label' => array('class' => 'control-label required', 'text' => 'If yes to other, please specify'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f', 'rows' => '3'
		));
	?>
	</div>
	<hr>

	<!--REPEATING SECTION-->
	<div id="organization_multis">
	<?php
	if (!empty($this->request->data['Organization'])) {
		for ($i = 1; $i <= count($this->request->data['Organization'])-1; $i++) {
		?>
	<div class="organization-group">
	<?php
		echo $this->Form->input('Organization.'.$i.'.id');
		echo $this->Html->tag('p', $i.' additional organizations', array('class' => 'topper'));
		echo $this->Html->tag('span', $i, array('class' => 'badge badge-info'));
		echo $this->Form->input('Organization.'.$i.'.organization', array(
			'label' => array('class' => 'control-label required', 'text' => 'Organization '),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.'.$i.'.contact_person', array(
			'label' => array('class' => 'control-label required', 'text' => 'Name of contact person'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.'.$i.'.physical_address', array(
			'label' => array('class' => 'control-label required', 'text' => 'Physical Address <span class="sterix">*</span>'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.'.$i.'.postal_address', array(
			'label' => array('class' => 'control-label', 'text' => 'Postal Address'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.'.$i.'.email_address', array(
			'type' => 'email',
			'label' => array('class' => 'control-label', 'text' => 'Email Address'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));
		echo $this->Form->input('Organization.'.$i.'.telephone_number', array(
			'label' => array('class' => 'control-label required', 'text' => 'Telephone Number'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f'
		));

		$allTasksError = $monitoringError = $regulatoryError = $investigatorRecruitmentError = $ivrsTreatmentRandomisationError =
		$dataManagementError = $eDataCaptureError = $susarReportingError = $qualityAssuranceAuditingError = $statisticalAnalysisError =
		$medicalWritingError = $otherDutiesError = '';
		if($this->Form->isFieldError('Organization.'.$i.'.all_tasks')) $allTasksError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.monitoring')) $monitoringError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.regulatory')) $regulatoryError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.investigator_recruitment')) $investigatorRecruitmentError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.ivrs_treatment_randomisation')) $ivrsTreatmentRandomisationError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.data_management')) $dataManagementError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.e_data_capture')) $eDataCaptureError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.susar_reporting')) $susarReportingError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.quality_assurance_auditing')) $qualityAssuranceAuditingError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.statistical_analysis')) $statisticalAnalysisError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.medical_writing')) $medicalWritingError = 'error';
		if($this->Form->isFieldError('Organization.'.$i.'.other_duties')) $otherDutiesError = 'error';

		echo $this->Form->input('Organization.'.$i.'.all_tasks', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'all_tasks'.$i.' organizations_o',
			'before' => '<div class="control-group '.$allTasksError.'"> <label class="control-label required">All tasks of the sponsor
				</label> <div class="controls"> <input type="hidden" value="" id="Organisation'.$i.'AllTasks_" name="data[Organization]['.$i.'][all_tasks]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.all_tasks', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'all_tasks'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.all_tasks'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.monitoring', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'monitoring'.$i.' organizations_o',
			'before' => '<div class="control-group '.$monitoringError.'">
					<label class="control-label required">Monitoring  </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'Monitoring_" name="data[Organization]['.$i.'][monitoring]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.monitoring', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'monitoring'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.monitoring'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.regulatory', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'regulatory'.$i.' organizations_o',
			'before' => '<div class="control-group '.$regulatoryError.'">
				<label class="control-label required">Regulatory (e.g. preparation of applications to CA and ethics committee)
				</label>  <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'Regulatory_" name="data[Organization]['.$i.'][regulatory]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.regulatory', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'regulatory'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.regulatory'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.investigator_recruitment', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'investigator_recruitment'.$i.' organizations_o',
			'before' => '<div class="control-group '.$investigatorRecruitmentError.'">
					<label class="control-label required">Investigator Recruitment </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'InvestigatorRectruitment_" name="data[Organization]['.$i.'][investigator_recruitment]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.investigator_recruitment', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'investigator_recruitment'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.investigator_recruitment'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.ivrs_treatment_randomisation', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'ivrs_treatment_randomisation'.$i.' organizations_o',
			'before' => '<div class="control-group '.$ivrsTreatmentRandomisationError.'">
							<label class="control-label required">IVRS &mdash; treatment randomisation </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'IvrsTreatmentRandomisation_" name="data[Organization]['.$i.'][ivrs_treatment_randomisation]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.ivrs_treatment_randomisation', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'ivrs_treatment_randomisation'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.ivrs_treatment_randomisation'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.data_management', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'data_management'.$i.' organizations_o',
			'before' => '<div class="control-group '.$dataManagementError.'">
					<label class="control-label required">Data management </label>  <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'DataManagement_" name="data[Organization]['.$i.'][data_management]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.data_management', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'data_management'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.data_management'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.e_data_capture', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'e_data_capture'.$i.' organizations_o',
			'before' => '<div class="control-group '.$eDataCaptureError.'">
					<label class="control-label required">E-data capture </label>  <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'EDataCapture_" name="data[Organization]['.$i.'][e_data_capture]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.e_data_capture', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'e_data_capture'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.e_data_capture'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.susar_reporting', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'susar_reporting'.$i.' organizations_o',
			'before' => '<div class="control-group '.$susarReportingError.'">
					<label class="control-label required">SUSAR reporting </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'SusarReporting_" name="data[Organization]['.$i.'][susar_reporting]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.susar_reporting', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'susar_reporting'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.susar_reporting'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.quality_assurance_auditing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'quality_assurance_auditing'.$i.' organizations_o',
			'before' => '<div class="control-group '.$qualityAssuranceAuditingError.'">
					<label class="control-label required">Quality assurance auditing </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'QualityAssuranceAuditing_" name="data[Organization]['.$i.'][quality_assurance_auditing]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.quality_assurance_auditing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'quality_assurance_auditing'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.quality_assurance_auditing'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.statistical_analysis', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'statistical_analysis'.$i.' organizations_o',
			'before' => '<div class="control-group '.$statisticalAnalysisError.'">
					<label class="control-label required">Statistical analysis  </label><div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'StatisticalAnalysis_" name="data[Organization]['.$i.'][statistical_analysis]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.statistical_analysis', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'statistical_analysis'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.statistical_analysis'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.medical_writing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'medical_writing'.$i.' organizations_o',
			'before' => '<div class="control-group '.$medicalWritingError.'">
							<label class="control-label required">Medical Writing </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'MedicalWriting_" name="data[Organization]['.$i.'][medical_writing]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.medical_writing', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'medical_writing'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.medical_writing'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.other_duties', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
			'class' => 'other_duties'.$i.' organizations_o',
			'before' => '<div class="control-group '.$otherDutiesError.'">
							<label class="control-label required">Other duties subcontracted </label> <div class="controls">
				<input type="hidden" value="" id="Organisation'.$i.'OtherDuties_" name="data[Organization]['.$i.'][other_duties]">
				<label class="radio inline">',
			'after' => '</label>',
			'options' => array('Yes' => 'Yes'),
		));
		echo $this->Form->input('Organization.'.$i.'.other_duties', array(
			'type' => 'radio',	'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
			'class' => 'other_duties'.$i.' organizations_o',
			'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
			'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
			'before' => '<label class="radio inline">',
			'after' => '</label>
						<span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
						onclick="$(\'.other_duties'.$i.'\').removeAttr(\'checked disabled\')">
						<em class="accordion-toggle">clear!</em></a> </span>
						</div> </div>',
			'options' => array('No' => 'No'),
		));

		echo $this->Form->input('Organization.'.$i.'.other_duties_specify', array(
			'label' => array('class' => 'control-label required', 'text' => 'If yes to other, please specify'),
			'placeholder' => ' ' , 'class' => 'input-xxlarge organizations_f', 'rows' => '3'
		));

		echo $this->Html->tag('div', '<button id="OrganizationButton'.$i.'" type="button" class="btn btn-mini btn-danger removeOrganization">
									Remove Organization</button>', array(
					'class' => 'controls', 'escape' => false));
		echo $this->Html->tag('hr', '', array('id' => 'OrganizationHr'.$i.''));
			?>
		</div>
	<?php
		}
	  }
	?>
	</div>
</div>

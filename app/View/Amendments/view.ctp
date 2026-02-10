<div class="amendments view">
<h2><?php  echo __('Amendment'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Application'); ?></dt>
		<dd>
			<?php echo $this->Html->link($amendment['Application']['id'], array('controller' => 'applications', 'action' => 'view', $amendment['Application']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Status'); ?></dt>
		<dd>
			<?php echo $this->Html->link($amendment['TrialStatus']['name'], array('controller' => 'trial_statuses', 'action' => 'view', $amendment['TrialStatus']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Abstract Of Study'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['abstract_of_study']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Study Title'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['study_title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ECCT Reference No'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['protocol_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Version No'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['version_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Of Protocol'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['date_of_protocol']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Study Drug'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['study_drug']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Disease Condition'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['disease_condition']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Biologicals'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_biologicals']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Proteins'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_proteins']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Immunologicals'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_immunologicals']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Vaccines'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_vaccines']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Hormones'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_hormones']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Toxoid'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_toxoid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Chemical'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_chemical']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Medical Device'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_medical_device']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Chemical Name'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_chemical_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Type Medical Device Name'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['product_type_medical_device_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ecct Not Applicable'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['ecct_not_applicable']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ecct Ref Number'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['ecct_ref_number']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email Address'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['email_address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Covering Letter'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_covering_letter']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Protocol'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_protocol']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Patient Information'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_patient_information']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Investigators Brochure'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_investigators_brochure']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Investigators Cv'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_investigators_cv']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Signed Declaration'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_signed_declaration']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Financial Declaration'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_financial_declaration']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Gmp Certificate'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_gmp_certificate']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Indemnity Cover'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_indemnity_cover']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Opinion Letter'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_opinion_letter']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Approval Letter'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_approval_letter']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Statement'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_statement']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Participating Countries'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_participating_countries']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Addendum'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_addendum']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Applicant Fees'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['applicant_fees']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Declaration Applicant'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['declaration_applicant']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Declaration Date1'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['declaration_date1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Declaration Principal Investigator'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['declaration_principal_investigator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Declaration Date2'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['declaration_date2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Placebo Present'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['placebo_present']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Principal Inclusion Criteria'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['principal_inclusion_criteria']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Principal Exclusion Criteria'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['principal_exclusion_criteria']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Primary End Points'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['primary_end_points']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Diagnosis'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_diagnosis']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Prophylaxis'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_prophylaxis']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Therapy'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_therapy']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Safety'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_safety']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Efficacy'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_efficacy']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Pharmacokinetic'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_pharmacokinetic']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Pharmacodynamic'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_pharmacodynamic']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Bioequivalence'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_bioequivalence']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Dose Response'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_dose_response']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Pharmacogenetic'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_pharmacogenetic']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Pharmacogenomic'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_pharmacogenomic']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Pharmacoecomomic'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_pharmacoecomomic']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Others'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_others']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Scope Others Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['scope_others_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Human Pharmacology'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_human_pharmacology']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Administration Humans'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_administration_humans']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Bioequivalence Study'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_bioequivalence_study']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Other'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_other']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Other Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_other_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Therapeutic Exploratory'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_therapeutic_exploratory']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Therapeutic Confirmatory'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_therapeutic_confirmatory']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Therapeutic Use'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['trial_therapeutic_use']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Site Capacity'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['site_capacity']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Staff Numbers'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['staff_numbers']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Other Details Explanation'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['other_details_explanation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Other Details Regulatory Notapproved'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['other_details_regulatory_notapproved']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Other Details Regulatory Approved'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['other_details_regulatory_approved']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Other Details Regulatory Rejected'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['other_details_regulatory_rejected']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Other Details Regulatory Halted'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['other_details_regulatory_halted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Estimated Duration'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['estimated_duration']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Randomised'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_randomised']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Open'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_open']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Single Blind'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_single_blind']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Double Blind'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_double_blind']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Parallel Group'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_parallel_group']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Cross Over'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_cross_over']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Other'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_other']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Comparator'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_comparator']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Other Medicinal'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_other_medicinal']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Placebo'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_placebo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Medicinal Other'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_medicinal_other']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Design Controlled Medicinal Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['design_controlled_medicinal_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Single Site Member State'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['single_site_member_state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Location Of Area'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['location_of_area']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Multiple Sites Member State'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['multiple_sites_member_state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Multiple Countries'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['multiple_countries']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Multiple Member States'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['multiple_member_states']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Multi Country List'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['multi_country_list']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data Monitoring Committee'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['data_monitoring_committee']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Total Enrolment Per Site'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['total_enrolment_per_site']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Total Participants Worldwide'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['total_participants_worldwide']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Less Than 18 Years'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_less_than_18_years']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Utero'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_utero']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Preterm Newborn'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_preterm_newborn']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Newborn'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_newborn']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Infant And Toddler'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_infant_and_toddler']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Children'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_children']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Adolescent'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_adolescent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Above 18'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_above_18']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Adult'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_adult']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Population Elderly'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['population_elderly']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender Female'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['gender_female']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Gender Male'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['gender_male']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Healthy'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_healthy']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Patients'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_patients']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Vulnerable Populations'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_vulnerable_populations']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Women Child Bearing'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_women_child_bearing']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Women Using Contraception'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_women_using_contraception']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Pregnant Women'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_pregnant_women']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Nursing Women'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_nursing_women']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Emergency Situation'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_emergency_situation']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Incapable Consent'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_incapable_consent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Others'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_others']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subjects Others Specify'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['subjects_others_specify']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigator1 Given Name'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['investigator1_given_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigator1 Middle Name'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['investigator1_middle_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigator1 Family Name'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['investigator1_family_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigator1 Qualification'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['investigator1_qualification']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigator1 Professional Address'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['investigator1_professional_address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Organisations Transferred '); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['organisations_transferred_']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Number Participants'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['number_participants']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Approval Date'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['approval_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Submitted'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['submitted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date Submitted'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['date_submitted']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($amendment['Amendment']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Amendment'), array('action' => 'edit', $amendment['Amendment']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Amendment'), array('action' => 'delete', $amendment['Amendment']['id']), null, __('Are you sure you want to delete # %s?', $amendment['Amendment']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Amendments'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Amendment'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Trial Statuses'), array('controller' => 'trial_statuses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Trial Status'), array('controller' => 'trial_statuses', 'action' => 'add')); ?> </li>
	</ul>
</div>

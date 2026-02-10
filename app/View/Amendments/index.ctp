<div class="amendments index">
	<h2><?php echo __('Amendments'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('application_id'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_status_id'); ?></th>
			<th><?php echo $this->Paginator->sort('abstract_of_study'); ?></th>
			<th><?php echo $this->Paginator->sort('study_title'); ?></th>
			<th><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
			<th><?php echo $this->Paginator->sort('version_no'); ?></th>
			<th><?php echo $this->Paginator->sort('date_of_protocol'); ?></th>
			<th><?php echo $this->Paginator->sort('study_drug'); ?></th>
			<th><?php echo $this->Paginator->sort('disease_condition'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_biologicals'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_proteins'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_immunologicals'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_vaccines'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_hormones'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_toxoid'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_chemical'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_medical_device'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_chemical_name'); ?></th>
			<th><?php echo $this->Paginator->sort('product_type_medical_device_name'); ?></th>
			<th><?php echo $this->Paginator->sort('ecct_not_applicable'); ?></th>
			<th><?php echo $this->Paginator->sort('ecct_ref_number'); ?></th>
			<th><?php echo $this->Paginator->sort('email_address'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_covering_letter'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_protocol'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_patient_information'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_investigators_brochure'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_investigators_cv'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_signed_declaration'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_financial_declaration'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_gmp_certificate'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_indemnity_cover'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_opinion_letter'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_approval_letter'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_statement'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_participating_countries'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_addendum'); ?></th>
			<th><?php echo $this->Paginator->sort('applicant_fees'); ?></th>
			<th><?php echo $this->Paginator->sort('declaration_applicant'); ?></th>
			<th><?php echo $this->Paginator->sort('declaration_date1'); ?></th>
			<th><?php echo $this->Paginator->sort('declaration_principal_investigator'); ?></th>
			<th><?php echo $this->Paginator->sort('declaration_date2'); ?></th>
			<th><?php echo $this->Paginator->sort('placebo_present'); ?></th>
			<th><?php echo $this->Paginator->sort('principal_inclusion_criteria'); ?></th>
			<th><?php echo $this->Paginator->sort('principal_exclusion_criteria'); ?></th>
			<th><?php echo $this->Paginator->sort('primary_end_points'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_diagnosis'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_prophylaxis'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_therapy'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_safety'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_efficacy'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_pharmacokinetic'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_pharmacodynamic'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_bioequivalence'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_dose_response'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_pharmacogenetic'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_pharmacogenomic'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_pharmacoecomomic'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_others'); ?></th>
			<th><?php echo $this->Paginator->sort('scope_others_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_human_pharmacology'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_administration_humans'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_bioequivalence_study'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_other'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_other_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_therapeutic_exploratory'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_therapeutic_confirmatory'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_therapeutic_use'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled'); ?></th>
			<th><?php echo $this->Paginator->sort('site_capacity'); ?></th>
			<th><?php echo $this->Paginator->sort('staff_numbers'); ?></th>
			<th><?php echo $this->Paginator->sort('other_details_explanation'); ?></th>
			<th><?php echo $this->Paginator->sort('other_details_regulatory_notapproved'); ?></th>
			<th><?php echo $this->Paginator->sort('other_details_regulatory_approved'); ?></th>
			<th><?php echo $this->Paginator->sort('other_details_regulatory_rejected'); ?></th>
			<th><?php echo $this->Paginator->sort('other_details_regulatory_halted'); ?></th>
			<th><?php echo $this->Paginator->sort('estimated_duration'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_randomised'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_open'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_single_blind'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_double_blind'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_parallel_group'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_cross_over'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_other'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_comparator'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_other_medicinal'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_placebo'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_medicinal_other'); ?></th>
			<th><?php echo $this->Paginator->sort('design_controlled_medicinal_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('single_site_member_state'); ?></th>
			<th><?php echo $this->Paginator->sort('location_of_area'); ?></th>
			<th><?php echo $this->Paginator->sort('multiple_sites_member_state'); ?></th>
			<th><?php echo $this->Paginator->sort('multiple_countries'); ?></th>
			<th><?php echo $this->Paginator->sort('multiple_member_states'); ?></th>
			<th><?php echo $this->Paginator->sort('multi_country_list'); ?></th>
			<th><?php echo $this->Paginator->sort('data_monitoring_committee'); ?></th>
			<th><?php echo $this->Paginator->sort('total_enrolment_per_site'); ?></th>
			<th><?php echo $this->Paginator->sort('total_participants_worldwide'); ?></th>
			<th><?php echo $this->Paginator->sort('population_less_than_18_years'); ?></th>
			<th><?php echo $this->Paginator->sort('population_utero'); ?></th>
			<th><?php echo $this->Paginator->sort('population_preterm_newborn'); ?></th>
			<th><?php echo $this->Paginator->sort('population_newborn'); ?></th>
			<th><?php echo $this->Paginator->sort('population_infant_and_toddler'); ?></th>
			<th><?php echo $this->Paginator->sort('population_children'); ?></th>
			<th><?php echo $this->Paginator->sort('population_adolescent'); ?></th>
			<th><?php echo $this->Paginator->sort('population_above_18'); ?></th>
			<th><?php echo $this->Paginator->sort('population_adult'); ?></th>
			<th><?php echo $this->Paginator->sort('population_elderly'); ?></th>
			<th><?php echo $this->Paginator->sort('gender_female'); ?></th>
			<th><?php echo $this->Paginator->sort('gender_male'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_healthy'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_patients'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_vulnerable_populations'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_women_child_bearing'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_women_using_contraception'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_pregnant_women'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_nursing_women'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_emergency_situation'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_incapable_consent'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_others'); ?></th>
			<th><?php echo $this->Paginator->sort('subjects_others_specify'); ?></th>
			<th><?php echo $this->Paginator->sort('investigator1_given_name'); ?></th>
			<th><?php echo $this->Paginator->sort('investigator1_middle_name'); ?></th>
			<th><?php echo $this->Paginator->sort('investigator1_family_name'); ?></th>
			<th><?php echo $this->Paginator->sort('investigator1_qualification'); ?></th>
			<th><?php echo $this->Paginator->sort('investigator1_professional_address'); ?></th>
			<th><?php echo $this->Paginator->sort('organisations_transferred_'); ?></th>
			<th><?php echo $this->Paginator->sort('number_participants'); ?></th>
			<th><?php echo $this->Paginator->sort('approval_date'); ?></th>
			<th><?php echo $this->Paginator->sort('submitted'); ?></th>
			<th><?php echo $this->Paginator->sort('date_submitted'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($amendments as $amendment): ?>
	<tr>
		<td><?php echo h($amendment['Amendment']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($amendment['Application']['id'], array('controller' => 'applications', 'action' => 'view', $amendment['Application']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($amendment['TrialStatus']['name'], array('controller' => 'trial_statuses', 'action' => 'view', $amendment['TrialStatus']['id'])); ?>
		</td>
		<td><?php echo h($amendment['Amendment']['abstract_of_study']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['study_title']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['protocol_no']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['version_no']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['date_of_protocol']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['study_drug']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['disease_condition']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_biologicals']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_proteins']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_immunologicals']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_vaccines']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_hormones']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_toxoid']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_chemical']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_medical_device']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_chemical_name']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['product_type_medical_device_name']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['ecct_not_applicable']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['ecct_ref_number']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['email_address']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_covering_letter']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_protocol']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_patient_information']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_investigators_brochure']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_investigators_cv']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_signed_declaration']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_financial_declaration']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_gmp_certificate']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_indemnity_cover']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_opinion_letter']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_approval_letter']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_statement']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_participating_countries']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_addendum']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['applicant_fees']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['declaration_applicant']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['declaration_date1']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['declaration_principal_investigator']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['declaration_date2']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['placebo_present']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['principal_inclusion_criteria']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['principal_exclusion_criteria']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['primary_end_points']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_diagnosis']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_prophylaxis']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_therapy']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_safety']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_efficacy']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_pharmacokinetic']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_pharmacodynamic']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_bioequivalence']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_dose_response']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_pharmacogenetic']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_pharmacogenomic']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_pharmacoecomomic']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_others']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['scope_others_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_human_pharmacology']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_administration_humans']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_bioequivalence_study']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_other']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_other_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_therapeutic_exploratory']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_therapeutic_confirmatory']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['trial_therapeutic_use']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['site_capacity']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['staff_numbers']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['other_details_explanation']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['other_details_regulatory_notapproved']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['other_details_regulatory_approved']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['other_details_regulatory_rejected']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['other_details_regulatory_halted']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['estimated_duration']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_randomised']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_open']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_single_blind']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_double_blind']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_parallel_group']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_cross_over']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_other']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_comparator']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_other_medicinal']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_placebo']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_medicinal_other']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['design_controlled_medicinal_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['single_site_member_state']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['location_of_area']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['multiple_sites_member_state']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['multiple_countries']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['multiple_member_states']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['multi_country_list']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['data_monitoring_committee']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['total_enrolment_per_site']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['total_participants_worldwide']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_less_than_18_years']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_utero']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_preterm_newborn']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_newborn']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_infant_and_toddler']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_children']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_adolescent']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_above_18']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_adult']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['population_elderly']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['gender_female']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['gender_male']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_healthy']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_patients']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_vulnerable_populations']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_women_child_bearing']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_women_using_contraception']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_pregnant_women']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_nursing_women']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_emergency_situation']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_incapable_consent']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_others']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['subjects_others_specify']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['investigator1_given_name']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['investigator1_middle_name']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['investigator1_family_name']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['investigator1_qualification']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['investigator1_professional_address']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['organisations_transferred_']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['number_participants']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['approval_date']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['submitted']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['date_submitted']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['modified']); ?>&nbsp;</td>
		<td><?php echo h($amendment['Amendment']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $amendment['Amendment']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $amendment['Amendment']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $amendment['Amendment']['id']), null, __('Are you sure you want to delete # %s?', $amendment['Amendment']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Amendment'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Applications'), array('controller' => 'applications', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Application'), array('controller' => 'applications', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Trial Statuses'), array('controller' => 'trial_statuses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Trial Status'), array('controller' => 'trial_statuses', 'action' => 'add')); ?> </li>
	</ul>
</div>

<?php

// app/Views/Subscribers/export.ctp
$header = array('id' => '#', 'protocol_no' => 'Protocol No.', 'study_title' => 'Study Title', 'version_no' => 'Version No.', 'date_of_protocol' => 'Date of protocol', 
				'study_drug' => 'Study Drug', 
				'study_routes' => 'Route(s) of Administration', 'disease_condition' => 'Disease condition', 'product_type' => 'Product Type', 'manufacturers' => 'Manufacturers', 'ethical_committees' => 'Ethical Committees', 'coordinating_investigator' => 'Co-ordinating Investigator', 'principal_investigators' => 'Principal Investigators', 'pharmacists' => 'Pharmacists', 'sponsors' => 'Sponsors', 'number_participants' => 'Participants in Kenya', 'total_enrolment_per_site' => 'Enrolment per Kenyan site', 'total_participants_worldwide' => 'Participants worldwide', 'population_less_than_18_years' => 'Less than 18 years', 'population_utero' => 'In Utero', 'population_preterm_newborn' => 'Preterm newborn', 'population_newborn' => 'Newborn', 'population_infant_and_toddler' => 'Infant', 'population_children' => 'Children', 'population_adolescent' => 'Adolescent', 'population_above_18' => '18 Years and over', 'population_adult' => 'Adult', 'population_elderly' => 'Elderly', 'subjects_healthy' => 'Healthy volunteers', 'subjects_vulnerable_populations' => 'Specific vulnerable populations', 'vulnerable_populations' => 'Vulnerable populations', 'gender_female' => 'Gender female', 'gender_male' => 'Gender male', 'single_site_member_state' => 'Singel site in Kenya', 'location_of_area' => 'Single site name', 'multiple_sites_member_state' => 'Multiple sites in Kenya', 'number_of_sites' => 'Number of sites in Kenya', 'sites' => 'Multiple site names', 'multiple_countries' => 'Multiple countries', 'multiple_member_states' => 'Number of states', 'multi_country_list' => 'List of countries', 'data_monitoring_committee' => 'Data monitoring committee', 'placebo_present' => 'Placebo', 'placebo_details' => 'Placebo details', 'scope_of_trial' => 'Scope of the trial', 'trial_phase' => 'Trial Type and Phase', 'trial_design' => 'Design of the trial', 'organisations_transferred_' => 'Sponsor transferred duties', 'organizations' => 'Organisations sponsor transferred', 'estimated_duration' => 'Estimated duration', 'declaration_applicant' => 'Applicant (local contact)', 'declaration_date1' => 'Applicant declaration date', 'declaration_principal_investigator' => 'Principal investigator'
            );

if($redir != 'applicant') $header['reviewers'] = 'Assigned Reviewers';

echo implode(',', $header)."\n";
// debug($data);
foreach ($applications as $application):
	/*foreach ($row['Application'] as &$cell):
		// Escape double quotation marks
		$cell = '"' . preg_replace('/"/','""',$cell) . '"';
	endforeach;*/
	// echo implode(',', $row['Application']) . "\n";
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		// pr($application);
		//if simple match 
		// pr($row['Application']);
		if (array_key_exists($key, $application['Application'])) {
			// $content .= '"' . preg_replace('/"/','""',$row['Application'][$key]) . '",';
			$row[$key] = '"' . preg_replace('/"/','""',$application['Application'][$key]) . '"';
		} 
		elseif ($key == 'study_routes') {
			foreach ($application['StudyRoute'] as $study_route) {
				(isset($row[$key])) ? $row[$key] .= '; '.$study_route['study_route'] : $row[$key] = $study_route['study_route'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'product_type') {
			$row[$key] = '';
			if($application['Application']['product_type_biologicals']) $row[$key] = 'Biologicals';
			if($application['Application']['product_type_proteins']) $row[$key] .= '; Proteins';
			if($application['Application']['product_type_immunologicals']) $row[$key] .= '; Immunologicals';
			if($application['Application']['product_type_vaccines']) $row[$key] .= '; Vaccines';
			if($application['Application']['product_type_hormones']) $row[$key] .= '; Hormones';
			if($application['Application']['product_type_toxoid']) $row[$key] .= '; Toxoid';
			if($application['Application']['product_type_chemical']) $row[$key] .= '; Chemical';
			if($application['Application']['product_type_chemical_name']) $row[$key] .= ': '.$application['Application']['product_type_chemical_name'];
			if($application['Application']['product_type_medical_device']) $row[$key] .= '; Medical Device';
			if($application['Application']['product_type_medical_device_name']) $row[$key] .= ': '.$application['Application']['product_type_medical_device_name'];
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'manufacturers') {
			foreach ($application['Manufacturer'] as $manufacturer) {
				(isset($row[$key])) ? $row[$key] .= '; '.$manufacturer['manufacturer_name'] : $row[$key] = $manufacturer['manufacturer_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'ethical_committees') {
			foreach ($application['EthicalCommittee'] as $ethical_committee) {
				(isset($row[$key])) ? $row[$key] .= '; '.$ethical_committee['ethical_committee'] : $row[$key] = $ethical_committee['ethical_committee'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'coordinating_investigator') {
			$row[$key] = $application['Application']['investigator1_given_name'].' '.$application['Application']['investigator1_middle_name'].' '.$application['Application']['investigator1_family_name'];	
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'principal_investigators') {
			foreach ($application['InvestigatorContact'] as $principal_investigator) {
				(isset($row[$key])) ? $row[$key] .= '; '.$principal_investigator['given_name'].' '.$principal_investigator['middle_name'].' '.$principal_investigator['family_name'] : $row[$key] = $principal_investigator['given_name'].' '.$principal_investigator['middle_name'].' '.$principal_investigator['family_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'pharmacists') {
			foreach ($application['Pharmacist'] as $pharmacist) {
				(isset($row[$key])) ? $row[$key] .= '; REG: '.$pharmacist['reg_no'].' Name: '.$pharmacist['given_name'] : $row[$key] = 'REG: '.$pharmacist['reg_no'].' Name: '.$pharmacist['given_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'sponsors') {
			foreach ($application['Sponsor'] as $sponsor) {
				(isset($row[$key])) ? $row[$key] .= '; '.$sponsor['sponsor'].' Contact: '.$sponsor['contact_person'] : $row[$key] = $sponsor['sponsor'].' Contact: '.$sponsor['contact_person'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'vulnerable_populations') {
			$row[$key] = '';
			if($application['Application']['subjects_patients'] == 'Yes') $row[$key] = 'Patients';
			if($application['Application']['subjects_women_child_bearing'] == 'Yes') $row[$key] .= '; Women of child bearing potential';
			if($application['Application']['subjects_women_using_contraception'] == 'Yes') $row[$key] .= '; Women of child bearing potential using contraception';
			if($application['Application']['subjects_pregnant_women'] == 'Yes') $row[$key] .= '; Pregnant women';
			if($application['Application']['subjects_nursing_women'] == 'Yes') $row[$key] .= '; Nursing women';
			if($application['Application']['subjects_emergency_situation'] == 'Yes') $row[$key] .= '; Emergency situation';
			if($application['Application']['subjects_incapable_consent'] == 'Yes') $row[$key] .= '; Subjects incapable of giving consent personally';
			if($application['Application']['subjects_specify']) $row[$key] .= ': '.$application['Application']['subjects_specify'];
			if($application['Application']['subjects_others'] == 'Yes') $row[$key] .= '; Others';
			if($application['Application']['subjects_others_specify']) $row[$key] .= ': '.$application['Application']['subjects_others_specify'];
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'sites') {
			foreach ($application['SiteDetail'] as $site) {
				(isset($row[$key])) ? $row[$key] .= '; '.$site['site_name'].' Contact: '.$site['contact_details'] : $row[$key] = $site['site_name'].' Contact: '.$site['contact_details'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'scope_of_trial') {
			$row[$key] = '';
			if($application['Application']['scope_diagnosis']) $row[$key] = 'Diagnosis';
			if($application['Application']['scope_prophylaxis']) $row[$key] .= '; Prophylaxis';
			if($application['Application']['scope_therapy']) $row[$key] .= '; Therapy';
			if($application['Application']['scope_safety']) $row[$key] .= '; Safety';
			if($application['Application']['scope_efficacy']) $row[$key] .= '; Efficacy';
			if($application['Application']['scope_pharmacokinetic']) $row[$key] .= '; Pharmacokinetic';
			if($application['Application']['scope_pharmacodynamic']) $row[$key] .= '; Pharmacodynamic';
			if($application['Application']['scope_bioequivalence']) $row[$key] .= '; Bioequivalence';
			if($application['Application']['scope_dose_response']) $row[$key] .= '; Dose Response';
			if($application['Application']['scope_pharmacogenetic']) $row[$key] .= '; Pharmacogenetic';
			if($application['Application']['scope_pharmacogenomic']) $row[$key] .= '; Pharmacogenomic';
			if($application['Application']['scope_pharmacoecomomic']) $row[$key] .= '; Pharmacoecomomic';
			if($application['Application']['product_type_medical_device']) $row[$key] .= '; Medical';
			if($application['Application']['scope_others']) $row[$key] .= '; Others';
			if($application['Application']['scope_others_specify']) $row[$key] .= ': '.$application['Application']['scope_others_specify'];
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'trial_phase') {
			$row[$key] = '';
			if($application['Application']['trial_human_pharmacology']) $row[$key] = 'Human pharmacology (Phase I)';
			if($application['Application']['trial_administration_humans']) $row[$key] .= ': First administration to humans';
			if($application['Application']['trial_bioequivalence_study']) $row[$key] .= ', Bioequivalence study';
			if($application['Application']['trial_other']) $row[$key] .= ', Other: ';
			if($application['Application']['trial_other_specify']) $row[$key] .= $application['Application']['trial_other_specify'];
			if($application['Application']['trial_therapeutic_exploratory']) $row[$key] .= '; Therapeutic exploratory (Phase II)';
			if($application['Application']['trial_therapeutic_confirmatory']) $row[$key] .= '; Therapeutic confirmatory (Phase III) ';
			if($application['Application']['trial_therapeutic_use']) $row[$key] .= '; Therapeutic use (Phase IV)';
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
		 elseif ($key == 'trial_design') {
			$row[$key] = '';
			if($application['Application']['design_controlled'] == 'Yes') $row[$key] = 'Controlled: ';
			if($application['Application']['design_controlled_randomised'] == 'Yes') $row[$key] = 'Randomised ';
			if($application['Application']['design_controlled_open'] == 'Yes') $row[$key] .= '; Open';
			if($application['Application']['design_controlled_single_blind'] == 'Yes') $row[$key] .= '; Single Blind';
			if($application['Application']['design_controlled_double_blind'] == 'Yes') $row[$key] .= '; Double Blind';
			if($application['Application']['design_controlled_parallel_group'] == 'Yes') $row[$key] .= '; Parallel group';
			if($application['Application']['design_controlled_cross_over'] == 'Yes') $row[$key] .= '; Cross over';
			if($application['Application']['design_controlled_other'] == 'Yes') $row[$key] .= '; Other';
			if($application['Application']['design_controlled_specify']) $row[$key] .= $application['Application']['design_controlled_specify'];
			if($application['Application']['design_controlled_comparator']) $row[$key] .= ': Comparator: '.$application['Application']['design_controlled_comparator'];
			if($application['Application']['design_controlled_other_medicinal'] == 'Yes') $row[$key] .= '; Other product(s)';
			if($application['Application']['design_controlled_placebo'] == 'Yes') $row[$key] .= '; Placebo';
			if($application['Application']['design_controlled_medicinal_other'] == 'Yes') $row[$key] .= '; Other';
			if($application['Application']['design_controlled_medicinal_specify']) $row[$key] .= $application['Application']['design_controlled_medicinal_specify'];
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'organizations') {
			foreach ($application['Organization'] as $org) {
				(isset($row[$key])) ? $row[$key] .= '; Org: '.$org['organization'].' Contact: '.$org['contact_person'] : $row[$key] = 'Org: '.$org['organization'].' Contact: '.$org['contact_person'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'placebo_details') {
			foreach ($application['Placebo'] as $plc) {
				(isset($row[$key])) ? $row[$key] .= '; Form: '.$plc['pharmaceutical_form'].' Route: '.$plc['route_of_administration'] : $row[$key] = 'Form: '.$plc['pharmaceutical_form'].' Route: '.$plc['route_of_administration'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
		elseif ($key == 'reviewers') {
			$row[$key] = '';
			foreach ($application['Review'] as $review) {
				if ($review['accepted'] == 'accepted') {
					$row[$key] .= $review['User']['name']."; ";
				}				
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		}
	}
	echo implode(',', $row) . "\n";
endforeach;

?>
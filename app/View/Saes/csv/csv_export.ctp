<?php

// app/Views/Subscribers/export.ctp
$header = array('id' => '#', 'protocol_no' => 'Protocol No.', 'reference_no' => 'Reference No.', 'patient_initials' => 'Patient Initials', 'country_id' => 'Country', 'date_of_birth' => 'Date of Birth', 'age_years' => 'Age years', 'reaction_onset' => 'Reaction onset', 'duration' => 'Duration drug and SAE', 'gender' => 'Gender', 'adverse_reaction' => 'Adverse reaction', 'reaction_description' => 'Reaction description', 'ip_not_given' => 'IP Not Given', 'ip_not_given_narrative' => 'Reason IP Not Given', 'suspected_drugs' => 'Suspected drugs', 'concomitant_drugs' => 'Concomitant drug', 'relevant_history' => 'History', 'manufacturer_name' => 'Manufacturer', 'mfr_no' => 'MFR Control No.', 'manufacturer_date' => 'Date received by manufacturer', 'report_source' => 'Report source', 'reporter_name' => 'Reporter name', 'reporter_phone' => 'Reporter phone', 'reporter_email' => 'Reporter email'
            );


echo implode(',', $header)."\n";
// debug($data);
foreach ($csaes as $sae):
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		
	  if (array_key_exists($key, $sae['Sae'])) {
                if ($key == 'ip_not_given') {
                    $row[$key] = (!empty($sae['Sae']['ip_not_given'])) ? '"Yes"' : '"No"';
                } elseif ($key == 'ip_not_given_narrative') {
                    $row[$key] = (!empty($sae['Sae']['ip_not_given']) && !empty($sae['Sae']['ip_not_given_narrative'])) 
                        ? '"' . preg_replace('/"/','""',$sae['Sae']['ip_not_given_narrative']) . '"' 
                        : '""';
                } else {
                    $row[$key] = '"' . preg_replace('/"/','""',$sae['Sae'][$key]) . '"';
                }
            } elseif ($key == 'protocol_no') {
    
			$row[$key] = '"' . preg_replace('/"/','""',$sae['Application'][$key]) . '"';
		} elseif ($key == 'country_id') {
			$row[$key] = '"' . preg_replace('/"/','""',$sae['Country']['name']) . '"';
		} elseif($key == 'duration') { 
			$sd = new DateTime($sae['Sae']['reaction_onset']);
			$ed = (!empty(Hash::extract($sae, 'SuspectedDrug.{n}.date_from'))) ? new DateTime(min(Hash::extract($sae, 'SuspectedDrug.{n}.date_from'))) : new DateTime();
			// $ed = new DateTime($v);
			$row[$key] = '"' . preg_replace('/"/','""',$ed->diff($sd)->format('%a')) . '"';
		} elseif ($key == 'adverse_reaction') {
			$row[$key] = '';
			if($sae['Sae']['patient_died']) $row[$key] = 'Patient Died';
			if($sae['Sae']['prolonged_hospitalization']) $row[$key] .= '; Prolonged Hospitalization';
			if($sae['Sae']['incapacity']) $row[$key] .= '; Disability or Incapacity';
			if($sae['Sae']['life_threatening']) $row[$key] .= '; Life Threatening';
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} elseif ($key == 'suspected_drugs') {
			foreach ($sae['SuspectedDrug'] as $sdrug) {
				(isset($row[$key])) ? $row[$key] .= '; '.$sdrug['generic_name'] : $row[$key] = $sdrug['generic_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} elseif ($key == 'concomitant_drugs') {
			foreach ($sae['ConcomittantDrug'] as $cdrug) {
				(isset($row[$key])) ? $row[$key] .= '; '.$cdrug['generic_name'] : $row[$key] = $cdrug['generic_name'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} elseif ($key == 'report_source') {
			$row[$key] = '';
			if($sae['Sae']['source_study']) $row[$key] = 'Study';
			if($sae['Sae']['source_literature']) $row[$key] .= '; Literature';
			if($sae['Sae']['source_health_professional']) $row[$key] .= '; Health professional';
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} 
	}
	echo implode(',', $row) . "\n";
endforeach;

?>
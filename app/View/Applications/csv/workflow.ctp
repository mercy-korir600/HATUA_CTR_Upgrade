<?php

// app/Views/Subscribers/export.ctp
$header = array('id' => '#', 'protocol_no' => 'ECCT Reference No.', 'short_title' => 'Short Title', 'version_no' => 'Version No.', 'date_of_protocol' => 'Date of protocol', 
				'study_drug' => 'Study Drug', 
				'application_stages' => 'Application stages'
            );

if($redir != 'applicant') $header['reviewers'] = 'Assigned Reviewers';

echo implode(',', $header)."\n";
foreach ($applications as $application):
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		if (array_key_exists($key, $application['Application'])) {
			// $content .= '"' . preg_replace('/"/','""',$row['Application'][$key]) . '",';
			$row[$key] = '"' . preg_replace('/"/','""',$application['Application'][$key]) . '"';
		} elseif ($key == 'application_stages') {
			foreach ($application['ApplicationStage'] as $stage) {
				(isset($row[$key])) ? $row[$key] .= '; '.$stage['stage'].':'.$stage['start_date'].':'.$stage['end_date'] : $row[$key] = $stage['stage'].':'.$stage['start_date'].':'.$stage['end_date'];
			}
			(isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/','""',$row[$key]) . '"' : $row[$key] = '""';
		} elseif ($key == 'reviewers') {
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
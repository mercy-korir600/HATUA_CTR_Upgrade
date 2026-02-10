<?php

// app/Views/Subscribers/export.ctp
$header = array('id' => '#', 'protocol_no' => 'ECCT Reference No.', 'reference_no' => 'Reference No.', 'reporter_email' => 'Reporter email', 'basename' => 'Filename', 'created' => 'Creation date');


echo implode(',', $header)."\n";
// debug($data);
foreach ($cioms as $ciom):
	$content = '';
	$row = [];
	foreach ($header as $key => $val) {
		
		if (array_key_exists($key, $ciom['Ciom'])) {
			$row[$key] = '"' . preg_replace('/"/','""',$ciom['Ciom'][$key]) . '"';
		} elseif ($key == 'protocol_no') {
			$row[$key] = '"' . preg_replace('/"/','""',$ciom['Application'][$key]) . '"';
		}  
	}
	echo implode(',', $row) . "\n";
endforeach;

?>
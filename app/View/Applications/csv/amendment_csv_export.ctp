<?php
App::uses('Hash', 'Utility');
$header = array('id' => '#', 'protocol_no' => 'ECCT Reference No.', 'amendment' => 'Amendments');
echo implode(',', $header) . "\n";
foreach ($applications as $application) :
    $content = '';
    $row = [];

    // debug($application);
    // exit;

    foreach ($header as $key => $val) {
        if (array_key_exists($key, $application['Application'])) {
            $row[$key] = '"' . preg_replace('/"/', '""', $application['Application'][$key]) . '"';
        } elseif ($key == 'amendment') {
            if (!empty($application['AmendmentApproval'])) {

                $years = array_unique(Hash::extract($application['AmendmentChecklist'], '{n}.year'));
                rsort($years);
                foreach ($years as $year) {
                    foreach ($application['AmendmentApproval'] as $apr) {

                        if ($apr['amendment'] == $year) {

                            (isset($row[$key])) ? $row[$key] .= '; ' . $year.'-'.$apr['approval_date'] : $row[$key] = $year.'-'.$apr['approval_date'] ;
                        }
                    }
                }
                (isset($row[$key])) ? $row[$key] = '"' . preg_replace('/"/', '""', $row[$key]) . '"' : $row[$key] = '""';
            }
        }
    }
    echo implode(',', $row) . "\n";
endforeach;

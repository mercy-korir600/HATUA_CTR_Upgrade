<?php
$header = array(
    'reference_no' => 'Reference_No', 'protocol_no' => 'Protocol_No', 'reviewer' => 'Reviewer',
    'status' => 'Status', 'opened_date' => 'Opened_Date', 'deadline_date' => 'Deadline_Date',
    'days_overdue' => 'Days_Overdue_At_Open', 'description' => 'Description',
);

echo implode(',', $header) . "\n";
foreach ($capas as $capa):
    $values = array(
        'reference_no' => $capa['Capa']['reference_no'],
        'protocol_no' => !empty($capa['Application']['protocol_no']) ? $capa['Application']['protocol_no'] : '',
        'reviewer' => !empty($capa['Reviewer']['name']) ? $capa['Reviewer']['name'] : '',
        'status' => $capa['Capa']['status'],
        'opened_date' => !empty($capa['Capa']['created']) ? date('Y-m-d', strtotime($capa['Capa']['created'])) : '',
        'deadline_date' => !empty($capa['Capa']['deadline_date']) ? date('Y-m-d', strtotime($capa['Capa']['deadline_date'])) : '',
        'days_overdue' => (int) $capa['Capa']['days_overdue'],
        'description' => $capa['Capa']['description'],
    );
    $row = array();
    foreach ($values as $val) {
        $row[] = '"' . preg_replace('/"/', '""', $val) . '"';
    }
    echo implode(',', $row) . "\n";
endforeach;

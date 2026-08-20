<?php
$header = array('id' => '#', 
    'reference_no' => 'Reference_No', 'type' => 'Type', 'protocol_no' => 'Protocol No', 'reviewer' => 'Reviewer',
    'status' => 'Status', 'closed_date' => 'Closed Date', 'opened_date' => 'Opened Date', 'deadline_date' => 'Deadline Date',
    'days_overdue' => 'Days Overdue At Open', 'description' => 'Description',
);

echo implode(',', $header) . "\n";
$count=0;
foreach ($capas as $capa):
    $count++;
    $values = array(
        'id' => $count,
        'reference_no' => $capa['Capa']['reference_no'],
        'type' => $capa['Capa']['type'] === 'FollowUp' ? 'Follow-up' : 'Initial',
        'protocol_no' => !empty($capa['Application']['protocol_no']) ? $capa['Application']['protocol_no'] : '',
        'reviewer' => !empty($capa['Reviewer']['name']) ? $capa['Reviewer']['name'] : '',
        'status' => $capa['Capa']['status'],
        'closed_date' => !empty($capa['Capa']['closed_date']) ? date('Y-m-d H:i', strtotime($capa['Capa']['closed_date'])) : '',
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

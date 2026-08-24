<?php
// 'reviewer' is labelled "Responsible Person" here - per the CAPA.doc
// format, that's just the reviewer whose missed deadline opened the
// case, not a separately stored value (see Capa.php).
$header = array('id' => '#',
    'reference_no' => 'Reference_No', 'type' => 'Type', 'protocol_no' => 'Protocol No', 'reviewer' => 'Responsible Person',
    'status' => 'Status', 'closed_date' => 'Closed Date', 'opened_date' => 'Opened Date', 'deadline_date' => 'Deadline Date',
    'days_overdue' => 'Days Overdue At Open', 'description' => 'Description of Non Conformity',
    'root_cause' => 'Root Cause', 'corrective_action' => 'Corrective/Preventive Action',
    'target_date' => 'Target Date',
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
        'root_cause' => $capa['Capa']['root_cause'],
        'corrective_action' => $capa['Capa']['corrective_action'],
        'target_date' => !empty($capa['Capa']['target_date']) ? date('Y-m-d', strtotime($capa['Capa']['target_date'])) : '',
    );
    $row = array();
    foreach ($values as $val) {
        $row[] = '"' . preg_replace('/"/', '""', $val) . '"';
    }
    echo implode(',', $row) . "\n";
endforeach;

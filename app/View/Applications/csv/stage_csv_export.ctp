<?php
$stageColumns = array(
    'application_creation' => array('key' => 'Creation', 'label' => 'Application Creation', 'annual' => false),
    'screening' => array('key' => 'Screening', 'label' => 'Screening', 'annual' => false),
    'response_to_queries' => array('key' => 'ScreeningSubmission', 'label' => 'Response to Queries', 'annual' => false),
    'assigned_to_reviewers' => array('key' => 'Assign', 'label' => 'Assigned to Reviewers', 'annual' => false),
    'review_comments' => array('key' => 'Review', 'label' => 'Review Comments', 'annual' => false),
    'sponsor_feedback' => array('key' => 'ReviewSubmission', 'label' => 'Sponsor Feedback', 'annual' => false),
    'final_decision' => array('key' => 'FinalDecision', 'label' => 'Final Decision', 'annual' => false),
    'annual_approval' => array('key' => 'AnnualApproval', 'label' => 'Annual Approval', 'annual' => true),
);

$header = array(
    'id' => '#',
    'protocol_no' => 'ECCT Reference No.',
    'application_creation' => 'Application Creation',
    'screening' => 'Screening',
    'response_to_queries' => 'Response to Queries',
    'assigned_to_reviewers' => 'Assigned to Reviewers',
    'review_comments' => 'Review Comments',
    'sponsor_feedback' => 'Sponsor Feedback',
    'final_decision' => 'Final Decision',
    'annual_approval' => 'Annual Approval',
    'date_submitted' => 'Date Submitted',
    'approval_date' => 'Date Approved'
);

echo implode(',', $header) . "\n";

foreach ($applications as $application) :
    $row = array();
    $stages = $this->requestAction('applications/stages/' . $application['Application']['id']);

    foreach ($header as $key => $label) {
        if (array_key_exists($key, $application['Application'])) {
            $row[$key] = '"' . preg_replace('/"/', '""', $application['Application'][$key]) . '"';
            continue;
        }

        if (isset($stageColumns[$key])) {
            $stageKey = $stageColumns[$key]['key'];
            $stageValue = '';

            if (isset($stages[$stageKey])) {
                $stageDate = !empty($stages[$stageKey]['start_date']) ? $stages[$stageKey]['start_date'] : '';
                $stageDays = ($stages[$stageKey]['days'] === '' || $stages[$stageKey]['days'] === null)
                    ? ''
                    : (string)$stages[$stageKey]['days'];
                $stageValue = $stageDate;

                if ($stageDays !== '') {
                    $dayUnit = ($stageDays === '0' || $stageDays === '1') ? 'day' : 'days';
                    if ($stageColumns[$key]['annual']) {
                        $stageValue = trim($stageDate . ' (' . $stageDays . ' ' . $dayUnit . ' to expiry)');
                    } else {
                        $stageValue = trim($stageDate . ' (' . $stageDays . ' ' . $dayUnit . ')');
                    }
                }
            }

            $row[$key] = '"' . preg_replace('/"/', '""', $stageValue) . '"';
            continue;
        }

        $row[$key] = '""';
    }

    echo implode(',', $row) . "\n";
endforeach;

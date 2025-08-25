<?php

$header = array(
    'id' => '#',
    'protocol_no' => 'Protocol No.',
    'created' => 'Application Creation',
    'screening_stage' => 'Screening',
    'response_to_queries_stage' => 'Response to Queries',
    'assigned_to_reviewers_stage' => 'Assigned to Reviewers',
    'review_comments_stage' => 'Review Comments',
    'sponsor_feedback_stage' => 'Sponsor Feedback',
    'final_decision_stage' => 'Final Decision',
    'annual_approval_stage' => 'Annual Approval',
    'date_submitted' => 'Date Submitted',
    'approval_date' => 'Date Approved'
);
echo implode(',', $header) . "\n";
foreach ($applications as $application) :
    $content = '';
    $row = [];
    $stages = $this->requestAction(
        'applications/stages/' . $application['Application']['id']  //get first element of array
    );
    foreach ($header as $key => $val) {
        if (array_key_exists($key, $application['Application'])) {
            $row[$key] = '"' . preg_replace('/"/', '""', $application['Application'][$key]) . '"';
        } elseif ($key == 'screening_stage') {
            $stage = Hash::extract($stages, 'Screening.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'response_to_queries_stage') {
            $stage = Hash::extract($stages, 'ScreeningSubmission.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'assigned_to_reviewers_stage') {
            $stage = Hash::extract($stages, 'Assign.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'review_comments_stage') {
            $stage = Hash::extract($stages, 'Review.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'sponsor_feedback_stage') {
            $stage = Hash::extract($stages, 'ReviewSubmission.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'final_decision_stage') {
            $stage = Hash::extract($stages, 'FinalDecision.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        }
        elseif ($key == 'annual_approval_stage') {
            $stage = Hash::extract($stages, 'AnnualApproval.start_date');
            $startDate = !empty($stage) ? $stage[0] : null; 
            if (!empty($startDate) && strtotime($startDate)) {
                $row[$key] = date('d-m-y', strtotime($startDate));
            } else {
                $row[$key] = '';
            }
        } else {
            $row[$key] = '';
        }
    }
    echo implode(',', $row) . "\n";
endforeach;

<?php
App::uses('Hash', 'Utility');

$summary = !empty($amendmentTimelineSummary) && is_array($amendmentTimelineSummary)
    ? $amendmentTimelineSummary
    : array('rows' => array(), 'max_amendments' => 0);

$rows = !empty($summary['rows']) && is_array($summary['rows']) ? $summary['rows'] : array();

$formatProtocolReference = function ($protocolNo) {
    $protocolNo = trim((string)$protocolNo);
    if ($protocolNo === '') {
        return '';
    }

    $protocolNo = html_entity_decode($protocolNo, ENT_QUOTES, 'UTF-8');
    $protocolNo = preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $protocolNo);
    $protocolNo = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2212}]/u', '-', $protocolNo);

    return preg_replace_callback(
        '/\s*-?\s*AMD\s*([0-9]+(?:\.[0-9]+)?)\s*$/iu',
        function ($matches) {
            $number = trim((string)$matches[1]);
            if (is_numeric($number)) {
                $numericValue = (float)$number;
                if (floor($numericValue) == $numericValue) {
                    $number = (string)(int)$numericValue;
                } else {
                    $number = rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
                }
            }
            return ' AMD-' . $number;
        },
        $protocolNo
    );
};

$escape = function ($value) {
    return '"' . str_replace('"', '""', (string)$value) . '"';
};

$header = array('#', 'ECCT Reference No.', 'Amendment', 'Created', 'Submitted', 'Review', 'Approval');
echo implode(',', array_map($escape, $header)) . "\n";

$count = 0;
foreach ($rows as $rowData) {
    $application = !empty($rowData['application']) ? $rowData['application'] : array();
    $timelines = !empty($rowData['timelines']) && is_array($rowData['timelines']) ? $rowData['timelines'] : array();
    $protocolNo = Hash::get($application, 'Application.protocol_no', '');
    $formattedProtocolNo = $formatProtocolReference($protocolNo);

    if (empty($timelines)) {
        $count++;
        $row = array($count, $formattedProtocolNo, '', '', '', '', '');
        echo implode(',', array_map($escape, $row)) . "\n";
        continue;
    }

    foreach ($timelines as $timelineIndex => $timeline) {
        $count++;
        $referenceValue = ($timelineIndex === 0) ? $formattedProtocolNo : '';
        $row = array(
            $count,
            $referenceValue,
            !empty($timeline['label']) ? (string)$timeline['label'] : '',
            Hash::get($timeline, 'stages.created.date', '-'),
            Hash::get($timeline, 'stages.submitted.date', '-'),
            Hash::get($timeline, 'stages.review.date', '-'),
            Hash::get($timeline, 'stages.approved.date', '-')
        );
        echo implode(',', array_map($escape, $row)) . "\n";
    }
}

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
    $protocolNo = str_replace(array("\xC2\xA0", "\xA0"), ' ', $protocolNo);
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

$formatAmendmentLabel = function ($label) {
    $label = html_entity_decode((string)$label, ENT_QUOTES, 'UTF-8');
    $label = str_replace(array("\xC2\xA0", "\xA0"), ' ', $label);
    $label = preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $label);
    $label = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2212}]/u', '-', $label);
    if (preg_match('/([0-9]+(?:\.[0-9]+)?)/', (string)$label, $matches)) {
        $label = (string)$matches[1];
    }
    $label = trim((string)$label);
    $label = preg_replace('/^[\-\s_]+/', '', $label);
    $label = preg_replace('/^amd[\s_-]*/iu', '', $label);
    $label = trim((string)$label);

    if ($label === '') {
        return 'AMD';
    }

    if (is_numeric($label)) {
        $numericValue = (float)$label;
        if (floor($numericValue) == $numericValue) {
            $label = (string)(int)$numericValue;
        } else {
            $label = rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
        }
    }

    return 'AMD-' . strtoupper((string)$label);
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
            $formatAmendmentLabel(!empty($timeline['label']) ? (string)$timeline['label'] : ''),
            Hash::get($timeline, 'stages.created.date', '-'),
            Hash::get($timeline, 'stages.submitted.date', '-'),
            Hash::get($timeline, 'stages.review.date', '-'),
            Hash::get($timeline, 'stages.approved.date', '-')
        );
        echo implode(',', array_map($escape, $row)) . "\n";
    }
}

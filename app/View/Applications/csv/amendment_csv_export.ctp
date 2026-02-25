<?php
App::uses('Hash', 'Utility');

$extractAmendmentNumber = function ($value) {
    if (preg_match('/(\d+)/', (string)$value, $matches)) {
        return (int)$matches[1];
    }
    return null;
};

$normalizeAmendmentDisplayNumber = function ($value) {
    $normalized = trim((string)$value);
    if ($normalized === '') {
        return '';
    }

    if (is_numeric($normalized)) {
        $numericValue = (float)$normalized;
        if (floor($numericValue) == $numericValue) {
            return (string)(int)$numericValue;
        }

        return rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
    }

    return $normalized;
};

$formatProtocolReference = function ($protocolNo) use ($normalizeAmendmentDisplayNumber) {
    $protocolNo = trim((string)$protocolNo);
    if ($protocolNo === '') {
        return '';
    }

    return preg_replace_callback(
        '/\s*-?\s*AMD\s*([0-9]+(?:\.[0-9]+)?)\s*$/i',
        function ($matches) use ($normalizeAmendmentDisplayNumber) {
            return ' AMD-' . $normalizeAmendmentDisplayNumber($matches[1]);
        },
        $protocolNo
    );
};

$buildAmendmentDates = function ($application) use ($extractAmendmentNumber) {
    $datesByAmendment = array();

    $checklists = Hash::get($application, 'AmendmentChecklist', array());
    foreach ($checklists as $checklist) {
        $amendmentKey = trim((string)Hash::get($checklist, 'year', ''));
        if ($amendmentKey === '') {
            continue;
        }

        if (!array_key_exists($amendmentKey, $datesByAmendment)) {
            $datesByAmendment[$amendmentKey] = '';
        }

        $fileDate = trim((string)Hash::get($checklist, 'file_date', ''));
        if ($fileDate !== '' && $datesByAmendment[$amendmentKey] === '') {
            $datesByAmendment[$amendmentKey] = $fileDate;
        }
    }

    $approvals = Hash::get($application, 'AmendmentApproval', array());
    foreach ($approvals as $approval) {
        $amendmentKey = trim((string)Hash::get($approval, 'amendment', ''));
        if ($amendmentKey === '') {
            continue;
        }

        if (!array_key_exists($amendmentKey, $datesByAmendment)) {
            $datesByAmendment[$amendmentKey] = '';
        }

        $approvalDate = trim((string)Hash::get($approval, 'approval_date', ''));
        if ($approvalDate !== '') {
            $datesByAmendment[$amendmentKey] = $approvalDate;
        }
    }

    if (empty($datesByAmendment)) {
        return array();
    }

    $amendmentKeys = array_keys($datesByAmendment);
    usort($amendmentKeys, function ($left, $right) use ($extractAmendmentNumber) {
        $leftNumber = $extractAmendmentNumber($left);
        $rightNumber = $extractAmendmentNumber($right);

        if ($leftNumber === $rightNumber) {
            return strnatcasecmp((string)$left, (string)$right);
        }
        if ($leftNumber === null) {
            return 1;
        }
        if ($rightNumber === null) {
            return -1;
        }
        return ($leftNumber < $rightNumber) ? -1 : 1;
    });

    $dates = array();
    foreach ($amendmentKeys as $amendmentKey) {
        $dates[] = $datesByAmendment[$amendmentKey];
    }

    return $dates;
};

$rows = array();
$maxAmendments = 0;

foreach ($applications as $application) {
    $dates = $buildAmendmentDates($application);
    if (empty($dates)) {
        continue;
    }

    $rows[] = array('application' => $application, 'dates' => $dates);
    $maxAmendments = max($maxAmendments, count($dates));
}

$header = array('#', 'ECCT Reference No.');
for ($column = 1; $column <= $maxAmendments; $column++) {
    $header[] = 'AMD-' . $column;
}

$escape = function ($value) {
    return '"' . str_replace('"', '""', (string)$value) . '"';
};

echo implode(',', array_map($escape, $header)) . "\n";

$count = 0;
foreach ($rows as $rowData) {
    $count++;
    $protocolNo = Hash::get($rowData, 'application.Application.protocol_no', '');
    $row = array(
        $count,
        $formatProtocolReference($protocolNo)
    );

    for ($column = 0; $column < $maxAmendments; $column++) {
        $row[] = isset($rowData['dates'][$column]) ? $rowData['dates'][$column] : '';
    }

    echo implode(',', array_map($escape, $row)) . "\n";
}

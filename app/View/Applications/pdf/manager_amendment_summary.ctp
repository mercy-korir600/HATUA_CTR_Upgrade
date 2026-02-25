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

    $protocolNo = html_entity_decode($protocolNo, ENT_QUOTES, 'UTF-8');
    $protocolNo = preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $protocolNo);
    $protocolNo = preg_replace('/[\x{2010}\x{2011}\x{2012}\x{2013}\x{2014}\x{2212}]/u', '-', $protocolNo);

    return preg_replace_callback(
        '/\s*-?\s*AMD\s*([0-9]+(?:\.[0-9]+)?)\s*$/iu',
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
?>
<div style="text-align: center;">
    <h3 style="text-align: center;">
        <?php
        echo $this->Html->image('cake.power.png', array(
            'fullBase' => true, 'alt' => 'Pharmacy and Poisons Board',
            'style' => 'border: 0; float: center; margin-right: 10px; margin-bottom: 10px;'
        ));
        ?>
    </h3>
    <p style="text-align: center;">
        <span style="font-family:bookman old style,serif;"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span>
    </p>
    <p style="text-align: center;">
        <span style="font-family:bookman old style,serif;"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span>
    </p>
</div>

<div class="row-fluid">
    <div class="span12">
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 20%;">ECCT Reference No.</th>
                    <?php for ($column = 1; $column <= $maxAmendments; $column++) { ?>
                        <th>AMD-<?php echo $column; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)) { ?>
                    <tr>
                        <td colspan="<?php echo 2 + $maxAmendments; ?>">No amendments found.</td>
                    </tr>
                <?php } else { ?>
                    <?php
                    $count = 0;
                    foreach ($rows as $rowData) {
                        $count++;
                        $protocolNo = Hash::get($rowData, 'application.Application.protocol_no', '');
                    ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo h($formatProtocolReference($protocolNo)); ?></td>
                            <?php for ($column = 0; $column < $maxAmendments; $column++) { ?>
                                <td><?php echo h(isset($rowData['dates'][$column]) ? $rowData['dates'][$column] : ''); ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 0 auto;
    }

    th,
    td {
        border: 1px solid gray;
        padding: 8px;
        text-align: left;
    }
</style>

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

$tableRows = array();
foreach ($rows as $rowData) {
    $application = !empty($rowData['application']) ? $rowData['application'] : array();
    $timelines = !empty($rowData['timelines']) && is_array($rowData['timelines']) ? $rowData['timelines'] : array();
    $protocolNo = Hash::get($application, 'Application.protocol_no', '');
    $formattedProtocolNo = $formatProtocolReference($protocolNo);

    if (empty($timelines)) {
        $tableRows[] = array(
            'protocol_no' => $formattedProtocolNo,
            'amendment' => '',
            'created' => '',
            'submitted' => '',
            'review' => '',
            'approval' => ''
        );
        continue;
    }

    foreach ($timelines as $timelineIndex => $timeline) {
        $tableRows[] = array(
            'protocol_no' => ($timelineIndex === 0) ? $formattedProtocolNo : '',
            'amendment' => $formatAmendmentLabel(!empty($timeline['label']) ? $timeline['label'] : ''),
            'created' => Hash::get($timeline, 'stages.created.date', '-'),
            'submitted' => Hash::get($timeline, 'stages.submitted.date', '-'),
            'review' => Hash::get($timeline, 'stages.review.date', '-'),
            'approval' => Hash::get($timeline, 'stages.approved.date', '-')
        );
    }
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
                    <th style="width: 16%;">ECCT Reference No.</th>
                    <th style="width: 12%;">Amendment</th>
                    <th style="width: 13%;">Created</th>
                    <th style="width: 13%;">Submitted</th>
                    <th style="width: 13%;">Review</th>
                    <th style="width: 13%;">Approval</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tableRows)) { ?>
                    <tr>
                        <td colspan="7">No amendment timelines found.</td>
                    </tr>
                <?php } else { ?>
                    <?php
                    $count = 0;
                    foreach ($tableRows as $timelineRow) {
                        $count++;
                    ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo h($timelineRow['protocol_no']); ?></td>
                            <td><?php echo h($timelineRow['amendment']); ?></td>
                            <td><?php echo h($timelineRow['created']); ?></td>
                            <td><?php echo h($timelineRow['submitted']); ?></td>
                            <td><?php echo h($timelineRow['review']); ?></td>
                            <td><?php echo h($timelineRow['approval']); ?></td>
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
        padding: 6px;
        text-align: left;
        vertical-align: top;
        font-size: 10px;
    }
</style>

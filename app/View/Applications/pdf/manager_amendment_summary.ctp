<?php
App::uses('Hash', 'Utility');

$summary = !empty($amendmentTimelineSummary) && is_array($amendmentTimelineSummary)
    ? $amendmentTimelineSummary
    : array('rows' => array(), 'max_amendments' => 0);

$rows = !empty($summary['rows']) && is_array($summary['rows']) ? $summary['rows'] : array();
$maxAmendments = !empty($summary['max_amendments']) ? (int)$summary['max_amendments'] : 0;
if ($maxAmendments < 1) {
    $maxAmendments = 1;
}

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
                    <?php for ($column = 1; $column <= $maxAmendments; $column++) { ?>
                        <th>AMD-<?php echo $column; ?> Timeline</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)) { ?>
                    <tr>
                        <td colspan="<?php echo 2 + $maxAmendments; ?>">No amendment timelines found.</td>
                    </tr>
                <?php } else { ?>
                    <?php
                    $count = 0;
                    foreach ($rows as $rowData) {
                        $count++;
                        $application = !empty($rowData['application']) ? $rowData['application'] : array();
                        $timelines = !empty($rowData['timelines']) && is_array($rowData['timelines']) ? $rowData['timelines'] : array();
                        $protocolNo = Hash::get($application, 'Application.protocol_no', '');
                    ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo h($formatProtocolReference($protocolNo)); ?></td>
                            <?php for ($column = 0; $column < $maxAmendments; $column++) { ?>
                                <td>
                                    <?php if (empty($timelines[$column])) { ?>
                                        <span class="muted">-</span>
                                    <?php } else { ?>
                                        <?php
                                        $timeline = $timelines[$column];
                                        $timelineLabel = !empty($timeline['label']) ? $timeline['label'] : ('AMD-' . ($column + 1));
                                        $createdDate = Hash::get($timeline, 'stages.created.date', '-');
                                        $submittedDate = Hash::get($timeline, 'stages.submitted.date', '-');
                                        $reviewDate = Hash::get($timeline, 'stages.review.date', '-');
                                        $approvedDate = Hash::get($timeline, 'stages.approved.date', '-');
                                        ?>
                                        <strong><?php echo h($timelineLabel); ?></strong><br>
                                        <small><strong>Created:</strong> <?php echo h($createdDate); ?></small><br>
                                        <small><strong>Submitted:</strong> <?php echo h($submittedDate); ?></small><br>
                                        <small><strong>Review:</strong> <?php echo h($reviewDate); ?></small><br>
                                        <small><strong>Approval:</strong> <?php echo h($approvedDate); ?></small>
                                    <?php } ?>
                                </td>
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
        padding: 6px;
        text-align: left;
        vertical-align: top;
        font-size: 10px;
    }
</style>

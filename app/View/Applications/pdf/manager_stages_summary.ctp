<div class="row-fluid">
  <div class="span12">
    <h3 style="text-align: center; margin-bottom: 15px;">Application Stages Summary</h3>

    <?php
    $stageColumns = array(
      array('key' => 'Creation', 'label' => 'Application<br>Creation', 'annual' => false),
      array('key' => 'Screening', 'label' => 'Screening', 'annual' => false),
      array('key' => 'ScreeningSubmission', 'label' => 'Response to<br>Queries', 'annual' => false),
      array('key' => 'Assign', 'label' => 'Assigned to<br>Reviewers', 'annual' => false),
      array('key' => 'Review', 'label' => 'Review<br>Comments', 'annual' => false),
      array('key' => 'ReviewSubmission', 'label' => 'Sponsor<br>Feedback', 'annual' => false),
      array('key' => 'FinalDecision', 'label' => 'Final<br>Decision', 'annual' => false),
      array('key' => 'AnnualApproval', 'label' => 'Annual<br>Approval', 'annual' => true),
    );
    ?>

    <table style="border-collapse: collapse; width: 100%; margin: 0 auto; font-size: 8px;">
      <thead>
        <tr>
          <th style="border: 1px solid #999; padding: 4px; text-align: left;">#</th>
          <th style="border: 1px solid #999; padding: 4px; text-align: left;">ECCT Reference No</th>
          <?php foreach ($stageColumns as $stageColumn) { ?>
            <th style="border: 1px solid #999; padding: 4px; text-align: left;"><?php echo $stageColumn['label']; ?></th>
          <?php } ?>
          <th style="border: 1px solid #999; padding: 4px; text-align: left;">Date Submitted</th>
          <th style="border: 1px solid #999; padding: 4px; text-align: left;">Date Approved</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $count = 0;
        foreach ($applications as $application) {
          $stages = $this->requestAction('applications/stages/' . $application['Application']['id']);
          $rowClass = '';

          if (Hash::check($stages, '{s}[color!=success]')) {
            $colors = Hash::extract($stages, '{s}[color!=success].color');
            if (in_array('danger', $colors)) {
              $rowClass = 'error';
            } elseif (in_array('warning', $colors)) {
              $rowClass = 'warning';
            }
          }
        ?>
          <tr class="<?php echo $rowClass; ?>">
            <td style="border: 1px solid #999; padding: 4px; text-align: left;"><?php $count++; echo $count; ?></td>
            <td style="border: 1px solid #999; padding: 4px; text-align: left;"><?php echo h($application['Application']['protocol_no']); ?></td>
            <?php foreach ($stageColumns as $stageColumn) { ?>
              <td style="border: 1px solid #999; padding: 4px; text-align: left;">
                <?php
                $stageValue = '';
                if (isset($stages[$stageColumn['key']])) {
                  $stageDate = !empty($stages[$stageColumn['key']]['start_date']) ? $stages[$stageColumn['key']]['start_date'] : '';
                  $stageDays = ($stages[$stageColumn['key']]['days'] === '' || $stages[$stageColumn['key']]['days'] === null)
                    ? ''
                    : (string)$stages[$stageColumn['key']]['days'];
                  $stageValue = $stageDate;

                  if ($stageDays !== '') {
                    $dayUnit = ($stageDays === '0' || $stageDays === '1') ? 'day' : 'days';
                    if ($stageColumn['annual']) {
                      $stageValue = trim($stageDate . ' (' . $stageDays . ' ' . $dayUnit . ' to expiry)');
                    } else {
                      $stageValue = trim($stageDate . ' (' . $stageDays . ' ' . $dayUnit . ')');
                    }
                  }
                }
                echo h($stageValue);
                ?>
              </td>
            <?php } ?>
            <td style="border: 1px solid #999; padding: 4px; text-align: left;"><?php echo h($application['Application']['date_submitted']); ?></td>
            <td style="border: 1px solid #999; padding: 4px; text-align: left;"><?php echo h($application['Application']['approval_date']); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

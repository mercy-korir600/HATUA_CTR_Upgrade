<?php
/**
 * Renders the CAPA "case(s)" tied to a single reviewer assignment (one
 * Review row).
 *
 * A case is a small group of `capas` rows sharing the same review_id: one
 * `type` = 'Initial' row (auto-opened by ReviewDeadlineAlertShell) plus
 * any number of `type` = 'FollowUp' rows a manager has appended since -
 * see app/Model/Capa.php for the reasoning. This element expects the
 * whole group for this review, oldest first, and splits it back into
 * cases itself (in practice there's normally just one case per review,
 * since source_stage is currently always 'Review', but this doesn't
 * assume that).
 *
 * Expects:
 *   $capas - array of Capa rows (Reviewer, CreatedBy contained) for this
 *            Review id, ordered oldest -> newest. Already filtered to
 *            this Review's id by the caller.
 *
 * Used from app/View/Applications/manager_view.ctp, in both the "Assigned
 * ECCT Reviewers" and "Assigned Internal Reviewers" loops - CAPAs are
 * raised the same way for both (see ReviewDeadlineAlertShell, which does
 * not distinguish Review.category).
 */
if (!empty($capas)):
    // Split the flat, oldest-first list back into cases, keyed by
    // source_stage (currently always 'Review', but future-proofed).
    $cases = array();
    foreach ($capas as $row) {
        $cases[$row['Capa']['source_stage']][] = $row;
    }

    foreach ($cases as $case):
        $initial = $case[0];
        $latest = end($case);
        $status = !empty($latest['Capa']['status']) ? $latest['Capa']['status'] : 'Open';
        $statusClass = 'text-error';
        if ($status === 'Closed') {
            $statusClass = 'text-success';
        } elseif ($status === 'In Progress') {
            $statusClass = 'text-warning';
        }
?>
    <div class="well well-small" style="margin: 8px 0; background-color: #fff8f0;">
      <p class="<?php echo $statusClass; ?>" style="margin-bottom: 4px;">
        <i class="icon-warning-sign"></i>
        <strong>CAPA <?php echo h($initial['Capa']['reference_no']); ?></strong>
        <small class="muted">(<?php echo h($status); ?>)</small>
      </p>
      <p><small class="muted">
        Deadline was <?php echo !empty($initial['Capa']['deadline_date']) ? date('d-m-Y', strtotime($initial['Capa']['deadline_date'])) : 'N/A'; ?>
        &mdash; <?php echo (int) $initial['Capa']['days_overdue']; ?> day(s) overdue when opened on
        <?php echo date('d-m-Y', strtotime($initial['Capa']['created'])); ?>.
      </small></p>
      <p><?php echo nl2br(h($initial['Capa']['description'])); ?></p>

      <?php if (count($case) > 1): ?>
        <div style="margin-left: 12px; border-left: 2px solid #eee; padding-left: 10px;">
          <p style="margin-bottom: 4px;"><small><strong>Follow-ups:</strong></small></p>
          <?php foreach (array_slice($case, 1) as $followup): ?>
            <p style="margin-bottom: 4px;">
              <small>
                <strong><?php echo h(!empty($followup['CreatedBy']['name']) ? $followup['CreatedBy']['name'] : 'N/A'); ?></strong>
                on <?php echo date('d-m-Y H:i', strtotime($followup['Capa']['created'])); ?>:
                <?php echo nl2br(h($followup['Capa']['description'])); ?>
                <?php if (!empty($followup['Capa']['status']) && $followup['Capa']['status'] !== $initial['Capa']['status']): ?>
                  <em>(status set to <?php echo h($followup['Capa']['status']); ?>)</em>
                <?php endif; ?>
              </small>
            </p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($status !== 'Closed'): ?>
        <?php
          // 'type' => 'post' forced explicitly: FormHelper::create()
          // would otherwise decide GET-vs-POST-vs-PUT by checking whether
          // $this->request->data['Capa']['id'] looks like an "editing"
          // context, which could emit a hidden _method=PUT override and
          // make the controller's request->is('post') check fail.
          echo $this->Form->create('Capa', array(
              'type' => 'post',
              'url' => array('controller' => 'applications', 'action' => 'manager_add_capa_followup'),
              'style' => 'margin-top: 8px;',
          ));
          echo $this->Form->hidden('id', array('value' => $latest['Capa']['id']));
          echo $this->Form->input('note', array(
              'type' => 'textarea', 'rows' => 2, 'label' => 'Add follow-up',
              'placeholder' => 'Progress notes, evidence received, escalation, etc.',
          ));
          echo $this->Form->input('status', array(
              'type' => 'select',
              'label' => 'Status',
              'options' => array('Open' => 'Keep Open', 'In Progress' => 'Mark In Progress', 'Closed' => 'Close CAPA'),
              'empty' => false,
              'default' => $status,
          ));
          echo $this->Form->end(array(
              'label' => 'Save Follow-up',
              'value' => 'Save Follow-up',
              'class' => 'btn btn-small btn-warning',
          ));
        ?>
      <?php endif; ?>
    </div>
<?php
    endforeach;
endif;

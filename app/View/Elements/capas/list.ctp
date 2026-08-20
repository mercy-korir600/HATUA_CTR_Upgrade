<?php
/**
 * Renders the CAPA "case(s)" tied to a single reviewer assignment (one
 * Review row) as a single-line title that opens a popup with the rest -
 * deadline/overdue detail, description, the follow-up thread, and the
 * add-follow-up form. Keeps the "Assigned Reviewers" column compact
 * instead of pushing a multi-paragraph card into it per CAPA.
 *
 * Modal markup/attrs (data-toggle="modal", .modal-dialog/.modal-content/
 * .modal-header/.modal-body/.modal-footer) mirror the existing pattern in
 * app/View/Elements/application/review.ctp's "View Summary" popup, for
 * consistency with the rest of this app.
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
        $btnClass = 'btn-danger';
        if ($status === 'Closed') {
            $btnClass = 'btn-success';
        } elseif ($status === 'In Progress') {
            $btnClass = 'btn-warning';
        }
        $followupCount = count($case) - 1;
        $modalId = 'capaModal_' . $initial['Capa']['id'];
?>
    <p style="margin: 4px 0;">
      <button type="button" class="btn btn-mini <?php echo $btnClass; ?>" data-toggle="modal" data-target="#<?php echo $modalId; ?>">
        <i class="icon-warning-sign icon-white"></i>
        CAPA <?php echo h($initial['Capa']['reference_no']); ?> &middot; <?php echo h($status); ?><?php if ($followupCount > 0): ?> &middot; <?php echo $followupCount; ?> follow-up<?php echo $followupCount === 1 ? '' : 's'; ?><?php endif; ?>
      </button>
    </p>

    <div class="modal fade" id="<?php echo $modalId; ?>">
      <div class="modal-dialog">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title">CAPA <?php echo h($initial['Capa']['reference_no']); ?> <small class="muted">(<?php echo h($status); ?>)</small></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
            <p><small class="muted">
              Deadline was <?php echo !empty($initial['Capa']['deadline_date']) ? date('d-m-Y', strtotime($initial['Capa']['deadline_date'])) : 'N/A'; ?>
              &mdash; <?php echo (int) $initial['Capa']['days_overdue']; ?> day(s) overdue when opened on
              <?php echo date('d-m-Y', strtotime($initial['Capa']['created'])); ?>.
            </small></p>
            <p><?php echo nl2br(h($initial['Capa']['description'])); ?></p>

            <?php if ($followupCount > 0): ?>
              <hr>
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
            <?php endif; ?>

            <?php if ($status !== 'Closed'): ?>
              <hr>
              <?php
                // 'type' => 'post' forced explicitly: FormHelper::create()
                // would otherwise decide GET-vs-POST-vs-PUT by checking
                // whether $this->request->data['Capa']['id'] looks like an
                // "editing" context, which could emit a hidden
                // _method=PUT override and make the controller's
                // request->is('post') check fail.
                echo $this->Form->create('Capa', array(
                    'type' => 'post',
                    'url' => array('controller' => 'applications', 'action' => 'manager_add_capa_followup'),
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

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
<?php
    endforeach;
endif;

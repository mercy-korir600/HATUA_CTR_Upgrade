<?php
/**
 * Renders the CAPA "case(s)" tied to a single reviewer assignment (one
 * Review row) as a trigger button (capas/trigger.ctp, verbose mode) plus
 * its detail modal (capas/modal.ctp), kept together here since each case
 * only ever appears once on the page at this call site. The dedicated
 * CAPA section (app/View/Capas/manager_index.ctp) renders trigger and
 * modal separately instead - it lists every row of a case on its own
 * line, so a shared case needs many trigger buttons but only ONE modal;
 * rendering both together per row there would create duplicate modal ids.
 *
 * A case is a small group of `capas` rows sharing the same review_id: one
 * `type` = 'Initial' row (auto-opened by ReviewDeadlineAlertShell) plus
 * any number of `type` = 'FollowUp' rows appended since, each pointing at
 * its immediate parent via `capa_id` so a follow-up can itself gain
 * follow-ups - see app/Model/Capa.php for the full model.
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
      <?php
      echo $this->element('capas/trigger', array(
          'modalId' => $modalId,
          'btnClass' => $btnClass,
          'mode' => 'verbose',
          'referenceNo' => $initial['Capa']['reference_no'],
          'status' => $status,
          'followupCount' => $followupCount,
      ));
      ?>
    </p>
    <?php
    echo $this->element('capas/modal', array(
        'modalId' => $modalId,
        'initial' => $initial,
        'case' => $case,
        'status' => $status,
    ));
    endforeach;
endif;

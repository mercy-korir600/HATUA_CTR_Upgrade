<?php
/**
 * Renders the button that opens a CAPA case's detail modal (see
 * capas/modal.ctp, which must be rendered elsewhere on the same page for
 * this button's data-target to resolve to anything). Split out of the old
 * combined capas/list.ctp so a case's modal can be rendered exactly once
 * while every row belonging to it (the Initial row AND each of its
 * FollowUps) still gets its own trigger button pointing at that same
 * modal - see app/View/Capas/manager_index.ctp, which now lists every row
 * of a case individually instead of just the Initial row.
 *
 * Two display modes:
 *   'verbose' (default) - descriptive label with reference no, status,
 *              and follow-up count. Used from manager_view.ctp via
 *              capas/list.ctp, where each case only ever appears once on
 *              the page.
 *   'compact' - a plain "View" button. Used from the dedicated CAPA
 *              section (manager_index.ctp), which already shows the
 *              reference no, status and type as their own table columns -
 *              repeating that in the button label on every row would just
 *              be noise, so the Action column stays a single clean
 *              "View" button.
 *
 * Expects:
 *   $modalId       - string, must match the target modal's id (see
 *                     capas/modal.ctp).
 *   $btnClass      - Bootstrap button class for the case's current status
 *                     (btn-danger/btn-warning/btn-success).
 *   $mode          - 'verbose' (default) or 'compact'.
 *   $referenceNo   - the case's reference_no (verbose mode only).
 *   $status        - the case's current status (verbose mode only).
 *   $followupCount - number of follow-ups in the case (verbose mode only).
 */
$mode = !empty($mode) ? $mode : 'verbose';
?>
<button type="button" class="btn btn-mini <?php echo $btnClass; ?>" data-toggle="modal" data-target="#<?php echo $modalId; ?>">
  <?php if ($mode === 'compact'): ?>
    <i class="icon-eye-open"></i> View
  <?php else: ?>
    <i class="icon-warning-sign icon-white"></i>
    CAPA <?php echo h($referenceNo); ?> &middot; <?php echo h($status); ?><?php if (!empty($followupCount)): ?> &middot; <?php echo $followupCount; ?> follow-up<?php echo $followupCount === 1 ? '' : 's'; ?><?php endif; ?>
  <?php endif; ?>
</button>

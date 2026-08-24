<?php
/**
 * Renders a link to a CAPA case's dedicated detail page (see
 * app/Controller/CapasController.php::view()/manager_view() and
 * app/View/Capas/manager_view.ctp - a full page rather than a popup, so
 * there's room for the whole record and its follow-up thread at once).
 * Split out of the old combined capas/list.ctp so every row belonging to
 * a case (the Initial row AND each of its FollowUps) can still get its
 * own link pointing at that one shared case - see
 * app/View/Capas/manager_index.ctp, which lists every row of a case
 * individually instead of just the Initial row.
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
 *   $capaId        - id of the case's Initial Capa row - the page this
 *                     links to is /manager/capas/view/{$capaId}.
 *   $btnClass      - Bootstrap button class for the case's current status
 *                     (btn-danger/btn-warning/btn-success).
 *   $mode          - 'verbose' (default) or 'compact'.
 *   $referenceNo   - the case's reference_no (verbose mode only).
 *   $status        - the case's current status (verbose mode only).
 *   $followupCount - number of follow-ups in the case (verbose mode only).
 */
$mode = !empty($mode) ? $mode : 'verbose';
if ($mode === 'compact') {
    $label = '<i class="icon-eye-open"></i> View';
} else {
    $label = '<i class="icon-warning-sign icon-white"></i> CAPA ' . h($referenceNo) . ' &middot; ' . h($status);
    if (!empty($followupCount)) {
        $label .= ' &middot; ' . $followupCount . ' follow-up' . ($followupCount === 1 ? '' : 's');
    }
}
echo $this->Html->link(
    $label,
    array('controller' => 'capas', 'action' => 'view', $capaId, 'manager' => true),
    array('class' => 'btn btn-mini ' . $btnClass, 'escape' => false)
);

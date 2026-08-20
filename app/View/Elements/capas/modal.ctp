<?php
/**
 * Renders one CAPA case's full detail modal - deadline/overdue info, the
 * Initial row's description, the follow-up thread, and (if the case isn't
 * Closed) the add-follow-up control. Split out of the old combined
 * capas/list.ctp so it can be rendered exactly ONCE per case even when
 * every row in the case (the Initial row and every FollowUp) gets its own
 * trigger button pointing at this same modal id - see capas/trigger.ctp
 * and app/View/Capas/manager_index.ctp.
 *
 * The follow-up thread is rendered recursively via Capa::buildThread(),
 * which arranges the flat $case list into a parent/child tree using each
 * row's `capa_id` - so a follow-up that is itself a reply to another
 * follow-up (rather than to the Initial row) renders nested/indented
 * under its real parent, not just appended to a flat list. See
 * app/Model/Capa.php.
 *
 * Modal markup/attrs (data-toggle="modal", .modal-dialog/.modal-content/
 * .modal-header/.modal-body/.modal-footer) mirror the existing pattern in
 * app/View/Elements/application/review.ctp's "View Summary" popup.
 *
 * The add-follow-up control is deliberately NOT an HTML <form> - see the
 * note in the AJAX <script> block at the bottom of this file for why -
 * nesting a <form> inside a page's existing <form> is invalid HTML and
 * previously broke the whole page's menu until reload.
 *
 * Expects:
 *   $modalId - string, matches the trigger button(s)' data-target.
 *   $initial - the case's Initial Capa row (Reviewer, CreatedBy contained).
 *   $case    - flat array of every row in the case (Initial + all
 *              FollowUps, any depth), oldest first.
 *   $status  - the case's current status (already resolved by the caller,
 *              i.e. the most recently-saved row's status).
 */
if (empty($initial) || empty($case)) {
    return;
}
$followupCount = count($case) - 1;
$latest = end($case);

if (!function_exists('_renderCapaThreadNode')) {
    function _renderCapaThreadNode($node, $depth = 0)
    {
        ?>
        <div style="margin-left: <?php echo (int) $depth * 18; ?>px; margin-bottom: 4px;">
          <p style="margin-bottom: 2px;">
            <small>
              <strong><?php echo h(!empty($node['CreatedBy']['name']) ? $node['CreatedBy']['name'] : 'N/A'); ?></strong>
              on <?php echo date('d-m-Y H:i', strtotime($node['Capa']['created'])); ?>:
              <?php echo nl2br(h($node['Capa']['description'])); ?>
              <?php if (!empty($node['Capa']['status'])): ?>
                <em>(status: <?php echo h($node['Capa']['status']); ?>)</em>
              <?php endif; ?>
            </small>
          </p>
          <?php foreach ($node['_children'] as $child): ?>
            <?php _renderCapaThreadNode($child, $depth + 1); ?>
          <?php endforeach; ?>
        </div>
        <?php
    }
}
?>
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

        <?php if ($followupCount > 0):
            $thread = ClassRegistry::init('Capa')->buildThread($case);
            $root = !empty($thread[0]) ? $thread[0] : null;
        ?>
          <?php if (!empty($root['_children'])): ?>
            <hr>
            <p style="margin-bottom: 4px;"><small><strong>Follow-ups:</strong></small></p>
            <?php foreach ($root['_children'] as $child): ?>
              <?php _renderCapaThreadNode($child); ?>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($status !== 'Closed'): ?>
          <hr>
          <div class="control-group">
            <label>Add follow-up</label>
            <textarea class="capa-followup-note span12" rows="2" placeholder="Progress notes, evidence received, escalation, etc."></textarea>
          </div>
          <div class="control-group">
            <label>Status</label>
            <select class="capa-followup-status">
              <option value="Open" <?php echo $status === 'Open' ? 'selected' : ''; ?>>Keep Open</option>
              <option value="In Progress" <?php echo $status === 'In Progress' ? 'selected' : ''; ?>>Mark In Progress</option>
              <option value="Closed" <?php echo $status === 'Closed' ? 'selected' : ''; ?>>Close CAPA</option>
            </select>
          </div>
          <button type="button" class="btn btn-small btn-warning capa-followup-submit" data-capa-id="<?php echo (int) $latest['Capa']['id']; ?>">
            Save Follow-up
          </button>
        <?php endif; ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<script>
  // Bound once regardless of how many CAPA modals are rendered on the
  // page (one per case) - submits the add-follow-up control via plain
  // AJAX rather than a <form>, since this element can end up nested
  // inside a page's existing <form> (e.g. manager_view.ctp's "Assign
  // Reviewer" panel) - a nested <form> is invalid HTML that browsers
  // silently drop/reparent, which previously broke the whole page's menu
  // until a full reload. Mirrors the existing '.ResendReview' AJAX
  // pattern already used elsewhere in this app.
  if (typeof window.__capaFollowupBound === 'undefined') {
    window.__capaFollowupBound = true;
    $(document).on('click', '.capa-followup-submit', function(e) {
      e.preventDefault();
      var $btn = $(this);
      var $body = $btn.closest('.modal-body');
      var capaId = $btn.data('capa-id');
      var note = $body.find('.capa-followup-note').val();
      var status = $body.find('.capa-followup-status').val();

      $.ajax({
        url: '<?php echo $this->Html->url(array('controller' => 'applications', 'action' => 'manager_add_capa_followup')); ?>',
        type: 'post',
        data: {
          'data[Capa][id]': capaId,
          'data[Capa][note]': note,
          'data[Capa][status]': status
        },
        beforeSend: function() {
          $btn.prop('disabled', true).text('Saving...');
        },
        success: function() {
          window.location.reload();
        },
        error: function() {
          alert('Could not save the follow-up. Please try again.');
          $btn.prop('disabled', false).text('Save Follow-up');
        }
      });
    });
  }
</script>

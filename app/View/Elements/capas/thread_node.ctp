<?php
/**
 * Renders one row of a CAPA case's follow-up thread table (see
 * app/View/Capas/manager_view.ctp, which wraps this in a <table>/<tbody>),
 * and recurses into its children as further rows immediately below - a
 * depth-first walk of the thread renders as a flat, ordered set of table
 * rows, with nested replies indented in the Update column via an
 * "&#8627;" marker rather than actual nested <tr>s (HTML tables don't
 * nest rows). Split out of the old capas/modal.ctp, which needed a
 * function_exists()-guarded helper function because several modals (and
 * so several threads) could be rendered on one page at once - now that
 * every case has its own dedicated page there's only ever one thread per
 * page, so a plain recursive element is enough.
 *
 * Expects:
 *   $node  - one node from Capa::buildThread() (see app/Model/Capa.php),
 *            with a '_children' key holding its own replies in the same
 *            shape.
 *   $depth - nesting depth, for indentation. Defaults to 0.
 */
$depth = !empty($depth) ? $depth : 0;

$rowStatusClass = '';
if (!empty($node['Capa']['status'])) {
    if ($node['Capa']['status'] === 'Closed') {
        $rowStatusClass = 'text-success';
    } elseif ($node['Capa']['status'] === 'In Progress') {
        $rowStatusClass = 'text-warning';
    } else {
        $rowStatusClass = 'text-error';
    }
}
?>
<tr>
    <td style="white-space: nowrap;"><?php echo date('d-m-Y H:i', strtotime($node['Capa']['created'])); ?></td>
    <td><?php echo h(!empty($node['CreatedBy']['name']) ? $node['CreatedBy']['name'] : 'N/A'); ?></td>
    <td style="padding-left: <?php echo (int) ($depth * 18) + 8; ?>px;">
        <?php if ($depth > 0): ?><span class="muted">&#8627;&nbsp;</span><?php endif; ?>
        <?php echo nl2br(h($node['Capa']['description'])); ?>
    </td>
    <td class="<?php echo $rowStatusClass; ?>">
        <?php if (!empty($node['Capa']['status'])): ?>
            <strong><?php echo h($node['Capa']['status']); ?></strong>
        <?php endif; ?>
    </td>
</tr>
<?php foreach ($node['_children'] as $child): ?>
    <?php echo $this->element('capas/thread_node', array('node' => $child, 'depth' => $depth + 1)); ?>
<?php endforeach; ?>

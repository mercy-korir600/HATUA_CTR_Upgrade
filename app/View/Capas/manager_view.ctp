<?php
/**
 * A single CAPA case's own dedicated page - the full record (description
 * of non-conformity, root cause, corrective/preventive action, target
 * date, responsible person - the CAPA.doc table format, see Capa.php)
 * plus its whole follow-up thread, and, while still open, the
 * add-follow-up form. Replaces the old capas/modal.ctp popup: a case
 * often has a long description, a growing thread, and several fields to
 * review together, and a modal just didn't give that enough room - a
 * full page does. Linked to from app/View/Capas/manager_index.ctp and
 * app/View/Elements/capas/list.ctp, both via app/View/Elements/capas/
 * trigger.ctp.
 *
 * Set by CapasController::view()/manager_view():
 *   $initial - the case's Initial Capa row (Application, Reviewer,
 *              CreatedBy contained). Root cause / corrective action /
 *              target date / status here always reflect the case's
 *              latest follow-up (kept in sync - see
 *              ApplicationsController::manager_add_capa_followup()).
 *   $case    - flat array of every row in the case (Initial + all
 *              FollowUps, any depth, CreatedBy contained), oldest first.
 *   $status  - the case's current status (the most recently-saved row's
 *              status).
 */
$this->assign('CAPA', 'active');
$followupCount = count($case) - 1;
$latest = end($case);

$statusClass = 'text-error';
if ($status === 'Closed') {
    $statusClass = 'text-success';
} elseif ($status === 'In Progress') {
    $statusClass = 'text-warning';
}
?>
<div class="row-fluid">
    <div class="span12">

        <div class="marketing">
            <div class="row-fluid">
                <div class="span8">
                    <h3>
                        CAPA <?php echo h($initial['Capa']['reference_no']); ?>
                        <small class="<?php echo $statusClass; ?>">
                            <strong><?php echo h($status); ?></strong><?php if ($status === 'Closed' && !empty($initial['Capa']['closed_date'])): ?> on <?php echo date('d-m-Y H:i', strtotime($initial['Capa']['closed_date'])); ?><?php endif; ?>
                        </small>
                    </h3>
                </div>
                <div class="span4" style="text-align: right; padding-top: 12px;">
                    <?php
                    echo $this->Html->link(
                        '<i class="icon-arrow-left"></i> Back to CAPA list',
                        array('controller' => 'capas', 'action' => 'index', 'manager' => true),
                        array('class' => 'btn', 'escape' => false)
                    );
                    if (!empty($initial['Application']['id'])) {
                        echo ' ' . $this->Html->link(
                            '<i class="icon-file"></i> View Application',
                            array('controller' => 'applications', 'action' => 'view', $initial['Application']['id'], 'manager' => true),
                            array('class' => 'btn', 'escape' => false)
                        );
                    }
                    ?>
                </div>
            </div>
            <hr class="soften" style="margin: 7px 0px;">
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row-fluid">
            <div class="span8">
                <div class="well" style="background-color: #fff;">
                    <p><small class="muted">
                        Type: <strong><?php echo $initial['Capa']['type'] === 'FollowUp' ? 'Follow-up' : 'Initial'; ?></strong>
                        &middot; Protocol No.:
                        <?php
                        if (!empty($initial['Application']['id'])) {
                            echo $this->Html->link(
                                h(!empty($initial['Application']['protocol_no']) ? $initial['Application']['protocol_no'] : 'N/A'),
                                array('controller' => 'applications', 'action' => 'view', $initial['Application']['id'], 'manager' => true)
                            );
                        } else {
                            echo 'N/A';
                        }
                        ?>
                        &middot; Deadline was <?php echo !empty($initial['Capa']['deadline_date']) ? date('d-m-Y', strtotime($initial['Capa']['deadline_date'])) : 'N/A'; ?>
                        &mdash; <?php echo (int) $initial['Capa']['days_overdue']; ?> day(s) overdue when opened on
                        <?php echo date('d-m-Y', strtotime($initial['Capa']['created'])); ?>.
                    </small></p>

                    <p><strong>Description of non conformity:</strong><br><?php echo nl2br(h($initial['Capa']['description'])); ?></p>

                    <p style="margin-bottom: 4px;">
                        <strong>Root cause:</strong><br>
                        <?php echo !empty($initial['Capa']['root_cause']) ? nl2br(h($initial['Capa']['root_cause'])) : '<span class="muted">Not yet determined.</span>'; ?>
                    </p>
                    <p style="margin-bottom: 4px;">
                        <strong>Corrective/Preventive action:</strong><br>
                        <?php echo !empty($initial['Capa']['corrective_action']) ? nl2br(h($initial['Capa']['corrective_action'])) : '<span class="muted">Not yet determined.</span>'; ?>
                    </p>
                    <p style="margin-bottom: 0;">
                        <strong>Target date:</strong>
                        <?php echo !empty($initial['Capa']['target_date']) ? date('d-m-Y', strtotime($initial['Capa']['target_date'])) : '<span class="muted">Not set.</span>'; ?>
                        &nbsp;&middot;&nbsp;
                        <strong>Responsible person:</strong>
                        <?php echo h(!empty($initial['Reviewer']['name']) ? $initial['Reviewer']['name'] : 'N/A'); ?>
                    </p>
                </div>

                <?php if ($followupCount > 0):
                    $thread = ClassRegistry::init('Capa')->buildThread($case);
                    $root = !empty($thread[0]) ? $thread[0] : null;
                ?>
                    <?php if (!empty($root['_children'])): ?>
                        <h4><small><strong>Follow-ups</strong></small></h4>
                        <table class="table table-bordered table-striped table-condensed" style="background-color: #fff;">
                            <thead>
                                <tr>
                                    <th style="width: 130px;">Date</th>
                                    <th style="width: 150px;">By</th>
                                    <th>Update</th>
                                    <th style="width: 110px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($root['_children'] as $child): ?>
                                    <?php echo $this->element('capas/thread_node', array('node' => $child)); ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="span4">
                <?php if ($status !== 'Closed'): ?>
                    <div class="well">
                        <h4 style="margin-top: 0;">Add follow-up</h4>
                        <?php
                        echo $this->Form->create('Capa', array(
                            'url' => array('controller' => 'applications', 'action' => 'manager_add_capa_followup', 'manager' => true),
                        ));
                        echo $this->Form->hidden('id', array('value' => $latest['Capa']['id']));
                        ?>
                        <div class="control-group">
                            <label>Progress notes, evidence received, escalation, etc.</label>
                            <?php echo $this->Form->input('note', array('type' => 'textarea', 'rows' => 3, 'div' => false, 'class' => 'span12', 'label' => false)); ?>
                        </div>
                        <div class="control-group">
                            <label>Root cause</label>
                            <?php
                            echo $this->Form->input('root_cause', array(
                                'type' => 'textarea', 'rows' => 2, 'div' => false, 'class' => 'span12', 'label' => false,
                                'value' => !empty($initial['Capa']['root_cause']) ? $initial['Capa']['root_cause'] : '',
                                'placeholder' => 'Leave blank to keep the current value.',
                            ));
                            ?>
                        </div>
                        <div class="control-group">
                            <label>Corrective/Preventive action</label>
                            <?php
                            echo $this->Form->input('corrective_action', array(
                                'type' => 'textarea', 'rows' => 2, 'div' => false, 'class' => 'span12', 'label' => false,
                                'value' => !empty($initial['Capa']['corrective_action']) ? $initial['Capa']['corrective_action'] : '',
                                'placeholder' => 'Leave blank to keep the current value.',
                            ));
                            ?>
                        </div>
                        <div class="control-group">
                            <label>Target date</label>
                            <?php
                            // Same jQuery UI datepicker convention used throughout the
                            // rest of the app (class 'datepickers', dd-mm-yy format,
                            // calendar-icon trigger) - see e.g.
                            // app/View/AnnualLetters/admin_letter_upload.ctp.
                            echo $this->Form->input('target_date', array(
                                'div' => false, 'type' => 'text', 'class' => 'input-medium datepickers', 'label' => false,
                                'value' => !empty($initial['Capa']['target_date']) ? date('d-m-Y', strtotime($initial['Capa']['target_date'])) : '',
                                'placeholder' => 'dd-mm-yyyy',
                            ));
                            ?>
                        </div>
                        <div class="control-group">
                            <label>Status</label>
                            <?php
                            echo $this->Form->input('status', array(
                                'div' => false, 'type' => 'select', 'label' => false,
                                'options' => array('Open' => 'Keep Open', 'In Progress' => 'Mark In Progress', 'Closed' => 'Close CAPA'),
                                'value' => $status,
                            ));
                            ?>
                        </div>
                        <?php
                        echo $this->Form->button('Save Follow-up', array('class' => 'btn btn-warning', 'div' => false));
                        echo $this->Form->end();
                        ?>
                        <script type="text/javascript">
                            (function ($) {
                                $(".datepickers").datepicker({
                                    minDate: "-5Y", maxDate: "+999D", dateFormat: 'dd-mm-yy', showButtonPanel: true,
                                    changeMonth: true, changeYear: true,
                                    buttonImageOnly: true, showAnim: 'show', showOn: 'both', buttonImage: '/img/calendar.gif'
                                });
                            })(jQuery);
                        </script>
                    </div>
                <?php else: ?>
                    <div class="alert alert-success">This CAPA is closed.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

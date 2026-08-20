<?php
$this->assign('CAPA', 'active');
?>
<div class="row-fluid">
    <div class="span12">

        <div class="marketing">
            <div class="row-fluid">
                <div class="span12">
                    <h3>CAPAs: <small>Corrective and Preventive Actions - <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> manage</small></h3>
                    <hr class="soften" style="margin: 7px 0px;">
                </div>
            </div>
        </div>

        <?php echo $this->Session->flash(); ?>

        <?php
        echo $this->Form->create('Capa', array(
            'url' => array_merge(array('action' => 'index'), $this->params['pass']),
            'class' => 'ctr-groups',
            'style' => array('padding:9px;', 'background-color: #F5F5F5'),
        ));
        ?>
        <table class="table table-condensed" style="margin-bottom: 2px;">
            <tbody>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input('reference_no', array(
                            'div' => false, 'placeholder' => 'CAPA/ECCT.../2026/..',
                            'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'Reference No.'),
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input('protocol_no', array(
                            'div' => false, 'placeholder' => 'ECCT/20..',
                            'class' => 'span12', 'label' => array('class' => 'required', 'text' => 'ECCT Reference No.'),
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input('reviewer_user_id', array(
                            'type' => 'select', 'div' => false, 'empty' => 'All Reviewers',
                            'class' => 'span12', 'options' => $reviewers,
                            'label' => array('class' => 'required', 'text' => 'Reviewer'),
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input('status', array(
                            'type' => 'select', 'div' => false, 'empty' => 'Any Status',
                            'class' => 'span12',
                            'options' => array('Open' => 'Open', 'In Progress' => 'In Progress', 'Closed' => 'Closed'),
                            'label' => array('class' => 'required', 'text' => 'Status'),
                        ));
                        ?>
                    </td>
                    <td colspan="2">
                        <?php
                        echo $this->Form->input('start_date', array(
                            'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                            'after' => '-to-', 'label' => array('class' => 'required', 'text' => 'Opened Between'),
                            'placeHolder' => 'Start Date',
                        ));
                        echo $this->Form->input('end_date', array(
                            'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                            'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" ><em class="accordion-toggle">clear!</em></a>',
                            'label' => false, 'placeHolder' => 'End Date',
                        ));
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        echo $this->Form->input('pages', array(
                            'div' => false, 'type' => 'select', 'label' => array('text' => 'Per Page'),
                            'empty' => true, 'options' => $page_options,
                        ));
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <?php
                        echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                            'class' => 'btn btn-primary', 'div' => false,
                            'formnovalidate' => 'formnovalidate', 'style' => array('margin-bottom: 5px'),
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array_merge(array('action' => 'index', 'ext' => 'csv'), $this->request->named), array('class' => 'btn btn-success', 'escape' => false));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                showing <span class="badge">{:current}</span> CAPAs out of
                <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                ending on <span class="badge">{:end}</span>')
            ));
            ?>
        </p>
        <?php echo $this->Form->end(); ?>

        <div class="pagination">
            <ul>
                <?php
                echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
                echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active'));
                echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
                ?>
            </ul>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?php echo $this->Paginator->sort('reference_no'); ?></th>
                    <th>Protocol No.</th>
                    <th>Reviewer</th>
                    <th><?php echo $this->Paginator->sort('status'); ?></th>
                    <th><?php echo $this->Paginator->sort('created', 'Opened'); ?></th>
                    <th><?php echo $this->Paginator->sort('deadline_date', 'Deadline'); ?></th>
                    <th><?php echo $this->Paginator->sort('days_overdue', 'Days Overdue (at open)'); ?></th>
                    <th class="actions"><?php echo __('View / Follow up'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($capas as $capa): ?>
                    <?php
                    $status = !empty($capa['Capa']['status']) ? $capa['Capa']['status'] : 'Open';
                    $statusClass = 'text-error';
                    if ($status === 'Closed') {
                        $statusClass = 'text-success';
                    } elseif ($status === 'In Progress') {
                        $statusClass = 'text-warning';
                    }
                    ?>
                    <tr>
                        <td><?php echo h($capa['Capa']['reference_no']); ?></td>
                        <td>
                            <?php
                            if (!empty($capa['Application']['id'])) {
                                echo $this->Html->link(
                                    h(!empty($capa['Application']['protocol_no']) ? $capa['Application']['protocol_no'] : 'N/A'),
                                    array('controller' => 'applications', 'action' => 'view', $capa['Application']['id'], 'manager' => true)
                                );
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td><?php echo h(!empty($capa['Reviewer']['name']) ? $capa['Reviewer']['name'] : 'N/A'); ?></td>
                        <td class="<?php echo $statusClass; ?>"><strong><?php echo h($status); ?></strong></td>
                        <td><?php echo date('d-m-Y', strtotime($capa['Capa']['created'])); ?></td>
                        <td><?php echo !empty($capa['Capa']['deadline_date']) ? date('d-m-Y', strtotime($capa['Capa']['deadline_date'])) : 'N/A'; ?></td>
                        <td><?php echo (int) $capa['Capa']['days_overdue']; ?></td>
                        <td>
                            <?php
                            echo $this->element('capas/list', array(
                                'capas' => !empty($capasByReview[$capa['Capa']['review_id']]) ? $capasByReview[$capa['Capa']['review_id']] : array($capa),
                            ));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

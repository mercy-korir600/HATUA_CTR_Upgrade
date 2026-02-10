<div class="row-fluid">
    <?php
    $this->assign('Users', 'active');
    ?>
    <h2><?php echo __('Audit Trail'); ?></h2>
    <?php
    echo $this->Form->create('AuditTrail', array(
        'url' => array_merge(array('action' => 'index'), $this->params['pass']),
        'class' => 'ctr-groups', 'style' => array('padding:9px;', 'background-color: #F5F5F5'),
    ));
    ?>
    <div class="row-fluid">
        <div class="span4">
            <?php
            echo $this->Form->input('filter', array(
                'div' => false, 'class' => 'span12 unauthorized_index',
                'label' => array('class' => 'required', 'text' => 'ECCT Reference No.'),
                'type' => 'text',
            ));
            ?>
        </div>
        <div class="span4">
            <?php
            echo $this->Form->input(
                'start_date',
                array(
                    'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                    'label' => array('class' => 'required', 'text' => 'Audit Trail Dates'), 'placeHolder' => 'Start Date'
                )
            );
            echo $this->Form->input(
                'end_date',
                array(
                    'div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                    'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                        <em class="accordion-toggle">clear!</em></a>',
                    'label' => false, 'placeHolder' => 'End Date'
                )
            );
            ?>
        </div>
        <div class="span2">
            <?php
            echo $this->Form->input('pages', array(
                'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['AuditTrail']['limit'],
                'empty' => true,
                'options' => $page_options,
                'label' => array('class' => 'required', 'text' => 'Pages'),
            ));
            ?>
        </div>
        <div class="span2">
            <?php
            echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                'style' => array('margin-bottom: 5px')
            ));

            echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false));

            echo "<br>";
            echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv'), array('class' => 'btn btn-success', 'escape' => false));
            ?>
        </div>
    </div>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                    showing <span class="badge">{:current}</span> Logs out of
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
    <table class="table  table-bordered table-striped">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
                <th><?php echo $this->Paginator->sort('message'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($audits as $data) : ?>
                <tr>
                    <td><?php echo h($data['AuditTrail']['id']); ?>&nbsp;</td>
                    <td><?php echo h($data['AuditTrail']['ip']); ?>&nbsp;</td>
                    <td><?php echo (strip_tags($data['AuditTrail']['message'])); ?>&nbsp;</td> 
                    <td><?php echo h($data['AuditTrail']['created']); ?>&nbsp;</td>
                    <td class="actions">
                        <?php
                        echo $this->Form->postLink(__('<label class="label label-warning">Delete</label>'), array('action' => 'delete', $data['AuditTrail']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $data['AuditTrail']['id']));

                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
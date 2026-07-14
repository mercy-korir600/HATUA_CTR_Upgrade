<div class="row-fluid">
    <?php $this->assign('Users', 'active'); ?>
    <h4>Manage Assignments for Auditor: <?php echo h($user['User']['name']); ?></h4>
    <hr>
    
    <div class="row-fluid">
        <div class="span12">
            <dl class="dl-horizontal">
                <dt>Auditor ID</dt> <dd><?php echo h($user['User']['id']); ?>&nbsp;</dd>
                <dt>Username</dt> <dd><?php echo h($user['User']['username']); ?>&nbsp;</dd>
                <dt>Email</dt> <dd><?php echo h($user['User']['email']); ?>&nbsp;</dd>
            </dl>
            
            <?php if (count($user['AuditorProtocol']) > 0) { ?>
            <h5 class="text-success">Currently Assigned Protocols</h5>
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Protocol No.</th>
                        <th>Assigned Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        foreach ($user['AuditorProtocol'] as $assignment) {
                            $i++;
                            echo "<tr>";
                            echo "<td>".$i."</td>";
                            echo "<td>".h($assignment['Application']['protocol_no'])."</td>";
                            echo "<td>".h($assignment['created'])."</td>";
                            echo "<td>".$this->Form->postLink(__('<span class="label label-important">Revoke Access</span>'), 
                                array('action' => 'delete', $assignment['id']), 
                                array('escape' => false), 
                                __('Are you sure you want to revoke access to protocol %s from auditor %s?', $assignment['Application']['protocol_no'], $user['User']['name']))."</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="alert alert-info">No protocols currently assigned to this auditor.</div>
            <?php } ?>
        </div>
    </div>
    
    <hr>
    
    <h5>Assign a New Protocol</h5>
    <?php
        echo $this->Form->create('AuditorProtocol', array(
          'url' => array_merge(array('action' => 'view', $user['User']['id']), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
    ?>
    <div class="row-fluid">
        <div class="span5">
            <?php echo $this->Form->input('protocol_no', array('div' => false, 'class' => 'span12', 'label' => 'Protocol No.', 'type' => 'text')); ?>
        </div>
        <div class="span5">
            <?php echo $this->Form->input('filter', array('div' => false, 'class' => 'span12', 'label' => 'Study Title', 'type' => 'text')); ?>
        </div>
        <div class="span2">
            <br>
            <?php echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array('class' => 'btn btn-inverse', 'div' => false)); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Protocol No.</th>
                <th>Study Title</th>
                <th>Owner/Applicant</th>
                <th class="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <tr>
                <td><?php echo h($app['Application']['id']); ?></td>
                <td><?php echo h($app['Application']['protocol_no']); ?></td>
                <td><?php echo $this->Text->truncate($app['Application']['study_title'], 60, array('html' => true)); ?></td>
                <td><?php echo h($app['User']['name']); ?></td>
                <td class="actions">
                    <?php
                        echo $this->Form->postLink(__('<span class="badge badge-success">Assign Protocol</span>'), 
                            array('action' => 'view', $user['User']['id'], $app['Application']['id']), 
                            array('escape' => false), 
                            __('Assign protocol %s to auditor %s?', $app['Application']['protocol_no'], $user['User']['name']));
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
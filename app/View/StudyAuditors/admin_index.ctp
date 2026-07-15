<div class="row-fluid">
    <?php $this->assign('Users', 'active'); ?>
    <h2><?php echo __('Auditors & Protocol Assignments'); ?></h2>
    
    <?php
        echo $this->Form->create('StudyAuditor', array(
          'url' => array_merge(array('action' => 'index'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
    ?>
    <div class="row-fluid">
          <div class="span6">
          <?php
            echo $this->Form->input('filter', array('div' => false, 'class' => 'span12 unauthorized_index',
              'label' => array('class' => 'required', 'text' => 'Search by Email / Name / Username'),
              'type' => 'text',
              ));
          ?>
          </div>
          <div class="span3">
            <?php
              echo $this->Form->input('pages', array(
                'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['User']['limit'],
                'empty' => true,
                'options' => $page_options,
                'label' => array('class' => 'required', 'text' => 'Pages'),
              ));
            ?>
          </div>
          <div class="span3">
            <br>
            <?php
              echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                  'class' => 'btn btn-inverse', 'div' => false, 'style' => array('margin-bottom: 5px')
              ));
              echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => 'margin-left: 5px;'));
            ?>
          </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Assigned Protocols</th>
                <th>Created</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr class="<?php if($user['User']['deactivated']) echo 'muted';?>">
                <td><?php echo h($user['User']['id']); ?></td>
                <td><?php echo h($user['User']['username']); ?></td>
                <td><?php echo h($user['User']['name']); ?></td>
                <td><?php echo h($user['User']['email']); ?></td>
                <td>
                    <?php 
                        foreach ($user['StudyAuditor'] as $assignment) {
                            echo h($assignment['Application']['protocol_no']) . "<br>"; 
                        }        
                    ?>
                </td>
                <td><?php echo h($user['User']['created']); ?></td>
                <td class="actions">
                    <?php echo $this->Html->link(__('<label class="label label-info">Manage Assignments</label>'), array('action' => 'view', $user['User']['id']), array('escape' => false)); ?>
                    <?php echo $this->Html->link(__('<label class="label label-success">Edit Profile</label>'), array('controller' => 'users', 'action' => 'edit', $user['User']['id']), array('escape' => false)); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
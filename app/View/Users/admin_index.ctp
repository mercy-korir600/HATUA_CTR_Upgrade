<div class="row-fluid">
  <?php
  $this->assign('Users', 'active');
  ?>
  <h2><?php echo __('Users'); ?></h2>
  <?php
  echo $this->Form->create('User', array(
    'url' => array_merge(array('action' => 'index'), $this->params['pass']),
    'class' => 'ctr-groups',
    'style' => array('padding:9px;', 'background-color: #F5F5F5'),
  ));
  ?>
  <div class="row-fluid">
    <div class="span4">
      <?php
      echo $this->Form->input('filter', array(
        'div' => false,
        'class' => 'span12 unauthorized_index',
        'label' => array('class' => 'required', 'text' => 'Email / Name / Username'),
        'type' => 'text',
      ));
      ?>
    </div>
    <div class="span4">
      <?php
      echo $this->Form->input(
        'start_date',
        array(
          'div' => false,
          'type' => 'text',
          'class' => 'input-small unauthorized_index',
          'after' => '-to-',
          'label' => array('class' => 'required', 'text' => 'User Register Dates'),
          'placeHolder' => 'Start Date'
        )
      );
      echo $this->Form->input(
        'end_date',
        array(
          'div' => false,
          'type' => 'text',
          'class' => 'input-small unauthorized_index',
          'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                        <em class="accordion-toggle">clear!</em></a>',
          'label' => false,
          'placeHolder' => 'End Date'
        )
      );
      ?>
    </div>
    <div class="span2">
      <?php
      echo $this->Form->input('pages', array(
        'type' => 'select',
        'div' => false,
        'class' => 'span12',
        'selected' => $this->request->params['paging']['User']['limit'],
        'empty' => true,
        'options' => $page_options,
        'label' => array('class' => 'required', 'text' => 'Pages'),
      ));
      ?>
    </div>
    <div class="span2">
      <?php
      echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
        'class' => 'btn btn-inverse',
        'div' => 'control-group',
        'div' => false,
        'style' => array('margin-bottom: 5px')
      ));

      echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false));

      echo "<br>";
      echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv'), array('class' => 'btn btn-success', 'escape' => false));
      ?>
    </div>
  </div>
    <div class="row-fluid">
          <div class="span4">
          <?php
            
              echo $this->Form->input('group_id', array('label' => array('class' => 'control-label required', 'text' => 'User Role'), 'empty' => true));
				
          ?>
          </div>
    </div>
  <p>
    <?php
    echo $this->Paginator->counter(array(
      'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                    showing <span class="badge">{:current}</span> Users out of
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
        <th><?php echo $this->Paginator->sort('username'); ?></th>
        <th><?php echo $this->Paginator->sort('name'); ?></th>
        <th><?php echo $this->Paginator->sort('phone_no'); ?></th>
        <th><?php echo $this->Paginator->sort('email'); ?></th>
        <th><?php echo $this->Paginator->sort('group_id', 'Role'); ?></th>
        <th><?php echo $this->Paginator->sort('created'); ?></th>
        <th class="actions"><?php echo __('Actions'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($users as $user): ?>
        <tr class="<?php if ($user['User']['deactivated']) echo 'muted'; ?>">
          <td><?php echo h($user['User']['id']); ?>&nbsp;</td>
          <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
          <td><?php echo h($user['User']['name']); ?>&nbsp;</td>
          <td><?php echo h($user['User']['phone_no']); ?>&nbsp;</td>
          <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
          <td>
            <?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
          </td>
          <td><?php echo h($user['User']['created']); ?>&nbsp;</td>
          <td class="actions">
            <?php echo $this->Html->link(__('<label class="label label-info">View</label>'), array('action' => 'view', $user['User']['id']), array('escape' => false)); ?>
            <?php echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?>
            <?php
            if (!$user['User']['deactivated']) {
              echo $this->Form->postLink(__('<label class="label label-inverse">Deactivate</label>'), array('action' => 'delete', $user['User']['id'], 1), array('escape' => false), __('Are you sure you want to deactivate # %s?', $user['User']['id']));
            } else {
              echo $this->Form->postLink(__('<label class="label">Activate</label>'), array('action' => 'delete', $user['User']['id'], 0), array('escape' => false), __('Are you sure you want to Reactivate # %s?', $user['User']['id']));
            }
            if (!$user['User']['is_active'] && !empty($user['User']['activation_key'])) {
              echo $this->Form->postLink(__('<label class="label label-warning">Approve</label>'), array('action' => 'approve', $user['User']['id']), array('escape' => false), __('Are you sure you want to approve # %s?', $user['User']['id']));
            }
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
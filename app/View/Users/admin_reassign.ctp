<div class="row-fluid">
	<?php
	$this->assign('Reports', 'active');
?>
  <h4>Reassign Application to User</h4>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <dl class="dl-horizontal">
        <dt>ECCT Reference No.</dt>
        <dd><?php echo $application['Application']['protocol_no'];?></dd>
        <dt>Short Title</dt>
        <dd><?php echo $application['Application']['short_title'];?></dd>
        <dt>Current User</dt>
        <dd>
          ID: <?php echo $application['User']['id'];?><br>
          Name: <?php echo $application['User']['name'];?><br>
          Username: <?php echo $application['User']['username'];?><br>
          Email: <?php echo $application['User']['email'];?><br>
          
        </dd>
        <?php 
          if(count($application['Reassignment']) > 0) {
            echo '<dt>Previous User(s)</dt><dd>';
            foreach ($application['Reassignment'] as $reassignment) {
                $userdetails = $this->requestAction('/users/userdesc/'.$reassignment['orig_user']);
                echo "ID: ".$userdetails['User']['id']."<br> Name: ".$userdetails['User']['name']."<br> Username: ".$userdetails['User']['username']."<br> Email: ".$userdetails['User']['email']."<br>"."Date: ".$reassignment['created']."<br>";
            }
            echo "</dd>";
          }
        ?>
      </dl>
    </div>
  </div>
  <hr>
	
    <?php
        echo $this->Form->create('User', array(
          'url' => array_merge(array('action' => 'reassign'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
     <div class="row-fluid">
          <div class="span5">
          <?php
            echo $this->Form->input('filter', array('div' => false, 'class' => 'span12 unauthorized_index',
              'label' => array('class' => 'required', 'text' => 'Username / Email / Name'),
              'type' => 'text',
              ));
          ?>
          </div>
          <div class="span2">
            <br>
            <?php
              echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                  'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                  'style' => array('margin-bottom: 5px')
              ));
            ?>
          </div>
          <div class="span5">
            <p><?php
              echo $this->Paginator->counter(array(
              'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                      showing <span class="badge">{:current}</span> Users out of
                      <span class="badge badge-inverse">{:count}</span> total')
              ));
            ?></p>
          </div>
       </div>
	
          
          
          <?php echo $this->Form->end(); ?>
          <div class="pagination">
            <ul>
            <?php
              echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
              echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active'));
              echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false ));
            ?>
            </ul>
          </div>
	<table  class="table  table-bordered table-striped">
	 <thead>
            <tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('username'); ?></th>
		<th><?php echo $this->Paginator->sort('name'); ?></th>
		<th><?php echo $this->Paginator->sort('phone_no'); ?></th>
		<th><?php echo $this->Paginator->sort('email'); ?></th>
		<th><?php echo $this->Paginator->sort('created'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	      </tr>
       </thead>
      <tbody>
	<?php
	foreach ($users as $user): ?>
	<tr class="<?php if($user['User']['deactivated']) echo 'muted';?>">
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['phone_no']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php
        echo $this->Form->postLink(__('<span class="badge badge-success">Reassign</span>'), array('action' => 'reassign', $application['Application']['id'], $user['User']['id']), array('escape' => false), 
              __('Are you sure you want to reassign protocol %s to %s?', $application['Application']['protocol_no'], $user['User']['name']));
      ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

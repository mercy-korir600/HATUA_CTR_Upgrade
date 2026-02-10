<div class="row-fluid">
	<?php
	$this->assign('Reports', 'active');
?>
  <h4>Assign Application to Study Monitor</h4>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <dl class="dl-horizontal">
        <dt>ID</dt>
        <dd>
          <?php echo h($user['User']['id']); ?>
          &nbsp;
        </dd>
        <dt>Username</dt>
        <dd><?php echo $user['User']['username'] ;?> &nbsp; </dd>
        <dt>Name</dt>
        <dd><?php echo $user['User']['name']; ?> &nbsp; </dd>
        <dt>Email</dt>
        <dd><?php echo $user['User']['email']; ?> &nbsp; </dd>
      </dl>
      <?php if (count($user['StudyMonitor']) > 0) { ?>
      <h5 class="text-success">Assigned protocols</h5>
      <table class="table table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>ECCT Reference No.</th>
            <th>Created</th>
            <th><i class="icon-link"></i></th>
          </tr>
        </thead>
        <?php
          $i = 0;
          foreach ($user['StudyMonitor'] as $study_monitor) {
            $i++;
            echo "<tr>";
            echo "<td>".$i."</td><td>".$study_monitor['Application']['protocol_no']."</td><td>".$study_monitor['created']."</td>";
            echo "<td>".$this->Form->postLink(__('<span class="label label-important">Remove</span>'), array('action' => 'delete', $study_monitor['id']), array('escape' => false), 
              __('Are you sure you want to remove protocol %s from user %s profile?', $study_monitor['Application']['protocol_no'], $user['User']['name']))."</td>";
            echo "</tr>";
          }
        ?>
      </table>
      <?php } ?>
    </div>
  </div>
  <hr>
	
    <?php
        echo $this->Form->create('StudyMonitor', array(
          'url' => array_merge(array('action' => 'view'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
     <div class="row-fluid">
          <div class="span5">
          <?php
            echo $this->Form->input('protocol_no', array('div' => false, 'class' => 'span12 unauthorized_index',
              'label' => array('class' => 'required', 'text' => 'ECCT Reference No.'),
              'type' => 'text',
              ));
          ?>
          </div>
          <div class="span5">
          <?php
            echo $this->Form->input('filter', array('div' => false, 'class' => 'span12 unauthorized_index',
              'label' => array('class' => 'required', 'text' => 'Study Title'),
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
       </div>
       <div class="row-fluid">
         <div class="span12">
            <p><?php
              echo $this->Paginator->counter(array(
              'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                      showing <span class="badge">{:current}</span> Protocols out of
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
		<th><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
    <th><?php echo $this->Paginator->sort('study_title'); ?></th>
		<th>Owner</th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	      </tr>
       </thead>
      <tbody>
	<?php
	foreach ($applications as $application): ?>
	<tr>
		<td><?php echo h($application['Application']['id']); ?>&nbsp;</td>
		<td><?php echo h($application['Application']['protocol_no']); ?>&nbsp;</td>
    <td><?php echo $this->Text->truncate($application['Application']['study_title'], 32, array('html' => true)); ?>&nbsp;</td>
		<td><?php echo h($application['User']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php
        echo $this->Form->postLink(__('<span class="badge badge-success">Assign</span>'), array('action' => 'view', $user['User']['id'], $application['Application']['id']), array('escape' => false), 
              __('Are you sure you want to assign protocol %s to %s?', $application['Application']['protocol_no'], $user['User']['name']));
      ?>
		</td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

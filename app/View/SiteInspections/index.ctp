<div class="siteInspections index">
	<h2><?php echo __('Site Inspections'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('study_title'); ?></th>
			<th><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
			<th><?php echo $this->Paginator->sort('version_no'); ?></th>
			<th><?php echo $this->Paginator->sort('trial_phase'); ?></th>
			<th><?php echo $this->Paginator->sort('investigators'); ?></th>
			<th><?php echo $this->Paginator->sort('co_investigators'); ?></th>
			<th><?php echo $this->Paginator->sort('study_stage'); ?></th>
			<th><?php echo $this->Paginator->sort('inspection_country'); ?></th>
			<th><?php echo $this->Paginator->sort('inspector_names'); ?></th>
			<th><?php echo $this->Paginator->sort('inspection_date'); ?></th>
			<th><?php echo $this->Paginator->sort('site_address'); ?></th>
			<th><?php echo $this->Paginator->sort('lab_address'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($siteInspections as $siteInspection): ?>
	<tr>
		<td><?php echo h($siteInspection['SiteInspection']['id']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['study_title']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['protocol_no']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['version_no']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['trial_phase']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['investigators']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['co_investigators']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['study_stage']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['inspection_country']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['inspector_names']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['inspection_date']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['site_address']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['lab_address']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['created']); ?>&nbsp;</td>
		<td><?php echo h($siteInspection['SiteInspection']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $siteInspection['SiteInspection']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $siteInspection['SiteInspection']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $siteInspection['SiteInspection']['id']), null, __('Are you sure you want to delete # %s?', $siteInspection['SiteInspection']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Site Inspection'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Site Answers'), array('controller' => 'site_answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Site Answer'), array('controller' => 'site_answers', 'action' => 'add')); ?> </li>
	</ul>
</div>

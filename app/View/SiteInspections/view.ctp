<div class="siteInspections view">
<h2><?php  echo __('Site Inspection'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Study Title'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['study_title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('ECCT Reference No'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['protocol_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Version No'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['version_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Trial Phase'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['trial_phase']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Investigators'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['investigators']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Co Investigators'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['co_investigators']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Study Stage'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['study_stage']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Inspection Country'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['inspection_country']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Inspector Names'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['inspector_names']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Inspection Date'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['inspection_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Site Address'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['site_address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lab Address'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['lab_address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($siteInspection['SiteInspection']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Site Inspection'), array('action' => 'edit', $siteInspection['SiteInspection']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Site Inspection'), array('action' => 'delete', $siteInspection['SiteInspection']['id']), null, __('Are you sure you want to delete # %s?', $siteInspection['SiteInspection']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Site Inspections'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Site Inspection'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Site Answers'), array('controller' => 'site_answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Site Answer'), array('controller' => 'site_answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Site Answers'); ?></h3>
	<?php if (!empty($siteInspection['SiteAnswer'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Site Inspection Id'); ?></th>
		<th><?php echo __('Question Type'); ?></th>
		<th><?php echo __('Question Number'); ?></th>
		<th><?php echo __('Question'); ?></th>
		<th><?php echo __('Answer'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($siteInspection['SiteAnswer'] as $siteAnswer): ?>
		<tr>
			<td><?php echo $siteAnswer['id']; ?></td>
			<td><?php echo $siteAnswer['site_inspection_id']; ?></td>
			<td><?php echo $siteAnswer['question_type']; ?></td>
			<td><?php echo $siteAnswer['question_number']; ?></td>
			<td><?php echo $siteAnswer['question']; ?></td>
			<td><?php echo $siteAnswer['answer']; ?></td>
			<td><?php echo $siteAnswer['comment']; ?></td>
			<td><?php echo $siteAnswer['created']; ?></td>
			<td><?php echo $siteAnswer['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'site_answers', 'action' => 'view', $siteAnswer['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'site_answers', 'action' => 'edit', $siteAnswer['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'site_answers', 'action' => 'delete', $siteAnswer['id']), null, __('Are you sure you want to delete # %s?', $siteAnswer['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Site Answer'), array('controller' => 'site_answers', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>

<?php
if ($redir == 'admin') {
	$this->Html->script('multi/deletefile', array('inline' => false));
} else {
	$this->Html->script('multi/extrafiles', array('inline' => false));
}

?>

<div class="row-fluid">
	<div class="span12">
		<?php

		?>
		<h4>15. Notification(s) -
			<small class="muted">(Notifications may include files (pictures, scanned documents, pdf, word documents) or generic updates</small>
		</h4>
		<hr>

		<h5>Do you have files that you would like to send to PPB? click on the button to add them:
			<button type="button" class="btn-mini" id="addMAttachment">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
		</h5>
		<table id="buildAattachmentsform" class="table table-bordered  table-condensed table-striped">
			<thead>
				<tr id="attachmentsTableHeader">
					<th>#</th>
					<th>File</th>
					<th>Text Description</th>
					<th><?php if ($redir != 'admin') { ?> Action <?php } ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($application['Attachment'])) {
					for ($i = 0; $i <= count($application['Attachment']) - 1; $i++) {
						// for ($i = 0; $i <= 0; $i++) {
				?>
						<tr>
							<td><?php echo $i + 1; ?></td>
							<td><?php
								// echo $this->Form->input('Attachment.'.$i.'.id');
								if (
									!empty($application['Attachment'][$i]['id']) &&
									!empty($application['Attachment'][$i]['basename'])
								) {
									echo $this->Html->link(
										__($application['Attachment'][$i]['basename']),
										array(
											'controller' => 'attachments',
											'action' => 'download',
											$application['Attachment'][$i]['id'],
											'full_base' => true
										),
										array('class' => 'btn btn-info')
									);
								}
								?>
								<small class="muted">- Uploaded on <?php echo $application['Attachment'][$i]['created']; ?></small>
							</td>
							<td>
								<?php
								if (
									!empty($application['Attachment'][$i]['id']) &&
									!empty($application['Attachment'][$i]['basename'])
								) {
									echo $application['Attachment'][$i]['description'];
								}
								?>
							</td>
							<td>

						 
								<!-- Action to delete the attachment -->
								<?php if ($redir != 'admin') { ?>
									<button type="button" class="btn-mini btn-danger remove-row-amendment" value="<?php if (isset($application['Attachment'][$i]['id'])) {
																														echo $application['Attachment'][$i]['id'];
																													} ?>">
										&nbsp;<i class="icon-trash"></i>&nbsp;
									</button>
								<?php } ?>
							</td>
						</tr>
				<?php }
				}; ?>
				<?php echo $this->fetch('attachments'); ?>
			</tbody>
		</table>
	</div><!--/span-->
</div><!--/row-->
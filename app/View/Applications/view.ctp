<?php
	// $this->Html->script('jqprint.0.3', array('inline' => false));
	$this->assign('Applications', 'active');
 ?>

<section id="pqmpsview">
  <div class="row-fluid">
	<div class="span12">
	  <?php
		echo $this->Session->flash();
	  ?>
	  <div class="row-fluid">
		<div class="span3">
			<div class="well" style="width:195px; padding:8px;">
				<ul class="nav nav-list">
				  <li class="divider"></li>
				  <li><?php echo $this->Html->link(__('List Applications'), array('action' => 'index')); ?> </li>
				  <li class="divider"></li>
				</ul>
			</div>
		</div>

		<div class="span9">
			<!-- <div class="form-actions">
				<div class="row-fluid">
					<div class="span4">
					<?php
						// echo $this->Html->link('Download PDF', array('action'=>'view', 'ext'=> 'pdf',
						// 										$application['Application']['id']),
						// array('class' => 'btn btn-primary mapop', 'title'=>'Download PDF',
						// 							'data-content' => 'Download the pdf version of the report',));
					?>
					</div>
					<div class="span4">
						<?php
								// echo $this->Form->button('Print Report', array('type' => 'button', 'class'=>'btn btn-inverse btnPrint' ,
								// 						'onclick' => '$(\'#applicationPrintArea\').jqprint(); '
								// 						));
						?>
					</div>
					<div class="span4">

					</div>
				</div>
			</div> -->

		   <div id="applicationPrintArea">
				<div class="vformbackp">
					 <hr>
					<table style="width: 100%;">
						<tr>
							<td style="width: 25%;">ECCT Reference No:</td>
							<td style="width: 25%;"><strong><?php echo __($application['Application']['protocol_no'], true) ?></strong></td>
							<td style="width: 25%;">Date of Protocol:</td>
							<td style="width: 25%;"><strong><?php echo __($application['Application']['date_of_protocol'], true) ?></strong></td>
						</tr>
					</table>
					 <hr>
					<table style="width: 100%;">
						<tr>
							<td style="width: 25%;">Study Title:</td>
							<td style="width: 75%;"><strong><?php echo $application['Application']['study_title']; ?></strong></td>							
						</tr>
						<?php
			              foreach($application['Amendment'] as $key => $amendment) {
			                if($amendment['submitted'] == 1 && !empty($amendment['study_title'])){      ?>
			               <tr class="table-viewlabel_rm"><td class="table-viewlabel_rm"><?php //echo $key+1; ?></td>
			                                           <td class="table-noline"> <?php echo $amendment['study_title'];  ?></td></tr>
			            <?php   } } ?>
						<tr>
							<td style="width: 25%;">Study Objectives:</td>
							<td style="width: 75%;"><strong><?php echo $application['Application']['study_objectives']; ?></strong></td>
						</tr>
						<?php
			              foreach($application['Amendment'] as $key => $amendment) {
			                if($amendment['submitted'] == 1 && !empty($amendment['study_objectives'])){      ?>
			               <tr class="table-viewlabel_rm"><td class="table-viewlabel_rm"><?php echo $key+1; ?></td>
			                                           <td class="table-noline"> <?php echo $amendment['study_objectives'];  ?></td></tr>
			            <?php   } } ?>
						<tr>
							<td style="width: 25%;">Laymans Summary:</td>
							<td style="width: 75%;"><?php echo $application['Application']['laymans_summary']; ?></td>
						</tr>
						<?php
			              foreach($application['Amendment'] as $key => $amendment) {
			                if($amendment['submitted'] == 1 && !empty($amendment['laymans_summary'])){      ?>
			               <tr class="table-viewlabel_rm"><td class="table-viewlabel_rm"><?php echo $key+1; ?></td>
			                                           <td class="table-noline"> <?php echo $amendment['laymans_summary'];  ?></td></tr>
			            <?php   } } ?>
						<tr>
							<td style="width: 25%;">Abstract of Study:</td>
							<td style="width: 75%;"><?php echo $application['Application']['abstract_of_study']; ?></td>
						</tr>
						<?php
			              foreach($application['Amendment'] as $key => $amendment) {
			                if($amendment['submitted'] == 1 && !empty($amendment['abstract_of_study'])){      ?>
			               <tr class="table-viewlabel_rm"><td class="table-viewlabel_rm"><?php echo $key+1; ?></td>
			                                           <td class="table-noline"> <?php echo $amendment['abstract_of_study'];  ?></td></tr>
			            <?php   } } ?>
					</table>
					<hr>
				</div>
			</div>

		</div>
	  </div>
	</div>
  </div>
</section>


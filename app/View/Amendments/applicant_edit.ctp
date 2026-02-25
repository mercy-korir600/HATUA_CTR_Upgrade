<?php
$this->extend('/Elements/application/applicant_view');

$currentAmendment = array();
$currentAmendmentId = !empty($this->request->data['Amendment']['id']) ? (int) $this->request->data['Amendment']['id'] : 0;
$amendmentCount = !empty($application['Amendment']) ? count($application['Amendment']) : 0;

if (!empty($application['Amendment'])) {
  foreach ($application['Amendment'] as $amendmentRow) {
    if (!empty($amendmentRow['id']) && (int) $amendmentRow['id'] === $currentAmendmentId) {
      $currentAmendment = $amendmentRow;
      break;
    }
  }

  if (empty($currentAmendment)) {
    $amendmentRows = array_values($application['Amendment']);
    $currentAmendment = $amendmentRows[count($amendmentRows) - 1];
  }
}

$sectionOne = !empty($currentAmendment['Amend']) ? $currentAmendment['Amend'] : array();
?>

<?php $this->start('amendment-lead'); ?>
  <?php
      $this->assign('MyApplications', 'active');
      $this->Html->script('ckeditor/ckeditor', array('inline' => false));
      $this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
      $this->Html->script('multi/amendment_attachments', array('inline' => false));
      $this->Html->css('amendment', null, array('inline' => false));
    ?>
     <div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
      <ul class="nav nav-tabs">
          <li class="active"><a href="#tab0" data-toggle="tab">Section 1</a></li>
          <li><a href="#tab1" data-toggle="tab">Amendment No. <?php echo $amendmentCount; ?></a></li>
          <li><a href="#tab2" data-toggle="tab">Reviewer's Comments</a></li>
      </ul>
      <div class="tab-content my-tab-content">
        <div class="tab-pane active" id="tab0">
          <div class="row-fluid">
            <h4 class="text-success">Section 1</h4>
          </div>
          <table class="table table-condensed table-striped table-bordered">
            <tbody>
              <?php
              echo $this->element('amendments/section_one_snapshot', array(
                'sectionOne' => $sectionOne,
                'format' => 'table_rows',
                'allowHtml' => true,
                'emptyText' => 'Not provided'
              ));
              ?>
              <tr>
                <td class="table-label"><strong>Cover Letter File</strong></td>
                <td>
                  <?php if (!empty($currentAmendment['CoverLetter'])) { ?>
                    <?php foreach ($currentAmendment['CoverLetter'] as $coverFile) { ?>
                      <?php if (!empty($coverFile['id']) && !empty($coverFile['basename'])) { ?>
                        <p style="margin-bottom: 6px;">
                          <?php
                            echo $this->Html->link(
                              __($coverFile['basename']),
                              array('controller' => 'attachments', 'action' => 'download', $coverFile['id']),
                              array('class' => 'btn btn-info btn-mini')
                            );
                          ?>
                          <small class="muted"> uploaded on <?php echo $coverFile['created']; ?></small>
                        </p>
                      <?php } ?>
                    <?php } ?>
                  <?php } else { ?>
                    <span class="muted">No cover letter file uploaded.</span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td class="table-label"><strong>Additional Attachments</strong></td>
                <td>
                  <?php if (!empty($currentAmendment['Attachment'])) { ?>
                    <?php foreach ($currentAmendment['Attachment'] as $attachmentFile) { ?>
                      <?php if (!empty($attachmentFile['id']) && !empty($attachmentFile['basename'])) { ?>
                        <p style="margin-bottom: 6px;">
                          <?php
                            echo $this->Html->link(
                              __($attachmentFile['basename']),
                              array('controller' => 'attachments', 'action' => 'download', $attachmentFile['id']),
                              array('class' => 'btn btn-info btn-mini')
                            );
                          ?>
                          <small class="muted">
                            uploaded on <?php echo !empty($attachmentFile['created']) ? $attachmentFile['created'] : 'N/A'; ?>
                            <?php if (!empty($attachmentFile['description'])) { ?>
                              - <?php echo h($attachmentFile['description']); ?>
                            <?php } ?>
                          </small>
                        </p>
                      <?php } ?>
                    <?php } ?>
                  <?php } else { ?>
                    <span class="muted">No additional attachments uploaded.</span>
                  <?php } ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab1">
          <!-- content for tab1 comes here -->

    <div class="row-fluid">
      <h4 class="text-success">
       Submitted Application :  (<?php echo $application['Application']['protocol_no'];?>) &mdash; <span class="muted">Amendment No.
        <?php
          echo $amendmentCount;
        ?>
        </span>
      </h4>
  </div>
<?php $this->end();?>

<?php $this->start('view-rightbar'); ?>
</div>

  <?php $this->end();  ?>

<?php $this->start('form-header'); ?>
    <div class="span12">
  <?php
      echo $this->Form->create('Amendment', array(
            'type' => 'file',
            'class' => 'form-horizontal',
            'inputDefaults' => array(
              'div' => false,
              'label' => false,
              'class' => '',
              'format' => array('before', 'label', 'between', 'input', 'after','error'),
              'error' => array('attributes' => array( 'class' => 'controls help-block')),
             ),
          ));
      echo $this->Form->input('id');
    ?>
<?php $this->end();?>

<?php
$this->start('form-actions');
?>
<div class="form-actions" style="margin-top: 0px; padding-left: 10px;">
	<?php
		echo $this->Form->button('Save Changes', array(
				'name' => 'saveChanges',
				'class' => 'btn btn-success',  'style' => 'margin-right: 10px;',
				'id' => 'SadrSaveChanges', 'title'=>'Save & continue editing',
				'data-content' => 'Save changes to form without submitting it.
																		The form will still be available for further editing.',
				'div' => false,
			));

		echo $this->Form->button('Submit', array(
						'name' => 'submitReport',
						'onclick'=>"return confirm('Are you sure you wish to submit the amendment to PPB? You will not be able to edit it later.');",
						'class' => 'btn btn-primary',   'style' => 'margin-right: 10px;',
						'id' => 'ApplicationSubmitReport', 'title'=>'Save and Submit Report',
						'data-content' => 'Save the report and submit it to the pharmacy and Poisons Board. You will also get a copy of this report.',
						'div' => false,
					));

		echo $this->Form->button('Cancel', array(
						'name' => 'cancelReport',
						'onclick'=>"return confirm('Are you sure you wish to cancel the form? You can edit it later.');",
						'class' => 'btn',  'style' => 'margin-right: 10px;',
						'id' => 'ApplicationCancelReport', 'title'=>'Cancel form',
						'data-content' => 'Cancel form and go back to dashboard.',
						'div' => false,
					));
            echo $this->Html->link('<i class="icon-trash"></i> Delete',
                array('controller' => 'amendments', 'action' => 'delete', $this->request->data['Amendment']['id']),
                array('escape' => false, 'class' => 'btn btn-danger',  'style' => 'margin-right: 10px;',
                    'onclick'=>"return confirm('Are you sure you wish to delete the form? You can not edit it later.');",
                  )
            );

          echo $this->Html->link(__('<i class="icon-download-alt"></i> Download PDF'),
            array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
            array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));

            // echo $this->Form->button('Delete', array(
            //         'name' => 'deleteReport',
            //         'onclick'=>"return confirm('Are you sure you wish to Delete the amendment? You can edit it later.');",
            //         'class' => 'btn mapop',  'style' => 'margin-right: 10px;',
            //         'id' => 'ApplicationCancelReport', 'title'=>'Cancel form',
            //         'data-content' => 'Cancel form and go back to dashboard.',
            //         'div' => false,
            //       ));
	?>
</div>
<?php $this->end(); ?>

<?php $this->start('tabs'); ?>
<ul>
  <li><a href="#tabs-1">1. Abstract</a></li>
  <li><a href="#tabs-2">2. Investigator &amp; Pharmacist</a></li>
  <li><a href="#tabs-3">3. Sponsor</a></li>
  <li><a href="#tabs-4">4. Participants</a></li>
  <li><a href="#tabs-5">5. Sites</a></li>
  <li><a href="#tabs-6">6. Placebo</a></li>
  <li><a href="#tabs-7">7. Criteria</a></li>
  <li><a href="#tabs-8">8. Scope</a></li>
  <li><a href="#tabs-9">9. Design</a></li>
  <li><a href="#tabs-15">10. Study Budget</a></li>
  <li><a href="#tabs-10">11. Organizations</a></li>
  <li><a href="#tabs-11">12. Other details</a></li>
  <li><a href="#tabs-12">13. Checklist </a></li>
  <li><a href="#tabs-13">14. Declaration</a></li>
  <li><a href="#tabs-14">15. Notifications</a></li>
</ul>
<?php $this->end(); ?>

<?php $this->start('endjs'); ?>
</div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab2">
        <div class="marketing">
             <div class="row-fluid">
                <div class="span12">
                   <h2 class="text-info">The Expert Committee on Clinical Trials</h2>
                   <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments</h3>
                </div>
             </div>
              <hr class="soften" style="margin: 10px 0px;">
        </div>
        <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no'];?></p>
        <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title'];?></p>
        <div class="row-fluid">
          <div class="span12">
            <h4 class="text-success">Reviewer's Comments
              <?php
                echo $this->Html->link(__('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
                  array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
                  array('escape' => false, 'class' => 'btn pull-right', 'style'=>'margin-right: 10px;'));
                ?>
              </h4>
            <?php
                $counter = 0;
                foreach ($application['Review'] as $review) {
                   $counter++;
                   echo "<hr><span class=\"badge badge-success\">".$counter."</span> <small class='muted'>created on: ".date('d-m-Y H:i:s', strtotime($review['created']))."</small>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['text']."</div>";
                   // echo "<br>";
                   echo "<div style='padding-left: 29px;' class='morecontent'>".$review['recommendation']."</div>";
                }
            ?>
          </div>
       </div>
    </div>
</div>
</div>

<script type="text/javascript">
	$(function() {
            $( "#tabs" ).tabs({
	      cookie: {
	                  expires: 1
	                }
	            });
			$('.mapop').popover();
		$('.tooltipper').tooltip();
		$( ".datepickers" ).datepicker({
			minDate:"-100Y", maxDate:"-0D", dateFormat:'dd-mm-yy', showButtonPanel:true, changeMonth:true, changeYear:true,
			buttonImageOnly:true, showAnim:'show', showOn:'both', buttonImage:'/img/calendar.gif'
		});
		$('#AmendmentStudyTitle').ckeditor();
	             $('#AmendmentAbstractOfStudy').ckeditor();
	             $('#AmendmentNotification').ckeditor();
			$('#AmendmentOrganizations').ckeditor();
			// CKEDITOR.replace( 'data[Amendment][study_title]');
			if ($.expander && $.expander.defaults) {
			  $.expander.defaults.slicePoint = 170;
			  $.expander.defaults.expandText = 'show more';
			  $.expander.defaults.userCollapseText = 'show less';
			}
			if ($.fn.expander) {
	            $(".morecontent").expander();
	        }
		});
</script>
<?php	$this->end(); ?>

<!-- START AMENDMENT FIELDS -->

<?php $this->start('study_title'); ?>
     <tr class="table-amendlabel"><td class="table-amendlabel"></td></tr>
     <tr class="table-amendlabel">
      <td class="table-noline">
        <?php
          echo $this->Form->input('Amendment.study_title', array(
            'label' => false, 'between'=>'<div class="nocontrols">', 'placeholder' => 'study title' , 'class' => 'input-large',
          ));
        ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('laymans_summary'); ?>
     <tr class="table-amendlabel"><td class="table-amendlabel"></td></tr>
     <tr class="table-amendlabel">
      <td class="table-noline">
        <?php
          echo $this->Form->input('Amendment.laymans_summary', array(
            'label' => false, 'between'=>'<div class="nocontrols">', 'placeholder' => 'Laymans Summary' , 'class' => 'input-large',
          ));
        ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('abstract_of_study'); ?>
     <tr class="table-amendlabel"><td class="table-amendlabel"></td></tr>
     <tr class="table-amendlabel">
      <td class="table-noline">
  <?php
    echo $this->Form->input('Amendment.abstract_of_study', array(
      'label' => false, 'between'=>'<div class="nocontrols">', 'placeholder' => 'abstract of study' , 'class' => 'input-large',
    ));
  ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('version_no'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
  <?php
    echo $this->Form->input('Amendment.version_no', array( 'placeholder' => ' version no' , 'class' => 'input-xxlarge', )); ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('date_of_protocol'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
  <?php
    echo $this->Form->input('Amendment.date_of_protocol', array(
        'type' => 'text', 'class' => 'datepickers', 'after'=>'<span class="help-inline">  Date format (dd-mm-yyyy) </span>',
    ));
  ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('study_drug'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
  <?php
    echo $this->Form->input('study_drug', array('placeholder' => 'Study Drug ' , 'class' => 'input-xxlarge',));
  ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('disease_condition'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('disease_condition', array('placeholder' => ' ' , 'class' => 'input-xxlarge', ));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('product_type'); ?>
     <tr class="table-amendlabel">
          <td class="table-amendlabel"></td>
          <td class="table-noline">
            <?php
              echo $this->Form->input('disease_condition', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 4));
            /*$productTypeError = $biologicalError =  $chemicalError =  $medicalDeviceError = '';
            if ($this->Form->isFieldError('product_type')) $productTypeError = 'error';
            if ($this->Form->isFieldError('product_type_biologicals')) $biologicalError = 'error';
            if ($this->Form->isFieldError('product_type_chemical')) $chemicalError = 'error';
            if ($this->Form->isFieldError('product_type_medical_device')) $medicalDeviceError = 'error';
            echo $this->Form->input('product_type', array('type' => 'hidden', 'value' => ''));
            echo $this->Form->error('Application.product_type', 'Please select at least one product type'
                        ,array('wrap' => 'span', 'class' => 'controls required error', 'escape'=> false));
            echo $this->Form->input('product_type_biologicals', array(
              'before' => '<div class="control-group '.$productTypeError.' '.$biologicalError.'">',
              'label' => false,
              'div' => false, 'class' => false, 'hiddenField' => false,
              'error' => array('attributes' => array( 'class' => 'required error')),
              'between' => '<input type="hidden" value="0"
                    id="ApplicationProductTypeBiologicals_" name="data[Application][product_type_biologicals]">
                    <label class="checkbox required">',
              'after' => 'Biologicals </label>',));
            echo $this->Form->input('product_type_proteins', array(
                // 'before' => '<div class="controls">',
                'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
                'between' => '<input type="hidden" value="0" id="ApplicationProductTypeProteins_"
                        name="data[Application][product_type_proteins]"> <label class="checkbox inline">',
                'after' => 'Proteins </label>',));
            echo $this->Form->input('product_type_immunologicals', array(
                'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
                'between' => '<input type="hidden" value="0" id="ApplicationProductTypeImmunologicals_"
                        name="data[Application][product_type_immunologicals]"> <label class="checkbox inline">',
                'after' => 'Immunologicals  </label>',));
            echo $this->Form->input('product_type_vaccines', array(
                'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
                'between' => '<input type="hidden" value="0" id="ApplicationProductTypeVaccines_"
                        name="data[Application][product_type_vaccines]">  <label class="checkbox inline">',
                'after' => 'Vaccines </label>',));
            echo $this->Form->input('product_type_hormones', array(
                'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
                'between' => '<input type="hidden" value="0" id="ApplicationProductTypeHormones_"
                        name="data[Application][product_type_hormones]"> <label class="checkbox inline">',
                'after' => 'Hormones  </label>',));
            echo $this->Form->input('product_type_toxoid', array(
                'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
                'between' => '<input type="hidden" value="0" id="ApplicationProductTypeToxoid_"
                        name="data[Application][product_type_toxoid]">  <label class="checkbox inline">',
                'after' => 'Toxoid </label></div>',));
            echo $this->Form->input('biologicals', array('type' => 'hidden', 'value' => ''));
            echo $this->Form->error('Application.biologicals', array('wrap' => 'span', 'class' => 'control-group required error'));


            echo $this->Form->input('product_type_chemical', array(
              'before' => false,
              'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
              'between' => '<input type="hidden" value="0"
                 id="ApplicationProductTypeChemical_" name="data[Application][product_type_chemical]"> <label class="checkbox required">',
              'after' => 'Chemical </label></div>',));
            echo $this->Form->input('product_type_chemical_name', array(
              'label' => false, 'div' => false, 'after' => '</div>',
              'placeholder' => 'generic name' , 'class' => 'input-xxlarge',
            ));
            echo $this->Form->input('product_type_medical_device', array(
              'before' => false,
              'label' => false, 'div' => false, 'class' => false, 'hiddenField' => false,
              'between' => '<input type="hidden" value="0"
                id="ApplicationProductTypeMedicalDevice_" name="data[Application][product_type_medical_device]">
                      <label class="checkbox required">',
              'after' => 'Medical Device </label></div>',));
            echo $this->Form->input('product_type_medical_device_name', array(
              'label' => false, 'div' => false, 'after' => '</div>',
              'placeholder' => ' ' , 'class' => 'input-xxlarge',
            ));*/
            ?>
          </td>
        </tr>
<?php $this->end(); ?>

<?php $this->start('previous_dates'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('previous_dates', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('approval_date'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('approval_date', array( 'type' => 'text', 'class' => 'datepickers',
              'after'=>'<span class="help-inline">  Date format (dd-mm-yyyy) </span>',
            ));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('coordinating_investigators'); ?>
     <tr class="table-amendlabel">
      <td class="table-label table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('coordinating_investigators', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 4));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('principal_investigators'); ?>
     <tr class="table-amendlabel">
      <td class="table-label table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('principal_investigators', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 4));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('pharmacists'); ?>
     <tr class="table-amendlabel">
      <td class="table-label table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('pharmacist', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 4));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('sponsor_details'); ?>
     <tr class="table-amendlabel">
      <td class="table-label table-amendlabel"></td>
      <td class="table-noline">
      <?php
        echo $this->Form->input('sponsor_details', array('placeholder' => ' ' , 'class' => 'input-xxlarge', 'rows' => 4));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('number_participants'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('number_participants', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('total_enrolment_per_site'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('total_enrolment_per_site', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('total_participants_worldwide'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('total_participants_worldwide', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_less_than_18_years'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_less_than_18_years', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_utero'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_utero', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_preterm_newborn'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_preterm_newborn', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_newborn'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_newborn', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_infant_and_toddler'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_infant_and_toddler', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_children'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_children', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_adolescent'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_adolescent', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_above_18'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_above_18', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_adult'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_adult', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('population_elderly'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('population_elderly', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_healthy'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_healthy', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_vulnerable_populations'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_vulnerable_populations', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_patients'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_patients', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_women_child_bearing'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_women_child_bearing', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_women_using_contraception'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_women_using_contraception', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_pregnant_women'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_pregnant_women', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_nursing_women'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_nursing_women', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_emergency_situation'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_emergency_situation', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_incapable_consent'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_incapable_consent', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_specify'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_specify', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_others'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_others', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('subjects_others_specify'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('subjects_others_specify', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('gender'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('gender', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('single_site_member_state'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('single_site_member_state', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('location_of_area'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('location_of_area', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('multiple_sites_member_state'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('multiple_sites_member_state', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('number_of_sites'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('number_of_sites', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('details_of_sites'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('details_of_sites', array('class' => 'input-xxlarge', 'rows' => 3));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('multiple_countries'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('multiple_countries', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('multiple_member_states'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('multiple_member_states', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('multi_country_list'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('multi_country_list', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('data_monitoring_committee'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('data_monitoring_committee', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('staff_numbers'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('staff_numbers', array('class' => 'input-xxlarge', 'rows' => 3));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('placebo_present'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('placebo_present', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('placebos'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('placebos', array('class' => 'input-xxlarge', 'rows' => 3));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('study_objectives'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('study_objectives', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('principal_inclusion_criteria'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('principal_inclusion_criteria', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('principal_exclusion_criteria'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('principal_exclusion_criteria', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('primary_end_points'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('primary_end_points', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('scopes'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('scopes', array('class' => 'input-xxlarge', 'rows' => 4));
      ?>
      </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('types_and_phases'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('types_and_phases', array('class' => 'input-xxlarge', 'rows' => 4));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_randomised'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_randomised', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_open'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_open', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_single_blind'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_single_blind', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_double_blind'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_double_blind', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_parallel_group'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_parallel_group', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_cross_over'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_cross_over', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_other'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_other', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_specify'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_specify', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_comparator'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_comparator', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_other_medicinal'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_other_medicinal', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_placebo'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_placebo', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_medicinal_other'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_medicinal_other', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('design_controlled_medicinal_specify'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('design_controlled_medicinal_specify', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('organizations'); ?>
     <tr class="table-amendlabel">
      <!-- <td class="table-amendlabel"></td> -->
      <td class="table-noline" colspan="2">
      <?php
            echo $this->Form->input('organizations', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('other_details_explanation'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('other_details_explanation', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('estimated_duration'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('estimated_duration', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('other_details_regulatory_notapproved'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('other_details_regulatory_notapproved', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('other_details_regulatory_approved'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('other_details_regulatory_approved', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('other_details_regulatory_rejected'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('other_details_regulatory_rejected', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('other_details_regulatory_halted'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('other_details_regulatory_halted', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('declaration_applicant'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('declaration_applicant', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('declaration_date1'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('declaration_date1', array('type'=>'text', 'label' => false, 'class' => 'input-xlarge datepickers'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('declaration_principal_investigator'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
            echo $this->Form->input('declaration_principal_investigator', array('class' => 'input-xxlarge'));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('declaration_date2'); ?>
     <tr class="table-amendlabel">
      <td class="table-amendlabel"></td>
      <td class="table-noline">
      <?php
          echo $this->Form->input('declaration_date2', array('type'=>'text', 'label' => false, 'class' => 'input-xlarge datepickers' ));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

<?php $this->start('attachments'); ?>
     <?php
      foreach($application['Amendment']  as $key => $amendment) {
        if($amendment['submitted'] == 0){
          for ($i = 0; $i <= count($amendment['Attachment'])-1; $i++) {
        ?>
         <tr>
          <td><?php echo count($application['Attachment'])+$i+1; ?></td>
          <td><?php
            echo $this->Html->link(__($amendment['Attachment'][$i]['basename']),
              array('controller' => 'attachments', 'admin' => false, 'action' => 'download',
                $amendment['Attachment'][$i]['id']), array('class' => 'btn btn-info'));
            ?>
            <small class="muted">- Uploaded on <?php echo $amendment['Attachment'][$i]['created'];?></small>
          </td>
          <td>
            <?php
              echo $amendment['Attachment'][$i]['description'];
            ?>
          </td>
          <td>
              <button  type="button" class="btn-mini removeATr"
                            id="<?php if (isset($amendment['Attachment'][$i]['id'])) { echo $amendment['Attachment'][$i]['id']; } ?>" >
                &nbsp;<i class="icon-minus">&nbsp;</i>
              </button>
          </td>
         </tr>
      <?php } }} ?>
<?php $this->end(); ?>

<?php $this->start('notification'); ?>
  <tr class="table-amendlabel">
      <td class="table-amendlabel" colspan="2">
      <?php
            echo $this->Form->input('notification', array('class' => 'input-xxlarge', 'rows' => 2));
      ?>
    </td>
   </tr>
<?php $this->end(); ?>

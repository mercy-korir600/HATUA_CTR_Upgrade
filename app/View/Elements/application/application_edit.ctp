<?php
$this->assign('MyApplications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jUpload/vendor/jquery.ui.widget.js', array('inline' => false));
$this->Html->script('jUpload/jquery.iframe-transport.js', array('inline' => false));
$this->Html->script('jUpload/jquery.fileupload.js', array('inline' => false));
$this->Html->script('save', array('inline' => false));
// pr($this->request->data);
?>
<div class="row-fluid">
  <?php echo $this->fetch('header'); ?>
  <?php echo $this->Session->flash(); ?>
</div>
<div class="row-fluid">
  <div class="span10">
    <?php
    echo $this->Form->create('Application', array(
      'type' => 'file',
      'class' => 'form-horizontal',
      'inputDefaults' => array(
        'div' => array('class' => 'control-group'),
        'label' => array('class' => 'control-label'),
        'between' => '<div class="controls">',
        'after' => '</div>',
        'class' => '',
        'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
        'error' => array('attributes' => array('class' => 'controls help-block')),
      ),
    ));
    echo $this->Form->input('id');
    ?>
    <div id="tabs">
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
      <div id="tabs-1">
        <?php
        echo $this->Form->input('study_title', array(
          'label' => array('class' => 'control-nolabel required', 'text' => 'Study Title <span class="sterix">*</span>'),
          'between' => '<div class="nocontrols">',
          'placeholder' => 'study title',
          'class' => 'input-large',
        ));
        echo $this->Form->input('short_title', array(
          'label' => array('class' => 'control-label required', 'text' => 'Short Title <span class="sterix">*</span>'),
          'maxlength' => 30,
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));
        echo $this->Form->input('laymans_summary', array(
          'label' => array('class' => 'control-nolabel required', 'text' => '<hr>Laymans Summary <span class="sterix">*</span>'),
          'between' => '<div class="nocontrols">',
          'placeholder' => 'study title',
          'class' => 'input-large',
        ));
        echo $this->Form->input('abstract_of_study', array(
          'label' => array('class' => 'control-nolabel required', 'text' => 'ABSTRACT OF THE STUDY <span class="sterix">*</span>'),
          'between' => '<div class="nocontrols">',
          'placeholder' => '',
          'class' => 'input-xxlarge',
        ));
        // echo $this->Form->input('protocol_no', array(
        // 'label' => array('class' => 'control-label required', 'text' => 'Protocol No:<span class="sterix">*</span>'),
        // 'placeholder' => ' ' , 'class' => 'input-xxlarge',
        // ));
        echo $this->Form->input('version_no', array(
          'label' => array('class' => 'control-label required', 'text' => 'Version No: <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));
        echo $this->Form->input('date_of_protocol', array(
          'div' => array('class' => 'control-group'),
          'type' => 'text',
          'class' => 'datepickers',
          'label' => array('class' => 'control-label required', 'text' => 'Date of Protocol <span class="sterix">*</span>'),
          'after' => '<span class="help-inline">  Date format (dd-mm-yyyy) </span></div>',
        ));
        echo $this->Form->input('study_drug', array(
          'label' => array('class' => 'control-label required', 'text' => 'Study Drug <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));
        echo $this->element('multi/study_routes');
        echo $this->Form->input('disease_condition', array(
          'label' => array('class' => 'control-label required', 'text' => 'Disease condition being investigated <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));

        $productTypeError = $biologicalError =  $chemicalError =  $medicalDeviceError = '';
        if ($this->Form->isFieldError('product_type')) $productTypeError = 'error';
        if ($this->Form->isFieldError('product_type_biologicals')) $biologicalError = 'error';
        if ($this->Form->isFieldError('product_type_chemical')) $chemicalError = 'error';
        if ($this->Form->isFieldError('product_type_medical_device')) $medicalDeviceError = 'error';
        echo $this->Form->input('product_type', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.product_type',
          'Please select at least one product type',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        echo $this->Form->input('product_type_biologicals', array(
          'before' => '<div class="control-group ' . $productTypeError . ' ' . $biologicalError . '">',
          'label' => array('class' => 'control-label required', 'text' => 'Product Type <span class="sterix">*</span>'),
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'error' => array('attributes' => array('class' => 'required error')),
          'between' => '<div class="controls"><input type="hidden" value="0"
                    id="ApplicationProductTypeBiologicals_" name="data[Application][product_type_biologicals]">
                    <label class="checkbox required">',
          'after' => 'Biologicals </label>',
        ));
        echo $this->Form->input('product_type_proteins', array(
          // 'before' => '<div class="controls">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationProductTypeProteins_"
                        name="data[Application][product_type_proteins]"> <label class="checkbox inline">',
          'after' => 'Proteins </label>',
        ));
        echo $this->Form->input('product_type_immunologicals', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationProductTypeImmunologicals_"
                        name="data[Application][product_type_immunologicals]"> <label class="checkbox inline">',
          'after' => 'Immunologicals  </label>',
        ));
        echo $this->Form->input('product_type_vaccines', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationProductTypeVaccines_"
                        name="data[Application][product_type_vaccines]">  <label class="checkbox inline">',
          'after' => 'Vaccines </label>',
        ));
        echo $this->Form->input('product_type_hormones', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationProductTypeHormones_"
                        name="data[Application][product_type_hormones]"> <label class="checkbox inline">',
          'after' => 'Hormones  </label>',
        ));
        echo $this->Form->input('product_type_toxoid', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationProductTypeToxoid_"
                        name="data[Application][product_type_toxoid]">  <label class="checkbox inline">',
          'after' => 'Toxoid </label></div></div>',
        ));
        echo $this->Form->input('biologicals', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error('Application.biologicals', array('wrap' => 'span', 'class' => 'control-group required error'));

        echo $this->Form->input('product_type_chemical', array(
          'before' => '<div class="control-group ' . $productTypeError . ' ' . $chemicalError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<div class="controls"><input type="hidden" value="0"
                 id="ApplicationProductTypeChemical_" name="data[Application][product_type_chemical]"> <label class="checkbox required">',
          'after' => 'Chemical </label></div>',
        ));
        echo $this->Form->input('product_type_chemical_name', array(
          'label' => false,
          'div' => false,
          'after' => '</div></div>',
          'placeholder' => 'generic name',
          'class' => 'input-xxlarge',
        ));
        echo $this->Form->input('product_type_medical_device', array(
          'before' => '<div class="control-group ' . $productTypeError . ' ' . $medicalDeviceError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<div class="controls"><input type="hidden" value="0"
                id="ApplicationProductTypeMedicalDevice_" name="data[Application][product_type_medical_device]">
                      <label class="checkbox required">',
          'after' => 'Medical Device </label></div>',
        ));
        echo $this->Form->input('product_type_medical_device_name', array(
          'label' => false,
          'div' => false,
          'after' => '</div></div>',
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));
        // echo $this->Form->input('ecct_not_applicable', array(
        // 'before' => '<div class="control-group">',
        // 'label' => array('class' => 'control-label required', 'text' => 'ECCT Ref number (if applicable) <span class="sterix">*</span>'),
        // 'div' => false, 'class' => false, 'hiddenField' => false,
        // 'between' => '<div class="controls"><input type="hidden" value="0" id="ApplicationGenderFemale_" name="data[Application][gender_female]">
        // <label class="checkbox">',
        // 'after' => 'Not Applicable </label></div>',));
        // echo $this->Form->input('ecct_ref_number', array(
        // 'label' => false, 'div' => false, 'after' => '</div></div>',
        // 'placeholder' => ' ' , 'class' => 'input-xxlarge',
        // ));
        // echo $this->Form->input('ecct_ref_number', array(
        // 'label' => array('class' => 'control-label required', 'text' => 'ECCT Ref number (if applicable) <span class="sterix">*</span>'),
        // 'between'=>'<div class="controls input-prepend">
        // <span class="add-on">
        // <input type="hidden" value="0" id="ApplicationEcctNotApplicable_" name="data[Application][ecct_not_applicable]">
        // <label style="padding-top:0px;" class="checkbox inline">
        // <input type="checkbox" id="ApplicationEcctNotApplicable" value="1" class="" name="data[Application][ecct_not_applicable]"> Not Applicable
        // </label>
        // </span>', 'placeholder' => ' ' , 'class' => 'span4',
        // ));
        echo $this->element('multi/manufacturers');

        $comparator = '';
        if ($this->Form->isFieldError('comparator')) $comparator = 'error';
        echo $this->Form->input('comparator', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'comparator',
          'before' => '<div class="control-group ' . $comparator . ' ">   <label class="control-label required">

                Is there a comparator drug/medical device? <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationComparator_" name="data[Application][comparator]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('comparator', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'comparator',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.comparator\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>

                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        echo $this->Form->input('comparator_name', array(
          'label' => array('class' => 'control-label', 'text' => 'If yes, give the name'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('comparator_registered', array(
          'label' => array('class' => 'control-label', 'text' => 'If yes, is the comparator currently registered?'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('comparator_countries', array(
          'label' => array('class' => 'control-label', 'text' => 'List of the countries where the comparator is registered'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'rows' => '2'
        ));

        echo $this->element('multi/ethical_committees');
        // echo $this->Form->input('protocol_reviewers_names', array(
        // 'label' => array('class' => 'control-label', 'text' => 'Names of Protocol Reviewers'),
        // 'placeholder' => '' , 'class' => 'input-xxlarge',
        // ));
        // echo $this->Form->input('current_status_trial', array(
        // 'type' => 'select', 'options' => array('recruiting' => 'recruiting', 'closed' => 'closed'), 'empty' => true,
        // 'label' => array('class' => 'control-label', 'text' => 'Current status of the trial'),
        // ));
        // echo $this->Form->input('trial_status_id', array(
        // 'options'=>$trial_statuses, 'empty' => true,
        // 'label' => array('class' => 'control-label', 'text' => 'Current status of the trial'),
        // ));
        ?>
      </div>
      <div id="tabs-2">
        <h5>2.0 CO-ORDINATING INVESTIGATOR (<em>for multicentre trials in Kenya</em>) </h5>
        <hr>
        <?php
        echo $this->Form->input('investigator1_given_name', array(
          'label' => array('class' => 'control-label required', 'text' => 'Given name <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_middle_name', array(
          'label' => array('class' => 'control-label', 'text' => 'Middle name, if applicable'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_family_name', array(
          'label' => array('class' => 'control-label required', 'text' => 'Family name <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_qualification', array(
          'label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_professional_address', array(
          'label' => array('class' => 'control-label required', 'text' => 'Professional address <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_telephone', array(
          'label' => array('class' => 'control-label required', 'text' => 'Telephone number <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('investigator1_email', array(
          'type' => 'email',
          'label' => array('class' => 'control-label required', 'text' => 'email address <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        ?>
        <hr>
        <?php
        echo $this->element('multi/investigators');
        ?>
        <hr>
        <div>
          <?php
          echo $this->element('multi/pharmacists');
          ?>
        </div>
      </div>
      <div id="tabs-3">
        <?php
        echo $this->element('multi/sponsors');
        ?>
      </div>
      <div id="tabs-4">
        <h5>4.0 PARTICIPANTS (SUBJECTS)</h5>
        <hr>
        <?php
        echo $this->Form->input('number_participants', array(
          'label' => array('class' => 'control-label required', 'text' => 'Expected Number of participants in Kenya <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'rows' => '3'
        ));
        echo $this->Form->input('total_enrolment_per_site', array(
          'label' => array(
            'class' => 'control-label required',
            'text' => 'Total enrolment in each Kenyan site: (if competitive enrolment, state minimum and maximum number per site.)  <span class="sterix">*</span>'
          ),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'rows' => '3'
        ));
        echo $this->Form->input('total_participants_worldwide', array(
          'label' => array('class' => 'control-label required', 'text' => 'Total participants worldwide  <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
        ));
        ?>
        <hr>
        <h5>4.1 AGE SPAN</h5>
        <?php
        $ageSpanError = $less18Error =  $uteroError = $pretermError = $newbornError = $infantError = $childrenError =
          $adolescentError = '';
        if ($this->Form->isFieldError('age_span')) $ageSpanError = 'error';
        if ($this->Form->isFieldError('population_less_than_18_years')) $less18Error = 'error';
        if ($this->Form->isFieldError('population_utero')) $uteroError = 'error';
        if ($this->Form->isFieldError('population_preterm_newborn')) $pretermError = 'error';
        if ($this->Form->isFieldError('population_newborn')) $newbornError = 'error';
        if ($this->Form->isFieldError('population_infant_and_toddler')) $infantError = 'error';
        if ($this->Form->isFieldError('population_children')) $childrenError = 'error';
        if ($this->Form->isFieldError('population_adolescent')) $adolescentError = 'error';
        echo $this->Form->input('age_span', array('type' => 'hidden', 'value' => ''));

        echo $this->Form->error(
          'Application.age_span',
          'You have to select yes for either less than 18 years or greater than 18 years',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        ?>
        <hr>
        <?php
        echo $this->Form->input('population_less_than_18_years', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'population_less_than_18_years',
          'before' => '<div class="control-group ' . $ageSpanError . ' ' . $less18Error . '">   <label class="control-label required">

                Less than 18 years? <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationLessThan18Years_" name="data[Application][population_less_than_18_years]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('population_less_than_18_years', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'population_less_than_18_years',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_less_than_18_years\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>

                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        ?>
        <div class="ctr-groups">
          <p class="topper"><em class="text-success">If Yes, Specify</em></p>
          <?php
          echo $this->Form->input('population_utero', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_utero',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $uteroError . '">
                <label class="control-label required">In Utero <span class="sterix">*</span></label> <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationUtero_" name="data[Application][population_utero]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_utero', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_utero',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_utero\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_preterm_newborn', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_preterm_newborn',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $pretermError . '">
                <label class="control-label required">Preterm Newborn Infants (up to gestational age &lt; 37 weeks) <span class="sterix">*</span> </label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationPretermNewborn_" name="data[Application][population_preterm_newborn]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_preterm_newborn', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_preterm_newborn',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_preterm_newborn\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_newborn', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_newborn',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $newbornError . '">
                <label class="control-label required">Newborn (0-28 days) <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationNewborn_" name="data[Application][population_newborn]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_newborn', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_newborn',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_newborn\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_infant_and_toddler', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_infant_and_toddler',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $infantError . '">
                <label class="control-label required">Infant and toddler (29 days - 23 months) <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationInfantAndToddler_" name="data[Application][population_infant_and_toddler]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_infant_and_toddler', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_infant_and_toddler',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_infant_and_toddler\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_children', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_children',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $childrenError . '">
                <label class="control-label required">Children (2-12 years) <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationChildren_" name="data[Application][population_children]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_children', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_children',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),

            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_children\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_adolescent', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_adolescent',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $adolescentError . '">
                <label class="control-label required">Adolescent (13-17 years) <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationPopulationAdolescent_" name="data[Application][population_adolescent]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_adolescent', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_adolescent',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_adolescent\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          ?>
        </div>

        <?php
        $above18Error = $adultError = $elderlyError = '';
        if ($this->Form->isFieldError('population_above_18')) $above18Error = 'error';
        if ($this->Form->isFieldError('population_adult')) $adultError = 'error';
        if ($this->Form->isFieldError('population_elderly')) $elderlyError = 'error';
        echo $this->Form->input('population_above_18', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'population_above_18',
          'before' => '<div class="control-group ' . $ageSpanError . ' ' . $above18Error . '">

                      <label class="control-label required">18 Years and over  <span class="sterix">*</span></label>  <div class="controls">
                      <input type="hidden" value="" id="ApplicationPopulationAbove18_" name="data[Application][population_above_18]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('population_above_18', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'population_above_18',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_above_18\').removeAttr(\'checked disabled\')">

                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        ?>
        <div class="ctr-groups">
          <p class="topper"><em class="text-success">If Yes, Specify</em></p>
          <?php
          echo $this->Form->input('population_adult', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_adult population_above_18',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $adultError . '">
                      <label class="control-label required">Adult (18-65 years)  <span class="sterix">*</span></label> <div class="controls">

                      <input type="hidden" value="" id="ApplicationPopulationAdult_" name="data[Application][population_adult]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_adult', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_adult population_above_18',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_adult\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('population_elderly', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'population_elderly population_above_18',
            'before' => '<div class="control-group ' . $ageSpanError . ' ' . $elderlyError . '">
                      <label class="control-label required">Elderly (&gt; 65 years) <span class="sterix">*</span> </label><div class="controls">

                      <input type="hidden" value="" id="ApplicationPopulationElderly_" name="data[Application][population_elderly]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('population_elderly', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'population_elderly population_above_18',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.population_elderly\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          ?>
        </div>
        <hr>
        <h5>4.2 GROUP OF TRIAL SUBJECTS</h5>
        <hr>
        <?php
        $subjectsHealthyError = $subjectsVulnerableError = $subjectsPatientsError =  $subjectsChildBearingError =
          $subjectsContraceptionError = $subjectsPregnantError = $subjectsNursingError = $subjectsEmergencyError =
          $subjectsIncapableError = $subjectsOthersError = '';
        if ($this->Form->isFieldError('subjects_healthy')) $subjectsHealthyError = 'error';
        if ($this->Form->isFieldError('subjects_vulnerable_populations')) $subjectsVulnerableError = 'error';
        if ($this->Form->isFieldError('subjects_patients')) $subjectsPatientsError = 'error';
        if ($this->Form->isFieldError('subjects_women_child_bearing')) $subjectsChildBearingError = 'error';
        if ($this->Form->isFieldError('subjects_women_using_contraception')) $subjectsContraceptionError = 'error';
        if ($this->Form->isFieldError('subjects_pregnant_women')) $subjectsPregnantError = 'error';
        if ($this->Form->isFieldError('subjects_nursing_women')) $subjectsNursingError = 'error';
        if ($this->Form->isFieldError('subjects_emergency_situation')) $subjectsEmergencyError = 'error';
        if ($this->Form->isFieldError('subjects_incapable_consent')) $subjectsIncapableError = 'error';
        if ($this->Form->isFieldError('subjects_others')) $subjectsOthersError = 'error';
        echo $this->Form->input('subjects_healthy', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'subjects_healthy',
          'before' => '<div class="control-group ' . $subjectsHealthyError . '">
                <label class="control-label required">Healthy volunteers <span class="sterix">*</span> </label> <div class="controls">

                <input type="hidden" value="" id="ApplicationSubjectsHealthy_" name="data[Application][subjects_healthy]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('subjects_healthy', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'subjects_healthy',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_healthy\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        echo $this->Form->input('subjects_vulnerable_populations', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'subjects_vulnerable_populations',
          'before' => '<div class="control-group ' . $subjectsVulnerableError . '"> <label class="control-label required">
                Specific vulnerable populations <span class="sterix">*</span></label> <div class="controls">

                <input type="hidden" value="" id="ApplicationSubjectsVulnerablePopulations_" name="data[Application][subjects_vulnerable_populations]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('subjects_vulnerable_populations', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'subjects_vulnerable_populations',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_vulnerable_populations\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        ?>
        <div class="ctr-groups">
          <p class="topper"><em class="text-success">Specific vulnerable populations</em></p>
          <?php
          echo $this->Form->input('subjects_patients', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_patients',
            'before' => '<div class="control-group ' . $subjectsPatientsError . '"> <label class="control-label required">Patients
                <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationSubjectsPatients_" name="data[Application][subjects_patients]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_patients', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_patients',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),

            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_patients\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_women_child_bearing', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_women_child_bearing',
            'before' => '<div class="control-group ' . $subjectsChildBearingError . '">
                <label class="control-label required">Women of child bearing potential <span class="sterix">*</span> </label> <div class="controls">
                <input type="hidden" value="" id="ApplicationsubjectsWomenChildBearing_" name="data[Application][subjects_women_child_bearing]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_women_child_bearing', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_women_child_bearing',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_women_child_bearing\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_women_using_contraception', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_women_using_contraception',
            'before' => '<div class="control-group ' . $subjectsContraceptionError . '"> <label class="control-label required">
                Women of child bearing potential using contraception <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationSubjectsWomenUsingContraception_" name="data[Application][subjects_women_using_contraception]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_women_using_contraception', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_women_using_contraception',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_women_using_contraception\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_pregnant_women', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_pregnant_women',
            'before' => '<div class="control-group ' . $subjectsPregnantError . '">
                <label class="control-label required">Pregnant women <span class="sterix">*</span></label>  <div class="controls">
                <input type="hidden" value="" id="ApplicationSubjectsPregnantWomen_" name="data[Application][subjects_pregnant_women]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_pregnant_women', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_pregnant_women',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>

                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_pregnant_women\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_nursing_women', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_nursing_women',
            'before' => '<div class="control-group ' . $subjectsNursingError . '">  <label class="control-label required">
                Nursing Women <span class="sterix">*</span> </label>
                <div class="controls">  <input type="hidden" value="" id="ApplicationSubjectsNursingWomen_"
                name="data[Application][subjects_nursing_women]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_nursing_women', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'before' => '<label class="radio inline">',
            'after' => '</label>',
            'options' => array('No' => 'No')
          ));
          echo $this->Form->input('subjects_nursing_women', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_nursing_women',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_nursing_women\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('Unclear' => 'Unclear'),
          ));
          echo $this->Form->input('subjects_emergency_situation', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_emergency_situation',
            'before' => '<div class="control-group ' . $subjectsEmergencyError . '"> <label class="control-label required">
                Emergency situation <span class="sterix">*</span></label>
                <div class="controls">  <input type="hidden" value="" id="ApplicationSubjectsEmergencySituation_"

                name="data[Application][subjects_emergency_situation]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_emergency_situation', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_emergency_situation',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_emergency_situation\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>

                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_incapable_consent', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_incapable_consent',
            'before' => '<div class="control-group ' . $subjectsIncapableError . '"> <label class="control-label required">
                Subjects incapable of giving consent personally <span class="sterix">*</span>

                </label> <div class="controls">  <input type="hidden" value="" id="ApplicationSubjectsIncapableConsent_"
                name="data[Application][subjects_incapable_consent]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_incapable_consent', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_incapable_consent',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_incapable_consent, #ApplicationSubjectsSpecify\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_specify', array(
            'label' => array('class' => 'control-label required', 'text' => 'If yes, specify'),
            'placeholder' => ' ',
            'class' => 'input-xxlarge subjects_incapable_consent',
            'rows' => '3'
          ));
          echo $this->Form->input('subjects_others', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'subjects_others',
            'before' => '<div class="control-group ' . $subjectsOthersError . '"> <label class="control-label required">
                Others <span class="sterix">*</span></label>

                <div class="controls">  <input type="hidden" value="" id="ApplicationSubjectsOthers_"
                name="data[Application][subjects_others]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('subjects_others', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'subjects_others',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.subjects_others, #ApplicationSubjectsOthersSpecify\').removeAttr(\'checked disabled\')">

                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('subjects_others_specify', array(
            'label' => array('class' => 'control-label required', 'text' => 'If yes, specify'),
            'placeholder' => ' ',
            'class' => 'input-xxlarge subjects_others',
            'rows' => '3'
          ));
          ?>
        </div>
        <hr>
        <h5>4.3 GENDER <span class="sterix">*</span></h5>
        <hr>
        <?php
        echo $this->Form->input('gender', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.gender',
          'Please select at least one gender below',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        $genderError = '';
        if ($this->Form->isFieldError('gender')) $genderError = 'error';
        echo $this->Form->input('gender_female', array(
          'before' => '<div class="control-group ' . $genderError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,

          'between' => '<div class="controls"><input type="hidden" value="0" id="ApplicationGenderFemale_" name="data[Application][gender_female]">
                                  <label class="checkbox required">',
          'after' => 'Female </label></div></div>',
        ));

        echo $this->Form->input('gender_male', array(
          'before' => '<div class="control-group ' . $genderError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<div class="controls"><input type="hidden" value="0" id="ApplicationGenderMale_" name="data[Application][gender_male]">
                                  <label class="checkbox required">',
          'after' => 'Male </label></div></div>',
        ));
        ?>
      </div>
      <div id="tabs-5">
        <h5>TICK AND PROVIDE NECESSARY DETAILS AS APPROPRIATE</h5>
        <hr>
        <?php
        echo $this->Form->input('site_exists', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.site_exists',
          'You have to select yes for either single site, muliple sites or multiple countries',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        ?>
        <h5>5.0 Number of Sites</h5>

        <?php
        echo $this->Form->input('single_site_member_state', array(
          'type' => 'radio',
          'label' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'single_site_member_state',
          'before' => '<div class="control-group"> <div class="required">
                      <label class="control-label required">Single site in Kenya  <span class="sterix">*</span> </label> </div> <div class="controls">
                      <input type="hidden" value="" id="ApplicationSingleSiteMemberState_" name="data[Application][single_site_member_state]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('single_site_member_state', array(
          'type' => 'radio',
          'label' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'single_site_member_state',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.single_site_member_state\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        echo $this->Form->input('location_of_area', array(
          'label' => array('class' => 'control-label', 'text' => '<b>If yes</b>, name of site'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge single_site_member_state_f',
        ));
        echo $this->Form->input('single_site_physical_address', array(
          'label' => array('class' => 'control-label', 'text' => 'Physical address'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge single_site_member_state_f',
        ));
        echo $this->Form->input('single_site_contact_person', array(
          'label' => array('class' => 'control-label', 'text' => 'Contact person'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge single_site_member_state_f',
        ));
        echo $this->Form->input('single_site_telephone', array(
          'label' => array('class' => 'control-label', 'text' => 'Telephone'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge single_site_member_state_f',
        ));

        echo $this->element('multi/sites');

        echo $this->Form->input('multiple_countries', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'multiple_countries',
          'before' => '<div class="control-group"> <div class="required">
                      <label class="control-label required">Multiple Countries  <span class="sterix">*</span> </label> </div> <div class="controls">
                      <input type="hidden" value="" id="ApplicationMultipleCountries_" name="data[Application][multiple_countries]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('multiple_countries', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'multiple_countries',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.multiple_countries\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        echo $this->Form->input('multiple_member_states', array(
          'label' => array('class' => 'control-label', 'text' => 'Number of states anticipated in the trial'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge multiple_countries_f',
        ));
        echo $this->Form->input('multi_country_list', array(
          'label' => array('class' => 'control-label', 'text' => 'If yes above, list the countries'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge multiple_countries_f',
          'rows' => '3'
        ));

        echo $this->Form->input('data_monitoring_committee', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'data_monitoring_committee',
          'before' => '<div class="control-group"> <div class="required">
                      <label class="control-label required">Does this trial have a data monitoring committee?  <span class="sterix">*</span> </label> </div> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDataMonitoringCommittee_" name="data[Application][data_monitoring_committee]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('data_monitoring_committee', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'data_monitoring_committee',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.data_monitoring_committee\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        // echo $this->Form->input('site_capacity', array(
        // 'label' => array('class' => 'control-label required', 'text' => '19.0 Capacity of Site(s) <span class="sterix">*</span>'),
        // 'placeholder' => ' ' , 'class' => 'input-xxlarge'
        // ));
        ?>
        <hr>
        <h5>5.1</h5>
        <?php
        echo $this->Form->input('staff_numbers', array(
          'label' => array('class' => 'control-nolabel', 'text' => '<h5> Capacity of Site(s)  <span class="sterix">*</span></h5> <h5> Number of staff, names, qualifications, experience
              -- including study co-ordinators, site facilities, emergency facilities, other relevant infrastructure)  </h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        ?>
      </div>
      <div id="tabs-6">
        <?php echo $this->element('multi/placebos'); ?>
      </div>
      <div id="tabs-7">
        <?php
        echo $this->Form->input('study_objectives', array(
          'label' => array('class' => 'control-nolabel', 'text' => ' <hr><h5>7.0 STUDY OBJECTIVES <span class="sterix">*</span></h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'rows' => '3',
          'escape' => false,
        ));
        echo $this->Form->input('principal_inclusion_criteria', array(
          'label' => array('class' => 'control-nolabel', 'text' => ' <hr><h5>7.1 PRINCIPAL INCLUSION CRITERIA <span class="sterix">*</span></h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'rows' => '3',
          'escape' => false,
        ));
        echo $this->Form->input('principal_exclusion_criteria', array(
          'label' => array('class' => 'control-nolabel', 'text' => '<hr><h5>7.2 PRINCIPAL EXCLUSION CRITERIA <span class="sterix">*</span></h5> '),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'rows' => '3',
          'escape' => false,
        ));
        echo $this->Form->input('primary_end_points', array(
          'label' => array('class' => 'control-nolabel', 'text' => '<hr><h5>7.3 PRIMARY END POINTS <span class="sterix">*</span></h5> '),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'rows' => '3',
          'escape' => false,
        ));
        ?>
      </div>
      <div id="tabs-8">
        <hr>
        <?php
        echo $this->Form->input('scope', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.scope',
          'Please select at least one option for scope of the trial',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        $scopeError = '';
        if ($this->Form->isFieldError('scope')) $scopeError = 'error';
        ?>
        <h5 class="<?php echo $scopeError; ?>">8.0 SCOPE OF THE TRIAL - <span class="sterix">*</span> <small>Tick all boxes where applicable</small></h5>
        <div class="row-fluid">
          <div class="span4">
            <?php
            echo $this->Form->input('scope_diagnosis', array(
              'before' => '<div class="control-group ' . $scopeError . '">',
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeDiagnosis_" name="data[Application][scope_diagnosis]">
                                  <label class="checkbox">',
              'after' => 'Diagnosis </label>',
            ));
            echo $this->Form->input('scope_prophylaxis', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeProphylaxis_" name="data[Application][scope_prophylaxis]">
                                  <label class="checkbox">',
              'after' => 'Prophylaxis </label>',
            ));
            echo $this->Form->input('scope_therapy', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeTherapy_" name="data[Application][scope_therapy]">
                                  <label class="checkbox">',
              'after' => 'Therapy </label>',
            ));
            echo $this->Form->input('scope_safety', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeSafety_" name="data[Application][scope_safety]">
                                  <label class="checkbox">',
              'after' => 'Safety </label>',
            ));
            echo $this->Form->input('scope_efficacy', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeEfficacy_" name="data[Application][scope_efficacy]">
                                  <label class="checkbox">',
              'after' => 'Efficacy </label>',
            ));
            echo $this->Form->input('scope_pharmacokinetic', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopePharmacokinetic_" name="data[Application][scope_pharmacokinetic]">
                                  <label class="checkbox">',
              'after' => 'Pharmacokinetic </label>',
            ));
            echo $this->Form->input('scope_pharmacodynamic', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopePharmacodynamic_" name="data[Application][scope_pharmacodynamic]">
                                  <label class="checkbox">',
              'after' => 'Pharmacodynamic </label></div>',
            ));

            ?>
          </div>
          <div class="span5">
            <?php
            echo $this->Form->input('scope_bioequivalence', array(
              'before' => '<div class="control-group ' . $scopeError . '">',
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeBioequivalence_" name="data[Application][scope_bioequivalence]">
                                  <label class="checkbox">',
              'after' => 'Bioequivalence </label>',
            ));
            echo $this->Form->input('scope_dose_response', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeDoseResponse_" name="data[Application][scope_dose_response]">
                                  <label class="checkbox">',
              'after' => 'Dose Response </label>',
            ));
            echo $this->Form->input('scope_pharmacogenetic', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopePharmacogenetic_" name="data[Application][scope_pharmacogenetic]">
                                  <label class="checkbox">',
              'after' => 'Pharmacogenetic </label>',
            ));
            echo $this->Form->input('scope_pharmacogenomic', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopePharmacogenomic_" name="data[Application][scope_pharmacogenomic]">
                                  <label class="checkbox">',
              'after' => 'Pharmacogenomic </label>',
            ));
            echo $this->Form->input('scope_pharmacoecomomic', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopePharmacoecomomic_" name="data[Application][scope_pharmacoecomomic]">
                                  <label class="checkbox">',
              'after' => 'Pharmacoecomomic </label></div>',
            ));
            ?>
          </div>
        </div>
        <div class="row-fluid">
          <div class="span12">
            <?php
            echo $this->Form->input('scope_others', array(
              'label' => false,
              'div' => false,
              'class' => false,
              'hiddenField' => false,
              'between' => '<input type="hidden" value="0" id="ApplicationScopeOther_" name="data[Application][scope_others]">
                                  <label class="checkbox">',
              'after' => 'Others  </label>',
            ));
            echo $this->Form->input('scope_others_specify', array(
              'class' => 'input-xxlarge',
              'rows' => '3',
              'between' => false,
              'label' => array('class' => 'checkbox', 'text' => 'If others, specify'),
              'after' => '<p class="help-block">  </p>',
              'readonly' => 'readonly',
              'placeholder' => 'If others, specify',
            ));
            ?>
          </div>
        </div>
        <hr>
        <?php
        echo $this->Form->input('phase', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.phase',
          'Please select at least one option for trial type and phase',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        $phaseError = '';
        if ($this->Form->isFieldError('phase')) $phaseError = 'error';
        ?>
        <h5 class="<?php echo $phaseError; ?>">8.1 TRIAL TYPE AND PHASE <span class="sterix">*</span></h5>
        <?php
        echo $this->Form->input('trial_human_pharmacology', array(
          'before' => '<div class="control-group ' . $phaseError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialHumanPharmacology_" name="data[Application][trial_human_pharmacology]">
                                <label class="checkbox">',
          'after' => 'Human pharmacology  (Phase I) </label></div>',
        ));
        ?>
        <h6>Is it:</h6>
        <?php
        echo $this->Form->input('trial_administration_humans', array(
          'before' => '<div class="control-group ' . $phaseError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialAdministrationHumans_" name="data[Application][trial_administration_humans]">
                                <label class="checkbox">',
          'after' => 'First administration to humans </label>',
        ));
        echo $this->Form->input('trial_bioequivalence_study', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialBioequivalenceStudy_" name="data[Application][trial_bioequivalence_study]">
                                <label class="checkbox">',
          'after' => 'Bioequivalence study </label>',
        ));
        echo $this->Form->input('trial_other', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialOther_" name="data[Application][trial_other]">
                                <label class="checkbox">',
          'after' => 'Other </label></div>',
        ));
        // echo $this->Form->input('trial', array('type' => 'hidden', 'value' => ''));
        // echo $this->Form->error('Application.trial', array('wrap' => 'span', 'class' => 'control-group '.$phaseError.' required error'));
        echo $this->Form->input('trial_other_specify', array(
          'class' => 'input-xxlarge',
          'rows' => '3',
          'between' => false,
          'label' => array('class' => 'checkbox', 'text' => 'If other, please specify'),
          'after' => '<p class="help-block">  </p>',
          'readonly' => 'readonly',
          'placeholder' => 'If other, please specify',
        ));
        echo $this->Form->input('trial_therapeutic_exploratory', array(
          'before' => '<div class="control-group ' . $phaseError . '">',
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialTherapeuticExploratory_" name="data[Application][trial_therapeutic_exploratory]">
                                <label class="checkbox">',
          'after' => 'Therapeutic exploratory  (Phase II) </label>',
        ));
        echo $this->Form->input('trial_therapeutic_confirmatory', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialTherapeuticConfirmatory_" name="data[Application][trial_therapeutic_confirmatory]">
                                <label class="checkbox">',
          'after' => 'Therapeutic confirmatory (Phase III) </label>',
        ));
        echo $this->Form->input('trial_therapeutic_use', array(
          'label' => false,
          'div' => false,
          'class' => false,
          'hiddenField' => false,
          'between' => '<input type="hidden" value="0" id="ApplicationTrialTherapeuticUse_" name="data[Application][trial_therapeutic_use]">
                                <label class="checkbox">',
          'after' => 'Therapeutic use (Phase IV) </label></div>',
        ));

        ?>
      </div>
      <div id="tabs-9">
        <h5>9.0 DESIGN OF THE TRIAL</h5>
        <hr>
        <?php
        echo $this->Form->input('design_options', array('type' => 'hidden', 'value' => ''));
        echo $this->Form->error(
          'Application.design_options',
          'Please select yes because some the the options for design controlled
                          have been selected as yes.',
          array('wrap' => 'span', 'class' => 'controls required error', 'escape' => false)
        );
        $controlledError = $randomisedError = $openError = $singleBlindError = $doubleBlindError =
          $parallelError = $crossError = $controlledOtherError = $medicinalError = $placeboError =
          $medicinalOtherError = '';
        if ($this->Form->isFieldError('design_controlled')) $controlledError = 'error';
        if ($this->Form->isFieldError('design_controlled_randomised')) $randomisedError = 'error';
        if ($this->Form->isFieldError('design_controlled_open')) $openError = 'error';
        if ($this->Form->isFieldError('design_controlled_single_blind')) $singleBlindError = 'error';
        if ($this->Form->isFieldError('design_controlled_double_blind')) $doubleBlindError = 'error';
        if ($this->Form->isFieldError('design_controlled_parallel_group')) $parallelError = 'error';
        if ($this->Form->isFieldError('design_controlled_cross_over')) $crossError = 'error';
        if ($this->Form->isFieldError('design_controlled_other')) $controlledOtherError = 'error';
        if ($this->Form->isFieldError('design_controlled_other_medicinal')) $medicinalError = 'error';
        if ($this->Form->isFieldError('design_controlled_placebo')) $placeboError = 'error';
        if ($this->Form->isFieldError('design_controlled_medicinal_other')) $medicinalOtherError = 'error';
        echo $this->Form->input('design_controlled', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'error' => false,
          'class' => 'design_controlled',
          'before' => '<div class="control-group ' . $controlledError . '">
                <label class="control-label required">Controlled <span class="sterix">*</span></label> <div class="controls">
                <input type="hidden" value="" id="ApplicationDesignControlled_" name="data[Application][design_controlled]"> <label class="radio inline">',
          'after' => '</label>',
          'options' => array('Yes' => 'Yes'),
        ));
        echo $this->Form->input('design_controlled', array(
          'type' => 'radio',
          'label' => false,
          'legend' => false,
          'div' => false,
          'hiddenField' => false,
          'class' => 'design_controlled',
          'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
          'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
          'before' => '<label class="radio inline">',
          'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
          'options' => array('No' => 'No'),
        ));
        ?>
        <div class="ctr-groups">
          <p class="topper"><em class="text-success">If Yes, Specify</em></p>
          <?php
          echo $this->Form->input('design_controlled_randomised', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_randomised design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $randomisedError . '">
                      <label class="control-label required">Randomised </label>  <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledRandomised_" name="data[Application][design_controlled_randomised]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_randomised', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_randomised design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_randomised\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_open', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_open design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $openError . '">
                      <label class="control-label required">Open </label>  <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledOpen_" name="data[Application][design_controlled_open]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_open', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_open design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_open\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_single_blind', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_single_blind design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $singleBlindError . '">
                      <label class="control-label required">Single Blind </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledSingleBlind_" name="data[Application][design_controlled_single_blind]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_single_blind', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_single_blind design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_single_blind\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_double_blind', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_double_blind design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $doubleBlindError . '">
                      <label class="control-label required">Double Blind </label>  <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledDoubleBlind_" name="data[Application][design_controlled_double_blind]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_double_blind', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_double_blind design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_double_blind\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_parallel_group', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_parallel_group design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $parallelError . '">
                      <label class="control-label required">Parallel group </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationdesignControlledParallelGroup_" name="data[Application][design_controlled_parallel_group]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_parallel_group', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_parallel_group design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_parallel_group\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_cross_over', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_cross_over design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $crossError . '">
                      <label class="control-label required">Cross over </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledCrossOver_" name="data[Application][design_controlled_cross_over]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_cross_over', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_cross_over design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_cross_over\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_other', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_other design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $controlledOtherError . '">
                      <label class="control-label required">Other </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledOther_" name="data[Application][design_controlled_other]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_other', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_other design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_other, #ApplicationDesignControlledSpecify\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_specify', array(
            'label' => array('class' => 'control-label', 'text' => 'If yes to other, specify'),
            'placeholder' => ' ',
            'class' => 'input-xxlarge design_controlled design_controlled_f'
          ));
          echo $this->Form->input('design_controlled_comparator', array(
            'label' => array('class' => 'control-label', 'text' => 'If controlled, specify the comparator'),
            'placeholder' => ' ',
            'class' => 'input-xxlarge design_controlled design_controlled_f'
          ));
          echo $this->Form->input('design_controlled_other_medicinal', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_other_medicinal design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $medicinalError . '">
                      <label class="control-label required">Other medicinal product(s) </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledOtherMedicinal_" name="data[Application][design_controlled_other_medicinal]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_other_medicinal', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_other_medicinal design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_other_medicinal\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_placebo', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_placebo design_controlled design_controlled_',
            'before' => '<div class="control-group  ' . $placeboError . '">
                      <label class="control-label required">Placebo </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledOtherMedicinal_" name="data[Application][design_controlled_placebo]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_placebo', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_placebo design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_placebo\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_medicinal_other', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'error' => false,
            'class' => 'design_controlled_medicinal_other design_controlled design_controlled_',
            'before' => '<div class="control-group ' . $medicinalOtherError . '">
                      <label class="control-label required">Other </label> <div class="controls">
                      <input type="hidden" value="" id="ApplicationDesignControlledMedicinalOther_" name="data[Application][design_controlled_medicinal_other]"> <label class="radio inline">',
            'after' => '</label>',
            'options' => array('Yes' => 'Yes'),
          ));
          echo $this->Form->input('design_controlled_medicinal_other', array(
            'type' => 'radio',
            'label' => false,
            'legend' => false,
            'div' => false,
            'hiddenField' => false,
            'class' => 'design_controlled_medicinal_other design_controlled design_controlled_',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
            'before' => '<label class="radio inline">',
            'after' => '</label>
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                    onclick="$(\'.design_controlled_medicinal_other, #ApplicationDesignControlledMedicinalSpecify\').removeAttr(\'checked disabled\')">
                    <em class="accordion-toggle">clear!</em></a> </span>
                    </div> </div>',
            'options' => array('No' => 'No'),
          ));
          echo $this->Form->input('design_controlled_medicinal_specify', array(
            'label' => array('class' => 'control-label', 'text' => 'If yes to other, specify'),
            'placeholder' => ' ',
            'class' => 'input-xxlarge design_controlled design_controlled_f'
          ));
          ?>
        </div>
      </div>
      <div id="tabs-15">
        <?php echo $this->element('multi/study_budget'); ?>
      </div>
      <div id="tabs-10">
        <?php echo $this->element('multi/organizations'); ?>
      </div>
      <div id="tabs-11">
        <h5>12.0 OTHER DETAILS</h5>
        <hr>
        <?php
        echo $this->Form->input('other_details_explanation', array(
          'label' => array('class' => 'control-nolabel', 'text' => '<h5> 12.1 If the trial is to be conducted in
              Kenya and not in the host country of the applicant / sponsor, provide an explanation <span class="sterix">*</span></h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        echo $this->Form->input('estimated_duration', array(
          'label' => array(
            'class' => 'control-nolabel required',
            'text' => '12.2 Estimated duration of trial <span class="sterix">*</span>'
          ),
          'between' => '<div class="nocontrols">',
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('other_details_regulatory_notapproved', array(
          'label' => array('class' => 'control-nolabel required', 'text' => '<h5> 12.3 Name other Regulatory Authorities to
                which applications to do this trial have been submitted, but approval has not yet been granted. Include date(s)
                of application: <span class="sterix">*</span></h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        echo $this->Form->input('other_details_regulatory_approved', array(
          'label' => array('class' => 'control-nolabel required', 'text' => '<h5> 12.4 Name other Regulatory Authorities
                which have approved this trial, date(s) of approval and number of sites per country. <span class="sterix">*</span></h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        echo $this->Form->input('other_details_regulatory_rejected', array(
          'label' => array('class' => 'control-nolabel required', 'text' => '<h5> 12.5 if applicable, name other Regulatory
              Authorities or Ethics Committees which have rejected this trial and give reasons for rejection:</h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        echo $this->Form->input('other_details_regulatory_halted', array(
          'label' => array('class' => 'control-nolabel required', 'text' => '<h5> 12.6 If applicable, details of and reasons
                for this trial having been halted at any stage by other Regulatory Authorities:</h5>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge',
          'between' => '<div class="nocontrols">',
          'escape' => false,
        ));
        ?>
      </div>

      <div id="tabs-12">
        <?php echo $this->element('multi/checklist'); ?>
      </div>
      <div id="tabs-13">
        <h5>DECLARATION BY APPLICANT</h5>
        <hr>
        <p>We, the undersigned have submitted all requested and required documentation, and have disclosed all
          information which may influence the approval of this application. </p>

        <p>We, the undersigned, agree to ensure that if the above-said clinical trial is approved, it will be conducted
          according to the submitted protocol and Kenyan legal, ethical and regulatory requirements. </p>

        <?php
        echo $this->Form->input('declaration_applicant', array(
          'label' => array('class' => 'control-label required', 'text' => 'Applicant (local contact) <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('declaration_date1', array(
          'type' => 'text',
          'label' => array('class' => 'control-label required', 'text' => '<span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xlarge datepickers'
        ));
        echo $this->Form->input('declaration_principal_investigator', array(
          'label' => array('class' => 'control-nolabel required', 'text' => 'National Principal Investigator / National Co-ordinator / Other (state designation) <span class="sterix">*</span>'),
          'between' => '<div class="nocontrolls">',
          'placeholder' => ' ',
          'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('declaration_date2', array(
          'type' => 'text',
          'label' => array('class' => 'control-label required', 'text' => ' <span class="sterix">*</span>'),
          'placeholder' => ' ',
          'class' => 'input-xlarge datepickers'
        ));
        ?>
      </div>
      <div id="tabs-14">
        <?php
        echo $this->element('multi/attachments');
        echo $this->Form->input('notification', array(
          'label' => array(
            'class' => 'control-nolabel required',
            'text' => '<i class="icon-comment-alt"></i> Any other comment(s)'
          ),
          'between' => '<div class="nocontrols">',
          'class' => 'input-large',
        ));
        ?>
      </div>
    </div>

  </div>
  <div class="span2">
    <div data-spy="affix" class="my-sidebar">
      <div class="well">
        <?php
        echo $this->Form->hidden('submit_type', array(
          'value' => '',
          'id' => 'ApplicationSubmitType'));
        // echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(
        //   'name' => 'saveChanges',
        //   'formnovalidate' => 'formnovalidate',
        //   'class' => 'btn btn-success btn-block mapop',
        //   'id' => 'SadrSaveChanges', 'title' => 'Save & continue editing',
        //   'data-content' => 'Save changes to form without submitting it.
        //                                 The form will still be available for further editing.',
        //   'div' => false,
        // ));
        //     echo $this->Form->button('<i class="icon-save"></i>Save Changes', array(
        //       // 'name' => 'saveChanges',
        // 'onclick' => "this.form.submit_type.value='saveChanges';",
        //       'formnovalidate' => 'formnovalidate',
        //       'class' => 'btn btn-success btn-block mapop',
        //       'id' => 'SadrSaveChanges',
        //       'type' => 'submit', 
        //       'title' => 'Save & continue editing',
        //       'data-content' => 'Save changes to form without submitting it.',
        //       'escape' => false, // Allows the <i> icon to render
        //       'type' => 'submit', 
        //     ));

        echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(
          'type' => 'submit',
          'escape' => false,
          'name' => 'saveChanges',
          'class' => 'btn btn-primary',
          'id' => 'ApplicationSaveChanges'));


        ?>
        <hr>
        <?php
        echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
          'type' => 'submit',
          'escape' => false,
          'name' => 'submitReport',
          'class' => 'btn btn-info',
          'id' => 'ApplicationSubmitReport'));


        // echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
        //     'type' => 'submit',
        //     'escape' => false,
        //     'class' => 'btn btn-info',
        //     'id' => 'ApplicationSubmitReport',
        //     'onclick' => "
        //         if (confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.')) {
        //             document.getElementById('ApplicationSubmitType').value = 'submitReport';
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     "
        // ));

        // echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
        //   'name' => 'submitReport',
        //   'formnovalidate' => 'formnovalidate',
        //   'onclick' => "return confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.');",
        //   'class' => 'btn btn-info btn-block mapop',
        //   'id' => 'ApplicationSubmitReport', 'title' => 'Save and Submit Report',
        //   'data-content' => 'Save the report and submit it to the pharmacy and Poisons Board. You will also get a copy of this report.',
        //   'div' => false,
        // ));
        // echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
        //   'name' => 'submitReport',
        // 'formnovalidate' => 'formnovalidate',
        //   'onclick' => "return confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.');",
        //   'class' => 'btn btn-info btn-block mapop',
        //   'id' => 'ApplicationSubmitReport',
        //   'title' => 'Save and Submit Report',
        //   'data-content' => 'Save and submit the report to PPB.',
        //   'escape' => false,
        //   'div' => false,
        //   'type' => 'submit', 
        // ));

        //         echo $this->Form->submit('Submit', array(
        //     // 'name' => 'submitReport',
        //     // 'formnovalidate' => 'formnovalidate',
        //     // 'onclick' => "return confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.');",
        //         'onclick' => "if (confirm('Are you sure you wish to submit the form to PPB? You will not be able to edit it later.')) { this.form.submit_type.value='submitReport'; return true; } else { return false; }",
        //     'class' => 'btn btn-info btn-block mapop',
        //     'id' => 'ApplicationSubmitReport',
        //     'title' => 'Save and Submit Report',
        //     'data-content' => 'Save and submit the report to PPB.',
        //     'escape' => false,
        //     'div' => false,
        //       'type' => 'submit', 
        // ));


        ?>
        <hr>
        <?php
        // echo $this->Form->button('<i class="icon-remove-circle"></i> Cancel', array(
        //   'name' => 'cancelReport',
        //   'onclick' => "return confirm('Are you sure you wish to cancel the form? You can edit it later from the dashboard.');",
        //   'class' => 'btn btn-block mapop',
        //   'id' => 'ApplicationCancelReport', 'title' => 'Cancel form',
        //   'data-content' => 'Cancel form and go back to dashboard.',
        //   'div' => false,
        // ));
        echo $this->Form->button('<i class="icon-remove-circle"></i> Cancel', array(
          'name' => 'cancelReport',
          'type' => 'submit', // <-- optional, but safe
          'escape' => false, // <-- required to render the icon
          'onclick' => "return confirm('Are you sure you wish to cancel the form? You can edit it later from the dashboard.');",
          'class' => 'btn btn-block mapop',
          'id' => 'ApplicationCancelReport',
          'title' => 'Cancel form',
          'data-content' => 'Cancel form and go back to dashboard.',
          'div' => false,
        ));

        echo $this->Form->end();
        ?>
        <hr>
        <?php
        echo $this->Form->postLink(__('<i class="icon-trash"></i> Delete'), array('action' => 'delete', $this->Form->value('Application.id')), array('escape' => false, 'class' => 'btn btn-danger btn-block'), __('Are you sure you want to delete Application # %s? You will not be able to recover it later.', $this->Form->value('Application.id')));
        // echo $this->Form->button('Delete', array(
        //    'name' => 'deleteReport',
        //    'class' => 'btn btn-danger btn-block mapop',
        //    'id' => 'ApplicationDeleteReport', 'title'=>'Delete report',
        //    'data-content' => 'Delete report and go back to dashboard.',
        //    'div' => false,
        //  ));

        ?>
        <hr>
        <?php
        echo $this->Html->link(
          __('<i class="icon-download-alt"></i> Download PDF'),
          array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $this->Form->value('Application.id')),
          array('escape' => false, 'class' => 'btn', 'style' => 'margin-right: 10px;')
        );
        ?>
        <!--<button type="submit" class="btn btn-success btn-block">Save changes</button>
      <button type="submit" class="btn btn-info btn-block">Submit</button>
      <button type="button" class="btn btn-block">Cancel</button>-->
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $("#tabs").tabs({
      cookie: {
        expires: 1
      }
    });

    $('#ApplicationApplicantEditForm').submit(function() {
      //check if there is an empty file input
      rr = 0;
      $("#ApplicationApplicantEditForm").find("input[type=file]").each(function(index, field) {
        console.log($(field).val());
        if ($(field).val() == '') {
          $(field).addClass('error');
          // alert('You have an empty file input in Checklist or Notifications sections. Please add a file before saving.');              
          rr = rr + 1;
        }
      });
      if (rr > 0) {
        $('<div></div>').appendTo('body')
          .html('<div> <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>You have an empty file input in Checklist or Notifications sections. \
               Please add a file before saving. </p></div>')
          .dialog({
            modal: true,
            title: 'message',
            zIndex: 10000,
            autoOpen: true,
            width: 'auto',
            resizable: false,
            close: function(event, ui) {
              $(this).remove();
            }
          });
        return false;
      } else {
        return true;
      }

    });
    // $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    // $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );

    $(".datepickers").datepicker({
      minDate: "-100Y",
      maxDate: "-0D",
      dateFormat: 'dd-mm-yy',
      showButtonPanel: true,
      changeMonth: true,
      changeYear: true,
      buttonImageOnly: true,
      showAnim: 'show',
      showOn: 'both',
      buttonImage: '/img/calendar.gif'
    });
    $('#ApplicationStaffNumbers').ckeditor();
    $('#ApplicationPrimaryEndPoints').ckeditor();
    $('#ApplicationOtherDetailsRegulatoryNotapproved').ckeditor();
    $('#ApplicationOtherDetailsRegulatoryApproved').ckeditor();
    $('#ApplicationOtherDetailsRegulatoryRejected').ckeditor();
    $('#ApplicationOtherDetailsRegulatoryHalted').ckeditor();
    $('#ApplicationNotification').ckeditor();
    ///---- Enable or disable based on checkbox
    enable_ecct();
    $("#ApplicationEcctNotApplicable").click(enable_ecct);
    enable_scope();
    $("#ApplicationScopeOthers").click(enable_scope);
    enable_trial();
    $("#ApplicationTrialOther").click(enable_trial);

    toggle_single_location();
    $('#ApplicationSingleSiteMemberStateYes').on("click", toggle_single_location);
    $('#ApplicationSingleSiteMemberStateNo').on("click", toggle_single_location);
    $('#ApplicationLocationOfArea').change(function() {
      $('#ApplicationSingleSiteMemberStateYes').attr('checked', 'checked');
      // $('#ApplicationSingleSiteMemberStateYes').click();
      toggle_single_location();
    });

    toggle_multisite_location();
    $('#ApplicationMultipleSitesMemberStateYes').on("click", toggle_multisite_location);
    $('#ApplicationMultipleSitesMemberStateNo').on("click", toggle_multisite_location);
    $('#ApplicationNumberOfSites, #ApplicationMultiSiteList').change(function() {
      $('#ApplicationMultipleSitesMemberStateYes').attr('checked', 'checked');
      // $('#ApplicationMultipleSitesMemberStateYes').click();
      toggle_multisite_location();
    });

    toggle_multicountry_location();
    $('#ApplicationMultipleCountriesYes').on("click", toggle_multicountry_location);
    $('#ApplicationMultipleCountriesNo').on("click", toggle_multicountry_location);
    $('#ApplicationMultipleMemberStates, #ApplicationMultiCountryList').change(function() {
      $('#ApplicationMultipleCountriesYes').attr('checked', 'checked');
      // $('#ApplicationMultipleCountriesYes').click();
      toggle_multicountry_location();
    });

    $('.population_utero, .population_preterm_newborn, .population_newborn, .population_infant_and_toddler, \
      .population_infant_and_toddler, .population_children, .population_adolescent').change(function() {
      if ($(this).val() == 'Yes') {
        $('#ApplicationPopulationLessThan18YearsYes').attr('checked', 'checked');
      }
    });
    $('.population_less_than_18_years').change(function() {
      if ($(this).val() == 'No') {
        $('#ApplicationPopulationUteroNo, #ApplicationPopulationPretermNewbornNo, #ApplicationPopulationNewbornNo,  \
              #ApplicationPopulationInfantAndToddlerNo, #ApplicationPopulationChildrenNo, \
              #ApplicationPopulationAdolescentNo').attr('checked', 'checked');
      }
    });

    $('.population_adult, .population_elderly').change(function() {
      if ($(this).val() == 'Yes') {
        $('#ApplicationPopulationAbove18Yes').attr('checked', 'checked');
      }
    });
    $('.population_above_18').change(function() {
      if ($(this).val() == 'No') {
        $('#ApplicationPopulationAdultNo, #ApplicationPopulationElderlyNo').attr('checked', 'checked');
      }
    });

    $('.subjects_patients, .subjects_women_child_bearing, .subjects_women_using_contraception, .subjects_pregnant_women, \
      .subjects_nursing_women, .subjects_emergency_situation, .subjects_incapable_consent, .subjects_others').change(function() {
      if ($(this).val() == 'Yes') {
        $('#ApplicationSubjectsVulnerablePopulationsYes').attr('checked', 'checked');
      }
    });
    $('.subjects_vulnerable_populations').change(function() {
      if ($(this).val() == 'No') {
        $('#ApplicationSubjectsPatientsNo, #ApplicationSubjectsWomenChildBearingNo, #ApplicationSubjectsWomenUsingContraceptionNo, \
            #ApplicationSubjectsPregnantWomenNo, #ApplicationSubjectsNursingWomenNo, #ApplicationSubjectsEmergencySituationNo, \
            #ApplicationSubjectsIncapableConsentNo, #ApplicationSubjectsOthersNo').attr('checked', 'checked');
        toggle_subjects_incapable();
        toggle_subjects_others();
      }
    });

    toggle_design_controlled();
    $('#ApplicationDesignControlledYes, #ApplicationDesignControlledNo').on("click", toggle_design_controlled);
    $('.design_controlled_').change(function() {
      if ($(this).attr('id') == 'ApplicationDesignControlledSpecify') {
        $('#ApplicationDesignControlledOtherYes').attr('checked', 'checked');
      }
      if ($(this).attr('id') == 'ApplicationDesignControlledMedicinalSpecify') {
        $('#ApplicationDesignControlledMedicinalOtherYes').attr('checked', 'checked');
      }
      toggle_design_controlled();
    });

    toggle_subjects_incapable();
    $('#ApplicationSubjectsIncapableConsentYes, #ApplicationSubjectsIncapableConsentNo').on("click", toggle_subjects_incapable);
    $('#ApplicationSubjectsSpecify').change(function() {
      $('#ApplicationSubjectsIncapableConsentYes').attr('checked', 'checked');
      toggle_subjects_incapable();
    });

    toggle_subjects_others();
    $('#ApplicationSubjectsOthersYes, #ApplicationSubjectsOthersNo').on("click", toggle_subjects_others);
    $('#ApplicationSubjectsOthersSpecify').change(function() {
      $('#ApplicationSubjectsOthersYes').attr('checked', 'checked');
      toggle_subjects_others();
    });

  });

  ///---- Functions to be used. Probably to be moved to separate file??


  function enable_ecct() {
    if ($("#ApplicationEcctNotApplicable").is(':checked')) {
      $("#ApplicationEcctRefNumber").attr("disabled", true);
    } else {
      $("#ApplicationEcctRefNumber").removeAttr("disabled");
    }
  }

  function enable_scope() {
    if ($("#ApplicationScopeOthers").is(':checked')) {
      $("#ApplicationScopeOthersSpecify").removeAttr("readonly");
    } else {
      $("#ApplicationScopeOthersSpecify").attr("readonly", "readonly");
    }
  }

  function enable_trial() {
    if ($("#ApplicationTrialOther").is(':checked')) {
      $("#ApplicationTrialOtherSpecify").removeAttr("readonly");
    } else {
      $("#ApplicationTrialOtherSpecify").attr("readonly", "readonly");
    }
  }


  function toggle_single_location() {
    if ($("#ApplicationSingleSiteMemberStateYes").is(":checked")) {
      $('.single_site_member_state_f').removeAttr('readonly');
      // $('.multiple_sites_member_state_f, .multiple_countries_f ').attr('readonly', 'readonly');
      $('.multiple_sites_member_state_f').attr('readonly', 'readonly');
      $('.multiple_sites_member_state_s').attr('disabled', 'disabled');
      $('#addSiteDetail').attr('disabled', 'disabled');
      // $('#ApplicationStaffNumbers').ckeditorGet().setReadOnly();
      // $('.multiple_sites_member_state, .multiple_countries').removeAttr('checked');
      $('#ApplicationMultipleSitesMemberStateNo').prop('checked', true);
      // $('#ApplicationMultipleSitesMemberStateNo, #ApplicationMultipleCountriesNo').prop('checked', true);
      $('.removeSiteDetail').each(function() {
        $(this).click();
      });
      // $('.multiple_sites_member_state_f, .multiple_countries_f').val('');
      $('.multiple_sites_member_state_f').val('');
      $('.multiple_sites_member_state_s').val('');
    } else if ($("#ApplicationSingleSiteMemberStateNo").is(":checked")) {
      $('.single_site_member_state_f').attr('readonly', 'readonly').val('');
    }
  }

  function toggle_multisite_location() {
    if ($("#ApplicationMultipleSitesMemberStateYes").is(":checked")) {
      $('.multiple_sites_member_state_f').removeAttr('readonly');
      $('.multiple_sites_member_state_s').removeAttr('disabled');
      // $('.single_site_member_state_f, .multiple_countries_f').attr('readonly', 'readonly');
      $('.single_site_member_state_f').attr('readonly', 'readonly');
      $('#addSiteDetail').removeAttr('disabled');
      // $('.single_site_member_state, .multiple_countries').prop('checked', false);
      $('#ApplicationSingleSiteMemberStateNo').prop('checked', true);
      // $('#ApplicationSingleSiteMemberStateNo, #ApplicationMultipleCountriesNo').prop('checked', true);
      // $('.single_site_member_state_f, .multiple_countries_f').val('');
      $('.single_site_member_state_f').val('');
    } else if ($("#ApplicationMultipleSitesMemberStateNo").is(":checked")) {
      $('.multiple_sites_member_state_f').attr('readonly', 'readonly').val('');
      $('.multiple_sites_member_state_s').attr('disabled', 'disabled').val('');
      $('#addSiteDetail').attr('disabled', 'disabled');
      // $('#ApplicationStaffNumbers').ckeditorGet().setReadOnly();
      $('.removeSiteDetail').each(function() {
        $(this).click();
      });
    }
  }

  function toggle_multicountry_location() {
    if ($("#ApplicationMultipleCountriesYes").is(":checked")) {
      $('.multiple_countries_f').removeAttr('readonly');
      // $('.single_site_member_state_f, .multiple_sites_member_state_f').attr('readonly', 'readonly');
      // $('.multiple_sites_member_state_s').attr('disabled', 'disabled');
      // $('#addSiteDetail').attr('disabled', 'disabled');
      // $('#ApplicationSingleSiteMemberStateNo, #ApplicationMultipleSitesMemberStateNo').prop('checked', true);
      // $('.removeSiteDetail').each(function() {
      //   $(this).click();
      // });
      // $('.single_site_member_state_f, .multiple_sites_member_state_f').val('');
      // $('.multiple_sites_member_state_s').val('');
    } else if ($("#ApplicationMultipleCountriesNo").is(":checked")) {
      $('.multiple_countries_f').attr('readonly', 'readonly').val('');
    }
  }

  function toggle_design_controlled() {
    if ($("#ApplicationDesignControlledYes").is(":checked")) {
      $('.design_controlled_').removeAttr('disabled');
      $('.design_controlled_f').removeAttr('readonly');
    } else if ($("#ApplicationDesignControlledNo").is(":checked")) {
      $('.design_controlled_').removeAttr('checked').attr('disabled', 'disabled');
      $('.design_controlled_f').val('').attr('readonly', 'readonly');
      // $('.design_controlled_:input[type="text"]').val('');
      $('.design_controlled_f').val('');
    }

    if ($('#ApplicationDesignControlledOtherNo').is(':checked')) {
      $('#ApplicationDesignControlledSpecify').val('').attr('readonly', 'readonly');
    } else if ($('#ApplicationDesignControlledOtherYes').is(':checked')) {
      $('#ApplicationDesignControlledSpecify').removeAttr('readonly');
    }
    if ($('#ApplicationDesignControlledMedicinalOtherNo').is(':checked')) {
      $('#ApplicationDesignControlledMedicinalSpecify').val('').attr('readonly', 'readonly');
    } else if ($('#ApplicationDesignControlledMedicinalOtherYes').is(':checked')) {
      $('#ApplicationDesignControlledMedicinalSpecify').removeAttr('readonly');
    }
  }

  function toggle_subjects_incapable() {
    if ($("#ApplicationSubjectsIncapableConsentYes").is(":checked")) {
      $('#ApplicationSubjectsSpecify').removeAttr('readonly');
    } else if ($("#ApplicationSubjectsIncapableConsentNo").is(":checked")) {
      $('#ApplicationSubjectsSpecify').val('').attr('readonly', 'readonly');
    }
  }

  function toggle_subjects_others() {
    if ($("#ApplicationSubjectsOthersYes").is(":checked")) {
      $('#ApplicationSubjectsOthersSpecify').removeAttr('readonly');
    } else if ($("#ApplicationSubjectsOthersNo").is(":checked")) {
      $('#ApplicationSubjectsOthersSpecify').val('').attr('readonly', 'readonly');
    }
  }


  // CKEDITOR.replace( 'data[Application][study_title]' );
  CKEDITOR.replace('data[Application][study_title]');
  CKEDITOR.replace('data[Application][laymans_summary]');
  CKEDITOR.replace('data[Application][abstract_of_study]');
  CKEDITOR.replace('data[Application][study_objectives]');
  CKEDITOR.replace('data[Application][principal_inclusion_criteria]');
  CKEDITOR.replace('data[Application][principal_exclusion_criteria]');
  // CKEDITOR.replace( 'data[Application][staff_numbers]');
  CKEDITOR.replace('data[Application][other_details_explanation]');
</script> 
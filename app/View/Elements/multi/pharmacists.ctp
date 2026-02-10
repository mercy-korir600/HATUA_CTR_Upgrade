<?php
 	$this->Html->script('multi/pharmacists', array('inline' => false));
?>
<h5>2.4 PHARMACIST (<small>where necessary, Click button to add more -
<button type="button" class="btn-mini" id="addPharmacist">Add Pharmacist</button></small>) </h5>
<div class="ctr-groups">
	<div id="investigator_primary_contact">
	<?php
		echo $this->Form->input('Pharmacist.0.id');
		echo $this->Form->input('Pharmacist.0.reg_no', array(
          'label' => array('class' => 'control-label required', 'text' => 'Registration Number <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.given_name', array(
          'label' => array('class' => 'control-label required', 'text' => 'Name <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.valid_year', array(
          'label' => array('class' => 'control-label required', 'text' => 'Valid year <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.qualification', array(
          'label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.premise_name', array(
          'label' => array('class' => 'control-label required', 'text' => 'Premise'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.professional_address', array(
          'label' => array('class' => 'control-label required', 'text' => 'Physical address <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.telephone', array(
          'label' => array('class' => 'control-label required', 'text' => 'Telephone number'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.mobile', array(
          'label' => array('class' => 'control-label required', 'text' => 'Mobile number'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
        echo $this->Form->input('Pharmacist.0.email', array(
          'type' => 'email', 'label' => array('class' => 'control-label required', 'text' => 'Email address <span class="sterix">*</span>'),
          'placeholder' => ' ' , 'class' => 'input-xxlarge'
        ));
		echo $this->Html->tag('hr', '', array('id' => 'PharmacistHr0'));
	?>
	</div>
	<div id="pharmacist_contacts">
	<?php
		if (!empty($this->request->data['Pharmacist'])) {
			for ($i = 1; $i <= count($this->request->data['Pharmacist'])-1; $i++) {
			?>
			<div class="pharmacist-group">
			<?php
				echo $this->Form->input('Pharmacist.'.$i.'.id');
				echo $this->Form->input('Pharmacist.'.$i.'.reg_no', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Registration Number <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.given_name', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Name <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.valid_year', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Valid year <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.qualification', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.premise_name', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Premise'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.professional_address', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Physical address <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.telephone', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Telephone number'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.mobile', array(
	              'label' => array('class' => 'control-label required', 'text' => 'Mobile number'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
	            echo $this->Form->input('Pharmacist.'.$i.'.email', array(
	              'type' => 'email', 'label' => array('class' => 'control-label required', 'text' => 'Email address <span class="sterix">*</span>'),
	              'placeholder' => ' ' , 'class' => 'input-xxlarge'
	            ));
				echo $this->Html->tag('div', '<button id="PharmacistButton'.$i.'" class="btn btn-mini btn-danger removePharmacist" type="button">Remove Pharmacist</button>', array(
							'class' => 'controls', 'escape' => false));
				echo $this->Html->tag('hr', '', array('id' => 'PharmacistHr'.$i));
			?>
			</div>
			<?php
			}
		}
	?>
	</div>
</div>

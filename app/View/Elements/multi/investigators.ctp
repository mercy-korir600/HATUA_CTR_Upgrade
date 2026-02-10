<?php
  $this->Html->script('multi/investigators_v2', array('inline' => false));

  $investigatorContacts = array();
  if (!empty($this->request->data['InvestigatorContact']) && is_array($this->request->data['InvestigatorContact'])) {
    $investigatorContacts = $this->request->data['InvestigatorContact'];
  }

  $coPiContacts = array();
  $coIContacts = array();
  $principalContacts = array();
  $maxInvestigatorIndex = -1;

  foreach ($investigatorContacts as $index => $investigatorContact) {
    $contactIndex = (int) $index;
    if ($contactIndex > $maxInvestigatorIndex) {
      $maxInvestigatorIndex = $contactIndex;
    }

    $role = 'principal';
    if (!empty($investigatorContact['investigator_role'])) {
      $role = $investigatorContact['investigator_role'];
    }

    $investigatorEntry = array('index' => $contactIndex, 'contact' => $investigatorContact);

    if ($role === 'co_pi') {
      $coPiContacts[] = $investigatorEntry;
    } elseif ($role === 'co_i') {
      $coIContacts[] = $investigatorEntry;
    } else {
      $principalContacts[] = $investigatorEntry;
    }
  }

  if (empty($principalContacts)) {
    $maxInvestigatorIndex++;
    $principalContacts[] = array(
      'index' => $maxInvestigatorIndex,
      'contact' => array('investigator_role' => 'principal')
    );
  }

  $renderInvestigatorContactFields = function($index, $role, $showRemoveButton, $removeButtonLabel = 'Remove Contact') {
    echo $this->Form->input('InvestigatorContact.'.$index.'.id');
    echo $this->Form->input('InvestigatorContact.'.$index.'.investigator_role', array(
      'type' => 'hidden',
      'value' => $role
    ));

    echo $this->Form->input('InvestigatorContact.'.$index.'.given_name', array(
      'label' => array('class' => 'control-label required', 'text' => 'Given name <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.middle_name', array(
      'label' => array('class' => 'control-label', 'text' => 'Middle name, if applicable'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.family_name', array(
      'label' => array('class' => 'control-label required', 'text' => 'Family name <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.qualification', array(
      'label' => array('class' => 'control-label required', 'text' => 'Qualification <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.professional_address', array(
      'label' => array('class' => 'control-label required', 'text' => 'Professional address <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.telephone', array(
      'label' => array('class' => 'control-label required', 'text' => 'Telephone number <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));
    echo $this->Form->input('InvestigatorContact.'.$index.'.email', array(
      'type' => 'email',
      'label' => array('class' => 'control-label required', 'text' => 'email address <span class="sterix">*</span>'),
      'placeholder' => ' ' , 'class' => 'input-xxlarge'
    ));

    if ($showRemoveButton) {
      echo $this->Html->tag(
        'div',
        '<button id="InvestigatorContactButton'.$index.'" class="btn btn-mini btn-danger removePIContact" type="button">'.$removeButtonLabel.'</button>',
        array('class' => 'controls', 'escape' => false)
      );
    }

    echo $this->Html->tag('hr', '', array('id' => 'InvestigatorContactHr'.$index));
  };
?>

<h5>2.1 CO-PRINCIPAL INVESTIGATOR (CO-PI) <small>where necessary</small></h5>
<div class="controls">
  <button
    type="button"
    class="btn btn-mini js-add-investigator"
    id="addCoPiContact"
    data-role="co_pi"
    style="position: relative; z-index: 2;"
    onclick="return window.addInvestigatorContactByRole('co_pi');">Add Co-PI</button>
</div>
<div class="ctr-groups">
  <div id="investigator_co_pi_contact">
    <?php foreach ($coPiContacts as $coPiContact) { ?>
      <div class="contact-group co-pi-group">
        <?php $renderInvestigatorContactFields($coPiContact['index'], 'co_pi', true, 'Remove Co-PI'); ?>
      </div>
    <?php } ?>
  </div>
</div>

<h5>2.2 CO-INVESTIGATOR (CO-I) <small>where necessary</small></h5>
<div class="controls">
  <button
    type="button"
    class="btn btn-mini js-add-investigator"
    id="addCoIContact"
    data-role="co_i"
    style="position: relative; z-index: 2;"
    onclick="return window.addInvestigatorContactByRole('co_i');">Add Co-I</button>
</div>
<div class="ctr-groups">
  <div id="investigator_co_i_contact">
    <?php foreach ($coIContacts as $coIContact) { ?>
      <div class="contact-group co-i-group">
        <?php $renderInvestigatorContactFields($coIContact['index'], 'co_i', true, 'Remove Co-I'); ?>
      </div>
    <?php } ?>
  </div>
</div>

<h5>2.3 PRINCIPAL INVESTIGATOR <small>for multicentre trial; where necessary</small></h5>
<div class="controls">
  <button
    type="button"
    class="btn btn-mini js-add-investigator"
    id="addPIContact"
    data-role="principal"
    style="position: relative; z-index: 2;"
    onclick="return window.addInvestigatorContactByRole('principal');">Add Contact</button>
</div>
<div class="ctr-groups">
  <div id="investigator_primary_contact">
    <div class="contact-group principal-group principal-primary-group">
      <?php
        $primaryPrincipalContact = array_shift($principalContacts);
        $primaryPrincipalRole = (!empty($primaryPrincipalContact['contact']['investigator_role'])) ?
          $primaryPrincipalContact['contact']['investigator_role'] : 'principal';
        $renderInvestigatorContactFields($primaryPrincipalContact['index'], $primaryPrincipalRole, false);
      ?>
    </div>
  </div>
  <div id="investigator_contacts">
    <?php foreach ($principalContacts as $principalContact) { ?>
      <div class="contact-group principal-group">
        <?php
          $principalRole = (!empty($principalContact['contact']['investigator_role'])) ?
            $principalContact['contact']['investigator_role'] : 'principal';
          $renderInvestigatorContactFields($principalContact['index'], $principalRole, true, 'Remove Contact');
        ?>
      </div>
    <?php } ?>
  </div>
</div>

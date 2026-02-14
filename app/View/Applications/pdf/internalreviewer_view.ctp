<div class="row-fluid">
    <?php if($application['Application']['submitted'] == 1 ) { ?>
      <h4 class="text-success">
       <!-- <img border="0" alt="Pharmacy and Poisons Board" src="http://localhost/img/cake.power.png"> -->
       <?php
        echo $this->Html->image('cake.power.png', array('fullBase' => true, 'alt' => 'Pharmacy and Poisons Board', 'style' => 'border: 0;'));
        ?>
        Submitted Application :  (<?php echo $application['Application']['protocol_no'];?>) &mdash;
       <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } else { ?>
      <h4 class="text-success">
        UnSubmitted Application :  &mdash; <small> Created on:
        <?php
         echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
       ?>
      </small>
      </h4>
    <?php } ?>
  </div>

<?php echo $this->element('application/applicant_view');  ?>

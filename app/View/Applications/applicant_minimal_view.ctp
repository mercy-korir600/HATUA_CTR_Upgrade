<?php
  // $this->Html->script('jqprint.0.3', array('inline' => false));
  $this->assign('Applications', 'active');
 ?>
<section>
  <div class="form-actions">
    <div class="row-fluid">
      <div class="span8">
        <?php
          if($application['Application']['deactivated']) {
              echo '<p class="text-warning"> This Application has been deactivated. Please contact PPB for further details.</p>';
          } elseif ($application['Application']['approved'] == 1) {
             echo '<p class="text-error"> This Application has been rejected by PPB.</p>';
          }
        ?>

      </div>
      <div class="span4">
        <?php
            echo $this->Form->button('Print', array('type' => 'button', 'class'=>'btn btn-inverse btnPrint' ,
                        'onclick' => '$(\'#applicationPrintArea\').jqprint(); '
                        ));
        ?>
      </div>
    </div>
  </div>

  <div class="row-fluid">
  <div class="span12">
    <?php
    echo $this->Session->flash();
    ?>
    <div class="row-fluid">
	<div class="span12">
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
                <td style="width: 25%;">Abstract of Study:</td>
                <td style="width: 75%;"><strong><?php echo __($application['Application']['abstract_of_study'], true) ?></strong></td>
              </tr>
              <tr>
                <td style="width: 25%;">Study Title:</td>
                <td style="width: 75%;"><strong><?php echo __($application['Application']['study_title'], true) ?></strong></td>
              </tr>
            </table>
            <hr>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>
</section>


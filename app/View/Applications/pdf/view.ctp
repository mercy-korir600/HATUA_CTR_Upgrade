<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="STYLESHEET" href="css/pdf.css" type="text/css" />
   <style type="text/css">
      * {
      font-family: "DejaVu Sans";
      font-size: 10px;
    }
    </style>
<title></title>
</head>
<body>

  <div class="row-fluid">
    <?php if($application['Application']['submitted'] == 1 ) { ?>
      <h4 class="text-success">
       <!-- <img border="0" alt="Pharmacy and Poisons Board" src="http://localhost/img/cake.power.png"> -->
       <?php
        echo $this->Html->image('cake.power.png', array('fullBase' => true, 'alt' => 'Pharmacy and Poisons Board',
          'style' => 'border: 0; float: left; margin-right: 10px; margin-bottom: 10px;'));
        ?>
        Pharmacy and Poisons Board <br>
        <small>Online clinical trials</small><br>
        Submitted Application :  (<?php echo $application['Application']['protocol_no'];?>)
      </h4>
    <?php } else { ?>
      <h4 class="text-success">
        UnSubmitted Application
      </h4>
    <?php } ?>
  </div>

    <div class="row-fluid">
      <div class="span12">

         <div id="applicationPrintArea">
          <div class="vformbackp">
             <hr style="clear: left;">
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
                <td style="width: 75%;"><strong><?php echo $application['Application']['abstract_of_study']; ?></strong></td>
              </tr>
              <tr>
                <td style="width: 25%;">Study Title:</td>
                <td style="width: 75%;"><strong><?php echo $application['Application']['study_title']; ?></strong></td>
              </tr>
            </table>
            <hr>
          </div>
        </div>

      </div>
  </div>

</body>
</html>

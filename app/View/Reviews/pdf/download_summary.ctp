<?php
  echo $this->Html->css('bootstrap', null,array('fullBase' => true));
  echo $this->Html->css('ctr-fix', null,array('fullBase' => true));
?>
<table class="table table-bordered table-condensed">
    <tbody>
      <tr>
        <th class="my-well" style="width: 45%">Study Title</th>
        <td><?php echo $application['Application']['study_title'];?></td>
      </tr>
      <tr>
        <th class="my-well">Short Title</th>
        <td><?php echo $application['Application']['short_title'];?></td>
      </tr>
      <tr>
        <th class="my-well">ECCT Reference No</th>
        <td><?php echo $application['Application']['protocol_no'];?></td>
      </tr>
      <tr>
        <th class="my-well">Investigation medicinal product</th>
        <td><?php echo $application['Application']['study_drug'];?></td>
      </tr>
    </tbody>        
  </table>
  <?php
  echo $this->element('/application/rreview_summary');
?>
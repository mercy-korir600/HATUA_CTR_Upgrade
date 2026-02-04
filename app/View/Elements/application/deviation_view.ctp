
<h3 style="text-align: center;"> Protocol Deviation</h3>   
<hr class="soften" style="margin: 10px 0px;">

  <table class="table table-bordered table-condensed">
    <tbody>
      <tr>
        <th class="my-well" style="width: 45%">Study Title</th>
        <td><?php echo $deviation['study_title'];?></td>
      </tr>
      <tr>
        <th class="my-well">Reference No.</th>
        <td><?php echo $deviation['reference_no'];?></td>
      </tr>
      <tr>
        <th class="my-well">Type of Deviation.</th>
        <td><?php echo $deviation['deviation_type'];?> : <?php echo $deviation['deviation_type_dev'];?></td>
      </tr>
      <tr>
        <th class="my-well">PI Name</th>
        <td><?php echo $deviation['pi_name']; ?></td>
      </tr>        
      <tr>
        <th class="my-well">Date of deviation </th>
        <td><?php echo $deviation['deviation_date']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Study participant number </th>
        <td><?php echo $deviation['participant_number']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Name of treating physician </th>
        <td><?php echo $deviation['treating_physician']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Description of deviation </th>
        <td><?php echo $deviation['deviation_description']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Explanation why deviation occurred</th>
        <td><?php echo $deviation['deviation_explanation']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Measures taken to address the deviation </th>
        <td><?php echo $deviation['deviation_measures']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Measures taken to preclude further occurrence of the deviation  </th>
        <td><?php echo $deviation['deviation_preclude']; ?></td>
      </tr>
      <tr>
      <tr>
    <th class="my-well">
        Indicate whether the study sponsor has been notified
    </th>
    <td>
        <?php if (!empty($deviation['sponsor_notified'])): ?>
           Yes
            <?php if (!empty($deviation['sponsor_notification_date'])): ?>
                <br>
                <small>
                    Date notified:
                    <?= h(date('d M Y', strtotime($deviation['sponsor_notification_date']))) ?>
                </small>
            <?php endif; ?>
        <?php else: ?>
           No
        <?php endif; ?>
    </td>
</tr>
      </tr>
      <tr>
        <th class="my-well">Impact on the study </th>
        <td><?php echo $deviation['study_impact']; ?></td>
      </tr> 
    </tbody>        
  </table>

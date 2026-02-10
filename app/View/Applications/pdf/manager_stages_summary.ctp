<<<<<<< HEAD
 
   
   <div style="text-align: center;">
        <h3 style="text-align: center;">
            <img height="86" src="https://lh7-us.googleusercontent.com/eOWVvo3AHW74GguHveDaqdgy3YwNZqOqOO0QtscRoV4hJfcvt8Q6v4oLN3beVNvClvoD1ncu1RhB5D4iuAY-5R9h1aD2lEGqUorBVcQ0azfxsdvIz-WhbF3rA9-VQomtusnP2bNZTuYLFTC6vQ46nQ" style="background-color: transparent; color: rgb(0, 0, 0); font-family: &quot;Bookman Old Style&quot;, serif; font-size: 11pt; white-space-collapse: preserve; margin-left: 0px; margin-top: 0px;" width="116" />
        </h3>
        <p style="text-align: center;">
            <span style="font-family:bookman old style,serif;"><strong>MINISTRY</strong> <strong>OF</strong> <strong>HEALTH</strong></span>
        </p>
        <p style="text-align: center;">
            <span style="font-family:bookman old style,serif;"><strong>PHARMACY</strong> <strong>AND</strong> <strong>POISONS</strong> <strong>BOARD</strong></span>
        </p>

    </div>

    <div class="row-fluid">
        <div class="span12">
            <table>
                <thead>
                    <tr>
                        <th style="width:3%">#</th>
                        <th style="width: 27%"><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
                        <th style="width: 60%">Application Stages </th>
                        <th style="width: 10%">Date Submitted</th>
                        <th style="width: 10%">Date Approved</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $count = 0;
                    foreach ($applications as $application) {
                    ?>
                        <tr class="<?php
                                    $stages = $this->requestAction('applications/stages/' . $application['Application']['id']);
                                    if (Hash::check($stages, '{s}[color!=success]')) {
                                        $var = Hash::extract($stages, '{s}[color!=success].color');
                                        if (in_array('warning', $var)) echo 'warning';
                                        if (in_array('danger', $var)) echo 'error';
                                    }
                                    ?>">
                            <td><?php $count++;
                                echo $count; ?></td>
                            <td> <?php echo $application['Application']['protocol_no']; ?></td>
                            <td>
                                <!-- In table start -->
                                <table >
                                    <thead>
                                        <tr>
                                            <th>
                                                <p class="text-warning"><strong>Stage</strong></p>
                                            </th>
                                            <th>
                                                <p class="text-warning"><strong>Start Date</strong></p>
                                            </th>
                                            <th>
                                                <p class="text-warning"><strong>End Date</strong></p>
                                            </th>
                                            <th>
                                                <p class="text-warning"><strong>Days</strong></p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $cound = 0;
                                    ?>
                                    <tbody>
                                        <?php
                                        foreach ($stages as $sk => $stage) {
                                            $cound++;

                                            echo "<tr>";
                                            echo "<td>" . $cound . '. ' . strip_tags($stage['label']) . (($sk == 'AnnualApproval') ? ' (to expiry)' : '');
                                            echo "</td>";
                                            echo "<td>" . $stage['start_date'];
                                            echo "</td>";
                                            echo "<td>" . $stage['end_date'];
                                            echo "</td>";
                                            echo "<td>" . $stage['days'];
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </td>
                            <td> <?php echo $application['Application']['date_submitted']; ?></td>
                            <td> <?php echo $application['Application']['approval_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <!-- In table end -->
        </div>
    </div>
 
    <style>
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0px auto;
        }

        th, td {
            border: 1px solid gray;
            padding: 8px;
            text-align: left;
        }

        
    </style>
=======
<?php
echo $this->Html->image('cake.power.png', array('fullBase' => true, 'alt' => 'Pharmacy and Poisons Board', 'style' => 'border: 0;'));
?>

<div class="row-fluid">
    <div class="span12">
        <table class="table  table-bordered">
            <thead>
                <tr>
                    <th style="width:3%">#</th>
                    <th style="width: 27%"><?php echo $this->Paginator->sort('protocol_no', 'ECCT Reference No'); ?></th>
                    <th style="width: 70%">Application Stages </th>
                </tr>
            </thead>
            <tbody>

                <?php
                $count = 0;
                foreach ($applications as $application) {
                ?>
                    <tr class="<?php
                      $stages = $this->requestAction('applications/stages/'.$application['Application']['id']);
                      if(Hash::check($stages, '{s}[color!=success]')) {
                          $var = Hash::extract($stages, '{s}[color!=success].color');
                          if(in_array('warning', $var)) echo 'warning';
                          if(in_array('danger', $var)) echo 'error';
                      }
                   ?>">
                        <td><?php $count++;
                            echo $count; ?></td>
                        <td> <?php echo $application['Application']['protocol_no']; ?></td>
                        <td>
                            <!-- In table start -->
                            <table class="table table-condensed table-intable" style="margin: 1px; border:1px">
                                <thead>
                                    <tr>
                                        <th>
                                            <p class="text-warning"><strong>Stage</strong></p>
                                        </th>
                                        <th>
                                            <p class="text-warning"><strong>Start Date</strong></p>
                                        </th>
                                        <th>
                                            <p class="text-warning"><strong>End Date</strong></p>
                                        </th>
                                        <th>
                                            <p class="text-warning"><strong>Days</strong></p>
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $cound = 0; 
                                ?>
                                <tbody>
                                    <?php
                                    foreach ($stages as $sk => $stage) {
                                        $cound++;

                                        echo "<tr>";
                                        echo "<td>" . $cound . '. ' . strip_tags($stage['label']) . (($sk == 'AnnualApproval') ? ' (to expiry)' : '');
                                        echo "</td>";
                                        echo "<td>" . $stage['start_date'];
                                        echo "</td>";
                                        echo "<td>" . $stage['end_date'];
                                        echo "</td>";
                                        echo "<td>" . $stage['days'];
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php }?>
            </tbody>
        </table>
        <!-- In table end --> 
    </div>
</div>
>>>>>>> 123a14be9c332510d471ebbbbe868ade284b22e2

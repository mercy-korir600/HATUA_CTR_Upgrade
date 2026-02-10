<?php 
  echo $this->Session->flash();
?>

<h3 style="text-align: center;"> <?php echo ucfirst($rreview['assessment_type']); ?> Assessment Form</h3>   
<hr class="soften" style="margin: 10px 0px;">

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

  <table class="table table-bordered table-condensed">
    <thead><th></th><th width="35%"></th></thead>
    <tbody>
        <?php        
        for ($i = 0; $i <= count($rreview['ReviewAnswer'])-1; $i++) {
            echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.id', array('type' => 'hidden', 'value' => $rreview['ReviewAnswer'][$i]['id']));
            echo $this->Form->input('Review.'.$akey.'.ReviewAnswer.'.$i.'.rreview_id', array('type' => 'hidden', 'value' => $rreview['id']));
            if ($rreview['ReviewAnswer'][$i]['question_type'] == 'label') {
                 echo "<tr class='success'><td colspan='2'><strong>".$rreview['ReviewAnswer'][$i]['question']."</strong></td></tr>";                     
            }  elseif($rreview['ReviewAnswer'][$i]['question_type'] == 'yesno') {                    
                echo "<tr>";   
                    echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                    echo "<td>";
                    echo $rreview['ReviewAnswer'][$i]['answer'];
                    echo "</td>";
                  echo '</tr>';
            } elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'text') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $rreview['ReviewAnswer'][$i]['answer'];
                echo "</td></tr>"; 
            }  elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'workspace') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $rreview['ReviewAnswer'][$i]['workspace'];
                echo "</td></tr>"; 
            }  elseif ($rreview['ReviewAnswer'][$i]['question_type'] == 'comment') {
                echo "<tr>"; 
                  echo "<td>".$rreview['ReviewAnswer'][$i]['question']."</td>"; 
                  echo "<td>"; 
                  echo $rreview['ReviewAnswer'][$i]['comment'];
                echo "</td></tr>"; 
            } 
        }

        ?>     
     </tbody>        
    </table>


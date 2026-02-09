<form class="form-horizontal" style="margin: 0px">
  <table class="table table-condensed table-intable" style="margin: 0px">
    <tbody>
      <tr>
        <td colspan="2"><strong class="<?php
                                        if (count($application['Review']) < 3) {
                                          echo 'text-error';
                                        } elseif (count($application['Review']) == 3) {
                                          echo 'text-success';
                                        } elseif (count($application['Review']) > 3) {
                                          echo 'text-warning';
                                        }
                                        ?>">Assigned Reviewers: <?php
                                        $reviewCount = !empty($application['Review']) ? count($application['Review']) : 0;
                                        $internalCount = !empty($application['InternalReview']) ? count($application['InternalReview']) : 0;
                                        
                                        echo $reviewCount + $internalCount;
                                        
                                         ?></strong><br />
          <?php
           $names = [];

           foreach ($application['Review'] as $akey => $avalue) {
           
               $userId = $avalue['user_id'];
           
               // 1. Primary source: Users list
               if (!empty($users[$userId])) {
                   $names[] = $users[$userId];
                   continue;
               }
           
               // 2. Fallback: InternalReview model data
               if (!empty($application['InternalReview'])) {
                   foreach ($application['InternalReview'] as $internal) {
                       if ($internal['user_id'] == $userId && !empty($users[$internal['user_id']])) {
                           $names[] = $users[$internal['user_id']];
                           break;
                       }
                   }
               }
           
               // 3. Final fallback (optional)
               if (!in_array($userId, array_keys($users))) {
                   $names[] = 'Unknown Reviewer';
               }
           }
           
           // Output clean comma-separated list
           echo implode(', ', array_unique($names));
          ?>
        </td>
      </tr>
      <?php if ($application['Application']['deactivated']) { ?>
        <tr>
          <td><strong class="text-warning">Deactivated!!</strong></td>
          <td>
            <span class="text-warning">Please contact PPB.</span>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td><strong>Approval Status</strong></td>
        <td>
          <span>
            <?php
            if ($application['Application']['approved'] == 2)  echo "<i class='icon-ok'></i> Approved";
            elseif ($application['Application']['approved'] == 1)  echo "<i class='icon-remove'></i> Rejected!!";
            elseif ($application['Application']['approved'] == 0)  echo "<i class='icon-time'></i> in review";
            // else echo "<span class='text-error'><i class='icon-remove'></i></span>";
            ?>
          </span>
        </td>
      </tr>
      <tr>
        <td><strong>Trial Status</strong></td>
        <td>
          <span>
            <?php
            if (!empty($application['Application']['trial_status_id'])) echo $trial_statuses[$application['Application']['trial_status_id']];
            else echo "<em>(not set!)</em>";
            ?>
          </span>
        </td>
      </tr>
      <tr>
        <td>Submitted to ppb</td>
        <td><span><?php
                  if ($application['Application']['submitted']) {
                    echo "<span class='text-success'><i class='icon-ok'></i> <em>(submitted!)</em></span>";
                    if ($application['Application']['unsubmitted']) {
                      echo "<br>".$application['Application']['initial_date_submitted'];
                    }else{
                    echo "<br>".$application['Application']['date_submitted'];
                    }
                  } else {
                    echo "<span class='text-error'><i class='icon-remove'></i> <em>(not submitted!)</em></span>";
                  }
                  ?></span></td>
      </tr>
      <tr>
        <td style="width: 50%; padding-right: 0px;">Protocol Date</td>
        <td><?php echo $application['Application']['date_of_protocol']; ?></td>
      </tr>
      <tr>
        <td colspan="2">Created on &nbsp; : &nbsp; <?php echo date('d-m-Y h:i a', strtotime($application['Application']['created'])); ?></td>
      </tr>
    </tbody>
  </table>
</form>
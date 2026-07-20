<form class="form-horizontal" style="margin: 0px">
    <table class="table table-condensed table-intable" style="margin: 0px">
      <tbody>
          <tr>
            <td><strong>Approval Status</strong></td>
            <td>
              <span>
                <?php
                  if($application['Application']['approved'] == 2)  echo "<span class='text-success'><i class='icon-ok'></i> Approved</span>";
                  elseif($application['Application']['approved'] == 1)  echo "<span class='text-error'><i class='icon-remove'></i> Rejected</span>";
                  elseif($application['Application']['approved'] == 0)  echo "<span class='text-warning'><i class='icon-time'></i> In review</span>";
                ?>
              </span>
            </td>
          </tr>
          <tr>
            <td><strong>Study Status</strong></td>
            <td>
              <span>
                <?php echo isset($application['TrialStatus']['name']) ? $application['TrialStatus']['name'] : 'N/A'; ?>
              </span>
            </td>
          </tr>
          <tr>
            <td><strong>Submitted</strong></td>
            <td>
              <span>
                <?php
                  if($application['Application']['submitted']){
                    echo "<span class='text-success'><i class='icon-ok'></i> Yes</span>";
                  } else {
                    echo "<span class='text-error'><i class='icon-remove'></i> No</span>";
                  }
                ?>
              </span>
            </td>
          </tr>
      </tbody>
    </table>
</form>
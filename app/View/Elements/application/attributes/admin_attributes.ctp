<?php
$this->Html->script('bootstrap-editable', array('inline' => false));
$this->Html->css('bootstrap-editable', null, array('inline' => false));
?>
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

/** Collect all user_ids from BOTH arrays safely **/
$reviewUserIds = [];
$internalUserIds = [];

if (!empty($application['Review']) && is_array($application['Review'])) {
    foreach ($application['Review'] as $r) {
        if (!empty($r['user_id'])) $reviewUserIds[] = $r['user_id'];
    }
}

if (!empty($application['InternalReview']) && is_array($application['InternalReview'])) {
    foreach ($application['InternalReview'] as $ir) {
        if (!empty($ir['user_id'])) $internalUserIds[] = $ir['user_id'];
    }
}

$allUserIds = array_unique(array_merge($reviewUserIds, $internalUserIds));

/** Resolve to names **/
foreach ($allUserIds as $userId) {
    if (!empty($users[$userId])) {
        $names[] = $users[$userId];
    } else {
        // optional: only show unknown if you really want it
        // $names[] = 'Unknown Reviewer';
    }
}

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

      <?php if (!empty($application['Application']['approval_date']) && !empty($application['Application']['date_submitted'])) { ?>
        <tr>
          <td colspan="2">
            Duration from submit to approve: <br />
            <p class="xeditable" data-type="date" data-name="data[Application][date_submitted]" data-url="/admin/applications/view/<?php echo $application["Application"]["id"]; ?>" data-pk="<?php echo $application['Application']['id']; ?>" data-original-title="Update submission date">
              <?php echo date('d-m-Y', strtotime($application['Application']['date_submitted'])); ?>
            </p>
            <small> -to- </small>
            <p class="xeditable" data-type="date" data-name="data[Application][approval_date]" data-url="/admin/applications/view/<?php echo $application["Application"]["id"]; ?>" data-pk="<?php echo $application['Application']['id']; ?>" data-original-title="Update approval date">
              <?php echo $application['Application']['approval_date']; ?>
            </p>
            <br />
            <?php
            //echo date('d-m-Y H:i', strtotime($application['Application']['date_submitted']))." &#8212; " ;
            //echo date('d-m-Y H:i', strtotime($application['Application']['approval_date']));
            $start_date_ = new DateTime($application['Application']['date_submitted']);
            $end_date_ = new DateTime($application['Application']['approval_date']);
            $dd = date_diff($end_date_, $start_date_);
            // //pr($dd);
            // //To get hours use $dd->h, minutes - $dd->i, seconds - $dd->s.
            echo "Y = $dd->y, M = $dd->m, D = $dd->d: &sum; days = $dd->days";
            ?>
          </td>
        </tr>

        <?php if (!empty($application['Application']['admin_stopped_reason'])) { ?>
        <tr>
          <td colspan="2"> Reason for admin action: <br />
          <?php   echo $application['Application']['admin_stopped_reason']; ?>
          </td>
        </tr>
      <?php } } ?>

    </tbody>
  </table>
</form>


<script text="type/javascript">
  $.expander.defaults.slicePoint = 170;
  $.fn.editable.defaults.mode = 'popup';
  $(function() {
    //$('#data\\[Application\\]\\[approval_date\\] ,  #data\\[Application\\]\\[date_submitted\\]').editable({
    $('.xeditable').editable({
      //url: '/admin/applications/view/<?php echo $application["Application"]["id"]; ?>',
      ajaxOptions: {
        dataType: 'json' //assuming json response
      },
      params: function(params) {
        var data = {};
        data['_method'] = 'POST';
        data['data[Application][id]'] = params.pk;
        data[params.name] = params.value;
        return data;
      },
      format: 'dd-mm-yyyy',
      viewformat: 'dd-mm-yyyy',
      datepicker: {
        firstDay: 1
      }
    });

  });
</script>
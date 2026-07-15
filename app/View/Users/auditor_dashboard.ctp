<?php
    $this->assign('Dashboard', 'active');
    $this->Html->script('dashboard', array('inline' => false));
?>
<section>
  <div class="row-fluid">
    <div class="span12">
        <div class="marketing">
            <p class="lead">Centralized view of clinical trial protocols assigned to you for auditing.</p>
            <hr class="soften" style="margin: 10px 0px;">
        </div>
    </div>
  </div>

  <div class="row-fluid">
    <div class="span8">
      <div class="well" style="background-color: #ffffff;">
        <div class="navbar navbar-static" style="margin-bottom: 10px;">
          <div class="navbar-inner">
            <span class="brand"><i class="icon-file-text"></i> Assigned Protocols (<?php echo count($assignedProtocols); ?>)</span>
            <form class="navbar-search pull-right">
              <input type="text" id="auditorSearchInput" class="search-query span12" placeholder="Search protocol no., title or status...">
            </form>
          </div>
        </div>

        <table class="table table-bordered table-striped" id="auditorProtocolsTable">
          <thead>
            <tr>
              <th style="width: 20%;">Protocol Number</th>
              <th style="width: 45%;">Protocol Title</th>
              <th style="width: 15%;">Study Status</th>
              <th style="width: 15%;">Assigned Date</th>
              <th style="width: 5%;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($assignedProtocols)): ?>
              <?php foreach ($assignedProtocols as $protocol): ?>
                <tr>
                  <td>
                    <strong>
                      <?php echo $this->Html->link($protocol['Application']['protocol_no'], 
                        array('controller' => 'applications', 'action' => 'view', $protocol['Application']['id'], 'auditor' => true)); 
                      ?>
                    </strong>
                  </td>
                  <td>
                    <span class="text-info"><?php echo h($protocol['Application']['short_title']); ?></span>
                    <br>
                    <small class="muted"><?php echo h($protocol['Application']['study_drug']); ?></small>
                  </td>
                  <td>
                    <span class="label label-info">
                      <?php echo h($protocol['Application']['TrialStatus']['name']); ?>
                    </span>
                  </td>
                  <td>
                    <?php echo date('d-M-Y', strtotime($protocol['StudyAuditor']['created'])); ?>
                  </td>
                  <td>
                    <?php echo $this->Html->link('<i class="icon-eye-open"></i> View', 
                      array('controller' => 'applications', 'action' => 'view', $protocol['Application']['id'], 'auditor' => true),
                      array('escape' => false, 'class' => 'btn btn-mini btn-primary')); 
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center"><em>No protocols have been assigned to you yet.</em></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

 
    <div class="span4">
      <div class="thumbnail">
        <img alt="" src="/img/authenticated/preferences_desktop_notification.png">
        <div class="caption">
          <h4>Notifications <small>Updates requiring attention</small></h4>
          <dl>
            <?php echo $this->element('alerts/notifications', ['notifications' => $notifications]); ?>
          </dl>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    $("#auditorSearchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#auditorProtocolsTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
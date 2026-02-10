  
<div class="row-fluid">
  <div class="span12">
      <?php 
        echo $this->Session->flash();
      ?>
    <div class="page-header">
      <div class="styled_title"><h3>Participants Flow</h3></div>
    </div>

  <?php
    $participantFlows = array();
    if (!empty($application['ParticipantFlow']) && is_array($application['ParticipantFlow'])) {
      $participantFlows = $application['ParticipantFlow'];
      usort($participantFlows, function($left, $right) {
        $leftYear = isset($left['year']) ? (int) $left['year'] : 0;
        $rightYear = isset($right['year']) ? (int) $right['year'] : 0;
        if ($leftYear !== $rightYear) return ($leftYear < $rightYear) ? 1 : -1;

        $leftCreated = !empty($left['created']) ? strtotime($left['created']) : 0;
        $rightCreated = !empty($right['created']) ? strtotime($right['created']) : 0;
        if ($leftCreated !== $rightCreated) return ($leftCreated < $rightCreated) ? 1 : -1;

        $leftId = isset($left['id']) ? (int) $left['id'] : 0;
        $rightId = isset($right['id']) ? (int) $right['id'] : 0;
        if ($leftId === $rightId) return 0;

        return ($leftId < $rightId) ? 1 : -1;
      });
    }

    $participantFlowGroups = array();
    foreach ($participantFlows as $participantFlow) {
      $year = isset($participantFlow['year']) ? (string) $participantFlow['year'] : '';
      if (!isset($participantFlowGroups[$year])) {
        $participantFlowGroups[$year] = array(
          'latest' => $participantFlow,
          'history' => array(),
        );
      } else {
        $participantFlowGroups[$year]['history'][] = $participantFlow;
      }
    }
  ?>

  <?php if (empty($participantFlowGroups)) { ?>
    <div class="alert alert-info">No participant flow records submitted yet.</div>
  <?php } ?>

  <?php foreach ($participantFlowGroups as $year => $participantFlowGroup) {
    $participantFlow = $participantFlowGroup['latest'];
    $historyRows = $participantFlowGroup['history'];
    $historyCollapseId = 'participantFlowHistory' . (isset($participantFlow['id']) ? (int) $participantFlow['id'] : md5($year));
  ?>
  <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th colspan="4">
            <h4 class="text-warning">Study Recruitment Status (<?php echo h($year); ?>)</h4>
            <small class="muted">Latest record created on <?php echo h($participantFlow['created']); ?></small>
          </th>
        </tr>
      </thead>
      <tbody>
          <tr>
            <td class="table-label required"><p>Number of subjects Originally Authorized to enroll:</p></td>
            <td><?php echo h($participantFlow['original_subjects']); ?></td>
            <td class="table-label required"><p>Number Consented</p></td>
            <td><?php echo h($participantFlow['consented']); ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>Number Screened:</p></td>
            <td><?php echo h($participantFlow['screened']); ?></td>
            <td class="table-label required"><p>Number Enrolled</p></td>
            <td><?php echo h($participantFlow['enrolled']); ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>Number Lost (deaths,other) and reason for each:</p></td>
            <td><?php echo h($participantFlow['lost']); ?></td>
            <td class="table-label required"><p>Reasons</p></td>
            <td><?php echo h($participantFlow['lost_reason']); ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>Number Withdrawn by Investigator and reason for withdrawal(s) of each:</p></td>
            <td><?php echo h($participantFlow['withdrawn']); ?></td>
            <td class="table-label required"><p>Reasons</p></td>
            <td><?php echo h($participantFlow['withdrawal_reason']); ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>Number Withdrawn (drop outs - subject withdrew him/herself) and reason for withdrawal(s) for each:</p></td>
            <td><?php echo h($participantFlow['self_withdrawal']); ?></td>
            <td class="table-label required"><p>Reasons</p></td>
            <td><?php echo h($participantFlow['self_withdrawal_reasons']); ?></td>
          </tr>
          <tr>
            <td class="table-label required"><p>Number of Active Subjects:</p></td>
            <td><?php echo h($participantFlow['active_subjects']); ?></td>
            <td class="table-label required"><p>Number Completed all study activities:</p></td>
            <td><?php echo h($participantFlow['completed_number']); ?></td>
          </tr>
      </tbody>
  </table>

  <?php if (!empty($historyRows)) { ?>
    <div style="margin-top: -12px; margin-bottom: 15px;">
      <a class="btn btn-link btn-mini" data-toggle="collapse" href="#<?php echo h($historyCollapseId); ?>">
        View history for <?php echo h($year); ?> (<?php echo count($historyRows); ?> older record(s))
      </a>
      <div id="<?php echo h($historyCollapseId); ?>" class="collapse">
        <table class="table table-bordered table-condensed table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Original</th>
              <th>Consented</th>
              <th>Enrolled</th>
              <th>Active</th>
              <th>Completed</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historyRows as $historyIndex => $historyFlow) { ?>
              <?php $historyModalId = 'participantFlowHistoryModal' . (int) $historyFlow['id']; ?>
              <tr>
                <td><?php echo (int) $historyIndex + 1; ?></td>
                <td><?php echo h($historyFlow['original_subjects']); ?></td>
                <td><?php echo h($historyFlow['consented']); ?></td>
                <td><?php echo h($historyFlow['enrolled']); ?></td>
                <td><?php echo h($historyFlow['active_subjects']); ?></td>
                <td><?php echo h($historyFlow['completed_number']); ?></td>
                <td>
                  <button type="button" class="btn btn-mini btn-info" data-toggle="modal" data-target="#<?php echo h($historyModalId); ?>">
                    View
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <?php foreach ($historyRows as $historyFlow) { ?>
          <?php $historyModalId = 'participantFlowHistoryModal' . (int) $historyFlow['id']; ?>
          <div class="modal hide fade" id="<?php echo h($historyModalId); ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h3>Participant Flow History (<?php echo h($year); ?>)</h3>
            </div>
            <div class="modal-body">
              <table class="table table-bordered table-condensed">
                <thead>
                  <tr>
                    <th colspan="4">
                      <h4 class="text-warning">Study Recruitment Status (<?php echo h($year); ?>)</h4>
                      <small class="muted">Created on <?php echo h($historyFlow['created']); ?></small>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="table-label required"><p>Number of subjects Originally Authorized to enroll:</p></td>
                    <td><?php echo h($historyFlow['original_subjects']); ?></td>
                    <td class="table-label required"><p>Number Consented</p></td>
                    <td><?php echo h($historyFlow['consented']); ?></td>
                  </tr>
                  <tr>
                    <td class="table-label required"><p>Number Screened:</p></td>
                    <td><?php echo h($historyFlow['screened']); ?></td>
                    <td class="table-label required"><p>Number Enrolled</p></td>
                    <td><?php echo h($historyFlow['enrolled']); ?></td>
                  </tr>
                  <tr>
                    <td class="table-label required"><p>Number Lost (deaths,other) and reason for each:</p></td>
                    <td><?php echo h($historyFlow['lost']); ?></td>
                    <td class="table-label required"><p>Reasons</p></td>
                    <td><?php echo h($historyFlow['lost_reason']); ?></td>
                  </tr>
                  <tr>
                    <td class="table-label required"><p>Number Withdrawn by Investigator and reason for withdrawal(s) of each:</p></td>
                    <td><?php echo h($historyFlow['withdrawn']); ?></td>
                    <td class="table-label required"><p>Reasons</p></td>
                    <td><?php echo h($historyFlow['withdrawal_reason']); ?></td>
                  </tr>
                  <tr>
                    <td class="table-label required"><p>Number Withdrawn (drop outs - subject withdrew him/herself) and reason for withdrawal(s) for each:</p></td>
                    <td><?php echo h($historyFlow['self_withdrawal']); ?></td>
                    <td class="table-label required"><p>Reasons</p></td>
                    <td><?php echo h($historyFlow['self_withdrawal_reasons']); ?></td>
                  </tr>
                  <tr>
                    <td class="table-label required"><p>Number of Active Subjects:</p></td>
                    <td><?php echo h($historyFlow['active_subjects']); ?></td>
                    <td class="table-label required"><p>Number Completed all study activities:</p></td>
                    <td><?php echo h($historyFlow['completed_number']); ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
  <?php } ?>

  <?php if($redir == 'applicant') { ?>
  <h3>Form</h3>
  <div class="well">
    <div class="row-fluid">
      <div class="span12">
      <?php

        echo $this->Form->create('ParticipantFlow', array(
            'url' => array('controller' => 'participant_flows', 'action' => 'add'),
           'class' => 'form-horizontal',
           'inputDefaults' => array(
            'div' => array('class' => 'control-group'),
            'label' => array('class' => 'control-label'),
            'between' => '<div class="controls">',
            'after' => '</div>',
            'class' => '',
            'format' => array('before', 'label', 'between', 'input', 'after','error'),
            'error' => array('attributes' => array('class' => 'controls help-block')),
           ),
        ));
      ?>

      <div class="row-fluid">
        <div class="span6">
          <?php
            echo $this->Form->input('application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
            $years = [];
            foreach (range(1986, date('Y')) as $value) {
               $years[$value] = $value;
            }
            arsort($years);
            echo $this->Form->input('year', array('type' => 'select', 'options' => ($years),
                'label' => array('class' => 'control-label', 'text' => 'Year')
              ));
            echo '<p class="help-block muted">Submitting another record for an existing year will make the new entry the current record and keep earlier entries in history.</p>';
            echo $this->Form->input('original_subjects',
              array('label' => array('class' => 'control-label required', 'text' => 'Number of subjects originally authorized to enroll: <span class="sterix">*</span>'),));
            echo $this->Form->input('consented', array(
                'label' => array('class' => 'control-label required', 'text' => 'Number Consented <span class="sterix">*</span>'), ));
            echo $this->Form->input('screened', array('label' => array('class' => 'control-label', 'text' => 'Number Screened'),));
            echo $this->Form->input('enrolled', array(
              'div' => array('class' => 'control-group required'),
              'label' => array('class' => 'control-label required', 'text' => 'Number Enrolled <span class="sterix">*</span>')
            ));
            echo $this->Form->input('lost', array(
              'div' => array('class' => 'control-group required'),
              'label' => array('class' => 'control-label required', 'text' => 'Number Lost <span class="muted">(deaths/other)</span>and reason for each')
            ));
            echo $this->Form->input('lost_reason',
              array('type' => 'textarea', 'label' => array('class' => 'control-label required', 'text' => 'Reasons'),));

            ?>
        </div><!--/span-->
        <div class="span6">
          <?php
            echo $this->Form->input('withdrawn',
              array(
                'label' => array('class' => 'control-label required', 'text' => 'Number withdrawn by Investigator'),                
                'after'=>'<p class="help-block"> Number withdrawn by Investigator and reason for withdrawal(s) of each </p></div>',
                ));
            echo $this->Form->input('withdrawal_reason', array(
              'type' => 'textarea',
              'label' => array('class' => 'control-label', 'text' => 'Reason'),       
                'after'=>'<p class="help-block"> Reason for withdrawal(s) of each </p></div>',
            ));
            echo $this->Form->input('self_withdrawal',
              array(
                'label' => array('class' => 'control-label required', 'text' => 'Number withdrawn by subjects'),                
                'after'=>'<p class="help-block"> Number withdrawn (drop outs - subject withdrew him/herself) and reason for withdrawal(s) of withdrawal(s) for each </p></div>',
                ));
            echo $this->Form->input('self_withdrawal_reasons', array(
              'type' => 'textarea',
              'label' => array('class' => 'control-label', 'text' => 'Reason'),       
                'after'=>'<p class="help-block"> Reason for subjects withdrawing </p></div>',
            ));
            echo $this->Form->input('active_subjects', array(
              'label' => array('class' => 'control-label', 'text' => 'Number of Active Subjects'),
            ));
            echo $this->Form->input('completed_number', array('label' => array('class' => 'control-label', 'text' => 'Number Completed all study activities'),));
            ?>
        </div><!--/span-->
      </div><!--/row-->
       <hr>

      <?php
        echo $this->Form->end(array(
          'label' => 'Submit',
          'value' => 'Save',
          'class' => 'btn btn-primary',
          'id' => 'ParticipantsSaveChanges',
          'div' => array(
            'class' => 'form-actions',
          )
        ));
      ?>
     </div>  
    </div>
  </div>   <!-- ctr-groups -->
  <hr>
  <?php } ?>



  </div><!--/span-->
</div><!--/row-->

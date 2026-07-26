<div class="row-fluid">
  <div class="span12">
    <div class="page-header" style="margin-top: 0;">
      <h3 class="text-info"><i class="icon-file-text"></i> Clinical Trial Audit Findings & Official Report</h3>
    </div>

    <?php 
    $isSubmitted = !empty($audit_report['AuditReport']['submitted']) && $audit_report['AuditReport']['submitted'] == 1;
    $existing_checklists = array();
    if (!empty($audit_report['AuditChecklist'])) {
        foreach ($audit_report['AuditChecklist'] as $chk) {
            $existing_checklists[$chk['section_name']] = $chk;
        }
    }

    $checklist_sections = array(
        '1. Informed Consent & Ethical Compliance' => 'Verification that informed consent process, documentation, and ethical board approvals strictly conform to protocol requirements.',
        '2. Investigator Site File (ISF) & Documentation' => 'Review of essential protocol documents, delegations of authority logs, qualifications, and regulatory approvals.',
        '3. Investigational Product (IP) Management' => 'Inspection of IP storage condition logs, temperature records, dispensing logs, accountability, and reconciliation.',
        '4. Safety & SAE/SUSAR Reporting' => 'Audit of serious adverse event notifications, timeline compliance, and safety notifications to the oversight authority.',
        '5. Protocol Adherence & Data Integrity' => 'Source Data Verification (SDV) to confirm trial data accuracy, protocol amendment compliance, and case report forms.'
    );
    ?>

    <?php if (!$isSubmitted): ?>
      <?php 
      echo $this->Form->create('AuditReport', array(
          'url' => array('controller' => 'audit_reports', 'action' => 'edit', $application['Application']['id'], 'auditor' => true),
          'class' => 'form-horizontal',
          'id' => 'auditorReportForm'
      ));

      if (!empty($audit_report['AuditReport']['id'])) {
          echo $this->Form->input('AuditReport.id', array('type' => 'hidden', 'value' => $audit_report['AuditReport']['id']));
      }
      echo $this->Form->input('AuditReport.application_id', array('type' => 'hidden', 'value' => $application['Application']['id']));
      ?>

      <h4 class="text-primary"><i class="icon-tasks"></i> 1. Structured Audit Checklist & Findings</h4>
      <p class="muted">Complete the structured checklist below. Record compliance observations, comments, and recommendations for each audited domain.</p>
      
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="25%">Audit Domain / Section</th>
            <th width="20%">Compliance Status</th>
            <th width="25%">Compliance Observation</th>
            <th width="30%">Comments & Recommendations</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 0;
          foreach ($checklist_sections as $section_title => $section_desc): 
              $chkData = isset($existing_checklists[$section_title]) ? $existing_checklists[$section_title] : array();
          ?>
            <tr>
              <td>
                <strong><?php echo h($section_title); ?></strong>
                <br><small class="muted"><?php echo h($section_desc); ?></small>
                <?php 
                if (!empty($chkData['id'])) {
                    echo $this->Form->input("AuditChecklist.$i.id", array('type' => 'hidden', 'value' => $chkData['id']));
                }
                echo $this->Form->input("AuditChecklist.$i.section_name", array('type' => 'hidden', 'value' => $section_title));
                ?>
              </td>
              <td>
                <?php 
                $selected_status = isset($chkData['compliance_status']) ? $chkData['compliance_status'] : '';
                echo $this->Form->input("AuditChecklist.$i.compliance_status", array(
                    'type' => 'select',
                    'empty' => '-- Status --',
                    'options' => array(
                        'Compliant' => 'Compliant',
                        'Minor Non-Compliance' => 'Minor Non-Compliance',
                        'Major Non-Compliance' => 'Major Non-Compliance',
                        'Critical Non-Compliance' => 'Critical Non-Compliance',
                        'Not Applicable' => 'Not Applicable'
                    ),
                    'default' => $selected_status,
                    'label' => false,
                    'class' => 'span12'
                ));
                ?>
              </td>
              <td>
                <?php 
                $obs_val = isset($chkData['observation']) ? $chkData['observation'] : '';
                echo $this->Form->textarea("AuditChecklist.$i.observation", array(
                    'rows' => 3, 
                    'class' => 'span12', 
                    'placeholder' => 'Record compliance observations...',
                    'value' => $obs_val
                )); 
                ?>
              </td>
              <td>
                <?php 
                $cmt_val = isset($chkData['comments']) ? $chkData['comments'] : '';
                $rec_val = isset($chkData['recommendation']) ? $chkData['recommendation'] : '';
                echo $this->Form->textarea("AuditChecklist.$i.comments", array(
                    'rows' => 2, 
                    'class' => 'span12', 
                    'placeholder' => 'Capture comments...',
                    'value' => $cmt_val,
                    'style' => 'margin-bottom: 5px;'
                ));
                echo $this->Form->textarea("AuditChecklist.$i.recommendation", array(
                    'rows' => 2, 
                    'class' => 'span12', 
                    'placeholder' => 'Capture recommendations...',
                    'value' => $rec_val
                ));
                ?>
              </td>
            </tr>
          <?php 
          $i++;
          endforeach; 
          ?>
        </tbody>
      </table>

      <h4 class="text-primary" style="margin-top: 30px;"><i class="icon-file-alt"></i> 2. Official Audit Report Submission</h4>
      <div class="well">
        <div class="control-group">
          <label class="control-label" style="font-weight: bold;">Overall Compliance Observations:</label>
          <div class="controls">
            <?php 
            $overall_obs = isset($audit_report['AuditReport']['compliance_observations']) ? $audit_report['AuditReport']['compliance_observations'] : '';
            echo $this->Form->textarea('AuditReport.compliance_observations', array(
                'class' => 'span11', 
                'rows' => 4,
                'placeholder' => 'Synthesize summary compliance observations across all audited areas...',
                'value' => $overall_obs
            )); 
            ?>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" style="font-weight: bold;">Overall Comments:</label>
          <div class="controls">
            <?php 
            $overall_comments = isset($audit_report['AuditReport']['overall_comments']) ? $audit_report['AuditReport']['overall_comments'] : '';
            echo $this->Form->textarea('AuditReport.overall_comments', array(
                'class' => 'span11', 
                'rows' => 3,
                'placeholder' => 'General audit comments...',
                'value' => $overall_comments
            )); 
            ?>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" style="font-weight: bold;">Audit Recommendations:</label>
          <div class="controls">
            <?php 
            $overall_recs = isset($audit_report['AuditReport']['recommendations']) ? $audit_report['AuditReport']['recommendations'] : '';
            echo $this->Form->textarea('AuditReport.recommendations', array(
                'class' => 'span11', 
                'rows' => 3,
                'placeholder' => 'Key recommendations for principal investigator / sponsor / regulator...',
                'value' => $overall_recs
            )); 
            ?>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label required" style="font-weight: bold;">Audit Outcome <span class="sterix">*</span>:</label>
          <div class="controls">
            <?php 
            $selected_outcome = isset($audit_report['AuditReport']['outcome']) ? $audit_report['AuditReport']['outcome'] : '';
            echo $this->Form->input('AuditReport.outcome', array(
                'type' => 'select',
                'label' => false,
                'empty' => '-- Select Audit Outcome --',
                'class' => 'span11',
                'options' => array(
                    'Compliant' => 'Compliant',
                    'Compliant with Conditions (CAPA Required)' => 'Compliant with Conditions (CAPA Required)',
                    'Non-Compliant (Suspension/Revocation Recommended)' => 'Non-Compliant (Suspension/Revocation Recommended)'
                ),
                'default' => $selected_outcome
            )); 
            ?>
          </div>
        </div>

        <div class="form-actions" style="padding-left: 20px;">
          <button type="submit" name="save_progress" class="btn btn-success btn-large">
            <i class="icon-save"></i> Save Progress
          </button>
          &nbsp;
          <button type="submit" name="submit_report" class="btn btn-primary btn-large" onclick="return confirm('Are you sure you wish to submit this Official Audit Report? Once submitted, it will be finalized.');">
            <i class="icon-check"></i> Submit Official Audit Report
          </button>
        </div>
      </div>

      <?php echo $this->Form->end(); ?>

    <?php else: ?>

      <div class="alert alert-success">
        <h4><i class="icon-lock"></i> Official Audit Report Submitted</h4>
        <p>This audit report was officially submitted on <strong><?php echo date('d-M-Y H:i', strtotime($audit_report['AuditReport']['modified'])); ?></strong>.</p>
      </div>

      <div class="well">
        <h4 class="text-info">Audit Summary</h4>
        <table class="table table-bordered">
          <tr>
            <th width="25%">Audit Outcome:</th>
            <td>
              <?php 
              $outcome = $audit_report['AuditReport']['outcome'];
              $badge_class = ($outcome === 'Compliant') ? 'badge-success' : (($outcome === 'Compliant with Conditions (CAPA Required)') ? 'badge-warning' : 'badge-important');
              ?>
              <span class="badge <?php echo $badge_class; ?>" style="font-size: 14px; padding: 6px 12px;"><?php echo h($outcome); ?></span>
            </td>
          </tr>
          <tr>
            <th>Compliance Observations:</th>
            <td><?php echo nl2br(h($audit_report['AuditReport']['compliance_observations'])); ?></td>
          </tr>
          <tr>
            <th>Overall Comments:</th>
            <td><?php echo nl2br(h($audit_report['AuditReport']['overall_comments'])); ?></td>
          </tr>
          <tr>
            <th>Recommendations:</th>
            <td><?php echo nl2br(h($audit_report['AuditReport']['recommendations'])); ?></td>
          </tr>
        </table>

        <h4 class="text-info" style="margin-top: 20px;">Structured Checklist Results</h4>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Audit Domain</th>
              <th>Compliance Status</th>
              <th>Compliance Observation</th>
              <th>Comments & Recommendations</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($audit_report['AuditChecklist'] as $chk): ?>
              <tr>
                <td><strong><?php echo h($chk['section_name']); ?></strong></td>
                <td><span class="label label-info"><?php echo h($chk['compliance_status']); ?></span></td>
                <td><?php echo nl2br(h($chk['observation'])); ?></td>
                <td>
                  <?php if (!empty($chk['comments'])): ?>
                    <p><strong>Comments:</strong> <?php echo nl2br(h($chk['comments'])); ?></p>
                  <?php endif; ?>
                  <?php if (!empty($chk['recommendation'])): ?>
                    <p><strong>Recommendation:</strong> <?php echo nl2br(h($chk['recommendation'])); ?></p>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

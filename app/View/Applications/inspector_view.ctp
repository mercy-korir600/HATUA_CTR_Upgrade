<?php
$this->extend('/Elements/application/applicant_view');
?>

<?php $this->start('amendment-lead'); ?>
<?php
$this->assign('Applications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));
?>
<?php
echo $this->Session->flash();
?>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Application</a></li>
    <?php
    echo '<li><a href="#tab6" data-toggle="tab">Site Inspections (' . count($application['SiteInspection']) . ')</a></li>';
    echo '<li><a href="#tab7" data-toggle="tab">SAE/SUSAR (' . count($application['Sae']) . ')</a></li>';
    echo '<li><a href="#tab13" data-toggle="tab">Protocol Deviations (' . count($application['Deviation']) . ')</a></li>';
    ?>
  </ul>
  <div class="tab-content my-tab-content">
    <div class="tab-pane active" id="tab1">
      <!-- content for tab1 comes here -->

      <div class="row-fluid">
        <?php if ($application['Application']['submitted'] == 1) { ?>
          <h4 class="text-success">
            Submitted Application : (<?php echo $application['Application']['protocol_no']; ?>) &mdash;
            <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } else { ?>
          <h4 class="text-success">
            UnSubmitted Application : &mdash; <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } ?>
      </div>
      <?php $this->end(); ?>


      <?php $this->start('form-header'); ?>
      <div class="span10">
        <?php
        echo $this->Form->create('Application', array(
          'type' => 'file',
          'class' => 'form-horizontal',
          'inputDefaults' => array(
            'div' => array('class' => 'control-group'),
            'label' => array('class' => 'control-label'),
            'between' => '<div class="controls">',
            'after' => '</div>',
            'class' => '',
            'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
            'error' => array('attributes' => array('class' => 'controls help-block')),
          ),
        ));
        echo $this->Form->input('id');
        ?>
        <?php $this->end(); ?>

        <?php
        $this->start('form-actions');
        ?>

        <?php
        $this->end();
        ?>

        <?php $this->start('tabs'); ?>
        <ul>
          <li><a href="#tabs-1">1. Abstract</a></li>
          <li><a href="#tabs-2">2. Investigator</a></li>
          <li><a href="#tabs-3">3. Sponsor</a></li>
          <li><a href="#tabs-4">4. Participants</a></li>
          <li><a href="#tabs-5">5. Sites</a></li>
          <li><a href="#tabs-6">6. Placebo</a></li>
          <li><a href="#tabs-7">7. Criteria</a></li>
          <li><a href="#tabs-8">8. Scope</a></li>
          <li><a href="#tabs-9">9. Design</a></li>
          <li><a href="#tabs-15">10. Study Budget</a></li>
          <li><a href="#tabs-10">11. Organizations</a></li>
          <li><a href="#tabs-11">12. Other details</a></li>
          <li><a href="#tabs-12">13. Checklist </a></li>
          <li><a href="#tabs-13">14. Declaration</a></li>
          <li><a href="#tabs-14">15. Notifications</a></li>
        </ul>
        <?php $this->end(); ?>


        <?php $this->start('view-rightbar'); ?>
      </div>
      <div class="span2">
        <?php
        if ($application['Application']['submitted'] == 1) {
        ?>
          <div class="well">
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download PDF'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn')
            );
            echo "<hr>";
            echo $this->Html->link(
              __('<i class="icon-search"></i> Site Inspection'),
              array('controller' => 'site_inspections', 'action' => 'add', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn btn-info')
            );
            ?>
          </div>
        <?php
        }
        ?>
      </div>
      <?php $this->end();  ?>

      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrab tab1 -->
    <div class="tab-pane" id="tab6">
      <div class="row-fluid">
        <div class="span12">

          <?php
          echo $this->element('/application/inspection_edit');
          ?>

        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab13">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/deviation'); ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab7">
      <div class="row-fluid">
        <div class="span12">

          <table class="table  table-bordered table-striped">
            <thead>
              <tr>
                <th>Id</th>
                <th>Reference No.</th>
                <th>Report Type</th>
                <th>Patient Initials</th>
                <th>Created</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($application['Sae'] as $sae) : ?>
                <tr class="">
                  <td><?php echo h($sae['id']); ?>&nbsp;</td>
                  <td><?php echo h($sae['reference_no']); ?>&nbsp;</td>
                  <td><?php echo h($sae['report_type']);
                      if ($sae['report_type'] == 'Followup') {
                        echo "<br> Initial: ";
                        echo $this->Html->link(
                          '<label class="label label-info">' . substr($sae['reference_no'], 0, strpos($sae['reference_no'], '-')) . '</label>',
                          array('controller' => 'saes', 'action' => 'view', $sae['sae_id']),
                          array('escape' => false)
                        );
                      }
                      ?>&nbsp;
                  </td>
                  <td><?php echo h($sae['patient_initials']); ?>&nbsp;</td>
                  <td><?php echo h($sae['created']); ?>&nbsp;</td>
                  <td class="actions">
                    <?php if ($sae['approved'] > 0) echo $this->Html->link(
                      __('<label class="label label-info">View</label>'),
                      array('controller' => 'saes', 'action' => 'view', $sae['id']),
                      array('target' => '_blank', 'escape' => false)
                    ); ?>
                    <?php if ($redir === 'applicant' && $sae['approved'] < 1) echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('controller' => 'saes', 'action' => 'edit', $sae['id']), array('escape' => false)); ?>
                    <?php
                    if ($sae['approved'] < 1) {
                      echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'saes', 'action' => 'delete', $sae['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['id']));
                    }
                    if ($redir === 'applicant' && $sae['approved'] > 0) echo $this->Form->postLink('<i class="icon-facebook"></i> Follow Up', array('controller' => 'saes', 'action' => 'followup', $sae['id']), array('class' => 'btn btn-mini btn-warning', 'escape' => false), __('Create followup for %s?', $sae['reference_no']));
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>

  </div>
</div>

<script text="type/javascript">
  $(function() {
    if ($.unblockUI) {
      $(document).ajaxStop($.unblockUI);
    }
    if ($.fn.expander) {
      $(".morecontent").expander();
    }
  });
</script>
<?php $this->end(); ?>

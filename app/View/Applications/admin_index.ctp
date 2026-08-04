<?php
  $this->extend('/Elements/application/application_index');
?>

<?php $this->start('header'); ?>
    <?php
      $this->assign('Reports', 'active');
    ?>
    <div class="marketing">
      <div class="row-fluid">
            <div class="span12">
              <h3>Clinical Trial Applications:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view applications</small></h3>
              <hr class="soften" style="margin: 10px 0px;">
            </div>
        </div>
    </div>
<?php $this->end(); ?>

<?php
    $this->assign('color-codes', 'true');
    $this->assign('is-admin', 'true');
?>

<?php
    $this->assign('attributes', 'application/attributes/admin_attributes');
 ?>

<?php $this->start('admin-sidebar'); ?>
    <?php
          $all = 'active';
          $unsubmitted = $submitted = $approved = $x_approved = $waiting_approval = $deactivated = $deleted = '';
          if (isset($this->request->params['named']['approved']) && $this->request->params['named']['approved'] == '2') {
              $approved = 'active'; $all = '';
          }
          if (isset($this->request->params['named']['approved']) && $this->request->params['named']['approved'] == '1') {
              $x_approved = 'active'; $all = '';
          }
          if (isset($this->request->params['named']['approved']) && $this->request->params['named']['approved'] == '0') {
              $waiting_approval = 'active'; $all = '';
          }

          if (isset($this->request->params['named']['submitted']) && !isset($this->request->params['named']['approved'])) {
            if ($this->request->params['named']['submitted'] == '0') {
                 $unsubmitted = 'active';
            } elseif ($this->request->params['named']['submitted'] == '1') {
                 $submitted = 'active';
            }
            $all = '';
          }

          if (isset($this->request->params['named']['deactivated']) && $this->request->params['named']['deactivated'] == '1') {
              $deactivated = 'active'; $all = '';
          }
          if (isset($this->request->params['named']['deleted']) && $this->request->params['named']['deleted'] == '1') {
              $deleted = 'active'; $all = '';
          }
    ?>
      <li class="nav-header"><small><i class="icon-glass"></i> Status</small></li>
      <li class="<?php echo $all; ?>">
        <?php echo $this->Html->link('<i class="icon-table"></i> All Applications',  array('action' => 'index'), array('escape' => false));?>
      </li>
      <li class="<?php echo $approved; ?>">
        <?php     echo $this->Html->link('<i class="icon-ok"></i> Approved',
                    array('action' => 'index', 'submitted'=>'1', 'approved'=>2), array('escape' => false, 'class' =>  'text-success'));   ?>
      </li>
      <li class="<?php echo $waiting_approval; ?>">
        <?php     echo $this->Html->link('<i class="icon-shopping-cart"></i> Waiting Approval',
                    array('action' => 'index', 'submitted'=>'1', 'approved'=>0), array('escape' => false, 'class' =>  'text-success'));   ?>
      </li>
      <li class="<?php echo $x_approved; ?>">
        <?php     echo $this->Html->link('<i class="icon-pause"></i> Rejected',
                    array('action' => 'index', 'submitted'=>'1', 'approved'=>1), array('escape' => false, 'class' =>  'text-success'));   ?>
      </li>
      <li class="<?php echo $unsubmitted; ?>">
        <?php echo $this->Html->link('<i class="icon-minus"></i> Unsubmitted',
                    array('action' => 'index', 'submitted'=>'0'), array('escape' => false, 'class' => 'text-info'));    ?>
      </li>
      <li class="<?php echo $deactivated; ?>">
        <?php echo $this->Html->link('<i class="icon-remove-circle"></i> Deactivated',
                    array('action' => 'index', 'deactivated'=>'1'), array('escape' => false, 'class' => 'text-warning'));    ?>
      </li>
      <li class="<?php echo $deleted; ?>">
        <?php     echo $this->Html->link('<i class="icon-remove"></i> Deleted',
                    array('action' => 'index', 'deleted'=>'1'), array('escape' => false, 'class' => 'text-error'));   ?>
      </li>
      <li class="divider"></li>
<?php $this->end(); ?>

<?php $this->start('persistent-search'); ?>
    <?php
         if (isset($this->request->params['named']['submitted'])) {
           echo $this->Form->input('Application.submitted', array('type' => 'hidden', 'value' => $this->request->params['named']['submitted']));
         }
    ?>
<?php $this->end(); ?>

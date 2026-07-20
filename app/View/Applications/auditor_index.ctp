<?php
  $this->extend('/Elements/application/application_index');
?>

<?php $this->start('header'); ?>
    <?php
      $this->assign('MyApplications', 'active');
    ?>
    <div class="marketing">
      <div class="row-fluid">
            <div class="span12">
              <h3>Assigned Protocols:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view assigned applications</small></h3>
              <hr class="soften" style="margin: 10px 0px;">
            </div>
        </div>
    </div>
<?php $this->end(); ?>

<?php
    $this->assign('color-codes', 'true');
    $this->assign('is-monitor', 'true'); 
?>

<?php
    $this->assign('attributes', 'application/attributes/auditor_attributes');
?><?php
  
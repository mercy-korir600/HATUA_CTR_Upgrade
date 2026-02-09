<?php
  $this->extend('/Elements/application/application_index');
?>

<?php $this->start('header'); ?>
    <?php
      $this->assign('Applications', 'active');
    ?>
    <div class="marketing">
      <div class="row-fluid">
            <div class="span12">
              <h3>My Clinical Trial Applications:<small> <i class="icon-glass"></i> Filter, <i class="icon-search"></i> Search, and <i class="icon-eye-open"></i> view applications</small></h3>
              <!-- <hr style="margin: 10px 0px"> -->
              <hr class="soften" style="margin: 10px 0px;">
            </div>
        </div>
    </div>
<?php $this->end(); ?>

<?php
    $this->assign('color-codes', 'true');
?>

<?php
    $this->assign('attributes', 'application/attributes/reviewer_attributes');
 ?>

<?php
    $this->assign('quick-pdf', 'true');
 ?>

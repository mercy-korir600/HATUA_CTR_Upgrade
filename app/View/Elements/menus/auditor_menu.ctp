<div class="menu text-center">
    <ul class="nav nav-pills center-pills">
        <li class="<?php echo $this->fetch('Dashboard') ?>">
            <?php
                echo $this->Html->link('<i class="icon-dashboard"></i> Dashboard',
                array('controller' => 'users', 'action'=>'dashboard', 'auditor' => true ), array('escape' => false ));
            ?>
         </li>
         <li class="<?php echo $this->fetch('MyApplications') ?>">
            <?php
                echo $this->Html->link('<i class="icon-file-text"></i> Assigned Protocols',
                    array('controller' => 'applications', 'action'=>'index', 'auditor' => true ), array('escape' => false ));
            ?>
         </li>
         <li class="<?php echo $this->fetch('Profile') ?>">
           <?php
            echo $this->Html->link('<i class="icon-user"></i> My Profile',
              array('controller' => 'users', 'action'=>'profile', 'admin' => false ), array('escape' => false ));
            ?>
          </li>
    </ul>
</div>
<hr class="soften">
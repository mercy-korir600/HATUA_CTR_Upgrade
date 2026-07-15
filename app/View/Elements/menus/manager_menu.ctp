
 <div class="menu text-center">
    <ul class="nav nav-pills center-pills">
      <li class="<?php echo $this->fetch('Dashboard') ?>">
        <?php
          echo $this->Html->link('<i class="icon-dashboard"></i> Dashboard',
          array('controller' => 'users', 'action'=>'dashboard', 'manager' => true ), array('escape' => false ));
            ?>
       </li>
       <li class="<?php echo $this->fetch('MEETINGS') ?>">
          <?php
              echo $this->Html->link('<i class="icon-calendar"></i> Meetings',
                  array('controller' => 'meeting_dates', 'action'=>'index', 'manager' => true ), array('escape' => false ));
              ?>
       </li>
       <li class="<?php echo $this->fetch('Applications') ?>">
        <?php
          echo $this->Html->link('<i class="icon-file-text"></i> Applications',
            array('controller' => 'applications', 'action'=>'index', 'manager' => true ), array('escape' => false ));
            ?>
       </li>
       <li class="<?php echo $this->fetch('Workflows') ?>">
        <?php
          echo $this->Html->link('<i class="icon-code-fork"></i> Workflows',
            array('controller' => 'applications', 'action'=>'workflow', 'manager' => true ), array('escape' => false ));
            ?>
       </li>
       <li class="<?php echo $this->fetch('Auditors') ?>">
         <?php
           echo $this->Html->link('<i class="icon-group"></i> Study Auditors',
             array('controller' => 'study_auditors', 'action' => 'index', 'admin' => true), array('escape' => false ));
         ?>
       </li>
       <li class="dropdown <?php echo $this->fetch('SAE') ?> <?php echo $this->fetch('SI') ?> <?php echo $this->fetch('PF') ?> <?php echo $this->fetch('CIOM') ?> <?php echo $this->fetch('DEV') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop7" href="#">
            <i class="icon-paper-clip"></i> Application data <b class="caret"></b></a>
          <ul aria-labelledby="drop7" role="menu" class="dropdown-menu">
             <li>
             <?php
              echo $this->Html->link('<i class="icon-list-alt"></i> SAE',
                  array('controller' => 'saes', 'action'=>'index', 'manager' => true ), array('escape' => false ));
              ?>
            </li>
          <li class="divider"></li>
             <li>
              <?php
              echo $this->Html->link('<i class="icon-random"></i> Deviations/Violations',
                  array('controller' => 'deviations', 'action'=>'index', 'manager' => true ), array('escape' => false ));
              ?>
            </li>
          <li class="divider"></li>
             <li>
              <?php
              echo $this->Html->link('<i class="icon-skype"></i> Site Inspections',
                  array('controller' => 'site_inspections', 'action'=>'index', 'manager' => true ), array('escape' => false ));
              ?>
            </li>
          <li class="divider"></li>
            <li>
              <?php
              echo $this->Html->link('<i class="icon-sitemap"></i> Participant flows',
                  array('controller' => 'participant_flows', 'action'=>'index', 'manager' => true ), array('escape' => false ));
              ?>
            </li>
          <li class="divider"></li>
            <li>
              <?php
                    echo $this->Html->link('<i class="icon-upload-alt"></i> CIOMS E2B',
                        array('controller' => 'cioms', 'action'=>'index', 'manager' => true ), array('escape' => false ));
                    ?>
            </li>
          <li class="divider"></li>
            <li>
              <?php
                    echo $this->Html->link('<i class="icon-money"></i> Study Budgets',
                        array('controller' => 'budgets', 'action'=>'index', 'manager' => true ), array('escape' => false ));
                    ?>
            </li>
        </ul>
       </li>

       <li class="dropdown <?php echo $this->fetch('Reports') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop4" href="#">
            <i class="icon-bar-chart"></i> Summaries <b class="caret"></b></a>
          <ul aria-labelledby="drop4" role="menu" class="dropdown-menu">
             <li><?php
                        echo $this->Html->link('<i class="icon-signal"></i> GCP Study Inspections conducted per month',  array('controller' => 'reports', 'action'=>'si_per_month', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
          <li class="divider"></li>
             <li><?php
                        echo $this->Html->link('<i class="icon-signal"></i> SAE/SUSAR Reported per Month',  array('controller' => 'reports', 'action'=>'sae_per_month', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            <li><?php  
              echo $this->Html->link('<i class="icon-arrow-right"></i> Monthly Deviations',  
                 array('controller' => 'reports', 'action'=>'dev_per_month', 'manager' => true ), array('escape' => false, 'tabindex' => '-1'));?>
            </li>
            <li><?php  
            echo $this->Html->link('<i class="icon-arrow-right"></i> SAE/SUSAR Reported per study',  
                 array('controller' => 'reports', 'action'=>'sae_by_type', 'manager' => true ), array('escape' => false, 'tabindex' => '-1'));?>
            </li>
            <li><?php  
            echo $this->Html->link('<i class="icon-random"></i> Deviations Reported per study',  
                 array('controller' => 'reports', 'action'=>'dev_by_study', 'manager' => true ), array('escape' => false, 'tabindex' => '-1'));?>
            </li>
          <li class="divider"></li>
             <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Status of Studies',  array('controller' => 'reports', 'action'=>'protocols_by_status', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
             <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Studies by phase',  array('controller' => 'reports', 'action'=>'protocols_by_phase', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            <li class="divider"></li>
            <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Studies by Participants Gender distribution',  array('controller' => 'reports', 'action'=>'protocols_by_distribution', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Studies using Placebo',  array('controller' => 'reports', 'action'=>'protocols_by_placebo', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Studies by design type',  array('controller' => 'reports', 'action'=>'protocols_by_design_type', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            <li><?php
                        echo $this->Html->link('<i class="icon-bar-chart"></i> Scope of Studies',  array('controller' => 'reports', 'action'=>'protocols_by_clinical_trial', 'manager' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
            
        </ul>
       </li>

       <li class="dropdown <?php echo $this->fetch('NT') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop5" href="#">
            <i class="icon-envelope"></i> Messages &amp; Notifications <b class="caret"></b></a>
          <ul aria-labelledby="drop5" role="menu" class="dropdown-menu">
             <li><?php
                   echo $this->Html->link('<i class="icon-exclamation-sign"></i> Notifications',
                      array('controller' => 'notifications', 'action'=>'index', 'manager' => true ), array('escape' => false ));
                 ?>
            </li>
          <li class="divider"></li>
             <li><?php                      
                    echo $this->Html->link('<i class="icon-envelope"></i> Messages',
                      array('controller' => 'feedbacks', 'action'=>'index', 'manager' => true ), array('escape' => false ));
                ?>
            </li>            
        </ul>
       </li>
       <li class="<?php echo $this->fetch('Profile') ?>">
        <?php
          echo $this->Html->link('<i class="icon-user"></i> My Profile',
            array('controller' => 'users', 'action'=>'profile', 'admin' => false ), array('escape' => false ));
            ?>
       </li>
    </ul>

</div>

<hr>
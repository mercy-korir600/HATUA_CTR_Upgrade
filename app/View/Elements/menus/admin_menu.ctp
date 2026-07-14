
    <div class="menu text-center">
    <ul class="nav nav-pills center-pills">
      <li class="<?php echo $this->fetch('Dashboard') ?>">
        <?php
          echo $this->Html->link('<i class="icon-dashboard"></i> Dashboard',
          array('controller' => 'users', 'action'=>'dashboard', 'admin' => true), array('escape' => false ));
            ?>
       </li>
       <li class="dropdown <?php echo $this->fetch('Reports') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop1" href="#">
            <i class="icon-file"></i>  Reports<b class="caret"></b></a>
          <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
            <li>
              <?php echo $this->Html->link('<i class="icon-table"></i> All Applications',  array('controller' => 'applications',
                    'action' => 'index', 'admin' => true), array('escape' => false));?>
            </li>
            <li>
              <?php     echo $this->Html->link('<i class="icon-ok"></i> Approved',
                          array('controller' => 'applications', 'action' => 'index', 'submitted'=>'1', 'approved'=>2, 'admin' => true), array('escape' => false));   ?>
            </li>
            <li>
              <?php     echo $this->Html->link('<i class="icon-shopping-cart"></i> Waiting Approval',
                          array('controller' => 'applications', 'action' => 'index', 'submitted'=>'1', 'approved'=>0, 'admin' => true), array('escape' => false));   ?>
            </li>
            <li>
              <?php     echo $this->Html->link('<i class="icon-pause"></i> On hold',
                          array('controller' => 'applications', 'action' => 'index', 'submitted'=>'1', 'approved'=>1, 'admin' => true), array('escape' => false));   ?>
            </li>
            <li>
              <?php echo $this->Html->link('<i class="icon-minus"></i> Unsubmitted',
                          array('controller' => 'applications', 'action' => 'index', 'submitted'=>'0', 'admin' => true), array('escape' => false, 'class' => 'text-info'));    ?>
            </li>
            <li>
              <?php echo $this->Html->link('<i class="icon-remove-circle"></i> Deactivated',
                  array('controller' => 'applications', 'action' => 'index', 'deactivated'=>'1', 'admin' => true), array('escape' => false));    ?>
            </li>
            <li>
              <?php     echo $this->Html->link('<i class="icon-remove"></i> Deleted',
                          array('controller' => 'applications', 'action' => 'index', 'deleted'=>'1', 'admin' => true), array('escape' => false));   ?>
            </li>
             <li class="divider"></li>
        </ul>
       </li>
       <li class="dropdown <?php echo $this->fetch('Users') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop3" href="#">
            <i class="icon-group"></i> User Management <b class="caret"></b></a>
          <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
             <li><?php
                        echo $this->Html->link('<i class="icon-user"></i> All Users',  array('controller' => 'users', 'action'=>'index', 'admin' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
             <li><?php
                        echo $this->Html->link('<i class="icon-plus-sign"></i> Add Users',  array('controller' => 'users', 'action'=>'add', 'admin' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>

            <li><?php
                        echo $this->Html->link('<i class="icon-user-md"></i> Outsourced Users',  array('controller' => 'protocol_outsources', 'action'=>'index', 'admin' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
             <li><?php
                        echo $this->Html->link('<i class="icon-user-md"></i> Study Monitors',  array('controller' => 'study_monitors', 'action'=>'index', 'admin' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
             <li><?php
                         echo $this->Html->link('<i class="icon-user-md"></i> Auditor Protocols',  array('controller' => 'auditor_protocols', 'action'=>'index', 'admin' => true ),
                                   array('escape' => false, 'tabindex' => '-1'));
                     ?>
            </li>
            <li><?php
                echo $this->Html->link('<i class="icon-group"></i> User Roles',  array('controller' => 'groups', 'action'=>'index', 'admin' => true ),
                                  array('escape' => false, 'tabindex' => '-1'));
                    ?>
            </li>
             <li class="divider"></li>
        </ul>
       </li>
       <li class="dropdown <?php echo $this->fetch('Content') ?>">
         <a data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="#">
            <i class="icon-book"></i> Content Management <b class="caret"></b></a>
          <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
            <li>
            <?php
            echo $this->Html->link('<i class="icon-comment-alt"></i> User Feedback',
              array('controller' => 'feedbacks', 'action' => 'index',  'admin' => true),
              array('escape' => false));
            ?>
            </li>
            <li class="divider"></li>
            <li><?php
            echo $this->Html->link('<i class="icon-envelope"></i> Site Messages',
                array('controller' => 'messages', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
            </li>
            <li>
            <?php
            echo $this->Html->link('<i class="icon-hand-right"></i> Counties',
              array('controller' => 'counties', 'action' => 'index',  'admin' => true),
              array('escape' => false));
            ?>
            </li>
            <li>
            <?php
            echo $this->Html->link('<i class="icon-globe"></i> Countries',
              array('controller' => 'countries', 'action' => 'index', 'admin' => true),
              array('escape' => false));
            ?>
            </li>
            <li>
            <?php
            echo $this->Html->link('<i class="icon-flag"></i> Trial Statuses',
              array('controller' => 'trial_statuses', 'action' => 'index', 'admin' => true), array('escape' => false));
            ?>
            </li>            
        <li>
        <?php
        echo $this->Html->link('<i class="icon-question-sign"></i> Site Questions',
          array('controller' => 'site_questions', 'action' => 'index', 'admin' => true), array('escape' => false));
        ?>
        </li>
             <li class="divider"></li>
        </ul>
       </li>
       <li class="<?php echo $this->fetch('Profile') ?>">
        <?php
          echo $this->Html->link('<i class="icon-user"></i> My Profile',
            array('controller' => 'users', 'action'=>'profile', 'admin' => false ), array('escape' => false ));
            ?>
       </li>
    </ul>

    </div><!-- /.nav-collapse -->

<hr>

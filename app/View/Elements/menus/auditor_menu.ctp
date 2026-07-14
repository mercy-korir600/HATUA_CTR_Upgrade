<ul class="nav">
    <li class="<?php echo $this->viewVars['title_for_layout'] == 'Auditor Dashboard' ? 'active' : ''; ?>">
        <?php echo $this->Html->link('Dashboard', array('controller' => 'users', 'action' => 'dashboard', 'auditor' => true)); ?>
    </li>
</ul>
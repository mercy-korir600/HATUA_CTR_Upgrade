<?php
	$this->assign('Profile', 'active');
 ?>

      <!-- SADR
    ================================================== -->
	<div class="marketing">
		<h3 class="text-success"><?php echo $this->Session->read('Auth.User.username') ?>&rsquo;s profile</h3>
		<hr class="soften" style="margin: 10px;">
	</div>
	<div class="row-fluid">
		<div class="span3 columns">
				<h4>Welcome to your profile</h4>
				<hr>
				<p>Thank you for being a registered user.</p>
				<p>You may update your registration details within your profile or change your password.</p>
				<?php 
					if($redir == 'applicanter'){
				?>
				<h6 class="text-success" style="text-decoration: underline;">Contract Research Organizations (CRO)/ Monitors</h6>
				<p>Please add study CRO/Monitor to enable them submit reports e.g. SUSARs, directly to PPB</p>
				<?php echo $this->Html->link('Add Monitor', array('controller' => 'users', 'action' => 'add', 'applicant' => true), array('class' => 'btn btn-inverse', 'escape' => false)); ?>

				<?php
					}
				?>
		</div> <!-- /span5 -->

		<div class="span5 columns">
			 <h4>Registration details</h4>
			 <hr>
			  <dl class="dl-horizontal">
				<dt>Username</dt>
				<dd><?php echo $user['User']['username'] ;?> &nbsp; </dd>
				<dt>Name</dt>
				<dd><?php echo $user['User']['name']; ?> &nbsp; </dd>
				<dt>Email</dt>
				<dd><?php echo $user['User']['email']; ?> &nbsp; </dd>
				<dt>Sponsor's Email</dt>
				<dd><?php echo $user['User']['sponsor_email']; ?> &nbsp; </dd>
				<dt>Qualification</dt>
				<dd><?php echo $user['User']['qualification']; ?> &nbsp; </dd>
				<dt>Phone No</dt>
				<dd><?php echo $user['User']['phone_no']; ?> &nbsp; </dd>
				<dt>Name of institution</dt>
				<dd><?php echo $user['User']['name_of_institution'];?>&nbsp; </dd>
				<dt>Physical Address</dt>
				<dd><?php echo $user['User']['institution_physical'];?> &nbsp; </dd>
				<dt>Institution Address</dt>
				<dd><?php echo $user['User']['institution_address'];?> &nbsp; </dd>
				<dt>Institution Contact</dt>
				<dd><?php echo $user['User']['institution_contact'];?> &nbsp; </dd>
				<dt>County</dt>
				<dd><?php echo ($county['County']['county_name']) ?> &nbsp; </dd>
				<dt>Country</dt>
				<dd><?php echo ($country['Country']['name']) ?> &nbsp; </dd>
				<dt></dt>
				<dd>
					<?php echo $this->Html->link('edit', array('action' => 'edit'), array('class' => 'btn btn-primary')); ?>
				</dd>
			  </dl>
			  <hr>
		</div> <!-- /span6 -->
		<div class="span4 columns">
			<h4>Change Password<small> If you want</small></h4>
			<hr>
			<?php
				echo $this->Form->create('User', array(
					'inputDefaults' => array(
						'div' => array('class' => 'control-group'),
						'label' => array('class' => 'control-label'),
						'between' => '<div class="controls">',
						'after' => '</div>',
						'class' => '',
						'format' => array('before', 'label', 'between', 'input', 'after','error'),
						'error' => array('attributes' => array( 'class' => 'controls help-block')),
					 ),
				));

				echo $this->Form->input('old_password', array(
							'type' => 'password',
							'label' => array('class' => 'control-label required', 'text' => 'Old Password'.' <span style="color:red;">*</span>'),
							'div' => array('class' => 'control-group'),
							'after'=>'<p class="help-block"> Your current password! </p></div>',
				));
				echo $this->Form->input('password', array('label' => array('class' => 'control-label required', 'text' => 'New Password'.' <span style="color:red;">*</span>'),));
				echo $this->Form->input('confirm_password', array(
						'type' => 'password',
						'label' => array('class' => 'control-label required', 'text' => 'Confirm New Password'.' <span style="color:red;">*</span>'), ));

				echo $this->Form->end(array(
						'label' => 'Change',
						'value' => 'Change',
						'onclick'=>"return confirm('Are you sure you wish to change your password?');",
						'class' => 'btn btn-primary tooltipper',
						'id' => 'UserChangePassword',
						'div' => array(
							'class' => 'form-actions',
						)
					));
				?>
		</div> <!-- /span4 -->
	   </div> <!-- /row-fluid -->

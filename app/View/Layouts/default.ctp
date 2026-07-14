<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'Pharmacy and Poisons Board');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $title_for_layout; ?>
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700" rel="stylesheet">
    <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('jquery-ui-1.9.2.custom');
        echo $this->Html->css('bootstrap');
        echo $this->Html->css('bootstrap-responsive');
        echo $this->Html->css('docs2');
        // echo $this->Html->css('prettify');
        echo $this->Html->css('font-awesome');
        echo $this->Html->css('font-awesome-ie7');
        echo $this->Html->css('ctr-fix2');

        //
        echo $this->Html->css('assets/css/docs');
        echo $this->Html->css('assets/css/prettyPhoto');
        echo $this->Html->css('assets/js/google-code-prettify/prettify');
        
        echo $this->Html->css('assets/css/sequence');
        echo $this->Html->css('assets/css/style2');
        echo $this->Html->css('assets/color/default2');

            // echo $this->Html->script('jquery-1.8.3');
        echo $this->Html->script('jquery-1.9.1');
        echo $this->Html->script('jquery-ui-1.9.2.custom');
        echo $this->Html->script('jquery.cookie');
             echo $this->Html->script('jquery.expander');

        echo $this->Html->script('bootstrap');


        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');

        echo $this->element('google-analytics');
    ?>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="/ctr/js/html5.js"></script>
        <![endif]-->
</head>
<body>
  <header>
    <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <!-- logo -->
          <span><img style="float:left; width: 80px;" alt="Pharmacy and Poisons Board" src="/img/logo1.png" border="0"></span> 
          <span><a class="brand logo" style="color: #638624; " href="/">Pharmacy and Poisons Board </a></span>
          
          <!-- end logo -->
          <!-- top menu -->
          <div class="navigation">
            <nav>
              <ul class="nav topnav">
                <li class="dropdown <?php echo $this->fetch('Home') ?>">
                    <?php echo $this->Html->link('<i class="icon-home"></i> Home', array('controller' => 'pages', 'action' => 'home', 'admin' => false) , array('escape' => false)); ?>
                </li>
                <li class="dropdown <?php echo $this->fetch('Applications') ?>">
                    <?php echo $this->Html->link('<i class="icon-thumbs-up"></i> Approved Clinical Trials', array('controller' => 'applications', 'action'=>'index', 'admin' => false,) , array('escape' => false)); ?>
                </li>
                <li class="dropdown <?php echo $this->fetch('News') ?>">
                    <?php echo $this->Html->link('<i class="icon-bullhorn"></i> News', array('controller' => 'pages', 'action'=>'news', 'admin' => false,) , array('escape' => false)); ?>
                </li>
                <li class="dropdown <?php echo $this->fetch('FAQ') ?>">
                    <?php echo $this->Html->link('<i class="icon-question-sign"></i> FAQs', array('controller' => 'pages', 'action'=>'faqs', 'admin' => false,) , array('escape' => false)); ?>
                </li>
                <li class="dropdown <?php echo $this->fetch('ContactUs') ?>">
                    <?php
                        echo $this->Html->link('<i class="icon-envelope"></i> Contact us', array('controller' => 'feedbacks', 'action'=>'add',  'admin' => false,) , array('escape' => false)); 
                    ?>
                </li>
                <li class="dropdown <?php echo $this->fetch('Login') ?>">
                    <?php
                        if($this->Session->read('Auth.User')) {
                            echo $this->Html->link('<i class="icon-user"></i> '.$this->Text->truncate($this->Session->read('Auth.User.username'), 12, array('ellipsis' => '..', 'html' => true)),
                                array('controller' => 'users', 'action' => 'profile', 'admin' => false,) , array('escape' => false));
                        } else {
                            echo $this->Html->link('<i class="icon-signin"></i> Login',
                                array('controller' => 'users', 'action' =>  'login', 'admin' => false) , array('escape' => false));
                        }
                    ?>
                </li>
                <?php if($this->Session->read('Auth.User')) { ?>
                    <li class="dropdown ">
                    <?php
                            echo $this->Html->link('<i class="icon-signout"></i> Logout', array('controller' => 'users', 'action' => 'logout',  'admin' => false) , array('escape' => false));
                    ?>
                    </li>
                <?php } else { ?>
                    <li class="dropdown <?php echo $this->fetch('Register') ?>">
                    <?php
                            echo $this->Html->link('<i class="icon-edit"></i> Register', array('controller' => 'users', 'action' => 'register', 'admin' => false) , array('escape' => false));
                    ?>
                    </li>
                <?php } ?>
              </ul>
            </nav>
          </div>
          <!-- end menu -->
        </div>
      </div>
    </div>
  </header>
  
  <?php echo $this->fetch('banner'); ?>

  <!-- Subhead
================================================== -->


  <section id="maincontent">
    <div class="container">

          <div class="alert alert-error alertbrowser" style="display: none;">
              <button type="button" class="close"data-dismiss="alert">&times;</button>
              <strong>NOTE TO APPLICANT!</strong> Please use the latest versions of Firefox or Google Chrome for the best experience on this site.
          </div>
          <?php
              if($this->Session->read('Auth.User.group_id')) {
                  if($this->Session->read('Auth.User.group_id') == '1') echo $this->element('menus/admin_menu') ;
                  if($this->Session->read('Auth.User.group_id') == '2') echo $this->element('menus/manager_menu');
                  if($this->Session->read('Auth.User.group_id') == '3') echo $this->element('menus/reviewer_menu');
                  if($this->Session->read('Auth.User.group_id') == '4') echo $this->element('menus/partner_menu');
                  if($this->Session->read('Auth.User.group_id') == '5') echo $this->element('menus/applicant_menu');
                  if($this->Session->read('Auth.User.group_id') == '6') echo $this->element('menus/inspector_menu');
                  if($this->Session->read('Auth.User.group_id') == '7') echo $this->element('menus/monitor_menu');
                  if($this->Session->read('Auth.User.group_id') == '8') echo $this->element('menus/outsource_menu');
                   if($this->Session->read('Auth.User.group_id') == '10') echo $this->element('menus/auditor_menu');
              }
              echo $this->Session->flash();
              echo $this->Session->flash('auth');
              echo $this->fetch('content'); 
          ?>
      </div>
  </section>
  <!-- Footer
 ================================================== -->
 
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="span5">
          <div class="widget">
            <!-- logo -->
            <!-- <h5>Browse pages</h5>
            <address>
                            <strong>Registered Companyname, Inc.</strong><br>
                             8895 Somename Ave, Suite 600<br>
                             San Francisco, CA 94107<br>
                            <abbr title="Phone">P:</abbr> (123) 456-7890
                        </address> -->
              <?php
                echo $this->Html->link(
                    $this->Html->image('cake.power.png', array('alt' => $cakeDescription, 'border' => '0')),
                    array('controller' => 'pages', 'action' => 'home',
                        'admin' => false,), array('target' => '_blank', 'escape' => false)
                  );
              ?>
                <address>
                <a href="http://www.pharmacyboardkenya.org" target="_blank">Pharmacy and Poisons Board Kenya</a>.
                Kenya's Drug Regulatory Authority
                <ul class="footer-links">
                  <li><a href="/">Ministry of Health</a></li>
                  <li><a href="/">Contact Us</a></li>
                  <li><a href="http://clinicaltrials.org">Clinical Trials</a></li>
                </ul>
                </address>
          </div>
        </div>
        <div class="span7">  
          <p class="pull-right"><a href="#">Back to top</a></p>          

                <img src="/img/edctp.png" alt="EDCTP" width="12%">

                <img src="/img/eu.png" alt="EU" style="padding-left: 30%;" width="17%">
                <p>
                  Clinical Trial Registry Information system upgrade project (HATUA) with the support from EDCTP and EU partners
                </p>
        </div>
      </div>
    </div>
    <div class="verybottom">
      <div class="container">
        <div class="row">
          <div class="span6">
            <p>
              Copyright &copy; <?php echo date('Y'); ?>. All Rights Reserved.
            </p>
          </div>
          <div class="span6">
            <div class="credits">
              <!--
                All the links in the footer should remain intact.
                You can delete the links only if you purchased the pro version.
                Licensing information: https://bootstrapmade.com/license/
                Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Serenity
              -->
              <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <?php echo $this->fetch('homejs'); ?>

</body>
</html>

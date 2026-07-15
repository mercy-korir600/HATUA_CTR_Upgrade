<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::parseExtensions('json', 'pdf', 'xml', 'xlsx', 'csv');
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/api/sae/submit', array('controller' => 'saes', 'action' => 'submit'));

	//APPLICANT ROUTING: DASHBOARD PAGE
	Router::connect('/applicant', array('controller' => 'users', 'action' => 'dashboard', 'applicant' => true));	
	//REVIEWER ROUTING: DASHBOARD PAGE
	Router::connect('/reviewer', array('controller' => 'users', 'action' => 'dashboard', 'reviewer' => true));	
	//PARTNER ROUTING: DASHBOARD PAGE
	Router::connect('/partner', array('controller' => 'users', 'action' => 'dashboard', 'partner' => true));	
	//MANAGER ROUTING: DASHBOARD PAGE
	Router::connect('/manager', array('controller' => 'users', 'action' => 'dashboard', 'manager' => true));		
	//ADMIN ROUTING: DASHBOARD PAGE
	Router::connect('/admin', array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));	
	//INSPECTOR ROUTING: DASHBOARD PAGE
	Router::connect('/inspector', array('controller' => 'users', 'action' => 'dashboard', 'inspector' => true));	
	//MONITOR ROUTING: DASHBOARD PAGE
	Router::connect('/monitor', array('controller' => 'users', 'action' => 'dashboard', 'monitor' => true));	 
	//OUTSOURCE USER ROUTING: DASHBOARD PAGE
	Router::connect('/outsource', array('controller' => 'users', 'action' => 'dashboard', 'outsource' => true));	
	//PPB INTERNAL USER ROUTING: DASHBOARD PAGE
	Router::connect('/internalreviewer', array('controller' => 'users', 'action' => 'dashboard', 'internalreviewer' => true));	
		// AUDITOR ROUTING: DASHBOARD PAGE
	Router::connect('/auditor', array('controller' => 'users', 'action' => 'dashboard', 'auditor' => true));
 
/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';

<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
  protected $a_contain = array(
    'Amendment',
    'EthicalCommittee',
    'InvestigatorContact',
    'Pharmacist',
    'Sponsor',
    'SiteDetail'  => array('County'),
    'Organization',
    'Placebo',
    'Attachment',
    'CoverLetter',
    'Protocol',
    'PatientLeaflet',
    'Brochure',
    'GmpCertificate',
    'Cv',
    'Finance',
    'Declaration',
    'IndemnityCover',
    'OpinionLetter',
    'ApprovalLetter',
    'Statement',
    'ParticipatingStudy',
    'Addendum',
    'Registration',
    'Fee',
    'Checklist',
    'AnnualApproval',
    'AmendmentApproval' => array('Attachment'),
    'AmendmentApprovalSummary' => array('Attachment'),
    'AmendmentChecklist',
    'AmendmentLetter',
    'ParticipantFlow',
    'Budget',
    'Document',
    'ActiveInspector',
    'Review'  => array('InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User'),
    'Sae',
    'AmendmentLetter',
    'AnnualLetter' => array('InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment')),
    'StudyRoute',
    'Manufacturer',
    'Ciom',
    'Deviation' => array('ExternalComment' => array('Attachment')),
    'SiteInspection' => array('SiteAnswer', 'Attachment', 'InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'User'),
    'ApplicationStage' => array('Comment' => array('Attachment')),
    'Outsource' => array('User')
  );
  public $components = array(
    'Acl',
    'Auth' => array(
      'authorize' => array(
        'Actions' => array('actionPath' => 'controllers')
      ),
      //  'authenticate' => array(
      //    'Form' => array(
      //        'fields' => array('username' => 'email')
      //    )
      // )
    ),
    'RequestHandler' => array(
      'viewClassMap' => array('csv' => 'CsvView.Csv')
    ),
    'Session',
    'DebugKit.Toolbar' => array('panels' => array('DebugKit.history'))
  );
  public $helpers = array('Html', 'Form', 'Session', 'Text');

  public function beforeFilter()
  {
    $this->Auth->allow('display');
    //Configure AuthComponent
    // $this->set( 'domain', 'tools' );
    $redir = '';
    if ($this->Auth->User('group_id') == '1')  $redir = 'admin';
    if ($this->Auth->User('group_id') == '2')  $redir = 'manager';
    if ($this->Auth->User('group_id') == '3')  $redir = 'reviewer';
    if ($this->Auth->User('group_id') == '4')  $redir = 'partner';
    if ($this->Auth->User('group_id') == '5')  $redir = 'applicant';
    if ($this->Auth->User('group_id') == '6')  $redir = 'inspector';
    if ($this->Auth->User('group_id') == '7')  $redir = 'monitor';
    if ($this->Auth->User('group_id') == '8')  $redir = 'outsource';

    $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false);
    $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'admin' => false);
    $this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'dashboard', $redir => true);

    $this->Auth->authError = __('<div class="alert alert-error">
                      <button data-dismiss="alert" class="close">&times;</button>
                      <h4><strong>Sorry!</strong> You don\'t have sufficient permissions to access the location.</h4>
                     </div>', true);
    $this->Auth->loginError = __('<div class="alert alert-error">
                      <button data-dismiss="alert" class="close">&times;</button>
                      <h4>Invalid e-mail / password combination.  Please try again.</h4>
                     </div>', true);
    $this->set('redir', $redir);
    // $this->Auth->authenticate = array(
    //     'all' => array (
    //         'scope' => array('User.is_active' => 1)
    //     ),
    //     'Form'
    // );
  }

  // public function isAuthorized($user) {
  // if (empty($this->request->prefix)) {
  // return true;
  // }
  // Admin can access every action
  // if (isset($user['group_id']) && $user['group_id'] === '1') {
  // return true;
  // }
  // Allow actions with no parameters supplied
  // if (!empty($this->request->prefix) && !isset($this->request->params['pass'][0])) {
  // return true;
  // }
  // return false;
  // }

  protected function buildMediaDownloadParams($dirname, $basename, $fallbackName = null)
  {
    $path = 'media' . DS . 'transfer' . DS;
    if (!empty($dirname)) {
      $path .= trim($dirname, DS) . DS;
    }

    $params = array(
      'id' => $basename,
      'download' => true,
      'path' => $path
    );

    $extension = $this->_preferredDownloadExtension($dirname, $basename);
    $name = $this->_downloadNameWithoutExtension($basename, $fallbackName, $extension);

    if (!empty($name)) {
      $params['name'] = $name;
    }

    if (!empty($extension)) {
      $params['extension'] = $extension;
    }

    return $params;
  }

  protected function _preferredDownloadExtension($dirname, $basename)
  {
    $extension = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
    $mimeType = $this->_detectTransferredFileMimeType($dirname, $basename);
    $mappedExtension = $mimeType ? strtolower((string) $this->response->mapType($mimeType)) : null;

    // Generated reports are sometimes stored without an extension even
    // though the binary is already a PDF. Prefer the detected PDF type.
    if ($mappedExtension === 'pdf') {
      return 'pdf';
    }

    return $extension;
  }

  protected function _downloadNameWithoutExtension($basename, $fallbackName = null, $extension = null)
  {
    $name = $fallbackName;

    if (empty($name)) {
      $name = $basename;
    }

    if (!empty($extension)) {
      $suffix = '.' . $extension;
      if (strlen($name) > strlen($suffix) && strcasecmp(substr($name, -strlen($suffix)), $suffix) === 0) {
        $name = substr($name, 0, -strlen($suffix));
      }
    }

    return $name;
  }

  protected function _detectTransferredFileMimeType($dirname, $basename)
  {
    foreach ($this->_mediaDownloadPathCandidates($dirname, $basename) as $filePath) {
      if (!is_file($filePath) || !is_readable($filePath)) {
        continue;
      }

      if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
          $mimeType = finfo_file($finfo, $filePath);
          finfo_close($finfo);
          if (!empty($mimeType)) {
            return $mimeType;
          }
        }
      }

      if (function_exists('mime_content_type')) {
        $mimeType = @mime_content_type($filePath);
        if (!empty($mimeType)) {
          return $mimeType;
        }
      }
    }

    return null;
  }

  protected function _mediaDownloadPathCandidates($dirname, $basename)
  {
    $relativePath = trim((string) $dirname, DS);
    if ($relativePath !== '') {
      $relativePath .= DS;
    }
    $relativePath .= $basename;

    $candidates = array();

    if (defined('MEDIA_TRANSFER')) {
      $candidates[] = MEDIA_TRANSFER . $relativePath;
    }

    $candidates[] = WWW_ROOT . 'media' . DS . 'transfer' . DS . $relativePath;
    $candidates[] = APP . 'media' . DS . 'transfer' . DS . $relativePath;

    return array_unique($candidates);
  }
}

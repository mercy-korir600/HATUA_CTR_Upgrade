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
App::uses('ClassRegistry', 'Utility');
App::uses('Security', 'Utility');

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
    'InternalReview'  => array('InternalComment' => array('Attachment'), 'ExternalComment' => array('Attachment'), 'ReviewAnswer', 'User'),
    
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
    if ($this->Auth->User('group_id') == '9')  $redir = 'internalreviewer';
     if ($this->Auth->User('group_id') == '10') $redir = 'auditor';

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
  public function beforeRender()
  {
    parent::beforeRender();

    $requestExt = !empty($this->request->params['ext']) ? strtolower($this->request->params['ext']) : '';
    $requestUrl = !empty($this->request->url) ? strtolower($this->request->url) : '';
    $isPdfRequest = ($requestExt === 'pdf') || (strpos($requestUrl, '.pdf') !== false);
    if (
      $isPdfRequest &&
      isset($this->pdfConfig) &&
      is_array($this->pdfConfig) &&
      !empty($this->pdfConfig['filename']) &&
      !preg_match('/\.pdf$/i', $this->pdfConfig['filename'])
    ) {
      $this->pdfConfig['filename'] .= '.pdf';
    }
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

  protected function _getAuthenticatedApiUser()
  {
    $sessionUser = $this->Auth->user();
    if (!empty($sessionUser['id'])) {
      return $sessionUser;
    }

    $token = $this->_extractApiAccessToken();
    if (empty($token)) {
      return array();
    }

    return $this->_findUserByApiAccessToken($token);
  }

  protected function _extractApiAccessToken()
  {
    foreach ($this->_candidateApiAuthHeaders() as $header) {
      $token = $this->_tokenFromHeaderValue($header);
      if ($token !== '') {
        return $token;
      }
    }

    foreach (array('access_token', 'token', 'bearer_token', 'authorization') as $queryKey) {
      if (!empty($this->request->query[$queryKey])) {
        return trim((string) $this->request->query[$queryKey]);
      }
    }

    if (is_array($this->request->data)) {
      foreach (array('access_token', 'token', 'bearer_token', 'authorization') as $dataKey) {
        if (!empty($this->request->data[$dataKey])) {
          return trim((string) $this->request->data[$dataKey]);
        }
      }

      if (!empty($this->request->data['auth']) && is_array($this->request->data['auth'])) {
        foreach (array('access_token', 'token', 'bearer_token', 'authorization') as $dataKey) {
          if (!empty($this->request->data['auth'][$dataKey])) {
            return trim((string) $this->request->data['auth'][$dataKey]);
          }
        }
      }
    }

    $rawBody = trim((string) $this->request->input());
    if ($rawBody !== '') {
      $decoded = json_decode($rawBody, true);
      if (is_array($decoded)) {
        foreach (array('access_token', 'token', 'bearer_token', 'authorization') as $dataKey) {
          if (!empty($decoded[$dataKey])) {
            return $this->_tokenFromHeaderValue($decoded[$dataKey]);
          }
        }

        if (!empty($decoded['auth']) && is_array($decoded['auth'])) {
          foreach (array('access_token', 'token', 'bearer_token', 'authorization') as $dataKey) {
            if (!empty($decoded['auth'][$dataKey])) {
              return $this->_tokenFromHeaderValue($decoded['auth'][$dataKey]);
            }
          }
        }
      }
    }

    return '';
  }

  protected function _candidateApiAuthHeaders()
  {
    $headers = array();

    foreach (array(
      'Authorization',
      'authorization',
      'X-Authorization',
      'x-authorization',
      'X-Access-Token',
      'x-access-token'
    ) as $headerName) {
      $headerValue = $this->request->header($headerName);
      if (!empty($headerValue)) {
        $headers[] = $headerValue;
      }
    }

    foreach (array(
      'HTTP_AUTHORIZATION',
      'REDIRECT_HTTP_AUTHORIZATION',
      'Authorization',
      'HTTP_X_AUTHORIZATION',
      'HTTP_X_ACCESS_TOKEN'
    ) as $serverKey) {
      if (!empty($_SERVER[$serverKey])) {
        $headers[] = $_SERVER[$serverKey];
      }
    }

    if (function_exists('getallheaders')) {
      $allHeaders = getallheaders();
      if (is_array($allHeaders)) {
        foreach (array('Authorization', 'authorization', 'X-Authorization', 'X-Access-Token') as $headerName) {
          if (!empty($allHeaders[$headerName])) {
            $headers[] = $allHeaders[$headerName];
          }
        }
      }
    }

    return array_unique(array_filter($headers));
  }

  protected function _tokenFromHeaderValue($headerValue)
  {
    $headerValue = trim((string) $headerValue);
    if ($headerValue === '') {
      return '';
    }

    if (preg_match('/^(Bearer|Token)\s+(.+)$/i', $headerValue, $matches)) {
      return trim($matches[2]);
    }

    if (strpos($headerValue, '.') !== false && strpos($headerValue, ' ') === false) {
      return $headerValue;
    }

    return '';
  }

  protected function _issueApiAccessToken($user, $ttl = 86400)
  {
    $expiresAt = time() + (int) $ttl;
    $payload = array(
      'uid' => (int) $user['id'],
      'exp' => $expiresAt
    );

    $encodedPayload = $this->_base64UrlEncode(json_encode($payload));
    $signature = hash_hmac('sha256', $encodedPayload, $this->_apiTokenSecret($user));

    return array(
      'token' => $encodedPayload . '.' . $signature,
      'expires_at' => $expiresAt
    );
  }

  protected function _findUserByApiAccessToken($token)
  {
    $token = trim((string) $token);
    if ($token === '' || strpos($token, '.') === false) {
      return array();
    }

    list($encodedPayload, $providedSignature) = explode('.', $token, 2);
    $payloadJson = $this->_base64UrlDecode($encodedPayload);
    $payload = json_decode($payloadJson, true);

    if (!is_array($payload) || empty($payload['uid']) || empty($payload['exp'])) {
      return array();
    }

    if ((int) $payload['exp'] < time()) {
      return array();
    }

    $User = ClassRegistry::init('User');
    $user = $User->find('first', array(
      'recursive' => -1,
      'conditions' => array('User.id' => (int) $payload['uid']),
      'fields' => array(
        'User.id',
        'User.group_id',
        'User.name',
        'User.username',
        'User.email',
        'User.phone_no',
        'User.password',
        'User.created',
        'User.modified',
        'User.is_active',
        'User.deactivated'
      )
    ));

    if (empty($user['User'])) {
      return array();
    }

    if (
      (isset($user['User']['is_active']) && (int) $user['User']['is_active'] === 0) ||
      (isset($user['User']['deactivated']) && (int) $user['User']['deactivated'] === 1)
    ) {
      return array();
    }

    $expectedSignature = hash_hmac('sha256', $encodedPayload, $this->_apiTokenSecret($user['User']));
    if (!$this->_hashEquals($expectedSignature, $providedSignature)) {
      return array();
    }

    return $user['User'];
  }

  protected function _apiTokenSecret($user)
  {
    return implode('|', array(
      Configure::read('Security.salt'),
      isset($user['password']) ? $user['password'] : '',
      isset($user['created']) ? $user['created'] : '',
      isset($user['modified']) ? $user['modified'] : ''
    ));
  }

  protected function _base64UrlEncode($value)
  {
    return rtrim(strtr(base64_encode((string) $value), '+/', '-_'), '=');
  }

  protected function _base64UrlDecode($value)
  {
    $remainder = strlen($value) % 4;
    if ($remainder > 0) {
      $value .= str_repeat('=', 4 - $remainder);
    }

    return base64_decode(strtr($value, '-_', '+/'));
  }

  protected function _hashEquals($knownString, $userString)
  {
    if (function_exists('hash_equals')) {
      return hash_equals((string) $knownString, (string) $userString);
    }

    return (string) $knownString === (string) $userString;
  }

  protected function _setApiResponseStatusCode($statusCode)
  {
    $statusCode = (int) $statusCode;

    if ($statusCode === 422) {
      $this->response->httpCodes(array(
        422 => 'Unprocessable Entity'
      ));
    }

    $this->response->statusCode($statusCode);

    return $statusCode;
  }
}

<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('ClassRegistry', 'Utility');
App::uses('CakeSession', 'Model/Datasource');
/**
 * User Model
 *
 * @property Group $Group
 */
class User extends AppModel
{
  public $actsAs = array('Containable', 'Search.Searchable', 'Acl' => array('type' => 'requester'));
  public $filterArgs = array(
    'filter' => array('type' => 'query', 'method' => 'orConditions', 'encode' => true),
    'group_id' => array('type' => 'value'),
  );

  protected $_auditContext = array();
  protected $_auditBefore = array();
  public function orConditions($data = array())
  {
    $filter = $data['filter'];
    $cond = array(
      'OR' => array(
        $this->alias . '.email LIKE' => '%' . $filter . '%',
        $this->alias . '.name LIKE' => '%' . $filter . '%',
        $this->alias . '.username LIKE' => '%' . $filter . '%',
      )
    );
    return $cond;
  }

  //  The Associations below have been created with all possible keys, those that are not needed can be removed

  /**
   * Associations
   *
   * @var array
   */
  public $belongsTo = array(
    'Group' => array(
      'className' => 'Group',
      'foreignKey' => 'group_id',
      'conditions' => '',
      'fields' => '',
      'order' => ''
    ),
    'County' => array(
      'className' => 'County',
      'foreignKey' => 'county_id',
      'conditions' => '',
      'fields' => '',
      'order' => 'County.county_name DESC'
    ),
    'Country' => array(
      'className' => 'Country',
      'foreignKey' => 'country_id',
      'conditions' => '',
      'fields' => '',
    ),
  );


  public $hasMany = array(
    //  'Application' => array(
    //      'className' => 'Application',
    //      'foreignKey' => 'application_id',
    //      'dependent' => false,
    // ),
    'Review' => array(
      'className' => 'Review',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'ActiveInspector' => array(
      'className' => 'ActiveInspector',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'Notification' => array(
      'className' => 'Notification',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'Feedback' => array(
      'className' => 'Feedback',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'StudyMonitor' => array(
      'className' => 'StudyMonitor',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),
    'ProtocolOutsource' => array(
      'className' => 'ProtocolOutsource',
      'foreignKey' => 'user_id',
      'dependent' => false,
    ),


  );


  /**
   * Validation rules
   *
   * @var array
   */
  public $validate = array(
    'username' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Username required',
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'required' => 'create',
        'message' => 'The username is already in use. Please specify a different username'
      ),
    ),
    'email' => array(
      'notEmpty' => array(
        'rule'     => 'email',
        'required' => true,
        'message'  => 'Please provide a valid email address'
      ),
      'unique' => array(
        'rule' => 'isUnique',
        'required' => 'create',
        'message' => 'The email is already in use. Please specify a different email'
      ),
    ),
    'old_password' => array(
      'compareOldPasswords' => array(
        'rule' => array('compareOldPasswords'),
        'message' => 'This password does not match the old password',
        'allowEmpty' => true,
      ),
    ),
    'password' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Password cannot be empty!',
      ),
    ),
    'phone_no' => array(
      'notempty' => array(
        'rule' => array('notempty'),
        'message' => 'Please enter a valid phone number!',
      ),
    ),
    'sponsor_email' => array(
      'notEmpty' => array(
        'rule' => array('email'),
        'message' => 'Please enter the sponsor\'s email address!',
      ),
    ),
    'confirm_password' => array(
      'minLength' => array(
        'rule' => array('minLength', '6'),
        'required' => true,
        'message' => 'Your password must be at least 6 characters long',
      ),
      'notEmpty' => array(
        'rule' => 'notEmpty',
        'message' => 'Cannot be empty'
      ),
      'comparePasswords' => array(
        'rule' => array('comparePasswords'), // Protected function below
        'message' => 'Passwords do not match',
      ),
    ),
    'group_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'group must be numeric'
      ),
    ),
    // 'county_id' => array(
    //   'numeric' => array(
    //     'rule' => array('numeric'),
    //     'message' => 'Please select a valid county'
    //   ),
    // ),
    'country_id' => array(
      'numeric' => array(
        'rule' => array('numeric'),
        'message' => 'Please select a valid country'
      ),
    ),
  );
  public function comparePasswords($field = null)
  {
    return ($field['confirm_password'] === $this->data['User']['password']);
  }
  public function compareOldPasswords($field = null)
  {
    if (isset($field['old_password'])) {
      $a = $this->find('first', array('conditions' => array('User.id' => $this->data['User']['id']), 'recursive' => -1, 'fields' => 'User.password'));
      return (AuthComponent::password($field['old_password']) === $a['User']['password']);
    }
    return true;
  }

  public function beforeSave($options = array())
  {
    $this->_captureAuditBeforeState();

    if (isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    if (isset($this->data[$this->alias]['confirm_password'])) {
      $this->data[$this->alias]['confirm_password'] = AuthComponent::password($this->data[$this->alias]['confirm_password']);
    }
    return true;
  }

  public function afterSave($created, $options = array())
  {
    $this->_writeAuditTrailForUserSave((bool)$created);
    $this->_resetAuditState();
  }

  public function setAuditContext($context = array())
  {
    $this->_auditContext = is_array($context) ? $context : array();
  }

  public function parentNode()
  {
    if (!$this->id && empty($this->data)) {
      return null;
    }
    if (isset($this->data['User']['group_id'])) {
      $groupId = $this->data['User']['group_id'];
    } else {
      $groupId = $this->field('group_id');
    }
    if (!$groupId) {
      return null;
    } else {
      return array('Group' => array('id' => $groupId));
    }
  }

  // public function beforeSave($options = array()) {
  // $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
  // return true;
  // }

  protected function _captureAuditBeforeState()
  {
    $this->_auditBefore = array();
    $userId = $this->_resolveAuditUserId();
    if ($userId <= 0) {
      return;
    }

    $existing = $this->find('first', array(
      'conditions' => array($this->alias . '.' . $this->primaryKey => $userId),
      'recursive' => -1
    ));

    if (!empty($existing[$this->alias])) {
      $this->_auditBefore = $existing[$this->alias];
    }
  }

  protected function _resolveAuditUserId()
  {
    if (!empty($this->id)) {
      return (int)$this->id;
    }

    if (!empty($this->data[$this->alias][$this->primaryKey])) {
      return (int)$this->data[$this->alias][$this->primaryKey];
    }

    return 0;
  }

  protected function _writeAuditTrailForUserSave($created = false)
  {
    $userId = $this->_resolveAuditUserId();
    if ($userId <= 0) {
      return;
    }

    $saved = $this->find('first', array(
      'conditions' => array($this->alias . '.' . $this->primaryKey => $userId),
      'recursive' => -1
    ));

    if (empty($saved[$this->alias])) {
      return;
    }

    $after = $saved[$this->alias];
    $changes = $this->_buildAuditChanges($created, $this->_auditBefore, $after);
    if (!$created && empty($changes)) {
      return;
    }

    $this->_persistUserAuditTrail($created, $userId, $after, $changes);
  }

  protected function _buildAuditChanges($created, $before, $after)
  {
    $ignoredFields = array($this->primaryKey, 'created', 'modified');
    $sensitiveFields = array('password', 'confirm_password', 'old_password', 'activation_key');
    $changes = array();

    if ($created) {
      foreach ($after as $field => $value) {
        if (in_array($field, $ignoredFields, true)) {
          continue;
        }
        if ($this->_isEmptyAuditValue($value)) {
          continue;
        }
        if (in_array($field, $sensitiveFields, true)) {
          $changes[] = $field . ': [REDACTED]';
        } else {
          $changes[] = $field . ': ' . $this->_formatAuditValue($value);
        }
      }
      return $changes;
    }

    $fields = array_unique(array_merge(array_keys((array)$before), array_keys((array)$after)));
    foreach ($fields as $field) {
      if (in_array($field, $ignoredFields, true)) {
        continue;
      }

      $oldValue = array_key_exists($field, (array)$before) ? $before[$field] : null;
      $newValue = array_key_exists($field, (array)$after) ? $after[$field] : null;

      if ($this->_auditValuesEqual($oldValue, $newValue)) {
        continue;
      }

      if (in_array($field, $sensitiveFields, true)) {
        $changes[] = $field . ' changed';
      } else {
        $changes[] = $field . ': ' . $this->_formatAuditValue($oldValue) . ' -> ' . $this->_formatAuditValue($newValue);
      }
    }

    return $changes;
  }

  protected function _auditValuesEqual($left, $right)
  {
    $normalize = function ($value) {
      if ($value === null) {
        return '';
      }
      return (string)$value;
    };

    return $normalize($left) === $normalize($right);
  }

  protected function _isEmptyAuditValue($value)
  {
    return ($value === null || (is_string($value) && trim($value) === ''));
  }

  protected function _formatAuditValue($value)
  {
    if ($this->_isEmptyAuditValue($value)) {
      return '[empty]';
    }

    if (is_bool($value)) {
      return $value ? 'true' : 'false';
    }

    if (is_scalar($value)) {
      $output = (string)$value;
    } else {
      $output = json_encode($value);
      if ($output === false) {
        $output = '[complex]';
      }
    }

    $output = preg_replace('/\s+/', ' ', trim($output));
    if (strlen($output) > 100) {
      $output = substr($output, 0, 97) . '...';
    }

    return $output;
  }

  protected function _persistUserAuditTrail($created, $userId, $after, $changes)
  {
    $actorId = !empty($this->_auditContext['actor_id'])
      ? (int)$this->_auditContext['actor_id']
      : (int)CakeSession::read('Auth.User.id');
    $actorName = !empty($this->_auditContext['actor_name'])
      ? (string)$this->_auditContext['actor_name']
      : (string)CakeSession::read('Auth.User.name');
    $action = !empty($this->_auditContext['action'])
      ? (string)$this->_auditContext['action']
      : ($created ? 'User Created' : 'User Updated');

    $subjectUsername = !empty($after['username']) ? (string)$after['username'] : '';
    $byLabel = ($actorId > 0)
      ? 'by user #' . $actorId . (!empty($actorName) ? ' (' . $actorName . ')' : '')
      : 'by unauthenticated actor';

    $message = $action . ' for user #' . $userId;
    if ($subjectUsername !== '') {
      $message .= ' (' . $subjectUsername . ')';
    }
    $message .= ' ' . $byLabel;
    if (!empty($changes)) {
      $message .= '. Changes: ' . implode('; ', $changes);
    }
    if (strlen($message) > 4000) {
      $message = substr($message, 0, 3997) . '...';
    }

    try {
      $AuditTrail = ClassRegistry::init('AuditTrail');
      $AuditTrail->create();
      $AuditTrail->save(array(
        'AuditTrail' => array(
          'foreign_key' => $userId,
          'model' => 'User Profile',
          'message' => $message,
          'ip' => !empty($this->_auditContext['ip']) ? $this->_auditContext['ip'] : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''),
          'uri' => !empty($this->_auditContext['uri']) ? $this->_auditContext['uri'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''),
          'hostname' => gethostname(),
          'refer' => !empty($this->_auditContext['refer']) ? $this->_auditContext['refer'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
          'user_agent' => !empty($this->_auditContext['user_agent']) ? $this->_auditContext['user_agent'] : (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '')
        )
      ), false);
    } catch (Exception $e) {
      $this->log('User audit trail failed: ' . $e->getMessage(), 'audit_error');
    }
  }

  protected function _resetAuditState()
  {
    $this->_auditBefore = array();
    $this->_auditContext = array();
  }
}

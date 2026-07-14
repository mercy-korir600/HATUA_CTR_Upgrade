<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
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
 'AuditorProtocol' => array(
      'className' => 'AuditorProtocol',
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
    if (isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    if (isset($this->data[$this->alias]['confirm_password'])) {
      $this->data[$this->alias]['confirm_password'] = AuthComponent::password($this->data[$this->alias]['confirm_password']);
    }
    return true;
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
}

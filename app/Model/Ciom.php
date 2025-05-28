<?php
App::uses('AppModel', 'Model');
/**
 * Ciom Model
 *
 * @property Application $Application
 * @property User $User
 */
class Ciom extends AppModel {


    var $name = 'Ciom';
	var $actsAs = array('Containable', 'Media.Transfer', 'Media.Coupler', 'Media.Meta', 'Search.Searchable');
	public $filterArgs = array(
            'reference_no' => array('type' => 'like', 'encode' => true),
            'protocol_no' => array('type' => 'like', 'encode' => true),
            'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'Sae.created BETWEEN ? AND ?'),
        );
    public function makeRangeCondition($data = array()) {
            if(!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
            else $start_date = date('Y-m-d', strtotime('2012-05-01'));

            if(!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
            else $end_date = date('Y-m-d');

            return array($start_date, $end_date);
        }
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'file' => array(
			// 'resource'   => array('rule' => 'checkResource'),
			'resource'   => array(
				'rule' => 'checkResource',
				'allowEmpty' => false,
				'message' => 'Please attach an E2B compliant file!'
			),
			'access'     => array('rule' => 'checkAccess'),
			// 'location'   => array('rule' => array('checkLocation', array(
				// MEDIA_TRANSFER, '/tmp/'
			// ))),
			'permission' => array('rule' => array('checkPermission', '*')),
			'size'       => array('rule' => array('checkSize', '25M')),
			// 'pixels'     => array('rule' => array('checkPixels', '1600x1600')),  // removed image restriction
			'extension'  => array(
				'rule' => array('checkExtension', false, array('xml', 'tmp', 'Xml','XML')),
				'message' => 'Please attach a valid E2B file'),
			// 'mimeType'   => array('rule' => array('checkMimeType', false, array(
			// 	'image/jpeg', 'image/png', 'image/tiff', 'image/gif', 'application/pdf'	)))
		),
		'reporter_email' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'required' => true,
                'message'  => 'Please enter a valid email address'
            ),
        ),
		'basename' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'checksum' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Application' => array(
			'className' => 'Application',
			'foreignKey' => 'application_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

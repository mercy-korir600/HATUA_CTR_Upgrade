<?php
App::uses('AppModel', 'Model');
/**
 * ParticipantFlow Model
 *
 * @property Application $Application
 */
class ParticipantFlow extends AppModel {
	public $actsAs = array('Containable', 'Search.Searchable');

    public $filterArgs = array(
            'reference_no' => array('type' => 'like', 'encode' => true),
            'protocol_no' => array('type' => 'query', 'method' => 'findByProtocolNo', 'encode' => true),
            'range' => array('type' => 'expression', 'method' => 'makeRangeCondition', 'field' => 'ParticipantFlow.created BETWEEN ? AND ?'),
        );
    public function makeRangeCondition($data = array()) {
            if(!empty($data['start_date'])) $start_date = date('Y-m-d', strtotime($data['start_date']));
            else $start_date = date('Y-m-d', strtotime('2012-05-01'));

            if(!empty($data['end_date'])) $end_date = date('Y-m-d', strtotime($data['end_date']));
            else $end_date = date('Y-m-d');

            return array($start_date, $end_date);
    }

    public function findByProtocolNo($data = array()) {
            $cond = array($this->alias.'.application_id' => $this->Application->find('list', array(
                'conditions' => array(
                    'OR' => array(
                        'Application.protocol_no LIKE' => '%' . $data['protocol_no'] . '%',
                        'Application.protocol_no LIKE' => '%' . $data['protocol_no'] . '%', )),
                'fields' => array('id', 'id')
                    )));
            return $cond;
    }

    public function latestPerApplicationYearCondition($outerAlias = null) {
            if (empty($outerAlias)) $outerAlias = $this->alias;

            $tableName = $this->getDataSource()->fullTableName($this);

            return $outerAlias . '.id = (
                SELECT pf_latest.id
                FROM ' . $tableName . ' pf_latest
                WHERE pf_latest.application_id = ' . $outerAlias . '.application_id
                  AND pf_latest.year = ' . $outerAlias . '.year
                ORDER BY pf_latest.created DESC, pf_latest.id DESC
                LIMIT 1
            )';
    }
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'application_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
		)
	);
}

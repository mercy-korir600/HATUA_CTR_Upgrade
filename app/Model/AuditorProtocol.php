<?php
App::uses('AppModel', 'Model');

class AuditorProtocol extends AppModel
{
    public $actsAs = array('Containable', 'Search.Searchable');
    
    public $filterArgs = array(
        'protocol_no' => array('type' => 'like', 'encode' => true),
        'filter' => array('type' => 'query', 'method' => 'orConditions', 'encode' => true),
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

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'Application' => array(
            'className' => 'Application',
            'foreignKey' => 'application_id',
        )
    );
}
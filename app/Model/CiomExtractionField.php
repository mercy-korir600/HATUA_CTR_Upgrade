<?php
App::uses('AppModel', 'Model');

/**
 * CiomExtractionField Model
 *
 * Stores flattened E2B XML nodes/attributes for human-readable display
 * and downstream processing.
 */
class CiomExtractionField extends AppModel
{
    public $name = 'CiomExtractionField';

    public $belongsTo = array(
        'Ciom' => array(
            'className' => 'Ciom',
            'foreignKey' => 'ciom_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}

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

	public $hasMany = array(
		'CiomExtractionField' => array(
			'className' => 'CiomExtractionField',
			'foreignKey' => 'ciom_id',
			'dependent' => true
		)
	);

	public function rebuildE2bExtraction($ciomId, $xmlContent) {
		$result = array(
			'saved' => 0,
			'is_r3' => false,
			'version' => null,
			'error' => null
		);

		$ciomId = (int) $ciomId;
		if ($ciomId <= 0) {
			$result['error'] = 'invalid_ciom';
			return $result;
		}

		$this->CiomExtractionField->deleteAll(array('CiomExtractionField.ciom_id' => $ciomId), false);

		if (trim((string) $xmlContent) === '') {
			$result['error'] = 'empty_xml';
			return $result;
		}

		$dom = new DOMDocument('1.0', 'UTF-8');
		libxml_use_internal_errors(true);
		$loaded = $dom->loadXML($xmlContent, LIBXML_NONET | LIBXML_NOBLANKS);
		if (!$loaded || empty($dom->documentElement)) {
			libxml_clear_errors();
			$result['error'] = 'invalid_xml';
			return $result;
		}
		libxml_clear_errors();

		$root = $dom->documentElement;
		$result['is_r3'] = $this->_detectR3FromRoot($root);
		$result['version'] = $result['is_r3'] ? 'R3' : 'R2';

		$rows = array();
		$sequence = 0;
		$rootName = !empty($root->localName) ? $root->localName : $root->nodeName;
		$rootPath = '/' . $rootName . '[1]';
		$this->_flattenDomElement($ciomId, $root, $rootPath, $rows, $sequence, $result['version']);

		if (empty($rows)) {
			return $result;
		}

		foreach (array_chunk($rows, 200) as $chunk) {
			$this->CiomExtractionField->create();
			$saved = $this->CiomExtractionField->saveMany(
				$chunk,
				array('validate' => false, 'atomic' => false, 'deep' => false)
			);
			if ($saved === false) {
				$result['error'] = 'save_failed';
				return $result;
			}
		}

		$result['saved'] = count($rows);
		return $result;
	}

	public function getExtractionRows($ciomId, $limit = 3000) {
		$ciomId = (int) $ciomId;
		if ($ciomId <= 0) {
			return array();
		}

		return $this->CiomExtractionField->find('all', array(
			'conditions' => array('CiomExtractionField.ciom_id' => $ciomId),
			'fields' => array(
				'CiomExtractionField.id',
				'CiomExtractionField.field_path',
				'CiomExtractionField.field_key',
				'CiomExtractionField.field_label',
				'CiomExtractionField.field_value',
				'CiomExtractionField.source_format',
				'CiomExtractionField.version',
				'CiomExtractionField.sequence'
			),
			'order' => array('CiomExtractionField.sequence' => 'ASC', 'CiomExtractionField.id' => 'ASC'),
			'limit' => (int) $limit,
			'contain' => array()
		));
	}

	public function isE2bR3($xmlContent = '') {
		if (trim((string) $xmlContent) === '') {
			return false;
		}
		$dom = new DOMDocument('1.0', 'UTF-8');
		libxml_use_internal_errors(true);
		$loaded = $dom->loadXML($xmlContent, LIBXML_NONET | LIBXML_NOBLANKS);
		if (!$loaded || empty($dom->documentElement)) {
			libxml_clear_errors();
			return false;
		}
		libxml_clear_errors();
		return $this->_detectR3FromRoot($dom->documentElement);
	}

	private function _detectR3FromRoot($root) {
		$rootName = strtoupper((string) (!empty($root->localName) ? $root->localName : $root->nodeName));
		$namespace = strtolower((string) $root->namespaceURI);
		if ($rootName === 'MCCI_IN200100UV01') {
			return true;
		}
		if (!empty($namespace) && strpos($namespace, 'hl7-org:v3') !== false) {
			return true;
		}
		return false;
	}

	private function _flattenDomElement($ciomId, $element, $path, &$rows, &$sequence, $version) {
		$elementName = !empty($element->localName) ? $element->localName : $element->nodeName;
		$parentPath = preg_replace('/\/[^\/]+$/', '', $path);
		$now = date('Y-m-d H:i:s');

		if ($element->hasAttributes()) {
			foreach ($element->attributes as $attribute) {
				$attributeName = !empty($attribute->localName) ? $attribute->localName : $attribute->nodeName;
				$attributePath = $path . '/@' . $attributeName;
				$attributeValue = trim((string) $attribute->nodeValue);
				if ($attributeValue !== '') {
					$rows[] = array(
						'CiomExtractionField' => array(
							'ciom_id' => $ciomId,
							'source_format' => 'E2B',
							'version' => $version,
							'field_path' => $attributePath,
							'parent_path' => $path,
							'field_key' => '@' . $attributeName,
							'field_label' => $this->_humanizeField($attributeName),
							'field_value' => $attributeValue,
							'sequence' => ++$sequence,
							'created' => $now,
							'modified' => $now
						)
					);
				}
			}
		}

		$hasElementChildren = $this->_hasElementChildren($element);
		if (!$hasElementChildren) {
			$textValue = trim((string) $element->textContent);
			if ($textValue !== '') {
				$textValue = preg_replace('/\s+/u', ' ', $textValue);
				$rows[] = array(
					'CiomExtractionField' => array(
						'ciom_id' => $ciomId,
						'source_format' => 'E2B',
						'version' => $version,
						'field_path' => $path,
						'parent_path' => $parentPath,
						'field_key' => $elementName,
						'field_label' => $this->_humanizeField($elementName),
						'field_value' => $textValue,
						'sequence' => ++$sequence,
						'created' => $now,
						'modified' => $now
					)
				);
			}
		}

		$childIndexes = array();
		foreach ($element->childNodes as $childNode) {
			if ($childNode->nodeType !== XML_ELEMENT_NODE) {
				continue;
			}
			$childName = !empty($childNode->localName) ? $childNode->localName : $childNode->nodeName;
			if (!isset($childIndexes[$childName])) {
				$childIndexes[$childName] = 0;
			}
			$childIndexes[$childName]++;
			$childPath = $path . '/' . $childName . '[' . $childIndexes[$childName] . ']';
			$this->_flattenDomElement($ciomId, $childNode, $childPath, $rows, $sequence, $version);
		}
	}

	private function _hasElementChildren($element) {
		foreach ($element->childNodes as $childNode) {
			if ($childNode->nodeType === XML_ELEMENT_NODE) {
				return true;
			}
		}
		return false;
	}

	private function _humanizeField($fieldName) {
		$label = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', (string) $fieldName);
		$label = str_replace(array('_', '-'), ' ', $label);
		$label = trim(preg_replace('/\s+/', ' ', $label));
		return ucwords(strtolower($label));
	}
}

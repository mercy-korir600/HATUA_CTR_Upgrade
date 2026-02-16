<?php
App::uses('AppModel', 'Model');

class DeletionSetting extends AppModel
{
    const DEFAULT_MONTHS = 3;

    public $validate = array(
        'duration_months' => array(
            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'message' => 'Please enter a valid number of months.'
            )
        )
    );

    public function ensureTable()
    {
        $tableName = $this->tablePrefix . $this->useTable;
        $sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `duration_months` int(11) unsigned NOT NULL DEFAULT '3',
            `created_by` int(11) DEFAULT NULL,
            `modified_by` int(11) DEFAULT NULL,
            `created` datetime DEFAULT NULL,
            `modified` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        try {
            $this->query($sql);
            return true;
        } catch (Exception $e) {
            $this->log('Failed to create deletion_settings table: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    public function getCurrentMonths($fallback = null)
    {
        $defaultMonths = (int)$fallback;
        if ($defaultMonths < 1) {
            $defaultMonths = self::DEFAULT_MONTHS;
        }

        if (!$this->ensureTable()) {
            return $defaultMonths;
        }

        try {
            $setting = $this->find('first', array(
                'fields' => array($this->alias . '.duration_months'),
                'order' => array($this->alias . '.id' => 'DESC'),
                'recursive' => -1
            ));
        } catch (Exception $e) {
            $this->log('Failed reading deletion settings: ' . $e->getMessage(), 'error');
            return $defaultMonths;
        }

        if (!empty($setting[$this->alias]['duration_months']) && (int)$setting[$this->alias]['duration_months'] > 0) {
            return (int)$setting[$this->alias]['duration_months'];
        }

        return $defaultMonths;
    }

    public function saveCurrentMonths($months, $userId = null)
    {
        $months = (int)$months;
        if ($months < 1 || !$this->ensureTable()) {
            return false;
        }

        $existing = $this->find('first', array(
            'fields' => array($this->alias . '.id'),
            'order' => array($this->alias . '.id' => 'DESC'),
            'recursive' => -1
        ));

        $data = array(
            $this->alias => array(
                'duration_months' => $months
            )
        );
        $fields = array('duration_months');

        if (!empty($existing[$this->alias]['id'])) {
            $data[$this->alias]['id'] = $existing[$this->alias]['id'];
            $fields[] = 'id';
            if (!empty($userId) && $this->hasField('modified_by')) {
                $data[$this->alias]['modified_by'] = $userId;
                $fields[] = 'modified_by';
            }
        } else {
            $this->create();
            if (!empty($userId) && $this->hasField('created_by')) {
                $data[$this->alias]['created_by'] = $userId;
                $fields[] = 'created_by';
            }
            if (!empty($userId) && $this->hasField('modified_by')) {
                $data[$this->alias]['modified_by'] = $userId;
                $fields[] = 'modified_by';
            }
        }

        return (bool)$this->save($data, true, array_unique($fields));
    }
}

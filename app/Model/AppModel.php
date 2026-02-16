<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');
App::uses('ClassRegistry', 'Utility');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	const AUTO_DELETION_PERIOD_DEFAULT_MONTHS = 3;

		
	function dateFormatAfterFind($dateString) {
		return date('d-m-Y', strtotime($dateString));
	}

	public function dateFormatBeforeSave($dateString) {
		return date('Y-m-d', strtotime($dateString));
	}
	
	function dateTimeFormatAfterFind($dateString) {
		return date('d-m-Y H:i', strtotime($dateString));
	}

	public function dateTimeFormatBeforeSave($dateString) {
		return date('Y-m-d H:i', strtotime($dateString));
	}

	public static function getAutoDeletionPeriodMonths($defaultMonths = null) {
		$fallback = (int) $defaultMonths;
		if ($fallback < 1) {
			$fallback = self::AUTO_DELETION_PERIOD_DEFAULT_MONTHS;
		}

		$DeletionSetting = ClassRegistry::init('DeletionSetting');
		if (empty($DeletionSetting)) {
			return $fallback;
		}

		if (method_exists($DeletionSetting, 'ensureTable') && !$DeletionSetting->ensureTable()) {
			return $fallback;
		}

		if (method_exists($DeletionSetting, 'getCurrentMonths')) {
			try {
				$months = (int)$DeletionSetting->getCurrentMonths($fallback);
				return ($months > 0) ? $months : $fallback;
			} catch (Exception $e) {
				return $fallback;
			}
		}

		return $fallback;
	}

	public static function getAutoDeletionExpirationString($defaultMonths = null) {
		$months = self::getAutoDeletionPeriodMonths($defaultMonths);
		return '-' . (int) $months . ' months';
	}
}

<?php
namespace OliverHader\Interceptor\Hook\Backend;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use OliverHader\Interceptor\Service\RegistryService;
use OliverHader\Interceptor\Service\MonitorService;

/**
 * Class DataHandlerHook
 * @package OliverHader\Interceptor\Hook\Backend
 */
class DataHandlerHook implements SingletonInterface {

	/**
	 * @return string
	 */
	static public function className() {
		return __CLASS__;
	}

	/**
	 * @param string $status
	 * @param string $table
	 * @param int $id
	 * @complete processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, DataHandler $dataHandler)
	 */
	public function processDatamap_afterDatabaseOperations($status, $table, $id) {
		if ($status === 'new') {
			$this->forward('insert', $table, $id);
		}
		if ($status === 'update') {
			$this->forward('update', $table, $id);
		}
	}

	/**
	 * @param string $table
	 * @param int $uid
	 * @complete moveRecord($table, $uid, $destPid, array $propArr, array $moveRec, $resolvedPid, $recordWasMoved, DataHandler $dataHandler)
	 */
	public function moveRecord($table, $uid) {
		$this->forward('update', $table, $uid);

	}

	/**
	 * @param string $table
	 * @param int $id
	 * @complete processCmdmap_deleteAction($table, $id, array $recordToDelete, $recordWasDeleted, DataHandler $dataHandler)
	 */
	public function processCmdmap_deleteAction($table, $id) {
		$this->forward('remove', $table, $id);

	}

	/**
	 * @param string $type
	 * @param string $tableName
	 * @param int $identifier
	 * @return bool
	 */
	protected function forward($type, $tableName, $identifier) {
		if (empty($tableName) || empty($identifier)) {
			return FALSE;
		}

		/**
		 * @see MonitorService::getFetchCollection
		 * @see MonitorService::getUpdateCollection
		 * @see MonitorService::getInsertCollection
		 * @see MonitorService::getRemoveCollection
		 */
		$monitorMethod = 'get' . ucfirst($type) . 'Collection';
		MonitorService::instance()->{$monitorMethod}()->append($tableName, $identifier);

		return TRUE;
	}

}
<?php
namespace OliverHader\Interceptor;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use OliverHader\Interceptor\Hook\Backend\DataHandlerHook;
use OliverHader\Interceptor\Hook\Frontend\PersistenceBackendHook;

/**
 * Class Bootstrap
 * @package OliverHader\Interceptor
 */
class Bootstrap {

	const EXTENSION_Key = 'interceptor';
	const EXTENSION_Name = 'Interceptor';

	static public function registerSlots() {
		// Slots watching the Extbase Persistence Backend for accordant actions
		static::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend', 'afterGettingObjectData',
			PersistenceBackendHook::className(), 'interceptFetch'
		);
		static::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend', 'afterUpdateObject',
			PersistenceBackendHook::className(), 'interceptUpdate'
		);
		static::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend', 'afterInsertObject',
			PersistenceBackendHook::className(), 'interceptInsert'
		);
		static::getSignalSlotDispatcher()->connect(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend', 'afterRemoveObject',
			PersistenceBackendHook::className(), 'interceptRemove'
		);
	}

	static public function registerHooks() {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['interceptor'] =
			DataHandlerHook::className();
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['moveRecordClass']['interceptor'] =
			DataHandlerHook::className();
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['interceptor'] =
			DataHandlerHook::className();

		// @todo Frontend Finish actions (-> write and clear caches)
	}

	/**
	 * @return string
	 */
	static public function getPath() {
		return \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath(static::EXTENSION_Key);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory
	 */
	static public function getDataMapFactory() {
		return static::getObjectManager()->get(
			'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Mapper\\DataMapFactory'
		);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	static public function getObjectManager() {
		return GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Extbase\\Object\\ObjectManager'
		);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	static public function getSignalSlotDispatcher() {
		return GeneralUtility::makeInstance(
			'TYPO3\\CMS\\Extbase\\SignalSlot\Dispatcher'
		);
	}

	/**
	 * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	static public function getFrontendController() {
		return $GLOBALS['TSFE'];
	}

}
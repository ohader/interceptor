<?php
namespace OliverHader\Interceptor\Service;

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
use OliverHader\Interceptor\Domain\Object\TableDefinitionCollection;
use OliverHader\Interceptor\Bootstrap;

/**
 * Class RegistryService
 * @package OliverHader\Interceptor\Service
 */
class RegistryService implements SingletonInterface {

	/**
	 * @return RegistryService
	 */
	static public function instance() {
		return Bootstrap::getObjectManager()->get(__CLASS__);
	}

	/**
	 * @var TableDefinitionCollection
	 */
	protected $fetchCollection;

	/**
	 * @var TableDefinitionCollection
	 */
	protected $updateCollection;

	/**
	 * @var TableDefinitionCollection
	 */
	protected $insertCollection;

	/**
	 * @var TableDefinitionCollection
	 */
	protected $removeCollection;

	/**
	 * Creates this object.
	 */
	public function __construct() {
		$this->fetchCollection = TableDefinitionCollection::instance();
		$this->updateCollection = TableDefinitionCollection::instance();
		$this->insertCollection = TableDefinitionCollection::instance();
		$this->removeCollection = TableDefinitionCollection::instance();
	}

	/**
	 * @param string $tableName
	 * @param NULL|callable $callback
	 */
	public function onAll($tableName, $callback = NULL) {
		$this->fetchCollection->append($tableName, $callback);
		$this->updateCollection->append($tableName, $callback);
		$this->insertCollection->append($tableName, $callback);
		$this->removeCollection->append($tableName, $callback);
	}

	/**
	 * @param string $tableName
	 * @param NULL|callable $callback
	 */
	public function onFetch($tableName, $callback = NULL) {
		$this->fetchCollection->append($tableName, $callback);
	}

	/**
	 * @param string $tableName
	 * @param NULL|callable $callback
	 */
	public function onModify($tableName, $callback = NULL) {
		$this->updateCollection->append($tableName, $callback);
		$this->insertCollection->append($tableName, $callback);
		$this->removeCollection->append($tableName, $callback);
	}

	/**
	 * @return TableDefinitionCollection
	 */
	public function getFetchCollection() {
		return $this->fetchCollection;
	}

	/**
	 * @return TableDefinitionCollection
	 */
	public function getUpdateCollection() {
		return $this->updateCollection;
	}

	/**
	 * @return TableDefinitionCollection
	 */
	public function getInsertCollection() {
		return $this->insertCollection;
	}

	/**
	 * @return TableDefinitionCollection
	 */
	public function getRemoveCollection() {
		return $this->removeCollection;
	}

}
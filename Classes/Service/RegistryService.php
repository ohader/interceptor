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
use OliverHader\Interceptor\Domain\Object\TableNameCollection;
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
	 * @var TableNameCollection
	 */
	protected $fetchCollection;

	/**
	 * @var TableNameCollection
	 */
	protected $updateCollection;

	/**
	 * @var TableNameCollection
	 */
	protected $insertCollection;

	/**
	 * @var TableNameCollection
	 */
	protected $removeCollection;

	/**
	 * Creates this object.
	 */
	public function __construct() {
		$this->fetchCollection = TableNameCollection::instance();
		$this->updateCollection = TableNameCollection::instance();
		$this->insertCollection = TableNameCollection::instance();
		$this->removeCollection = TableNameCollection::instance();
	}

	/**
	 * @param string $tableName
	 */
	public function add($tableName) {
		$this->fetchCollection->append($tableName);
		$this->updateCollection->append($tableName);
		$this->insertCollection->append($tableName);
		$this->removeCollection->append($tableName);
	}

	/**
	 * @return TableNameCollection
	 */
	public function getFetchCollection() {
		return $this->fetchCollection;
	}

	/**
	 * @return TableNameCollection
	 */
	public function getUpdateCollection() {
		return $this->updateCollection;
	}

	/**
	 * @return TableNameCollection
	 */
	public function getInsertCollection() {
		return $this->insertCollection;
	}

	/**
	 * @return TableNameCollection
	 */
	public function getRemoveCollection() {
		return $this->removeCollection;
	}

}
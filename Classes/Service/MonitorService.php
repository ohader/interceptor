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
use OliverHader\Interceptor\Domain\Object\TableIdentifierCollection;
use OliverHader\Interceptor\Bootstrap;

/**
 * Class MonitorService
 * @package OliverHader\Interceptor\Service
 */
class MonitorService implements SingletonInterface {

	/**
	 * @return MonitorService
	 */
	static public function instance() {
		return Bootstrap::getObjectManager()->get(__CLASS__);
	}

	/**
	 * @var TableIdentifierCollection
	 */
	protected $fetchCollection;

	/**
	 * @var TableIdentifierCollection
	 */
	protected $updateCollection;

	/**
	 * @var TableIdentifierCollection
	 */
	protected $insertCollection;

	/**
	 * @var TableIdentifierCollection
	 */
	protected $removeCollection;

	/**
	 * Creates this object.
	 */
	public function __construct() {
		$registryService = RegistryService::instance();

		$this->fetchCollection = TableIdentifierCollection::instance()
			->setAsk($registryService->getFetchCollection());
		$this->updateCollection = TableIdentifierCollection::instance()
			->setAsk($registryService->getUpdateCollection());
		$this->insertCollection = TableIdentifierCollection::instance()
			->setAsk($registryService->getInsertCollection());
		$this->removeCollection = TableIdentifierCollection::instance()
			->setAsk($registryService->getRemoveCollection());
	}

	/**
	 * @return TableIdentifierCollection
	 */
	public function getFetchCollection() {
		return $this->fetchCollection;
	}

	/**
	 * @return TableIdentifierCollection
	 */
	public function getUpdateCollection() {
		return $this->updateCollection;
	}

	/**
	 * @return TableIdentifierCollection
	 */
	public function getInsertCollection() {
		return $this->insertCollection;
	}

	/**
	 * @return TableIdentifierCollection
	 */
	public function getRemoveCollection() {
		return $this->removeCollection;
	}


}
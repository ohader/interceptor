<?php
namespace OliverHader\Interceptor\Hook\Frontend;

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
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;

/**
 * Class PersistenceBackendHook
 * @package OliverHader\Interceptor\Hook\Frontend
 */
class PersistenceBackendHook implements SingletonInterface {

	/**
	 * @return string
	 */
	static public function className() {
		return __CLASS__;
	}

	/**
	 * @inject
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory
	 */
	protected $dataMapFactory;

	/**
	 * @inject
	 * @var \OliverHader\Interceptor\Service\RegistryService
	 */
	protected $registryService;

	/**
	 * @inject
	 * @var \OliverHader\Interceptor\Service\MonitorService
	 */
	protected $monitorService;

	/**
	 * @param QueryInterface $query
	 * @param array $result
	 */
	public function interceptFetch(QueryInterface $query, array $result) {
		$this->forward('fetch', $query, $result);
	}

	/**
	 * @param DomainObjectInterface $object
	 */
	public function interceptUpdate(DomainObjectInterface $object) {
		$this->forward('update', $object);
	}

	/**
	 * @param DomainObjectInterface $object
	 */
	public function interceptInsert(DomainObjectInterface $object) {
		$this->forward('insert', $object);
	}

	/**
	 * @param DomainObjectInterface $object
	 */
	public function interceptRemove(DomainObjectInterface $object) {
		$this->forward('remove', $object);
	}

	/**
	 * @param string $type
	 * @param QueryInterface|DomainObjectInterface $subject
	 * @param NULL|array $result
	 * @return bool
	 */
	protected function forward($type, $subject, array $result = NULL) {
		$arguments = $this->determine($subject, $result);

		if ($arguments === NULL) {
			return FALSE;
		}

		/**
		 * @see RegistryService::getFetchCollection
		 * @see RegistryService::getUpdateCollection
		 * @see RegistryService::getInsertCollection
		 * @see RegistryService::getRemoveCollection
		 */
		$registryMethod = 'get' . ucfirst($type) . 'Collection';
		if (!$this->registryService->{$registryMethod}()->has($arguments['tableName'])) {
			return FALSE;
		}

		/**
		 * @see MonitorService::getFetchCollection
		 * @see MonitorService::getUpdateCollection
		 * @see MonitorService::getInsertCollection
		 * @see MonitorService::getRemoveCollection
		 */
		$monitorMethod = 'get' . ucfirst($type) . 'Collection';
		foreach ($arguments['identifiers'] as $identifier) {
			$this->monitorService->{$monitorMethod}()->append($arguments['tableName'], $identifier);
		}

		return TRUE;
	}

	/**
	 * @param QueryInterface|DomainObjectInterface $subject
	 * @param NULL|array $result
	 * @return NULL|array|string[]|array[]
	 */
	protected function determine($subject, array $result = NULL) {
		$className = NULL;
		$identifiers = array();

		if ($subject instanceof QueryInterface) {
			$className = $subject->getType();
			foreach ($result as $item) {
				$identifiers[] = $item['uid'];
			}
		} elseif ($subject instanceof DomainObjectInterface) {
			$className = get_class($subject);
			$identifiers[] = $subject->getUid();
		}

		if ($className === NULL || count($identifiers) === 0) {
			return NULL;
		}

		return array(
			'tableName' => $this->dataMapFactory->buildDataMap($className)->getTableName(),
			'identifiers' => $identifiers,
		);
	}

}
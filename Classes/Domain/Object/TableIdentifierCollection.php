<?php
namespace OliverHader\Interceptor\Domain\Object;

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
use OliverHader\Interceptor\Bootstrap;

/**
 * Class TableIdentifierCollection
 * @package OliverHader\Interceptor\Service
 */
class TableIdentifierCollection extends \ArrayObject {

	/**
	 * @return TableIdentifierCollection
	 */
	static public function instance() {
		return Bootstrap::getObjectManager()->get(__CLASS__);
	}

	/**
	 * @var TableDefinitionCollection
	 */
	protected $ask;

	/**
	 * Overrides default constructor.
	 */
	public function __construct() {
	}

	/**
	 * @param TableDefinitionCollection $tableDefinitionCollection
	 * @return TableIdentifierCollection
	 */
	public function setAsk(TableDefinitionCollection $tableDefinitionCollection) {
		$this->ask = $tableDefinitionCollection;
		return $this;
	}

	/**
	 * @param string $tableName
	 * @param int $identifier
	 */
	public function append($tableName, $identifier) {
		// Ask, whether to append the identifier
		if ($this->ask !== NULL && !$this->ask->has($tableName)) {
			return;
		}

		$this->get($tableName)->append($identifier);

		// Delegate to callbacks (if any)
		if ($this->ask !== NULL) {
			$callbackParameters = array(
				'tableName' => $tableName,
				'identifier' => $identifier,
			);
			foreach ($this->ask->get($tableName)->getCallbacks() as $callback) {
				GeneralUtility::callUserFunction($callback, $callbackParameters, $this);
			}
		}
	}

	/**
	 * @param string $tableName
	 * @return NULL|IdentifierCollection
	 */
	public function get($tableName) {
		if ($this->ask !== NULL && !$this->ask->has($tableName)) {
			return NULL;
		}

		if (!$this->has($tableName)) {
			$this->offsetSet($tableName, IdentifierCollection::instance($tableName));
		}

		return $this->offsetGet($tableName);
	}

	/**
	 * @param string $tableName
	 * @return bool
	 */
	public function has($tableName) {
		return $this->offsetExists($tableName);
	}

}
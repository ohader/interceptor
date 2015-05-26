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

/**
 * Class TableDefinitionCollection
 * @package OliverHader\Interceptor\Service
 */
class TableDefinitionCollection extends \ArrayObject {

	/**
	 * @return TableDefinitionCollection
	 */
	static public function instance() {
		return GeneralUtility::makeInstance(__CLASS__);
	}

	/**
	 * Overrides default constructor.
	 */
	public function __construct() {
	}

	/**
	 * @param string $tableName
	 * @param NULL|callable $callback
	 */
	public function append($tableName, $callback = NULL) {
		$this->get($tableName)->getCallbacks()->append($callback);
	}

	/**
	 * @param string $tableName
	 * @return NULL|TableDefinition
	 */
	public function get($tableName) {
		if (!$this->has($tableName)) {
			$this->offsetSet($tableName, TableDefinition::instance($tableName));
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
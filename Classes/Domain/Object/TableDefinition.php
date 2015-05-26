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

use OliverHader\Interceptor\Bootstrap;

/**
 * Class TableDefinitionCollection
 * @package OliverHader\Interceptor\Service
 */
class TableDefinition {

	/**
	 * @param string $tableName
	 * @return TableDefinition
	 */
	static public function instance($tableName) {
		return Bootstrap::getObjectManager()->get(__CLASS__, $tableName);
	}

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @var CallbackCollection|callable[]
	 */
	protected $callbacks;

	/**
	 * @param string $tableName
	 */
	public function __construct($tableName) {
		$this->setTableName($tableName);
		$this->callbacks = CallbackCollection::instance();
	}

	/**
	 * @return string
	 */
	public function getTableName() {
		return $this->tableName;
	}

	/**
	 * @param string $tableName
	 */
	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * @return CallbackCollection|callable[]
	 */
	public function getCallbacks() {
		return $this->callbacks;
	}

}
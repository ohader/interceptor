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
	 * @var TableNameCollection
	 */
	protected $ask;

	/**
	 * Overrides default constructor.
	 */
	public function __construct() {
	}

	/**
	 * @param TableNameCollection $tableNameCollection
	 * @return TableIdentifierCollection
	 */
	public function setAsk(TableNameCollection $tableNameCollection) {
		$this->ask = $tableNameCollection;
		return $this;
	}

	/**
	 * @param string $tableName
	 * @param int $identifier
	 */
	public function append($tableName, $identifier) {
		if ($this->ask !== NULL && $this->ask->has($tableName)) {
			$this->get($tableName)->append($identifier);
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
		return ($this->offsetExists($tableName));
	}

}
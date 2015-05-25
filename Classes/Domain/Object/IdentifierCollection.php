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
 * Class IdentifierCollection
 * @package OliverHader\Interceptor\Service
 */
class IdentifierCollection extends \ArrayObject {

	/**
	 * @param string $tableName
	 * @return IdentifierCollection
	 */
	static public function instance($tableName) {
		return Bootstrap::getObjectManager()->get(__CLASS__, $tableName);
	}

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * Overrides default constructor.
	 */
	public function __construct($tableName) {
		$this->setTableName($tableName);
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
	 * @param int $identifier
	 * @return bool
	 */
	public function has($identifier) {
		return (in_array($identifier, $this->getArrayCopy()));
	}

	/**
	 * @param string $prefix
	 * @return array
	 */
	public function getPrefixedArrayCopy($prefix) {
		$arrayCopy = $this->getArrayCopy();

		foreach ($arrayCopy as &$arrayItem) {
			$arrayItem = $prefix . $arrayItem;
		}

		return $arrayCopy;
	}

}
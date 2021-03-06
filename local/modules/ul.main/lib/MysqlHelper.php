<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 10.08.2016
 * Time: 12:59
 */

namespace UL\Main;

use Bitrix\Main\Entity;
use Bitrix\Main\ArgumentException;

class MysqlHelper
{
	/** @var  Entity\Base */
	protected $entity;

	/**
	 * @method createTable
	 * @param Entity\Base $entity
	 * @param array $indexes
	 */
	public static function createTable(Entity\Base $entity, $indexes = [])
	{
		$ob = new static();
		$ob->entity = $entity;

		$connect = $ob->entity->getConnection();
		$tblName = $ob->entity->getDBTableName();

		if(!$connect->isTableExists($tblName)){
			$dump = $ob->compileDbTableStructureDump();
			if(count($indexes) > 0 && $connect->isTableExists($tblName)){
				foreach ($indexes as $index => $cols) {
					$connect->createIndex($tblName, $index, $cols);
				}
			}
		}
	}

	/**
	 * @method compileDbTableStructureDump
	 * @return null|\string[]
	 */
	public function compileDbTableStructureDump()
	{
		$fields = $this->entity->getScalarFields();
		$connection = $this->entity->getConnection();

		$autocomplete = array();

		foreach ($fields as $field)
		{
			if ($field->isAutocomplete())
			{
				$autocomplete[] = $field->getName();
			}
		}

		// start collecting queries
//		$connection->disableQueryExecuting();

		// create table
		$this->createTableMysql($this->entity->getDBTableName(), $fields, $this->entity->getPrimaryArray(), $autocomplete);
		// stop collecting queries
//		$connection->enableQueryExecuting();

		return $connection->getDisabledQueryExecutingDump();
	}

	/**
	 * @method createTableMysql
	 * @param $tableName
	 * @param $fields
	 * @param array $primary
	 * @param array $autoincrement
	 *
	 * @throws ArgumentException
	 */
	public function createTableMysql($tableName, $fields, $primary = array(), $autoincrement = array())
	{
		$connection = $this->entity->getConnection();
		$configuration = $connection->getConfiguration();
		$engine = isset($configuration['engine']) ? $configuration['engine'] : "";

		$sql = 'CREATE TABLE '.$connection->getSqlHelper()->quote($tableName).' (';
		$sqlFields = array();

		foreach ($fields as $columnName => $field)
		{
			if (!($field instanceof Entity\ScalarField))
			{
				throw new ArgumentException(sprintf(
					'Field `%s` should be an Entity\ScalarField instance', $columnName
				));
			}

			$realColumnName = $field->getColumnName();

			$sqlFields[] = $connection->getSqlHelper()->quote($realColumnName)
				. ' ' . $connection->getSqlHelper()->getColumnTypeByField($field)
				. ' ' .(in_array($columnName, $primary, true) ? 'NOT NULL' : 'NULL')
				. (in_array($columnName, $autoincrement, true) ? ' AUTO_INCREMENT' : '')
			;
		}

		$sql .= join(', ', $sqlFields);

		if (!empty($primary))
		{
			foreach ($primary as &$primaryColumn)
			{
				$realColumnName = $fields[$primaryColumn]->getColumnName();
				$primaryColumn = $connection->getSqlHelper()->quote($realColumnName);
			}

			$sql .= ', PRIMARY KEY('.join(', ', $primary).')';
		}

		$sql .= ')';
		$connection->getConfiguration();
		if ($engine)
		{
			$sql .= ' Engine='.$engine;
		}

		$connection->queryExecute($sql);
	}

}
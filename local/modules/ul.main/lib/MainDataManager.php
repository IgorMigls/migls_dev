<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 23.11.2016
 */

namespace UL\Main;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class MainDataManager extends Entity\DataManager
{
	protected static $loc_prefix = '';

	/**
	 * @method getTitleField
	 * @param $fieldName
	 *
	 * @return string
	 */
	public static function getTitleField($fieldName)
	{
		if(strlen(static::$loc_prefix) == 0){
			$entityName = static::getEntity()->getFullName();
			$entityName = str_replace('\\', '_', $entityName);
			static::$loc_prefix = strtoupper('ENTITY_'.$entityName.'_FIELD_');
		}
		return Loc::getMessage(static::$loc_prefix.$fieldName);
	}

	/**
	 * @method getUser
	 * @return array|bool|\CDBResult|\CUser|mixed
	 */
	public static function getUser()
	{
		global $USER;
		if(!$USER instanceof \CUser){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method getIndexes
	 * @return array
	 */
	protected static function getIndexes()
	{
		return array();
	}

	/**
	 * @method createTable
	 */
	public static function createTable()
	{
		$tableName = static::getTableName();
		$connect = static::getEntity()->getConnection();
		if(!is_null($tableName) && !$connect->isTableExists($tableName)){
			MysqlHelper::createTable(static::getEntity(), static::getIndexes());
		}
	}

	/**
	 * @method clearTable
	 */
	public static function clearTable()
	{
		$tableName = static::getTableName();
		$connect = static::getEntity()->getConnection();
		if(!is_null($tableName) && $connect->isTableExists($tableName)){
			$connect->truncateTable($tableName);
		}
	}

	/**
	 * @method dropTable
	 */
	public static function dropTable()
	{
		$tableName = static::getTableName();
		$connect = static::getEntity()->getConnection();
		if(!is_null($tableName) && $connect->isTableExists($tableName)){
			$connect->dropTable($tableName);
		}
	}

}
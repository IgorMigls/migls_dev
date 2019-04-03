<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 06.09.2016
 * Time: 14:45
 */

namespace UL\Main\Import\Model;

use Bitrix\Main\Entity;

class ComparePropTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_import_compare_prop';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'IBLOCK_ID' => new Entity\IntegerField('IBLOCK_ID', array(
				'required' => true,
			)),
			'PROPERTY_ID' => new Entity\IntegerField('PROPERTY_ID', array(
				'required' => true,
			)),
			'PROPERTY_CODE' => new Entity\StringField('PROPERTY_CODE'),
			'PROPERTY_NAME' => new Entity\StringField('PROPERTY_NAME'),
			'PROPERTY_IMPORT' => new  Entity\StringField('PROPERTY_IMPORT', array(
				'required' => true,
			)),
			'PROFILE_ID' => new Entity\IntegerField('PROFILE_ID')
		];
	}

	/**
	 * @method createTable
	 */
	public static function createTable()
	{
		\UL\Main\MysqlHelper::createTable(self::getEntity(), [
			'ix_import_iblock_id' => ['IBLOCK_ID'],
			'ix_import_prop_id' => ['PROPERTY_ID'],
			'ix_import_code' => ['PROPERTY_CODE'],
			'ix_import_name_prop' => ['PROPERTY_IMPORT']
		]);
	}

	/**
	 * @method getByPropertyId
	 * @param $id
	 *
	 * @return array|null
	 */
	public static function getByPropertyId($id)
	{
		return parent::getRow([
			'select' => ['*'],
			'filter' => ['=PROPERTY_ID' => $id]
		]);
	}

	/**
	 * @method getByImportProperty
	 * @param $name
	 *
	 * @return array|null
	 */
	public static function getByImportProperty($name)
	{
		return parent::getRow([
			'select' => ['*'],
			'filter' => ['=PROPERTY_IMPORT' => $name]
		]);
	}
}
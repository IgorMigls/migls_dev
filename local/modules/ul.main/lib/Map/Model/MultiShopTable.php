<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 15.07.2016
 * Time: 13:14
 */

namespace UL\Main\Map\Model;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

class MultiShopTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ul_multi_shop';
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
			'VALUE' => new Entity\StringField('VALUE', ['default_value' => '']),
			'ELEMENT'=>new Entity\ReferenceField(
				'ELEMENT',
				\Bitrix\Iblock\ElementTable::getEntity(),
				['=this.VALUE'=>'ref.ID']
			)
		];
	}

	/**
	 * @method createTables
	 */
	public static function createTables()
	{
		$connect = Application::getConnection();
		if(!$connect->isTableExists(self::getTableName())){
			self::getEntity()->createDbTable();
			$connect->createIndex(self::getTableName(), 'is_multi_val', 'VALUE');
		}
	}
}
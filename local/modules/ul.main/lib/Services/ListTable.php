<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 18.09.2016
 * Time: 11:04
 */

namespace UL\Main\Services;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use PW\Tools\Debug;
use UL\Main\MysqlHelper;

class ListTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ul_favorite_list';
	}

	/**
	 * @method getMap
	 * @return array
	 * @throws \Bitrix\Main\ObjectException
	 */
	public static function getMap()
	{
		global $USER;

		return [
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'USER_ID' => new Entity\IntegerField('USER_ID', array(
				'required' => true,
				'default_value' => $USER->GetID(),
			)),
			'DATE_X' => new Entity\DatetimeField('DATE_X', array(
				'default_value' => new Type\DateTime(),
			)),
			'NAME' => new Entity\StringField('NAME'),
			'CNT_PRODUCTS' => new Entity\IntegerField('CNT_PRODUCTS', [
				'default_value' => 0
			]),
		];
	}

	/**
	 * @method createTable
	 */
	public static function createTable()
	{
		MysqlHelper::createTable(self::getEntity(), [
			'ix_fav_userId' => ['USER_ID'],
		]);
	}


}
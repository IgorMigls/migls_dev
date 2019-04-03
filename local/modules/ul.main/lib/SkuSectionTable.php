<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 29.07.2016
 * Time: 15:32
 */

namespace UL\Main;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class SkuSectionTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_remain_section';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'ID' => new Entity\IntegerField('ID', array(
				'title' => self::getLang('ID'),
				'primary' => true,
				'autocomplete' => true,
			)),
			'SECTION_ID'=> new Entity\IntegerField('SECTION_ID', array(
				'title' => self::getLang('SECTION_ID'),
			)),
			'SKU_ID' => new Entity\IntegerField('SKU_ID', array(
				'title' => self::getLang('SKU_ID'),
			)),
			'SHOP_ID' => new Entity\IntegerField('SHOP_ID', array(
				'title' => self::getLang('SHOP_ID'),
			)),
		];
	}

	private static function getLang($filedName)
	{
		return Loc::getMessage('UL_REMAIN_SECTION_ENTITY_'.$filedName);
	}

}
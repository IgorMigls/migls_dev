<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 26.08.2016
 * Time: 16:46
 */

namespace UL\Main\Import\Model;

use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use PW\Tools\Debug;

Loc::loadMessages(__FILE__);

includeModules(['iblock', 'ab.iblock']);

class PresetTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_import_preset';
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
			'TITLE' => new Entity\StringField('TITLE', array(
				'required' => true,
				'title' => Loc::getMessage('UL_MAIN_PRESET_ENTITY_TITLE'),
			)),
			'CATALOG_ID' => new Entity\IntegerField('CATALOG_ID', array(
				'required' => true,
				'title' => Loc::getMessage('UL_MAIN_PRESET_ENTITY_CATALOG_ID'),
			)),
			'LAST_IMPORT' => new Entity\DatetimeField('LAST_IMPORT', array(
				'title' => Loc::getMessage('UL_MAIN_PRESET_ENTITY_LAST_IMPORT'),
			)),
			'LAST_USER_IMPORT' => new Entity\IntegerField('LAST_USER_IMPORT', array(
				'title' => Loc::getMessage('UL_MAIN_PRESET_ENTITY_LAST_USER_IMPORT'),
			)),
			'IN_CRON' => new Entity\BooleanField('IN_CRON', array(
				'title' => Loc::getMessage('UL_MAIN_PRESET_ENTITY_IN_CRON'),
				'values' => array(0, 1),
			)),
			'SHOP' => new Entity\ReferenceField(
				'SHOP',
				\Bitrix\Iblock\ElementTable::getEntity(),
				['=this.SHOP_ID' => 'ref.ID']
			),
		];
	}

	public static function createTable()
	{
		\UL\Main\MysqlHelper::createTable(self::getEntity());
	}

	public static function dropTable()
	{
		$connect = Application::getConnection();
		$connect->dropTable(self::getTableName());
	}

	/**
	 * @method add
	 * @param array $data
	 *
	 * @return Entity\AddResult
	 * @throws \Exception
	 */
	public static function add(array $data)
	{
		$result = parent::add($data);

		return $result;
	}

	/**
	 * @method update
	 * @param mixed $primary
	 * @param array $data
	 *
	 * @return Entity\UpdateResult
	 * @throws \Exception
	 */
	public static function update($primary, array $data)
	{
		$result = parent::update($primary, $data);

		return $result;
	}

}
<?php namespace UL\Main\Map\Model;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Web\Json;
use PW\Tools\Debug;

Loc::loadMessages(__FILE__);

class CordTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'ul_cord_shops';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		Loader::includeModule('iblock');

		return [
			'ID' => new Entity\IntegerField('ID', array(
				'title' => self::getLang('ID'),
				'primary' => true,
				'autocomplete' => true,
			)),
			'SHOP_ID' => new Entity\TextField('SHOP_ID', array(
				'title' => self::getLang('SHOP_ID'),
				'serialized' => true,
				'default_value' => '',
			)),
			'CORDS' => new Entity\TextField('CORDS', array(
				'title' => self::getLang('CORDS'),
			)),
			'TITLE_REGION' => new Entity\StringField('TITLE_REGION', array(
				'title' => self::getLang('TITLE_REGION'),
			)),
			'CITY_ID' => new Entity\IntegerField('CITY_ID', array(
				'title' => self::getLang('CITY_ID'),
				'default_value' => 0,
			)),
			'UID' => new Entity\StringField('UID', array(
				'title' => self::getLang('UID'),
				'default_value' => '',
			)),
//			'SHOP' => new Entity\ReferenceField(
//				'SHOP',
//				ElementTable::getEntity(),
//				['=this.SHOP_ID' => 'ref.ID']
//			),
//			'CITY' => new Entity\ReferenceField(
//				'SHOP',
//				SectionTable::getEntity(),
//				['=this.SHOP_ID' => 'ref.ID']
//			),
		];
	}

	public static function add(array $data)
	{
		if(empty($data['CORDS'])){
			$result = new Entity\UpdateResult();
			$result->addError(new Error('Введите коррдинаты области'));

			return $result;
		}

		$data = self::modifiedCity($data);
		$result = parent::add($data);

		if($result->isSuccess()){
			parent::update($result->getId(), ['SHOP_ID'=>self::saveMultiShops($data['SHOP_ID'])]);
		}

		return $result;
	}

	public static function update($primary, array $data)
	{

		if(empty($data['CORDS']) && intval($data['ID']) > 0){
			$arCords = parent::getRowById($data['ID']);
			$data['CORDS'] = $arCords['CORDS'];
		} elseif(empty($data['CORDS']) && intval($data['ID']) == 0){
			$result = new Entity\UpdateResult();
			$result->addError(new Error('Введите коррдинаты области'));

			return $result;
		}

		$data = self::modifiedCity($data);

//		PR($data); exit;

		$result = parent::update($primary, $data);

		if($result->isSuccess()){
//			PR($data['SHOP_ID']); exit;
			parent::update($result->getId(), ['SHOP_ID'=>self::saveMultiShops($data['SHOP_ID'])]);
		}

		return $result;
	}

	public static function saveMultiShops($shops)
	{
//		MultiShopTable::getEntity()->getConnection()->dropTable(MultiShopTable::getTableName());
		MultiShopTable::createTables();
		$arShop = [];

		foreach ($shops as $shop) {
			$row = MultiShopTable::getRowById($shop);

			if(is_array($shop)){
				$val = array_shift($shop['VALUE']);
			}

			if($row && !is_null($row)){
				$resultSave = MultiShopTable::update($row['ID'], ['VALUE'=>$shop]);
			} else {
				$resultSave = MultiShopTable::add(['VALUE'=>$shop]);
			}

			if($resultSave->isSuccess()){
				$arShop[] = $resultSave->getId();
			}
		}

		return $arShop;
	}

	public static function uidCoors($arCoords)
	{
		foreach ($arCoords as &$coord) {
			$coord[0] = round($coord[0], 5);
			$coord[1] = round($coord[1], 5);
		}

		return md5(serialize($arCoords));
	}

	/**
	 * @method modifiedCity
	 * @param array $data
	 *
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	protected static function modifiedCity($data)
	{

		$arCords = Json::decode($data['CORDS']);

		$uid = self::uidCoors($arCords['cords'][0]);

		$data['UID'] = $uid;

		if (count($data['SHOP_ID']) > 0){
			Loader::includeModule('iblock');

			foreach ($data['SHOP_ID'] as &$item) {
				if(isset($item['VALUE'])){
					$item = $item['VALUE'];
				}
			}

			$shopTmp = $data['SHOP_ID'];
			$shop = array_shift($shopTmp);

//			$row = ElementTable::getRow([
//				'filter' => ['=ID' => $shop],
//				'select' => ['IBLOCK_SECTION_ID'],
//			]);
//			$data['CITY_ID'] = $row['IBLOCK_SECTION_ID'];
		}

		if(strlen($data['UID']) == 0){
			$data['UID'] = '';
		}

		return $data;
	}


	/**
	 * @method getLang
	 * @param $filedName
	 *
	 * @return string
	 */
	private static function getLang($filedName)
	{
		return Loc::getMessage('UL_CORD_LANG_'.$filedName);
	}

	/**
	 * @method createTable
	 */
	public static function createTable()
	{
		$connect = Application::getConnection();
		if (!$connect->isTableExists(static::getTableName())){
			static::getEntity()->createDbTable();
			$connect->createIndex(static::getTableName(), 'ix_city', ['CITY_ID']);
			$connect->createIndex(static::getTableName(), 'ix_uid_region', 'UID');
		}
	}

	/**
	 * @method dropTables
	 */
	public static function dropTables()
	{
		$connect = Application::getConnection();
		if ($connect->isTableExists(static::getTableName())){
			$connect->dropTable(static::getTableName());
		}
	}
}
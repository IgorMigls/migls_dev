<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 20.01.2017
 */

namespace UL\Main;

use AB\Iblock\Element;
use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Main\Type\ParameterDictionary;

Loader::includeModule('iblock');

class CatalogHelper
{
	protected static $skuIblocks = [];
	protected static $catalogIblocks = [];

	const SHOP_IB = 5;

	public static function getCatalogIblocks()
	{
		if(count(self::$catalogIblocks) == 0){
			$oSkuIblocks = Iblock\IblockTable::getList([
				'select' => ['ID','NAME'],
				'filter' => ['=IBLOCK_TYPE_ID' => 'catalog']
			]);
			while ($block = $oSkuIblocks->fetch()){
				self::$catalogIblocks[$block['ID']] = $block;
			}
		}

		return self::$catalogIblocks;
	}

	public static function getSkuIblocks()
	{
		if(count(self::$skuIblocks) == 0){
			$oSkuIblocks = Iblock\IblockTable::getList([
				'select' => ['ID','NAME'],
				'filter' => ['=IBLOCK_TYPE_ID' => 'remains']
			]);
			while ($block = $oSkuIblocks->fetch()){
				self::$skuIblocks[$block['ID']] = $block;
			}
		}

		return self::$skuIblocks;
	}

	public static function getSkuBlockByProductsBlock()
	{
		$result = [];
		foreach (self::getCatalogIblocks() as $id => $catalogIblock) {
			$catalogIblock['SKU_INFO'] = \CCatalogSku::GetInfoByIBlock($id);
			$result[$id] = $catalogIblock;
		}

		return $result;
	}

	/**
	 * @method getAvailableShops
	 * @param $shop
	 *
	 * @return Dictionary
	 */
	public static function getAvailableShops($shop)
	{

		$filter = [
			'IBLOCK_ID' => self::SHOP_IB,
			'ACTIVE' => 'Y',
			'!=PROPERTY.NO_AVAILABLE' => 1
		];

		if(is_array($shop)){
			$filter['@ID'] = $shop;
		} else {
			$filter['=ID'] = (int)$shop;
		}

		$arShop = [];
		$shopIterator = Element::getList([
			'select' => [
				'ID','IBLOCK_ID','NAME','CODE',
				'LOGO' => 'DETAIL_PICTURE_FILE.SRC_FILE'
			],
			'filter' => $filter,
		]);
		while ($rs = $shopIterator->fetch()){
			$arShop[$rs['ID']] = $rs;
		}

		return new Dictionary($arShop);
	}

	/**
	 * @method makeUrl
	 * @param $data
	 * @param $template
	 *
	 * @return string
	 */
	public static function makeUrl($data, $template)
	{
		$template = str_replace('#SITE_DIR#', '', $template);
		foreach ($data as $code => $val) {
			$template = str_replace('#'.$code.'#', $val, $template);
		}

		return $template;
	}
}
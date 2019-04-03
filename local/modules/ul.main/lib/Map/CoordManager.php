<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 09.11.2016
 * Time: 20:54
 */

namespace UL\Main\Map;

class CoordManager
{
	public function __construct()
	{
	}

	public static function checkOrderCoordsAction($post = [])
	{
		$uid = Model\CordTable::uidCoors($post['coords']);
		$result = Model\CordTable::getRow([
			'select'=>['CITY_ID', 'SHOP_ID', 'ID'],
			'filter' => ['=UID' => $uid]
		]);

		$arCurrentShops = [];
		$oShop = Model\MultiShopTable::getList([
			'filter' => ['=ID' => $result['SHOP_ID']]
		]);
		while ($shop = $oShop->fetch()){
			$arCurrentShops[] = $shop['VALUE'];
		}

		$intersect = array_intersect($arCurrentShops, $_SESSION['REGIONS']['SHOP_ID']);

		if(count($intersect) == 0){
			return 3;
		} elseif(count($intersect) == count($_SESSION['REGIONS']['SHOP_ID'])) {
			return 1;
		}

		return 2;

	}
}
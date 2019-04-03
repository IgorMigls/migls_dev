<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
/** @global $APPLICATION */
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$response = \Bitrix\Main\Context::getCurrent()->getResponse();
\Bitrix\Main\Loader::includeModule('ul.main');
\Bitrix\Main\Loader::includeModule('iblock');

use UL\Main\Map\Model\CordTable;

$post = $request->getPostList()->toArray();

$result['DATA'] = null;

if(check_bitrix_sessid() && $request['set_region'] == 'Y'){
	$_SESSION['addressChange'] = 'Y';
	$uid = CordTable::uidCoors($post['CORDS']);


	$cacheIndexProducts = md5(serialize($_SESSION['REGIONS']['SHOP_ID']));
	$tags = new Bitrix\Main\Data\TaggedCache();
	$tags->clearByTag($cacheIndexProducts);
	$tags->clearByTag('shop_list_small_'.md5(serialize($_SESSION['REGIONS']['CITY_ID'])));


	unset($_SESSION['REGIONS']);
	unset($_SESSION['SHOPS']);

	$result = CordTable::getRow([
		'select'=>['CITY_ID', 'SHOP_ID', 'ID'],
		'filter' => ['=UID' => $uid]
	]);

	if($result && !is_null($result)){
		TrimArr($result['SHOP_ID']);
		$_SESSION['REGIONS']['CITY_ID'] = $result['CITY_ID'];
		$shopIds = UL\Main\Map\Model\MultiShopTable::getList([
			'select'=>['VALUE', 'ID'],
			'filter' => ['=ID'=>$result['SHOP_ID']]
		])->fetchAll();

		foreach ($shopIds as $id) {
			if(intval($id['VALUE']) > 0){
				$_SESSION['REGIONS']['AREAL'] = $id['ID'];
				$_SESSION['REGIONS']['SHOP_ID'][] = $id['VALUE'];
			}
		}

//		\PW\Tools\Debug::toLog($_SESSION['REGIONS']);
//		$APPLICATION->set_cookie('REGION_ID', $result['CITY_ID'], false, '/', $request->getHttpHost());
//		$APPLICATION->set_cookie('SHOP_ID', serialize($result['SHOP_ID']), false, '/', $request->getHttpHost());

		if(strlen($post['ADDRESS']) > 0){
			$_SESSION['REGIONS']['ADDRESS'] = $post['ADDRESS'];
//			$response->addCookie(new Bitrix\Main\Web\Cookie('ADDRESS', $post['SHOP_ID']));
		}
	}
}
$result['DATA'] = $_SESSION['REGIONS'];

if(strlen($_SESSION['REGIONS']['ADDRESS']) == 0){
	$arCity = \Bitrix\Iblock\SectionTable::getRow([
		'filter'=>['IBLOCK_ID'=>5,'=ID'=>$_SESSION['REGIONS']['CITY_ID']],
		'select' =>['NAME']
	]);
	$_SESSION['REGIONS']['ADDRESS'] = 'Россия, '.$arCity['NAME'];
}

echo \Bitrix\Main\Web\Json::encode($result);

exit;
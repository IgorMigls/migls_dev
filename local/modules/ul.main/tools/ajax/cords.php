<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
/** @global \CMain $APPLICATION */
use UL\Main\Map\Model\CordTable;
use Bitrix\Main\Loader;

Loader::includeModule('ul.main');

$APPLICATION->RestartBuffer();

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();


if(check_bitrix_sessid() && $request->get('getAllCords') == 'Y'){
	$arCords = [];
	$arCords = CordTable::getList()->fetchAll();

	foreach ($arCords as &$cord) {
		$obShops = \UL\Main\Map\Model\MultiShopTable::getList([
			'filter' => ['ID'=>$cord['SHOP_ID']]
		]);
//		unset($cord['SHOP_ID']);
		while ($s = $obShops->fetch()){
			$cord['SHOP_VALUES'][] = $s['VALUE'];
		}
	}

	if(count($arCords) > 0){
		if($request->get('v') == 2){
			$result['DATA']['DATA'] = $arCords;
		} else {
			$result['DATA'] = $arCords;
		}
	} else {
		$result['DATA'] = null;
	}

	$result['CURRENT_SHOP'] = $_SESSION['REGIONS'];



	echo \Bitrix\Main\Web\Json::encode($result);
}

exit;
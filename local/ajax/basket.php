<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
/** @global \CMain $APPLICATION */
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$ID = intval($request->get('ID'));
$quantity = floatval($request->get('q'));

\Bitrix\Main\Loader::includeModule('catalog');

if(check_bitrix_sessid() && $ID > 0){
	if($quantity == 0)
		$quantity = 1;

	$basketId = \Add2BasketByProductID($ID, $quantity);

	$status = 0;
	$error = null;

	if(intval($basketId) > 0){
		$status = 1;
	} else {
		$error = 'Ошибка добавления в корзину';
	}
	$result = [
		'status' => $status,
		'ID' => $basketId,
		'ERROR' => $error
	];

	echo \Bitrix\Main\Web\Json::encode($result);

	exit;
}

if($request->get('getBasket')){
	$APPLICATION->IncludeComponent('ul:basket.small','',[], false);
}
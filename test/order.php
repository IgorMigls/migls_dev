<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

\Bitrix\Main\Loader::includeModule('sale');

use Bitrix\Sale;

//Sale\Order::delete(321);

CBitrixComponent::includeComponentClass('mig:mobile.delivery');
$component = new Mig\Mobile\DeliveryComponent();
$component->setPostData([
	'orderId' => 324,
	'count' => 6,
	'storeQuantity' => 10,
	'basketId' => 4984,
	'basketCustomId' => 183
]);

$component->saveComplectationOrder();

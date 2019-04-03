<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$oBasket = new UL\Sale\Basket();
$arBasket = $oBasket::getBasketUser();

dd($arBasket);
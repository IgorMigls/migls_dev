<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
/** @global CMain $APPLICATION */
/** @var Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$ID = intval($request->get('id'));
if($ID > 0){
	$APPLICATION->IncludeComponent('ul:product.detail','',['ID'=>$ID], false);
}
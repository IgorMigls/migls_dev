<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$free = $request->getPost('free');
$expires = time() + (86400 * 7);

global $APPLICATION;
$APPLICATION->set_cookie('FREE_DELIVERY', $free, $expires, '/');
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

Bitrix\Main\Loader::includeModule('pw.util');

$Ajax = new PW\Tools\Ajax\Ajax();
echo $Ajax->init()->getResult();
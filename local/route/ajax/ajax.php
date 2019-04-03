<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
/** @global $APPLICATION */

use UL\Ajax;

includeModules(['ul.main']);

$Ajax = new Ajax\Manager();
$Ajax->parseUrl();

$APPLICATION->RestartBuffer();
echo $Ajax->init()->getResult();

exit;
<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use PW\Tools\Debug;
use UL\Main\Import;
use Bitrix\Main\Application;

includeModules(['ul.main','catalog']);

$iblock = 26;

Debug::startMemory();
Debug::startTime();



$params = [
	'NAME' => 'Бытовая химия и товары для дома',
	'PATH' => '/home/bitrix/www/upload/base_ahan/Бытовая химия и товары для дома'
];


$Product = new Import\Products('/base_ahan/Бытовая химия и товары для дома');
//$arIblock = Import\Helper::getIblockCatalog($params['NAME']);
$arIblock = \CCatalogSku::GetInfoByIBlock($iblock);
$Product->setIblock($arIblock['PRODUCT_IBLOCK_ID']);
$Product->process();

Debug::getMemory();
Debug::getTime(true);
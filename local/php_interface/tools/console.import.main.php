#!/usr/bin/php
<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
//require($DOCUMENT_ROOT . "/bitrix/modules/main/include/prolog_before.php");
require($DOCUMENT_ROOT."/bitrix/modules/main/include.php");

use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('ul.main');
Loader::includeModule('ab.tools');

$AjaxHandler = new UL\Main\Admins\Import\AjaxHandler();

$arIblocks = \UL\Main\CatalogHelper::getCatalogIblocks();

$Bar = new \PW\Tools\ProgressBar();
$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, count($arIblocks));
$i = 1;

foreach ($arIblocks as $iblock) {
//	$AjaxHandler->setImportParamsAction(['NAME' => $iblock['NAME']]);
//	$Bar->update($i++);
}
$AjaxHandler->setImportParamsAction(['NAME' => $arIblocks[66]['NAME']]);

echo "\n\n";
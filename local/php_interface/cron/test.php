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
use UL\Main\Import\Model\QueueTable;
use UL\Main\Import\Prices;
use Bitrix\Main\Config\Option;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('ul.main');
Loader::includeModule('ab.tools');



$test = \UL\Main\Admins\Import\OffersMeasureSetter::getInstance(88);
$test->addProductId(197534);

/**
 * класс заполняется данными в классе \UL\Main\Import\ProductItems::readXls
 * и тут Prices\Base::importRun
 */
$offersMeasureSetters = \UL\Main\Admins\Import\OffersMeasureSetter::getAllInstances();
foreach ($offersMeasureSetters as $setter) {
    $setter->exec();
}
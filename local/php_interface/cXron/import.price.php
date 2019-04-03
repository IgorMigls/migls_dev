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

$isProcess = Option::get('ul.main', 'IMPORT_PRICES');

\AB\Tools\Debug::toLog($isProcess);

if ($isProcess === 1){
	exit('Import already working');
}
Option::set('ul.main', 'IMPORT_PRICES', 1);

//QueueTable::createTable();
$row = QueueTable::getRow([
	'filter' => ['IN_PROCESS' => QueueTable::QUEUE_IMPORT_IN_PROCESS],
	'order' => ['ID' => 'DESC'],
]);

//$row['SHOP_ID'] = 150624;

try {
	\AB\Tools\Debug::toLog($row);
	if (!is_null($row)){
		if(intval($row['SHOP_ID']) > 0){
			Prices\Base::importRun($row['SHOP_ID']);
			QueueTable::update($row['ID'], ['IN_PROCESS' => QueueTable::QUEUE_IMPORT_NOT_PROCESS]);
		} else {

			$Import = new UL\Main\Admins\Import\AjaxHandler();
			$Import->setImportParamsAction(['NAME' => $row['FILE']]);
			QueueTable::update($row['ID'], ['IN_PROCESS' => QueueTable::QUEUE_IMPORT_NOT_PROCESS]);
		}
	}
} catch (\Exception $e) {
	QueueTable::update($row['ID'], ['IN_PROCESS' => QueueTable::QUEUE_IMPORT_NOT_PROCESS]);

	\AB\Tools\Debug::toLog($e);
}
Option::set('ul.main', 'IMPORT_PRICES', 0);
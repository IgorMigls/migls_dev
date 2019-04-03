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
use UL\Main\Import\Model\PriceTmpTable;
use Bitrix\Iblock;
use AB\Tools\Console\ProgressBar;


Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('ul.main');
Loader::includeModule('ab.tools');

$Bar = new \PW\Tools\ProgressBar();
$shop = null;

$params = $argv;
array_shift($params);

foreach ($params as $param) {
	$p = explode('=', $param);
	switch ($p[0]) {
		case '-s':
		case '-shop':
			$shop = $p[1];
			break;
	}
}


QueueTable::createTable();

try {

	UL\Main\Import\Prices\Base::importRun($shop);

} catch (\Exception $e) {

//	\PW\Tools\Debug::toLog($e);

//	ProgressBar::showError($e->getMessage());
	$Queue = new \UL\Main\Import\Prices\Queue();
	if($e->getCode() != 400){
		$Queue->stop();
	}
}

echo "\n";
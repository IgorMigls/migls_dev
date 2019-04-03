#!/usr/bin/php
<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
//require($DOCUMENT_ROOT . "/bitrix/modules/main/include/prolog_before.php");
require($DOCUMENT_ROOT."/bitrix/modules/main/include.php");

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('ab.iblock');
\Bitrix\Main\Loader::includeModule('catalog');

use AB\Tools\Debug;

$Bar = new \PW\Tools\ProgressBar();

$params = $argv;
array_shift($params);
$filter = [];

foreach ($params as $param) {
	$p = explode('=', $param);
	switch ($p[0]){
		case '-ib':
		case '-iblock':
			$filter['IBLOCK_ID'] = $p[1];
			break;
	}
}

$limit = AB\Iblock\Element::getRow([
	'select' => ['CNT'],
	'filter' => $filter,
	'limit' => null,
	'runtime' => [
		'CNT' => new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'),
	],
]);
$CIBlockElement = new CIBlockElement();
$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$obElement = AB\Iblock\Element::getList([
	'select' => ['ID'],
	'filter' => $filter,
	'limit' => null,
]);


$i = 0;
while ($el = $obElement->fetch()) {
	$Bar->update($i++);
	$CIBlockElement->Delete($el['ID']);
}
echo "\n";
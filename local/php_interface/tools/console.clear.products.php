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
\Bitrix\Main\Loader::includeModule('catalog');

$Bar = new \PW\Tools\ProgressBar();

$params = $argv;
array_shift($params);
foreach ($params as $param) {
	$p = explode('=', $param);
	switch ($p[0]){
		case '-ib':
		case '-iblock':
			$filter['IBLOCK_ID'] = $p[1];
			break;
	}
}

$filter['ACTIVE'] = 'Y';
$limit = \Bitrix\Iblock\ElementTable::getRow([
	'select' => ['CNT'],
	'filter' => $filter,
	'limit' => null,
	'runtime' => [
		'CNT' => new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'),
	],
]);
$CIBlockElement = new CIBlockElement();
$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$obElement = \Bitrix\Iblock\ElementTable::getList([
	'select' => ['ID'],
	'filter' => $filter,
	'limit' => null,
]);

$catalogIB = CCatalogSKU::GetInfoByIBlock($IBLOCK_ID);

$i = 0;
while ($el = $obElement->fetch()) {
	$Bar->update($i++);
	$ID = $el['ID'];
	if($CIBlockElement->Delete($el['ID'])){
		$obSku = $CIBlockElement->GetList(
			array(),
			array('IBLOCK_ID' => $catalogIB['IBLOCK_ID'], 'PROPERTY_CML2_LINK' => $ID),
			false,
			false,
			array('ID', 'IBLOCK_ID')
		);
		while ($sku = $obSku->Fetch()){
			$CIBlockElement->Delete($sku['ID']);
		}
	}
}

echo "\n\n";
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
\Bitrix\Main\Loader::includeModule('soft.iblock');
\Bitrix\Main\Loader::includeModule('catalog');

$Bar = new \PW\Tools\ProgressBar();
$params = $argv;
array_shift($params);
$filter = [];

foreach ($params as $param) {
	$p = explode('=', $param);
	switch ($p[0]) {
		case '-ib': // set product IB
		case '-iblock':
			$filter['IBLOCK_ID'] = $p[1];
			break;
		default:
			exit("Iblock is null \n");
	}
}
$limit = Soft\Element::getRow([
	'select' => ['CNT'],
	'filter' => $filter,
	'limit' => null,
	'runtime' => [
		'CNT' => new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'),
	],
]);
$CIBlockElement = new CIBlockElement();

$catalog = CCatalogSku::GetInfoByIBlock($filter['IBLOCK_ID']);

$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$obElement = Soft\Element::getList([
	'select' => ['ID', 'CODE', 'NAME'],
	'filter' => $filter,
	'limit' => null,
]);

$Sku = \Bitrix\Iblock\ElementTable::getList([
	'filter' => ['IBLOCK_ID' => $catalog['IBLOCK_ID']],
	'select' => ['ID'],
	'order' => ['ID' => 'ASC'],
	'limit' => $limit['CNT']
])->fetchAll();

$i = 0;
while ($el = $obElement->fetch()) {
	\CIBlockElement::SetPropertyValuesEx($Sku[$i]['ID'], $catalog['IBLOCK_ID'], ['CML2_LINK' => $el['ID']]);
	$CIBlockElement->Update($Sku[$i]['ID'], ['NAME' => $el['NAME']]);
	\Bitrix\Catalog\Product\Sku::updateAvailable($Sku[$i]['ID'], 0, ['QUANTITY' => 50]);
	$Bar->update($i++);
}

exit("\n");
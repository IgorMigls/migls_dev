#!/usr/bin/php
<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__) . '/../../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
//require($DOCUMENT_ROOT . "/bitrix/modules/main/include/prolog_before.php");
require($DOCUMENT_ROOT . "/bitrix/modules/main/include.php");

\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('soft.iblock');
\Bitrix\Main\Loader::includeModule('catalog');

use Bitrix\Iblock;

$Bar = new \PW\Tools\ProgressBar();
$params = $argv;
array_shift($params);
$filter = [];

foreach ($params as $param) {
	$p = explode('=', $param);
	switch ($p[0]) {
		case '-ib':
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
$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$obProduct = Soft\Element::getList([
	'select' => ['ID', 'CODE', 'IBLOCK_ID'],
	'filter' => $filter,
	'limit' => null,
]);

$catalog = CCatalogSku::GetInfoByIBlock($filter['IBLOCK_ID']);
$i = 0;
while ($product = $obProduct->fetch()) {
	$rowProduct = Iblock\ElementTable::getRow([
		'select' => ['ID', 'CODE'],
		'filter' => ['IBLOCK_ID' => $catalog['PRODUCT_IBLOCK_ID'], '=CODE' => $product['CODE']],
	]);
	if ($rowProduct && !is_null($rowProduct)) {
		\CIBlockElement::SetPropertyValuesEx($product['ID'], $product['IBLOCK_ID'], ['CML2_LINK' => $rowProduct['ID']]);
	}

	$Bar->update($i++);
}
echo "\n";
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
\Bitrix\Main\Loader::includeModule('iblock');
\Bitrix\Main\Loader::includeModule('soft.iblock');
/*
//$Bar = new \PW\Tools\ProgressBar();

$filter = ['IBLOCK_ID' => 4];
$limit = Soft\Element::getRow([
	'select' => ['CNT'],
	'filter' => $filter,
	'limit' => null,
	'runtime' => [
		'CNT' => new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(1)'),
	],
]);
$CIBlockElement = new CIBlockElement();
//$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$obElement = Soft\Element::getList([
	'select' => ['ID', 'BARCODE' => 'PROPERTY.BARCODE', 'ARTICLE' => 'PROPERTY.ARTICLE'],
	'filter' => $filter,
	'limit' => null,
]);

$i = 0;
while ($el = $obElement->fetch()) {
	//$Bar->update($i++);
	$CIBlockElement->Update($el['ID'], ['CODE' =>$el['BARCODE'].'|'.$el['ARTICLE']]);
}*/
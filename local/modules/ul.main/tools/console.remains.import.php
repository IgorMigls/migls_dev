#!/usr/bin/php
<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../../../../';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
require($DOCUMENT_ROOT."/bitrix/modules/main/include.php");

use Bitrix\Main\Loader;
use PW\Tools\Debug;
use UL\Import\RemainsTmpTable;
use AB\Iblock;
use Bitrix\Main\Application;

Loader::includeModule('ul.main');

includeModules(['iblock', 'ab.iblock', 'catalog', 'sale', 'pw.util']);

Debug::startMemory();
Debug::startTime();

$IBLOCK_ID = 10;
$shopId = 150618;
$arPropShop = \Bitrix\Iblock\PropertyTable::getRow([
	'select' => ['ID'],
	'filter' => ['IBLOCK_ID' => $IBLOCK_ID, '=CODE' => 'SHOP_ID'],
]);

$connect = Application::getConnection();
$tbl = Iblock\Element::getTableName();
$sql = "UPDATE ".$tbl." BE
			LEFT JOIN b_iblock_element_prop_s".$IBLOCK_ID." BP ON (BE.ID = BP.IBLOCK_ELEMENT_ID)
			SET ACTIVE = 'N'
			WHERE BE.IBLOCK_ID = ".$IBLOCK_ID." AND BP.PROPERTY_".$arPropShop['ID']." = ".$shopId;
$connect->queryExecute($sql);

$arIblock = CCatalogSku::GetInfoByIBlock($IBLOCK_ID);

//PR($arIblock, 1);
//exit;


$arShopRemain = [];

$Bar = new \PW\Tools\ProgressBar();

$limit = RemainsTmpTable::getRow([
	'limit' => null,
	'runtime' => [
		'CNT' => new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)'),
	],
	'select' => ['CNT'],
]);

$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $limit['CNT']);

$CIBlockElement = new \CIBlockElement();
$i = 0;
$obRemain = RemainsTmpTable::getList();
while ($remain = $obRemain->fetch()) {

	$Bar->update($i++);

//	$arElement = Iblock\Element::getRow([
//		'select' => ['ID'],
//		'filter' => [
//			'IBLOCK_ID'=>$remain['IBLOCK_ID'],
//			'PROPERTY.SHOP_ID.ID' => $remain['SHOP_ID'],
//			'=CODE' => $remain['ARTICLE'].'|'.$remain['BARCODE']
//		],
//	]);

	$arElement = \CIBlockElement::GetList(
		array(),
		array(
			'IBLOCK_ID'=>$remain['IBLOCK_ID'],
			'=CODE' => $remain['BARCODE'],
			'=PROPERTY_SHOP_ID' => $remain['SHOP_ID']
		),
		false,
		array('nTopCount'=>1),
		array('ID')
	)->Fetch();

	if(!$arShopRemain[$remain['SHOP_ID']]){
		$arShopRemain[$remain['SHOP_ID']] = \Bitrix\Iblock\SectionTable::getRow([
			'select' => ['ID'],
			'filter'=>['=XML_ID'=>$remain['SHOP_ID'], 'IBLOCK_ID'=>$remain['IBLOCK_ID']]
		]);
	}

	$save = [
		'IBLOCK_ID' => $remain['IBLOCK_ID'],
		'NAME' => $remain['BARCODE'],
		'ACTIVE'=>'Y',
		'CODE' => $remain['BARCODE'],
		'IBLOCK_SECTION_ID' => $arShopRemain[$remain['SHOP_ID']]['ID'],
		'PROPERTY_VALUES' => [
			'SHOP_ID' =>  $remain['SHOP_ID'],
			'BARCODE' => $remain['BARCODE'],
			'ARTICLE' => $remain['ARTICLE']
		]
	];

	$arProduct = Iblock\Element::getRow([
		'select' => ['ID','NAME'],
		'filter' => [
			'IBLOCK_ID'=>$arIblock['PRODUCT_IBLOCK_ID'],
			'=CODE' => $remain['BARCODE']
		],
	]);
	if(!is_null($arProduct)){
		$save['PROPERTY_VALUES']['CML2_LINK'] = $arProduct['ID'];
		$save['NAME'] = $arProduct['NAME'];
	} else {
		continue;
	}
	$ID = false;
	if ($arElement){
		if($CIBlockElement->Update($arElement['ID'], $save, false, false)){
			$ID = $arElement['ID'];
		}
	} else {
		$ID = $CIBlockElement->Add($save, false, false);
	}

	if(intval($ID) > 0) {
		\CPrice::SetBasePrice($ID, $remain['PRICE'], 'RUB');
		\CCatalogProduct::Add(['ID' => $ID, 'QUANTITY'=>$remain['QUANTITY']]);
	}
	if(!is_null($arProduct)){
		PR($arProduct, 1);
		echo "\n";

		\CIBlockElement::SetPropertyValuesEx($ID, $remain['IBLOCK_ID'], ['CML2_LINK' => $arProduct['ID']]);
	}
}

echo "\r\n";
echo Debug::getMemory(false, false)."\n";
echo Debug::getTime(false)."\n";
echo "\r\n";
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use AB\Iblock\Element;

includeModules('ab.iblock');

$Query = Element::query();
$Query->setFilter([
	'IBLOCK_ID'=>4,
	'ACTIVE' => 'Y',
	'PROPERTY.BRANDS.ID' => 20
//	array(
//		'LOGIC'=>'OR',
//		array('=PROPERTY.CML2_LINK.NAME' => 'asd'),
//		array('=PROPERTY.CML2_LINK.NAME' => '123468sad'),
//	)
]);
$Query->setSelect([
	'ID','NAME',
	'BARCODE' => 'PROPERTY.BARCODE',
	'ARTICLE' => 'PROPERTY.ARTICLE',
	'COMPOSITION' => 'PROPERTY.COMPOSITION',
//	'COUNTRY' => 'PROPERTY.COUNTRY',
	'BRANDS' => 'PROPERTY.BRANDS',
//	'SHOP_NAME' => 'TEST.NAME',
//	'SHOP_LIST' => 'TEST.PROPERTY.LIST',
//	'SHOP_TEST' => 'TEST.PROPERTY.TEST'
]);
$Query->setLimit(10);
$Query->setOrder(['TIMESTAMP_X'=>'DESC']);
//$Query->setGroup(['ID','IBLOCK_ID']);
//$Query->setOrder(['NAME'=>'ASC', 'IBLOCK_ID'=>'ASC']);

PR($Query->getQuery());

$res = $Query->exec();

PR($res->fetchAll());
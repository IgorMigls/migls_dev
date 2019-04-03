<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

\Bitrix\Main\Loader::includeModule('soft.iblock');

\Bitrix\Main\Diag\Debug::startTimeLabel('IB');

$iblock = 114;
$product = 16444459;
//$product = 11566022;

$Query = new \Soft\IBlock\Query(\Soft\IBlock\ElementTable::getEntity());
//\Esd\Debug::startSql();
$obProduct = $Query->setSelect(array(
	'ID','NAME','IBLOCK_ID',
	'PROJECT_ID'=>'PROPERTY.PROJECT_ID',
	'BASE_NAME'=>'PROPERTY.BASE_NAME',
	'PRODUCT_CODE'=>'PROPERTY.PRODUCT_CODE',
//	'BASE_PRICE'=>'PROPERTY.PRODUCT_PRICE.NAME',
	'WAREHOUSE'=>'PROPERTY.PRODUCT_PRICE.PROPERTY.WAREHOUSE',
//	'PRICE_XML_ID'=>'PROPERTY.PRODUCT_PRICE.XML_ID',
//	'AGE'=>'PROPERTY.PEGI_AGE.VALUE',
//	'DISTRIBUTION_TYPE'=>'PROPERTY.DISTRIBUTION_TYPE.VALUE',
//	'SRC_PUBLISHERS_RUS'=>'PROPERTY.SRC_PUBLISHERS_RUS',
//	'KEY_TYPE'=>'PROPERTY.KEY_TYPE',
//	'PROPERTY_'=>'PROPERTY',
//	'PROPERTY.SRC_PUBLISHERS_RUS'
))
	->setFilter(array(
		'IBLOCK_ID'=>$iblock,
//		'ID'=>$product,
		'%NAME'=>'Battlefield',
//		'PROPERTY.PRODUCT_PRICE.PROPERTY.BASE_PRICE'=>999
//		'PROPERTY.PROJECT_ID.ID'=>11411159,
//		'PROPERTY.PEGI_AGE.VALUE'=>'18+ (от 18 лет)'
	))
	->setLimit(1)
	->exec();
//PR(\Esd\Debug::getSql($obProduct));

$arProduct = $obProduct->fetchAll();
PR($arProduct);
PR(count($arProduct));
\Bitrix\Main\Diag\Debug::endTimeLabel('IB');
Bitrix\Main\Diag\Debug::startTimeLabel('getList');

$arList = array();
$obList = Soft\IBlock\ElementTable::getList(array(
	'select'=>array(
		'ID','NAME','IBLOCK_ID',
		'BASE_NAME'=>'PROPERTY.BASE_NAME',
		'PRODUCT_CODE'=>'PROPERTY.PRODUCT_CODE',
		'BASE_PRICE'=>'PROPERTY.PRODUCT_PRICE.PROPERTY.BASE_PRICE',
//		'WAREHOUSE'=>'PROPERTY.PRODUCT_PRICE.PROPERTY.WAREHOUSE',
//		'SRC_PUBLISHERS_RUS'=>'PROPERTY.SRC_PUBLISHERS_RUS',
		'KEY_TYPE'=>'PROPERTY.KEY_TYPE',
		'DISTRIBUTION_TYPE'=>'PROPERTY.DISTRIBUTION_TYPE.XML_ID'
	),
	'filter'=>array(
		'IBLOCK_ID'=>$iblock,
//		'ID'=>$product,
//		'%NAME'=>'Battlefield',
		'ACTIVE'=>'Y',
		'PROPERTY.DISTRIBUTION_TYPE.VALUE'=>'Online'
//		'PROPERTY.PROJECT_ID'=>9214508,
	),
//	'order'=>array('PROPERTY.PRODUCT_PRICE.PROPERTY.WAREHOUSE'=>'DESC'),
	'limit'=>30
));
while($list = $obList->fetch()){
	$arList[] = $list;
}
PR($arList);
PR(count($arList));

Bitrix\Main\Diag\Debug::endTimeLabel('getList');

PR(\Bitrix\Main\Diag\Debug::getTimeLabels());
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

use Bitrix\Main\Loader;
use Bitrix\Iblock\SectionTable;

Loader::includeModule('iblock');

$cityList = [];
$sectionIterator = SectionTable::getList([
	'filter' => ['IBLOCK_ID' => \UL\Main\CatalogHelper::SHOP_IB, 'ACTIVE' => 'Y'],
	'select' => ['ID', 'NAME']
]);
while ($rs = $sectionIterator->fetch()){
	$cityList[$rs['ID']] = $rs['NAME'];
}

$catalogs = \UL\Main\CatalogHelper::getCatalogIblocks();

$arComponentParameters = array(
	"GROUPS" => array('BASE'=>array('NAME'=>'Основные')),
	"PARAMETERS" => array(
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		'CITY_ID' => array(
			'NAME' => 'Город',
			'TYPE' => 'LIST',
			'VALUES' => $cityList,
		),
		'IBLOCK_ID' => array(
			'NAME' => 'Каталог',
			'TYPE' => 'LIST',
			'VALUES' => $catalogs,
		),
		'SHOP_FOLDER' => array(
			'NAME' => 'Раздел магазинов',
			'DEFAULT' => '/shop/'
		),
		'CATALOG_FOLDER' => array(
			'NAME' => 'Раздел каталогов без магазинов',
			'DEFAULT' => '/catalog/'
		)
	),
);
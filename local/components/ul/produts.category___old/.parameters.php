<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

\Bitrix\Main\Loader::includeModule('iblock');

$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

$arComponentParameters = array(
	"GROUPS" => array('BASE'=>array('NAME'=>'Основные')),
	"PARAMETERS" => array(
		'DEPTH_LEVEL'=>['NAME'=>'Максимальный уровень вложенности'],
		'IBLOCK_TYPE'=>['NAME'=>'Тип инфоблока','TYPE'=>'LIST','VALUES'=>$arTypesEx],
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
	),
);
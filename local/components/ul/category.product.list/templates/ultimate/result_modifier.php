<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$result = [];
PR($arResult['SECTION_INFO']);
foreach ($arResult['SECTIONS_PRODUCT'] as $arSection) {
	foreach ($arSection['PRODUCTS'] as $arElement){
		$result[] = $arElement;
	}
}
$arResult['SECTIONS_PRODUCT'] = $result;
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
global $USER_FIELD_MANAGER;

if(!empty($arParams['SECTIONS'])){
	$arResult['SECTIONS'] = $arParams['SECTIONS'];
} else {
	foreach ($arResult['SECTIONS'] as &$SECTION) {
		$SECTION['ITEMS'] = $SECTION['SECTIONS'];
		$SECTION['MAIN_NAME'] = $SECTION['NAME'];
		$SECTION['MAIN_URL'] = $SECTION['MAIN_CATALOG_URL'];

		unset($SECTION['SECTIONS'], $SECTION['NAME'], $SECTION['MAIN_CATALOG_URL']);
	}
}

foreach ($arResult['SECTIONS'] as &$SECTION) {
	$uFields = $USER_FIELD_MANAGER->GetUserFields('ASD_IBLOCK', $SECTION['IBLOCK_ID']);
	if(!$SECTION['ICON']){
		$SECTION['ICON'] = CFile::GetFileArray($uFields['UF_ICON']['VALUE']);
	}
}

//dump($arResult['SECTIONS'][30]);
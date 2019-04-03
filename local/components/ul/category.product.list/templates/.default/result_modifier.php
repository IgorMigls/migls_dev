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
foreach ($arResult['SECTIONS_PRODUCT'] as &$section){
	$fields = $USER_FIELD_MANAGER->GetUserFields('ASD_IBLOCK', $section['IBLOCK_ID']);
	if((int)$fields['UF_IMG_LINE']['VALUE'] > 0){
		$section['LINE_IMG'] = \CFile::GetFileArray($fields['UF_IMG_LINE']['VALUE']);
	}
}
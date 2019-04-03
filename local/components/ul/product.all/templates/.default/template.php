<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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
$this->setFrameMode(true);
//PR($arResult["IBLOCK"]);
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$sections = $arResult['SECTIONS'][$request->get('CATALOG')]['ITEMS'];
//PR($sections);
$catalogInfo = \CCatalogSku::GetInfoByIBlock($request->get('CATALOG'));
?>
<? foreach ($sections as $section): ?>
	<? $APPLICATION->IncludeComponent('ul:product.section.main', '', [
		'SECTION_ID' => $section['ID'],
		'LIMIT' => 4,
		'IBLOCK_ID' => $section['IBLOCK_ID'],
		'CATALOG_INFO' => $catalogInfo
	]) ?>
<? endforeach; ?>
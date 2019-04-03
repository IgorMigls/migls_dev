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
//dump($arResult);
?>
<div class="b-catalog b-ib-wrapper shop_detail">
	<div class="b-catalog__left b-ib-wrapper">
		<? $categories = $APPLICATION->IncludeComponent(
			'ul:catalog.categories',
			'shop_inner_ultimate',
			array(
				'CITY_ID' => $arResult['SHOPS']['CITY_ID'],
				'SHOP_ID' => $arResult['SHOP_INFO']['ID'],
				'IBLOCK_ID' => $arResult['IBLOCK_ID'],
				'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
				'URL_TEMPLATE' => $arParams['URLS'],
				'SHOP_INFO' => $arResult['SHOP_INFO'],
				'ALL_SHOPS' => $arResult['ALL_SHOPS'],
				'SECTION_ID' => $arResult['SECTION_ID'],
				'SECTIONS' => $arResult['SECTIONS']
			), $component) ?>
	</div>

	<!-- контент -->
	<div class="b-catalog__right b-ib-wrapper ">
		<? $APPLICATION->IncludeComponent(
			'ul:catalog.elements',
			'',
			array(
				'SHOP_INFO' => $arResult['SHOP_INFO'],
				'IBLOCK_ID' => $arResult['IBLOCK_ID'],
				'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
				'URL_TEMPLATE' => $arParams['URLS'],
				'SHOP_FOLDER' => $arParams['SHOP_FOLDER'],
				'CATEGORY_TREE' => $categories[$arResult['IBLOCK_ID']],
				'CURRENT_SECTION' => $arResult['SECTION_ID'],
				'LEVEL' => 2,
				'START_CHAIN_SECTION' => '/shop/',
				'PAGE_LIMIT' => 16
			), $component) ?>
	</div>
</div>
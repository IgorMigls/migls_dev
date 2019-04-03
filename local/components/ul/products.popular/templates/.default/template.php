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
$this->setFrameMode(true);
//PR($arResult['CATALOG_INFO']);
?>
<div class="b-main-products" id="popular_products_app">
	<div class="b-products-block b-ib-wrapper">
		<div class="b-products-block-top b-ib-wrapper">
			<div class="b-products-block-top__left b-ib">
				<div class="b-products-block-top__title">
					<span class="b-icon_hot"></span>
					Популярные товары
				</div>
			</div>
			<div class="b-products-block-top__right b-ib">
				<a href="/catalog/<?=$arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID']?>/" class="b-button b-button_show">Смотреть все</a>
			</div>
		</div>
		<div data-tabs-wrapper="#mainTabs" class="b-products-block-tabs b-ib-wrapper js-tabs">
			<?
			$d = 0;
			foreach ($arResult['SHOP'] as $k => $arShop):
				?>
				<div data-tab="#tab-<?=$d?>" @click="openShop('#tab-<?=$d?>')" :class="['b-products-block-tabs__item b-ib js-tab',{'active': tabActive == 'tab-<?=$d?>'}]">
					<img src="<?=$arShop['PICTURE']['src']?>" alt="<?=$arShop['SHOP_NAME']?>">
					<div class="b-products-block-tabs__item-arrow"></div>
				</div>
			<?
				$d++;
			endforeach;?>
		</div>
		<div id="mainTabs" class="b-products-block-tabs-content b-tabs-wrapper b-ib-wrapper">
			<?
			$i = 0;
			foreach ($arResult['SHOP'] as $k => $arShop):?>
			<div id="tab-<?=$i?>" class="b-products-block-tabs-content__item b-ib-wrapper b-tabs-content js-tab-content <?=($i == 0 ? 'active' : false)?>">
				<div class="b-products-slider">
					<div class="b-products-slider__slider js-products-slider">
						<?foreach ($arShop['ITEMS'] as $l => $arProduct){?>
							<?$q = floatval($arProduct['BASKET_QUANTITY']); ?>

							<product-item :product="<?=CUtil::PhpToJSObject($arProduct)?>"></product-item>


						<?}?>
					</div>
				</div>
			</div>
			<?
				$i++;
			endforeach;?>
		</div>
	</div>
</div>
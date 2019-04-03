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
//PR($arResult);
$shop = array_shift($arResult['SHOP']);
?>
<div class="b-catalog__content b-catalog__all">
	<div class="b-ib-wrapper b-id-wrapper_shadow">
		<div class="b-products-block-top b-ib-wrapper bg_reverse">
			<div class="b-products-block-top__left b-ib">
				<div class="b-products-block-top__title">
					<div class="search__title">Популярные товары</div>
				</div>
			</div>
			<div class="b-products-block-top__right b-ib"><a href="" class="b-button b-button_show">Смотреть все</a></div>
		</div>
		<div class="b-products-slider slider_popular" id="popular_products_app">
			<div class="b-products-slider__slider js-products-slider-search">

				<?foreach ($shop['ITEMS'] as $l => $arProduct){?>
					<div class="b-products-slider__item js-products-slider-item" id="pr_<?=$arProduct['PRODUCT_ID']?>">
						<product-item :product="<?=CUtil::PhpToJSObject($arProduct)?>"></product-item>
					</div>
				<?}?>

			</div>
		</div>
	</div>
</div>
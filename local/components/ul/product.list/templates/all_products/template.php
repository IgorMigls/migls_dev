<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
?>
<div class="b-catalog__content b-ib b-catalog__all">
	<div class="b-content__block b-ib-wrapper">
		<div class="b-products-block-top b-ib-wrapper bg_reverse">
			<div class="b-products-block-top__left b-ib">
				<div class="b-products-block-top__title">
					<span class="b-icon_hot"></span>
					<?=$arResult['IBLOCK']['NAME']?>
				</div>
			</div>
			<div class="b-products-block-top__right b-ib">
				<a href="/catalog/<?=$arResult['IBLOCK']['ID']?>/" class="b-button b-button_show">Смотреть все</a>
			</div>
		</div>
	</div>
	<div class="b-all__item">
		<?foreach ($arResult['ITEMS'] as $productId => $arProduct):?>
			<div class="b-products-slider__item b-products-slider__item_responsive">
			<div class="b-product-preview b-product-preview_border b-ib-wrapper <?= ($arProduct['IN_BASKET'] ? 'b-product-preview_actvie' : false) ?>">
				<div class="b-product__count b-ib">
					<? if ($arProduct['IN_BASKET']): ?>
						<span class="b-count__in"><?= $arProduct['BASKET_QUANTITY'] ?> шт. в корзине</span>
					<?endif;?>
				</div>
				<div class="b-product-preview__pic b-ib">
					<a href="#/catalog/<?= $arProduct['PRODUCT_ID'] ?>">
						<img src="<?= $arProduct['IMG']['src'] ?>" alt="<?=$arProduct['PRODUCT_NAME']?>">
					</a>
				</div>
				<div class="b-product-preview__name b-ib">
					<a href="#/catalog/<?= $arProduct['PRODUCT_ID'] ?>"><?= $arProduct['PRODUCT_NAME'] ?></a>
				</div>
				<div class="b-product-preview__price b-ib">
					<?= $arProduct['PRICE_FORMAT'] ?>
					<span class="b-rouble">&#8381;</span>
				</div>
				<div class="b-product-preview__buy b-ib">
					<form action="" basket-add="">
						<div class="b-product-preview__count b-ib">
							<input type="text" ng-model="quantity" value="1" class="b-product-preview__input quantity_input" />
							<button type="button" ng-click="changeQuantity('+')" class="b-button b-button_plus">+</button>
							<button type="button" ng-click="changeQuantity('-')" class="b-button b-button_minus">–</button>
						</div>
						<div class="b-product-preview__incart b-ib">
							<button type="button" ng-click="addBasket(<?= $arProduct['ID'] ?>)" class="b-button b-button_green">
								В корзину
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?endforeach;?>
	</div>
</div>
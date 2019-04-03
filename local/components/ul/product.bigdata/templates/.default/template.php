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
//$this->setFrameMode(true);
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>
<div class="b-products-slider">
	<div class="b-products-slider__slider js-products-slider-<?=$request->get('id')?> b-products-slider__popup">
		<?foreach ($arResult['ITEMS'] as $arProduct):?>
			<div class="b-products-slider__item js-products-slider-item" id="pr_<?=$arProduct['PRODUCT_ID']?>">
			<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
				<div class="b-product-preview__pic b-ib">
					<a href="#/catalog/<?= $arProduct['PRODUCT_ID'] ?>">
						<img src="<?= $arProduct['PRODUCT_PICTURE']['src'] ?>" alt="" />
					</a>
				</div>
				<div class="b-product-preview__name b-ib">
					<a href="#/catalog/<?=$arProduct['PRODUCT_ID']?>"><?=$arProduct['PRODUCT_NAME']?></a></div>
				<div class="b-product-preview__price b-ib"><?=$arProduct['PRICE_FORMAT']?> <span class="b-rouble">&#8381;</span> / <?=$arProduct['MEASURE_SHORT_NAME']?></div>
				<div class="b-product-preview__buy b-ib">
					<form class="index_products_basket similar_items" basket-add="">
						<div class="b-product-preview__count b-ib">
							<input type="text" class="quantity_input b-product-preview__input" ng-model="quantity" ng-blur="validateQuantity()" ng-init="quantity=<?=$arProduct['MEASURE_RATIO']?>; ratio=<?=$arProduct['MEASURE_RATIO']?>" >
							<button  type="button" class="b-button b-button_plus" ng-click="changeQuantity('+')">+</button>
							<button  type="button" class="b-button b-button_minus" ng-click="changeQuantity('-')">–</button>
						</div>
						<div class="b-product-preview__incart b-ib">
							<a href="javascript:" class="b-button" ng-click="addBasket(<?=$arProduct['ID']?>, <?= $arProduct['PRODUCT_ID'] ?>)" type="button">
								<i class="fa fa-cart-plus"></i>
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?endforeach;?>
	</div>
</div>
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
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>
<div class="b-grid__right b-ib">
	<div class="b-grid__col b-single-slide"><a href="" class="b-grid-slide__title">% Скидки и акции</a>
		<div class="b-products-slider">
			<div class="b-products-slider__slider js-products-slider-3">
				<?foreach ($arResult['ITEMS'] as $k => $arItem):?>
					<div class="b-products-slider__item js-products-slider-item">
						<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
							<div class="b-product-discount"><?=$arItem['DISCOUNT_VAL']?>%</div>
							<div class="b-product-preview__pic b-product-preview__pic_margin b-ib">
								<a href="#/catalog/<?=$arItem['PRODUCT_ID']?>"><img src="<?=$arItem['IMG']['src']?>" alt=""></a>
							</div>
							<div class="b-product-preview__name b-ib">
								<a href="#/catalog/<?=$arItem['PRODUCT_ID']?>"><?= $arItem['PRODUCT_NAME'] ?></a>
							</div>
							<div class="b-product-preview__price b-ib"><?=$arItem['PRICE_FORMAT']?> <span class="b-rouble">&#8381;</span>
							</div>
							<div class="b-product-preview__buy b-ib">
								<form basket-add="">
									<div class="b-product-preview__count b-ib">
										<input type="text" value="1" class="b-product-preview__input quantity_input" ng-model="quantity" ng-init="quantity = 1">
										<button type="button" class="b-button b-button_plus" ng-click="changeQuantity('+')">+</button>
										<button type="button" class="b-button b-button_minus" ng-click="changeQuantity('-')">–</button>
									</div>
									<div class="b-product-preview__incart b-ib">
										<button type="button" ng-click="addBasket(<?=$arItem['ID']?>)" class="b-button b-button_green">В корзину</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?endforeach;?>
				<!--<div class="b-products-slider__item js-products-slider-item">
					<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
						<div class="b-product-discount">15%</div>
						<div class="b-product-preview__pic b-product-preview__pic_margin b-ib">
							<a href=""><img src="images/demo/product-preview-1.jpg" alt=""></a>
						</div>
						<div class="b-product-preview__name b-ib"><a href="">
								Молоко кокосовое
								Suzi-Wan 200мл</a></div>
						<div class="b-product-preview__price b-ib">258 <span class="b-rouble">&#8381;</span>
						</div>
						<div class="b-product-preview__buy b-ib">
							<form action="">
								<div class="b-product-preview__count b-ib">
									<input type="text" value="1" class="b-product-preview__input">
									<button class="b-button b-button_plus">+</button>
									<button class="b-button b-button_minus">–</button>
								</div>
								<div class="b-product-preview__incart b-ib">
									<button type="submit" class="b-button b-button_green">В
										корзину
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="b-products-slider__item js-products-slider-item">
					<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
						<div class="b-product-discount">15%</div>
						<div class="b-product-preview__pic b-product-preview__pic_margin b-ib">
							<a href=""><img src="images/demo/product-preview-1.jpg" alt=""></a>
						</div>
						<div class="b-product-preview__name b-ib"><a href="">
								Молоко кокосовое
								Suzi-Wan 200мл</a></div>
						<div class="b-product-preview__price b-ib">258 <span class="b-rouble">&#8381;</span>
						</div>
						<div class="b-product-preview__buy b-ib">
							<form action="">
								<div class="b-product-preview__count b-ib">
									<input type="text" value="1" class="b-product-preview__input">
									<button class="b-button b-button_plus">+</button>
									<button class="b-button b-button_minus">–</button>
								</div>
								<div class="b-product-preview__incart b-ib">
									<button type="submit" class="b-button b-button_green">В
										корзину
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="b-products-slider__item js-products-slider-item">
					<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
						<div class="b-product-discount">15%</div>
						<div class="b-product-preview__pic b-product-preview__pic_margin b-ib">
							<a href=""><img src="images/demo/product-preview-1.jpg" alt=""></a>
						</div>
						<div class="b-product-preview__name b-ib"><a href="">
								Молоко кокосовое
								Suzi-Wan 200мл</a></div>
						<div class="b-product-preview__price b-ib">258 <span class="b-rouble">&#8381;</span>
						</div>
						<div class="b-product-preview__buy b-ib">
							<form action="">
								<div class="b-product-preview__count b-ib">
									<input type="text" value="1" class="b-product-preview__input">
									<button class="b-button b-button_plus">+</button>
									<button class="b-button b-button_minus">–</button>
								</div>
								<div class="b-product-preview__incart b-ib">
									<button type="submit" class="b-button b-button_green">В
										корзину
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>-->
			</div>
		</div>
	</div>
</div>
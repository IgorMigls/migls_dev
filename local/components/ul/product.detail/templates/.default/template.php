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
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
//dump($arResult);
?>
<detail-product style="height: 100%">
	<div class="mfp-bg"></div>
	<div class="detail_product_wrap">
		<div class="detail_product b-popup b-popup-card">
			<button class="b-popup__close" ng-click="closeProduct('<?=$request->get('back')?>')"></button>
			<div class="b-popup__products b-ib-wrapper">
				<div class="b-popup__row">
					<?//PR($request->toArray());?>
					<div class="b-popup__col_1 b-ib">
						<!--<div class="big__rate">
							<input type="radio" name="rating" value="5" id="rating5">
							<label for="rating5"></label>
							<input type="radio" name="rating" value="4" id="rating4">
							<label for="rating4"></label>
							<input type="radio" name="rating" value="3" id="rating3">
							<label for="rating3"></label>
							<input type="radio" name="rating" value="2" id="rating2">
							<label for="rating2"></label>
							<input type="radio" name="rating" value="1" id="rating1">
							<label for="rating1"></label>
						</div>-->
						<div class="b-prod__img zummer_img"
								style="background: url(<?=$arResult['DETAIL_IMG']['ORIGINAL']?>) no-repeat center center">
							<img style="display: none" src="<?=$arResult['DETAIL_IMG']['ORIGINAL']?>" alt="" />
						</div>
						<?if($USER->IsAuthorized()):?>
							<div class="favourite__print">
								<a href="javascript:" add-favorite="<?=$arResult['ID']?>"
										class="<?if($arResult['IN_FAVORITE']['ID']) echo 'in_favorite '?>favourite">
									В избранное
								</a>
	<!--							<a href="" class="print print__sup">Пжаловаться </a>-->
							</div>
						<?endif;?>
					</div>
					<div class="b-popup__col_2 b-ib">
						<div class="b-popup-product__wrapper">
							<?if(count($arResult['CHAIN']) > 0):?>
								<ul class="b-bread">

									<? foreach ($arResult['CHAIN'] as $k => $arChain): ?>
										<li class="bread__list">
											<a href="<?= $arChain['URL'] ?>" class="bread__item"><?= $arChain['NAME'] ?></a>
										</li>
									<? endforeach; ?>
								</ul>
							<?endif;?>
							<h1 class="popup__h1"><?=$arResult['NAME']?></h1>
							<div class="b-popup__descr">
                                <?=$arResult['PREVIEW_TEXT']?></div>
							<div class="b-product-preview__price b-ib b-popup_price"><?=$arResult['PRICE_FORMAT']?> <span class="b-rouble">&#8381;</span> / <?=$arResult['SKU']['MEASURE_SHORT_NAME']?></div>
							<div class="b-product-preview__buy b-ib b-popup_preview">
								<form class="index_products_basket" basket-add="">
									<div class="b-product-preview__count b-ib">
										<input type="text" class="quantity_input b-product-preview__input" ng-model="quantity" ng-init="quantity = <?=$arResult['SKU']['MEASURE_RATIO']?>; ratio=<?=$arResult['SKU']['MEASURE_RATIO']?>" ng-blur="validateQuantity()" value="<?=$arResult['SKU']['MEASURE_RATIO']?>">
										<button  type="button" class="b-button b-button_plus" ng-click="changeQuantity('+')">+</button>
										<button  type="button" class="b-button b-button_minus" ng-click="changeQuantity('-')">–</button>
                                        <span class="input-measure"><?=$arResult['SKU']['MEASURE_SHORT_NAME']?></span>
									</div>
									<div class="b-product-preview__incart b-ib">
										<button ng-click="addBasket(<?=$arResult['REMAIN_ID']?>, <?=$arResult['ID']?>)" type="button" class="b-button b-button_green add_basket_btn">В корзину</button>
									</div>
								</form>
							</div>
							<?/*<ul class="b-popup-lists">
								<?foreach ($arResult['PROPERTIES'] as $code => $arProp):?>
									<li class="lists__element">
									<span class="lists__item">
										<?=$arProp['VALUE']['TEXT']?>
									</span>
									</li>
								<?endforeach;?>
							</ul>*/?>
							<div class="show_detail_text"><?=$arResult['DETAIL_TEXT']?></div>
						</div>
					</div>
				</div>
				<div class="b-popup__slider">
					<div class="b-same__wrapper">
						<div data-tabs-wrapper="#mainTabs" class="b-same__products js-tabs">
							<button data-tab="#tab-1" class="button__tab js-tab active">С этим товаром покупают</button>
							<button data-tab="#tab-2" class="button__tab js-tab">Похожие товары</button>
						</div>
					</div>
					<div id="mainTabs" class="b-products-block-tabs-content b-tabs-wrapper b-ib-wrapper">
						<div id="tab-1" class="b-products-block-tabs-content__item b-ib-wrapper b-tabs-content js-tab-content active">
							<?$APPLICATION->IncludeComponent('ul:product.bigdata', '', [
								'SHOP_ID' => $arResult['SHOP']['VALUE'],
								'REMAIN_ID' => $arResult['ID'],
								'IBLOCK_SKU_ID' => $arResult['SKU_IBLOCK']
							], $component)?>
						</div>
						<div id="tab-2" class="b-products-block-tabs-content__item b-ib-wrapper b-tabs-content js-tab-content">
							<?$APPLICATION->IncludeComponent('ul:product.similar', '', [
								'IBLOCK_SKU_ID' => $arResult['SKU_IBLOCK'],
								'FILTER' => [
									'!PRODUCT.ID' => $arResult['ID'],
									'=PRODUCT.IBLOCK_SECTION_ID' => $arResult['IBLOCK_SECTION_ID'],
								],
								'SHOP_ID' => $arResult['SHOP']['VALUE']
							], $component)?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="as_overlay" style="display: block" ng-click="closeProduct('<?=$request->get('back')?>')"></div>
</detail-product>
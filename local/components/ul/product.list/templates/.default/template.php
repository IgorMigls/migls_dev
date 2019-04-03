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

use \Bitrix\Main\Web\Uri;

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$styleBg = false;
if(strlen($arResult['SECTION']['IMG']['src']) > 0){
	$styleBg = 'style="background: url('.$arResult['SECTION']['IMG']['src'].') no-repeat right, linear-gradient(to left, #fac10e 0%, #fbc902 30%, #fcd000 65%, #ffe800 100%);"';
}
?>
<div class="b-catalog__right b-ib-wrapper">
	<div class="b-content__bread">
		<ul class="b-bread">
			<? if (!is_null($arResult['SECTION'])): ?>
				<? foreach ($arResult['SECTION']['CHAIN'] as $i => $arChain): ?>
					<? if ($i + 1 == count($arResult['SECTION']['CHAIN'])): ?>
						<li class="bread__list"><?= $arChain['NAME'] ?></li>
					<? else: ?>
						<li class="bread__list"><a href="<?= $arChain['URL'] ?>" class="bread__item"><?= $arChain['NAME'] ?></a></li>
					<? endif; ?>
				<? endforeach; ?>
			<? endif; ?>
		</ul>
	</div>
	<div class="b-catalog__content b-ib">
		<div class="b-content__block b-ib-wrapper">
			<div class="b-products-block-top b-ib-wrapper b-products-block-top__reverse" <?=$styleBg?>>
				<div class="b-ib">
					<div class="b-products-block-top__title">
						<span class="b-icon_hot"></span>
						<? if (!is_null($arResult['SECTION'])) {
							echo $arResult['SECTION']['NAME'];
						} else {
							$arResult['IBLOCK']['NAME'];
						} ?>
					</div>
				</div>
			</div>
		</div>
		<div class="b-filter__result">
			<div class="b-header-popup__top-left b-header-popup_catalog-left b-ib">
				<div class="b-catalog__filter-left b-ib">Упорядочить</div>
				<div class="b-header-popup__filter b-header-popup__filter_catalog b-ib">
					<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
						<?
						$arSortCode = [
							'DEFAULT' => 'Стандартно',
							'PRODUCT.DATE_CREATE' => 'По дате',
							'PRICE.PRICE' => 'По цене',
						];
						$resultSort = [];
						$Uri = new Uri($request->getRequestUri());
						$Uri->deleteParams(['order', 'sort']);

						foreach ($arSortCode as $item => $value) {
							$Uri = new Uri($request->getRequestUri());
							$order = $request->get('order');

							if (!$order || $order == 'desc') {
								$order = 'asc';
							}
							$Uri->addParams(['order' => $order, 'sort' => $item]);

							$resultSort[$item] = $Uri->getUri();
							if ($item == 'DEFAULT') {
								$Uri->deleteParams(['order', 'sort']);
								$resultSort['DEFAULT'] = $Uri->getUri();
							}
							unset($Uri);
						}
						?>

						<select class="b-custom-select js-custom-select2" id="sortSelectCatalog">
							<? foreach ($resultSort as $code => $sortable): ?>
								<option value="<?= $sortable ?>"
									<?= ($request->get('sort') == $code ? ' selected ' : false) ?>>
									<?= $arSortCode[$code] ?>
								</option>
							<? endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<? /*if(!is_null($arResult['SECTION'])):?>
					<div class="b-filter__result-items b-ib">
						<a href="" class="result__item">Объем, л: 0,2—0,31<span class="del__item"></span>
						</a><a href="" class="result__item">Жирность, %: 2,5—3,3 <span class="del__item"></span></a>
						<a href="" class="result__item">Молоко<span class="del__item"></span></a>
						<a href="" class="result__item">Козье<span class="del__item"></span></a>
					</div>
				<?endif*/ ?>
		</div>
		<div class="b-content__item">
			<? foreach ($arResult['ITEMS'] as $k => $arProduct): ?>
				<div class="b-products-slider__item b-products-slider__item_responsive">
					<div class="b-product-preview b-product-preview_border b-ib-wrapper <?= ($arProduct['IN_BASKET'] ? 'b-product-preview_actvie' : false) ?>">
						<div class="b-product__count b-ib">
							<? if ($arProduct['IN_BASKET']): ?>
								<span class="b-count__in"><?= $arProduct['BASKET_QUANTITY'] ?> шт. в корзине</span>
							<? endif; ?>
						</div>
						<div class="b-product-preview__pic b-ib">
							<a href="#/catalog/<?= $arProduct['PRODUCT_ID'] ?>">
								<img src="<?= $arProduct['IMG']['src'] ?>" alt="<?= $arProduct['PRODUCT_NAME'] ?>">
							</a>
						</div>
						<div class="b-product-preview__name b-ib">
							<a href="#/catalog/<?= $arProduct['PRODUCT_ID'] ?>"><?= $arProduct['PRODUCT_NAME'] ?></a>
						</div>
						<div class="b-product-preview__price b-ib"><?= $arProduct['PRICE_FORMAT'] ?> <span
								class="b-rouble">&#8381;</span></div>
						<div class="b-product-preview__buy b-ib">
							<form class="index_products_basket" basket-add="">
								<div class="b-product-preview__count b-ib">
									<input type="text" ng-init="quantity = 1" ng-model="quantity"
									       class="quantity_input b-product-preview__input">
									<button type="button" class="b-button b-button_plus" ng-click="changeQuantity('+')">
										+
									</button>
									<button type="button" class="b-button b-button_minus"
									        ng-click="changeQuantity('-')">–
									</button>
								</div>
								<div class="b-product-preview__incart b-ib">
									<button ng-click="addBasket(<?= $arProduct['ID'] ?>)" type="button"
									        class="b-button b-button_green add_basket_btn">В корзину
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<? $APPLICATION->IncludeComponent(
		"bitrix:main.pagenavigation",
		"",
		array(
			"NAV_OBJECT" => $arResult['NAV'],
			"SEF_MODE" => "N",
			'TEMPLATE_THEME' => 'yellow',
		),
		false
	); ?>
</div>

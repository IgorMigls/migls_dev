<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
?>
<div class="b-catalog b-ib-wrapper catalog_search_wrap">
	<div class="b-catalog__left b-ib-wrapper">
		<div style="height: 35px"></div>
		<? $APPLICATION->IncludeComponent('ul:catalog.categories', '', array(
			'CITY_ID' => $arResult['SHOPS']['CITY_ID'],
			'SHOP_ID' =>$arResult['SHOPS']['SHOP_ID'],
//			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
//			'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
			'URL_TEMPLATE' => [
				"CATALOG_URL" => "/catalog/#IBLOCK_ID#/",
				"INDEX_URL" => "/catalog/",
				"SECTION_URL" => "/catalog/#IBLOCK_ID#/#SECTION_ID#/",
			],
			'SHOW_MAIN_HEAD' => 'Y'
		), $component) ?>
	</div>
	<div class="b-catalog__right b-ib-wrapper">
		<div class="b-content__bread">
			<ul class="b-bread">
				<li class="bread__list"><a href="/" class="bread__item">Главная</a></li>
				<li class="bread__list"><a href="/catalog/" class="bread__item">Каталог</a></li>
				<li class="bread__list">Результаты поиска</li>
			</ul>
		</div>
		<?if(count($arResult['SHOP_ITEMS']) == 0){?>
			<div class="b-search__result">
				<p class="search__result">
					По запросу «<?=$arResult['REQUEST']['QUERY']?>» товары не найдены.
				</p><span class="search__help">
					Попробуйте уточнить поисковый запрос, либо выберите нужные товары слева из каталога.
				</span>
			</div>
		<? }else{?>
			<?foreach ($arResult['SHOP_ITEMS'] as $arProductSearch):
//				dump($arProductSearch);
				?>
				<div class="b-catalog__content b-catalog__all search_page">
					<div class="b-ib-wrapper b-id-wrapper_shadow">

						<div class="b-content__block b-ib-wrapper">
							<div class="b-products-block-top b-ib-wrapper bg_reverse"
									style="background:url(<?=$arSection['LINE_IMG']['SRC']?>) no-repeat right, linear-gradient(to left, #fac10e 0%, #fbc902 30%, #fcd000 65%, #ffe800 100%)">
								<div class="all_products_line">
									<div class="b-products-block-top__left b-ib">
										<div class="b-products-block-top__title"><?=$arProductSearch['INFO']['NAME']?></div>
									</div>
								</div>
								<div class="b-products-block-top__right b-ib">
									<a href="/shop/<?=$arProductSearch['INFO']['ID']?>/" class="b-button b-button_show">Смотреть все</a>
								</div>
							</div>

						</div>

						<?/*<div class="b-products-block-top b-ib-wrapper bg_reverse">
							<div class="b-products-block-top__left b-ib">
								<div class="b-products-block-top__title">
									<?$img = \CFile::ResizeImageGet(
										$arProductSearch['INFO']['PICTURE'],
										['width'=>60, 'height'=>60],
										BX_RESIZE_IMAGE_PROPORTIONAL_ALT
									)?>
									<img src="<?=$img['src']?>" alt="" class="b-icon b-ib">
									<div class="search__title b-ib"><?=$arProductSearch['INFO']['NAME']?></div>
								</div>
							</div>
							<div class="b-products-block-top__right b-ib">
								<a href="/shop/<?=$arProductSearch['INFO']['ID']?>" class="b-button b-button_show">Смотреть все</a>
							</div>
						</div>*/?>

						<div class="b-products-slider">
							<div class="b-products-slider__slider js-products-slider-search popular_products_app">
								<?foreach ($arProductSearch['ITEMS'] as $k => $arProduct):
									$arProduct['PRODUCT_PICTURE'] = $arProduct['IMG'];
									$arProduct['ID'] = $arProduct['PRICE_ID'];
									?>
									<product-item :product="<?=CUtil::PhpToJSObject($arProduct)?>" key="<?=$arProduct['ID']?>"></product-item>

									<!--<div class="b-products-slider__item js-products-slider-item">
										<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
											<div class="b-product-preview__pic b-ib">
												<a href="#/catalog/<?/*= $arProduct['PRODUCT_ID'] */?>">
													<img src="<?/*=$arProduct['IMG']['src']*/?>" alt="<?/*=$arProduct['PRODUCT_NAME']*/?>">
												</a>
											</div>
											<div class="b-product-preview__name b-ib">
												<a href="#/catalog/<?/*= $arProduct['PRODUCT_ID'] */?>">
													<?/*= $arProduct['PRODUCT_NAME'] */?>
												</a>
											</div>
											<div class="b-product-preview__price b-ib"><?/*= $arProduct['PRICE_FORMAT'] */?>
												<span class="b-rouble">&#8381;</span></div>
											<div class="b-product-preview__buy b-ib">
												<form basket-add="">
													<div class="b-product-preview__count b-ib">
														<input ng-model="quantity" type="text" value="1"
																class="quantity_input b-product-preview__input">
														<button type="button" ng-click="changeQuantity('+')" class="b-button b-button_plus">+</button>
														<button type="button" ng-click="changeQuantity('-')" class="b-button b-button_minus">–</button>
													</div>
													<div class="b-product-preview__incart b-ib">
														<button ng-click="addBasket(<?/*= $arProduct['PRICE_ID'] */?>)"
																type="button" class="b-button b-button_green">В корзину</button>
													</div>
												</form>
											</div>
										</div>
									</div>-->
								<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		<?}?>
		<? /*$APPLICATION->IncludeComponent('ul:products.popular',
			'shop_detail', ['IBLOCK_SKU_ID' => 99], false) */?>	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('#sidebar .js-toggle-class').click();
	});
</script>
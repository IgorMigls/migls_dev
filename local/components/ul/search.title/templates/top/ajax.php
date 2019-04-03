<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
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
/** @var \Bitrix\Main\HttpRequest $request */
if (!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result">

		<? foreach ($arResult['WORDS'] as $word):?>
			<tr style="padding-top: 20px">
				<td colspan="4" class="words_items title-search-item item_first">
					<a onclick="setQuerySearchWord(this)" href="javascript:"><?=$word?></a>
				</td>
			</tr>
		<? endforeach; ?>

		<tr>
			<td colspan="4">
				<div class="clear_hr"></div>
			</td>
		</tr>
		<? foreach ($arResult["CATEGORIES"] as $category_id => $arCategory):
			$categoryExist = false;
			?>
			<? foreach ($arCategory["ITEMS"] as $i => $arItem) {
				if (substr($arItem['ITEM_ID'], 0, 1) == 'S'){
//					Bitrix\Iblock\SectionTable::getRow();
					$categoryExist = true;
					?>
					<?if($categoryExist): ?>
						<tr>
							<td colspan="4">
								<div class="title_cat_search">Категории</div>
							</td>
						</tr>
					<?endif;?>
					<tr>
						<td colspan="4" class="title-search-item item_first">
							<div class="section_item_search">
								<? preg_match('#\d+#', $arItem['ITEM_ID'], $secId);?>
								<a href="/<?=($arResult['SHOP_CURRENT_ID'] ? 'shop' : 'catalog')?>/<?=($arResult['SHOP_CURRENT_ID'] ? $arResult['SHOP_CURRENT_ID'].'/' : false)?><?=$arItem['PARAM2']?>/<?=$secId[0]?>/"><?=$arItem['NAME']?></a>
							</div>
						</td>
					</tr>
				<? } ?>
			<? } ?>
		<? endforeach; ?>
		<tr>
			<td colspan="4">
				<div class="title_cat_search">Товары</div>
			</td>
		</tr>
		<? foreach ($arResult["ELEMENTS"] as $i => $arElement):
			if (is_array($arElement)){
				?>
				<tr>
					<td class="item_first">
						<? if (is_array($arElement["PICTURE"])):?>
							<a href="#/catalog/<?=$arElement['ID']?>" class="item_link_search">
								<img align="left" src="<? echo $arElement["PICTURE"]["src"] ?>"
									width="<? echo $arElement["PICTURE"]["width"] ?>"
									height="<? echo $arElement["PICTURE"]["height"] ?>">
							</a>
						<? endif; ?>
					</td>
					<td class="title-search-item">
						<a href="#/catalog/<?=$arElement['ID']?>" class="item_link_search">
							<? echo $arElement["NAME"] ?>
						</a>
					</td>
					<td>
						<? if (count($arElement["PRICES"]) > 0):?>
						<nobr>
							<span class="catalog-price"><?=$arElement["PRICES"]['PRICE_FORMAT']?>
								<span class="b-rouble">&#8381;</span>
							</span>
						</nobr>
						<? endif; ?>
					</td>
					<td class="item_last">
						<?if(in_array($arElement['PRICES']['SHOP_ID'], $_SESSION['REGIONS']['SHOP_ID'])):?>
							<form class="index_products_basket" basket-add="">
								<div class="b-product-preview__count b-ib">
									<input type="hidden" ng-init="quantity = 1" value="1" ng-model="quantity"
									       class="quantity_input b-product-preview__input">
								</div>
								<div class="b-product-preview__incart b-ib">
									<!--<button ng-click="addBasket(<?/*= $arElement['PRICES']['SKU_ID'] */?>)"
									        type="button"
									        class="b-button b-button_green add_basket_btn">
										<span class="basket_search_icon"></span>
									</button>-->
								</div>
							</form>
						<?endif;?>
					</td>
				</tr>
			<? } ?>
		<? endforeach; ?>
		<tr>
			<td colspan="4" class="title-search-all">
				<?
				$q = $arResult['query'];
				if(strlen($arResult['alt_query']) > 0){
					$q = $arResult['alt_query'];
				}
				$uri = new \Bitrix\Main\Web\Uri('/search/');
				$paramsQuery['q'] = $q;
				if((int)$arResult['SHOP_CURRENT_ID'] > 0){
					$paramsQuery['shop'] = $arResult['SHOP_CURRENT_ID'];
				}
				$uri->addParams($paramsQuery);
				?>
				<a href="<?=$uri->getUri()?>">Все результаты</a>
			</td>
		</tr>
	</table>
	<div class="title-search-fader"></div>
<? endif; ?>
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
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$this->setFrameMode(true);

$imgSections = $arResult['SECTION_INFO']['CHAIN_IMG'];
asort($imgSections);
foreach ($imgSections as $sectionImg) {
	if(is_array($sectionImg['DETAIL_IMG'])){
		$mageLine = $sectionImg['DETAIL_IMG'];
		break;
	}
}
?>

<div class="b-content__bread">
	<ul class="b-bread">
		<li class="bread__list"><a href="/" class="bread__item">Главная</a></li>
		<? foreach ($arResult['URL_CHAIN'] as $k => $item) { ?>
			<li class="bread__list">
				<? if ($k + 1 < count($arResult['URL_CHAIN'])){ ?>
					<a href="<?=$item['URL']?>" class="bread__item"><?=$item['NAME']?></a>
				<? } else {
					echo $item['NAME'];
				} ?>
			</li>
		<? } ?>
	</ul>
</div>

<div class="b-catalog__content b-ib b-catalog__all">
	<div class="b-content__block b-ib-wrapper">
		<div class="b-products-block-top b-ib-wrapper bg_reverse"
				style="background:url(<?=$mageLine['SRC']?>) no-repeat right, linear-gradient(to left, #fac10e 0%, #fbc902 30%, #fcd000 65%, #ffe800 100%)">

			<div class="b-products-block-top__left b-ib">
				<div class="b-products-block-top__title">
					<?=$arResult['SECTION_INFO']['NAME']?>
				</div>
			</div>
		</div>
	</div>
	<div class="b-filter__result">
		<div class="b-header-popup__top-left b-header-popup_catalog-left b-ib">
			<div class="b-catalog__filter-left b-ib">Упорядочить</div>
			<div class="b-header-popup__filter b-header-popup__filter_catalog b-ib">
				<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
					<select class="b-custom-select js-custom-select2" onchange="window.location.assign(this.value)">
						<?foreach ($arResult['SORT_URL'] as $code => $uri):?>
							<option value="<?=$uri['URI']?>" <?=($request->get('sortBy') == $code ? 'selected="selected"': false)?>>
								<?=$uri['NAME']?>
							</option>
						<?endforeach;?>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="b-all__item" id="popular_products_app">
		<?
		foreach ($arResult['ITEMS'] as $arElement):
//			PR($arElement);
			$arElement['PRODUCT_PICTURE'] = $arElement['IMAGE'];
			$arElement['ID'] = $arElement['SKU_ID'];
			unset($arElement['IMAGE'], $arElement['SKU_ID']);

			$q = (float)$arElement['BASKET']['QUANTITY']; ?>

			<product-item :product="<?=CUtil::PhpToJSObject($arElement)?>"></product-item>

		<?endforeach; ?>
	</div>
</div>

<?
$APPLICATION->IncludeComponent(
	"bitrix:main.pagenavigation",
	"",
	array(
		"NAV_OBJECT" => $arResult['NAV'],
		"SEF_MODE" => "N",
		"TEMPLATE_THEME" => 'yellow'
	),
	$component
);
?>
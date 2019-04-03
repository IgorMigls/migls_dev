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
$deliverCurrentTime = $arParams['SHOP_INFO']['DELIVERY_TIME'];
$deliverCurrent = $arParams['SHOP_INFO']['CURRENT'];

$this->setFrameMode(true);
$this->addExternalCss($componentPath.'/templates/.default/style.css');
$this->addExternalJs($componentPath.'/templates/.default/script.js');
$nearDelivery = false;
foreach ($deliverCurrentTime[0]['ITEMS'] as $item){
	if($item['DISABLED'] === false){
		$nearDelivery = $item['PROPERTY_TIME_FROM_VALUE'].' &mdash; '.$item['PROPERTY_TIME_TO_VALUE'];
		break;
	}
}
if($nearDelivery === false){
	$item = $deliverCurrentTime[1]['ITEMS'][0];
	$nearDelivery = 'Завтра  '.$item['PROPERTY_TIME_FROM_VALUE'].' &mdash; '.$item['PROPERTY_TIME_TO_VALUE'];
}
?>
	<div class="b-sidebar__delivery">
		<div class="b-delivery__content">
			<div class="b-img-wrapper">
				<a href="/shop/<?=$arParams['SHOP_INFO']['ID']?>/">
					<img src="<?=$arParams['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arParams['SHOP_INFO']['NAME']?>" />
				</a>
			</div>
		</div>
		<div class="b-delivery__close">
			<? if ($deliverCurrent): ?>
				Ближайшая доставка:
				<span>
					<?=$deliverCurrent['NAME']?>,
					<?=$deliverCurrent['DATE_FORMAT']['DAY']?> <?=$deliverCurrent['DATE_FORMAT']['MONTH_LOCALE']?>
					<p style="margin-top: 7px"><?=$deliverCurrent['CURRENT']['TIME_FROM']?> &ndash; <?=$deliverCurrent['CURRENT']['TIME_TO']?></p>
				</span>
			<? endif; ?>
		</div>
		<div class="b-delivery__interval">
			<a href="javascript:" id="show_all_interval" class="b-button b-button_delivery">Все интервалы</a>
<!--			<a href="javascript:" class="b-button b-button_delivery">Дополнительно</a>-->
		</div>
	</div>

<?
//dump($arParams);
$arResult['SECTIONS'] = $arParams['SECTIONS'];
?>

	<div class="b-sidebar__catalog-wrapper" id="sidebar">
		<div class="b-sidebar__catalog__head">
			<div class="b-ctalog__title b-ib">Каталог товаров</div>
			<div class="b-main-sections__more b-main-sections__more_sidebar b-ib">
				<button data-toggle-element="#sidebar" data-toggle-class="open" class="b-button js-toggle-class"></button>
			</div>
		</div>
		<div class="b-sidebar__catalog b-sidebar__catalog_white b-sidebar__off">
			<div class="b-catalog__favorite">
				<ul class="catalog__acordion catalog__acordion_new-icons">
					<?if($USER->IsAuthorized()):?>
					<li class="catalog__list">
						<a href="/lk/favorite" class="catalog__title result__item favorite_item">
							<span></span>Избранное
						</a>
					</li>
					<br /><br />
					<?endif;?>
					<? foreach ($arResult['SECTIONS'] as $iblockId => $arType): ?>
						<? if (count($arType['ITEMS']) > 0){?>
							<li class="catalog__list <?=($arResult['COMPONENT_PARENT']['IBLOCK_ID'] == $iblockId ? 'open_item_menu' : false)?>">

								<a href="#" class="catalog__title catalog__title_grey result__item">
									<? if (!empty($arType['ICON']['SRC'])): ?>
										<i style="background: url(<?=$arType['ICON']['SRC']?>) no-repeat center center"></i>
									<? endif; ?>
									<span><?=$arType['CATEGORY_NAME']?></span>
								</a>
								<ul class="catalog__sub">
									<? foreach ($arType['ITEMS'] as $arItem):
										$url = '/shop/'.$arParams['SHOP_INFO']['ID'].'/';
										$url .= $arItem['IBLOCK_ID'].'/'.$arItem['ID'].'/';
										if(count($arItem['SUBSECTION']) > 0){
											$url = 'javascript:';
										}
										?>
										<li class="catalog__item__sub">
											<a href="<?=$url?>"
													class="catalog__item <?=($arParams['SECTION']['ID'] == $arItem['SECTION_ID'] ? 'active_category' : false)?>">
												<?=$arItem['NAME']?>
											</a>
											<?if(count($arItem['SUBSECTION']) > 0):?>
												<ul class="subcategory">
													<? foreach ($arItem['SUBSECTION'] as $sub) {
														$url = '/shop/'.$arParams['SHOP_INFO']['ID'].'/';
														$url .= $sub['IBLOCK_ID'].'/'.$sub['ID'].'/';
														?>
														<li>
															<a class="catalog__item" href="<?=$url?>"><?=$sub['NAME']?></a>
														</li>
													<?}?>
												</ul>
											<?endif;?>
										</li>
									<? endforeach; ?>
								</ul>
							</li>
						<? } ?>

					<? endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
<?
$APPLICATION->IncludeComponent(
	"ul:catalog.smart.filter",
	"",
	array(
		"IBLOCK_TYPE" =>'catalog',
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"SECTION_ID" => $arParams['SECTION_ID'],
		"FILTER_NAME" => 'filterCatalog',
		"PRICE_CODE" => '1',
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SAVE_IN_SESSION" => "N",
		"FILTER_VIEW_MODE" => 'vertical',
		"XML_EXPORT" => "Y",
		"SECTION_TITLE" => "NAME",
		"SECTION_DESCRIPTION" => "DESCRIPTION",
		'HIDE_NOT_AVAILABLE' => 'N',
		"TEMPLATE_THEME" => 'yellow',
		'CONVERT_CURRENCY' => 'N',
		'CURRENCY_ID' => 'RUB',
		"SEF_MODE" => 'N',
		"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
		"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);
?>
<? include ($_SERVER['DOCUMENT_ROOT'].$componentPath.'/include/timeIntervalPopup.php');?>
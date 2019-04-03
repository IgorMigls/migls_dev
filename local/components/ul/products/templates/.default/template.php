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
/** @var \Bitrix\Main\HttpRequest $request */
$this->setFrameMode(true);
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
global $filterCatalog;
?>
<div class="b-catalog b-ib-wrapper">
	<div class="b-catalog__left b-ib-wrapper">
		<!--<div class="b-sidebar__delivery">
			<div class="b-delivery__content">
				<div class="b-img-wrapper"><img src="/local/dist/images/demo/main-sections_auchan.jpg" alt=""></div>
			</div>
			<div class="b-delivery__close">Ближайшая доставка: <span>15:00-17:00</span></div>
			<div class="b-delivery__interval"><a href="" class="b-button b-button_delivery">Все интервалы</a><a href="" class="b-button b-button_delivery">Дополнительно</a></div>
		</div>-->
		<div style="height: 35px"></div>
		<?$APPLICATION->IncludeComponent(
			"ul:products.category",
			"left_menu",
			array(
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"DEPTH_LEVEL" => "1",
				"IBLOCK_TYPE" => "catalog"
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"ul:catalog.smart.filter",
			"",
			array(
				"IBLOCK_TYPE" =>'catalog',
				"IBLOCK_ID" => $request->get('CATALOG'),
				"SECTION_ID" => $request->get('CAT'),
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
	</div>
	<?$APPLICATION->IncludeComponent('ul:product.list', '',
		array(
			'COUNT_PAGE'=>12,
			'SORT'=> $request->get('sort'),
			'ORDER'=>$request->get('order'),
			'FILTER_VALUES' => $filterCatalog,
			'CACHE_TYPE' => 'N'
//	    	'COUNT_TOTAL'=>200
		),
		false,
		array('HIDE_ICONS' => 'Y'));?>
</div>

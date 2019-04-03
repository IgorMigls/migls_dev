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
$this->setFrameMode(true);
//PR($arResult["IBLOCK"]);

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
global $filterCatalog;

//$catalog = $request->get('CATALOG');
//if (empty($catalog)){
//	LocalRedirect('/');
//}
?>
<div class="b-catalog b-ib-wrapper">
	<div class="b-catalog__left b-ib-wrapper">
		<? if ($request->get('CATALOG') && $request->get('SHOP_ID')){

			$APPLICATION->IncludeComponent(
				'ul:shop.detail',
				'left_block',
				array(
					"IBLOCK_TYPE" => 'catalog',
					"IBLOCK_ID" => $request->get('CATALOG'),
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"MESSAGE_404" => $arParams["MESSAGE_404"],
					"SET_STATUS_404" => 'Y',
					"SHOW_404" => 'Y',
					"FILE_404" => $arParams["FILE_404"],
					"CACHE_TYPE" =>'A',
					"CACHE_TIME" => 36000,
					"ID" => $request->get('SHOP_ID'),
				),
				$component
			);
		} ?>

		<? $APPLICATION->IncludeComponent(
			"ul:products.category",
			"left_all",
			array(
				"COMPONENT_TEMPLATE" => "left_all",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"DEPTH_LEVEL" => "1",
				"IBLOCK_TYPE" => "catalog",
				'URL_TEMPLATE' => $arParams['URL_TEMPLATE'],
				'SHOP_ID' => intval($request->get('SHOP_ID')),
				'CITY' => $request->get('CITY'),
				'CATALOG' => $request->get('CATALOG')
			),
			false
		); ?>
	</div>

	<!-- контент -->
	<div class="b-catalog__right b-ib-wrapper">
		<div class="b-content__bread">
			<ul class="b-bread">
				<li class="bread__list"><a href="/" class="bread__item">Главная</a></li>
				<li class="bread__list">Каталог</li>
			</ul>
		</div>
		<?$APPLICATION->IncludeComponent('ul:catalog.products', '', array(), $component)?>
	</div>
</div>

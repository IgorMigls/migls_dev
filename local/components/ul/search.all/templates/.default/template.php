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
$this->setFrameMode(true);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if (strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$shopCurrent = null;
foreach ($arResult['SHOPS'] as $id => $shop) {
	if (preg_match('#'.$shop['ID'].'#', $request->getRequestedPage()) || $request->get('shop') == $shop['ID']){
		$shopCurrent = $shop;
		break;
	}
}
//dump($shopCurrent);
?>
<div id="<? echo $CONTAINER_ID ?>">
	<div class="b-header-search__left b-header-search__left_p b-ib">
		<div class="b-header-popup__top-left b-header-popup__search_wrap b-ib">
			<div class="b-header-popup__filter b-header-popup__filter_search b-ib">
				<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
					<div class="item" style="text-align: center; padding-right: 20px">
						<? if (!is_null($shopCurrent)){
							echo $shopCurrent['NAME'];
						} else { ?>
							По магазинам
						<? } ?>
					</div>
					<!--<select class="b-custom-select js-custom-select2" name="shop">
							<option <? /*=($request->get('shop') == 'all' ? 'selected' : false)*/ ?> value="all">Поиск</option>
							<? /*foreach ($arResult['SHOPS'] as $shop):*/ ?>
								<option <? /*=($request->get('shop') == $shop['ID'] ? 'selected' : false)*/ ?>
									value="<? /*=$shop['ID']*/ ?>"><? /*=$shop['NAME']*/ ?>
								</option>
							<? /*endforeach;*/ ?>
						</select>-->
				</div>
			</div>
		</div>
	</div>
	<? $APPLICATION->IncludeComponent(
		"ul:search.title",
		"top",
		array(
			"FORM_ACTION" => $arResult["FORM_ACTION"],
			"CATEGORY_0" => $arParams['CATEGORY_0'],
			"CATEGORY_0_TITLE" => $arParams['CATEGORY_0_TITLE'],
			"CATEGORY_0_iblock_catalog" => $arParams['CATEGORY_0_iblock_catalog'],
			"CHECK_DATES" => $arParams['CHECK_DATES'],
			"CONTAINER_ID" => $arParams['CONTAINER_ID'],
			"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
			"INPUT_ID" => $arParams['INPUT_ID'],
			"NUM_CATEGORIES" => $arParams['NUM_CATEGORIES'],
			"ORDER" => $arParams['ORDER'],
			"PAGE" => $arParams['PAGE'],
			"PREVIEW_HEIGHT" => $arParams['PREVIEW_HEIGHT'],
			"PREVIEW_TRUNCATE_LEN" => $arParams['PREVIEW_TRUNCATE_LEN'],
			"PREVIEW_WIDTH" => $arParams['PREVIEW_WIDTH'],
			"PRICE_CODE" => $arParams['PRICE_CODE'],
			"PRICE_VAT_INCLUDE" => $arParams['PRICE_VAT_INCLUDE'],
			"SHOW_INPUT" => $arParams['SHOW_INPUT'],
			"SHOW_OTHERS" => $arParams['SHOW_OTHERS'],
			"SHOW_PREVIEW" => $arParams['SHOW_PREVIEW'],
			"TOP_COUNT" => $arParams['TOP_COUNT'],
			"USE_LANGUAGE_GUESS" => $arParams['USE_LANGUAGE_GUESS'],
			"COMPONENT_TEMPLATE" => 'top',
			'SHOP_CURRENT' => $shopCurrent,
		),
		false
	); ?>

</div>

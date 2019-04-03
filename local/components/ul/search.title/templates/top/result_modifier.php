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

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$isShopPage = false;

$arUrl = explode('/', $request->getRequestedPageDirectory());
TrimArr($arUrl);
$isShopPage = array_shift($arUrl) == 'shop' ? true : false;
$shopId = array_shift($arUrl);
$arResult['SHOP_CURRENT_ID'] = $shopId;

//\CBitrixComponent::includeComponentClass('ul:shop.list');

$PREVIEW_WIDTH = intval($arParams["PREVIEW_WIDTH"]);
if ($PREVIEW_WIDTH <= 0)
	$PREVIEW_WIDTH = 75;

$PREVIEW_HEIGHT = intval($arParams["PREVIEW_HEIGHT"]);
if ($PREVIEW_HEIGHT <= 0)
	$PREVIEW_HEIGHT = 75;

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arCatalogs = false;

$arResult["ELEMENTS"] = array();
$arResult["SEARCH"] = array();
$resultCnt = 0;
$arResult['SHOP_CURRENT_ID'] = $arParams['SHOP_CURRENT']['ID'];

$sectionIB = $arSection = $words = [];

foreach ($arResult["CATEGORIES"] as $category_id => $arCategory) {
	foreach ($arCategory["ITEMS"] as $i => $arItem) {
		if (isset($arItem["ITEM_ID"])){
			$arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];
			if ($arItem["MODULE_ID"] == "iblock" && substr($arItem["ITEM_ID"], 0, 1) !== "S"){
				if ($arCatalogs === false){
					$arCatalogs = array();
					if (CModule::IncludeModule("catalog")){
						$rsCatalog = CCatalog::GetList(array(
							"sort" => "asc",
						));
						while ($ar = $rsCatalog->Fetch()) {
							if ($ar["PRODUCT_IBLOCK_ID"])
								$arCatalogs[$ar["PRODUCT_IBLOCK_ID"]] = 1;
							else
								$arCatalogs[$ar["IBLOCK_ID"]] = 1;
						}
					}
				}

				if (array_key_exists($arItem["PARAM2"], $arCatalogs)){
					$arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
				}
			} else {
				$idSection = intval(str_replace('S', '', $arItem['ITEM_ID']));
				if (intval($idSection) > 0){
					$section = Bitrix\Iblock\SectionTable::getRow([
						'select' => ['ID', 'CODE', 'NAME', 'IBLOCK_ID'],
						'filter' => ['=ID' => $idSection, 'IBLOCK_ID' => $arItem['PARAM2']],
					]);
					$section['DETAIL_SECTION_URL'] = '/catalog/'.$section['IBLOCK_ID'].'/'.$section['ID'].'/';
					if (!is_null($section)){
						$arSection[$section['ID']] = $section;
					}
				}
			}

			$w = strip_tags($arItem['NAME']);
			$aRw = explode(' ', $w);
			TrimArr($aRw);
			$word = $aRw[0].' '.$aRw[1];

			if (preg_match('#'.$arResult['alt_query'].'#iu', $word, $mm)){
				$words[md5($word)] = $word;
			}
		}
	}

	$arResult['WORDS'] = $words;
}

//unset($arResult['WORDS']);

if (!empty($arResult["ELEMENTS"]) && CModule::IncludeModule("iblock")){
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY']){
		if (!CModule::IncludeModule('currency')){
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		} else {
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))){
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			} else {
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$obParser = new CTextParser;

	if (is_array($arParams["PRICE_CODE"]))
		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
	else
		$arResult["PRICES"] = array();

	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"PREVIEW_TEXT",
		"PREVIEW_PICTURE",
		"DETAIL_PICTURE",
		'IBLOCK_SECTION_ID',
		'NAME',
	);
	$arFilter = array(
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
		'CATALOG_AVAILABLE' => 'Y',
	);
	foreach ($arResult["PRICES"] as $value) {
		$arSelect[] = $value["SELECT"];
		$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
	}


	$arFilter["=ID"] = $arResult["ELEMENTS"];
	$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	while ($arElement = $rsElements->Fetch()) {
		$arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
		if ($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$arElement["PREVIEW_TEXT"] = $obParser->html_cut($arElement["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

		if (count($arElement['PRICES']) == 0){
			$arSkuIblock = CCatalogSku::GetInfoByIBlock($arElement['IBLOCK_ID']);
			$sku = \CIBlockElement::GetList(
				array(),
				array(
					'IBLOCK_ID' => $arSkuIblock['IBLOCK_ID'],
					'ACTIVE' => 'Y',
					'=PROPERTY_CML2_LINK' => $arElement['ID'],
					"=PROPERTY_SHOP_ID" => $arResult['SHOP_CURRENT_ID'] ? $arResult['SHOP_CURRENT_ID'] : $_SESSION['REGIONS']['SHOP_ID'],
				),
				false,
				array('nTopCount' => 1),
				array('ID', 'CATALOG_GROUP_1', 'PROPERTY_SHOP_ID', 'IBLOCK_ID')
			);
			if ($arSku = $sku->Fetch()){
				$arElement['PRICES'] = array(
					'PRICE' => $arSku['CATALOG_PRICE_1'],
					'PRICE_FORMAT' => UL\Tools::formatPrice($arSku['CATALOG_PRICE_1']),
					'SKU_ID' => $arSku['ID'],
					'SHOP_ID' => $arSku['PROPERTY_SHOP_ID_VALUE'],
				);
			} else {
				continue;
			}
		}

		if (intval($arElement['IBLOCK_SECTION_ID']) > 0){
			$section = Bitrix\Iblock\SectionTable::getRow([
				'select' => ['ID', 'CODE', 'NAME', 'IBLOCK_ID'],
				'filter' => ['=ID' => $arElement['IBLOCK_SECTION_ID'], 'IBLOCK_ID' => $arElement['IBLOCK_ID']],
			]);
			$section['DETAIL_SECTION_URL'] = '/catalog/'.$section['IBLOCK_ID'].'/'.$section['ID'].'/';
			if (!is_null($section)){
				$arSection[$section['ID']] = $section;
			}
		}
		if ($arElement["PREVIEW_PICTURE"] > 0)
			$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array("width" => $PREVIEW_WIDTH, "height" => $PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);
		elseif ($arElement["DETAIL_PICTURE"] > 0)
			$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array("width" => $PREVIEW_WIDTH, "height" => $PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);

		$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
		$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
		$resultCnt++;
	}
}
$searchPhrase = $arResult["alt_query"] ? $arResult["alt_query"] : $arResult["query"];

$noSearch = true;

if ($resultCnt == 0 && strlen($searchPhrase) > 3 && !$noSearch){
	$arItemsText = [];
	$connect = \Bitrix\Main\Application::getConnection();
	$sql = "SELECT
  ft.ID AS FT_ID,
  ft.IBLOCK_ID as FT_IBLOCK_ID,
  ft.TEXT as TEXT,
  ft.ITEM_ID as FT_ITEM_ID,
  MATCH (ft.TEXT) AGAINST ('".$searchPhrase."') as REL,
  BST.*
FROM ul_search_title_index ft
  LEFT JOIN b_search_content BST ON BST.ITEM_ID = ft.ITEM_ID AND BST.PARAM2 = ft.IBLOCK_ID
WHERE MATCH (ft.TEXT) AGAINST ('".$searchPhrase."') > 0
ORDER BY REL DESC
";
	$obList = $connect->query($sql);
	while ($list = $obList->fetch()) {
		$arResult["ELEMENTS"][$list['ITEM_ID']] = $list['ITEM_ID'];
	}

	if (!empty($arResult["ELEMENTS"]) && CModule::IncludeModule("iblock")){
		$arConvertParams = array();
		if ('Y' == $arParams['CONVERT_CURRENCY']){
			if (!CModule::IncludeModule('currency')){
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			} else {
				$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
				if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))){
					$arParams['CONVERT_CURRENCY'] = 'N';
					$arParams['CURRENCY_ID'] = '';
				} else {
					$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
					$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				}
			}
		}

		$obParser = new CTextParser;

		if (is_array($arParams["PRICE_CODE"]))
			$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
		else
			$arResult["PRICES"] = array();

		$arSelect = array(
			"ID",
			"IBLOCK_ID",
			"PREVIEW_TEXT",
			"PREVIEW_PICTURE",
			"DETAIL_PICTURE",
			'IBLOCK_SECTION_ID',
			'NAME',
		);
		$arFilter = array(
			"IBLOCK_LID" => SITE_ID,
			"IBLOCK_ACTIVE" => "Y",
			"ACTIVE_DATE" => "Y",
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y",
			"MIN_PERMISSION" => "R",
			'CATALOG_AVAILABLE' => 'Y',
		);
		foreach ($arResult["PRICES"] as $value) {
			$arSelect[] = $value["SELECT"];
			$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
		}


		$arFilter["=ID"] = $arResult["ELEMENTS"];
		$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		while ($arElement = $rsElements->Fetch()) {
			$arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
			if ($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
				$arElement["PREVIEW_TEXT"] = $obParser->html_cut($arElement["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

			if (count($arElement['PRICES']) == 0){
				$arSkuIblock = CCatalogSku::GetInfoByIBlock($arElement['IBLOCK_ID']);
				$sku = \CIBlockElement::GetList(
					array(),
					array(
						'IBLOCK_ID' => $arSkuIblock['IBLOCK_ID'],
						'ACTIVE' => 'Y',
						'=PROPERTY_CML2_LINK' => $arElement['ID'],
						"=PROPERTY_SHOP_ID" => $arResult['SHOP_CURRENT_ID'] ? $arResult['SHOP_CURRENT_ID'] : $_SESSION['REGIONS']['SHOP_ID'],
					),
					false,
					array('nTopCount' => 1),
					array('ID', 'CATALOG_GROUP_1', 'PROPERTY_SHOP_ID', 'IBLOCK_ID')
				);
				if ($arSku = $sku->Fetch()){
					$arElement['PRICES'] = array(
						'PRICE' => $arSku['CATALOG_PRICE_1'],
						'PRICE_FORMAT' => UL\Tools::formatPrice($arSku['CATALOG_PRICE_1']),
						'SKU_ID' => $arSku['ID'],
						'SHOP_ID' => $arSku['PROPERTY_SHOP_ID_VALUE'],
					);
				} else {
					continue;
				}
			}

			if (intval($arElement['IBLOCK_SECTION_ID']) > 0){
				$section = Bitrix\Iblock\SectionTable::getRow([
					'select' => ['ID', 'CODE', 'NAME', 'IBLOCK_ID'],
					'filter' => ['=ID' => $arElement['IBLOCK_SECTION_ID'], 'IBLOCK_ID' => $arElement['IBLOCK_ID']],
				]);
				$section['DETAIL_SECTION_URL'] = '/catalog/'.$section['IBLOCK_ID'].'/'.$section['ID'].'/';
				if (!is_null($section)){
					$arSection[$section['ID']] = $section;
				}
			}

			if ($arElement["PREVIEW_PICTURE"] > 0)
				$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array("width" => $PREVIEW_WIDTH, "height" => $PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			elseif ($arElement["DETAIL_PICTURE"] > 0)
				$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array("width" => $PREVIEW_WIDTH, "height" => $PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);

			$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
			$resultCnt++;
		}
	}
}

$sectionItems = [];
foreach ($arResult["ELEMENTS"] as $ELEMENT) {
	if(array_key_exists($ELEMENT['IBLOCK_SECTION_ID'], $arSection)){
		$sectionItems[$ELEMENT['IBLOCK_SECTION_ID']] = $arSection[$ELEMENT['IBLOCK_SECTION_ID']];
	}
}

unset($arSection);
$arResult['SECTIONS'] = $sectionItems;


foreach ($arResult["SEARCH"] as $i => $arItem) {
	$arResult["SEARCH"][$i]["ICON"] = true;
}

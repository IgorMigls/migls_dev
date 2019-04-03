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
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

use Bitrix\Main\Loader;
use AB\Iblock\Element;
use Bitrix\Main\Entity;
use UL\Tools;

Loader::includeModule('ab.iblock');
Loader::includeModule('iblock');
Loader::includeModule('sale');
Loader::includeModule('catalog');

//PR($arResult);
$resItems = [];

$shopIds =$request->get('shop');
$arResult['SHOP_CURRENT_ID'] = $shopIds;
if(empty($shopIds)){
	$shopIds = $_SESSION['REGIONS']['SHOP_ID'];
}

$exFILTER = [
	array('=MODULE_ID' => 'iblock', 'PARAM1' => 'catalog')
];

$defaultMeasure = \Ul\Main\Measure\MeasureSettings::getDefaultMeasure();
$defaultRatio = \Ul\Main\Measure\MeasureSettings::getDefaultMeasureRatio();

foreach ($arResult['SEARCH'] as $k => $item) {
	if (substr($item['ITEM_ID'], 0, 1) != 'S' && intval($item['ITEM_ID']) > 0 && intval($item['PARAM2']) > 0){
		$arInfoIB = CCatalogSku::GetInfoByIBlock($item['PARAM2']);
		if ($arInfoIB['IBLOCK_ID'] > 0){

			$arProduct = Element::getRow([
				'select' => [
					'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
					'SHOP_NAME' => 'PROPERTY.SHOP_ID.NAME',
					'SHOP_PICTURE' => 'PROPERTY.SHOP_ID.DETAIL_PICTURE',
					'PRICE_VALUE' => 'PRICES.PRICE',
					'PRODUCT_IMG' => 'PROPERTY.CML2_LINK.DETAIL_PICTURE',
					'PRICE_ID' => 'ID',
                    'CML2_LINK' => 'PROPERTY.CML2_LINK.ID',
                    'MEASURE_NAME' => 'MEASURE.MEASURE_TITLE',
                    'MEASURE_SHORT_NAME' => 'MEASURE.SYMBOL_RUS',
                    'MEASURE_RATIO' => 'RATIO.RATIO',
				],
				'filter' => [
					'IBLOCK_ID' => $arInfoIB['IBLOCK_ID'],
					'=PROPERTY.CML2_LINK.ID' => $item['ITEM_ID'],
					'=PROPERTY.SHOP_ID.ID' => $shopIds,
				],
				'runtime' => [
                    new Entity\ReferenceField(
                        'CATALOG',
                        \Bitrix\Catalog\ProductTable::getEntity(),
                        ['=this.ID' => 'ref.ID']
                    ),
					new Entity\ReferenceField(
						'PRICES',
						\Bitrix\Catalog\PriceTable::getEntity(),
						['=this.ID' => 'ref.PRODUCT_ID']
					),
                    new Entity\ReferenceField(
                        'RATIO',
                        \Bitrix\Catalog\MeasureRatioTable::getEntity(),
                        ['=this.ID' => 'ref.PRODUCT_ID']
                    ),
                    new Entity\ReferenceField(
                        'MEASURE',
                        \Ul\Main\Measure\MeasureTable::getEntity(),
                        ['=this.CATALOG.MEASURE' => 'ref.ID']
                    ),
				]
			]);
			if (!is_null($arProduct)){
				$arProduct['PRICE_FORMAT'] = Tools::formatPrice($arProduct['PRICE_VALUE']);
				if(intval($arProduct['PRODUCT_IMG']) > 0){
					$arProduct['IMG'] = \CFile::ResizeImageGet(
						$arProduct['PRODUCT_IMG'],
						['width' => 200, 'height' => 200],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
						true
					);
				}
				$resItems[$arProduct['SHOP_ID']]['INFO'] = array(
					'ID'=>$arProduct['SHOP_ID'],
					'NAME' => $arProduct['SHOP_NAME'],
					'PICTURE' => $arProduct['SHOP_PICTURE']
				);
				$arProduct['PRODUCT_ID'] = $item['ITEM_ID'];
				$arProduct['PRODUCT_NAME'] = $item['TITLE'];

				$sku = [];

                $sku['MEASURE_NAME'] = $arProduct['MEASURE_NAME'] ? : $defaultMeasure['MEASURE_TITLE'];
                $sku['MEASURE_SHORT_NAME'] = $arProduct['MEASURE_SHORT_NAME'] ? : $defaultMeasure['SYMBOL_RUS'];
                $sku['MEASURE_RATIO'] = $arProduct['MEASURE_RATIO'] ? : $defaultRatio;

                $arProduct['SKU'] = $sku;

				$resItems[$arProduct['SHOP_ID']]['ITEMS'][] = $arProduct;
			}
		}
	}
}
$arResult['SHOP_ITEMS'] = $resItems;
//PR($arResult['SHOP_ITEMS']);

$arResult['SHOPS']['CITY_ID'] = $_SESSION['REGIONS']['CITY_ID'];
$arResult['SHOPS']['SHOP_ID'] = $_SESSION['REGIONS']['SHOP_ID'];
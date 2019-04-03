<?php namespace UL\Products;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use PW\Tools\Debug;
use Soft\Element;
use Bitrix\Main\Data;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;
use Ul\Main\Measure\ProductMeasure;
use UL\Main\Services\FavoriteTable;
use UL\Tools;

includeModules(['iblock', 'soft.iblock', 'catalog', 'sale']);

Loc::loadLanguageFile(__FILE__);

class DetailProduct extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	protected $CIBlockElement;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
		$this->CIBlockElement = new \CIBlockElement();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams['ID'] = intval($arParams['ID']);

		return $arParams;
	}

	public function getProduct($data = [])
	{
		$ID = intval($data['ID']) > 0 ? intval($data['ID']) : $this->arParams['ID'];
		$iblock = Element::getIblockByIdElement($ID);


		try {
            $arProduct = Element::getRow([
                'select' => [
                    'ID', 'NAME', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
                    'CODE', 'IBLOCK_SECTION_ID','DETAIL_TEXT','PREVIEW_TEXT',
                    'MEASURE_ID' => 'PROPERTY.MEASURE',
                    'RATIO' =>'PROPERTY.MULTIPLICITY_MEASURE',
	                'IBLOCK_NAME' => 'IBLOCK.NAME'
                ],
                'filter' => ['IBLOCK_ID' => $iblock, '=ID' => $ID],
            ]);
        } catch (\Bitrix\Main\SystemException $e) {
            if (preg_match("#.*Unknown field definition `MEASURE` \(PROPERTY.MEASURE\)#Umsi", $e->getMessage())) {
                $arProduct = Element::getRow([
                    'select' => [
                        'ID', 'NAME', 'IBLOCK_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
                        'CODE', 'IBLOCK_SECTION_ID','DETAIL_TEXT','PREVIEW_TEXT',
	                    'IBLOCK_NAME' => 'IBLOCK.NAME'
                    ],
                    'filter' => ['IBLOCK_ID' => $iblock, '=ID' => $ID],
                ]);
            } else {
                throw $e;
            }
        }

        $defaultMeasure = MeasureSettings::getDefaultMeasure();
        $defaultRatio = MeasureSettings::getDefaultMeasureRatio();

		global $USER;
		$this->arResult['IS_AUTH'] = $USER->IsAuthorized();

		if ($arProduct){

			$skuTmp = \CCatalogSku::getOffersList($arProduct['ID'], $arProduct['IBLOCK_ID']);
			$sku = array_shift($skuTmp[$arProduct['ID']]);
			$price = \CPrice::GetBasePrice($sku['ID']);


            if ($sku) {
                $measure = ProductMeasure::getMeasureByProductId($sku['IBLOCK_ID'], $sku['ID']);
                $sku['MEASURE_NAME'] = $measure->getName();
                $sku['MEASURE_SHORT_NAME'] = $measure->getShortName();
                $sku['MEASURE_RATIO'] = $measure->getRatio();
            } else {

                /** @todo сделать метод в классе ProductMeasure */

                $sku = array(
                    'MEASURE_SHORT_NAME' => $defaultMeasure['SYMBOL_RUS'],
                    'MEASURE_RATIO' => $defaultRatio
                );

                if ($arProduct['MEASURE_ID']) {
                    $sku['MEASURE_SHORT_NAME'] = MeasureSettings::getMeasureById($arProduct['MEASURE_ID'])['SYMBOL_RUS'];
                }

                if ($arProduct['RATIO']) {
                    $sku['MEASURE_RATIO'] = $arProduct['RATIO'];
                }
            }


            //var_dump($sku);
            //exit;


			$arProduct['SKU'] = $sku;



			if (intval($arProduct['PREVIEW_PICTURE']) > 0){
				$arProduct['SMALL_IMG'] = \CFile::ResizeImageGet(
					$arProduct['PREVIEW_PICTURE'],
					['width' => 200, 'height' => 200],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
				$arProduct['SMALL_IMG']['ORIGINAL'] = \CFile::GetPath($arProduct['PREVIEW_PICTURE']);
			}
			if (intval($arProduct['DETAIL_PICTURE']) > 0){
				$arProduct['DETAIL_IMG'] = \CFile::ResizeImageGet(
					$arProduct['DETAIL_PICTURE'],
					['width' => 350, 'height' => 350],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
				$arProduct['DETAIL_IMG']['ORIGINAL'] = \CFile::GetPath($arProduct['DETAIL_PICTURE']);
			}

			$arProduct['CHAIN'][] = [
				'NAME' => 'Главная',
				'URL' => '/',
			];


			$shop = \CIBlockElement::GetProperty($arProduct['SKU']['IBLOCK_ID'], $arProduct['SKU']['ID'], [], ['CODE' => 'SHOP_ID'])->Fetch();

			$arUrl = explode('/', $this->request->get('back'));
			if($shop && in_array($shop['VALUE'], $arUrl)){
				\CBitrixComponent::includeComponentClass('ul:shop.detail');
				$Shop = new \UL\Shops\ShopDetail();
				$this->arResult['SHOP_INFO'] = $Shop->getShopInfo($shop['VALUE']);

				$arProduct['CHAIN'][] = [
					'NAME' => $this->arResult['SHOP_INFO']['NAME'],
					'URL' => '/shop/'.$this->arResult['SHOP_INFO']['ID'].'/',
				];

				$arProduct['CHAIN'][] = [
					'NAME' => $arProduct['IBLOCK_NAME'],
					'URL' => '/shop/'.$this->arResult['SHOP_INFO']['ID'].'/'.$arProduct['IBLOCK_ID'].'/'
				];
			}



			$obSection = \CIBlockSection::GetNavChain($iblock, $arProduct['IBLOCK_SECTION_ID'], ['NAME', 'ID']);
			while ($section = $obSection->Fetch()) {
				if($shop && in_array($shop['VALUE'], $arUrl)){
					$section['URL'] = '/shop/'.$this->arResult['SHOP_INFO']['ID'].'/'.$iblock.'/'.$section['ID'].'/#/';
				} else {
					$section['URL'] = '/catalog/'.$iblock.'/'.$section['ID'].'/';
				}


				$arProduct['CHAIN'][] = $section;
			}

			$arProduct['PRICE_FORMAT'] = \SaleFormatCurrency($price['PRICE'], $price['CURRENCY'], true);
			$props = [
				'COMPOSITION', 'ASSET', 'KEEP',
			];
			foreach ($props as $code) {
				$arProduct['PROPERTIES'][$code] = \CIBlockElement::GetProperty($iblock, $arProduct['ID'], [], ['CODE' => $code])->Fetch();
			}

			if($this->arResult['IS_AUTH']){
				$arProduct['IN_FAVORITE'] = FavoriteTable::getRow([
					'filter' => ['=ELEMENT_ID' => $arProduct['ID'], '=USER_ID' => $USER->GetID()]
				]);
			}

		}




		return $arProduct;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$ID = $this->arParams['ID'];
		$arProduct = $this->getProduct();
		$this->arResult = $arProduct;
		$this->arResult['SKU_IBLOCK'] = $arProduct['SKU']['IBLOCK_ID'];
		$this->arResult['REMAIN_ID'] = $arProduct['SKU']['ID'];
		$this->arResult['SHOP'] = \CIBlockElement::GetProperty($arProduct['SKU']['IBLOCK_ID'], $arProduct['SKU']['ID'], [], ['CODE' => 'SHOP_ID'])->Fetch();

//		$dataCache = Data\Cache::createInstance();
//		$tagCache = new Data\TaggedCache();
//		if($dataCache->initCache($this->arParams['CACHE_TIME'], Tools::CACHE_KEY_PRODUCT.$ID, '/ul/products')){
//			$arProduct = $dataCache->getVars();
//		} else {
//
//		}


		$this->includeComponentTemplate();
	}
}
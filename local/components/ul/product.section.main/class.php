<?php namespace UL\Main\Catalog;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Helpers\DataCache;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use UL\Tools;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');

Loc::loadLanguageFile(__FILE__);

class MainProductList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;

	protected $IblockElement;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;

		$this->IblockElement = new \CIBlockElement();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if (intval($arParams['LIMIT']) == 0){
			$arParams['LIMIT'] = 8;
		}

		if(strlen($arParams['CACHE_TIME']) == 0){
			$arParams['CACHE_TIME'] = 86400;
		}

		return $arParams;
	}

	/**
	 * @method getSku
	 * @param array $arProduct
	 *
	 * @return mixed|null
	 */
	public function getSku($arProduct = [])
	{
		if (count($arProduct) == 0){
			return null;
		}

		$sku = \CCatalogSku::getOffersList(
			$arProduct['ID'],
			$this->arParams['CATALOG_INFO']['PRODUCT_IBLOCK_ID']
		);

		$result = array_shift($sku[$arProduct['ID']]);
		$result['PRICE'] = \CPrice::GetBasePrice($result['ID']);

		return $result;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$iblockProduct = intval($this->arParams['CATALOG_INFO']['PRODUCT_IBLOCK_ID']);
		$skuIblock = intval($this->arParams['CATALOG_INFO']['IBLOCK_ID']);

		if ($iblockProduct == 0 || $skuIblock == 0){
			ShowError('Нет данных');
			return;
		}

		$cacheId = Tools::CACHE_KEY_PRODUCT.$iblockProduct.$this->arParams['SECTION_ID'].md5(serialize($_SESSION['REGIONS']['SHOP_ID']));
		$dataCache = new DataCache($this->arParams['CACHE_TIME'], '/ul/all_catalog_section', $cacheId);
		$clearCache = false;
		if($dataCache->isValid() && !$clearCache){
			$result = $dataCache->getData();
		} else {
			$result['SECTION'] = SectionTable::getRow([
				'filter' => ['=ID' => $this->arParams['SECTION_ID']],
				'select' => ['NAME']
			]);

			$oProduct = $this->IblockElement->GetList(
				array('SORT' => 'ASC', 'ID' => 'DESC'),
				array(
					'IBLOCK_ID' => $iblockProduct,
					'ACTIVE' => 'Y',
					'CATALOG_AVAILABLE' => 'Y',
					'SECTION_ID' => $this->arParams['SECTION_ID'],
					'INCLUDE_SUBSECTIONS' => 'Y',
				),
				false,
				array('nTopCount' => $this->arParams['LIMIT']),
				array(
					'ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'DETAIL_PICTURE',
				)
			);
			while ($product = $oProduct->Fetch()) {
				$sku = $this->getSku($product);
				$result['ITEMS'][$sku['ID']] = [
					'ID' => $sku['ID'],
					'PRODUCT_ID' => $product['ID'],
					'IMG' => \CFile::ResizeImageGet(
						$product['DETAIL_PICTURE'],
						['width' => 200, 'height' => 200],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					),
					'PRODUCT_NAME' => $product['NAME'],
					'PRICE_FORMAT' => \FormatCurrency($sku['PRICE']['PRICE'], $sku['PRICE']['CURRENCY']),
				];
			}

			$dataCache->addCache($result);
		}

		$this->arResult = $result;

		$this->includeComponentTemplate();
	}
}
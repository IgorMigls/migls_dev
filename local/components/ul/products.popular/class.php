<?php namespace UL\Catalog;
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
use AB\Iblock\Element;
use UL\DataCache;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;
use UL\Main\Services\FavoriteTable;

Loc::loadLanguageFile(__FILE__);

Loader::includeModule('iblock');
Loader::includeModule('ab.iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');
\CBitrixComponent::includeComponentClass('ul:shop.list');

class PopularList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	protected $shopIds;
	protected $skuCatalogInfo;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams['CACHE_TIME'] = 86400;

		if(intval($arParams['IBLOCK_SKU_ID']) == 0)
			$arParams['IBLOCK_SKU_ID'] = 67;

		return $arParams;
	}
	/**
	 * @method getShopList
	 * @return null
	 */
	public function getShopList()
	{
		$ShopList = new \UL\Shops\ShopList();

		return $ShopList->getShops();
	}

	/**
	 * @method getProducts
	 * @return \Bitrix\Main\DB\Result
	 */
	public function getProducts($shopIds)
	{
		$filter = [
			'IBLOCK_ID' => $this->arParams['IBLOCK_SKU_ID'],
			'ACTIVE' => 'Y',
			'=PROPERTY.SHOP_ID.ID' => $shopIds,
			'!=PROPERTY.CML2_LINK.ID' => false,
			'!=PRODUCT.ID' => false
		];
		if(count($this->arParams['FILTER']) > 0){
			foreach ($this->arParams['FILTER'] as $code => $value) {
				$filter[$code] = $value;
			}
		}


		global $USER;
		$arSkuCatalog = \CCatalogSku::GetInfoByIBlock($filter['IBLOCK_ID']);
		$this->skuCatalogInfo = $arSkuCatalog;
		$oProducts = Element::getList([
			'select' => [
				'ID', 'CODE',
				'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
				'SHOP_NAME' => 'SHOP.NAME',
				'PRODUCT_NAME' => 'PRODUCT.NAME',
				'SHOP_PICTURE' => 'SHOP.DETAIL_PICTURE',
				'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
				'PRICE_VAL' => 'PRICE.PRICE',
				'PRODUCT_ID' => 'PRODUCT.ID',
				'PROPERTY.CML2_LINK.ID',
				'FAVORITE_ID' => "FAVORITE.ID",
                'MEASURE_NAME' => 'MEASURE.MEASURE_TITLE',
                'MEASURE_SHORT_NAME' => 'MEASURE.SYMBOL_RUS',
                'MEASURE_RATIO' => 'RATIO.RATIO',
//				'BASKET_ID' => 'BASKET.ID',
//				'BASKET_QUANTITY' => 'BASKET.QUANTITY'
			],
			'filter' => $filter,
			'runtime' => [
                new Entity\ReferenceField(
                    'CATALOG',
                    \Bitrix\Catalog\ProductTable::getEntity(),
                    ['=this.ID' => 'ref.ID']
                ),
				new Entity\ReferenceField(
					'SHOP',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.SHOP_ID.ID' => 'ref.ID']
				),
				new Entity\ReferenceField(
					'PRODUCT',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.CML2_LINK.ID' => 'ref.ID', 'ref.IBLOCK_ID'=>array('?i', $arSkuCatalog['PRODUCT_IBLOCK_ID'])]
				),
				new Entity\ReferenceField(
					'PRICE',
					\Bitrix\Catalog\PriceTable::getEntity(),
					['=this.ID'=>'ref.PRODUCT_ID']
				),
				new Entity\ReferenceField(
					'FAVORITE',
					FavoriteTable::getEntity(),
					[
						'=this.PROPERTY.CML2_LINK.ID' => 'ref.ELEMENT_ID',
						'ref.USER_ID'=>array('?i', (int)$USER->GetID())
					]
				),
                new Entity\ReferenceField(
                    'RATIO',
                    \Bitrix\Catalog\MeasureRatioTable::getEntity(),
                    ['=this.ID' => 'ref.PRODUCT_ID']
                ),
                new Entity\ReferenceField(
                    'MEASURE',
                    MeasureTable::getEntity(),
                    ['=this.CATALOG.MEASURE' => 'ref.ID']
                ),
			],
			'limit' => 20,
			'order'=>['PROPERTY.SHOP_ID.ID'=>'DESC'],
			'count_total'=>true,
		]);

		return $oProducts;
	}

	/**
	 * @method getDataFile
	 * @param $file
	 * @param $w
	 * @param $h
	 *
	 * @return mixed
	 */
	public function getDataFile($file, $w, $h)
	{
		$arFile = false;

		if(intval($file) > 0){
			$arFile = \CFile::ResizeImageGet(
				$file,
				['width'=>$w, 'height'=>$h],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
				true
			);
		}

		return $arFile;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$arShop = $this->getShopList();
		$shopIds = [];
		foreach ($arShop as $item) {
			if($item['PROPERTY_NO_AVAILABLE_VALUE'] != 1)
				$this->shopIds[] = $item['ID'];
		}

		$this->arParams['SHOPS_IDS'] = $this->shopIds;


		$siteId = \Bitrix\Main\Context::getCurrent()->getSite();
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteId);
		$quantityList = [];

		/** @var \Bitrix\Sale\BasketItem $item */
		foreach ($basket->getBasketItems() as $item){
			$quantityList[$item->getProductId()] = $item;
		}

//		$cache = new DataCache($this->arParams['CACHE_TIME'], '/ul/products/popular', md5(serialize($this->shopIds)));
//		if($cache->getIsValid()){
//			$result = $cache->getData();
//		} else {

			if(intval($this->arParams['SHOP_CURRENT']) > 0){
				$this->shopIds = array($this->arParams['SHOP_CURRENT']);
			}

			foreach ($this->shopIds as $shopId) {
				$oProducts = $this->getProducts($shopId);
				while ($product = $oProducts->fetch()) {
					$product['SHOP_PICTURE'] = $this->getDataFile($product['SHOP_PICTURE'], 120, 80);
					$product['PRODUCT_PICTURE'] = $this->getDataFile($product['PRODUCT_PICTURE'], 180, 200);

					if (intval($product['PRICE_VAL']) > 0){
						$product['PRICE_FORMAT'] = \SaleFormatCurrency($product['PRICE_VAL'], 'RUB', true);
					}

					if(isset($quantityList[$product['ID']])){
						$basketItem = $quantityList[$product['ID']];
						if($basketItem instanceof \Bitrix\Sale\BasketItem){
							$product['BASKET_ID'] = $basketItem->getId();
							$product['BASKET_QUANTITY'] = $basketItem->getQuantity();
						}
					}

					$result['SHOP'][$shopId]['NAME'] = $product['SHOP_NAME'];
					$result['SHOP'][$shopId]['ID'] = $product['SHOP_ID'];
					$result['SHOP'][$shopId]['PICTURE'] = $product['SHOP_PICTURE'];
					$result['SHOP'][$shopId]['ITEMS'][] = $product;
				}
			}

//			$cache->writeVars($result);
//		}

		$this->arResult = $result;
		$this->arResult['CATALOG_INFO'] = $this->skuCatalogInfo;

		$this->includeComponentTemplate();
	}
}
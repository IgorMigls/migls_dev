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

use AB\Tools\Helpers\DataCache;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use PW\Tools\Debug;
use \Soft\Element;
use Soft\Property;
use UL\Tools;
use Bitrix\Main\UI;

includeModules(['ul.main', 'iblock', 'soft.iblock', 'catalog', 'sale']);

Loc::loadLanguageFile(__FILE__);

\CBitrixComponent::includeComponentClass('ul:shop.list');

class ProductList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	protected $iblockProduct = 4;
	protected $filter;
	protected $Nav;
	protected $CIBlockElement;
	protected $CIBlockSection;
	protected $arCatalog;

	private $cacheId;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;

		$this->USER = $USER;
		$this->CIBlockElement = new \CIBlockElement();
		$this->CIBlockSection = new \CIBlockSection();

		$this->cacheId = Tools::CACHE_KEY_PRODUCT.md5(serialize($_SESSION['REGIONS']['SHOP_ID']));

		$this->cacheId .= $this->request->get('CATALOG').$this->request->get('CAT');
		if(strlen($this->request->get('catalog')) > 0){
			$this->cacheId .= $this->request->get('catalog');
		}

		$this->cacheId = md5($this->cacheId);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(strlen($arParams['CACHE_TIME']) == 0){
			$arParams['CACHE_TIME'] = 86400;
		}

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
	public function getProducts()
	{
		$arShop = $this->getShopList();
		$shopIds = [];
		foreach ($arShop as $item) {
			$shopIds[] = $item['ID'];
		}

		$catIB = $this->request->get('CATALOG');
		if(intval($this->arParams['PRODUCT_IBLOCK']) > 0 && intval($catIB) == 0){
			$catIB = $this->arParams['PRODUCT_IBLOCK'];
		}

		$this->arCatalog = \CCatalogSku::GetInfoByIBlock($catIB);
//		PR($this->arCatalog);

		if(intval($this->request->get('SHOP_ID'))){
			$shopIds = $this->request->get('SHOP_ID');
		}

		$this->filter = [
			'IBLOCK_ID' => $this->arCatalog['IBLOCK_ID'],
			'ACTIVE' => 'Y',
			'=PROPERTY.SHOP_ID' => $shopIds,
//			'!PRODUCT.CODE' => false,
			'!=PROPERTY.CML2_LINK' => false
		];
		if (count($this->arParams['FILTER']) > 0){
			foreach ($this->arParams['FILTER'] as $code => $value) {
				$this->filter[$code] = $value;
			}
		}

		if(intval($this->arParams['SECTION_ID']) > 0){
			$rec['CAT'] = $this->arParams['SECTION_ID'];
		}
		if(intval($this->arParams['PRODUCT_IBLOCK']) > 0){
			$rec['CATALOG'] = $this->arParams['PRODUCT_IBLOCK'];
		}
		if(count($rec) > 0){
			$this->request->set($rec);
		}

		if (strlen($this->request->get('CATALOG')) > 0){
			if (intval($this->request->get('CATALOG')) > 0) {
				$this->iblockProduct = $this->request->get('CATALOG');
				$rowIblock = \Bitrix\Iblock\IblockTable::getRow([
					'select' => ['ID', 'CODE','NAME'],
					'filter' => ['=ID' => $this->iblockProduct],
				]);
			} elseif (is_string($this->request->get('CATALOG'))){
				$rowIblock = \Bitrix\Iblock\IblockTable::getRow([
					'select' => ['ID', 'CODE','NAME'],
					'filter' => ['=CODE' => $this->request->get('CATALOG')],
				]);
				$this->iblockProduct = $rowIblock['ID'];
			}

			$this->cacheId .= $this->iblockProduct;
		}

		if ($this->request->get('CATALOG') && strlen($this->request->get('CAT'))){

			$arSection = null;
			$cat = $this->request->get('CAT');
			if (!empty($cat)){

				if(intval($this->request->get('CAT')) > 0){
					$arSection = \Bitrix\Iblock\SectionTable::getRow([
						'select' => ['ID', 'NAME', 'CODE','PICTURE','LEFT_MARGIN','RIGHT_MARGIN'],
						'filter' => ['=ID' => $this->request->get('CAT'), 'IBLOCK_ID' => $this->iblockProduct],
					]);
				} else {
					$arSection = \Bitrix\Iblock\SectionTable::getRow([
						'select' => ['ID', 'NAME', 'CODE','PICTURE','LEFT_MARGIN','RIGHT_MARGIN'],
						'filter' => ['=CODE' => $this->request->get('CAT'), 'IBLOCK_ID' => $this->iblockProduct],
					]);
				}

				if (!is_null($arSection)){
					$obChain = $this->CIBlockSection->GetNavChain(
						$this->iblockProduct,
						$arSection['ID'],
						['NAME', 'CODE', 'ID', 'IBLOCK_ID']
					);
					$arSection['CHAIN'][0] = ['NAME' => 'Главная', 'URL' => '/'];
					$arSection['CHAIN'][1] = ['NAME' => $rowIblock['NAME'], 'URL' => '/catalog/'.$rowIblock['ID'].'/'];
					while ($chain = $obChain->Fetch()) {
						$chain['URL'] = 'catalog/'.$chain['IBLOCK_ID'].'/'.$chain['ID'].'/';
						$arSection['CHAIN'][] = $chain;
					}
				}

				if(intval($arSection['PICTURE']) > 0){
					$arSection['IMG'] = $this->getDataFile($arSection['PICTURE'], 500, 70);
				}

				$this->arResult['SECTION'] = $arSection;

				$endLevel = $arSection['RIGHT_MARGIN'] - $arSection['LEFT_MARGIN'];
				if($endLevel == 1){
					$this->filter['=PRODUCT.IBLOCK_SECTION_ID'] = $arSection['ID'];
				} else {
					$this->filter['>=PRODUCT.IBLOCK_SECTION.LEFT_MARGIN'] = $arSection['LEFT_MARGIN'];
					$this->filter['<=PRODUCT.IBLOCK_SECTION.RIGHT_MARGIN'] = $arSection['RIGHT_MARGIN'];
				}

				$this->cacheId .= $arSection['ID'];
			}
		}

		$limit = 100;
		if(intval($this->arParams['LIMIT']) > 0){
			$limit = $this->arParams['LIMIT'];
		}
		$offset = 0;
		$order = ['ID' => 'DESC','PRODUCT_PICTURE'=>'DESC'];

		if ((int)$this->arParams['COUNT_PAGE'] > 0){
			$this->Nav = new UI\PageNavigation('catalog');
			$this->Nav->allowAllRecords(true)
				->allowAllRecords(false)
				->setPageSize($this->arParams['COUNT_PAGE'])
				->initFromUri();

			$offset = $this->Nav->getOffset();
			$limit = $this->Nav->getLimit();
		}
		if (strlen($this->arParams['SORT']) > 0 && strlen($this->arParams['ORDER']) > 0){
			if($this->arParams['SORT'] == 'DEFAULT'){
				$this->arParams['SORT'] = 'ID';
				$this->arParams['ORDER'] = 'DESC';
			}

			$order = [$this->arParams['SORT'] => strtoupper($this->arParams['ORDER'])];
		}
		$arPropsForEntity = [];
		if(count($this->arParams['FILTER_VALUES']) > 0){
			$propsIds = [];
			foreach ($this->arParams['FILTER_VALUES'] as $code => $param) {
				preg_match('#PROPERTY_(\d+)#i', $code, $matchParam);
				if($matchParam[1]){
					unset($this->arParams['FILTER_VALUES'][$code]);
					$this->arParams['FILTER_VALUES'][$matchParam[1]] = $param;
					$propsIds[] = $matchParam[1];
				}
			}

			$this->getPropById($propsIds);

			foreach ($this->arResult['PROPERTY_FILTER'] as $idProp =>$item) {
				$arPropsForEntity[] = 'PROPERTY.'.$item['CODE'];
				switch ($item['TYPE']){
					case 'L':
						$this->filter['=PRODUCT.PROPERTY.'.$item['CODE']] = $this->arParams['FILTER_VALUES'][$idProp];
						break;
					default:
						$this->filter['=PRODUCT.PROPERTY.'.$item['CODE']] = $this->arParams['FILTER_VALUES'][$idProp];
						break;
				}
			}
		}


		$Element = new Element([
			'filter' => ['IBLOCK_ID'=>$this->arCatalog['IBLOCK_ID']],
			'select' => ['PROPERTY.CML2_LINK','ID','IBLOCK_ID']
		]);
		$productEntity = $Element->getBaseController()->init();

//		Debug::startSql();

//		PR($this->filter);

		$oProducts = Element::getList([
			'select' => [
				'ID', 'CODE',
				'SHOP_ID' => 'PROPERTY.SHOP_ID',
				'SHOP_NAME' => 'SHOP.NAME',
				'PRODUCT_NAME' => 'PRODUCT.NAME',
				'SHOP_PICTURE' => 'SHOP.DETAIL_PICTURE',
				'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
				'PRICE_VAL' => 'PRICE.PRICE',
				'PRODUCT_ID' => 'PRODUCT.ID',
				'BASKET_QUANTITY'=>'BASKET.QUANTITY',
				'BASKET_PRICE'=>'BASKET.PRICE',
			],
			'filter' => $this->filter,
			'runtime' => [
				new Entity\ReferenceField(
					'SHOP',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.SHOP_ID' => 'ref.ID']
				),
				new Entity\ReferenceField(
					'PRODUCT',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.CML2_LINK' => 'ref.ID', 'ref.IBLOCK_ID' => array('?i', $this->arCatalog['PRODUCT_IBLOCK_ID'])]
				),
				new Entity\ReferenceField(
					'PRICE',
					\Bitrix\Catalog\PriceTable::getEntity(),
					['=this.ID' => 'ref.PRODUCT_ID']
				),
				new Entity\ReferenceField(
					'BASKET',
					\Bitrix\Sale\Internals\BasketTable::getEntity(),
					['=this.ID'=>'ref.PRODUCT_ID', 'ref.FUSER_ID'=>array('?i', \CSaleBasket::GetBasketUserID())]
				)
			],
			'limit' => $limit,
			'order' => $order,
			'offset' => $offset,
			'count_total' => true,
		]);
//		Debug::getSql($oProducts);

		if ((int)$this->arParams['COUNT_PAGE'] > 0){
			$this->Nav->setRecordCount($oProducts->getCount());
			$this->cacheId .= $this->Nav->getCurrentPage();
			$this->arResult['NAV'] = $this->Nav;
		}


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

		if (intval($file) > 0){
			$arFile = \CFile::ResizeImageGet(
				$file,
				['width' => $w, 'height' => $h],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
				true
			);
		}

		return $arFile;
	}

	protected function getPropById($ids)
	{
		$oProp = \Bitrix\Iblock\PropertyTable::getList([
			'select'=>['CODE', 'ID', 'PROPERTY_TYPE'],
			'filter' => ['IBLOCK_ID'=>$this->iblockProduct, '=ID'=>$ids]
		]);
		while ($prop = $oProp->fetch()){
			$this->arResult['PROPERTY_FILTER'][$prop['ID']] = ['CODE'=>$prop['CODE'], 'TYPE'=>$prop['PROPERTY_TYPE']];
		}
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		global $USER;

		try {
			$dataCache = new DataCache($this->arParams['CACHE_TIME'], '/ul/product_list', $this->cacheId);
			$clearCache = false;
			if($dataCache->isValid() && !$clearCache){
				$result = $dataCache->getData();
			} else {
				$result['IBLOCK'] = \Bitrix\Iblock\IblockTable::getRow([
					'select'=>['ID','NAME','CODE','IBLOCK_TYPE_ID','PICTURE','DESCRIPTION','DESCRIPTION_TYPE'],
					'filter'=>['=ID'=>$this->iblockProduct]
				]);

				$oProducts = $this->getProducts();
				while ($product = $oProducts->fetch()) {

					if(intval($product['PRODUCT_PICTURE']) > 0){
						$product['IMG'] = $this->getDataFile($product['PRODUCT_PICTURE'], 200, 200);
					}
					if(floatval($product['BASKET_QUANTITY']) > 0){
						$product['BASKET_QUANTITY'] = floatval($product['BASKET_QUANTITY']);
						$product['IN_BASKET'] = true;
					}
					if(intval($product['PRICE_VAL']) > 0){
						$product['PRICE_FORMAT'] = Tools::formatPrice($product['PRICE_VAL']);
					}

					if(!empty($product['SALE'])){
						$arDiscounts = \CCatalogDiscount::GetDiscountByProduct(
							$product['ID'],
							$USER->GetUserGroupArray()
						);
						$product['DISCOUNT'] = $arDiscounts[0];
						$product['DISCOUNT_VAL'] = intval($arDiscounts[0]['VALUE']);
					}
					$result['ITEMS'][$product['PRODUCT_ID']] = $product;
				}
				$result = array_merge($this->arResult, $result);

				$dataCache->addCache($result);
			}
			$this->arResult = $result;

		} catch (\Exception $err){
			$dataCache->clear();
			PR($err->getMessage());
		}

		$this->includeComponentTemplate();
	}
}
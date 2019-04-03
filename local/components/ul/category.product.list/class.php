<?php namespace UL\Main;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Iblock\Element;
use AB\Tools\Debug;
use function array_merge;
use function array_values;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Sale\Fuser;
use function count;
use function dump;
use function htmlspecialcharsbx;
use function is_array;
use function preg_match;
use UL\Main\Basket\Model\BasketShopTable;
use Ul\Main\Measure\MeasureSettings;
use UL\Main\Services\Favorite;
use UL\Main\Services\FavoriteTable;

Loc::loadLanguageFile(__FILE__);

\CBitrixComponent::includeComponentClass('ul:products.shop.list');

Loader::includeModule('ab.iblock');
Loader::includeModule('online1c.iblock');
Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');

class CategoryProductsComponent extends ProductsShopsComponent
{
	/** @var  Dictionary */
	protected $sectionIterator;

	/** @var  \Bitrix\Main\UI\PageNavigation */
	protected $nav;

	function __construct($component)
	{
		parent::__construct($component);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams = parent::onPrepareComponentParams($arParams);
		$arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];

		if($arParams['IBLOCK_ID'] == 0){
			$this->errorCollection->setError(new Error('Не установлен каталог', 500));
		}

		return $arParams;
	}

	protected function getProductsIds($filter = [], $limit = 16)
	{
		$shopId = $this->arParams['SHOP_INFO']['ID'];
		$obSku = Element::getList([
			'select' => ['CML2_LINK' => 'PROPERTY.CML2_LINK.ID'],
			'filter' => $filter,
			'limit' => $limit,
			'group' => ['PROPERTY.CML2_LINK.ID'],
		]);
		$products = [];
		while ($sku = $obSku->fetch()) {
			$products[] = $sku['CML2_LINK'];
		}

		return $products;
	}

	protected function getProductsSku($sectionCurrent, $limit = 16)
	{

		$products = [];
		$nav = new \Bitrix\Main\UI\PageNavigation("PAGEN_1");
		$nav->allowAllRecords(false)
			->setPageSize($limit)
			->initFromUri();

//		$order = [];

//		dump($sectionCurrent);
		$arFilterSmart = false;
		$filter = [
			'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['IBLOCK_ID'],
			'=ACTIVE' => 'Y',
			'!PROPERTY.CML2_LINK_BIND.ID' => false,
			'=PROPERTY.SHOP_ID_BIND.ID' => $this->arParams['SHOP_INFO']['ID'],
			'PROPERTY.CML2_LINK_BIND.IBLOCK_SECTION_ID' => $sectionCurrent
		];

		if($this->request->get('set_filter') == 'y'){
			\CBitrixComponent::includeComponentClass('ul:catalog.smart.filter');
			$SmartFilter = new \CBitrixCatalogSmartFilter();
			$SmartFilter->onPrepareComponentParams([
				'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID'],
				'FILTER_NAME' => 'filterCatalog',
				'INCLUDE_SUBSECTIONS' => 'Y',
				'SECTION_ID' => $this->arResult['SECTION_INFO']['SECTION_ID'],
			]);
			$arFilterSmart = $SmartFilter->makeFilter('filterCatalog');
			$arFilterSmart['IBLOCK_ID'] = $this->arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID'];
			$arFilterSmart['SECTION_ID'] = $this->arResult['SECTION_INFO']['SECTION_ID'];

			if((int)$arFilterSmart['SECTION_ID'] == 0){
				$arFilterSmart['SECTION_ID'] = $sectionCurrent[0];
			}

		}

		$orderSort = strtoupper($this->request->get('order')) == 'DESC' ? 'DESC' : 'ASC';
		switch ($this->request['sortBy']){
			case 'price':

				$order = [
					'catalog_PRICE_1' => $orderSort
				];
				/*try {

					$iSortSku = Element::getList([
						'select' => ['ID', 'PRODUCT_ID' => 'PROPERTY.CML2_LINK.ID'],
						'filter' => [
							'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['IBLOCK_ID'],
							'=ACTIVE' => 'Y',
							'=PROPERTY.SHOP_ID.ID' => $this->arParams['SHOP_INFO']['ID'],
							'PROPERTY.CML2_LINK.IBLOCK_SECTION_ID' => $sectionCurrent
						],
						'order' => ['PRICE.PRICE' => 'ASC'],
						'runtime' => [
							new Entity\ReferenceField(
								'PRICE',
								\Bitrix\Catalog\PriceTable::getEntity(),
								['=this.ID' => 'ref.PRODUCT_ID']
							),
						]
					]);
					$skuIdElements = [];

					while ($rSku = $iSortSku->fetch()){
						$skuIdElements[] = $rSku;
					}
//					PR($skuIdElements);
				} catch (\Exception $err){
					PR($err->getMessage());
				}*/


				break;
			case 'date':
				$order = ['TIMESTAMP_X' => $orderSort];
				break;
			default:
				$order = ['PROPERTY_SORT' => 'ASC'];
				break;
		}


//		unset($filter['PROPERTY.CML2_LINK.IBLOCK_SECTION_ID']);

		$productIblock = $this->arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID'];
		$skuIblock = $this->arResult['CATALOG_INFO']['IBLOCK_ID'];
		$products = null;
		$filter = [
			'ACTIVE' => 'Y',
			'IBLOCK_ID' => $productIblock,
		];

		if((int)$this->arParams['SHOP_INFO']['ID'] > 0){
			$filter['ID'] = \CIBlockElement::SubQuery(
				"PROPERTY_CML2_LINK",
				array("IBLOCK_ID" =>$skuIblock , "PROPERTY_SHOP_ID" => $this->arParams['SHOP_INFO']['ID'])
			);
		}
		if($this->arResult['SECTION_INFO']){
			$filter['SECTION_ID'] = $this->arResult['SECTION_INFO']['ID'];
			$filter['INCLUDE_SUBSECTION'] = 'Y';
		} elseif($sectionCurrent){
			$filter['SECTION_ID'] = $sectionCurrent;
			$filter['INCLUDE_SUBSECTION'] = 'Y';
		}
		$nav->setCurrentPage('page-'.$this->request['PAGEN_1']);

		preg_match('/page-(\d)$/i', $this->request->get('PAGEN_1'), $matchPage);

		if(empty($matchPage)){
			$matchPage[1] = 1;
		}

		if(is_array($arFilterSmart)){
			$filter = array_merge($filter, $arFilterSmart);
		}

		if(empty($this->arParams['PAGE_LIMIT'])){
			$this->arParams['PAGE_LIMIT'] = $limit;
			$navParams = array('nTopCount' => $limit);
		} else {
			$navParams = array('nPageSize' => $this->arParams['PAGE_LIMIT'], 'iNumPage' => $matchPage[1]);
		}

        $defaultMeasure = MeasureSettings::getDefaultMeasure();
        $defaultRatio = MeasureSettings::getDefaultMeasureRatio();

		$obList = \CIBlockElement::GetList(
			$order,
			$filter,
			false,
			$navParams,
			array(
				'ID', 'NAME',
				'IBLOCK_ID',
				'DETAIL_PICTURE',
                'PROPERTY_MEASURE',
                'PROPERTY_MULTIPLICITY_MEASURE'
			)
		);
		$nav->setRecordCount($obList->SelectedRowsCount());
		$nav->setCurrentPage($matchPage[1]);
		$productIds = [];


		while ($rs = $obList->fetch()){
			$rs['NAME'] = htmlspecialcharsbx($rs['NAME']);

			$rs['PRODUCT_NAME'] = $rs['NAME'];
			$rs['PRODUCT_ID'] = $rs['ID'];
			$productIds[$rs['ID']] = $rs['ID'];

			if((int)$rs['DETAIL_PICTURE'] > 0){
				$rs['IMAGE'] = \CFile::ResizeImageGet(
					$rs['DETAIL_PICTURE'],
					['width' => 160, 'height' => 200],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}

            $rs['SKU'] = array(
                'MEASURE_SHORT_NAME' => $defaultMeasure['SYMBOL_RUS'],
                'MEASURE_RATIO' => $defaultRatio
            );

			$measureId = $rs['PROPERTY_MEASURE_VALUE'] ? : false;
			if ($measureId) {
                $rs['SKU']['MEASURE_SHORT_NAME'] = MeasureSettings::getMeasureById($measureId)['SYMBOL_RUS'];
			}

			$measureRatio = $rs['PROPERTY_MULTIPLICITY_MEASURE_VALUE'] ? : false;
			if ($measureRatio) {
                $rs['SKU']['MEASURE_RATIO'] = $measureRatio;
			}

			$basket = BasketShopTable::getRow([
				'select' => ['ID', 'QUANTITY', 'PRODUCT_ID'],
				'filter' => [
					'=FUSER_ID' => Fuser::getId(),
					'=PRODUCT_ID' => $rs['PRODUCT_ID'],
					'BASKET_ID' => false
				]
			]);
			$rs['BASKET_QUANTITY'] = 0;

			if(!is_null($basket)){
				$rs['BASKET_QUANTITY'] = $basket['QUANTITY'];
			}

			$rs['FAVORITE'] = FavoriteTable::getRow([
				'filter' => ['=ELEMENT_ID' => $rs['PRODUCT_ID'], '=USER_ID' => $this->getUser()->GetID()]
			]);

			$products[$rs['ID']] = $rs;
		}

		if(count($productIds) > 0){
			$obPrices = \CIBlockElement::GetList(
				array(),
				array(
					'=ACTIVE' => 'Y', 'IBLOCK_ID' => $skuIblock,
					"PROPERTY_SHOP_ID" => $this->arParams['SHOP_INFO']['ID'],
					'PROPERTY_CML2_LINK' => array_values($productIds),
				),
				false,
				array('nTopCount' => count($productIds)),
				array(
					'ID', 'NAME', 'IBLOCK_ID', 'CATALOG_GROUP_1', 'PROPERTY_CML2_LINK'
				)
			);
			while ($rPrice = $obPrices->Fetch()){
				$products[$rPrice['PROPERTY_CML2_LINK_VALUE']]['SKU_ID'] = $rPrice['ID'];
				$products[$rPrice['PROPERTY_CML2_LINK_VALUE']]['PRICE_VAL'] = $rPrice['CATALOG_PRICE_1'];
				$products[$rPrice['PROPERTY_CML2_LINK_VALUE']]['PRICE_FORMAT'] = \SaleFormatCurrency($rPrice['CATALOG_PRICE_1'], 'RUB', true);
			}
		}

		$this->nav = $nav;

		return $products;
	}

	protected function compileChain()
	{
		$start = $this->arParams['START_CHAIN_SECTION'];
		$chain = new \ArrayIterator([
			array(
				'NAME' => $this->arParams['SHOP_INFO']['NAME'],
				'URL' => $start.$this->arParams['SHOP_INFO']['ID'].'/'
			)
		]);
		$chain->append(array(
			'NAME' => $this->arParams['IBLOCK_INFO']['NAME'],
			'URL' => $chain->current()['URL'].$this->arParams['IBLOCK_INFO']['ID'].'/'
		));
		$chain->next();

		if(empty($this->arResult['SECTION_INFO']) && (int)$this->arParams['CURRENT_SECTION'] > 0){
			$this->arResult['SECTION_INFO'] = \Bitrix\Iblock\SectionTable::getRow([
				'select' => [
					'SECTION_ID' => 'ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PICTURE', 'SORT',
					'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL', 'DESCRIPTION', 'CODE', 'XML_ID', 'DETAIL_PICTURE',
				],
				'filter' => ['=ID' => $this->arParams['CURRENT_SECTION']]
			]);
		}
		if((int)$this->arParams['CURRENT_SECTION'] > 0){
			$chain->append([
				'NAME' => $this->arResult['SECTION_INFO']['NAME'],
				'URL' => ''
			]);
		}
		return $chain;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->getCatalogInfo($this->arParams['IBLOCK_ID']);

		if((int)$this->arParams['CURRENT_SECTION'] > 0){
			$current = $this->arParams['CATEGORY_TREE']['SECTIONS'][$this->arParams['CURRENT_SECTION']];

			if(count($current['SUB_SECTION']) > 0){
				$this->sectionIterator = new Dictionary($current['SUB_SECTION']);
			}
			unset($current['SUB_SECTION']);
			$this->arResult['SECTION_INFO'] = $current;
		} else {
			$this->sectionIterator = new Dictionary($this->arParams['CATEGORY_TREE']['ITEMS']);
		}

		if($this->sectionIterator->count() == 0){
			$this->errorCollection->setError(new Error('Товары не найдены', 404));
		}

		$this->arResult['SECTIONS_PRODUCT'] = [];

		$shopId = $this->arParams['SHOP_INFO']['ID'];
		$urlTemple = $this->arParams['URL_TEMPLATE']['SECTION_URL'];

		if($shopId){
			$urlTemple = str_replace('#SHOP_ID#', $shopId, $urlTemple);
		}

		$sectionCurrent = null;
		foreach ($this->sectionIterator as $id => $arSection) {
			$sectionCurrent = [];
			$url = $urlTemple;
			$arSection['SECTION_ID'] = $id;
//			$arSection['SECTION_PAGE_URL'] = '';
			$products = [];
			$limit = 4;

			if(count($arSection['SUB_SECTION']) > 0){
				foreach ($arSection['SUB_SECTION'] as $item) {
					$sectionCurrent[] = $item['SECTION_ID'];
				}
			} elseif($arSection['ID'] > 0) {
//				$limit = 16;
				$sectionCurrent[] = $arSection['ID'];
			} else {
				$limit = 16;
			}
//			PR($arSection['NAME']);

			try{
				$products = $this->getProductsSku($sectionCurrent, $limit);
			} catch (\Exception $e){
				$this->errorCollection->setError(new Error($e->getMessage(), 502));
			}


			if(count($products) == 0)
				continue;

			$url = str_replace('#IBLOCK_ID#', $arSection['IBLOCK_ID'], $url);
			$arSection['SECTION_PAGE_URL'] = str_replace('#SECTION_ID#', $arSection['SECTION_ID'], $url);

			$this->arResult['SECTIONS_PRODUCT'][$arSection['SECTION_ID']] = $arSection;

//			dump($arSection['SECTION_ID']);
//			dump($products);

			$this->arResult['SECTIONS_PRODUCT'][$arSection['SECTION_ID']]['PRODUCTS'] = $products;
		}

//		PR($this->arResult['SECTIONS_PRODUCT']);

		$this->arResult['URL_CHAIN'] = $this->compileChain();

		if($this->errorCollection->count() > 0){
			$this->arResult['ERRORS'] = $this->getErrors();
			$this->includeComponentTemplate('error');
		} else {
			$this->includeComponentTemplate();
		}

	}
}

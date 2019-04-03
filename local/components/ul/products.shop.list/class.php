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
use AB\Tools\Helpers\DataCache;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Collection;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Iblock\SectionTable;
use Bitrix\Iblock\SectionElementTable;
use Bitrix\Sale\Fuser;
use UL\Main\Basket\Model\BasketShopTable;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;

Loader::includeModule('ab.iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');
Loader::includeModule('iblock');

Loc::loadLanguageFile(__FILE__);

class ProductsShopsComponent extends \CBitrixComponent
{
	private $fUser = null;

	/** @var ErrorCollection */
	protected $errorCollection;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		$this->fUser = \CSaleBasket::GetBasketUserID();

		$this->errorCollection = new ErrorCollection();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
//		$arParams['CACHE_TYPE'] = 'N';
		return $arParams;
	}

	/**
	 * @method getErrors
	 * @return string
	 */
	protected function getErrors()
	{
		$errors = [];
		/** @var \Bitrix\Main\Error $Error */
		foreach ($this->errorCollection as $Error) {
			$errors[] = $Error->getMessage();
		}

		return $errors;
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	protected function getSubsectionEntity($iblockId, $sectionId, $limit = null)
	{

		$iblockId = (int)$iblockId;
		$sectionId = (int)$sectionId;

		$subSections[$sectionId] = $sectionId;

		$sectionQuery = new Entity\Query(SectionTable::getEntity());
		$sectionQuery->setTableAliasPostfix('_parent');
		$sectionQuery->setSelect(array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));
		$sectionQuery->setFilter(array('@ID' => $subSections));

		$subSectionQuery = new Entity\Query(SectionTable::getEntity());
		$subSectionQuery->setTableAliasPostfix('_sub');
		$subSectionQuery->setSelect(array('ID'));
		$subSectionQuery->setFilter(array('=IBLOCK_ID' => $iblockId));
		$subSectionQuery->registerRuntimeField(
			'',
			new Entity\ReferenceField(
				'BS',
				Entity\Base::getInstanceByQuery($sectionQuery),
				array('>=this.LEFT_MARGIN' => 'ref.LEFT_MARGIN', '<=this.RIGHT_MARGIN' => 'ref.RIGHT_MARGIN'),
				array('join_type' => 'INNER')
			)
		);

		$sectionElementQuery = new Entity\Query(SectionElementTable::getEntity());
		$sectionElementQuery->setSelect(array('IBLOCK_ELEMENT_ID'));
		$sectionElementQuery->setGroup(array('IBLOCK_ELEMENT_ID'));
		$sectionElementQuery->setFilter(array('=ADDITIONAL_PROPERTY_ID' => null));
		$sectionElementQuery->registerRuntimeField(
			'',
			new Entity\ReferenceField(
				'BSUB',
				Entity\Base::getInstanceByQuery($subSectionQuery),
				array('=this.IBLOCK_SECTION_ID' => 'ref.ID'),
				array('join_type' => 'INNER')
			)
		);

		$elementQuery = new Entity\Query(\Bitrix\Iblock\ElementTable::getEntity());
		$elementQuery->setSelect(array('ID'));
		$elementQuery->setFilter(array('=IBLOCK_ID' => $iblockId, '=WF_STATUS_ID' => 1, '=WF_PARENT_ELEMENT_ID' => null));
		$elementQuery->registerRuntimeField(
			'',
			new Entity\ReferenceField(
				'BSE',
				Entity\Base::getInstanceByQuery($sectionElementQuery),
				array('=this.ID' => 'ref.IBLOCK_ELEMENT_ID'),
				array('join_type' => 'INNER')
			)
		);
		$elementQuery->setLimit($limit);
		unset($subSectionQuery, $sectionQuery, $sectionElementQuery);

		return $elementQuery;
	}


	protected function getProductsIds($limit = 8)
	{
		if (empty($this->arResult['CATALOG_INFO']))
			throw new \Exception('Каталог не является торговым');

		$products = [];

		/** @var Dictionary $shopInfo */
		$shopInfo = $this->arResult['SHOPS_INFO'];
//		PR($this->arResult['CATALOG_INFO']);
//		PR($this->arParams['SECTION']);


//		dump($this->arResult['CATALOG_INFO']);

		$sectionsIds = [];
		$subSectionsElements = [];
		if (intval($this->arParams['SECTION']['ID']) > 0){

			$iblockId = (int)$this->arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID'];
			$sectionId = (int)$this->arParams['SECTION']['ID'];

			$subSections[$sectionId] = $sectionId;

			$sectionQuery = new Entity\Query(SectionTable::getEntity());
			$sectionQuery->setTableAliasPostfix('_parent');
			$sectionQuery->setSelect(array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));
			$sectionQuery->setFilter(array('@ID' => $subSections));

			$subSectionQuery = new Entity\Query(SectionTable::getEntity());
			$subSectionQuery->setTableAliasPostfix('_sub');
			$subSectionQuery->setSelect(array('ID'));
			$subSectionQuery->setFilter(array('=IBLOCK_ID' => $iblockId));
			$subSectionQuery->registerRuntimeField(
				'',
				new Entity\ReferenceField(
					'BS',
					Entity\Base::getInstanceByQuery($sectionQuery),
					array('>=this.LEFT_MARGIN' => 'ref.LEFT_MARGIN', '<=this.RIGHT_MARGIN' => 'ref.RIGHT_MARGIN'),
					array('join_type' => 'INNER')
				)
			);
			$iterator = $subSectionQuery->exec()->fetchAll();

			foreach ($iterator as $item) {
				$sectionsIds[] = $item['ID'];
			}
		}

		foreach ($this->arParams['SHOP_ID'] as $shopId) {
			if ($shopInfo->offsetExists($shopId)){

				$filter = [
					'ACTIVE' => 'Y',
					'PROPERTY.CML2_LINK.ACTIVE' => 'Y',
					'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['IBLOCK_ID'],
					'PROPERTY.SHOP_ID.ID' => $shopId,
				];

				if (count($sectionsIds) > 0){
					$filter['@PROPERTY.CML2_LINK.IBLOCK_SECTION_ID'] = $sectionsIds;
				}

				$obSku = Element::getList([
					'select' => ['CML2_LINK' => 'PROPERTY.CML2_LINK.ID'],
					'filter' => $filter,
					'limit' => $limit,
					'group' => ['PROPERTY.CML2_LINK.ID'],
				]);

				while ($sku = $obSku->fetch()) {
					$products[$shopId][] = $sku['CML2_LINK'];
				}

			}
		}

		unset($skuQuery, $entitySubSection);

		return $products;
	}

	protected function getCatalogInfo($iblockId)
	{
		$this->arResult['CATALOG_INFO'] = \CCatalogSKU::GetInfoByIBlock($iblockId);
	}

	protected function getCacheIdShop($shopId)
	{
		return 'product_list'.$shopId.$this->arParams['SECTION']['ID'];
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{

		$this->arResult['SHOPS_INFO'] = CatalogHelper::getAvailableShops($this->arParams['SHOP_ID']);

		try {
			$this->getCatalogInfo($this->arParams['IBLOCK_ID']);
			$ids = $this->getProductsIds();
			$products = [];


			foreach ($ids as $shopId => $productsIds) {
				$obProduct = Element::getList([
					'select' => [
						'PRODUCT_ID' => 'ID',
						'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'IBLOCK_SECTION_ID',
						'FAVORITE_ID' => "FAVORITE.ID",
					],
					'filter' => [
						'@ID' => $productsIds,
						'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['PRODUCT_IBLOCK_ID'],
					],
					'limit' => count($productsIds),
					'runtime' => [
						new Entity\ReferenceField(
							'FAVORITE',
							\UL\Main\Services\FavoriteTable::getEntity(),
							[
								'=this.ID' => 'ref.ELEMENT_ID',
								'ref.USER_ID'=>array('?i', (int)$this->getUser()->GetID())
							]
						),
//						new Entity\ReferenceField(
//							'BASKET_DATA',
//							BasketShopTable::getEntity(),
//							['=this.ID' => 'ref.PRODUCT_ID', ['ref.FUSER_ID' => array('?', Fuser::getId())], 'ref.BASKET_ID' => array('?', false)]
//						)
					]
				]);
				$products[$shopId] = $this->arResult['SHOPS_INFO']->get($shopId);

				$shopUrl = $this->arParams['SHOP_FOLDER'].$this->arResult['SHOPS_INFO']->get($shopId)['ID'].'/';
				if((int)$this->arParams['IBLOCK_INFO']['ID'] > 0){
					$shopUrl .= $this->arParams['IBLOCK_INFO']['ID'].'/';
				}
				if((int)$this->arParams['SECTION']['ID'] > 0){
					$shopUrl .= $this->arParams['SECTION']['ID'].'/';
				}
				$products[$shopId]['SHOP_URL'] = $shopUrl;

				while ($product = $obProduct->fetch()) {
					$basket = BasketShopTable::getRow([
						'select' => ['ID', 'QUANTITY', 'PRODUCT_ID'],
						'filter' => [
							'=FUSER_ID' => Fuser::getId(),
							'=PRODUCT_ID' => $product['PRODUCT_ID'],
							'BASKET_ID' => false
						]
					]);
					$product['BASKET_QUANTITY'] = 0;

					if(!is_null($basket)){
						$product['BASKET_QUANTITY'] = $basket['QUANTITY'];
					}


					if (intval($product['DETAIL_PICTURE']) > 0){
						$product['IMAGE'] = \CFile::ResizeImageGet(
							$product['DETAIL_PICTURE'],
							['width' => 160, 'height' => 200],
							BX_RESIZE_IMAGE_PROPORTIONAL_ALT
						);
					}

					$product['NAME'] = htmlspecialcharsbx($product['NAME']);
//					$product['NAME'] = str_replace('"', '', $product['NAME']);

					$products[$shopId]['ITEMS'][$product['PRODUCT_ID']] = $product;
				}

                $defaultMeasure = MeasureSettings::getDefaultMeasure();
                $defaultRatio = MeasureSettings::getDefaultMeasureRatio();


				$skuIterator = Element::getList([
					'select' => [
						'ID', 'NAME',
						'CML2_LINK' => 'PROPERTY.CML2_LINK.ID',
						'MEASURE_NAME' => 'MEASURE.MEASURE_TITLE',
                        'MEASURE_SHORT_NAME' => 'MEASURE.SYMBOL_RUS',
						'MEASURE_RATIO' => 'RATIO.RATIO',
						'PRICE_VAL' => 'PRICE.PRICE',
						'CURRENCY' => 'PRICE.CURRENCY'
					],
					'runtime' => [
						new Entity\ReferenceField(
							'CATALOG',
							\Bitrix\Catalog\ProductTable::getEntity(),
							['=this.ID' => 'ref.ID']
						),
                        new Entity\ReferenceField(
                            'RATIO',
                            \Bitrix\Catalog\MeasureRatioTable::getEntity(),
                            ['=this.ID' => 'ref.PRODUCT_ID']
                        ),
						new Entity\ReferenceField(
							'PRICE',
							\Bitrix\Catalog\PriceTable::getEntity(),
							['=this.ID' => 'ref.PRODUCT_ID']
						),
                        new Entity\ReferenceField(
                            'MEASURE',
                            MeasureTable::getEntity(),
                            ['=this.CATALOG.MEASURE' => 'ref.ID']
                        ),
					],
					'filter' => [
						'IBLOCK_ID' => $this->arResult['CATALOG_INFO']['IBLOCK_ID'],
						'@PROPERTY.CML2_LINK.ID' => $productsIds,
						'=PROPERTY.SHOP_ID.ID' => $shopId,
						'=ACTIVE' => 'Y',
					],
				]);



				while ($sku = $skuIterator->fetch()) {


                    $sku['MEASURE_NAME'] = $sku['MEASURE_NAME'] ? : $defaultMeasure['MEASURE_TITLE'];
                    $sku['MEASURE_SHORT_NAME'] = $sku['MEASURE_SHORT_NAME'] ? : $defaultMeasure['SYMBOL_RUS'];
                    $sku['MEASURE_RATIO'] = $sku['MEASURE_RATIO'] ? : $defaultRatio;

					$sku['NAME'] = htmlspecialcharsbx($sku['NAME']);
//					$sku['NAME'] = str_replace('"', '', $sku['NAME']);

					$sku['BASKET'] = $_SESSION['UL_BASKET_ITEMS'][$sku['ID']];
					$sku['PRICE_FORMAT'] = SaleFormatCurrency($sku['PRICE_VAL'], $sku['CURRENCY'], true);
					$products[$shopId]['ITEMS'][$sku['CML2_LINK']]['SKU'] = $sku;
				}
			}


			$this->arResult['SHOP_DATA'] = $products;
			unset($products, $ids);


			$this->includeComponentTemplate();

		} catch (\Exception $err) {
			\ShowError($err->getMessage());
		}

	}
}
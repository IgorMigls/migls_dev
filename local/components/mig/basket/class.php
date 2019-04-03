<?php namespace Mig;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Debug;
use AB\Tools\Helpers\DataCache;
use Bitrix\Catalog;
use Bitrix\Sale;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use function dump;
use function preg_match;
use Soft\Element;
use UL\Main\Basket\Model\BasketShopTable;
use Online1c\Iblock;
use UL\Main\Dates;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;
use Ul\Main\Measure\ProductMeasure;
use UL\Main\Services\FavoriteTable;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('catalog');

Loc::loadLanguageFile(__FILE__);

class BasketComponent extends \CBitrixComponent
{
	/** @var  array */
	protected $postData;

	protected $fUser;

	const BASKET_PREFIX = 'SHOP_BASKET';

	/** @var Main\Type\Dictionary */
	private $cacheCatalogData;

	/** @var Main\Type\Dictionary */
	private $cacheCatalogSku;

	/** @var  Main\Type\Dictionary */
	private $shopInfo;

	/** @var Main\Type\Dictionary */
	private $deliveryTime;

	const SHOP_CLOSE_FULL = 3;
	const SHOP_CLOSE_PART = 2;
	const SHOP_CLOSE_NONE = 1;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->fUser = Sale\Fuser::getId(true);

		$this->cacheCatalogData = new Main\Type\Dictionary();
		$this->cacheCatalogSku = new Main\Type\Dictionary();
		$this->shopInfo = new Main\Type\Dictionary();
		$this->deliveryTime = new Main\Type\Dictionary();

		$CSaleBasket = new \CSaleBasket();
		$CSaleBasket->DeleteAll($this->fUser);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
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

	private static function _sortBasketItems($a, $b)
	{
		if ($a['CAN_BUY'] == $b['CAN_BUY'])
			return 0;

		return ($a['CAN_BUY'] < $b['CAN_BUY']) ? -1 : 1;
	}

	/**
	 * @method getBasketData
	 * @return array [
	 *  SHOP_CODE => [
	 *          BASKET => [
	 *              PRODUCT_ID => [ ... ]
	 *          ]
	 *      ]
	 * ]
	 * @throws Main\ArgumentException
	 */
	public function getBasketData()
	{

//		BasketShopTable::clearTable();
//		BasketShopTable::createTable();

		$query = new Main\Entity\Query(BasketShopTable::getEntity());
		$query->setSelect([
			'*',
			'SHOP_IMG' => 'SHOP.DETAIL_PICTURE',
			'SHOP_NAME' => 'SHOP.NAME',
		]);

		$query->setFilter(['=FUSER_ID' => $this->fUser, 'BASKET_ID' => false]);

		$oBasket = $query->exec();

		$result['ITEMS'] = null;
		$basketData = [];

		while ($basketItem = $oBasket->fetch()) {
			if (!in_array($basketItem['SHOP_ID'], $_SESSION['REGIONS']['SHOP_ID'])){
				$basketItem['CAN_BUY'] = 0;
			}
			if ((int)$basketItem['IMG'] > 0){
				$basketItem['PICTURE'] = \CFile::ResizeImageGet(
					$basketItem['IMG'],
					['width' => 90, 'height' => 90],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}
			$basketData[$basketItem['SHOP_CODE']]['BASKET'][] = $basketItem;
		}

		$total = $sum = 0;


		foreach ($basketData as $shopCode => &$shop) {
			$sumShop = $totalShop = 0;
			usort($shop['BASKET'], [$this, '_sortBasketItems']);

			$basketProducts = [];
			$shopClosed = 0;
			foreach ($shop['BASKET'] as $k => $item) {


				$item['PRICE_FORMAT'] = self::formatPrice($item['PRICE']);

				$basketProducts[$item['PRODUCT_ID']] = $item;

				$shop['NAME'] = $item['SHOP_NAME'];
				$shop['CODE'] = $item['SHOP_CODE'];
				$shop['SHOP_ID'] = $item['SHOP_ID'];
				$shop['IMG'] = $item['SHOP_IMG'];

				if ($item['CAN_BUY'] == 1){
					$sumShop += $item['PRICE'];
					$sum += $item['PRICE'];
					$total++;
					$totalShop++;
				} else {
					$shopClosed++;
				}
			}

			$shop['BASKET'] = $basketProducts;
			$shop['SUM'] = $sumShop;
			$shop['SUM_FORMAT'] = self::formatPrice($shop['SUM']);
			$shop['COUNT'] = $totalShop;
			$shop['COUNT_FORMAT'] = \UL\Tools::formatContProduct($shop['COUNT']);


			if ($shop['SUM'] < 1000){
				$shop['MESSAGE'] = [
					'danger' => ['msg' => 'Мин. сумма заказа в магазине должна быть 1000 р', 'show' => true],
				];
			} else {
				$shop['MESSAGE'] = [
					'danger' => ['msg' => 'Мин. сумма заказа в магазине должна быть 1000 р', 'show' => false],
				];
			}

			$shop = $shop + $this->getShopInfo($shop['SHOP_ID']);
		}

		foreach ($basketData as $shopCode => &$shopData) {
			$shopClosed = 0;
			foreach ($shopData['BASKET'] as &$basketItem) {
				if($basketItem['CAN_BUY'] == 0){
					$shopClosed++;
				}

				/** @todo лучше конечно переделать на групповой запрос по ID.. но в корзине не будет тысячи товаров, пока терпит */
                $measure = ProductMeasure::getMeasureByProductId($basketItem['SKU_IBLOCK_ID'], $basketItem['SKU_ID']);

                $basketItem['MEASURE_NAME'] = $measure->getName();
                $basketItem['MEASURE_SHORT_NAME'] = $measure->getShortName();
                $basketItem['MEASURE_RATIO'] = $measure->getRatio();
			}

			if($shopClosed > 0 && $shopClosed < count($basketData[$shopCode]['BASKET']) ){
				$basketData[$shopCode]['CLOSED'] = self::SHOP_CLOSE_PART;
			} elseif ($shopClosed >= count($basketData[$shopCode]['BASKET'])){
				$basketData[$shopCode]['CLOSED'] = self::SHOP_CLOSE_FULL;
			} else {
				$basketData[$shopCode]['CLOSED'] = self::SHOP_CLOSE_NONE;
			}
		}

		uasort($basketData, array($this, '_sortClosedShops'));


		//$line = debug_backtrace()[1]['file'] . ":" . debug_backtrace()[1]['line'];

		//var_dump($line);
		//exit;

		return [
			'items' => $basketData,
			'total' => $total,
			'sum' => $sum,
			'sumFormat' => self::formatPrice($sum),
		];
	}

	private static function _sortClosedShops($a, $b)
	{
		if ($a['CLOSED'] == $b['CLOSED'])
			return 0;

		return ($a['CLOSED'] < $b['CLOSED']) ? -1 : 1;
	}

	public static function formatPrice($sum = 0)
	{
		return \SaleFormatCurrency($sum, 'RUB', true);
	}

	public function getShopInfo($shopId = 0)
	{
		$result = [];

		if ($this->shopInfo->offsetExists($shopId)){
			return $this->shopInfo->get($shopId);
		}

		$cache = new DataCache(3600, '/mig/shops', $shopId);
		$cache->clear(); // todo DELETE !!!
		if ($cache->isValid()){
			$result = $cache->getData();
		} else {
			$result = Iblock\Element::getRow([
				'select' => [
					'ID', 'CODE', 'NAME', 'DETAIL_PICTURE',
					'NO_AVAILABLE' => 'PROPERTY.NO_AVAILABLE',
					'DELIVERY_TIME' => 'PROPERTY.DELIVERY_TIME',
					'DELIVERY_ID' => 'PROPERTY.DELIVERY_TIME_BIND.ID',
					'DELIVERY_IBLOCK_ID' => 'PROPERTY.DELIVERY_TIME_BIND.IBLOCK_ID',
					'DELIVERY_LEFT_MARGIN' => 'PROPERTY.DELIVERY_TIME_BIND.LEFT_MARGIN',
					'DELIVERY_RIGHT_MARGIN' => 'PROPERTY.DELIVERY_TIME_BIND.RIGHT_MARGIN',
				],
				'filter' => [
					'IBLOCK_ID' => 5,
					'=ID' => $shopId,
					'=ACTIVE' => 'Y',
				],
			]);
			if (!is_null($result)){
				if ($result['DETAIL_PICTURE']){
					$result['PICTURE'] = \CFile::ResizeImageGet(
						$result['DETAIL_PICTURE'],
						['width' => 120, 'height' => 40],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
				}
				$result['NO_AVAILABLE'] = (int)$result['NO_AVAILABLE'] == 0 ? false : true;

				if ((int)$result['DELIVERY_ID'] > 0){
					$result['DELIVERY_TIMES'] = $this->getTimeDelivery([
						'ID' => $result['DELIVERY_ID'],
						'LEFT_MARGIN' => $result['DELIVERY_LEFT_MARGIN'],
						'RIGHT_MARGIN' => $result['DELIVERY_RIGHT_MARGIN'],
					]);

					$currentDelivery = [];
					$result['CALENDAR'] = $this->rotationDays($result['DELIVERY_TIMES']);

					$dayNum = 0;
					foreach ($result['DELIVERY_TIMES'] as $DELIVERY_TIME) {
						if($DELIVERY_TIME['CLOSED'] == true){
							continue;
						}


						if($DELIVERY_TIME['CLOSED'] !== true){
//							$dayNum = $DELIVERY_TIME['DAY_NUMBER'];
							break;
						}

						$dayNum++;
					}

//					$cal = $result['DELIVERY_TIMES'];
					$currentTmp = $result['CALENDAR'][$dayNum];
					$currentDelivery = [
						'NAME' => $currentTmp['NAME'],
						'DAY_NUMBER' => $currentTmp['DAY_NUMBER'],
						'DATE_FORMAT' => $currentTmp['FORMAT'],
						'CURRENT' => []
					];

					foreach ($currentTmp['TIMES'] as $k => $time) {

//						$dateCurrent = new Main\Type\DateTime('14.02.2018 12:00', 'd.m.Y H:i:s');
						$dateCurrent = new Main\Type\DateTime();
						$dateCurrent->add('+ 3 hour');
						preg_match('/^(\d+)/i', $time['TIME_TO'], $timeTo);

						if( $dateCurrent->format('G') > 20){
							continue;
						}

						if($dateCurrent->format('G') > $timeTo[1]){
							$result['CALENDAR'][0]['TIMES'][$k]['ACTIVE'] = 'N';
							continue;
						}
						if($time['ACTIVE'] == 'Y'){
							$currentDelivery['CURRENT'] = $time;
							break;
						}
					}

					if(count($currentDelivery['CURRENT']) == 0){
//						$currentTmp = array_shift($cal);
						$currentDelivery = [
							'NAME' => $currentTmp['NAME'],
							'DAY_NUMBER' => $currentTmp['DAY_NUMBER'],
							'DATE_FORMAT' => $currentTmp['FORMAT'],
							'CURRENT' => $currentTmp['TIMES'][0]
						];
					}

					$result['CURRENT'] = $currentDelivery;
					unset($cal);
				}

//				dd($result);

				$cache->addCache($result);
			}
		}
		$this->shopInfo->offsetSet($shopId, $result);

		return $result;
	}

	public function addAction($id = null, $quantity = 1)
	{
		$data = $this->getPostData();
		if ((int)$id == 0){
			$id = (int)$data['product'];
		}

		if ((float)$data['quantity'] > 0){
			$quantity = $data['quantity'];
		}
		if ($quantity == 0){
			$quantity = 1;
		}

		if ($id == 0){
			throw new \Exception('Нет товара', 500);
		}




//		BasketShopTable::dropTable();
//		BasketShopTable::createTable();

		$obCity = Iblock\Element::getList([
			'select' => ['ID', 'CODE'],
			'filter' => [
				'IBLOCK_ID' => 5,
				'IBLOCK_SECTION_ID' => $_SESSION['REGIONS']['CITY_ID'],
				'=ACTIVE' => 'Y',
				'=PROPERTY.NO_AVAILABLE' => 0,
				'=PROPERTY.OPENING_SOON' => 0,
			],
		]);

		$iblock = Iblock\Element::getIblockByElement($id);
		$cityIds = $cityList = [];
		while ($rs = $obCity->fetch()) {
			$cityList[$rs['ID']] = $rs;

			if ($data['shop'] == $rs['ID'])
				$currentShopCode = $rs['CODE'];
		}

		foreach ($cityList as $idShop => $item) {
			if (!in_array($idShop, $_SESSION['REGIONS']['SHOP_ID']) && $item['CODE'] == $currentShopCode){
				$cityIds[] = $item['ID'];
			}
		}

		$cityIds[] = (int)$data['shop'];

		$cityIds = array_unique($cityIds);

		$currentOfferIb = \CIBlockElement::GetIBlockByID($data['sku']);
		$currentOffer = \CIBlockElement::GetList(
			array(),
			array('IBLOCK_ID' => $currentOfferIb, '=ID' => $data['sku']),
			false,
			array('nTopCount'),
			array(
				'ID', 'NAME', 'IBLOCK_ID',
				'CATALOG_QUANTITY', 'CATALOG_GROUP_1',
				'PROPERTY_CML2_LINK.NAME',
				'PROPERTY_SHOP_ID.CODE',
				'PROPERTY_CML2_LINK',
				'PROPERTY_CML2_LINK.DETAIL_PICTURE',
				'PROPERTY_SHOP_ID'
			)
		)->Fetch();
		if(!$currentOffer){
			throw new \Exception('Товар не найден', 404);
		}

		$offer = [
			'ID' => $currentOffer['ID'],
			'NAME' => $currentOffer['NAME'],
			'PICTURE' => (int)$currentOffer['PROPERTY_CML2_LINK_DETAIL_PICTURE'],
			'PRICE' => $currentOffer['CATALOG_PRICE_1'],
			'QUANTITY' => $quantity,
			'PRODUCT_ID' => (int)$currentOffer['PROPERTY_CML2_LINK_VALUE'],
			'SHOP_ID' => (int)$currentOffer['PROPERTY_SHOP_ID_VALUE'],
			'SHOP_CODE' => $currentOffer['PROPERTY_SHOP_ID_CODE'],
			'PRODUCT_NAME' => $currentOffer['PROPERTY_CML2_LINK_NAME'],
			'PRODUCT_IBLOCK_ID' => (int)$iblock,
			'IBLOCK_ID' => (int)$currentOffer['IBLOCK_ID'],
		];


		$res = $this->add($offer, $quantity);

//		$currentOffers = $this->addBasketCurrentShop($data['sku'], $iblock);
//		foreach ($currentOffers as $arOffer) {
//			$this->add($arOffer, $quantity);
//		}

		/*$offerList = \CCatalogSku::getOffersList($id, $iblock,
			[
				'=PROPERTY_SHOP_ID' => $cityIds,
				'=ACTIVE' => 'Y',
				'CATALOG_AVAILABLE' => 'Y',
			],
			[
				'NAME', 'ID', 'PROPERTY_CML2_LINK.DETAIL_PICTURE', 'IBLOCK_ID',
				'CATALOG_GROUP_1', 'PROPERTY_SHOP_ID', 'PROPERTY_CML2_LINK',
				'PROPERTY_SHOP_ID.CODE',
				'PROPERTY_CML2_LINK.NAME',
				'PROPERTY_CML2_LINK.IBLOCK_ID',
			]
		);

		$offers = [];
		foreach ($offerList[$id] as $item) {
			$offers[$item['PROPERTY_SHOP_ID_VALUE']] = [
				'ID' => $item['ID'],
				'NAME' => $item['NAME'],
				'PICTURE' => (int)$item['PROPERTY_CML2_LINK_DETAIL_PICTURE'],
				'PRICE' => $item['CATALOG_PRICE_1'],
				'QUANTITY' => $quantity,
				'PRODUCT_ID' => (int)$item['PROPERTY_CML2_LINK_VALUE'],
				'SHOP_ID' => (int)$item['PROPERTY_SHOP_ID_VALUE'],
				'SHOP_CODE' => $item['PROPERTY_SHOP_ID_CODE'],
				'PRODUCT_NAME' => $item['PROPERTY_CML2_LINK_NAME'],
				'PRODUCT_IBLOCK_ID' => (int)$item['PROPERTY_CML2_LINK_IBLOCK_ID'],
				'IBLOCK_ID' => (int)$item['IBLOCK_ID'],
			];
		}

		foreach ($offers as $arOffer) {
			$this->add($arOffer, $quantity);
		}*/

		return $this->getSumCurrentShops();
	}

	protected function addBasketCurrentShop($id, $iblock)
	{

		/*$city = Iblock\Element::getList([
			'select' => ['ID', 'NAME'],
			'filter' => ['IBLOCK_ID' => 5, '!=PROPERTY.NO_AVAILABLE' => 1, '@ID' => $_SESSION['REGIONS']['SHOP_ID']]
		]);*/

		$offerList = \CCatalogSku::getOffersList($id, $iblock,
			[
				'=PROPERTY_SHOP_ID' => $_SESSION['REGIONS']['SHOP_ID'],
				'=ACTIVE' => 'Y',
				'CATALOG_AVAILABLE' => 'Y',
			],
			[
				'NAME', 'ID', 'PROPERTY_CML2_LINK.DETAIL_PICTURE', 'IBLOCK_ID',
				'CATALOG_GROUP_1', 'PROPERTY_SHOP_ID', 'PROPERTY_CML2_LINK',
				'PROPERTY_SHOP_ID.CODE',
				'PROPERTY_CML2_LINK.NAME',
				'PROPERTY_CML2_LINK.IBLOCK_ID',
			]
		);
		$result = [];
		foreach ($offerList[$id] as $item){
			$result[$item['PROPERTY_SHOP_ID_VALUE']] = [
				'ID' => $item['ID'],
				'NAME' => $item['NAME'],
				'PICTURE' => (int)$item['PROPERTY_CML2_LINK_DETAIL_PICTURE'],
				'PRICE' => $item['CATALOG_PRICE_1'],
				'QUANTITY' => (float)$item['CATALOG_QUANTITY'],
				'PRODUCT_ID' => (int)$item['PROPERTY_CML2_LINK_VALUE'],
				'SHOP_ID' => (int)$item['PROPERTY_SHOP_ID_VALUE'],
				'SHOP_CODE' => $item['PROPERTY_SHOP_ID_CODE'],
				'PRODUCT_NAME' => $item['PROPERTY_CML2_LINK_NAME'],
				'PRODUCT_IBLOCK_ID' => (int)$item['PROPERTY_CML2_LINK_IBLOCK_ID'],
				'IBLOCK_ID' => (int)$item['IBLOCK_ID'],
			];
		}

		return $result;
	}

	protected function getTimeDelivery($arSection)
	{
		if ($this->deliveryTime->offsetExists($arSection['ID']))
			return $this->deliveryTime->get($arSection['ID']);

		$oItems = Iblock\Element::getList([
			'select' => [
				'ID', 'NAME', 'ACTIVE',
				'TIME_FROM' => 'PROPERTY.TIME_FROM',
				'TIME_TO' => 'PROPERTY.TIME_TO',
				'PRICE' => 'PROPERTY.PRICE',
				'SECTION_CODE' => 'IBLOCK_SECTION.CODE',
				'SECTION_ID' => 'IBLOCK_SECTION_ID',
				'SECTION_NAME' => 'IBLOCK_SECTION.NAME',
                'CLOSED' => 'PROPERTY.CLOSED'
			],
			'filter' => [
				'IBLOCK_ID' => 8, '=ACTIVE' => 'Y',
				'>=IBLOCK_SECTION.LEFT_MARGIN' => $arSection['LEFT_MARGIN'],
				'<=IBLOCK_SECTION.RIGHT_MARGIN' => $arSection['RIGHT_MARGIN'],
			],
			'order' => ['IBLOCK_SECTION.CODE' => 'ASC', 'SORT' => 'ASC'],
		]);
		$times = [];

		$currentDate = new Main\Type\DateTime();

//		$currentDate = $currentDate->add('+ 3 days');
		$currentNumberDay = (int)$currentDate->format('N');

		while ($item = $oItems->fetch()) {
			$timeSection = [
				'NAME' => $item['SECTION_NAME'],
				'DAY_NUMBER' => $item['SECTION_CODE'],
			];

			if ($currentDate->format('N') == $item['SECTION_CODE']){
				$timeSection['NAME'] = 'Сегодня';
				$currentTime = new Main\Type\DateTime();
				$hourInterval = new Main\Type\DateTime();
				$currentHourFormat = $hourInterval->add('+ 3 hours')->format('H');

				$parseHourFrom = explode(':', $item['TIME_FROM'])[0];
				$end = new \DateTime($currentTime->format('d.m.Y ').'21:00');

				$currentHour = new \DateTime($currentTime->format('d.m.Y H:i:s'));
//				dump($currentHour->format('d'), new \DateTime(), $end);

//				Debug::toLog($currentHour);

				if ((int)$parseHourFrom < (int)$currentHourFormat || new \DateTime() >= $end
					|| ((int)$currentHour->format('d') != (int)$end->format('d') && $currentHour->format('A') == 'AM')
				){
					$item['ACTIVE'] = 'N';
				}
				$timeSection['DATE'] = new Main\Type\DateTime();
			} elseif (
				($currentNumberDay + 1) == $item['SECTION_CODE']
				|| ($currentNumberDay + 1) == 8 && $item['SECTION_CODE'] == 1
			) {
				$timeSection['NAME'] = 'Завтра';
				$tomorrow = new Main\Type\DateTime();
				$timeSection['DATE'] = $tomorrow->add('+ 1 days');
			}

			if ($currentNumberDay > $item['SECTION_CODE']){
				$timeSection['CLOSED'] = true;
			}


            $item['CLOSED_BY_ADMIN'] = 'N';
			if ($item['CLOSED']) {
			    //$item['ACTIVE'] = 'N';
			    $item['CLOSED_BY_ADMIN'] = 'Y';
			}


			$times[$item['SECTION_ID']] = array_merge($times[$item['SECTION_ID']], $timeSection);
			$times[$item['SECTION_ID']]['TIMES'][] = $item;
		}


		//var_dump($times[640]['TIMES']);
		//exit;
//		dd('ss');
		$this->deliveryTime->offsetSet($arSection['ID'], $times);

		return $this->deliveryTime->get($arSection['ID']);
	}

	/**
	 * @method add
	 * @param $product
	 * @param int $quantity
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	private function add($product, $quantity = 1)
	{
		$quantity = (float)$quantity;
		/** @todo ну нужно либо не давать людям ноль ставить, либо удалять из корзины когда 0, а то ноль поставил и вместо 0.5 в корзине 1 стало =) */
		if ($quantity == 0)
			$quantity = 1;


		if (is_null($product)){
			throw new \Exception('Продукт не найден', 404);
		}

		if (!$_SESSION['REGIONS']){
			throw new \Exception('Выберите зону доставки', 406);
		}

		$save = [
			'SKU_ID' => $product['ID'],
			'PRODUCT_ID' => $product['PRODUCT_ID'],
			'QUANTITY' => $quantity,
			'SHOP_ID' => $product['SHOP_ID'],
			'SHOP_CODE' => $product['SHOP_CODE'],
			'IMG' => $product['PICTURE'],
			'AREAL_ID' => (int)$_SESSION['REGIONS']['AREAL'],
			'PRICE' => $product['PRICE'],
			'FUSER_ID' => $this->fUser,
			'NAME' => $product['PRODUCT_NAME'],
			'WEIGHT' => 1000,
			'PRODUCT_IBLOCK_ID' => $product['PRODUCT_IBLOCK_ID'],
			'SKU_IBLOCK_ID' => $product['IBLOCK_ID'],
		];

		if ($save['QUANTITY'] > $product['QUANTITY']){
			throw new \Exception('На складе есть только '.$product['QUANTITY'].' шт', 502);
		}

		$row = BasketShopTable::getRow([
			'select' => ['ID', 'QUANTITY'],
			//Добавил условие BASKET_ID = false, иначе он находит корзины в находящиеся уже в заказе
			'filter' => ['=FUSER_ID' => $this->fUser, '=SKU_ID' => $save['SKU_ID'], 'SHOP_ID' => $save['SHOP_ID'], 'BASKET_ID' => false],
		]);

		if (!is_null($row)){
		    /** @todo вот тут я не понял на фига это сравнение? закоментил, по идие нам просто нужно апать кол-во */
		    /*
			if ($quantity < $row['QUANTITY']){
				$save['QUANTITY'] = $row['QUANTITY']++;
			}
		    */
		    $save['QUANTITY'] = $quantity;
			$resultSave = BasketShopTable::update($row['ID'], $save);
		} else {
			$resultSave = BasketShopTable::add($save);
		}

		if (!$resultSave->isSuccess()){
			throw new \Exception(implode(',', $resultSave->getErrorMessages()), 500);
		}

		$_SESSION[self::BASKET_PREFIX] = $this->getSumCurrentShops();

		return $_SESSION[self::BASKET_PREFIX];
	}

	/**
	 * @method updateQuantityAction
	 * @return bool
	 * @throws \Exception
	 */
	public function updateQuantityAction()
	{
		$data = $this->getPostData();

		$productId = (int)$data['productId'];
		$quantity = (float)$data['quantity'];

		if ($productId == 0)
			throw new \Exception('Нет товара', 500);

		if ($quantity == 0)
			$quantity = 1;

		try{
			$this->updateQuantity($productId, $quantity);
		} catch (\Exception $e){
			$errors = $e->getMessage();
		}

		if($errors){
			throw new \Exception($errors, 503);
		}

		return true;
	}

	/**
	 * @method updateQuantity
	 * @param $productId
	 * @param $quantity
	 *
	 * @throws \Exception
	 */
	public function updateQuantity($productId, $quantity)
	{
		$oItems = BasketShopTable::getList([
			'select' => ['ID'],
			'filter' => ['=FUSER_ID' => $this->fUser, '=PRODUCT_ID' => $productId, 'BASKET_ID' => false],
		]);
		while ($item = $oItems->fetch()) {
			$res = BasketShopTable::update($item['ID'], ['QUANTITY' => $quantity]);
			if(!$res->isSuccess()){
				throw new \Exception(implode(', ', $res->getErrorMessages()));
			}
		}
	}

	/**
	 * @method getSumCurrentShops
	 * @return array
	 */
	public function getSumCurrentShops()
	{
		$basket = [];
		$sum = 0;
		$oBasket = BasketShopTable::getList([
			'select' => ['*', 'SUM'],
			'filter' => ['=FUSER_ID' => $this->fUser, '=SHOP_ID' => $_SESSION['REGIONS']['SHOP_ID']],
		]);
		while ($rs = $oBasket->fetch()) {
			$basket[] = $rs;
			$sum += $rs['SUM'];
		}
		$result = [
			'count' => count($basket),
			'sum' => $sum,
			'sumFormat' => \SaleFormatCurrency($sum, 'RUB', true),
		];


		return $result;
	}

	/**
	 * @method delete
	 * @param $id
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function delete($id)
	{
		$items = BasketShopTable::getList([
			'select' => ['ID'],
			'filter' => ['=FUSER_ID' => $this->fUser, '=PRODUCT_ID' => $id, 'BASKET_ID' => false]
		])->fetchAll();
		foreach ($items as $item) {
			$res = BasketShopTable::delete($item['ID']);
			if(!$res->isSuccess()){
				throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
			}
		}

		return true;
	}

	/**
	 * @method deleteAction
	 * @throws \Exception
	 */
	public function deleteAction()
	{
		$data = $this->getPostData();
		$id = (int)$data['product'];
		if ($id == 0){
			throw new \Exception('Нет товара', 500);
		}

		return $this->delete($id);
	}

	/**
	 * @method saveComment
	 * @return bool
	 * @throws \Exception
	 */
	public function saveComment()
	{
		$data = $this->getPostData();
		$id = (int)$data['id'];
		if ($id == 0){
			throw new \Exception('Нет товара', 500);
		}

		$res = BasketShopTable::update($id, ['COMMENT' => $data['comment']]);
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return true;
	}

	/**
	 * @method getReplaceItems
	 * @return array
	 */
	public function getReplaceItems()
	{
		$data = $this->getPostData();

		$arProduct = Iblock\Element::getRow([
			'select' => ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID'],
			'filter' => ['=ID' => $data['product']]
		]);

		$iblock = $arProduct['IBLOCK_ID'];

        $defaultMeasure = MeasureSettings::getDefaultMeasure();
        $defaultRatio = MeasureSettings::getDefaultMeasureRatio();

		$catalog = \CCatalogSku::GetInfoByIBlock($iblock);
		$oList = Iblock\Element::getList([
			'select' => [
			    'CML2_LINK' => 'PROPERTY.CML2_LINK',
                'PRICE_VAL' => 'PRICE.PRICE',
                'MEASURE_NAME' => 'MEASURE.MEASURE_TITLE',
                'MEASURE_SHORT_NAME' => 'MEASURE.SYMBOL_RUS',
                'MEASURE_RATIO' => 'RATIO.RATIO'
            ],
			'filter' => [
				'IBLOCK_ID' => $catalog['IBLOCK_ID'],
				'=ACTIVE' => 'Y',
				'=PROPERTY.SHOP_ID' => $data['shop'],
				'!=ID' => $data['sku'],
				'>CATALOG.QUANTITY' => 0,
				'PROPERTY.CML2_LINK_BIND.IBLOCK_SECTION_ID' => $arProduct['IBLOCK_SECTION_ID']
			],
			'limit' => 26,
			'group' => ['PROPERTY.CML2_LINK'],
			'runtime' => [
				new Main\Entity\ReferenceField(
					'CATALOG',
					Catalog\ProductTable::getEntity(),
					['=this.ID' => 'ref.ID']
				),
				new Main\Entity\ReferenceField(
					'PRICE',
					Catalog\PriceTable::getEntity(),
					['=this.ID' => 'ref.PRODUCT_ID']
				),
                new Main\Entity\ReferenceField(
                    'RATIO',
                    \Bitrix\Catalog\MeasureRatioTable::getEntity(),
                    ['=this.ID' => 'ref.PRODUCT_ID']
                ),
                new Main\Entity\ReferenceField(
                    'MEASURE',
                    MeasureTable::getEntity(),
                    ['=this.CATALOG.MEASURE' => 'ref.ID']
                )
			]
		]);
		$productPrices = $ids = $measures = [];
		while ($rs = $oList->fetch()){

		    $ms = [];

            $ms['MEASURE_NAME'] = $rs['MEASURE_NAME'] ? : $defaultMeasure['MEASURE_TITLE'];
            $ms['MEASURE_SHORT_NAME'] = $rs['MEASURE_SHORT_NAME'] ? : $defaultMeasure['SYMBOL_RUS'];
            $ms['MEASURE_RATIO'] = $rs['MEASURE_RATIO'] ? : $defaultRatio;

            $measures[$rs['CML2_LINK']] = $ms;
			$productPrices[$rs['CML2_LINK']] = $rs;
			$ids[] = $rs['CML2_LINK'];
		}
		$result = null;
		if(count($productPrices) > 0){
			$oProduct = Iblock\Element::getList([
				'select' => [
				    'ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'XML_ID', 'CODE'
                ],
				'filter' => [
					'IBLOCK_ID' => $catalog['PRODUCT_IBLOCK_ID'], '=ID' => $ids
				]
			]);
			while ($item = $oProduct->fetch()){
				if((int)$item['DETAIL_PICTURE'] > 0){
					$item['PICTURE'] = \CFile::ResizeImageGet(
						$item['DETAIL_PICTURE'],
						['width' => 120, 'height' => 120],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
				}
				$item['PRICE'] = $productPrices[$item['ID']]['PRICE_VAL'];
				$item['PRICE_FORMAT'] = self::formatPrice($item['PRICE']);

				$measure = $measures[$item['ID']];
                $item['MEASURE_NAME'] = $measure['MEASURE_NAME'];
                $item['MEASURE_SHORT_NAME'] = $measure['MEASURE_SHORT_NAME'];
                $item['MEASURE_RATIO'] = $measure['MEASURE_RATIO'];


				$result[$item['ID']] = $item;
			}
		}

		return [
			'items' => $result,
			'iblockId' => $arProduct['IBLOCK_ID'],
			'section' => $arProduct['IBLOCK_SECTION_ID'],
			'skuIblock' => $catalog['IBLOCK_ID'],
			'shop' => $catalog,
			'shopId' => $data['shop']
		];
	}

	/**
	 * @method searchReplace
	 * @return null
	 */
	public function searchReplace()
	{
		$data = $this->getPostData();

		$query = htmlspecialcharsEx($data['q']);

		$result = [];
		if(strlen($data['q']) > 3){

			$oProducts = Iblock\Element::getList([
				'select' => ['CML2_LINK' => 'PROPERTY.CML2_LINK', 'PRICE_VAL' => 'PRICE.PRICE'],
				'filter' => [
					'=ACTIVE' => 'Y',
					'IBLOCK_ID' => $data['skuIblock'],
					'PROPERTY.CML2_LINK_BIND.IBLOCK_SECTION_ID' => $data['section'],
					'%PROPERTY.CML2_LINK_BIND.NAME' => $query,
					'=PROPERTY.SHOP_ID' => $data['shop']
				],
				'limit' => 26,
				'group' => ['PROPERTY.CML2_LINK'],
				'runtime' => [
					new Main\Entity\ReferenceField(
						'CATALOG',
						Catalog\ProductTable::getEntity(),
						['=this.ID' => 'ref.ID']
					),
					new Main\Entity\ReferenceField(
						'PRICE',
						Catalog\PriceTable::getEntity(),
						['=this.ID' => 'ref.PRODUCT_ID']
					)
				]
			]);
			$productPrices = $ids = [];
			while ($item = $oProducts->fetch()){
				$productPrices[$item['CML2_LINK']] = $item;
				$ids[] = $item['CML2_LINK'];
			}

			if(count($productPrices) > 0){
				$oProduct = Iblock\Element::getList([
					'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'XML_ID', 'CODE'],
					'filter' => [
						'IBLOCK_ID' => $data['iblockId'], '=ID' => $ids
					]
				]);
				while ($item = $oProduct->fetch()){
					if((int)$item['DETAIL_PICTURE'] > 0){
						$item['PICTURE'] = \CFile::ResizeImageGet(
							$item['DETAIL_PICTURE'],
							['width' => 120, 'height' => 120],
							BX_RESIZE_IMAGE_PROPORTIONAL_ALT
						);
					}
					$item['PRICE'] = $productPrices[$item['ID']]['PRICE_VAL'];
					$item['PRICE_FORMAT'] = self::formatPrice($item['PRICE']);

					$measure = ProductMeasure::getMeasureByProductId($data['skuIblock'], $productPrices[$item['ID']]['ID']);

					$item['MEASURE_NAME'] = $measure->getName();
					$item['MEASURE_SHORT_NAME'] = $measure->getShortName();
					$item['MEASURE_RATIO'] = $measure->getRatio();

					$result[$item['ID']] = $item;
				}
			}

		}

		return $result;
	}

	/**
	 * @method saveReplace
	 * @return bool
	 * @throws \Exception
	 */
	public function saveReplace()
	{
		$data = $this->getPostData();

		$data['place']['ACTIVE'] = 'N';

		$res = BasketShopTable::update($data['id'], ['REPLACE' => $data['place']]);

		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return $data['place'];
	}

	public function delReplace()
	{
		$data = $this->getPostData();
		$res = BasketShopTable::update($data['id'], ['REPLACE' => false]);
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return true;
	}

	public function getProductBySku($skuId)
	{
		$iblock = Iblock\Element::getIblockByElement($skuId);
		$catalogData = $this->getCatalogData($iblock);

		$row = Iblock\Element::getRow([
			'select' => [
				'ID', 'NAME', 'IBLOCK_ID', 'CODE',
				'SHOP_ID' => 'PROPERTY.SHOP_ID',
				'SHOP_CODE' => 'PROPERTY.SHOP_ID_BIND.CODE',
				'PRODUCT_NAME' => 'PROPERTY.CML2_LINK_BIND.NAME',
//				'BARCODE' => 'PROPERTY.CML2_LINK_BIND.XML_ID',
				'PICTURE' => 'PROPERTY.CML2_LINK_BIND.DETAIL_PICTURE',
				'PRICE_VAL' => 'PRICE.PRICE',
				'QUANTITY' => 'CATALOG.QUANTITY',
				'PRODUCT_ID' => 'PROPERTY.CML2_LINK',
			],
			'filter' => [
				'IBLOCK_ID' => $catalogData['IBLOCK_ID'],
				'=ID' => $skuId,
			],
			'runtime' => [
				new Main\Entity\ReferenceField(
					'PRICE',
					Catalog\PriceTable::getEntity(),
					['=this.ID' => 'ref.PRODUCT_ID']
				),
				new Main\Entity\ReferenceField(
					'CATALOG',
					Sale\Internals\ProductTable::getEntity(),
					['=this.ID' => 'ref.ID']
				),
			],
		]);

		$row['CATALOG_DATA'] = $catalogData;

		return $row;
	}

	private function getCatalogData($iblock_id)
	{
		if (!$this->cacheCatalogData->offsetExists($iblock_id)){
			$this->cacheCatalogData->offsetSet($iblock_id, \CCatalogSku::GetInfoByIBlock($iblock_id));
		}

		return $this->cacheCatalogData->get($iblock_id);
	}

	private function getCacheCatalogSku($skuId)
	{
		if (!$this->cacheCatalogSku->offsetExists($skuId)){
			$this->cacheCatalogSku->offsetSet($skuId, $this->getProductBySku($skuId));
		}

		return $this->cacheCatalogSku->get($skuId);
	}

	/**
	 * @method rotationDays
	 * @param array $days
	 *
	 * @return array
	 */
	//public function rotationDays(array $days = []): array
    public function rotationDays(array $days = [])
	{
		$result = [];
		$Dates = new Dates($days);
		$result = $Dates->makeCalendar()->toArray();

		return $result;
	}

	/**
	 * @method addToFavorite
	 * @return array|int
	 * @throws \Exception
	 */
	public function addToFavorite()
	{
		$productId = (int)$this->getPostData()['ID'];

		$skuId = (int)$this->getPostData()['SKU'];
		$shopId = null;
		if($skuId > 0){
			$iblockSku = Iblock\Element::getIblockByElement($skuId);
			$shop = \CIBlockElement::GetProperty($iblockSku, $skuId, [], ['CODE' => 'SHOP_ID'])->Fetch();
			$shopId = (int)$shop['VALUE'];
		}

		if(!$this->getUser()->IsAuthorized()){
			throw new \Exception('Только авторизованные пользователи могут добавлять товары в избранное', 403);
		}

		$row = FavoriteTable::getRow([
			'filter' => ['=ELEMENT_ID' => $productId, '=SHOP_ID' => $shopId],
		]);
		$save = [
			'ELEMENT_ID' => $productId,
			'USER_ID' => $this->getUser()->GetID(),
			'SHOP_ID' => $shopId
		];
		if(is_null($row)){
			$result = FavoriteTable::add($save);
		} else{
			$result = FavoriteTable::update($row['ID'], $save);
		}
		if(!$result->isSuccess()){
			throw new \Exception(implode(', ', $result->getErrorMessages()), 500);
		}
		return $result->getId();
	}

	/**
	 * @method delFavorite
	 * @return bool
	 * @throws \Exception
	 */
	public function delFavorite()
	{
		$productId = (int)$this->getPostData()['ID'];
		if(!$this->getUser()->IsAuthorized()){
			throw new \Exception('Только авторизованные пользователи могут добавлять товары в избранное', 403);
		}
		$row = FavoriteTable::getRow([
			'filter' => ['=ELEMENT_ID' => $productId, 'USER_ID' => $this->getUser()->GetID()],
		]);

		$res =FavoriteTable::delete($row['ID']);
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return true;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}

	/**
	 * @method getPostData - get param postData
	 * @return mixed
	 */
	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * @method setPostData - set param PostData
	 * @param mixed $postData
	 */
	public function setPostData($postData)
	{
		$this->postData = $postData;
	}
}

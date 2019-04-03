<?php namespace Mig\Order;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\DaData;
use AB\Tools\Debug;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use UL\Main\Basket\Model\BasketShopTable;
use UL\Main\Map\Model\CordTable;
use UL\Main\Personal\Address;
use UL\Suggestions;
use Mig\BasketComponent;
use Bitrix\Sale;
use UL\Main\Personal\OrderNumberTable;

Loc::loadLanguageFile(__FILE__);

Main\Loader::includeModule('sale');
Main\Loader::includeModule('iblock');

class CreatorComponent extends \CBitrixComponent
{
	protected $postData;

	protected $dbg = false;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		$this->setSiteId(SITE_ID);
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

	public function coords()
	{
		$arCords = [];
		$arCords = CordTable::getList()->fetchAll();

		foreach ($arCords as &$cord) {
			$obShops = \UL\Main\Map\Model\MultiShopTable::getList([
				'filter' => ['ID' => $cord['SHOP_ID']],
			]);
			while ($s = $obShops->fetch()) {
				if(in_array($s['VALUE'], $_SESSION['REGIONS']['SHOP_ID'])){
					$cord['SHOP_VALUES'][] = $s['VALUE'];
				}
			}
		}

		foreach ($arCords as $k => $cordItem) {
			if(!$cordItem['SHOP_VALUES'])
				unset($arCords[$k]);
		}


		if (count($arCords) > 0){
			$result['DATA'] = $arCords;
		} else {
			$result['DATA'] = null;
		}

		$result['CURRENT_SHOP'] = $_SESSION['REGIONS'];

		return $result;
	}

	public function getAddressList()
	{
		return Address::getProfilesByUser();
	}

	public function searchCity()
	{
		$data = $this->getPostData();
		$q = $data['q'];
		\CUtil::decodeURIComponent($q);

		$post = [
			'query' => $q,
			'from_bound' => ['value' => 'city'],
			'to_bound' => ['value' => 'city'],
			'restrict_value' => true,
			'count' => 5,
			'locations' => [
				'city_type_full' => 'город',
//				'fias_level' => 4
			],
		];
		$suggest = new Suggestions();
		$list = $suggest->loadData($post);
		$result = null;
		foreach ($list as $item){
			$val = str_replace(['г ', 'гор ','г.','гор.'],'', $item['value']);
			$result[] = [
				'value' => trim($val),
				'unrestricted_value' => $item['unrestricted_value'],
			];
		}

		return $result;
	}

	public function searchStreet()
	{
		$data = $this->getPostData();
		$q = $data['q'];

		\CUtil::decodeURIComponent($q);


		$post = [
			'query' => $q,
			'from_bound' => ['value' => 'street'],
			'to_bound' => ['value' => 'street'],
			'restrict_value' => true,
			'locations' => [
				['city' => $data['city']],
			],

		];

		$suggest = new Suggestions();
		$list = $suggest->loadData($post);
		$result = null;

		foreach ($list as $item){
			$val = str_replace('ул','', $item['value']);

			$result[] = [
				'value' => trim($val),
				'unrestricted_value' => $item['unrestricted_value'],
			];
		}

		return $result;
	}

	/**
	 * @method saveProfile
	 * @return array
	 * @throws \Exception
	 */
	public function saveProfile()
	{
		$data = $this->getPostData();
		if($this->dbg === true && $data['CITY']){
			file_put_contents(dirname(__FILE__).'/profile.json', Main\Web\Json::encode($data));
		} elseif($this->dbg && !$data['CITY']){
			$data = Main\Web\Json::decode(file_get_contents(dirname(__FILE__).'/profile.json'));
		}

		$data['PROFILE_NAME'] = $data['NAME'];
		unset($data['NAME'], $data['method'], $data['sessid'], $data['files']);

		$address = new Address();
		$save = $address->saveAddressAction($data);
		if($save > 0){
			$item = Address::getProfilesByUser($save);
			return array_shift($item);
		} else {
			throw new \Exception('Ошибка при сохранении адреса', 500);
		}
	}

	public function getBasket()
	{
		\CBitrixComponent::includeComponentClass('mig:basket');
		$basket = new BasketComponent();

		$data = $basket->getBasketData();
		$result = null;
		$totalSum = 0;
		$arUser = Main\UserTable::getRow([
			'select' => ['UF_*'],
			'filter' => ['=ID' => $this->getUser()->GetID()]
		]);
		foreach ($data['items'] as $shopCode => &$shop) {
			if($shop['CLOSED'] === BasketComponent::SHOP_CLOSE_FULL){
				continue;
			}

			if($arUser['UF_FREE_DELIVERY'] == 1){
				$shop['CURRENT']['CURRENT']['PRICE'] = 0;
				foreach ($shop['CALENDAR'] as &$calendar) {
					foreach ($calendar['TIMES'] as &$TIME) {
						$TIME['PRICE'] = 0;
					}
				}
			}
//			dump($shop);
			$sum = 0;
			foreach ($shop['BASKET'] as &$itemBasket){
				$itemBasket['SUMMARY'] = $itemBasket['PRICE'] * $itemBasket['QUANTITY'];
				$sum += $itemBasket['SUMMARY'];
			}

			$shop['SUM'] = $sum;
			$shop['SUM_FORMAT'] = BasketComponent::formatPrice($sum);
			$totalSum += $shop['SUM'];

//			$shop['CALENDAR'] = $basket->rotationDays($shop['DELIVERY_TIMES']);

			$result['items'][$shopCode] = $shop;
		}

//		dd($data);
		$result['total'] = $data['total'];
		$result['sum'] = $totalSum;
		$result['sumFormat'] = BasketComponent::formatPrice($totalSum);

		return $result;
	}

	public function makeOrder()
	{
		$data = $this->getPostData();
		if($this->dbg){
			if(!empty($data['order'])){
				file_put_contents(dirname(__FILE__). '/order.json', Main\Web\Json::encode($data['order']));
			} else {
				$data['order'] = Main\Web\Json::decode(file_get_contents(dirname(__FILE__). '/order.json'));
			}
		}

		$basketData = $this->getBasket();

		$CEvent = new \CEvent();

		foreach ($basketData['items'] as $codeShop => $item) {
			$dataOrder = [
				'delivery' => $data['order']['delivery'][$codeShop],
				'address' => $data['order']['address'],
				'promo' => $data['order']['final']['promo'],
				'comment' => $data['order']['final']['comment'],
			];
			$order = $this->saveOrderShop($item, $dataOrder);

			$orderNum[] = $order->getId();

			$CEvent->SendImmediate(
				'SALE_NEW_ORDER',
				$this->getSiteId(), [
					'EMAIL' => $this->getUser()->GetEmail(),
					'ORDER_ID' => $order->getId(),
					'PRICE' => $order->getPrice(),
					'ORDER_DATE' => date('d.m.Y H:i:s'),
				]
			);

			$OrderNumberSave[] = OrderNumberTable::add([
				'ORDER_ID' => $order->getId(),
				'SHOP_ID' => $item['SHOP_ID'],
				'USER_ID' => self::getUser()->GetID(),
			]);

		}

		unset($_SESSION['UL_BASKET_ITEMS']);
		$CUser = new \CUser();
		$CUser->Update($this->getUser()->GetID(), ['UF_FREE_DELIVERY' => 0]);
		$orderNum = implode('/', $orderNum);

		foreach ($OrderNumberSave as $item) {
			OrderNumberTable::update($item->getId(), [
				'ACCOUNT_NUMBER' => $orderNum,
			]);
		}
		$CEvent->SendImmediate(
			'SALE_NEW_ORDER_ADMIN',
			$this->getSiteId(), [
				'ORDER_ID' => $orderNum,
			]
		);

		return $orderNum;
	}

	/**
	 * @method saveOrderShop
	 * @param $shop
	 * @param $dataOrder
	 *
	 * @return Sale\Order
	 * @throws \Exception
	 */
	protected function saveOrderShop($shop, $dataOrder)
	{
		$basket = Sale\Basket::create($this->getSiteId());

		foreach ($shop['BASKET'] as $item) {
			$itemSave = $basket->createItem('catalog', $item['PRODUCT_ID']);
			$itemSave->setFields([
				'QUANTITY' => $item['QUANTITY'],
				'PRICE' => $item['PRICE'],
				'BASE_PRICE' => $item['PRICE'],
				'CURRENCY' => 'RUB',
				'NAME' => $item['NAME'],
				'PRODUCT_XML_ID' => $item['IMG'].'|'.$item['PRODUCT_ID'],
				'WEIGHT' => $item['WEIGHT'],
				'NOTES' => $item['SHOP_CODE'],
			]);
		}

		$order = Sale\Order::create($this->getSiteId(), $this->getUser()->GetID());
		$order->setPersonTypeId(1);
		$basket->save();


		$order->setBasket($basket);


//		if($order->getPrice() >= 2000){
//			$deliveryBXId = 24;
//		} else {
//			$deliveryBXId = 17;
//		}
		$deliveryBXId = 17;
		$arUser = Main\UserTable::getRow([
			'select' => ['UF_*'],
			'filter' => ['=ID' => $this->getUser()->GetID()]
		]);
		if($arUser['UF_FREE_DELIVERY'] == 1){
			$deliveryBXId = 24;
		}
//		PR($order->getPrice());
//		dd('ss');

		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem(
			Sale\Delivery\Services\Manager::getObjectById($deliveryBXId)
		);
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		/** @var Sale\BasketItem $basketItem */
		foreach ($basket as $basketItem) {
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}

		$paymentCollection = $order->getPaymentCollection();
		$payment = $paymentCollection->createItem(Sale\PaySystem\Manager::getObjectById(1));
		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());


		$shopAddress = \Bitrix\Iblock\ElementTable::getRow([
			'select' => ['PREVIEW_TEXT'],
			'filter' => ['IBLOCK_ID' => 5, '=ID' => $shop['SHOP_ID']],
		]);

		$arProps = [];
		$propertyCollection = $order->getPropertyCollection();
		/** @var Sale\PropertyValue $propItem */
		foreach ($propertyCollection as $propItem) {
			if (isset($dataOrder['address'][$propItem->getField('CODE')])){
				$value = $dataOrder['address'][$propItem->getField('CODE')];
				$propItem->setValue($value);
			}
			if($propItem->getField('CODE') == 'SHOP_CODE'){
				$propItem->setValue($dataOrder['delivery']['shopCode']);
			}
			if($propItem->getField('CODE') == 'DELIVERY_TIME'){
				$date = date('d.m.Y', $dataOrder['delivery']['timestamp']);
				$propItem->setValue($date.', '.$dataOrder['delivery']['timeFrom'].' - '.$dataOrder['delivery']['timeTo']);
			}
			if($propItem->getField('CODE') == 'EMAIL'){
				$propItem->setValue($this->getUser()->GetEmail());
			}
			if($propItem->getField('CODE') == 'FIO'){
				$propItem->setValue($this->getUser()->GetFormattedName());
			}
			if($propItem->getField('CODE') == 'SHOP_ID'){
				$propItem->setValue($shop['SHOP_ID']);
			}
			if($propItem->getField('CODE') == 'SHOP_ADDRESS'){
				$propItem->setValue($shopAddress['PREVIEW_TEXT']);
			}
		}

		$order->setField('USER_DESCRIPTION', $dataOrder['comment']);

		$save = $order->save();
		if(!$save->isSuccess()){
			throw new \Exception(implode(', ', $save->getErrorMessages()), 500);
		}

		/** @var Sale\BasketItem $basketItem */
		foreach ($order->getBasket() as $basketItem) {

			$rowBasketCustom = BasketShopTable::getRow([
				'filter' => [
					'=SHOP_ID' => $shop['SHOP_ID'],
					'=PRODUCT_ID' => $basketItem->getProductId(),
					'=FUSER_ID' => Sale\Fuser::getId(),
					'BASKET_ID' => false
				]
			]);

			if(!is_null($rowBasketCustom)){
				BasketShopTable::update($rowBasketCustom['ID'], ['BASKET_ID' => $basketItem->getField('ID')]);
			}
		}


		$oItems = BasketShopTable::getList([
			'select' => ['ID'],
			'filter' => [
//				'!=SHOP_ID' => $shop['SHOP_ID'],
				'=FUSER_ID' => Sale\Fuser::getId(),
				'BASKET_ID' => false
			],
		]);
		while ($rs = $oItems->fetch()) {
//			BasketShopTable::delete($rs['ID']);
		}

		return $order;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->arResult['USER_ID'] = $this->getUser()->GetID();

		$obItems = BasketShopTable::query()
			->setSelect(['ID', 'PRICE', 'SUM'])
			->where('BASKET_ID', null)
			->where('FUSER_ID', Sale\Fuser::getId())
			->exec();

		$sum = 0;
		while ($rs = $obItems->fetch()){
			$sum += $rs['SUM'];
		}
		if($sum < 1000){
			$this->includeComponentTemplate('error');
		} else {
			$this->includeComponentTemplate();
		}
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

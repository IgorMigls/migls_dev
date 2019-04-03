<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 16.08.2016
 * Time: 16:26
 */

namespace UL\Main;

use AB\Iblock\Element;
use Bitrix\Sale;
use Bitrix\Main;
use PW\Tools\Debug;
use UL\Main\Personal\Address;
use UL\Main\Personal\OrderNumberTable;
use UL\Tools;

Main\Loader::includeModule('ab.iblock');

class Order
{

	private static $siteId;
	private static $shopIds = [];
	private $arShopLocation = [];
	private $arDeliveryLoc = [];
	private $arProps = [];

	/** @var  Sale\Order */
	private $order;

	/**
	 * Order constructor.
	 */
	public function __construct()
	{
		self::$siteId = Main\Context::getCurrent()->getSite();
	}

	public function saveOrderAction($data = [])
	{
		$arProp = $data['PROPERTIES'];

		$this->arProps = $data;

		if (!self::getUser()->IsAuthorized()){
			$email = $arProp['EMAIL']['VALUE'];

			if (!check_email($email)){
				throw new \Exception('Введите e-mail правильно');
			}
			$arUser = static::getUserByMail($email);
			if (is_null($arUser) || !$arUser){
				$pass = randString(10);
				$arUserFields = [
					'EMAIL' => $email,
					'LOGIN' => $email,
					'PASSWORD' => $pass,
					'CONFIRM_PASSWORD' => $pass,
					'GROUP_ID' => [2, 5],
				];

				$arNames = explode(' ', $arProp['FIO']['VALUE']);
				if (count($arNames) == 3){
					$arUserFields['NAME'] = array_shift($arNames);
					$arUserFields['SECOND_NAME'] = array_shift($arNames);
					$arUserFields['LAST_NAME'] = array_shift($arNames);
				} else {
					$arUserFields['NAME'] = array_shift($arNames);
					$arUserFields['LAST_NAME'] = array_shift($arNames);
				}

				$arUserFields['PERSONAL_MOBILE'] = $arProp['PHONE']['VALUE'];

				$CUser = new \CUser();
				$userId = $CUser->Add($arUserFields);
				if (intval($userId) > 0){
					self::getUser()->Authorize($userId);
				} else {
					throw new \Exception(strip_tags($CUser->LAST_ERROR));
				}
			} else {
				self::getUser()->Authorize($arUser['ID']);
			}
		} else {
			if (empty($this->arProps['PROPERTIES']['FIO']['VALUE'])){
				$this->arProps['PROPERTIES']['FIO']['VALUE'] = implode(' ', array(
					self::getUser()->GetFirstName(),
					self::getUser()->GetLastName(),
				));
			}
			if (empty($this->arProps['PROPERTIES']['EMAIL']['VALUE'])){
				$this->arProps['PROPERTIES']['EMAIL']['VALUE'] = self::getUser()->GetEmail();
			}
		}


		Sale\DiscountCouponsManager::init();

		$order = Sale\Order::create(self::$siteId, self::getUser()->GetID(), 'RUB');
		$order->setPersonTypeId(1);

		\UL\Sale\Basket::getBasketUser(['del_order' => 'Y']);

		$basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId);
		$order->setBasket($basket);

		$this->order = $order;

		$this->afterSetBasket($data['DELIVERY_RAW']);

		$paymentCollection = $this->order->getPaymentCollection();
		$paymentCollection->createItem(Sale\PaySystem\Manager::getObjectById(1));

		$this->order->doFinalAction(true);

//		PR($this->arProps['PROPERTIES']);

		$propertyCollection = $this->order->getPropertyCollection();
		/** @var Sale\PropertyValue $propertyItem */
		foreach ($propertyCollection as $propertyItem) {
			if (isset($this->arProps['PROPERTIES'][$propertyItem->getField('CODE')])){
				$value = $this->arProps['PROPERTIES'][$propertyItem->getField('CODE')]['VALUE'];
				$propertyItem->setValue($value);
			}

			if ($propertyItem->getField('CODE') == 'LOCATION'){
				$locationVal = [];
				foreach ($this->getArShopLocation() as $valueLoc) {
					$locationVal[] = $valueLoc['CODE'];
				}
				$propertyItem->setValue($locationVal);
			}
		}

//		PR($propertyCollection->getArray());

		$this->order->setField('CURRENCY', 'RUB');
		$this->order->setField('COMMENTS', $this->arProps['COMMENT']);
		$this->order->setField('USER_DESCRIPTION', $this->arProps['COMMENT']);

//		PR($this->order->getPrice());

		$result = $this->order->save();
		if (!$result->isSuccess()){
			throw new \Exception(implode(', '.$result->getErrorMessages()));
		} else {
			$this->saveProfile();

			return $result->getId();
		}
	}

	public function afterSetBasket($arDelivery = [])
	{
		$shipmentCollection = $this->order->getShipmentCollection();

		$basket = $this->order->getBasket();
		/** @var Sale\BasketItem $basketItem */
		foreach ($basket->getBasketItems() as $basketItem) {
			$skuId = $basketItem->getField('PRODUCT_ID');
			$iblock = Element::getIblockByElementId($skuId);
			if($iblock == 0){
				continue;
			}
			$arElement = Element::getRow([
				'select' => ['SHOP_ID' => 'PROPERTY.SHOP_ID.ID', 'CITY' => 'PROPERTY.SHOP_ID.IBLOCK_SECTION.NAME' ,'SHOP_NAME' => 'PROPERTY.SHOP_ID.NAME'],
				'filter' => ['IBLOCK_ID' => $iblock, '=ID' => $skuId],
			]);
			$item = $basketItem->getPropertyCollection();
			$item->setBasketItem($basketItem);

//			PR($arElement);

			$item->setProperty(array(
				[
					'NAME' => 'Магазин',
					'VALUE' => $arElement['CITY'].' '.$arElement['SHOP_NAME'].'['.$arElement['SHOP_ID'].']',
					'CODE' => $arElement['SHOP_ID'],
				],
			));
			$item->save();

			self::$shopIds[$arElement['SHOP_ID']][] = $basketItem;
		}

		foreach (self::$shopIds as $shopId => $arBasket) {

//			$deliveryId = $this->getDeliveryLocation($shopId)['DELIVERY_ID'];
			$deliveryId = $arDelivery['ITEMS'][$shopId]['ID'];
			$deliveryObj = Sale\Delivery\Services\Manager::getObjectById($deliveryId);
			$shipment = $shipmentCollection->createItem($deliveryObj);

			foreach ($arBasket as $basketItem) {
				$shipmentItemCollection = $shipment->getShipmentItemCollection();
				/** @var Sale\ShipmentItem $item */
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}

			$shopCode = false;
			foreach ($this->arProps['DELIVERY']['shop'] as $code => $arShopTime) {
				if($arShopTime['ID'] == $shopId){
					$shopCode = $code;
					break;
				}
			}

			$dateStr = $this->arProps['DELIVERY']['shop'][$shopCode]['DAY']['NUM'];
			$dateStr .= ' '.$this->arProps['DELIVERY']['shop'][$shopCode]['DAY']['MONTH'];
			$timeStr = $this->arProps['DELIVERY']['shop'][$shopCode]['TIME'];

			$timeStr = str_replace(' ', '', $timeStr);
			$arTime = explode('-', $timeStr);

			if (count($arTime) > 1){
				$timeResStr = $arTime[0];
			} else {
				$timeResStr = $arTime[0].':00';
			}

			$date = implode('.', Tools::nominativeMonth($dateStr)).' '.$timeResStr;

			$shipment->setFields([
				'DELIVERY_ID' => $deliveryObj->getId(),
				'CURRENCY' => 'RUB',
				'DELIVERY_DOC_DATE' => new Main\Type\DateTime($date),
				'DEDUCTED' => 'Y',
				'CUSTOM_PRICE_DELIVERY' => 'N',
				'DELIVERY_NAME' => $deliveryObj->getName()
			]);

			$calcResult = $deliveryObj->calculate($shipment);

//			PR(new Main\Type\DateTime($date));
//			PR($shipment->getFieldValues());

			$this->addLocationToShop($shopId);
		}
	}

	public function saveProfile()
	{
		$arSave = [
			'PROFILE_NAME' => $this->arProps['PROPERTIES']['PROFILE_NAME'],
			'PROFILE_ID' => $this->arProps['PROPERTIES']['PROFILE_ID'],
		];

		foreach ($this->arProps['PROPERTIES'] as $code => $arProp) {
			if (!is_array($arProp) || !empty($arProp)){
				$arSave[$code] = $arProp;
			} elseif (is_array($arProp) && isset($arProp['VALUE'])) {
				$arSave[$code] = $arProp['VALUE'];
			}
		}
		$Address = new Address();
		$Address->saveAddressAction($arSave);
	}

	/**
	 * @method getUserByMail
	 * @param $email
	 *
	 * @return array|null
	 */
	public static function getUserByMail($email)
	{
		return Main\UserTable::getRow([
			'select' => ['ID', 'EMAIL', 'LOGIN', 'LAST_NAME', 'SECOND_NAME', 'NAME', 'PERSONAL_MOBILE'],
			'filter' => ['=EMAIL' => $email, 'ACTIVE' => 'Y'],
		]);
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public static function getUser()
	{
		global $USER;

		return $USER;
	}

	public function addLocationToShop($shopId)
	{
		if (!isset($this->arShopLocation[$shopId])){
			$this->arShopLocation[$shopId] = Sale\Location\LocationTable::getByCode($shopId)->fetch();
		}

		return $this;
	}

	public function getLocationByShop($shopId)
	{
		if (isset($this->arShopLocation[$shopId])){
			return $this->arShopLocation[$shopId];
		} else {
			$this->arShopLocation[$shopId] = Sale\Location\LocationTable::getByCode($shopId)->fetch();

			return $this->arShopLocation[$shopId];
		}
	}

	public function getDeliveryLocation($shopId)
	{
		if (!isset($this->arDeliveryLoc[$shopId])){
			$arDeliveries = Sale\Delivery\DeliveryLocationTable::getList([
				'select' => ['DELIVERY_ID'],
				'filter' => ['=LOCATION_CODE' => $shopId],
			])->fetchAll();

			if(is_array($arDeliveries)){
				foreach ($arDeliveries as $arDelivery) {
					$this->arDeliveryLoc[$shopId][] = $arDelivery['DELIVERY_ID'];
				}
			}
			return $this->arDeliveryLoc[$shopId];
		} else {
			return $this->arDeliveryLoc[$shopId];
		}
	}

	public function getDeliveryItemsAction($arShop = [])
	{
		$result = [];
		$sum = 0;

		foreach ($arShop as $item) {
			$price = 0;
			$arDeliveryId = $this->getDeliveryLocation($item);
			if(is_array($arDeliveryId)){
				foreach ($arDeliveryId as $id){
					$delivery = Sale\Delivery\Services\Manager::getObjectById($id);
					$conf = $delivery->getConfig();
					$rr = Sale\Delivery\Restrictions\Manager::getRestrictionsList($id);
					$deliveryId = $minPriceRestrict = 0;
					foreach ($rr as $rrr) {
						if($rrr['CLASS_NAME'] == '\Bitrix\Sale\Delivery\Restrictions\ByPrice'){
							$minPriceRestrict = $rrr['PARAMS']['MIN_PRICE'];
							break;
						}
					}

					if(intval($minPriceRestrict) > 0){
						if(\Bitrix\Sale\Delivery\Restrictions\ByPrice::check(
							$_SESSION['BASKET_SHOP'][$item]['SUM'],
							array('MIN_PRICE'=>$minPriceRestrict),
							$id
						)){
							$price = $conf['MAIN']['ITEMS'][0]['VALUE'];
							$deliveryName = $delivery->getName();
							$deliveryId = $id;
							break;
						}
					} else {
						 $price = $conf['MAIN']['ITEMS'][0]['VALUE'];
						 $deliveryName = $delivery->getName();
						 $deliveryId = $id;
						break;
					}
				}
			}
			$result['ITEMS'][$item] = [
				'NAME' => $deliveryName,
				'PRICE' => $price,
				'ID' => $deliveryId
			];

			$sum += $price;
		}

		$result['SUM'] = $sum;
		$result['SUM_FORMAT'] = Tools::formatPrice($sum);

		return $result;
	}

	/**
	 * @method getArShopLocation - get param arShopLocation
	 * @return array
	 */
	public function getArShopLocation()
	{
		return $this->arShopLocation;
	}

	public function getListOrdersAction()
	{
		$userId = self::getUser()->GetID();
		$siteId = Main\Context::getCurrent()->getSite();

		$obNumbers = OrderNumberTable::getList([
			'select' => [
				'ACCOUNT_NUMBER','ID', 'ORDER_ID',
				'ORDER_SUM' => 'ORDER.PRICE',
				'ORDER_DATE' => 'ORDER.DATE_INSERT_FORMAT'
			],
			'filter' => ['=USER_ID' => $userId],
			'order' => ['ID' => 'DESC']
		]);
		$orderIds = [];
		while ($rs = $obNumbers->fetch()){
			$orderIds[] = $rs;
		}
		$orderData = new Main\Type\Dictionary($orderIds);

//		PR($orderData);

		$arOrders = [];
		foreach ($orderData as $datum) {
//			$sum = $datum['ORDER_SUM'];
//			if($orderData->next()['ACCOUNT_NUMBER'] == $datum['ACCOUNT_NUMBER']){
//				$sum += $orderData->next()['ORDER_SUM'];
//				$datum['ORDER_SUM'] = $sum;
//				$arOrders[] = $datum;
//				continue;
//			} else {
//				$arOrders[] = $datum;
//			}


			$arOrders[] = $datum;
		}

//		PR($arOrders);

		foreach ($arOrders as &$order) {
			$order['PRICE_FORMAT'] = \SaleFormatCurrency($order['ORDER_SUM'], 'RUB', true);
//			$order['ADDRESS'] = self::getAddressForOrder($order['ID']);
			$order['ID'] = str_replace('/', '_', $order['ACCOUNT_NUMBER']);
		}

//		$arOrders = [];
//		$obOrder = Sale\Internals\OrderTable::getList([
//			'select' => ['ID','ACCOUNT_NUMBER','DATE_INSERT','PRICE','STATUS_ID','STATUS_'=>'STATUS'],
//			'filter' => ['=USER_ID'=>$userId,'=LID'=>$siteId],
//			'order' => ['ID'=>'DESC']
//		]);
//		while ($order = $obOrder->fetch()){
//			/** @var Main\Type\DateTime $dateInsert */
//			$dateInsert = $order['DATE_INSERT'];
//			$order['DATE_ORDER'] = $dateInsert->format('d').' '.FormatDate('F', $dateInsert->getTimestamp()).' '.$dateInsert->format('Y, H:i');
//			$order['PRICE_FORMAT'] = Tools::formatPrice($order['PRICE']);
//
//			$order['ADDRESS'] = self::getAddressForOrder($order['ID']);
//
//			$arOrders[] = $order;
//		}

		return $arOrders;
	}

	/**
	 * @method getAddressForOrder
	 * @param $orderId
	 *
	 * @return string
	 */
	public static function getAddressForOrder($orderId)
	{
		$addressStr = '';
		$arProp = Sale\Internals\OrderPropsValueTable::getList([
			'select' => ['NAME','VALUE','CODE'],
			'filter' => ['=ORDER_ID' => $orderId,'=PROPERTY.PROPS_GROUP_ID'=>2, 'PROPERTY.UTIL' => 'N']
		])->fetchAll();

		$address = [];
		$order['ADDRESS'] = '';
		foreach ($arProp as $item) {
			if(!empty($item['VALUE']))
				$address[] = $item['NAME'].' '.$item['VALUE'];
		}
		if(!empty($address))
			$addressStr = implode(', ', $address);

		return $addressStr;
	}

	public function cancelAction()
	{
		/** @var \Bitrix\Main\HttpRequest $request */
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();
		if(!check_bitrix_sessid()){
			throw new \Exception('Нет доступа', 403);
		}

		$arOrders = explode('|', $request->get('id'));

		foreach ($arOrders as $val) {
			$order = Sale\Order::load($val);
			if($order->getUserId() != self::getUser()->GetID()){
				throw new \Exception('У вас нет прав', 403);
			}
			$order->setField('STATUS_ID','N');
			$order->setField('CANCELED', 'Y');
			$order->save();
			unset($order);
		}

		return true;
	}
}
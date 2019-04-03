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

use AB\Tools\Debug;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Result;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Main\Web\Json;
use UL\Main\Personal\Address;
use AB\Iblock\Element as ABE;
use Bitrix\Sale as BXSale;
use UL\Main\Personal\OrderNumberTable;
use UL\Sale\Basket;

Loc::loadLanguageFile(__FILE__);

Loader::includeModule('ul.main');
Loader::includeModule('sale');
Loader::includeModule('catalog');
Loader::includeModule('iblock');
Loader::includeModule('ab.iblock');


//OrderNumberTable::createTable();

class OrderComponent extends \CBitrixComponent
{
	protected $CIBlockElement;
	protected $siteId = 's1';
	protected $arShopLocation;

	const DISCOUNT_DELIVERY_SUM = 2000;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		\CUtil::InitJSCore(['ajax']);

		$this->CIBlockElement = new \CIBlockElement();

		$this->siteId = Context::getCurrent()->getSite();
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

	public function getProfilesAction()
	{
		$Address = new Address();

		return $Address->getDataAction();
	}

	public function searchAddressAction($data = [])
	{

		$post = [
			'count' => 10,
			'query' => $data['value'],
		];
		switch ($data['name']) {
			case 'CITY':
				//{"count":10,"from_bound":{"value":"city"},"to_bound":{"value":"settlement"},"query":"мос"}:
				$post['from_bound'] = ['value' => 'city'];
				$post['to_bound'] = ['value' => 'settlement'];
				break;
			case 'STREET':
				//"count":10,"from_bound":{"value":"street"},"to_bound":{"value":"street"},"locations":[{"city":"Москва"}],"query":"fdnj"}:
				$post['from_bound'] = ['value' => 'street'];
				$post['to_bound'] = ['value' => 'street'];
				$post['locations'] = [
					array('city' => $data['addressItems']['CITY']),
				];
				break;
		}

		$Suggestions = new \UL\Suggestions();
		$items = $Suggestions->getAddress($post);
		$result = [];
		if ($data['addressItems']['CITY']){
			foreach ($items['suggestions'] as $k => &$value) {
				$val = '';
				//settlement_with_type
				if (strlen($value['data']['settlement_with_type']) > 0){
					$val = $value['data']['settlement_with_type'].' '.$value['data']['street_with_type'];

				} else {
					$val = $value['data']['street_with_type'];
				}

				$value['value'] = $val;
			}
		}


		return $items['suggestions'];
	}

	public function checkOrderCoordsAction($post = [])
	{
		return \UL\Main\Map\CoordManager::checkOrderCoordsAction($post);
	}

	public function basketLoadAction()
	{
		$dataBasket = \UL\Sale\Basket::getBasketUser();

		foreach ($dataBasket['ITEMS'] as $shopCode => $arShop) {
			if ($arShop['NO_SHOW_TIMES']){
				unset($dataBasket['ITEMS'][$shopCode]);
				continue;
			}
			if ($arShop['SUM_IN_SHOP'] > 1000){
				$dataBasket['ITEMS'][$shopCode]['IS_SMALL_SUM'] = true;
			}
		}

		$result = [
			'SHOPS' => $dataBasket['ITEMS'],
			'FORMAT_CNT' => $dataBasket['FORMAT_CNT'],
			'SUM_RAW' => $dataBasket['SUM_RAW'],
			'SUM' => $dataBasket['SUM'],
//			'DAYS_LIST' => $dataBasket['DAYS_LIST'],
		];
		unset($dataBasket);

		\CBitrixComponent::includeComponentClass('ul:shop.detail');
		foreach ($result['SHOPS'] as $codeShop => $shop) {

			$oDeliveries = BXSale\Delivery\DeliveryLocationTable::getList([
				'select' => ['*'],
				'filter' => ['=LOCATION_CODE' => $shop['SHOP_ID']],
			]);
			$deliveryCalcPrice = null;
			$arDeliveries = $shipments = [];
			while ($rDelivery = $oDeliveries->fetch()) {
				$deliveryManager = BXSale\Delivery\Services\Manager::getObjectById($rDelivery['DELIVERY_ID']);
				$dConfig = $deliveryManager->getConfig();
				$rDelivery['PRICE'] = $dConfig['MAIN']['ITEMS'][0]['VALUE'];
				$arDeliveries[$rDelivery['DELIVERY_ID']] = $rDelivery;
			}
			if ($shop['SUM_IN_SHOP'] >= self::DISCOUNT_DELIVERY_SUM){
				usort($arDeliveries, array($this, '_sortDelivery'));
			}

			$deliveryCalcPrice = $arDeliveries[0]['PRICE'];

			$timeSection = $this->CIBlockElement->GetList(
				array(),
				array('IBLOCK_ID' => 5, '=ID' => $shop['SHOP_ID']),
				false,
				array('nTopCount' => 1),
				array('ID', 'PROPERTY_DELIVERY_TIME')
			)->Fetch();
			if ((int)$timeSection['PROPERTY_DELIVERY_TIME_VALUE'] > 0){
				$shopComponent = new \UL\Shops\ShopDetail();
				$deliveryTimes = $shopComponent->getTimes($timeSection['PROPERTY_DELIVERY_TIME_VALUE']);

				foreach ($deliveryTimes as $k => $arTimes) {
					foreach ($arTimes['ITEMS'] as $l => $ITEM) {
						$priceCalc = $deliveryTimes[$k]['ITEMS'][$l]['PROPERTY_PRICE_VALUE'];
						$priceCalcFormat = $deliveryTimes[$k]['ITEMS'][$l]['PRICE_FORMAT'];

						if (!is_null($deliveryCalcPrice)){
							$priceCalc = $deliveryCalcPrice;
							$priceCalcFormat = \SaleFormatCurrency($deliveryCalcPrice, 'RUB', true);
						}

						if ($shop['FREE_DELIVERY'] || $priceCalc == 0){
							$priceCalc = 0;
							$priceCalcFormat = 'Бесплатно';
						}
						/*if((int)$shop['SUM_IN_SHOP'] >= 2000 && $priceCalc >= 300){
							$priceCalc = $priceCalc - 300;
							$priceCalcFormat = \SaleFormatCurrency($priceCalc, 'RUB', true);
							if($priceCalc <= 0){
								$priceCalc = 0;
								$priceCalcFormat = 'Бесплатно';
							}
						}*/
						$deliveryTimes[$k]['ITEMS'][$l]['PROPERTY_PRICE_VALUE'] = $priceCalc;
						$deliveryTimes[$k]['ITEMS'][$l]['PRICE_FORMAT'] = $priceCalcFormat;
					}
				}

				$result['DAYS_LIST'][$codeShop] = $deliveryTimes;
			}
		}

		return $result;
	}

	public function saveAddressAction($data = [])
	{
		if ($data['ADDRESS_SELECT']['id']){
			$data['PROFILE_ID'] = $data['ADDRESS_SELECT']['id'];
		}

		$Address = new Address();

		return $Address->saveAddressAction($data);
	}

	public function saveOrderAction($data = [])
	{
		/** @var BXSale\BasketItem $basketItem */
//		OrderNumberTable::createTable();
//		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/local/lg/set.json', Json::encode($data));
		if (!$this->getUser()->IsAuthorized()){
			throw new \Exception('Пройдите регистрацию, прежде чем заказывать товары');
		}

		BXSale\DiscountCouponsManager::init();

		$basketUser = \UL\Sale\Basket::getBasketUser(['del_order' => 'Y'])['ITEMS'];

		if(!empty($data)){
			file_put_contents(dirname(__FILE__).'/data.json', \Bitrix\Main\Web\Json::encode($data));
		} else {
			$data = \Bitrix\Main\Web\Json::decode(file_get_contents(dirname(__FILE__).'/data.json'));
		}

		$isTest = true;
		if($isTest)
			$data = \Bitrix\Main\Web\Json::decode(file_get_contents(dirname(__FILE__).'/data.json'));

		$basket = BXSale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $this->siteId);
		$basketProps = [];
		foreach ($basketUser as $shop => $arBasket){
			foreach ($arBasket['BASKET'] as $bId => $bb){
				$basketItem = $basket->getItemById($bId);
				$valuesProps = $basketItem->getPropertyCollection()->getPropertyValues();
				if(count($valuesProps) > 0){
					$basketProps[$shop][] = $valuesProps;
				}
			}
		}

		$CEvent = new \CEvent();
		foreach ($basket as $basketItem){
			$basketItem->delete();
		}
		$basket->save();
		$basket->clearCollection();

		$result = new Dictionary();

		$orderNums = [];

		foreach ($basketUser as $shopCode => $arBasketItem) {

			$order = BXSale\Order::create($this->siteId, $this->getUser()->GetID(), 'RUB');
			$order->setPersonTypeId(1);
			$order->setField('CURRENCY', 'RUB');

			$totalOrderSum = 0;
			$basket = BXSale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $this->siteId);

			foreach ($arBasketItem['BASKET'] as $id => $item) {
				$itemSave = $basket->createItem($item['MODULE'], $item['PRODUCT_ID']);
				$itemSave->setFields([
					'QUANTITY' => $item['QUANTITY'],
					'PRICE' => $item['PRICE'],
					'BASE_PRICE' => $item['BASE_PRICE'],
					'CURRENCY' => $item['CURRENCY'],
					'NAME' => $item['NAME'],
					'DETAIL_PAGE_URL' => $item['DETAIL_PAGE'],
					'PRODUCT_XML_ID' => $item['PRODUCT_XML_ID']
				]);

				if(count($item['replace']) > 0){
					$replaces = [];
					foreach ($item['replace'] as $replace){
						$replaces[] = [
							'NAME' => 'REPLACE',
							'CODE' => $replace['CODE'],
							'VALUE' => $replace['VALUE']
						];
					}
					$itemSave->getPropertyCollection()->setProperty($replaces);
				}
			}

				$basket->save();

			$order->setBasket($basket);

			$oDeliveries = BXSale\Delivery\DeliveryLocationTable::getList([
				'select' => ['*'],
				'filter' => ['=LOCATION_CODE' => $arBasketItem['SHOP_ID']],
			]);
			$arDeliveries = $managerObj = [];
			while ($rDelivery = $oDeliveries->fetch()) {
				$deliveryManager = BXSale\Delivery\Services\Manager::getObjectById($rDelivery['DELIVERY_ID']);
				$managerObj[$rDelivery['DELIVERY_ID']] = $deliveryManager;
				$dConfig = $deliveryManager->getConfig();
				$rDelivery['PRICE'] = $dConfig['MAIN']['ITEMS'][0]['VALUE'];
				$arDeliveries[$rDelivery['DELIVERY_ID']] = $rDelivery;

				unset($deliveryManager);
			}
			if ($totalOrderSum >= self::DISCOUNT_DELIVERY_SUM){
				usort($arDeliveries, array($this, '_sortDelivery'));
			}
			$deliveryId = array_shift($arDeliveries)['DELIVERY_ID'];
			$deliveryObj = $managerObj[$deliveryId];

			$shipmentCollection = $order->getShipmentCollection();
			$shipment = $shipmentCollection->createItem($deliveryObj);
			foreach ($basket as $basketItem) {
				$shipmentItemCollection = $shipment->getShipmentItemCollection();
				/** @var BXSale\ShipmentItem $item */
				$item = $shipmentItemCollection->createItem($basketItem);
				$item->setQuantity($basketItem->getQuantity());
			}

			$dateTimeStamp = $data['DELIVERY'][$shopCode]['timestamp'];
			$shipment->setFields([
				'DELIVERY_ID' => $deliveryObj->getId(),
				'CURRENCY' => 'RUB',
				'DELIVERY_DOC_DATE' => \Bitrix\Main\Type\DateTime::createFromTimestamp($dateTimeStamp),
				'DEDUCTED' => 'N',
				'CUSTOM_PRICE_DELIVERY' => 'N',
				'DELIVERY_NAME' => $deliveryObj->getName(),
			]);

			$deliveryObj->calculate($shipment);
			unset($deliveryObj, $managerObj);

			$this->addLocationToShop($arBasketItem['SHOP_ID']);

			$paymentCollection = $order->getPaymentCollection();
			$paymentCollection->createItem(BXSale\PaySystem\Manager::getObjectById(1));

			$order->doFinalAction(true);

			$propertyCollection = $order->getPropertyCollection();
			foreach ($propertyCollection as $propertyItem) {

				if (isset($data['PROPERTIES'][$propertyItem->getField('CODE')])){
					$value = $data['PROPERTIES'][$propertyItem->getField('CODE')];
					$propertyItem->setValue($value);
				}

				if ($propertyItem->getField('CODE') == 'LOCATION'){
					$locationVal = [];
					foreach ($this->arShopLocation as $valueLoc) {
						$locationVal[] = $valueLoc['CODE'];
					}
					$propertyItem->setValue($locationVal);
				}

				if($propertyItem->getField('CODE') == 'SHOP_CODE'){
					$propertyItem->setValue($arBasketItem['NAME']);
				}

				if($propertyItem->getField('CODE') == 'DELIVERY_TIME'){
					$valueDeliveryTime = $data['DELIVERY'][$shopCode]['name'].', '.$data['DELIVERY'][$shopCode]['from'].'-'.$data['DELIVERY'][$shopCode]['to'];
					$propertyItem->setValue($valueDeliveryTime);
				}

			}

//			dd($data);

			$order->setField('USER_DESCRIPTION', $data['comment']);

			$res = $order->save();

			if(!$res->isSuccess()){
				throw new \Exception(implode(', ', $res->getErrorMessages()));
			}

			if(!$isTest){
				$CEvent->SendImmediate(
					'SALE_NEW_ORDER',
					$this->getSiteId(), [
						'EMAIL' => $this->getUser()->GetEmail(),
						'ORDER_ID' => $res->getId(),
						'PRICE' => $order->getPrice(),
						'ORDER_DATE' => date('d.m.Y H:i:s'),
					]
				);
			}


			$result->offsetSet($shopCode, $res->getId());
			$orderNums[] = $res->getId();
			$OrderNumberSave[] = OrderNumberTable::add([
				'ORDER_ID' => $res->getId(),
				'SHOP_ID' => $arBasketItem['SHOP_ID'],
				'USER_ID' => self::getUser()->GetID(),
			]);
		}

		unset($_SESSION['UL_BASKET_ITEMS']);
		$CUser = new \CUser();
		$CUser->Update($this->getUser()->GetID(), ['UF_FREE_DELIVERY' => 0]);

		$orderNums = implode('/', $orderNums);

		/** @var Entity\AddResult $item */
		foreach ($OrderNumberSave as $item) {
			OrderNumberTable::update($item->getId(), [
				'ACCOUNT_NUMBER' => $orderNums
			]);
		}
		if(!$isTest){
			$CEvent->SendImmediate(
				'SALE_NEW_ORDER_ADMIN',
				$this->getSiteId(), [
					'ORDER_ID' => $orderNums,
				]
			);
		}

		return $orderNums;
	}

	private function _sortDelivery($a, $b)
	{
		if ($a['PRICE'] == $b['PRICE']){
			return 0;
		}

		return ($a['PRICE'] < $b['PRICE']) ? -1 : 1;
	}

	public function addLocationToShop($shopId)
	{
		if (!isset($this->arShopLocation[$shopId])){
			$this->arShopLocation[$shopId] = BXSale\Location\LocationTable::getByCode($shopId)->fetch();
		}

		return $this;
	}

	public function delAddressAction($data)
	{
		Address::getInstance()->deleteAction($data);

	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$basket = BXSale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $this->siteId);

		if (count($basket) == 0){
			$this->includeComponentTemplate('empty');
		} else {
			$this->includeComponentTemplate();
		}
	}
}
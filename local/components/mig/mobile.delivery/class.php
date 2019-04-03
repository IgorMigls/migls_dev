<?php namespace Mig\Mobile;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Helpers\DateFetchConverter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Sale;
use function implode;
use UL\Main\Basket\Model\BasketShopTable;
use Online1c\Iblock;
use Bitrix\Catalog;
use UL\Main\Delivery\ComplectationTable;
use Exception;
use UL\Main\Delivery\ProcessTable;
use UL\Main\HistoryOrderTable;
use Ul\Main\Measure\ProductMeasure;

Loc::loadLanguageFile(__FILE__);

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');
Main\Loader::includeModule('ul.main');

class DeliveryComponent extends \CBitrixComponent
{
	protected $postData;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
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
		return $arParams;
	}

	public function getNewOrders()
	{
		return $this->getOrders($this->request->toArray());
	}

	/**
	 * @method getOrders
	 * @param array $data
	 *
	 * @return array
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 * @throws Main\NotImplementedException
	 *
	 */
	protected function getOrders($data = [])
	{

//		ComplectationTable::createTable();

		$result = [];

		$filter = [];

		switch ($data['type']) {
			case 'delivery':
				$filter['=STATUS_ID'] = 'DD';
//				$filter['!PROCESS.ID'] = false;
//				$filter['=PROCESS.USER_ID'] = $this->getUser()->GetID();
				$filter['LOCKED_BY'] = false;
				break;
			case 'complect':
				$filter['=STATUS_ID'] = 'DA';
				$filter['!COMPLECTATION.ID'] = false;
				$filter['=COMPLECTATION.USER_ID'] = $this->getUser()->GetID();
				break;
			case 'myDelivery':
				$filter['LOCKED_BY'] = $this->getUser()->GetID();
				$filter['=STATUS_ID'] = 'DS';
				break;
			default:
				$filter['=STATUS_ID'] = 'N';
				break;
		}

		$filter['=CANCELED'] = 'N';

		$obOrder = Sale\Internals\OrderTable::getList([
			'select' => [
				'ID', 'ACCOUNT_NUMBER', 'DATE_INSERT', 'DATE_UPDATE', 'PERSON_TYPE_ID',
				'USER_ID', 'PAYED', 'STATUS_ID', 'PRICE',
				'USER_LOGIN' => 'USER.LOGIN',
				'USER_SHORT_NAME' => 'USER.SHORT_NAME',
				'USER_PHONE' => 'USER.PERSONAL_PHONE',
				'USER_EMAIL' => 'USER.EMAIL',
				'LOCKED_BY',
			],
			'filter' => $filter,
			'limit' => 20,
			'order' => ['ID' => "DESC"],
			'runtime' => [
				new Main\Entity\ReferenceField(
					'COMPLECTATION',
					ComplectationTable::getEntity(),
					['=this.ID' => 'ref.ORDER_ID']
				),
				new Main\Entity\ReferenceField(
					'PROCESS',
					ProcessTable::getEntity(),
					['=this.ID' => 'ref.ORDER_ID']
				),
			],
		]);
		while ($rs = $obOrder->fetch(new DateFetchConverter())) {
			$rs['PRICE_FORMAT'] = self::formatPrice($rs['PRICE']);

			$order = Sale\Order::load($rs['ID']);

			$resultItem = $order->getFieldValues();
			$propertyCollection = $order->getPropertyCollection();

			$arUser = Main\UserTable::getRow([
				'select' => ['SHORT_NAME', 'PERSONAL_PHONE', 'EMAIL'],
				'filter' => ['=ID' => $order->getUserId()],
			]);
			$badCodes = ['LOCATION'];
			/** @var Sale\PropertyValue $item */
			foreach ($propertyCollection as $item) {
				if (in_array($item->getField('CODE'), $badCodes)){
					continue;
				}
				$resultItem['PROPS'][$item->getField('CODE')] = $item->getFieldValues();

				if ($item->getField('CODE') === 'EMAIL' && strlen($item->getValue()) === 0){
					$resultItem['PROPS']['EMAIL']["VALUE"] = $arUser['EMAIL'];
				}
				if ($item->getField('CODE') === 'PHONE' && strlen($item->getValue()) === 0){
					$resultItem['PROPS']['PHONE']["VALUE"] = $arUser['PERSONAL_PHONE'];
				}
			}

			$rs['DATA'] = $resultItem;

			$result[] = $rs;
		}

		return $result;
	}

	private static function formatPrice($val)
	{
		return \SaleFormatCurrency($val, 'RUB', true);
	}

	/**
	 * @method getDetail
	 * @param null $id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getDetail($id = null)
	{
		if ((int)$id == 0){
			$id = (int)$this->getPostData()['id'];
		}
		if ($id == 0){
			throw new \Exception('Нет номера заказа', 406);
		}

		$order = Sale\Order::load($id);

		$result = $order->getFieldValues();
		$result['DATE_INSERT'] = $result['DATE_INSERT']->format('d.m.Y H:i:s');
		$result['PRICE_FORMAT'] = $this->formatPrice($result['PRICE']);

		$propertyCollection = $order->getPropertyCollection();

		$arUser = Main\UserTable::getRow([
			'select' => ['SHORT_NAME', 'PERSONAL_PHONE', 'EMAIL'],
			'filter' => ['=ID' => $order->getUserId()],
		]);
		$badCodes = ['LOCATION'];
		/** @var Sale\PropertyValue $item */
		foreach ($propertyCollection as $item) {
			if (in_array($item->getField('CODE'), $badCodes)){
				continue;
			}
			$result['PROPS'][$item->getField('CODE')] = $item->getFieldValues();

			if ($item->getField('CODE') === 'EMAIL' && strlen($item->getValue()) === 0){
				$result['PROPS']['EMAIL']["VALUE"] = $arUser['EMAIL'];
			}
			if ($item->getField('CODE') === 'PHONE' && strlen($item->getValue()) === 0){
				$result['PROPS']['PHONE']["VALUE"] = $arUser['PERSONAL_PHONE'];
			}
		}

		$basket = $order->getBasket();
		$basketBitrix = [];

		/** @var Sale\BasketItem $basketItem */
		foreach ($basket->getBasketItems() as $basketItem) {
			$basketFields = $basketItem->getFieldValues();
			$basketFields['SUM'] = $basketItem->getFinalPrice();
			$basketFields['SUM_FORMAT'] = self::formatPrice($basketFields['SUM']);
			$basketFields['PRICE_FORMAT'] = self::formatPrice($basketFields['PRICE']);
			$basketFields['QUANTITY'] = (float)$basketFields['QUANTITY'];

			$basketFields['WEIGHT'] = (int)$basketFields['WEIGHT'] > 0 ? (int)$basketFields['WEIGHT'] : 1000;

			$basketBitrix[$basketItem->getId()] = $basketFields;
		}

		$customBasket = BasketShopTable::getList([
			'select' => ['*'],
			'filter' => ['@BASKET_ID' => array_keys($basketBitrix)],
			'order' => ['QUANTITY' => 'DESC'],
		]);

		$result['REPLACES'] = $this->getHistory($id);

		while ($rs = $customBasket->fetch(new DateFetchConverter())) {
			$rs['BASKET_DATA'] = $basketBitrix[$rs['BASKET_ID']];

			$rs['IMG'] = \CFile::GetFileArray($rs['IMG']);
			$rs['RESIZE'] = \CFile::ResizeImageGet($rs['IMG']['ID'], ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

			if ($rs['REPLACE']){
				try {
					$rs['REPLACE'] = Main\Web\Json::decode($rs['REPLACE']);
				} catch (Main\ArgumentException $err) {
				}
			}

			$measure = ProductMeasure::getMeasureByProductId($rs['SKU_IBLOCK_ID'], $rs['SKU_ID']);
			$rs['MEASURE_NAME'] = $measure->getName();
			$rs['MEASURE_SHORT_NAME'] = $measure->getShortName();
			$rs['MEASURE_RATIO'] = (float)$measure->getRatio();
			$rs['REPLACE_FROM'] = null;

			$replaceFrom = array_filter($result['REPLACES'], function ($el) use ($rs) {
				return $el['ENTITY_ID'] == $rs['BASKET_DATA']['ID'] && $el['TYPE'] == 'BASKET_REPLACED_FROM';
			});
			$replaceFrom = array_shift($replaceFrom);

			$replaceFrom['DATA']['QUANTITY'] = round($replaceFrom['DATA']['QUANTITY'], 2);
			if (count($replaceFrom) > 0){
				$rs['REPLACE_FROM'] = $replaceFrom;
			}

			$result['BASKET'][$rs['BASKET_ID']] = $rs;
		}

		$result['TOTAL_PRICE_FORMAT'] = self::formatPrice($result['PRICE']);

		$rowComplectation = ComplectationTable::getRow([
			'filter' => ['=ORDER_ID' => $id],
		]);
		$result['COMPLECTATION'] = $rowComplectation;

		$result['REMOVED'] = array_filter($result['REPLACES'], function ($el) {
			return $el['TYPE'] == 'BASKET_REMOVED';
		});

		return $result;
	}

	/**
	 * @method getHistory
	 * @param null $orderId
	 *
	 * @return array|null
	 */
	public function getHistory($orderId = null)
	{
		$orderId = (int)$orderId;
		if ($orderId == 0){
			throw new Main\ArgumentNullException($orderId);
		}

		return HistoryOrderTable::getList([
			'filter' => [
				'ORDER_ID' => $orderId,
				'ENTITY' => 'BASKET',
				'TYPE' => ['BASKET_REPLACED_TO', 'BASKET_REPLACED_FROM', 'BASKET_REMOVED'],
			],
		])->fetchAll(new DateFetchConverter());
	}

	/**
	 * @method getProductData
	 * @param $xml_id
	 *
	 * @return array
	 */
	protected function getProductData($xml_id)
	{
		$tmp = explode('|', $xml_id);

		$product = [
			'IMG' => \CFile::GetFileArray($tmp[0]),
			'RESIZE' => \CFile::ResizeImageGet($tmp[0], ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL_ALT),
		];

		return $product;
	}

	/**
	 * @method lockedOrder
	 * @param null $id
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function lockOrder($id = null)
	{
		$id = (int)$id;
		if ($id == 0){
			$id = (int)$this->getPostData()['id'];
		}
		$order = Sale\Order::load($id);

		$productData = [];
		$detailOrder = $this->getDetail();
		$productData['products'] = $detailOrder['BASKET'];

		$saveLock = ComplectationTable::add([
			'USER_ID' => $this->getUser()->GetID(),
			'ORDER_ID' => $id,
			'PRODUCT_DATA' => $productData,
		]);

		if (!$saveLock->isSuccess()){
			throw new Exception(implode(', ', $saveLock->getErrorMessages()), 500);
		}

		$order->setField('STATUS_ID', 'DA');
		$order->save();

		return $id;
	}

	/**
	 * @method addToMyOrders
	 * @param null $id
	 *
	 * @return bool
	 */
	public function addToMyOrders($id = null)
	{
		$id = (int)$id;
		if ($id == 0){
			$id = (int)$this->getPostData()['id'];
		}
		$order = Sale\Order::load($id);
		$order->setField('STATUS_ID', 'DS');
		$order->save();

		return Sale\Order::lock($id)->isSuccess();
	}

	/**
	 * @method abortDelivery
	 * @param null $id
	 *
	 * @return bool
	 */
	public function abortDelivery($id = null)
	{
		$id = (int)$id;
		if ($id == 0){
			$id = (int)$this->getPostData()['id'];
		}
		$order = Sale\Order::load($id);
		$order->setField('STATUS_ID', 'DD');
		$order->save();

		return Sale\Order::unlock($id)->isSuccess();
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

	public function isAuth()
	{
		return $this->getUser()->GetID();
	}

	public function login()
	{
		$data = $this->getPostData();
		$auth = $this->getUser()->Login($data['email'], $data['pass']);

		return $this->isAuth();
	}

	/**
	 * @method searchReplaceItems
	 * @return array|null
	 */
	public function searchReplaceItems()
	{
		$data = $this->getPostData();

		$q = $data['q'];
		$skuId = (int)$data['sku'];

		$result = null;

		if (strlen($q) > 3){

			if ($skuId == 0){
				$custom = BasketShopTable::getRow([
					'filter' => ['!BASKET_ID' => false, 'PRODUCT_ID' => $data['product']],
				]);

				$skuId = $custom['SKU_ID'];
			}

			$skuIblock = Iblock\Element::getIblockByElement($skuId);
			$catalogData = \CCatalogSku::GetInfoByOfferIBlock($skuIblock);
			$skuItem = Iblock\Element::getRow([
				'select' => [
					'ID', 'IBLOCK_ID',
					'SHOP_ID' => 'PROPERTY.SHOP_ID',
					'PRODUCT_IB' => 'PROPERTY.CML2_LINK_BIND.IBLOCK_ID',
				],
				'filter' => ['IBLOCK_ID' => $skuIblock, '=ID' => $skuId],
			]);

//			\CCatalogSku::GetProductInfo()
			$filter = [
				'=ACTIVE' => 'Y',
				'IBLOCK_ID' => $skuIblock,
				'%PROPERTY.CML2_LINK_BIND.NAME' => $q,
				'=PROPERTY.SHOP_ID' => $skuItem['SHOP_ID'],
			];
			$product = Iblock\Element::getRow([
				'select' => ['IBLOCK_SECTION_ID'],
				'filter' => ['=ID' => $data['product'], $catalogData['PRODUCT_IBLOCK_ID']],
			]);
//			if ($product){
//				$filter['PROPERTY.CML2_LINK_BIND.IBLOCK_SECTION_ID'] = $product['IBLOCK_SECTION_ID'];
//			}

			$oProducts = Iblock\Element::getList([
				'select' => [
					'CML2_LINK' => 'PROPERTY.CML2_LINK',
					'PRICE_VAL' => 'PRICE.PRICE',
					'SKU_ID' => 'ID',
				],
				'filter' => $filter,
				'limit' => 10,
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
				],
			]);
			$productPrices = $ids = [];
			while ($item = $oProducts->fetch()) {
				$productPrices[$item['CML2_LINK']] = $item;
				$ids[] = $item['CML2_LINK'];
			}

			if (count($productPrices) > 0){
				$oProduct = Iblock\Element::getList([
					'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'XML_ID', 'CODE'],
					'filter' => [
						'IBLOCK_ID' => $catalogData['PRODUCT_IBLOCK_ID'], '=ID' => $ids,
					],
				]);
				while ($item = $oProduct->fetch()) {
					if ((int)$item['DETAIL_PICTURE'] > 0){
						$item['PICTURE'] = \CFile::ResizeImageGet(
							$item['DETAIL_PICTURE'],
							['width' => 120, 'height' => 120],
							BX_RESIZE_IMAGE_PROPORTIONAL_ALT
						);
					}

					$item['SKU_ID'] = $productPrices[$item['ID']]['SKU_ID'];
					$item['PRICE'] = $productPrices[$item['ID']]['PRICE_VAL'];
					$item['PRICE_FORMAT'] = self::formatPrice($item['PRICE']);

					$item['QUANTITY'] = $data['quantity'];

					$result[] = $item;
				}
			}

		}


		return $result;
	}

	/**
	 * @method cancelOrder
	 * @param null $id
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function cancelOrder($id = null)
	{
		$id = (int)$id;
		if ($id == 0){
			$id = (int)$this->getPostData()['id'];
		}

		if ($id == 0){
			throw new \Exception('Нет ид заказа', 406);
		}

		$order = Sale\Order::load($id);
		$order->setField('CANCELED', 'Y');
		$res = $order->save();
		if (!$res->isSuccess()){
			throw new \Exception(implode(',', $res->getErrorMessages()), 500);
		}

		return $res->isSuccess();
	}

	public function addReplaceToBasket()
	{
		$data = $this->getPostData();

		$bitrixBasketId = (int)$data['basketItem']['BASKET_DATA']['ID'];
		$customBasketId = (int)$data['basketItem']['ID'];

		$basketRow = Sale\Basket::getList([
			'filter' => ['=ID' => $bitrixBasketId],
			'select' => ['ID', 'ORDER_ID', 'NAME', 'QUANTITY', 'PRICE', 'PRODUCT_ID'],
		])->fetch();

		$order = Sale\Order::load($basketRow['ORDER_ID']);
		$basket = Sale\Basket::loadItemsForOrder($order);

		$replace = $data['replace'];
		$oldItem = $basket->getItemById($bitrixBasketId);
		$xmlId = explode('|', $oldItem->getField('PRODUCT_XML_ID'));
		$xmlId[0] = $replace['DETAIL_PICTURE'];
		$xmlId[1] = $replace['ID'];

		/*$oldItem->setFields([
			'NAME' => $replace['NAME'],
			'PRODUCT_ID' => $replace['ID'],
			'PRICE' => $replace['PRICE'],
			'PRODUCT_XML_ID' => implode('|', $xmlId),
		]);
		$saveOld = $oldItem->save();
		if (!$saveOld->isSuccess()){
			throw new \Exception(implode(',', $saveOld->getErrorMessages()), 500);
		}*/

		$saveBasketInternal = Sale\Internals\BasketTable::update($basketRow['ID'], [
			'NAME' => $replace['NAME'],
			'PRODUCT_ID' => $replace['ID'],
			'PRICE' => $replace['PRICE'],
			'BASE_PRICE' => $replace['PRICE'],
			'PRODUCT_XML_ID' => implode('|', $xmlId),
		]);

		$res = BasketShopTable::update($customBasketId, [
			'NAME' => $replace['NAME'],
			'PRODUCT_ID' => $replace['ID'],
			'PRICE' => $replace['PRICE'],
			'REPLACE' => false,
			'IMG' => $replace['DETAIL_PICTURE'],
		]);


		Sale\OrderHistory::addAction(
			'BASKET',
			$order->getId(),
			'BASKET_REPLACED_FROM',
			$basketRow['ID'],
			null,
			array(
				'NAME' => $basketRow['NAME'],
				'QUANTITY' => $basketRow['QUANTITY'],
				'PRODUCT_ID' => $basketRow['PRODUCT_ID'],
				'PRICE' => $basketRow['PRICE'],
			)
		);

		Sale\OrderHistory::addAction(
			'BASKET',
			$order->getId(),
			'BASKET_REPLACED_TO',
			$basketRow['ID'],
			null,
			array(
				'NAME' => $replace['NAME'],
				'QUANTITY' => $replace['QUANTITY'],
				'PRODUCT_ID' => $replace['ID'],
				'PRICE' => $replace['PRICE'],
			)
		);

		$basket->save();
		$order->save();

		if (!$res->isSuccess()){
			throw new \Exception(implode(',', $res->getErrorMessages()), 500);
		}

		$newBasket = $order->getBasket();
		$newBasketItem = $newBasket->getItemById($bitrixBasketId);

		$newCustom = BasketShopTable::getByPrimary($customBasketId)->fetch(new DateFetchConverter());

		$newBasketItem->setField('PRICE', $newCustom['PRICE']);
		$newBasketItem->setField('BASE_PRICE', $newCustom['PRICE']);
		$newBasketItem->save();
		$newBasket->save();
		$order->save();

		$basketFields = $newBasketItem->getFieldValues();
		$basketFields['SUM'] = $newBasketItem->getFinalPrice();
		$basketFields['SUM_FORMAT'] = self::formatPrice($basketFields['SUM']);
		$basketFields['PRICE_FORMAT'] = self::formatPrice($basketFields['PRICE']);
		$basketFields['QUANTITY'] = (float)$basketFields['QUANTITY'];
		$basketFields['WEIGHT'] = (int)$basketFields['WEIGHT'] > 0 ? (int)$basketFields['WEIGHT'] : 1000;

		$newCustom['BASKET_DATA'] = $basketFields;
		$newCustom['IMG'] = \CFile::GetFileArray($newCustom['IMG']);
		$newCustom['RESIZE'] = \CFile::ResizeImageGet($newCustom['IMG']['ID'], ['width' => 200, 'height' => 200], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);


		$Complectation = ComplectationTable::getByOrderId($order->getId());
		if(!is_null($Complectation)){
			$productData = $Complectation['PRODUCT_DATA'];

			foreach ($productData['products'] as $id => $product) {
				if($product['BASKET_ID'] == $bitrixBasketId){
					$productData['replaces'][$id] = $newCustom;
					unset($productData['products'][$id]);
				}
			}

			ComplectationTable::update($Complectation['ID'], ['PRODUCT_DATA' => $productData]);
		}

		$sumOrder = 0;
		/** @var Sale\BasketItem $basketItem */
		foreach ($newBasket->getBasketItems() as $basketItem) {
			$sumOrder += $basketItem->getFinalPrice();
		}

		$order->setField('PRICE', $sumOrder);
		$order->save();


		return [
			'orderId' => $order->getId(),
			'price' => $sumOrder,
			'priceFormat' => self::formatPrice($order->getPrice()),
			'basketItem' => $newCustom,
		];
	}

	/**
	 * @method deleteProduct
	 * @return bool
	 */
	public function deleteProduct()
	{
		$item = $this->getPostData()['item'];
		$order = Sale\Order::load($this->getPostData()['orderId']);

		$basket = $order->getBasket();

		$basketItem = $basket->getItemById((int)$item['BASKET_ID']);

		if (is_null($basketItem)){
			$this->deleteComplectation($item, $order->getId());

			return true;
		}

		$basketItem->delete();
		$basket->save();

		BasketShopTable::delete($item['ID']);

		if ($order->save()->isSuccess()){
			$this->deleteComplectation($item, $order->getId());
		}

		return true;
	}

	/**
	 * @method deleteComplectation
	 * @param $item
	 * @param $orderId
	 */
	private function deleteComplectation($item, $orderId)
	{
		$complectation = ComplectationTable::getRow([
			'filter' => ['=ORDER_ID' => $orderId],
		]);

		if (!is_null($complectation)){
			unset(
				$complectation['PRODUCT_DATA']['products'][$item['BASKET_ID']],
				$complectation['PRODUCT_DATA']['founded'][$item['BASKET_ID']],
				$complectation['PRODUCT_DATA']['replaces'][$item['BASKET_ID']]
			);

			$complectation['PRODUCT_DATA']['deleted'][$item['BASKET_ID']] = $item;
			ComplectationTable::update($complectation['ID'], ['PRODUCT_DATA' => $complectation['PRODUCT_DATA']]);
		}
	}

	/**
	 * @method sendForDelivery
	 * @param null $id
	 *
	 * @return int|null
	 * @throws Exception
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotImplementedException
	 */
	public function sendForDelivery($id = null)
	{
		$id = (int)$id;
		if ($id == 0){
			$id = (int)$this->getPostData()['id'];
		}

		if ($id == 0){
			throw new \Exception('Нет ид заказа', 406);
		}

		$order = Sale\Order::load($id);
		$order->setField('STATUS_ID', 'DD');
		$res = $order->save();

//		dd($res);

		if ($res->isSuccess()){
			ProcessTable::add([
				'ORDER_ID' => $id,
//				'USER_ID' => $this->getUser()->GetID(),
			]);
		} else {
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return $id;
	}

	/**
	 * @method saveComplectationOrder
	 * @return bool
	 * @throws Exception
	 */
	public function saveComplectationOrder()
	{

		$data = $this->getPostData();
		$orderId = $this->getPostData()['orderId'];
		$row = ComplectationTable::getByOrderId($orderId);
		$productData = $row['PRODUCT_DATA'];

		$data['count'] = (float)$data['count'];
		$data['storeQuantity'] = (float)$data['storeQuantity'];

		$order = Sale\Order::load($orderId);

		if ($data['count'] < $data['storeQuantity']){

			$diffQuantity = $data['storeQuantity'] - $data['count'];
			$productForCopy = $productData['products'][$data['basketId']];

			unset($productData['products'][$data['basketId']]);
			$productData['founded'][$data['basketId']] = $productForCopy;

			$basketFields = $productForCopy['BASKET_DATA'];

			$basketFields['QUANTITY'] = $diffQuantity;
			$basketFields['UPDATE_QUANTITY'] = $data['count'];

			$newBasketItem = $this->copyBasketItem($basketFields, $order);

			$productForCopy = array_merge($productForCopy, $newBasketItem);

			$productData['products'][$newBasketItem['BASKET_ID']] = $productForCopy;

			$updateCustom = BasketShopTable::getRowById($newBasketItem['ID']);
			$updateCustom['BASKET_DATA'] = Sale\Internals\BasketTable::getRowById($newBasketItem['BASKET_ID']);
			$founded = array_merge($productForCopy, $updateCustom);

			$founded['QUANTITY'] = round($founded['BASKET_DATA']['QUANTITY'], 2);
			$founded['BASKET_DATA']['QUANTITY'] = round($founded['BASKET_DATA']['QUANTITY'], 2);

			$productData['founded'][$data['basketId']] = $founded;

		} elseif ($data['count'] == $data['storeQuantity']) {
			$productData['founded'][$data['basketId']] = $productData['products'][$data['basketId']];
			unset($productData['products'][$data['basketId']]);

		} else {
			throw new \Exception('Неверное количество товара', 406);
		}

		if (is_null($row)){
			$res = ComplectationTable::add([
				'PRODUCT_DATA' => $productData,
				'ORDER_ID' => $orderId,
				'USER_ID' => $this->getUser()->GetID(),
			]);
		} else {
			$res = ComplectationTable::update($row['ID'], [
				'PRODUCT_DATA' => $productData,
			]);
		}

		if (!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		$detail = $this->getDetail($orderId);
		$complectation = $detail['COMPLECTATION']['PRODUCT_DATA'];
		foreach ($complectation['products'] as $id => &$item) {
			if (!empty($detail['BASKET'][$id]))
				$item = $detail['BASKET'][$id];
		}
		foreach ($complectation['founded'] as $id => &$item) {
			if (!empty($detail['BASKET'][$id]))
				$item = $detail['BASKET'][$id];
		}
		$res = ComplectationTable::update($row['ID'], [
			'PRODUCT_DATA' => $complectation,
		]);


		return $res->isSuccess();
	}

	/**
	 * @method copyBasketItem
	 * @param array $basketFields
	 * @param Sale\Order $order
	 *
	 * @return array|null
	 */
	public function copyBasketItem(array $basketFields = [], Sale\Order $order)
	{
		$updateQuantity = $basketFields['UPDATE_QUANTITY'];
		$updateBasketId = $basketFields['ID'];

//		dd($updateQuantity);
		unset(
			$basketFields['DATE_INSERT'],
			$basketFields['DATE_UPDATE'],
			$basketFields['FUSER_ID'],
			$basketFields['MODULE'],
			$basketFields['ID'],
			$basketFields['RESERVED'],
			$basketFields['RESERVE_QUANTITY'],
			$basketFields['ORDER_ID'],
			$basketFields['DATE_REFRESH'],
			$basketFields['SUM'],
			$basketFields['SUM_FORMAT'],
			$basketFields['PRICE_FORMAT'],
			$basketFields['UPDATE_QUANTITY']
		);


		$basket = $order->getBasket();
		$basketItem = $basket->getItemById($updateBasketId);
		/** @var Sale\BasketItem $newItem */
		$newItem = $basket->createItem('catalog', $basketFields['PRODUCT_ID']);
		$copyFields = array_merge($basketFields, [
			'QUANTITY' => $basketFields['QUANTITY'],
			'PRICE' => $basketFields['PRICE'],
			'BASE_PRICE' => $basketFields['BASE_PRICE'],
			'CURRENCY' => $basketFields['CURRENCY'],
			'NAME' => $basketFields['NAME'],
			'CAN_BUY' => 'Y',
			'PRODUCT_XML_ID' => $basketFields['PRODUCT_XML_ID'],
		]);

		$newItem->setFields($copyFields);

		Sale\Internals\BasketTable::update($updateBasketId, [
			'QUANTITY' => $updateQuantity,
		]);

		$customBasket = BasketShopTable::getBasketBitrix($updateBasketId);
		$customBasketId = $customBasket['ID'];

		unset($customBasket['ID']);
		$customBasket['QUANTITY'] = $updateQuantity;
		BasketShopTable::update($customBasketId, [
			'QUANTITY' => $updateQuantity,
		]);

		$basket->save();

		$shipmentCollection = $order->getShipmentCollection();

		/** @var Sale\Shipment $shipment */
		foreach ($shipmentCollection as $shipment) {
			if (!$shipment->isSystem())
				$shipmentItem = $shipment;
		}
		if ($shipmentItem instanceof Sale\Shipment){
			$shipmentItem
				->getShipmentItemCollection()
				->createItem($newItem)
				->setQuantity($newItem->getQuantity());
		}

		$order->save();

		$customBasket['BASKET_ID'] = $order->getBasket()->getItemByBasketCode('n1')->getId();
		$saveCustom = BasketShopTable::add($customBasket);
		$customBasket['ID'] = $saveCustom->getId();
		$customBasket['BASKET_DATA'] = $newItem->getFieldValues();

		return $customBasket;
	}

	/**
	 * @method returnToBasketList
	 * @return bool
	 * @throws Exception
	 */
	public function returnToBasketList()
	{
		$data = $this->getPostData();
		$orderId = (int)$data['orderId'];
		$basketId = (int)$data['basketId'];

		$complectation = ComplectationTable::getByOrderId($orderId);
		if(is_null($complectation)){
			throw new \Exception('Заказ не найден среди доступных к доставке', 500);
		}

		$productData = $complectation['PRODUCT_DATA'];
		if(!empty($productData['founded'][$basketId])){
			$productData['products'][$basketId] = $productData['founded'][$basketId];
			unset($productData['founded'][$basketId]);
		}

		$res = ComplectationTable::update($complectation['ID'], ['PRODUCT_DATA' => $productData]);
		if(!$res->isSuccess())
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);

		return $res->isSuccess();
	}


	public function updateQuantityFinal()
	{
		$data = $this->getPostData();
		$order = Sale\Order::load($data['orderId']);

		$basket = $order->getBasket();
		$basketItem = $basket->getItemById($data['basketId']);
		if(!is_null($basketItem)){
			$basketItem->setField('QUANTITY', $data['quantity']);
			$itemSave = $basketItem->save();
			Sale\Internals\BasketTable::update($basketItem->getId(), ['QUANTITY' => $data['quantity']]);
		}

		$saveBasket = $basket->save();

		$sumBasket = 0;


		$oBasket = Sale\Internals\BasketTable::getList([
			'filter' => ['=ORDER_ID' => $data['orderId']]
		]);
		while ($rs = $oBasket->fetch()){
			$sumBasket += $rs['BASE_PRICE'] * $rs['QUANTITY'];
		}

		$sum = $order->getDeliveryPrice() + $sumBasket;

		$order->setField('PRICE', $sum);

		$res = $order->save();

		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return $res->isSuccess();
	}

	public function deleteProductFinal(){
		$data = $this->getPostData();
		$order = Sale\Order::load($data['orderId']);
		$basket = $order->getBasket();
		$basketItem = $basket->getItemById($data['basketId']);
		if(!is_null($basketItem)){
			$basketItem->delete();
			$basketItem->save();
		}

		$basket->save();
		$res = $order->save();
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return $res->isSuccess();
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

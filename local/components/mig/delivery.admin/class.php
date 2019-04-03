<?php namespace Mig\DeliveryAdmin;
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
use UL\Main\Basket\Model\BasketShopTable;
use Online1c\Iblock;
use Bitrix\Catalog;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');


Loc::loadLanguageFile(__FILE__);

class MainComponent extends \CBitrixComponent
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

	/**
	* @method getUser
	* @return \CUser
	*/
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method getOrders
	 * @return array
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 * @throws Main\NotImplementedException
	 */
	public function getOrders()
	{
		$data = $this->getPostData();



		$result = [];

		$filter = [];

		switch ($data['type']){
			case 'delivery':
				$filter['=STATUS_ID'] = 'DD';
				$filter['LOCKED_BY'] = false;
				break;
			case 'complect':
				$filter['=STATUS_ID'] = 'DA';
				$filter['LOCKED_BY'] = $this->getUser()->GetID();
				break;
			default:
				$filter['=STATUS_ID'] = 'N';
				$filter['LOCKED_BY'] = false;
				break;
		}

		if($data['type'] === 'delivery'){
			$filter['=STATUS_ID'] = 'DD';
		}

		$obOrder = Sale\Internals\OrderTable::getList([
			'select' => [
				'ID', 'ACCOUNT_NUMBER', 'DATE_INSERT', 'DATE_UPDATE', 'PERSON_TYPE_ID',
				'USER_ID', 'PAYED', 'STATUS_ID', 'PRICE',
				'USER_LOGIN' => 'USER.LOGIN',
				'USER_SHORT_NAME' => 'USER.SHORT_NAME',
				'USER_PHONE' => 'USER.PERSONAL_PHONE',
				'USER_EMAIL' => 'USER.EMAIL',
				'LOCKED_BY'
			],
			'filter' => $filter,
			'limit' => 20,
			'order' => ['ID' => "DESC"]
		]);
		while ($rs = $obOrder->fetch(new DateFetchConverter())){
			$rs['PRICE_FORMAT'] = self::formatPrice($rs['PRICE']);

			$order = Sale\Order::load($rs['ID']);

			$resultItem = $order->getFieldValues();
			$propertyCollection = $order->getPropertyCollection();

			$arUser = Main\UserTable::getRow([
				'select' => ['SHORT_NAME', 'PERSONAL_PHONE', 'EMAIL'],
				'filter' => ['=ID' => $order->getUserId()]
			]);
			$badCodes = ['LOCATION'];
			/** @var Sale\PropertyValue $item */
			foreach ($propertyCollection as $item) {
				if(in_array($item->getField('CODE'), $badCodes)){
					continue;
				}
				$resultItem['PROPS'][$item->getField('CODE')] = $item->getFieldValues();

				if($item->getField('CODE') === 'EMAIL' && strlen($item->getValue()) === 0){
					$resultItem['PROPS']['EMAIL']["VALUE"] = $arUser['EMAIL'];
				}
				if($item->getField('CODE') === 'PHONE' && strlen($item->getValue()) === 0){
					$resultItem['PROPS']['PHONE']["VALUE"] = $arUser['PERSONAL_PHONE'];
				}
			}

			$rs['DATA'] = $resultItem;

			$result[] = $rs;
		}

		return $result;
	}

	private static function formatPrice($val = 0){
		return \SaleFormatCurrency($val, 'RUB', true);
	}

	public function getOrderById(){
		$id = (int)$this->getPostData()['id'];
		if($id == 0){
			throw new \Exception('Нет номера заказа', 406);
		}

		$order = Sale\Order::load($id);

		$result = $order->getFieldValues();
		$propertyCollection = $order->getPropertyCollection();

		$arUser = Main\UserTable::getRow([
			'select' => ['SHORT_NAME', 'PERSONAL_PHONE', 'EMAIL'],
			'filter' => ['=ID' => $order->getUserId()]
		]);
		$badCodes = ['LOCATION'];
		/** @var Sale\PropertyValue $item */
		foreach ($propertyCollection as $item) {
			if(in_array($item->getField('CODE'), $badCodes)){
				continue;
			}
			$result['PROPS'][$item->getField('CODE')] = $item->getFieldValues();

			if($item->getField('CODE') === 'EMAIL' && strlen($item->getValue()) === 0){
				$result['PROPS']['EMAIL']["VALUE"] = $arUser['EMAIL'];
			}
			if($item->getField('CODE') === 'PHONE' && strlen($item->getValue()) === 0){
				$result['PROPS']['PHONE']["VALUE"] = $arUser['PERSONAL_PHONE'];
			}
		}

		$basket = $order->getBasket();
		/** @var Sale\BasketItem $basketItem */
		foreach ($basket->getBasketItems() as $basketItem){
			$basketFields = $basketItem->getFieldValues();
			$basketFields['CUSTOM'] = BasketShopTable::getRow([
				'select' => ['*'],
				'filter' => ['BASKET_ID' => $basketItem->getId()]
			]);
			$basketFields['SUM'] = $basketItem->getFinalPrice();
			$basketFields['SUM_FORMAT'] = self::formatPrice($basketFields['SUM']);
			$basketFields['PRICE_FORMAT'] = self::formatPrice($basketFields['PRICE']);
			$basketFields['QUANTITY'] = (float)$basketFields['QUANTITY'];

			$basketFields['PRODUCT_DATA'] = $this->getProductData($basketFields['PRODUCT_XML_ID']);

//			BasketShopTable::getRow([]);

			$basketFields['WEIGHT'] = (int)$basketFields['WEIGHT'] > 0 ? (int)$basketFields['WEIGHT'] : 1000;

			$result['BASKET'][$basketItem->getId()] = $basketFields;
		}

//		dd($result);
//		dd($propertyCollection);
//		dd($order);

		return $result;
	}

	public function getProductData($xml_id = '')
	{
		$tmp = explode('|', $xml_id);

		$product = [
			'IMG' => \CFile::GetFileArray($tmp[0]),
			'RESIZE' => \CFile::ResizeImageGet($tmp[0], ['width' => 120, 'height' => 120], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)
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
	public function lockedOrder($id = null){
		$id = (int)$id;
		if($id == 0){
			$id = (int)$this->getPostData()['id'];
		}

		$orderLock = Sale\Order::lock($id);
		if(!$orderLock->isSuccess()){
			throw new \Exception(implode(', ', $orderLock->getErrorMessages()), 406);
		}

		$order = Sale\Order::load($id);
		$order->setField('STATUS_ID', 'DA');
		$order->save();

		return true;
	}

	/**
	 * @method deliveryOrder
	 * @param null $id
	 *
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 * @throws Main\NotImplementedException
	 * @throws \Exception
	 */
	public function deliveryOrder($id = null)
	{
		$id = (int)$id;
		if($id == 0){
			$id = (int)$this->getPostData()['id'];
		}

		$orderLock = Sale\Order::unlock($id);
		if(!$orderLock->isSuccess()){
			throw new \Exception(implode(', ', $orderLock->getErrorMessages()), 406);
		}

		$order = Sale\Order::load($id);
		$order->setField('STATUS_ID', 'DD');
		$order->save();
	}

	/**
	 * @method delProduct
	 * @return bool
	 */
	public function delProduct()
	{
		$data = $this->getPostData();

		$order = Sale\Order::load($data['order']);
		$basket = $order->getBasket();

		$item = $basket->getItemById($data['item']['ID']);
		$item->delete();
		$basket->save();

		$order->setBasket($basket);
		$order->save();

		return true;
	}

	public function searchReplaceItems()
	{
		$data = $this->getPostData();

		$q = $data['q'];
		$skuId = $data['sku'];

		$result = null;

		if(strlen($q) > 3){

			$skuIblock = Iblock\Element::getIblockByElement($skuId);
			$catalogData = \CCatalogSku::GetInfoByOfferIBlock($skuIblock);
			$skuItem = Iblock\Element::getRow([
				'select' => [
					'ID','IBLOCK_ID',
					'SHOP_ID' => 'PROPERTY.SHOP_ID',
					'PRODUCT_IB' => 'PROPERTY.CML2_LINK_BIND.IBLOCK_ID'
				],
				'filter' => ['IBLOCK_ID' => $skuIblock, '=ID' => $skuId]
			]);

//			\CCatalogSku::GetProductInfo()

			$oProducts = Iblock\Element::getList([
				'select' => ['CML2_LINK' => 'PROPERTY.CML2_LINK', 'PRICE_VAL' => 'PRICE.PRICE'],
				'filter' => [
					'=ACTIVE' => 'Y',
					'IBLOCK_ID' => $skuIblock,
//					'PROPERTY.CML2_LINK_BIND.IBLOCK_SECTION_ID' => $data['section'],
					'%PROPERTY.CML2_LINK_BIND.NAME' => $q,
					'=PROPERTY.SHOP_ID' => $skuItem['SHOP_ID']
				],
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
						'IBLOCK_ID' => $catalogData['PRODUCT_IBLOCK_ID'], '=ID' => $ids
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

					$item['QUANTITY'] = $data['QUANTITY'];

					$result[$item['ID']] = $item;
				}
			}

		}

//		PR($result);

		return $result;
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

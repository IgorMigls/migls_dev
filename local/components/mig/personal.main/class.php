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
use AB\Tools\Helpers\DateFetchConverter;
use Bitrix\Catalog\PriceTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Sale\Internals\StatusTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\PropertyValue;
use const BX_RESIZE_IMAGE_EXACT;
use const BX_RESIZE_IMAGE_PROPORTIONAL_ALT;
use function is_array;
use function is_null;
use Mig\Mobile\DeliveryComponent;
use Online1c\Iblock\Element;
use function sleep;
use function strip_tags;
use UL\Main\Basket\Model\BasketShopTable;
use UL\Main\Delivery\ComplectationTable;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\ProductMeasure;
use UL\Main\Personal\OrderNumberTable;
use UL\Main\Services\FavoriteTable;
use UL\Main\Services\ListTable;

Loc::loadLanguageFile(__FILE__);
Main\Loader::includeModule('sale');
Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');

\CBitrixComponent::includeComponentClass('mig:address.window');

class PersonalComponent extends \CBitrixComponent
{
	protected $postData;

	/**
	 * @var Address\WindowComponent
	 */
	protected $Address;

	/** @var Main\Type\Dictionary */
	protected $iblockCatalog;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->Address = new \Mig\Address\WindowComponent();

		$this->iblockCatalog = new Main\Type\Dictionary();
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

	/**
	 * @method getUserAction
	 * @return array|null
	 */
	public function getUserAction()
	{
		$arUser = Main\UserTable::getRow([
			'select' => [
				'ID', 'NAME', 'LAST_NAME', 'EMAIL', 'SHORT_NAME',
				'PERSONAL_PHONE', 'PERSONAL_MOBILE', 'PERSONAL_PHOTO', 'PERSONAL_BIRTHDAY',
			],
			'filter' => ['=ID' => $this->getUser()->GetID()],
		]);

		if ((int)$arUser['PERSONAL_PHOTO']){
			$arUser['AVA'] = \CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], ['width' => 130, 'height' => 130], BX_RESIZE_IMAGE_EXACT);
		}
		$date = $arUser['PERSONAL_BIRTHDAY'];
		if ($date instanceof Main\Type\Date){
			$arUser['PERSONAL_BIRTHDAY'] = [
				'day' => $date->format('d'),
				'month' => $date->format('n'),
				'year' => $date->format('Y'),
			];
		} else {
			$arUser['PERSONAL_BIRTHDAY'] = [
				'day' => '',
				'month' => 1,
				'year' => '',
			];
		}

		return $arUser;
	}

	/**
	 * @method saveAvatar
	 * @return array
	 * @throws \Exception
	 */
	public function saveAvatar()
	{
		$data = $this->getPostData();
		if (!$this->getUser()->IsAuthorized()){
			throw new \Exception('not auth', 403);
		}

		$result = ['data' => null];
		$CUser = new \CUser();
		if (!$CUser->Update($this->getUser()->GetID(), ['PERSONAL_PHOTO' => $data])){
			throw new \Exception(strip_tags($CUser->LAST_ERROR), 500);
		}

		$user = $this->getUserAction();
		$result['data'] = $user['AVA'];
		$result['status'] = 200;

		return $result;
	}

	/**
	 * @method saveUserFields
	 * @return bool
	 * @throws \Exception
	 */
	public function saveUserFields()
	{
		$data = $this->getPostData();

		$save = [
			'NAME' => $data['NAME'],
			'LAST_NAME' => $data['LAST_NAME'],
			'PERSONAL_MOBILE' => $data['PERSONAL_MOBILE'],
		];

		if (strlen($data['PERSONAL_BIRTHDAY']['day']) > 0 && strlen($data['PERSONAL_BIRTHDAY']['month']) > 0 && strlen($data['PERSONAL_BIRTHDAY']['year']) > 0){
			$dateStr = Main\Type\DateTime::createFromUserTime($data['PERSONAL_BIRTHDAY']['day'].'.'.$data['PERSONAL_BIRTHDAY']['month'].'.'.$data['PERSONAL_BIRTHDAY']['year']);
			if ($dateStr instanceof Main\Type\DateTime || $dateStr instanceof Main\Type\Date){
				$save['PERSONAL_BIRTHDAY'] = $dateStr->format('d.m.Y');
			}
		}

		$CUser = new \CUser();
		if (!$CUser->Update($this->getUser()->GetID(), $save)){
			throw new \Exception(strip_tags($CUser->LAST_ERROR), 500);
		}

		return true;
	}

	/**
	 * @method getAddressList
	 * @return array|null
	 * @throws \Exception
	 */
	public function getAddressList()
	{
		return $this->Address->getAddressList();
	}

	/**
	 * @method saveAddress
	 * @return int|null
	 * @throws Main\ObjectException
	 * @throws \Exception
	 */
	public function saveAddress()
	{
		return $this->Address->saveAddress();
	}

	/**
	 * @method loadAddress
	 * @return array|null
	 * @throws \Exception
	 */
	public function loadAddress()
	{
		return $this->Address->loadAddress();
	}

	/**
	 * @method setNewAddress
	 * @return array|null
	 * @throws Main\ArgumentException
	 */
	public function setNewAddress()
	{
		return $this->Address->setNewAddress();
	}

	/**
	 * @method saveAddressEmail
	 * @return array
	 */
	public function saveAddressEmail()
	{
		return $this->Address->saveAddressEmail();
	}

	/**
	 * @method deleteAddress
	 * @throws \Exception
	 */
	public function deleteAddress()
	{
		return $this->Address->deleteAddress();
	}

	/**
	 * @method getOrderList
	 * @return array
	 * @throws Main\ArgumentException
	 */
	public function getOrderList()
	{
		$userId = self::getUser()->GetID();
		$siteId = Main\Context::getCurrent()->getSite();
		$obNumbers = OrderNumberTable::getList([
			'select' => [
				'ACCOUNT_NUMBER'
//				'ORDER_SUM' => 'ORDER.PRICE',
//				'ORDER_DATE' => 'ORDER.DATE_INSERT_FORMAT',
			],
			'filter' => ['=USER_ID' => $userId],
			'order' => ['ACCOUNT_NUMBER' => 'DESC'],
			'group' => [
				'ACCOUNT_NUMBER'
			]
		]);
		$orderIds = [];
		while ($order = $obNumbers->fetch()) {
			$order['ID'] = str_replace('/', '_', $order['ACCOUNT_NUMBER']);

			$arOrderNum = explode('_', $order['ID']);

			$sum = 0;
			foreach ($arOrderNum as $value) {
				$rsOrder = Order::load($value);
				$sum += $rsOrder->getPrice();
				$order['ORDER_DATE'] = $rsOrder->getField('DATE_INSERT')->toString();
			}
			$order['ORDER_SUM'] = \SaleFormatCurrency($sum, 'RUB', true);

			$arOrders[] = $order;
		}


		return $arOrders;
	}

	/**
	 * @method getDetailOrder
	 * @return null
	 */
	public function getDetailOrder()
	{
		$arNumber = explode('_', $this->request->get('id'));

		$result = null;
		$sum = $count = 0;
		$date = '';
		$comment = '';
		$sumBasket = 0;


		foreach ($arNumber as $value) {
			$order = Order::load($value);

			if($order->getUserId() !== $this->getUser()->GetID()){
				continue;
			}

			if ($order->getField('STATUS_ID') == 'N'){
				$orderIsBlockedAdmin = false;
			} else {
				$orderIsBlockedAdmin = true;
			}

			if ($order->getField('CANCELED') == 'Y')
				$orderIsBlockedAdmin = true;

			$selectNum = [
				'*',
				'SHOP_CODE' => 'SHOP.CODE',
				'SHOP_NAME' => 'SHOP.NAME',
				'SHOP_IMG_ID' => 'SHOP.DETAIL_PICTURE',
			];
			$num = OrderNumberTable::getByOrderId($value, $selectNum);

			if ($num['SHOP_IMG_ID']){
				$num['IMG'] = \CFile::ResizeImageGet(
					$num['SHOP_IMG_ID'],
					['width' => 100, 'height' => 100],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}

			$props = [];

			/** @var PropertyValue $oProp */
			foreach ($order->getPropertyCollection() as $oProp) {
				$props[$oProp->getField('CODE')] = $oProp->getFieldValues();
			}

			$sum += $order->getPrice();

			$complectation = ComplectationTable::getByOrderId($order->getId());
			$complectProducts = $complectation['PRODUCT_DATA'];

			$history = $this->getHistory($order->getId());

			$oBasket = $order->getBasket();
			$basket = [];
			/** @var BasketItem $basketItem */
			foreach ($oBasket as $basketItem) {
				$rs = $basketItem->getFieldValues();
				$picture = Element::getRow([
					'select' => [
						'ID', 'NAME', 'DETAIL_PICTURE',
					],
					'filter' => ['=ID' => $rs['PRODUCT_ID']],
				]);

				$img = null;
				if ((int)$picture['DETAIL_PICTURE'] > 0){
					$img = \CFile::GetFileArray($picture['DETAIL_PICTURE']);
					$img['RESIZE'] = \CFile::ResizeImageGet(
						$picture['DETAIL_PICTURE'],
						['width' => 90, 'height' => 90],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
				}
				$rs['IMG'] = $img;
				$rs['CUSTOM'] = BasketShopTable::getBasketBitrix($basketItem->getId());

				/*if(is_null($rs['CUSTOM'])){

					$shopId = $props['SHOP_ID']['VALUE'];
					$CIB = new \CIBlockElement;
					$ib = \Soft\IBlock\ElementTable::getIBlock($basketItem->getProductId());
					$products = current(\CCatalogSku::getOffersList(
						$basketItem->getProductId(), $ib
					));
					$ids = array_map(function ($el) {
						return [''];
					}, $products);

					dump($ids);

					$customAdd = [
						'BASKET_ID' => $rs['ID'],
						'SKU_ID' => $rs['']
					];


				}*/

				if(empty($rs['CUSTOM'])){
					continue;
				}
				try{
					$measure = ProductMeasure::getMeasureByProductId($rs['CUSTOM']['SKU_IBLOCK_ID'], $rs['CUSTOM']['SKU_ID']);
					$rs['MEASURE_NAME'] = $measure->getName();
					$rs['MEASURE_SHORT_NAME'] = $measure->getShortName();
					$rs['MEASURE_RATIO'] = $measure->getRatio();
				} catch (\Exception $e){
				}

				if ((int)$rs['CUSTOM']['REPLACE']['DETAIL_PICTURE'] > 0){
					$rs['CUSTOM']['REPLACE']['ORIGINAL_IMG'] = \CFile::GetFileArray($rs['CUSTOM']['REPLACE']['DETAIL_PICTURE']);
				}
				$rs['SUM'] = $basketItem->getFinalPrice();
				$rs['SUM_FORMAT'] = \SaleFormatCurrency($rs['SUM'], 'RUB', true);
				$rs['QUANTITY'] = (float)$rs['QUANTITY'];

				$replaced = array_filter($history, function ($el) use ($rs) {
					return $rs['ID'] == $el['ENTITY_ID'] && $el['TYPE'] == 'BASKET_REPLACED_FROM';
				});

				$replaced = array_shift($replaced);
				$rs['REPLACE_FROM'] = null;

				$isMainList = $isFoundList = [];

				if ($complectProducts['products'][$basketItem->getId()]){
					$isMainList[] = $complectProducts['products'][$basketItem->getId()];
				}

				if ($complectProducts['founded'][$basketItem->getId()]){
					$isFoundList[] = $complectProducts['founded'][$basketItem->getId()];
				}

				$rs['FOUND_LIST'] = $isFoundList;
				$rs['MAIN_LIST'] = $isMainList;

				if (count($isMainList) == 0){
					$rs['MAIN_LIST'] = [$basketItem->getProductId()];
				}

				if ($replaced['ID']){

					$replaced['PRODUCT'] = Element::getRow([
						'filter' => ['=ID' => $replaced['DATA']['PRODUCT_ID']],
						'select' => ['DETAIL_PICTURE', 'NAME'],
					]);

					$replaced['PRODUCT']['IMG'] = \CFile::ResizeImageGet(
						$replaced['PRODUCT']['DETAIL_PICTURE'],
						['width' => 90, 'height' => 90],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
					$replaced['PRODUCT']['DETAIL_PICTURE'] = \CFile::GetFileArray($replaced['PRODUCT']['DETAIL_PICTURE']);
					$replaced['SUM'] = $rs['QUANTITY'] * $replaced['DATA']['PRICE'];
					$replaced['SUM_FORMAT'] = \SaleFormatCurrency($replaced['SUM'], 'RUB', true);

					$rs['REPLACE_FROM'] = $replaced;

					$basket['REPLACES'][$basketItem->getId()] = $rs;
				} elseif (count($rs['FOUND_LIST']) > 0) {
					$basket['FOUNDED'][$basketItem->getId()] = $rs;
				} elseif (count($rs['MAIN_LIST']) > 0) {
					$basket['MAIN'][$basketItem->getId()] = $rs;
				}

				$sumBasket += $basketItem->getFinalPrice();

//				dump($basketItem->getFieldValues());
			}


			$fields = $order->getFieldValues();

			if ($fields['CANCELED'] == 'Y'){
				$fields['STATUS_DATA'] = [
					'DESCRIPTION' => '',
					'LID' => 'ru',
					'NAME' => 'Заказ отменен',
				];
			} else {
				$fields['STATUS_DATA'] = StatusLangTable::getRow([
					'filter' => ['STATUS_ID' => $fields['STATUS_ID'], 'LID' => 'ru'],
				]);
			}

			$deleted = array_filter($history, function ($el) {
				return $el['TYPE'] == 'BASKET_REMOVED';
			});

			foreach ($deleted as &$del) {
				$del['DATA']['QUANTITY'] = round($del['DATA']['QUANTITY'], 2);
				if ((int)$del['DATA']['PRODUCT_ID'] > 0){
					$del['PRODUCT'] = Element::getRow([
						'filter' => ['=ID' => $del['DATA']['PRODUCT_ID']],
						'select' => ['DETAIL_PICTURE', 'NAME'],
					]);
					$del['PRODUCT']['IMG'] = \CFile::ResizeImageGet(
						$del['PRODUCT']['DETAIL_PICTURE'],
						['width' => 90, 'height' => 90],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
					$del['PRODUCT']['DETAIL_PICTURE'] = \CFile::GetFileArray($del['PRODUCT']['DETAIL_PICTURE']);

					$comlectDel = $complectProducts['deleted'][$del['ENTITY_ID']];
					$del['DATA']['MEASURE_NAME'] = $comlectDel['MEASURE_NAME'];
					$del['DATA']['MEASURE_SHORT_NAME'] = $comlectDel['MEASURE_SHORT_NAME'];
					$del['DATA']['MEASURE_RATIO'] = $comlectDel['MEASURE_RATIO'];
				}
			}

			$fields['DELETED'] = null;

			if (count($deleted) > 0 && !empty($deleted))
				$fields['DELETED'] = $deleted;

			unset($deleted, $history);

			/** @var Main\Type\DateTime $dateInsert */
			$dateInsert = $fields['DATE_INSERT'];

			$result['ORDER'][$value] = [
				'SHOP' => $num,
				'FIELDS' => $fields,
				'BASKET' => $basket,
				'PROPS' => $props,
			];
			$count += $oBasket->count();
			$comment = $fields['USER_DESCRIPTION'];


		}
//		dump($isFoundList);
		$result['DATE'] = [
			'day' => $dateInsert->format('d'),
			'month' => $dateInsert->format('m'),
			'monthRu' => FormatDate('F', $dateInsert->getTimestamp()),
			'year' => $dateInsert->format('Y'),
		];
		$result['SUM'] = $sum;
		$result['SUM_FORMAT'] = \SaleFormatCurrency($sum, 'RUB', true);
		$result['COUNT'] = $count;
		$result['USER_DESCRIPTION'] = $comment;
		$result['SUM_DELIVERY'] = $order->getDeliveryPrice();
		$result['SUM_BASKET'] = $sumBasket;
		$result['SUM_BASKET_FORMAT'] = \SaleFormatCurrency($sumBasket, 'RUB', true);

		$result['ORDER_BLOCKED'] = $orderIsBlockedAdmin;

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
		\CBitrixComponent::includeComponentClass('mig:mobile.delivery');
		$DeliveryComponent = new DeliveryComponent();

		return $DeliveryComponent->getHistory($orderId);
	}

	/**
	 * @method getSections
	 * @return array|null
	 * @throws Main\ArgumentException
	 */
	public function getFavoriteSections()
	{
		$result = null;
		$oList = ListTable::getList([
			'filter' => ['=USER_ID' => $this->getUser()->GetID()],
		]);
		while ($rs = $oList->fetch()) {
			$rs['CNT_PRODUCTS'] = (int)$rs['CNT_PRODUCTS'];
			$result[] = $rs;
		}

		return $result;
	}

	/**
	 * @method addFavoriteSection
	 * @return array
	 * @throws Main\ArgumentException
	 * @throws \Exception
	 */
	public function addFavoriteSection()
	{
		$data = $this->getPostData();

		if (strlen($data['NAME']) == 0){
			throw new \Exception('Нет названия для списка');
		}

		$row = ListTable::getRow([
			'filter' => ['NAME' => $data['NAME'], 'USER_ID' => $this->getUser()->GetID()],
		]);
		if (!is_null($row)){
			throw new \Exception('Список с таким названием уже есть');
		}

		$res = ListTable::add(['NAME' => $data['NAME']]);

		if ($res->isSuccess()){
			return [
				'ID' => $res->getId(),
				'ITEMS' => $this->getFavoriteSections(),
			];
		} else {
			throw new \Exception(implode(',', $res->getErrorMessages()));
		}
	}

	/**
	 * @method deleteFavoriteSection
	 * @return array|null
	 * @throws Main\ArgumentException
	 * @throws \Exception
	 */
	public function deleteFavoriteSection()
	{
		$data = $this->getPostData();
		$id = (int)$data['ID'];
		if ($id == 0)
			throw new \Exception('Список не найден', 406);

		if (!$this->getUser()->IsAuthorized())
			throw new \Exception('Вы не авторизованы', 403);

		$res = ListTable::delete($id);
		if ($res->isSuccess()){
			$oItems = FavoriteTable::getList([
				'filter' => [
					'=USER_ID' => $this->getUser()->GetID(),
					'=LIST_ID' => $id,
				],
			]);
			while ($rs = $oItems->fetch()) {
				FavoriteTable::update($rs['ID'], ['LIST_ID' => false]);
			}
		}


		return $this->getFavoriteSections();
	}

	/**
	 * @method deleteOutFavorite
	 * @throws \Exception
	 */
	public function deleteOutFavorite()
	{
		$items = $this->getPostData()['items'];
		if (count($items) == 0){
			throw new \Exception('Все уже удалили до вас', 406);
		}

		foreach ($items as $item) {
			$this->deleteFavoriteById((int)$item['favoriteId']);
		}
	}

	/**
	 * @method deleteFavoriteById
	 * @param int $id
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function deleteFavoriteById(int $id)
	{
		$item = FavoriteTable::getRow([
			'filter' => ['=USER_ID' => $this->getUser()->GetID(), '=ID' => $id],
		]);
		if(is_null($item)){
			throw new \Exception('Продукт не найден', 500);
		}

		$res = FavoriteTable::delete($item['ID']);
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
		}

		return $res->isSuccess();
	}

	/**
	 * @method changeEmail
	 * @return array
	 * @throws \Exception
	 */
	public function changeEmail()
	{
		$data = $this->getPostData();
		if ($data['CURRENT_EMAIL'] === $data['NEW_EMAIL']){
			throw new \Exception('Новый и старый e-mail не должны совпадать', 406);
		}

		$user = Main\UserTable::getRow([
			'select' => ['ID', 'EMAIL'],
			'filter' => ['=ID' => $this->getUser()->GetID()],
		]);
		if ($user['EMAIL'] !== $data['CURRENT_EMAIL']){
			throw new \Exception('Это не ваш e-mail. Введите e-mail, который вы указывали при регистрации', 403);
		}

		$bAuth = $this->getUser()->Login($data['CURRENT_EMAIL'], $data['PASSWORD']);
		if ($bAuth === true){
			$CUser = new \CUser();
			$CUser->Update($user['ID'], ['LOGIN' => $data['NEW_EMAIL'], 'EMAIL' => $data['NEW_EMAIL']]);
			$this->getUser()->Authorize($user['ID']);

			return [
				'success' => 'Ваш email изменен',
			];
		} else {
			throw new \Exception('Неверный пароль', 403);
		}
	}

	/**
	 * @method changePassword
	 * @return array
	 * @throws \Exception
	 */
	public function changePassword()
	{
		$data = $this->getPostData();
		$user = Main\UserTable::getRow([
			'select' => ['ID', 'EMAIL'],
			'filter' => ['=ID' => $this->getUser()->GetID()],
		]);
		if (is_null($user)){
			throw new \Exception('Пользователь не найден', 404);
		}

		if ($data['NEW_PASS'] !== $data['NEW_CONFIRM_PASS'])
			throw new \Exception('Новый пароль и его подтверждение не совпадают', 406);

		$bAuth = $this->getUser()->Login($user['EMAIL'], $data['CURRENT_PASS']);
		if ($bAuth === true){
			$CUser = new \CUser();
			if (!$CUser->Update($user['ID'], ['PASSWORD' => $data['NEW_PASS'], 'CONFIRM_PASSWORD' => $data['NEW_PASS']])){
				throw new \Exception(strip_tags($CUser->LAST_ERROR), 500);
			}
			$this->getUser()->Authorize($user['ID']);

			return [
				'success' => 'Ваш пароль изменен',
			];
		} else {
			throw new \Exception('Неверный пароль', 403);
		}
	}

	public function getFavoriteProducts()
	{
		$result = [];
		$listId = (int)$this->request->get('listId');

		$obItems = FavoriteTable::getList([
			'filter' => [
				'LIST_ID' => $listId,
				'=USER_ID' => $this->getUser()->GetID(),
				'@SHOP_ID' => $_SESSION['REGIONS']['SHOP_ID']
			],
		]);

		while ($rs = $obItems->fetch(new DateFetchConverter())) {
			$iblockId = Element::getIblockByElement($rs['ELEMENT_ID']);
			$catIblock = $this->getIblockCatalog($iblockId);

			$product = Element::getRow([
				'select' => [
					'ID', 'NAME', 'IBLOCK_ID', 'PICTURE' => 'PROPERTY.CML2_LINK_BIND.DETAIL_PICTURE',
					'PRICE_VAL' => 'PRICE_DATA.PRICE',
					'SHOP_ID' => 'PROPERTY.SHOP_ID',
					'SHOP_NAME' => 'SHOP_DATA.NAME',
					'SHOP_CODE' => 'SHOP_DATA.CODE',
					'SHOP_PICTURE' => 'SHOP_DATA.DETAIL_PICTURE',
					'PRODUCT_ID' => 'PROPERTY.CML2_LINK_BIND.ID',
				],
				'filter' => [
					'IBLOCK_ID' => $catIblock['IBLOCK_ID'],
					'=PROPERTY.CML2_LINK' => $rs['ELEMENT_ID'],
					'=PROPERTY.SHOP_ID' => $rs['SHOP_ID'],
				],
				'runtime' => [
					new Main\Entity\ReferenceField(
						'PRICE_DATA',
						PriceTable::getEntity(),
						['=this.ID' => 'ref.PRODUCT_ID']
					),
					new Main\Entity\ReferenceField(
						'SHOP_DATA',
						ElementTable::getEntity(),
						['=this.PROPERTY.SHOP_ID' => 'ref.ID']
					),
				],
			]);

			$product['IMG'] = \CFile::ResizeImageGet(
				$product['PICTURE'], ['width' => 80, 'height' => 80],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			);

			$product['SHOP_PICTURE'] = \CFile::ResizeImageGet(
				$product['SHOP_PICTURE'], ['width' => 100, 'height' => 70],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			);

			$product['PRICE_FORMAT'] = \SaleFormatCurrency((float)$product['PRICE_VAL'], 'RUB', true);


			$measure = ProductMeasure::getMeasureByProductId($product['IBLOCK_ID'], $product['ID']);

			$product['MEASURE_NAME'] = $measure->getName();
			$product['MEASURE_SHORT_NAME'] = $measure->getShortName();
			$product['MEASURE_RATIO'] = $measure->getRatio();
			$product['CHECKED'] = false;

			$rs['PRODUCT'] = $product;

			$result[$product['SHOP_CODE']]['NAME'] = $product['SHOP_NAME'];
			$result[$product['SHOP_CODE']]['CODE'] = $product['SHOP_CODE'];
			$result[$product['SHOP_CODE']]['PICTURE'] = $product['SHOP_PICTURE'];
			$result[$product['SHOP_CODE']]['ITEMS'][] = $rs;
		}

//		dd($result);
		return $result;
	}

	protected function getIblockCatalog($iblockId)
	{
		if (!$this->iblockCatalog->offsetExists($iblockId)){
			$cat = \CCatalogSku::GetInfoByIBlock($iblockId);
			$this->iblockCatalog->offsetSet($iblockId, $cat);
		}

		return $this->iblockCatalog->get($iblockId);
	}

	/**
	 * @method addToFavorite
	 * @throws \Exception
	 */
	public function addToFavorite()
	{
		$data = $this->getPostData();

		foreach ($data['items'] as $item) {
			$res = FavoriteTable::update((int)$item['favoriteId'], ['LIST_ID' => $data['elementId']]);
			if (!$res->isSuccess()){
				throw new \Exception(implode(', ', $res->getErrorMessages()), 500);
			}
		}
	}

	public function cancelOrder()
	{
		$data = $this->getPostData();
		$order = Order::load($data['orderId']);
		$order->setField('CANCELED', 'Y');
		$res = $order->save();
		if (!$res->isSuccess()){
			throw new \Exception(implode(',', $res->getErrorMessages()), 500);
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
		$this->Address->setPostData($postData);
	}
}

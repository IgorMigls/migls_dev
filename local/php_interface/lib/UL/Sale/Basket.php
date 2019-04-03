<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 18.07.2016
 * Time: 17:03
 */

namespace UL\Sale;

use AB\Tools\Helpers\DataCache;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale;
use PW\Tools\Debug;
use Soft\Element;
use UL\Main\Services\Favorite;
use UL\Tools;
use Bitrix\Main\Entity;
use UL\Main\Basket\Model;

Loader::includeModule('sale');
Loader::includeModule('soft.iblock');
Loader::includeModule('catalog');
includeModules(['ul.main', 'ab.iblock', 'search']);

class Basket
{
	protected static $siteId;
	protected static $basket = null;
	protected static $deliveryFree = null;

	/** @var Basket  */
	private static $instance = null;

	/**
	 * Basket constructor.
	 */
	public function __construct()
	{
		\CBitrixComponent::includeComponentClass('ul:shop.detail');

		self::$siteId = Context::getCurrent()->getSite();
	}

	/**
	 * @method getInstance - get param instance
	 * @return Basket
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new static();
		}

		return self::$instance;
	}


	public static function addBasketById($data = [])
	{
		Loader::includeModule('ab.iblock');

		$productId = intval($data['PRODUCT']);
		$quantity = floatval($data['QUANTITY']);

		if ($productId == 0)
			throw new SaleException('Нет товара для добавления в корзину');

		if ($quantity == 0){
			$quantity = 1;
		}

		$dataProduct = \CCatalogProduct::GetByID($productId);

		if($dataProduct['QUANTITY'] < $quantity){
			throw new \Exception('На складе есть только '.$dataProduct['QUANTITY'].' шт этого товара', 403);
		}

		$iblockIdSku = \AB\Iblock\Element::getIblockByElementId($productId);
		$arIblock = \CCatalogSku::GetInfoByIBlock($iblockIdSku);
		$oBasket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId);

		$item = self::getByProduct($productId);


		if ($item instanceof Sale\BasketItem){
			$item->setField('QUANTITY', $quantity);
			$res = $item->save();
		} else {

			$iblock = \CIBlockElement::GetIBlockByID($productId);

			$catalog = \CCatalogSku::GetInfoByIBlock($iblock);
			$arProduct = Sale\ProductTable::getRow([
				'select' => ['*', 'PRICE', 'CURRENCY'],
				'filter' => ['=ID' => $productId],
				'runtime' => [
					new Entity\ReferenceField(
						'ELEMENT',
						\Bitrix\Iblock\ElementTable::getEntity(),
						['=this.ID' => 'ref.ID']
					),
				],
			]);

			$arDataProduct = \CIBlockElement::GetList(
				array(),
				array('=ID' => $arProduct['ID'], 'IBLOCK_ID' => $catalog['IBLOCK_ID']),
				false,
				array('nTopCount' => 1),
				array(
					'ID', 'IBLOCK_ID', 'PROPERTY_CML2_LINK.NAME',
					'PROPERTY_CML2_LINK.DETAIL_PICTURE', 'PROPERTY_CML2_LINK',
					'PROPERTY_SHOP_ID.CODE',
				)
			)->Fetch();

			$item = $oBasket->createItem('catalog', $arProduct['ID']);
			$res = $item->setFields([
				'PRODUCT_ID' => $arProduct['ID'],
				'PRICE' => $arProduct['PRICE'],
				'CURRENCY' => 'RUB',
				'QUANTITY' => $quantity,
				'NAME' => $arDataProduct['PROPERTY_CML2_LINK_NAME'],
				'PRODUCT_XML_ID' => $arDataProduct['PROPERTY_CML2_LINK_DETAIL_PICTURE'].'|'.$arDataProduct['PROPERTY_CML2_LINK_VALUE'],
				'NOTES' => $arDataProduct['PROPERTY_SHOP_ID_CODE']
			]);

			$res = $oBasket->save();

			if($res->isSuccess()){
				$arShopId = \CIBlockElement::GetProperty($iblockIdSku, $arProduct['ID'], [], ['CODE' => 'SHOP_ID'])->Fetch();
				DataCache::clearTag('product_list'.$arShopId['VALUE']);
			}
		}

		if ($res->isSuccess()){
			return ['current' => self::getBasketById($item->getId()), 'items' => self::getBasketUser()];
		} else {
			throw new SaleException('Error: '.implode(', ', $res->getErrorMessages()));
		}
	}

	/**
	 * @method getByProduct
	 * @param $product
	 *
	 * @return Sale\BasketItem|bool
	 */
	public static function getByProduct($product)
	{
		$oBasket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId);

		return $oBasket->getExistsItem('catalog', $product);
	}

	/**
	 * @method getBasketById
	 * @param $id
	 *
	 * @return array
	 */
	public static function getBasketById($id)
	{
		return Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId)
			->getItemById($id)
			->getFieldValues();
	}

	/**
	 * @method getBasketUser
	 * @param array $data
	 *
	 * @return null
	 */
	public static function getBasketUser($data = [])
	{
		global $USER;

		$CUser = new \CUser();
		$arUserDelivery = $CUser->GetList($b, $o, ['=ID' => $USER->GetID()], ['SELECT' =>['UF_*']])->Fetch();

//		unset($_SESSION['BASKET_SHOP']);
		if (empty(self::$siteId))
			self::$siteId = Context::getCurrent()->getSite();
//		$_SESSION['addressChange'] = 'Y';
//		unset($_SESSION['addressChange']);
		if (is_null(self::$basket) || $data['RECALC']){
			/** @var \Bitrix\Main\HttpRequest $request */
			$request = \Bitrix\Main\Context::getCurrent()->getRequest();
			$result = null;


			$arShopCodes = [];
			$compilesProducts = [];
			$user = \CSaleBasket::GetBasketUserID();

			if ($data['FOR_ORDER']){
				$oBasket = Sale\Basket::loadItemsForFUser($user, self::$siteId)->copy();
			} else {
				$oBasket = Sale\Basket::loadItemsForFUser($user, self::$siteId);
			}

			/** @var Sale\BasketItem $basketItem */
			foreach ($oBasket->getBasketItems() as $basketItem) {


				$val = $basketItem->getFieldValues();
				$sumItem = $basketItem->getPrice() * $basketItem->getQuantity();

				$iblock = \AB\Iblock\Element::getIblockByElementId($val['PRODUCT_ID']);
				if($iblock == 0){
					continue;
				}

				$_SESSION['UL_BASKET_ITEMS'][$basketItem->getProductId()] = [
					'ID' => $basketItem->getId(),
					'QUANTITY' => $basketItem->getQuantity()
				];

				$arIblock = \CCatalogSku::GetInfoByIBlock($iblock);
				$val['SKU_PRODUCT_ID'] = $iblock;
				$productItem = \AB\Iblock\Element::getRow([
					'filter' => [
						'IBLOCK_ID' => $iblock,
						'=ID' => $val['PRODUCT_ID'],
						'SHOP.ACTIVE' => 'Y',
//						'PROPERTY.SHOP_ID.PROPERTY.NO_AVAILABLE' => 1,
					],
					'select' => [
						'PRODUCT_NAME' => 'PRODUCT.NAME',
						'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
						'SHOP_CODE' => 'PROPERTY.SHOP_ID.CODE',
						'SHOP_ACTIVE' => 'SHOP.ACTIVE',
						'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
						'PRODUCT_IB_ID' => 'PRODUCT.ID',
						'PRODUCT_CODE' => 'PRODUCT.CODE',
						'PRODUCT_IBLOCK' => 'PRODUCT.IBLOCK_ID',
						'PROPERTY.CML2_LINK.ID',
					],
					'runtime' => [
						new Entity\ReferenceField(
							'SHOP',
							\Bitrix\Iblock\ElementTable::getEntity(),
							['=this.PROPERTY.SHOP_ID.ID' => 'ref.ID']
						),
						new Entity\ReferenceField(
							'PRODUCT',
							\Bitrix\Iblock\ElementTable::getEntity(),
							['=this.PROPERTY.CML2_LINK.ID' => 'ref.ID', 'ref.IBLOCK_ID' => array('?i', $arIblock['PRODUCT_IBLOCK_ID'])]
						),
					],
				]);


				$resShop = \CIBlockElement::GetProperty(5, $productItem['SHOP_ID'], [], ['CODE' => 'NO_AVAILABLE'])->Fetch();

				if (intval($productItem['PRODUCT_PICTURE']) > 0){
					$productItem['IMG'] = \CFile::ResizeImageGet(
						$productItem['PRODUCT_PICTURE'],
						['width' => 60, 'height' => 75],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
						true
					);
				}

				$val['PRODUCT_ITEM'] = $productItem;
				$val['PRICE_FORMAT'] = Tools::formatPrice($sumItem);
				$val['QUANTITY'] = floatval($val['QUANTITY']);
				$val['DETAIL_PAGE'] = '/catalog/'.$productItem['PRODUCT_IB_ID'];

				$comment = Model\PropsTable::getRow([
					'select' => ['*'],
					'filter' => ['=BASKET_ID' => $val['ID'], '=NAME' => 'COMMENT'],
				]);
				$obReplaceRow = Model\PropsTable::getList([
					'select' => ['*'],
					'filter' => ['=BASKET_ID' => $val['ID'], '=NAME' => 'REPLACE'],
				]);

				$val['COMMENTARY'] = $comment['VALUE'];

				$replaceRow = null;
				while ($r = $obReplaceRow->fetch()) {
					$replaceRow[$r['ID']] = $r;
				}
				if (!is_null($replaceRow)){
					$val['replace'] = $replaceRow;
				}

				$iblockShop = Element::getIblockByIdElement($productItem['SHOP_ID']);
				$arShop = Element::getRow([
					'select' => ['NAME', 'DETAIL_PICTURE'],
					'filter' => ['IBLOCK_ID' => $iblockShop, '=ID' => $productItem['SHOP_ID']],
				]);
				$shopImg = null;
				if (intval($arShop['DETAIL_PICTURE']) > 0){
					$shopImg = \CFile::ResizeImageGet(
						$arShop['DETAIL_PICTURE'],
						['width' => 120, 'height' => 120],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
						true
					);
				}
				if ($resShop['VALUE'] == 1){
					$val['NO_BUY_IN_SHOP'] = true;
				}

				if (!in_array($productItem['SHOP_ID'], $_SESSION['REGIONS']['SHOP_ID'])){

					$val['NO_BUY_IN_SHOP'] = true;

					if ($data['del_order']){
						$basketItem->delete();
						$oBasket->save();
						continue;
					}

					/*if ($_SESSION['addressChange'] == 'Y'){
						$row = static::getAnalog($val['PRODUCT_ITEM']['PRODUCT_IB_ID'], $val['PRODUCT_ID'], $val['SKU_PRODUCT_ID']);
						if ($row && !is_null($row)){
							$arPrice = \CPrice::GetBasePrice($row['ID']);
							$val['PRICE'] = $arPrice['PRICE'];
							$val['PRICE_FORMAT'] = Tools::formatPrice($arPrice['PRICE']);
							unset($val['NO_BUY_IN_SHOP']);

							$item = $oBasket->createItem('iblock', $row['ID']);
							$item->setFields([
								'PRODUCT_ID' => $row['ID'],
								'PRICE' => $val['PRICE'],
								'CURRENCY' => 'RUB',
								'QUANTITY' => $val['QUANTITY'],
								'NAME' => $val['PRODUCT_ITEM']['PRODUCT_NAME'],
							]);
							$basketItem->delete();
							$oBasket->save();
						}
					}*/
				} else {
					$compilesProducts[] = $val['PRODUCT_ITEM']['PRODUCT_IB_ID'];
				}

				if ($resShop['VALUE'] == 0){
					$val['NO_ACTIVE'] = true;
				}

				$result['ITEMS'][$productItem['SHOP_CODE']]['SHOP_ID'] = $productItem['SHOP_ID'];
				$result['ITEMS'][$productItem['SHOP_CODE']]['SHOP_CODE'] = $productItem['SHOP_CODE'];
				$result['ITEMS'][$productItem['SHOP_CODE']]['NAME'] = $arShop['NAME'];
				$result['ITEMS'][$productItem['SHOP_CODE']]['PICTURE'] = $shopImg;

				$result['ITEMS'][$productItem['SHOP_CODE']]['INFO'] = '';


				//$basketItem->getField('NOTES')
//				PR($val);

//				if($val['NOTES'] == $productItem['SHOP_CODE']){}

				$result['ITEMS'][$productItem['SHOP_CODE']]['BASKET'][$val['ID']] = $val;

				if ($resShop['VALUE'] == 1){
					$result['ITEMS'][$productItem['SHOP_CODE']]['NO_ACTIVE'] = true;
				}
			}

			$result['SUM_RAW'] = 0;
//			$result['FORMAT_CNT'] = Tools::formatContProduct($oBasket->count());

			foreach ($result['ITEMS'] as $shopId => &$arItem) {

				$arShopCodes[$arItem['SHOP_CODE']][] = $arItem['SHOP_ID'];

				$time = \CIBlockElement::GetProperty(5, $arItem['SHOP_ID'], [], ['CODE' => 'DELIVERY_TIME'])->Fetch();
				$timeIds = [];
				$arTimeDays = $arTimeDaysIds = [];

				if((int)$time['VALUE'] != 0) {
					//LINK_IBLOCK_ID
					$obTimeDays = SectionTable::getList([
						'filter' => ['IBLOCK_ID' => $time['LINK_IBLOCK_ID'], 'ACTIVE' => 'Y', '=IBLOCK_SECTION_ID' => $time['VALUE']],
						'select' => ['ID','NAME','CODE']
					]);
					while ($rs = $obTimeDays->fetch()){
						$arTimeDays[] = $rs;
						$arTimeDaysIds[] = $rs['ID'];
					}
				}

				$arTimes = [];
				if(count($arTimeDaysIds) > 0){
					$obTimesElement = Element::getList([
						'select' => ['NAME', 'TIME_FROM' => 'PROPERTY.TIME_FROM', 'TIME_TO' => 'PROPERTY.TIME_TO'],
						'filter' => ['IBLOCK_ID' => 8, '@IBLOCK_SECTION_ID' => $arTimeDaysIds],
						'order' => ['PROPERTY.TIME_FROM' => 'ASC']
					]);

					while ($timer = $obTimesElement->fetch()) {
						$dateNow = new \DateTime();
						if (!empty($timer['TIME_FROM'])){
							$timer['TIME_FROM'] = explode(':', $timer['TIME_FROM']);
							if(count($timer['TIME_FROM']) == 1){
								$timer['TIME_FROM'] = explode('-', $timer['TIME_FROM'][0]);
							}

							$timerFrom = new \DateTime($dateNow->format('d.m.Y').' '.$timer['TIME_FROM'][0].':'.($timer['TIME_FROM'][1] ? $timer['TIME_FROM'][1] : '00'));

							$timer['TIME_TO'] = explode(':', $timer['TIME_TO']);
							if(count($timer['TIME_TO']) == 1){
								$timer['TIME_TO'] = explode('-', $timer['TIME_TO'][0]);
							}
							$timerTo = new \DateTime($dateNow->format('d.m.Y').' '.$timer['TIME_TO'][0].':'.($timer['TIME_TO'][1] ? $timer['TIME_TO'][1] : '00'));
							$arTimes[] = $timerFrom->format('H:i').' - '.$timerTo->format('H:i');
						}
					}

					$arTimes = array_unique($arTimes);
				}

				$arItem['DELIVERY_TIME'] = $arTimes;

				$shopSum = $cntInShop = 0;

				if (!$arItem['NO_BUY_IN_SHOP']){
					foreach ($arItem['BASKET'] as $k => $item) {

//						dump($item);

						if ($arItem['NO_ACTIVE'] == true){
							$item['NO_BUY_IN_SHOP'] = true;
//							$result['ITEMS'][$arItem['SHOP_CODE']]['BASKET'][$k]['NO_SHOW_TIMES'] = true;
							$result['ITEMS'][$arItem['SHOP_CODE']]['BASKET'][$k]['NO_BUY_IN_SHOP'] = true;
						}

						if (!$item['NO_BUY_IN_SHOP']){
							$cntInShop++;
							$shopSum += $item['PRICE'] * $item['QUANTITY'];
						} else {
//							$result['ITEMS'][$arItem['SHOP_CODE']]['NO_SHOW_TIMES'] = true;
						}
					}

					$result['ITEMS'][$arItem['SHOP_CODE']]['COUNT_IN_SHOP'] = $cntInShop;
					$result['ITEMS'][$arItem['SHOP_CODE']]['COUNT_IN_SHOP_FORMAT'] = Tools::formatContProduct($cntInShop);
					$arItem['SUM_IN_SHOP'] = $shopSum;
					$arItem['SUM_IN_SHOP_FORMAT'] = Tools::formatPrice($shopSum);
					$result['SUM_RAW'] += $shopSum;
					$result['FORMAT_CNT'] += $cntInShop;

					if ($shopSum < 2000){
						$result['ITEMS'][$arItem['SHOP_CODE']]['INFO'] = sprintf(
							'Заказ на %sр. Добавьте товаров до %sр и сэкономьте на доставке %sр',
							$arItem['SUM_IN_SHOP_FORMAT'],
							'2000',
							'300'
						);
					}
				} else {
//					$result['ITEMS'][$arItem['SHOP_CODE']]['NO_SHOW_TIMES'] = true;
				}
				$NO_SHOW_TIMES = 0;
				foreach ($arItem['BASKET'] as $k => $item) {
					if ($item['NO_BUY_IN_SHOP'])
						$NO_SHOW_TIMES++;
				}

				if ($NO_SHOW_TIMES >= count($arItem['BASKET'])){
					$result['ITEMS'][$arItem['SHOP_CODE']]['NO_SHOW_TIMES'] = true;
				} else {
					unset($result['ITEMS'][$arItem['SHOP_CODE']]['NO_SHOW_TIMES']);
				}


				$_SESSION['BASKET_SHOP'][$arItem['SHOP_ID']] = ['SUM' => $shopSum, 'SHOP_CODE' => $arItem['SHOP_CODE']];

				$result['FORMAT_CNT'] = Tools::formatContProduct(intval($result['FORMAT_CNT']));

				/** Бесплатная доставка на первый активный магазин в списке, если у покупателя есть такая возможность */
				if($NO_SHOW_TIMES == 0 && $arUserDelivery['UF_FREE_DELIVERY'] == 1 && is_null(self::$deliveryFree)){
					$result['ITEMS'][$shopId]['FREE_DELIVERY'] = true;
					self::$deliveryFree = $shopId;
				}
			}

			$result['SUM'] = \SaleFormatCurrency($result['SUM_RAW'], 'RUB', true);

			$minSum = 1000;
			$result['BUY_ORDER'] = true;

			foreach ($result['ITEMS'] as &$ITEM) {

				$ITEM['SHOP_TIMES'] = self::getInstance()->getNearDelivery($ITEM['SHOP_ID']);

				foreach ($ITEM['SHOP_TIMES']['DELIVERY_TIME'][0]['ITEMS'] as $time) {
					if($time['DISABLED'] === false){
						$ITEM['NEAR_DELIVERY'] = $time['PROPERTY_TIME_FROM_VALUE'].' - '.$time['PROPERTY_TIME_TO_VALUE'];
						break;
					}
				}

				if(empty($ITEM['NEAR_DELIVERY'])){
					$time = $ITEM['SHOP_TIMES']['DELIVERY_TIME'][1]['ITEMS'][0];
					$ITEM['NEAR_DELIVERY'] = 'Завтра, '.$time['PROPERTY_TIME_FROM_VALUE'].' - '.$time['PROPERTY_TIME_TO_VALUE'];
				}

				if($ITEM['SUM_IN_SHOP'] < $minSum){
					$result['BUY_ORDER'] = false;
					break;
				}
			}

			$result['DAYS_LIST'] = Tools::generateDaysForDelivery();


			foreach ($result['ITEMS'] as $c => &$ITEM) {
				$noActiveProductNow = [];
				foreach ($ITEM['BASKET'] as $id => $itemBasket) {
					if($itemBasket['NO_BUY_IN_SHOP'] === true){
						$noActiveProductNow[$id] = $itemBasket['PRODUCT_ITEM']['PRODUCT_IB_ID'];
					}
				}
				foreach ($noActiveProductNow as $id => $val){
					unset($ITEM['BASKET'][$id]);
				}

				if(count($ITEM['BASKET']) == 0){
					unset($result['ITEMS'][$c]);
				}
			}




			unset($_SESSION['addressChange']);

			self::$basket = $result;
		}

		return self::$basket;
	}

	public function getNearDelivery($shopId)
	{
		$ShopDetail = new \UL\Shops\ShopDetail();
		return $ShopDetail->getShopInfo($shopId);
	}

	public static function getBasketForOrder()
	{
		$arBasket = self::getBasketUser();
		foreach ($arBasket['ITEMS'] as $k => $item) {
			if ($item['NO_SHOW_TIMES'] === true){
				unset($arBasket['ITEMS'][$k]);
			}
		}

		return $arBasket;
	}

	/**
	 * @method getAnalog
	 * @param $productId
	 * @param $skuId
	 * @param $iblock
	 *
	 * @return array|false
	 * @throws SaleException
	 */
	protected static function getAnalog($productId, $skuId, $iblock)
	{
		if (intval($productId) == 0)
			throw new SaleException('analog $productId in null');

		Loader::includeModule('ab.iblock');
		$oElements = \AB\Iblock\Element::getList([
			'select' => ['ID', 'IBLOCK_ID', 'CML2_LINK' => 'PROPERTY.CML2_LINK.ID'],
			'filter' => [
				'IBLOCK_ID' => $iblock,
				'=PROPERTY.CML2_LINK.ID' => $productId,
				'!=ID' => $skuId,
				'=PROPERTY.SHOP_ID.ID' => $_SESSION['REGIONS']['SHOP_ID'],
			],
		]);
		$result = $oElements->fetch();

//		PR($result);

		return $result;
	}

	/**
	 * @method replaceItemAction
	 * @param array $data
	 *
	 * @return int
	 * @throws SaleException
	 */
	public function replaceItemAction($data = [])
	{
		$arItem = $data['current'];
		$replace = $data['replace'];

		$save = [
			'BASKET_ID' => $arItem['ID'],
			'NAME' => 'REPLACE',
			'CODE' => $replace['ID'],
			'VALUE' => $replace['PRODUCT_NAME'],
			'SORT' => 200,
		];

		$basketId = intval($save['BASKET_ID']);
		if (intval($basketId) == 0){
			throw new SaleException('Нет ИД корзины');
		}
		if (strlen($save['NAME']) == 0){
			throw new SaleException('Нет названия сваойства');
		}

		$row = Model\PropsTable::getRow([
			'select' => ['ID'],
			'filter' => ['=BASKET_ID' => $basketId, 'NAME' => $data['NAME'], '=CODE' => $replace['ID']],
		]);
		if (is_null($row)){
			$result = Model\PropsTable::add($save);
		} else {
			$result = Model\PropsTable::update($row['ID'], $save);
		}

		if (!$result->isSuccess()){
			throw new SaleException(implode(', ', $result->getErrorMessages()));
		}

		return $result->getId();

		/*$user = \CSaleBasket::GetBasketUserID();
		$oBasket = Sale\Basket::loadItemsForFUser($user, self::$siteId);
		$index = $oBasket->getIndexById($arItem['ID']);
		$oBasket->deleteItem($index);
		$oBasket->save();

		return $this->addBasketById([
			'PRODUCT' => $replace['ID'],
			'QUANTITY' => $arItem['QUANTITY'],
		]);*/
	}

	/**
	 * @method deleteReplace
	 * @param array $data
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function deleteReplace($data = [])
	{
		$result = Model\PropsTable::delete($data['id']);
		if ($result->isSuccess()){
			return true;
		} else {
			throw new \Exception(implode(', ', $result->getErrorMessages()));
		}
	}

	public function searchReplace($data = [])
	{
		$oIblocks = \Bitrix\Iblock\IblockTable::getList([
			'select' => ['ID'],
			'filter' => ['IBLOCK_TYPE_ID' => 'catalog'],
		]);
		$arIblocks = [];
		while ($block = $oIblocks->fetch()) {
			$arIblocks[] = $block['ID'];
		}
		$q = $data['q'];
//		$q = 'агуш';

		$CSearch = new \CSearch();
		$CSearch->Search(
			[
				'QUERY' => $q,
				'SITE_ID' => self::$siteId,
				'=MODULE_ID' => 'iblock',
				"!ITEM_ID" => "S%",
				'=PARAM2' => $arIblocks,
				'CHECK_DATES' => 'Y',
			],
			['RANK' => 'DESC', 'TITLE_RANK' => 'DESC']
		);
		$searchRes = [];
		$i = 0;
		while ($arResult = $CSearch->GetNext()) {
			if ($i == 30)
				break;

			if (intval($arResult['ITEM_ID']) == 0){
				continue;
			}
			$arIblock = \CCatalogSku::GetInfoByIBlock($arResult['PARAM2']);
			$arSku = \AB\Iblock\Element::getRow([
				'filter' => [
					'IBLOCK_ID' => $arIblock['IBLOCK_ID'],
					'PROPERTY.SHOP_ID.ID' => $_SESSION['REGIONS']['SHOP_ID'],
					'PROPERTY.CML2_LINK.ID' => intval($arResult['ITEM_ID']),
				],
				'select' => [
					'ID',
					'PRODUCT_NAME' => 'PRODUCT.NAME',
					'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
					'SHOP_CODE' => 'PROPERTY.SHOP_ID.CODE',
					'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
					'PRODUCT_IB_ID' => 'PRODUCT.ID',
					'PRODUCT_CODE' => 'PRODUCT.CODE',
					'PRODUCT_IBLOCK' => 'PRODUCT.IBLOCK_ID',
					'CML2_LINK' => 'PROPERTY.CML2_LINK.ID',
				],
				'limit' => 20,
				'order' => ['TIMESTAMP_X' => 'DESC'],
				'runtime' => [
					new Entity\ReferenceField(
						'SHOP',
						\Bitrix\Iblock\ElementTable::getEntity(),
						['=this.PROPERTY.SHOP_ID.ID' => 'ref.ID']
					),
					new Entity\ReferenceField(
						'PRODUCT',
						\Bitrix\Iblock\ElementTable::getEntity(),
						['=this.PROPERTY.CML2_LINK.ID' => 'ref.ID', 'ref.IBLOCK_ID' => array('?i', $arIblock['PRODUCT_IBLOCK_ID'])]
					),
				],
			]);
			if (is_null($arSku) || empty($arSku)){
				continue;
			}
			if (intval($arSku['PRODUCT_PICTURE']) > 0){
				$arSku['IMG'] = \CFile::ResizeImageGet(
					$arSku['PRODUCT_PICTURE'],
					array('width' => 124, 'height' => 124),
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}
			$arSku['PRICE'] = \CPrice::GetBasePrice($arSku['ID']);
			$arSku['PRICE']['FORMAT_VALUE'] = Tools::formatPrice($arSku['PRICE']['PRICE']);

			$arResult['SKU'] = $arSku;

			$searchRes[] = $arResult;
			$i++;
		}

		return $searchRes;
	}

	public function getReplacementAction($arItem)
	{
		$iblock = \AB\Iblock\Element::getIblockByElementId($arItem['PRODUCT_ID']);
//		$arIblock = \CCatalogSku::GetInfoByIBlock($iblock);


		$filter = [
			'IBLOCK_ID' => $iblock, '!=ID' => $arItem['PRODUCT_ID'],
			'PROPERTY.SHOP_ID.ID' => $_SESSION['REGIONS']['SHOP_ID'],
		];

		$arSkuProduct = \Bitrix\Iblock\ElementTable::getRow([
			'select' => ['ID', 'IBLOCK_SECTION_ID', 'IBLOCK_ID'],
			'filter' => [
				'=ID' => $arItem['PRODUCT_ITEM']['PRODUCT_IB_ID'],
				'IBLOCK_ID' => $arItem['PRODUCT_ITEM']['PRODUCT_IBLOCK']
			],
		]);

		if (!is_null($arSkuProduct)){
			$filter['PROPERTY.CML2_LINK.IBLOCK_SECTION_ID'] = $arSkuProduct['IBLOCK_SECTION_ID'];
		}

		$obProduct = \AB\Iblock\Element::getList([
			'filter' => $filter,
			'select' => [
				'ID',
				'PRODUCT_NAME' => 'PRODUCT.NAME',
				'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
				'SHOP_CODE' => 'PROPERTY.SHOP_ID.CODE',
				'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
				'PRODUCT_IB_ID' => 'PRODUCT.ID',
				'PRODUCT_CODE' => 'PRODUCT.CODE',
				'PRODUCT_IBLOCK' => 'PRODUCT.IBLOCK_ID',
				'CML2_LINK' => 'PROPERTY.CML2_LINK.ID',
			],
			'limit' => 20,
			'order' => ['TIMESTAMP_X' => 'DESC'],
			'runtime' => [
				new Entity\ReferenceField(
					'SHOP',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.SHOP_ID.ID' => 'ref.ID']
				),
				new Entity\ReferenceField(
					'PRODUCT',
					\Bitrix\Iblock\ElementTable::getEntity(),
					['=this.PROPERTY.CML2_LINK.ID' => 'ref.ID']
				),
			],
		]);

		$arProducts = [];
		while ($product = $obProduct->fetch()) {
			if (intval($product['PRODUCT_PICTURE']) > 0){
				$product['IMG'] = \CFile::ResizeImageGet(
					$product['PRODUCT_PICTURE'],
					array('width' => 124, 'height' => 124),
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}

			$product['PRICE'] = \CPrice::GetBasePrice($product['ID']);
			$product['PRICE']['FORMAT_VALUE'] = Tools::formatPrice($product['PRICE']['PRICE']);

			$arProducts[] = $product;
		}

		return $arProducts;
	}

	public function basketUserAction()
	{
		return static::getBasketUser();
	}

	/**
	 * @method recalcBasketItem
	 * @param $basket
	 *
	 * @return null
	 */
	public static function recalcBasketItem($basket)
	{
		$result = null;
		$basketCollection = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId);
		$item = $basketCollection->getItemById($basket['ID']);
		$item->setField('QUANTITY', $basket['QUANTITY']);
		if ($basketCollection->save()->isSuccess()){
			$result = self::getBasketUser(['RECALC' => true]);
		}

		return $result;
	}

	/**
	 * @method delItem
	 * @param $data
	 *
	 * @return null
	 */
	public static function delItem($data)
	{
		$result = null;
		$basketCollection = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), self::$siteId);
		$item = $basketCollection->getItemById($data['ID']);
		$productId = $item->getFieldValues()['PRODUCT_ID'];
		$item->delete();
		if ($basketCollection->save()->isSuccess()){
			unset($_SESSION['UL_BASKET_ITEMS']);
			$result = self::getBasketUser(['RECALC' => true]);
		}

		return $result;
	}

	/**
	 * @method saveProperty
	 * @param array $data
	 *
	 * @return bool|Entity\Result
	 * @throws SaleException
	 */
	public function saveProperty($data = [])
	{
		$bAllResult = $data['B_ALL_RES'];
		unset($data['B_ALL_RES']);

		$basketId = intval($data['BASKET_ID']);
		if (intval($basketId) == 0){
			throw new SaleException('Нет ИД корзины');
		}
		if (strlen($data['NAME']) == 0){
			throw new SaleException('Нет названия сваойства');
		}

		$row = Model\PropsTable::getRow([
			'select' => ['ID'],
			'filter' => ['=BASKET_ID' => $basketId, 'NAME' => $data['NAME']],
		]);
		if (is_null($row)){
			$result = Model\PropsTable::add($data);
		} else {
			$result = Model\PropsTable::update($row['ID'], $data);
		}

		if (!$result->isSuccess()){
			throw new SaleException(implode(', ', $result->getErrorMessages()));
		}

		if ($bAllResult === true){
			return $result;
		}

		return $result->isSuccess();
	}

	public function addToFavorite($data = [])
	{
		$Favorite = new Favorite();

		return $Favorite->addToFavorite($data);
	}

	public function getFavorite($data)
	{
		$Favorite = new Favorite();

		return $Favorite->getFavorite($data);
	}
}
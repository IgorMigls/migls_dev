<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 02.08.2016
 * Time: 13:25
 */

namespace UL\Main\Services;

use Bitrix\Main\DB\Exception;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use AB\Iblock;
use PW\Tools\Debug;
use UL\Sale\Basket;
use UL\Tools;

class Favorite
{
	/**
	 * Favorite constructor.
	 */
	public function __construct()
	{
	}

	public function addToFavorite($data)
	{
		FavoriteTable::createTable();

		$id = intval($data['ID']);
		if ($id == 0)
			throw new \Exception('Нет ИД элемента');

		global $USER;

		if(!$USER->IsAuthorized()){
			throw new Exception('Для добавления в избранное нужно авторизоваться');
		}

		$save = [
			'ELEMENT_ID' => $id,
			'USER_ID' => $USER->GetID(),
		];

//		FavoriteTable::getEntity()->getConnection()->truncateTable(FavoriteTable::getTableName());
//		ListTable::getEntity()->getConnection()->truncateTable(ListTable::getTableName());

		$exist = $this->getFavorite($data);
		if(!is_null($exist)){
//			return FavoriteTable::delete($data['ID']);
			return true;
		}

		$res = FavoriteTable::add($save);
		if ($res->isSuccess()) {
			return $res->getId();
		} else {
			throw new \Exception(implode(',', $res->getErrorMessages()));
		}
	}

	public function getFavorite($data)
	{
		$id = intval($data['ID']);
		if ($id == 0)
			throw new \Exception('Нет ИД элемента');

		global $USER;

		$res = FavoriteTable::getRow([
			'select' => ['ID'],
			'filter' => ['ELEMENT_ID' => $id, 'USER_ID' => $USER->GetID()],
		]);

		return $res;
	}

	public function addList($data = [])
	{
		if (strlen($data['NAME']) == 0) {
			throw new \Exception('Нет названия для списка');
		}

		$row = ListTable::getRow([
			'filter' => ['NAME' => $data['NAME'],'USER_ID'=>self::getUser()->GetID()]
		]);
		if(!is_null($row)){
			throw new \Exception('Список с таким названием уже есть');
		}

		$res = ListTable::add(['NAME' => $data['NAME']]);

		if ($res->isSuccess()) {
			return ['ID' => $res->getId(), 'ITEMS' => self::getMyList()];
		} else {
			throw new \Exception(implode(',', $res->getErrorMessages()));
		}
	}

	public function getMyList($data = [])
	{
		$filter = [
			'=USER_ID' => self::getUser()->GetID()
		];
		if(intval($data['LIST']) > 0){
			$filter['=ID'] = $data['LIST'];
		}
		return ListTable::getList([
			'select' => ['*'],
			'filter' => $filter,
			'order' => ['NAME' => 'ASC'],
		])->fetchAll();
	}

	public static function getUser()
	{
		global $USER;
		return $USER;
	}

	/**
	 * @method getProducts
	 * @param array $data
	 *
	 * @return array
	 */
	public static function getProducts($data = [])
	{
		Loader::includeModule('ab.iblock');
		Loader::includeModule('catalog');

		\CBitrixComponent::includeComponentClass('ul:shop.list');
		$ShopList = new \UL\Shops\ShopList();
		foreach ($ShopList->getShops() as $shop){
			$arShop[] = $shop['ID'];
		}

		$result['ITEMS'] = null;
		$result['SUM'] = 0;
		$result['CNT'] = 0;

		$entity = FavoriteTable::getEntity();
		$entity->addField(new Entity\ReferenceField(
			'PRODUCT',
			Iblock\Element::getEntity(),
			['=this.ELEMENT_ID' => 'ref.ID']
		));

		$q = new Entity\Query($entity);
		$q->setSelect([
			'*',
			'PRODUCT_ID' => 'PRODUCT.ID',
			'PRODUCT_NAME' => 'PRODUCT.NAME',
			'PRODUCT_PICTURE' => 'PRODUCT.DETAIL_PICTURE',
			'IBLOCK_ID' => 'PRODUCT.IBLOCK_ID'
		]);
		$q->setFilter(['=USER_ID' => self::getUser()->GetID()]);
		if (intval($data['LIST']) > 0) {
			$q->addFilter('=LIST_ID', $data['LIST']);
		} else {
			$q->addFilter('=LIST_ID', null);
		}

		$siteId = \Bitrix\Main\Context::getCurrent()->getSite();
		$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteId);
		$quantityList = [];

		/** @var \Bitrix\Sale\BasketItem $item */
		foreach ($basket->getBasketItems() as $item){
			$quantityList[$item->getProductId()] = $item;
		}

		$obElements = $q->exec();
		while ($element = $obElements->fetch()){
			if(intval($element['PRODUCT_PICTURE']) > 0){
				$element['PICTURE'] = \CFile::ResizeImageGet(
					$element['PRODUCT_PICTURE'],
					['width'=>80, 'height'=>80],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}

			$element['IBLOCK_INFO'] = \CCatalogSku::GetInfoByProductIBlock($element['IBLOCK_ID']);

			$arSku = Iblock\Element::getList([
				'select' => [
					'PRICE' => 'CATALOG.PRICE',
					'ID','NAME','CODE',
					'SHOP_ID'=>'PROPERTY.SHOP_ID.ID',
					'SHOP_NAME' => 'PROPERTY.SHOP_ID.NAME',
					'SHOP_PICTURE' => 'PROPERTY.SHOP_ID.DETAIL_PICTURE',
				],
				'filter' => [
					'IBLOCK_ID'=>$element['IBLOCK_INFO']['IBLOCK_ID'],
					'ACTIVE' => 'Y',
//					'=PROPERTY.SHOP_ID.ID' => $arShop,
					'=PROPERTY.CML2_LINK.ID' => $element['PRODUCT_ID']
				],
				'runtime' => [
					new Entity\ReferenceField(
						'CATALOG',
						\Bitrix\Sale\ProductTable::getEntity(),
						['=this.ID' => 'ref.ID']
					)
				]
			])->fetchAll();

			if(count($arSku) == 1){
				$sku = $arSku[0];
				if(in_array($sku['SHOP_ID'], $arShop)){
					$sku['IN_THIS_SHOP'] = true;
				}
			} else {
				foreach ($arSku as $item) {
					if(in_array($item['SHOP_ID'], $arShop)){
						$sku = $item;
						$sku['IN_THIS_SHOP'] = true;
						break;
					}
				}
			}
			if(!$sku || empty($sku)){
				$sku = $arSku[0];
			}

			if(intval($sku['SHOP_PICTURE']) > 0){
				$sku['SHOP_PICTURE'] = \CFile::ResizeImageGet(
					$sku['SHOP_PICTURE'], array('width'=>100, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}

			$result['SUM'] += $sku['PRICE'];
			$sku['PRICE_FORMAT'] = Tools::formatPrice($sku['PRICE']);

			$element['SKU'] = $sku;

			$result['CNT'] ++;

			$element['IN_FAVORITE'] = 1;

			if(isset($quantityList[$sku['ID']])){
				$basketItem = $quantityList[$sku['ID']];
				if($basketItem instanceof \Bitrix\Sale\BasketItem){
					$element['BASKET_ID'] = $basketItem->getId();
					$element['BASKET_QUANTITY'] = $basketItem->getQuantity();
				}
			}

			$result['ITEMS'][] = $element;
		}

		$result['SUM_FORMAT'] = Tools::formatPrice($result['SUM']);
		$result['CNT_FORMAT'] = Tools::formatContProduct($result['CNT']);

		return $result;
	}

	public function remove($arItems)
	{
		foreach ($arItems as $item) {
			FavoriteTable::delete($item['ID']);
		}
	}

	public function addToList($data = [])
	{
		foreach ($data['ITEMS'] as $item) {
			FavoriteTable::update($item['ID'], ['LIST_ID' => $data['LIST']['ID']]);
		}
	}

	public function addAllToBasket($data = [])
	{
		$res = null;
		$Basket = new Basket();
		foreach ($data as $item) {
			$id = $item['SKU']['ID'];
			$res = $Basket->addBasketById(['QUANTITY' => 1, 'PRODUCT' => $id]);
		}

		return $res;

	}

	public function changeOneProduct($data = [])
	{
		$data = $data['item'];
		$ID = intval($data['ID']);

		if(intval($ID) == 0){
			throw new \Exception('Нет Ид товара');
		}

		$row = FavoriteTable::getRow([
			'select'=>['ID'],
			'filter' => ['=ID' => $ID]
		]);


		if(is_null($row)){
			$res = FavoriteTable::add(['ELEMENT_ID' => $data['ELEMENT_ID'], 'USER_ID' => self::getUser()->GetID()]);
			$result['IN_FAVORITE'] = 1;
			if($res->isSuccess()){
				$result['ID'] = $res->getId();
			}
		} else {
			FavoriteTable::delete($ID);
			$result['IN_FAVORITE'] = 0;
		}

		return $result;
	}

	public function deleteList($data = [])
	{
		$listId = intval($data['LIST']);
		if($listId == 0){
			throw new \Exception('Нет ИД списка');
		}
		$arItems = $data['ITEMS'];
		if(empty($arItems) || count($arItems) == 0){
			$oItems = FavoriteTable::getList([
				'select' => ['ID'],
				'filter' => ['=USER_ID' => self::getUser()->GetID(), '=LIST_ID' => $listId]
			]);
			while ($item = $oItems->fetch()){
				$arItems[] = $item;
			}
		}

//		$this->remove($arItems);
		foreach ($arItems as $item) {
			FavoriteTable::update($item['ID'], ['LIST_ID' => false]);
		}
		$res = ListTable::delete($listId);
		if($res->isSuccess()){
			return ['status' => 1];
		} else {
			return ['status' => 0];
		}
	}

	public function editList($data = [])
	{
		$listId = intval($data['CURRENT']['ID']);
		if($listId == 0){
			throw new \Exception('Нет ИД списка');
		}

		$res = ListTable::update($listId, ['NAME' => $data['NAME']]);
		if(!$res->isSuccess()){
			throw new \Exception(implode(', ', $res->getErrorMessages()));
		}
	}
}
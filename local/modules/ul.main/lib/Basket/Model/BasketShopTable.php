<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 23.10.17
 */

namespace UL\Main\Basket\Model;

use AB\Tools\Debug;
use AB\Tools\Helpers\MainDataManager;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main;
use Bitrix\Main\Entity\Event;
use Bitrix\Sale\Internals\BasketTable;
use UL\Main\OrderLogTable;

Main\Loader::includeModule('sale');

/**
 * Class BasketShopTable
 * @package UL\Main\Basket\Model
 */
class BasketShopTable extends MainDataManager
{
	protected static function getIndexes()
	{
		return [
			'ix_mig_basket_sku' => ['SKU_ID', 'SHOP_ID', 'FUSER_ID'],
			'ix_mig_basket_id' => ['BASKET_ID', 'FUSER_ID'],
			'ix_mig_basket_product' => ['PRODUCT_ID', 'FUSER_ID'],
		];
	}

	public static function getTableName()
	{
		return 'mig_basket_shop';
	}

	/**
	 * @method getMap
	 * @return array
	 * @throws Main\ObjectException
	 */
	public static function getMap()
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', [
				'autocomplete' => true,
				'primary' => true
			]),
			'BASKET_ID' => new Main\Entity\IntegerField('BASKET_ID'),
			'SKU_ID' => new Main\Entity\IntegerField('SKU_ID'),
			'PRODUCT_ID' => new Main\Entity\IntegerField('PRODUCT_ID'),
			'SHOP_ID' => new Main\Entity\IntegerField('SHOP_ID'),
			'SHOP_CODE' => new Main\Entity\StringField('SHOP_CODE'),
			'SHOP' => new Main\Entity\ReferenceField(
				'SHOP',
				ElementTable::getEntity(),
				['=this.SHOP_ID' => 'ref.ID']
			),
			'IMG' => new Main\Entity\IntegerField('IMG'),
			'AREAL_ID' => new Main\Entity\IntegerField('AREAL_ID'),
			'PRICE' => new Main\Entity\FloatField('PRICE'),
			'QUANTITY' => new Main\Entity\FloatField('QUANTITY'),
			'SUM' => new Main\Entity\ExpressionField(
				'SUM',
				'%s * %s',
				['QUANTITY', 'PRICE']
			),
			'FUSER_ID' => new Main\Entity\IntegerField('FUSER_ID'),
			'FUSER' => new Main\Entity\ReferenceField(
				'FUSER',
				'Bitrix\Sale\Internals\Fuser',
				array('=this.FUSER_ID' => 'ref.ID'),
				array('join_type' => 'INNER')
			),
			'NAME' => new Main\Entity\StringField('NAME'),
			'DATE_INSERT' => new Main\Entity\DatetimeField('DATE_INSERT', [
				'default_value' => new Main\Type\DateTime(),
			]),
			'DATE_UPDATE' => new Main\Entity\DatetimeField('DATE_INSERT', [
				'default_value' => new Main\Type\DateTime(),
			]),
			'WEIGHT' => new Main\Entity\FloatField('WEIGHT'),
			'CAN_BUY' => new Main\Entity\IntegerField('CAN_BUY',[
				'default_value' => 1
			]),
			'PRODUCT_IBLOCK_ID' => new Main\Entity\IntegerField('PRODUCT_IBLOCK_ID'),
			'SKU_IBLOCK_ID' => new Main\Entity\IntegerField('SKU_IBLOCK_ID'),
			'REPLACE' => new Main\Entity\TextField('REPLACE', [
				'save_data_modification' => function(){
					return array(
						function($value){
							try{
								return Main\Web\Json::encode($value);
							} catch (Main\ArgumentException $e){
								return null;
							}
						}
					);
				},
				'fetch_data_modification' => function(){
					return array(
						function($value){
							try{
								return Main\Web\Json::decode($value);
							} catch (Main\ArgumentException $e){
								return null;
							}
						}
					);
				}
			]),
			'COMMENT' => new Main\Entity\StringField('COMMENT'),
			'BASKET' => new Main\Entity\ReferenceField(
				'BASKET',
				BasketTable::getEntity(),
				['=this.BASKET_ID' => 'ref.ID']
			)
		];
	}

	/**
	 * @method getBasketBitrix
	 * @param $basketId
	 *
	 * @return array|null
	 */
	public static function getBasketBitrix($basketId)
	{
		if((int)$basketId == 0)
			return null;

		return parent::getRow([
			'filter' => ['=BASKET_ID' => $basketId]
		]);
	}

	public static function onUpdate(Event $event)
	{
//		OrderLogTable::createTable();

		$result = new Main\Entity\Result();
		$fields = $event->getParameters()['fields'];
		$id = $event->getParameter('primary')['ID'];

		$row = parent::getRow([
			'select' => [
				'ID', 'QUANTITY', 'PRODUCT_ID', 'SHOP_ID', 'SUM', 'PRICE', 'FUSER_ID', 'REPLACE',
				'BASKET_ORDER' => 'BASKET.ORDER_ID',
				'BASKET_ID'
			],
			'filter' => ['=ID' => $id]
		]);

		if((int)$row['BASKET_ID'] > 0){

			try{
				$oldReplaces = Main\Web\Json::decode($fields['REPLACE']);
			} catch (Main\ArgumentException $e) {
				$oldReplaces = [];
			}
			try{
				$newReplaces = Main\Web\Json::decode($row['REPLACE']);
			} catch (Main\ArgumentException $e) {
				$newReplaces = [];
			}

			if($oldReplaces['ID'] != $newReplaces['ID'] || count($oldReplaces) == 0){

				$logOrder = OrderLogTable::getFieldsByOrder($row['BASKET_ORDER']);
				$basketData = $logOrder['BASKET_DATA'];

				if(is_array($basketData['REPLACES'])){
					$basketData['REPLACES'] = array_map(function ($el){
						$el['DELETED'] = 1;
						return $el;
					}, $basketData['REPLACES']);
				}
				$basketData['REPLACES'][] = $newReplaces;

				if(is_null($logOrder)){
					OrderLogTable::add([
						'ORDER_ID' => $row['BASKET_ORDER'],
						'TYPE' => OrderLogTable::TYPE_REPLACE_ADD,
						'BASKET_DATA' => $basketData
					]);
				} else {
					OrderLogTable::update($logOrder['ID'], [
						'ORDER_ID' => $row['BASKET_ORDER'],
						'TYPE' => OrderLogTable::TYPE_REPLACE_ADD,
						'BASKET_DATA' => $basketData
					]);
				}
			}
		}

		return $result;
	}

	/**
	 * @method deleteByBitrixId
	 * @param null $id
	 *
	 * @return Main\Entity\DeleteResult|bool
	 */
	public static function deleteByBitrixId($id = null)
	{
		if((int)$id == 0)
			return false;

		$row = parent::getRow([
			'filter' => ['=BASKET_ID' => $id],
			'select' => ['ID']
		]);

		if(!is_null($row))
			return static::delete($row['ID']);

	}
}

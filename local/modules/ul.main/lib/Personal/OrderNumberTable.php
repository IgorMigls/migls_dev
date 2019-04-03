<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 07.08.17
 */

namespace UL\Main\Personal;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main;
use AB\Tools\Helpers\MainDataManager;
use Bitrix\Sale\Internals\OrderTable;

Main\Loader::includeModule('iblock');
Main\Loader::includeModule('sale');


class OrderNumberTable extends MainDataManager
{
	protected static function getIndexes()
	{
		return [
			'ix_ul_order_num_id' => ['ORDER_ID'],
			'ix_ul_order_num_user' => ['USER_ID'],
			'ix_ul_order_num_shop' => ['SHOP_ID'],
		];
	}

	public static function getTableName()
	{
		return 'ul_order_numbers';
	}

	public static function getMap()
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'ORDER_ID' => new Main\Entity\IntegerField('ORDER_ID'),
			'SHOP_ID' => new Main\Entity\IntegerField('SHOP_ID'),
			'USER_ID' => new Main\Entity\IntegerField('USER_ID', [
				'default_value'=>self::getUser()->GetID()
			]),
			'ACCOUNT_NUMBER' => new Main\Entity\StringField('ACCOUNT_NUMBER'),
			'SHOP' => new Main\Entity\ReferenceField(
				'SHOP',
				ElementTable::getEntity(),
				['=this.SHOP_ID' => 'ref.ID']
			),
			'ORDER' => new Main\Entity\ReferenceField(
				'ORDER',
				OrderTable::getEntity(),
				['=this.ORDER_ID' => 'ref.ID']
			)
		];
	}


	public static function getByOrderId($orderId, $select = ['*'])
	{
		return static::getRow([
			'select' => $select,
			'filter' => ['=ORDER_ID' => $orderId]
		]);
	}

	/**
	 * @method deleteByOrderId
	 * @param null $orderId
	 *
	 * @return Main\Entity\DeleteResult|bool
	 */
	public static function deleteByOrderId($orderId = null)
	{
		if((int)$orderId == 0)
			return false;

		$row = static::getByOrderId($orderId);
		if(!is_null($row)){
			return static::delete($row['ID']);
		}
	}
}
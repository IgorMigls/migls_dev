<?php namespace UL\Main;
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 14.04.2018
 */
use Bitrix\Sale\Fuser;
use Bitrix\Main;

class OrderLogTable extends MainDataManager
{
	const TYPE_BASKET_CHANGE = 'BASKET_CHANGE';
	const TYPE_PRODUCT_DELETE = 'PRODUCT_DELETE';
	const TYPE_PRODUCT_ADD = 'PRODUCT_ADD';
	const TYPE_REPLACE_ADD = 'REPLACE_ADD';
	const TYPE_REPLACE_DELETE = 'REPLACE_DELETE';

	/**
	 * @method getIndexes
	 * @return array
	 */
	protected static function getIndexes()
	{
		return [
			'ix_mig_order_log_ORDER' => ['ORDER_ID'],
		];
	}

	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'mig_order_log';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),
			'ORDER_ID' => new Main\Entity\IntegerField('ORDER_ID', ['required' => true]),
			'TYPE' => new Main\Entity\StringField('TYPE', ['default_value' => self::TYPE_BASKET_CHANGE]),
			'BASKET_DATA' => new Main\Entity\TextField('BASKET_DATA', ['serialized' => true]),
			'SUM' => new Main\Entity\FloatField('SUM')
		];
	}

	/**
	 * @method getFieldsByOrder
	 * @param $orderId
	 *
	 * @return array|null
	 */
	public static function getFieldsByOrder($orderId)
	{
		return parent::getRow([
			'select' => ['*'],
			'filter' => ['=ORDER_ID' => $orderId]
		]);
	}
}

<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 21.02.2018
 */

namespace UL\Main\Delivery;

use Bitrix\Main;
use AB\Tools\Helpers\MainDataManager;
use Bitrix\Main\Entity\Event;
use Bitrix\Sale\Internals\OrderTable;
use function is_null;

Main\Loader::includeModule('sale');

class ComplectationTable extends MainDataManager
{
	/**
	 * @method getIndexes
	 * @return array
	 */
	protected static function getIndexes(): array
	{
		return [
			'ix_m_order' => ['USER_ID', 'ORDER_ID'],
			'ix_m_user_order' => ['USER_ID'],
		];
	}

	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName(): string
	{
		return 'm_complect_orders';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap(): array
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', [
				'autocomplete' => true,
				'primary' => true,
			]),
			'USER_ID' => new Main\Entity\IntegerField('USER_ID', [
				'required' => true,
			]),
			'ORDER_ID' => new Main\Entity\IntegerField('ORDER_ID', [
				'required' => true,
			]),
			'DATE_CRATE' => new Main\Entity\DatetimeField('DATE_CRATE', [
				'default_value' => new Main\Type\DateTime(),
			]),
			'DATE_UPDATE' => new Main\Entity\DatetimeField('DATE_UPDATE', [
				'default_value' => new Main\Type\DateTime(),
			]),
			'ORDER' => new Main\Entity\ReferenceField(
				'ORDER',
				OrderTable::getEntity(),
				['=this.ORDER_ID' => 'ref.ID']
			),
			'USER' => new Main\Entity\ReferenceField(
				'USER',
				Main\UserTable::getEntity(),
				['=this.USER_ID' => 'ref.ID']
			),
			'PRODUCT_DATA' => new Main\Entity\TextField('PRODUCT_DATA', [
				'serialized' => true
			])
		];
	}

	/**
	 * @method onBeforeAdd
	 * @param Event $event
	 *
	 * @return Main\Entity\EventResult|void
	 * @throws Main\ArgumentException
	 */
	public static function onBeforeAdd(Event $event): Main\Entity\EventResult
	{
		$result = new Main\Entity\EventResult();
		$data = $event->getParameter("fields");

		$orderId = (int)$data['ORDER_ID'];
		if (self::isExist($orderId)){
			$result->addError(new Main\Entity\EntityError(
				'Этот заказ уже занят'
			));
		}

		return $result;
	}

	/**
	 * @method getByOrderId
	 * @param int|null $orderId
	 *
	 * @return array|null
	 * @throws Main\ArgumentException
	 */
	public static function getByOrderId(int $orderId = null)
	{
		$orderId = (int)$orderId;
		if ($orderId == 0)
			throw new Main\ArgumentException('Нет ид заказа', 'orderId');

		return static::getRow([
			'filter' => ['=ORDER_ID' => $orderId, '=USER_ID' => parent::getUser()->GetID()],
		]);
	}

	/**
	 * @method isExist
	 * @param int|null $orderId
	 *
	 * @return bool
	 * @throws Main\ArgumentException
	 */
	public static function isExist(int $orderId = null): bool
	{
		$row = static::getByOrderId($orderId);
		return is_null($row) ? false : true;
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

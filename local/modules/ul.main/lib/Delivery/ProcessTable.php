<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 25.03.18
 */

namespace UL\Main\Delivery;

use Bitrix\Main;
use AB\Tools\Helpers\MainDataManager;
use Bitrix\Sale\Internals\OrderTable;

Main\Loader::includeModule('sale');

class ProcessTable extends MainDataManager
{
	public static function getTableName()
	{
		return 'm_delivery_order';
	}

	public static function getMap()
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
		];
	}

}

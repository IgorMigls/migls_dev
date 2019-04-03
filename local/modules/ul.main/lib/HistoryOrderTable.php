<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 02.05.18
 */

namespace UL\Main;

use Bitrix\Main;

class HistoryOrderTable extends MainDataManager
{
	public static function getTableName()
	{
		return 'b_sale_order_change';
	}

	public static function getMap()
	{
		return [
			'ID' => new Main\Entity\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),
			'ORDER_ID' => new Main\Entity\IntegerField('ORDER_ID'),
			'TYPE' => new Main\Entity\StringField('TYPE'),
			'DATA' => new Main\Entity\TextField('DATA', ['serialized' => true]),
			'DATE_CREATE' => new Main\Entity\DatetimeField('DATE_CREATE'),
			'DATE_MODIFY' => new Main\Entity\DatetimeField('DATE_MODIFY'),
			'USER_ID' => new Main\Entity\IntegerField('USER_ID'),
			'ENTITY' => new Main\Entity\StringField('ENTITY'),
			'ENTITY_ID' => new Main\Entity\IntegerField('ENTITY_ID')
		];
	}

}

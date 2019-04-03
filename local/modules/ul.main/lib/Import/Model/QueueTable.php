<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.01.2017
 */

namespace UL\Main\Import\Model;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;
use UL\Main;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

Loader::includeModule('iblock');

class QueueTable extends Main\MainDataManager
{
	const QUEUE_IMPORT_IN_PROCESS = 'Y';
	const QUEUE_IMPORT_NOT_PROCESS = 'N';

	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_queue_price_import';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new Entity\IntegerField(
				'ID', [
					'primary' => true,
					'autocomplete' => true,
				]
			),
			new Entity\DatetimeField(
				'LAST_IMPORT'
			),
			new Entity\DatetimeField(
				'NEXT_IMPORT'
			),
			new Entity\BooleanField(
				'IN_PROCESS', [
					'values' =>[self::QUEUE_IMPORT_NOT_PROCESS, self::QUEUE_IMPORT_IN_PROCESS]
				]
			),
			new Entity\IntegerField(
				'SHOP_ID'
			),
			new Entity\StringField(
				'FILE'
			),
			new Entity\IntegerField(
				'USER_X', ['default_value' => parent::getUser()->GetID()]
			),
			new Entity\DatetimeField(
				'DATE_X', ['default_value' => new Type\DateTime()]
			),
			new Entity\ReferenceField(
				'SHOP',
				ElementTable::getEntity(),
				['=this.SHOP_ID' => 'ref.ID']
			),
		];
	}
}
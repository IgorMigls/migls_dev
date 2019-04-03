<?php
namespace UL\Import;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class RemainsTmpTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 't_remains_csv';
	}

	public static function getMap()
	{
		return [
			'ID' => new Entity\IntegerField('ID', array(
					'primary' => true,
					'autocomplete' => true,
				)
			),
			'BARCODE' => new Entity\StringField('BARCODE', array(
					'default_value' => '',
				)
			),
			'ARTICLE' => new Entity\StringField('ARTICLE', array(
					'default_value' => '',
				)
			),
			'QUANTITY' => new Entity\IntegerField('QUANTITY', array(
				'default_value' => 0,
			)),
			'PRICE' => new Entity\FloatField('PRICE', array(
				'default_value' => 0,
			)),
			'SHOP_ID' => new Entity\IntegerField('SHOP_ID', array(
				'default_value' => 0,
			)),
			'IBLOCK_ID' => new Entity\IntegerField('IBLOCK_ID', array('default_value' => 0)),
		];
	}

	public static function createTables()
	{
		$connect = self::getEntity()->getConnection();
		if ($connect->isTableExists(self::getTableName())) {
			$connect->dropTable(self::getTableName());
		}
		self::getEntity()->createDbTable();

		$connect->createIndex(self::getTableName(), 'ix_ul_tmp_remain_IBLOCK_ID', 'IBLOCK_ID');
		$connect->createIndex(self::getTableName(), 'ix_ul_tmp_remain_SHOP_ID', 'SHOP_ID');
	}
}
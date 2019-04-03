<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.01.2017
 */

namespace UL\Main\Import\Model;

use UL\Main\MainDataManager;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

class PriceTmpTable extends MainDataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_price_tmp_import';
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
			new Entity\StringField(
				'ARTICLE'
			),
			new Entity\StringField(
				'BARCODE'
			),
			new Entity\FloatField(
				'PRICE'
			),
			new Entity\IntegerField(
				'SHOP_ID'
			),
			new Entity\IntegerField(
				'QUANTITY'
			),
			new Entity\StringField(
				'NAME'
			)
		];
	}

}
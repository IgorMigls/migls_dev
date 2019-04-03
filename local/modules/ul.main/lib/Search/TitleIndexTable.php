<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 14.07.2017
 */

namespace UL\Main\Search;

use Bitrix\Main;
use UL\Main\MainDataManager;

class TitleIndexTable extends MainDataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_search_title_index';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new Main\Entity\IntegerField(
				'ID',[
					'primary' => true,
					'autocomplete' => true,
				]
			),
			new Main\Entity\IntegerField(
				'ITEM_ID'
			),
			new Main\Entity\IntegerField(
				'IBLOCK_ID'
			),
			new Main\Entity\TextField(
				'TEXT'
			)
		];
	}
}
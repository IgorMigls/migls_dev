<?php namespace Soft;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Entity;

/**
 * Class IblockElementTable
 * @package Alvitek\Blocks
 */
class IblockElementTable extends ElementTable
{
	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		$map = parent::getMap();

		$map['PREVIEW_IMG'] = new Entity\ReferenceField(
			'PREVIEW_IMG',
			FileTable::getEntity(),
			['=this.PREVIEW_PICTURE'=>'ref.ID']
		);
		$map['DETAIL_IMG'] = new Entity\ReferenceField(
			'DETAIL_IMG',
			FileTable::getEntity(),
			['=this.DETAIL_PICTURE'=>'ref.ID']
		);

		return $map;
	}
}
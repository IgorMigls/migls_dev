<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Main\Entity;
use Bitrix\Iblock\SectionTable;

class Section extends SectionTable
{

	private static $iblockId = null;

	public static function getEntity($iblockId = null)
	{
		if(intval($iblockId) > 0){
			self::$iblockId = $iblockId;
		}

		if(intval(self::$iblockId) == 0){
			return parent::getEntity();
		} else {
			return self::compile();
		}
	}

	public static function compile()
	{
		$name = 'Section'.self::$iblockId;
		$fullName = '\\'.__NAMESPACE__.'\\'.$name;
		if(Entity\Base::isExists($fullName)){
			return Entity\Base::getInstance($fullName);
		} else {
			return Entity\Base::compileEntity($name, parent::getMap(), [
				'table_name' => parent::getTableName(),
				'namespace' => __NAMESPACE__,
				'uf_id' =>  "IBLOCK_".self::$iblockId."_SECTION"
			]);
		}
	}

	public static function getList(array $parameters = array())
	{
		if((int)$parameters['filter']['IBLOCK_ID'] > 0){
			self::$iblockId = $parameters['filter']['IBLOCK_ID'];
		}
		return parent::getList($parameters);
	}

	public static function query($iblockId = null)
	{
		$query = new Entity\Query(static::getEntity($iblockId));

		return $query;
	}


}
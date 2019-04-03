<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 20.04.2016
 * Time: 14:22
 */

namespace Soft\Util;


class Util
{
	/**
	 * @method clearCacheOnProductId
	 * @param int $id
	 */
	public static function clearCacheOnElementId($id = 0)
	{
		if($id > 0){
			$CacheTag = new \Bitrix\Main\Data\TaggedCache();
			$CacheTag->clearByTag('element_'.$id);
		}
	}

	/**
	 * @method clearIBlockMetaCache
	 * @param $arFields
	 */
	public static function clearIBlockMetaCache(&$arFields)
	{
		$CacheTag = new \Bitrix\Main\Data\TaggedCache();
		$CacheTag->clearByTag('pw_iblock_meta_props_'.$arFields['IBLOCK_ID']);
	}
}
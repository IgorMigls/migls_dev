<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 19.07.2016
 * Time: 17:02
 */

namespace UL\Handlers;

use UL\Tools;
use Bitrix\Main\Loader;
use AB\Iblock;

Loader::includeModule('ab.iblock');

class IblockElements
{

	const SHOP_IB = 5;
	const REMAIN_TYPE_IB = 'remains';

	/**
	 * @method clearElementCache
	 * @param $arFields
	 */
	public static function clearElementCache(&$arFields)
	{
		$ID = intval($arFields['ID']);
		if ($ID > 0){
			switch ($arFields['IBLOCK_ID']) {
				case Tools::getIblock('PRODUCTS'):
					Tools::clearCache(Tools::CACHE_KEY_PRODUCT, $ID);
					break;
			}
		}
	}

	/**
	 * @method clearCacheProperty
	 * @param $arFields
	 */
	public static function clearCacheProperty(&$arFields)
	{
		Tools::clearCache(Tools::CACHE_KEY_PROP, $arFields['IBLOCK_ID']);
	}


	public static function createRemainSection(&$arFields)
	{
		if ($arFields['IBLOCK_ID'] == self::SHOP_IB){

			$CIBlockSection = new \CIBlockSection();

			$element = Iblock\Element::getRow([
				'select' => ['ID', 'NAME', 'SECTION_NAME' => 'IBLOCK_SECTION.NAME'],
				'filter' => ['=ID' => $arFields['ID'], 'IBLOCK_ID' => $arFields['IBLOCK_ID']],
			]);

			if (!is_null($element)){
				$obIblocks = \Bitrix\Iblock\IblockTable::getList([
					'select' => ['ID'],
					'filter' => ['=IBLOCK_TYPE_ID' => self::REMAIN_TYPE_IB],
				]);
				while ($block = $obIblocks->fetch()) {
					$saveSection = [
						'XML_ID' => $element['ID'],
						'IBLOCK_ID' => $block['ID'],
						'NAME' => $element['NAME'].' '.$element['SECTION_NAME'],
					];
					$arSection = self::checkRemainSection($arFields['ID'], $block['ID']);
					if (!is_null($arSection)){
						$CIBlockSection->Update($arSection['ID'], $saveSection);
					} else {
						$CIBlockSection->Add($saveSection);
					}
				}
			}
		}
	}

	/**
	 * @method checkRemainSection
	 * @param $xmlId
	 * @param $iblock
	 *
	 * @return array|null
	 */
	protected static function checkRemainSection($xmlId, $iblock)
	{
		return \Bitrix\Iblock\SectionTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID' => $iblock, '=XML_ID' => $xmlId],
		]);
	}
}
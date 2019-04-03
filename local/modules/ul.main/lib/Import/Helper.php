<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 05.09.2016
 * Time: 12:59
 */

namespace UL\Main\Import;

use Bitrix\Iblock;
use Bitrix\Main\Error;
use Bitrix\Main\IO;

includeModules(['iblock', 'ab.iblock', 'catalog']);

class Helper
{
	const UPLOAD_DIR = '/upload/import_products';
	const PHOTO_DIR_NAME = 'фото';
	const EXT_XLS = 'xlsx';
	const REMAIN_DIR = '/upload/PRICES';

	protected static $arSection = [];

	/**
	 * @method sectionByXmlId
	 * @param $xmlId
	 * @param $iblock
	 *
	 * @return array|null
	 */
	public static function getByXmlId($xmlId, $iblock)
	{
		$arSection = Iblock\SectionTable::getRow([
			'select' => ['ID', 'XML_ID', 'IBLOCK_ID'],
			'filter' => ['IBLOCK_ID' => $iblock, '=XML_ID' => $xmlId],
		]);
		static::$arSection[$arSection['ID']] = $arSection['XML_ID'];

		return $arSection;
	}

	/**
	 * @method saveSection
	 * @param array $data
	 * @param null $id
	 *
	 * @return Result
	 */
	public static function saveSection(array $data, $id = null)
	{
		$CIBlockSection = new \CIBlockSection();
		$bUpdate = false;
		if (!$id || is_null($id)){
			$bUpdate = true;
			if (strlen($data['XML_ID']) > 0 && !in_array($data['XML_ID'], static::$arSection)){
				if (is_null(static::getByXmlId($data['XML_ID'], $data['IBLOCK_ID']))){
					$bUpdate = false;
					unset($id);
				};
			}
		}

		$Result = new Result();

		if ($bUpdate === true){
			if (!$CIBlockSection->Update($id, $data)){
				$Result->addError(new Error(strip_tags($CIBlockSection->LAST_ERROR)));
			}
		} else {
			unset($data['ID']);
			$id = $CIBlockSection->Add($data);
			if (intval($id) == 0){
				$Result->addError(new Error(strip_tags($CIBlockSection->LAST_ERROR)));
			} else {

			}
		}
		$Result->setId($id);

		return $Result;
	}

	/**
	 * @method recursiveDirectoryIterator
	 * @param null $directory
	 * @param array $files
	 *
	 * @return array
	 */
	public static function recursiveDirectoryIterator($directory = null, $files = array())
	{

		$iterator = new \DirectoryIterator ($directory);
		$k = 0;
		$arFolders = [];
		foreach ($iterator as $info) {

			if ($info->isFile()){
				continue;
			} elseif (!$info->isDot()) {

				$arSubDirs = self::recursiveDirectoryIterator($directory.DIRECTORY_SEPARATOR.$info);
				if (count($arSubDirs) > 0){
					$arFolders[$k]['NAME'] = $info->__toString();
					$arFolders[$k]['ITEMS'] = $arSubDirs;
					$arFolders[$k]['PATH'] = $info->getPathname();
//					$list = array($info->__toString() => $arSubDirs);
				} elseif (strtolower($info->__toString()) != 'фото') {
					$arFolders[$k]['PATH'] = $info->getPathname();
					$arFolders[$k]['NAME'] = $info->__toString();
//					$list = array($info->__toString() => $info->__toString());
				}
//				if (!empty($files) && count($list) > 0){
//					$files = array_merge_recursive($files, $list);
//				} else {
//					$files = $list;
//				}

				if (!empty($files) && count($arFolders) > 0){
					$arFolders[$k]['ITEMS'] = $files;
				}
			}

			$k++;
		}

		return $arFolders;
	}

	/**
	 * @method recursiveProductIterator
	 * @param null $directory
	 * @param array $files
	 *
	 * @return array
	 */
	public static function recursiveProductIterator($directory = null, $files = array())
	{

		$iterator = new \DirectoryIterator ($directory);

		foreach ($iterator as $info) {

            /**
             * если файл скрытый - то пропускам
             */
            if ($info->getFilename()[0] == '.') {
                continue;
            }

			if ($info->isFile()){
				$files [$info->__toString()] = new IO\File($info->getPathname());
			} elseif (!$info->isDot()) {
				$name = $info->__toString();
				$list = array(
					$info->__toString() => self::recursiveProductIterator(
						$directory.DIRECTORY_SEPARATOR.$name
					));
				if (!empty($files))
					$files = array_merge_recursive($files, $list);
				else {
					$files = $list;
				}
			}
		}

		return $files;
	}


	/**
	 * @method getIblockCatalogAction
	 * @param $path
	 *
	 * @return null|array
	 */
	public static function getIblockCatalog($path)
	{
		$iblock = $iblock_id =null;

		global $USER_FIELD_MANAGER;

		$res = \CIBlock::GetList(
			array(),
			array('TYPE' => 'catalog', '=NAME' => $path)
		);
//		while ($block = $res->Fetch()) {
//			$arUFields = $USER_FIELD_MANAGER->GetUserFields('ASD_IBLOCK', $block['ID']);
//			if ($arUFields['UF_IMPORT_DIR']['VALUE'] == $path){
//				$iblock_id = $block['ID'];
//				break;
//			}
//		}
		$iblock_id = $res->Fetch()['ID'];
		if (intval($iblock_id) > 0){
			$iblock = \CCatalogSku::GetInfoByIBlock($iblock_id);
		}

		return $iblock;
	}

	public static function getIblockCatalogById($iblockId)
	{
//		$iblock = \CCatalogSku::GetInfoByIBlock($iblock_id);
	}

	/**
	 * @method getProductByCode
	 * @param $iblock
	 * @param $code
	 *
	 * @return array|null
	 */
	public static function getProductByCode($iblock, $code)
	{
		return Iblock\ElementTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID'=>$iblock, '=XML_ID' => $code]
		]);
	}
}
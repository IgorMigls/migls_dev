<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 01.02.2017
 */

namespace UL\Main\Import\Prices;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use UL\Main\Import\Model;
use UL\Main\Import;
use Bitrix\Main\Application;
use Bitrix\Main\IO;
use Bitrix\Main\Type;

class AjaxHandler
{
	private static $root;
	protected $dir;

	/**
	 * AjaxHandler constructor.
	 */
	public function __construct()
	{
//		Model\QueueTable::dropTable();
//		Model\QueueTable::createTable();

		self::$root = Application::getDocumentRoot();
		$this->dir = self::$root.Import\Helper::REMAIN_DIR;
	}


	/**
	 * @method getFileListAction
	 * @return array
	 * @throws Import\ImportException
	 */
	public function getFileListAction()
	{
		$arFiles = [];
		$oFiles = Import\Helper::recursiveProductIterator($this->dir);
		/** @var IO\File $oFile */
		foreach ($oFiles as $k => $oFile) {
			$arFiles[] = [
				'label' => $oFile->getName(),
				'id' => $k
			];
		}

		if(count($arFiles) == 0){
			throw new Import\ImportException('Нет файлов для выгрузки в папке '.Import\Helper::REMAIN_DIR, 404);
		}

		return $arFiles;
	}

	public function addFileToQueueAction($data = [])
	{
		$file = trim($data['file']);

		if(strlen($file) == 0){
			throw new Import\ImportException('Файл не выбран', 400);
		}

		$row = Model\QueueTable::getRow([
			'filter' => ['=FILE' => $file, 'IN_PROCESS' => Model\QueueTable::QUEUE_IMPORT_IN_PROCESS],
		]);
		if(!is_null($row)){
			throw new Import\ImportException('Файл уже добавлен в очередь импорта', 401);
		}

		preg_match('/(\d+).csv/i', $file, $shopMatch);
		if(intval($shopMatch[1]) == 0){
			throw new Import\ImportException('Неверный формат файла', 500);
		}

		Loader::includeModule('iblock');
		$shop = ElementTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID' => 5, '=ID' => $shopMatch[1]]
		]);

		if(is_null($shop)){
			throw new Import\ImportException('Магазин ID='.$shopMatch[1].' не найден', 404);
		}

		$add = Model\QueueTable::add([
			'LAST_IMPORT' => new Type\DateTime(),
			'IN_PROCESS' => Model\QueueTable::QUEUE_IMPORT_IN_PROCESS,
			'SHOP_ID' => $shopMatch[1],
			'FILE' => $file,
		]);
		if(!$add->isSuccess()){
			throw new Import\ImportException(implode(', ', $add->getErrorMessages()), 500);
		}

		return $add->getId();
	}

	public function getProcessFilesAction()
	{
		$rows = Model\QueueTable::getList([
			'filter' => ['IN_PROCESS' => Model\QueueTable::QUEUE_IMPORT_IN_PROCESS],
			'select' => ['*', 'SHOP_NAME'=>'SHOP.NAME','SHOP_CITY' => 'SHOP.IBLOCK_SECTION.NAME']
		])->fetchAll(new \AB\Tools\Helpers\DateFetchConverter());

		if(count($rows) == 0){
			$rows = [];
		}

		return $rows;
	}

}
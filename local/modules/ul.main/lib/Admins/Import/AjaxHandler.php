<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 05.09.2016
 * Time: 16:03
 */

namespace UL\Main\Admins\Import;

use AB\Tools\Helpers\DateFetchConverter;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use PW\Tools\Debug;
use UL\Import\RemainsTmpTable;
use UL\Main\Import;
use Bitrix\Iblock;
use Bitrix\Main\IO;

includeModules(['iblock', 'catalog', 'ab.tools']);

class AjaxHandler
{
	protected $CIBlockSection;

	protected static $uploadDir = '/upload/import_products';

	/**
	 * AjaxHandler constructor.
	 */
	public function __construct()
	{
		$this->CIBlockSection = new \CIBlockSection();
	}

	/**
	 * @method getFoldersAction
	 * @param array $data
	 *
	 * @return array
	 */
	public function getFoldersAction($data = [])
	{
		$folders['DIR'] = Import\Helper::recursiveDirectoryIterator($data['PATH']);

		$fileDir = array_pop(explode('/', $data['PATH']));

		$folders['IBLOCK'] = Import\Helper::getIblockCatalog($fileDir);

		$iblock_id = $folders['IBLOCK']['PRODUCT_IBLOCK_ID'];

		$fields = array(
			array(
				'NAME' => 'Название товара',
				'ID' => 100500,
				'CODE' => 'NAME',
			),
			array(
				'NAME' => 'Картинка',
				'CODE' => 'DETAIL_PICTURE',
				'ID' => 100501,
			),
			array(
				'NAME' => 'Описание',
				'CODE' => 'DETAIL_TEXT',
				'ID' => 100502,
			),
			array(
				'NAME' => 'Артикул',
					'CODE' => 'CODE',
				'ID' => 100503
			),
			array(
				'NAME' => 'Штрихкод',
				'CODE' => 'BARCODE',
				'ID' => 100504
			),
            array(
                'NAME' => 'Единица измерения',
                'CODE' => 'MEASURE',
                'ID' => 100505
            ),
            array(
                'NAME' => 'Кратность ед.из.',
                'CODE' => 'MULTIPLICITY_MEASURE',
                'ID' => 100506
            )
		);

		$folders['PROP_SITE'] = Iblock\PropertyTable::getList([
			'filter' => ['IBLOCK_ID' => $iblock_id],
		])->fetchAll();

		$folders['PROP_SITE'] = array_merge($fields, $folders['PROP_SITE']);

		$Product = new Import\Products('/'.$fileDir);
		$heads = $Product->process()->getHeaders();
		foreach ($heads as $val) {
			$arProp = ['NAME' => $val];
			$arSaved = Import\Model\ComparePropTable::getRow([
				'filter' => ['IBLOCK_ID' => $iblock_id, '=PROPERTY_IMPORT' => $val],
			]);
			if (!is_null($arSaved)){
				$arProp['SAVED'] = $arSaved['PROPERTY_IMPORT'];
			}
			$folders['PROPERTIES'][] = $arProp;
		}

		return $folders;
	}

	public function getIblockCatalogAction($data = [])
	{
//		return \CCatalogSku::GetInfoByIBlock(26);
		return Import\Helper::getIblockCatalog($data['NAME']);
	}

	/**
	 * @method getMainFoldersAction
	 * @return array
	 */
	public function getMainFoldersAction()
	{
		$directory = Application::getDocumentRoot().self::$uploadDir;
		$iterator = new \DirectoryIterator ($directory);
		$k = 1;
		$arFolders = array(
			['NAME' => 'Выберите тип товара', 'PATH' => 0],
		);

		foreach ($iterator as $info) {

			if ($info->isFile()){
				continue;
			} elseif (!$info->isDot()) {

				if (strtolower($info->__toString()) != 'фото'){
					$arFolders[$k]['PATH'] = $info->getPathname();
					$arFolders[$k]['NAME'] = $info->__toString();
				}
			}

			$k++;
		}

		return $arFolders;
	}

	/**
	 * @method saveComparePropAction
	 * @param array $data
	 *
	 * @return array
	 */
	public function saveComparePropAction($data = [])
	{
//		Import\Model\ComparePropTable::createTable();

		$save = [
			'IBLOCK_ID' => $data['IBLOCK']['PRODUCT_IBLOCK_ID'],
			'PROPERTY_ID' => $data['SITE_PROP']['ID'],
			'PROPERTY_CODE' => $data['SITE_PROP']['CODE'],
			'PROPERTY_NAME' => $data['SITE_PROP']['NAME'],
			'PROPERTY_IMPORT' => $data['IMPORT_PROP']['NAME'],
		];
		$row = Import\Model\ComparePropTable::getRowById($save['PROPERTY_ID']);
		if (!is_null($row)){
			$result = Import\Model\ComparePropTable::update($row['ID'], $save);
		} else {
			$result = Import\Model\ComparePropTable::add($save);
		}

		$out = false;
		if ($result->isSuccess()){
			$out = true;
		}

		return ['SUCCESS' => $out, 'SAVE' => $save];
	}

	/**
	 * @method importSectionAction
	 * @param array $data
	 *
	 * @return bool|null
	 * @throws Import\ImportException
	 */
	public function importSectionAction($data = [])
	{
		$result = null;

		$AllCategory = new Import\AllCategory($data['IBLOCK']['PRODUCT_IBLOCK_ID'], Import\Helper::UPLOAD_DIR.'/'.$data['FOLDER']['NAME']);
		if (!$AllCategory->run()->getResult()->isSuccess()){
			throw new Import\ImportException(implode("\n", $AllCategory->getResult()->getErrorMessages()));
		} else {
			$result = true;
		}

		return $result;
	}

	public function setImportParamsAction($data = [])
	{
		$arIblock = Import\Helper::getIblockCatalog($data['NAME']);
//		$arIblock = \CCatalogSku::GetInfoByIBlock(26);
		$Product = new Import\Products('/'.$data['NAME']);
		$Product->setIblock($arIblock['PRODUCT_IBLOCK_ID']);
        /**
         * запускаем импорт
         */
		$Product->process();

		return true;
	}

	public function uploadMainTmpAction($data = [])
	{
		$filePath = Application::getDocumentRoot().$data['file'];

		RemainsTmpTable::createTables();

		$folderName = array_pop(explode('/', $data['folder']));
		$arIblock = Import\Helper::getIblockCatalog($folderName);
		$CsvReader = new Import\ReadCsv($filePath);
		$CsvReader->readData();

		foreach ($CsvReader->getData() as $k => $item) {
//			if($k == 10)
//				break;

			$item['SHOP_ID'] = $data['shopId'];
			$item['IBLOCK_ID'] = $arIblock['IBLOCK_ID'];

			if(!empty($item['BARCODE']) && !empty($item['ARTICLE'])){
				RemainsTmpTable::add($item);
			}
		}

		return true;
	}

	public function addProductImportAction($data = [])
	{
		$result = Import\Model\QueueTable::add([
			'LAST_IMPORT' => new DateTime(),
			'IN_PROCESS' => Import\Model\QueueTable::QUEUE_IMPORT_IN_PROCESS,
			'SHOP_ID' => null,
			'FILE' => $data['NAME'],
		]);
		if($result->isSuccess()){
			return 'OK';
		} else {
			throw new \Exception(implode(', ', $result->getErrorMessages()));
		}
	}

	public function getProcessImportAction()
	{
		$obRow = Import\Model\QueueTable::getList([
			'filter' => ['IN_PROCESS' => Import\Model\QueueTable::QUEUE_IMPORT_IN_PROCESS],
			'order' => ['ID' => 'DESC'],
			'limit' => 1
		]);

		return $obRow->fetch(new DateFetchConverter());
	}
}
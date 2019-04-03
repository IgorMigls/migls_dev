<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 03.09.2016
 * Time: 14:36
 */

namespace UL\Main\Import;

use Bitrix\Main\Application;
use Bitrix\Main\Error;
use Bitrix\Main\IO;
use PW\Tools\Debug;
use UL\Main\Import\Model\ComparePropTable;

includeModules(['iblock','ab.iblock']);

require_once(Application::getDocumentRoot().'/local/php_interface/vendor/PHPExcel/PHPExcel.php');

class Products
{
	const UPLOAD_DIR = '/upload/import_products';
	const PHOTO_DIR_NAME = 'фото';
	const EXT_XLS = 'xlsx';

	/** @var  IO\Directory */
	protected $mainDir;

	/** @var  string */
	protected $root;

	/** @var  array */
	protected $treeFiles = null;

	/** @var  array */
	protected $productItems;

	/** @var  int */
	protected $iblock;

	/** @var  array */
	protected $headers;

	private $recursiveCount = 0;

	private $preset;


    /**
     * Products constructor.
     * @param $dir - имя директории с файлами импорта
     * @throws ImportException
     */
	public function __construct($dir)
	{
		$this->iblock = 4;
		$this->root = Application::getDocumentRoot();

		$this->mainDir = new IO\Directory($this->root.self::UPLOAD_DIR.$dir);

		if(!$this->mainDir->isExists()){
			throw new ImportException('Нет директории для импорта');
		}
		if(!$this->mainDir->isDirectory()){
			throw new ImportException($dir. ' - это не директория');
		}
	}

	public function process()
	{
		ComparePropTable::createTable();
        /**
         * загружаем профили обмена
         */
		$obPreset = ComparePropTable::getList([
			'filter' => ['=IBLOCK_ID' => $this->iblock],
		]);
		while ($preset = $obPreset->fetch()){
			$this->preset[$preset['PROPERTY_CODE']] = $preset;
		}
        /**
         * инициализируем итератор для директории с файлами обмена
         */
		$this->setTreeFiles();
		$this->setProductItems();

		return $this;
	}

	/**
	 * @method saveProducts
	 */
	public function saveProducts()
	{
		$CIBlockElement = new \CIBlockElement();

		/** @var ProductItems $productItem */


//		Debug::toLog($this->getProductItems());

		foreach ($this->getProductItems() as $productItem) {
			foreach ($productItem->getFields() as $arProduct){
				$arProduct['FIELDS']['IBLOCK_ID'] = $this->iblock;

				$save = $arProduct['FIELDS'];

				$save['PROPERTY_VALUES'] = $arProduct['PROPERTY_VALUES'];

				$Result = new Result();

				$arElement = Helper::getProductByCode($this->iblock, $save['XML_ID']);
				if(!is_null($arElement)){
					$Result->setId($arElement['ID']);
					if($CIBlockElement->Update($arElement['ID'], $arProduct['FIELDS'])){
						$CIBlockElement->SetPropertyValuesEx($arElement['ID'], $this->iblock, $arProduct['PROPERTY_VALUES']);
					} else {
						$Result->addError(new Error(strip_tags($CIBlockElement->LAST_ERROR)));
					}
				} else {
					$id = $CIBlockElement->Add($save);
					if(intval($id) > 0){
						$Result->setId($id);
					} else {
						$Result->addError(new Error(strip_tags($CIBlockElement->LAST_ERROR)));
					}
				}

				if(!$Result->isSuccess()){
//					PR($Result->getErrorMessages());
				}
			}
		}
	}

	/**
	 * @method setProductItems
	 * @param array $treeList
	 */
	protected function setProductItems($treeList = array())
	{
		$this->recursiveCount++;

		if(count($treeList) == 0)
			$treeList = $this->getTreeFiles();


		if($this->recursiveCount > 0){
			/**
			 * @var string $name
			 * @var \DirectoryIterator $file
			 * @var IO\File $xls
			 */
			$k = 1;
			foreach ($treeList as $name => $file) {
//		    	if($k > 3)
//			    	break;

				$Item = ProductItems::builder($file, $name);
				if($Item->hasProducts()){

					$Item->setArSection($this->getCategory($name));

					foreach ($Item->getProductFiles() as $xls){
						$Item->setFields($Item->readXls($xls, $this->preset));
						foreach ($Item->getHeaders() as $header){
							$this->addHead($header);
						}

						$this->productItems[] = $Item;
					}
				} else {
					$this->setProductItems($file);
				}
				$k++;
			}
		}

	}

	public function getCategory($name)
	{
		$xmlId = md5($name);
		$arSectionUse = Helper::getByXmlId($xmlId, $this->iblock);

		return $arSectionUse;
	}

	/**
	 * @method getMainDir - get param mainDir
	 * @return IO\Directory
	 */
	public function getMainDir()
	{
		return $this->mainDir;
	}

	/**
	 * @param IO\Directory $mainDir
	 *
	 * @return Products
	 */
	public function setMainDir($mainDir)
	{
		$this->mainDir = $mainDir;

		return $this;
	}

	/**
	 * @param array $treeFiles
	 *
	 * @return Products
	 */
	public function setTreeFiles($treeFiles = array())
	{
		if(count($treeFiles) > 0){
			$this->treeFiles = $treeFiles;
		} else {
			if(is_null($this->treeFiles)){
				$this->treeFiles = Helper::recursiveProductIterator($this->mainDir->getPath());
			}
		}

		return $this;
	}

	/**
	 * @method getTreeFiles - get param treeFiles
	 * @return array
	 */
	public function getTreeFiles()
	{
		return $this->treeFiles;
	}

	/**
	 * @method getProductItems - get param productItems
	 * @return array
	 */
	public function getProductItems()
	{
		return $this->productItems;
	}

	/**
	 * @method addHead
	 * @param $head
	 */
	public function addHead($head)
	{
		if(!in_array($head, $this->headers)){
			$this->headers[] = $head;
		}
	}

	/**
	 * @method getHeaders - get param headers
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * @method getIblock - get param iblock
	 * @return int
	 */
	public function getIblock()
	{
		return $this->iblock;
	}

	/**
	 * @param int $iblock
	 *
	 * @return Products
	 */
	public function setIblock($iblock)
	{
		$this->iblock = $iblock;

		return $this;
	}

	/**
	 * @method getPreset - get param preset
	 * @return mixed
	 */
	public function getPreset()
	{
		return $this->preset;
	}

	/**
	 * @param mixed $preset
	 *
	 * @return Products
	 */
	public function setPreset($preset)
	{
		$this->preset = $preset;

		return $this;
	}

}
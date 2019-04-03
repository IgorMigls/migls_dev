<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 03.09.2016
 * Time: 15:19
 */

namespace UL\Main\Import;

use AB\Iblock\Property;
use Bitrix\Main\IO;
use Bitrix\Main\Application;
use PW\Tools\Debug;
use Bitrix\Main\Error;
use UL\Main\Admins\Import\OffersMeasureSetter;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;

class ProductItems
{
	/** @var  array */
	protected $photo = array();

	/** @var  array */
	protected $products;

	/** @var  array */
	protected $productFiles;

	/** @var  bool */
	protected $isHasProducts;

	/** @var  array */
	protected $fields;

	/** @var  string */
	protected $category;

	/** @var  array */
	protected $arSection;

	/** @var  array */
	protected $headers;

	/**
	 * ProductItems constructor.
	 */
	protected function __construct()
	{
	}

	/**
	 * @method builder
	 * @param array $arBuilds
	 * @param string $cat
	 *
	 * @return static
	 */
	public static function builder($arBuilds = [], $cat = '')
	{
		$Item = new static();

		/**
		 * @var string $name
		 * @var \DirectoryIterator|array $build
		 */

		foreach ($arBuilds as $name => $build) {
			if (is_array($build) && strtolower($name) == Products::PHOTO_DIR_NAME){
				$Item->addPhotos($build);
			} elseif ($build instanceof IO\File) {
				if ($build->getExtension() == Products::EXT_XLS){
					$Item->addXls($build);
				}
			}
		}

		if (count($Item->getProductFiles()) > 0){
			$Item->setIsHasProducts(true);
		}

		if (strlen($cat) > 0){
			$Item->setCategory($cat);
		}

		return $Item;
	}



	public function readXls(IO\File $file, $preset = null)
	{
		$objReader = \PHPExcel_IOFactory::load($file->getPath());
		$sheetData = $objReader->getActiveSheet();

		$arData = $sheetData->toArray(null, false, false);

		$header = $arData[0];
		unset($arData[0]);
		$this->setHeaders($header);

		$saeItem = [];

//		Debug::toLog($header);

		foreach ($preset as $code => $value) {
			if ($keyXls = array_search($value['PROPERTY_IMPORT'], $header)){
				$saeItem[$code] = $keyXls;
			}
		}

//		Debug::toLog($saeItem);
//		Debug::toLog($preset); exit;

		$arRes = [];

		$CIBlockElement = new \CIBlockElement();
		foreach ($arData as $k => $data) {
			$save = [];

			foreach ($data as $l => $value) {
				$key = array_search($l, $saeItem);
				if (!empty($key)){

					$num = $saeItem[$key];
					$value = str_replace('_x000D_', '', $data[$num]);
					$value = str_replace('_x000A_', '', $value);
					$save[$key] = $value;
				}
			}

			$Photo = $this->photo[0][$save['DETAIL_PICTURE']];
			if ($Photo instanceof IO\File && $Photo->isExists()){
				$save['DETAIL_PICTURE'] = \CFile::MakeFileArray($Photo->getPath());
			} else {
				$save['DETAIL_PICTURE'] = false;
			}

//			Debug::toLog($data);
//			Debug::toLog($save);
			$iblockId = $this->getArSection()['IBLOCK_ID'];

			$offersMeasureSetter = OffersMeasureSetter::getInstance($iblockId);


			$saveBX = [
				'FIELDS' => [
					'NAME' => $save['NAME'],
					'DETAIL_PICTURE' => $save['DETAIL_PICTURE'],
					'DETAIL_TEXT' => $save['DETAIL_TEXT'],
					'CODE' => $save['CODE'],
					'IBLOCK_SECTION_ID' => $this->getArSection()['ID'],
					'XML_ID' => $save['BARCODE'],
					'IBLOCK_ID' => $iblockId,
				],
			];

			unset($save['NAME']);
			unset($save['DETAIL_PICTURE']);
			unset($save['DETAIL_TEXT']);

			$saveBX['PROPERTY_VALUES'] = $save;

			if ($file->getPath() == '/home/bitrix/www/upload/import_products/Продукты питания/Крупы, макароны, бакалея/Крупы/Крупы1.xlsx'){
//				Debug::toLog($saveBX);
			}


			if (strlen($saveBX['FIELDS']['XML_ID']) > 0){

                $measureName = $saveBX['PROPERTY_VALUES'][MeasureSettings::getMeasurePropCode()];
                unset($saveBX['PROPERTY_VALUES'][MeasureSettings::getMeasurePropCode()]);
                $measureRatio = $saveBX['PROPERTY_VALUES'][MeasureSettings::getMeasureRatioPropCode()];
                unset($saveBX['PROPERTY_VALUES'][MeasureSettings::getMeasureRatioPropCode()]);


                if ($measureName) {
                    /*
                    define('DEBUG_M', true);
                    var_dump($measureName);
                    var_dump($measureRatio);
                    var_dump("=======================");
                    */
                }


				$arElement = Helper::getProductByCode($iblockId, $saveBX['FIELDS']['XML_ID']);
				$Result = new Result();

				if (!is_null($arElement)){
					$Result->setId($arElement['ID']);
					if ($CIBlockElement->Update($arElement['ID'], $saveBX['FIELDS'])){
						$CIBlockElement->SetPropertyValuesEx($arElement['ID'], $iblockId, $saveBX['PROPERTY_VALUES']);
					} else {
						$Result->addError(new Error(strip_tags($CIBlockElement->LAST_ERROR)));
					}
				} else {
					$id = $CIBlockElement->Add(array_merge($saveBX['FIELDS'], ['PROPERTY_VALUES' => $saveBX['PROPERTY_VALUES']]));
					if (intval($id) > 0){
						$Result->setId($id);
					} else {
						$Result->addError(new Error(strip_tags($CIBlockElement->LAST_ERROR)));
					}
				}

				if ($Result->getId()) {
				    $this->setMeasureForProduct($iblockId, $Result->getId(), $measureName, $measureRatio);
                    $offersMeasureSetter->addProductId($Result->getId());
                    //var_dump("SET PRODUCT ID: " . $Result->getId() . " - Measure name: {$measureName}");
				}


				if (!$Result->isSuccess()){
//					Debug::toLog($Result->getErrorMessages());
//					Debug::toLog($saveBX);
				}

				$arRes[$k] = $saveBX;
			}

		}

		return $arRes;
	}


	protected static $propertyCache = array();


    /**
     * проверяет наличие свойства в инфоблоке и если его нет - создает
     *
     * @param $iblockId
     * @param $propCode
     * @param $propName
     * @param string $propType
     * @return @bool
     */
	protected static function checkOrCreatePropForIblock($iblockId, $propCode, $propName, $propType = 'N')
    {
        if (isset(self::$propertyCache[$iblockId][$propCode])) {
            return true;
        }

        $prop = \CIBlockProperty::GetList(array(), array('IBLOCK_ID' => $iblockId, 'CODE' => $propCode))->Fetch();
        if ($prop) {
            return self::$propertyCache[$iblockId][$propCode] = $prop['ID'];
        }

        $data = array(
            "NAME" => $propName,
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => $propCode,
            "PROPERTY_TYPE" => $propType,
            "IBLOCK_ID" => $iblockId
        );

        $p = new \CIBlockProperty();

        Property::cleanPropCache($iblockId);

        return self::$propertyCache[$iblockId][$propCode] = $p->Add($data);

    }

    /**
     * Создает свойства для хранения кратности ед.измерения и ед.измерения для базового товара
     * в инфоблоке
     *
     * @param $iblockId
     * @return bool
     */
	protected static function makeMeasurePropertyForIblock($iblockId)
    {
        self::checkOrCreatePropForIblock($iblockId, MeasureSettings::getMeasurePropCode(), 'Ед.измерения');
        self::checkOrCreatePropForIblock($iblockId, MeasureSettings::getMeasureRatioPropCode(), 'Кратность ед.измерения');
    }

    protected static $measureCache = array();

    /**
     * Ищет (и создает если не находит) единицу измерения по краткому название
     *
     * @param $measureName
     * @return bool|array
     */
    protected function getMeasureOrCreate($measureName) {

        if (!$measureName) {
            return false;
        }

        $measureNameKey = trim(mb_strtolower($measureName));
        
        if (!$measureNameKey) {
            return false;
        }

        if (isset(self::$measureCache[$measureNameKey])) {
            return self::$measureCache[$measureNameKey];
        }

        $row = MeasureTable::query()
            ->addSelect('*')
            ->addFilter('=SYMBOL_RUS', $measureNameKey)
            ->exec()
            ->fetch();

        if ($row) {
            return self::$measureCache[$measureNameKey] = $row;
        }

	    $data = array(
            'CODE' => time(),
            'MEASURE_TITLE' => $measureName,
            'SYMBOL_RUS' => $measureName,
            'SYMBOL_INTL' => $measureName,
            'SYMBOL_LETTER_INTL' => $measureName,
            'IS_DEFAULT' => 'N'
        );


        $result = MeasureTable::add($data);
        if (!$result->isSuccess()) {
            return self::$measureCache[$measureNameKey] = false;
        }

        $data['ID'] = $result->getId();
        return self::$measureCache[$measureNameKey] = $data;
    }


    /**
     * Устанавливает значение кратности ед.измерения и ед.измерения для базового товара
     * 
     * @param $iblockId
     * @param $itemId
     * @param $measureName
     * @param $measureRatio
     */
	protected function setMeasureForProduct($iblockId, $itemId, $measureName, $measureRatio)
    {
        self::makeMeasurePropertyForIblock($iblockId);

        $defaultMeasure = MeasureSettings::getDefaultMeasureRatio();
        $defaultMeasureRatio = MeasureSettings::getDefaultMeasureRatio();

        $measure = $this->getMeasureOrCreate($measureName);
        $measureId = $measure ? $measure['ID'] : $defaultMeasure['ID'];

        $measureRatio = $measureRatio ? : $defaultMeasureRatio;

        $data = array(
            MeasureSettings::getMeasurePropCode() => $measureId,
            MeasureSettings::getMeasureRatioPropCode() => $measureRatio
        );

        $el = new \CIBlockElement();
        $el->SetPropertyValuesEx($itemId, $iblockId, $data);
    }

	/**
	 * @method getPhoto - get param photo
	 * @return mixed
	 */
	public function getPhoto()
	{
		return $this->photo;
	}

	/**
	 * @param mixed $photo
	 *
	 * @return ProductItems
	 */
	public function setPhoto($photo)
	{
		$this->photo = $photo;

		return $this;
	}

	/**
	 * @method getProducts - get param products
	 * @return mixed
	 */
	public function getProducts()
	{
		return $this->products;
	}

	/**
	 * @param mixed $products
	 *
	 * @return ProductItems
	 */
	public function setProducts($products)
	{
		$this->products = $products;

		return $this;
	}

	/**
	 * @method getProductFiles - get param productFiles
	 * @return mixed
	 */
	public function getProductFiles()
	{
		return $this->productFiles;
	}

	/**
	 * @param mixed $productFiles
	 *
	 * @return ProductItems
	 */
	public function setProductFiles($productFiles)
	{
		$this->productFiles = $productFiles;

		return $this;
	}

	/**
	 * @method addPhotos
	 * @param $arPhotos
	 */
	public function addPhotos($arPhotos)
	{
		$this->photo[] = array_merge($this->photo, $arPhotos);
	}

	/**
	 * @method addXls
	 * @param IO\File $xls
	 */
	public function addXls(IO\File $xls)
	{
		$this->productFiles[$xls->getName()] = $xls;
	}

	/**
	 * @method hasProducts
	 * @return bool
	 */
	public function hasProducts()
	{
		return $this->isHasProducts;
	}

	/**
	 * @param bool $isHasProducts
	 *
	 * @return ProductItems
	 */
	public function setIsHasProducts($isHasProducts = false)
	{
		$this->isHasProducts = $isHasProducts;

		return $this;
	}

	/**
	 * @method getFields - get param fields
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @param array $fields
	 *
	 * @return ProductItems
	 */
	public function setFields($fields)
	{
		$this->fields = $fields;

		return $this;
	}

	/**
	 * @method getCategory - get param category
	 * @return string
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * @method setCategory - set param Category
	 * @param string $category
	 */
	public function setCategory($category)
	{
		$this->category = $category;
	}

	/**
	 * @method getArSection - get param arSection
	 * @return array
	 */
	public function getArSection()
	{
		return $this->arSection;
	}

	/**
	 * @method setArSection - set param ArSection
	 * @param array $arSection
	 */
	public function setArSection($arSection)
	{
		$this->arSection = $arSection;
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
	 * @param array $headers
	 *
	 * @return ProductItems
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;

		return $this;
	}

}
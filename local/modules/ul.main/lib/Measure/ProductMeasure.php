<?php
/**
 * Created by IsWin.
 * Site: http://iswin.ru
 * Date: 03.04.18
 * Time: 23:47
 */


namespace Ul\Main\Measure;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use Soft\Element;
use Ul\Main\Measure\MeasureSettings;
use Ul\Main\Measure\MeasureTable;

\CModule::IncludeModule('soft.iblock');


class ProductMeasure
{
    protected $iblockId;
    protected $id;
    protected $name;
    protected $shortName;
    protected $ratio;


    public function __construct ($iblockId, $id)
    {
        $this->iblockId = $iblockId;
        $this->id = $id;
    }

    public static function getInstance($iblockId, $id)
    {
        return new ProductMeasure($iblockId, $id);
    }

    protected static $cache = array();

    /**
     * @param $iblockId
     * @param $id
     * @return $this|mixed
     */
    public static function getMeasureByProductId($iblockId, $id)
    {
        if (isset(self::$cache[$id])) {
            return self::$cache[$id];
        }

        $defaultMeasure = MeasureSettings::getDefaultMeasure();
        $defaultRatio = MeasureSettings::getDefaultMeasureRatio();


        $skuMeasure = Element::getList([
            'select' => [
                'ID',
                'MEASURE_NAME' => 'MEASURE.MEASURE_TITLE',
                'MEASURE_SHORT_NAME' => 'MEASURE.SYMBOL_RUS',
                'MEASURE_RATIO' => 'RATIO.RATIO',
            ],
            'runtime' => [
                new Entity\ReferenceField(
                    'CATALOG',
                    \Bitrix\Catalog\ProductTable::getEntity(),
                    ['=this.ID' => 'ref.ID']
                ),
                new Entity\ReferenceField(
                    'RATIO',
                    \Bitrix\Catalog\MeasureRatioTable::getEntity(),
                    ['=this.ID' => 'ref.PRODUCT_ID']
                ),
                new Entity\ReferenceField(
                    'MEASURE',
                    MeasureTable::getEntity(),
                    ['=this.CATALOG.MEASURE' => 'ref.ID']
                ),
            ],
            'filter' => [
                'IBLOCK_ID' => $iblockId,
                '=ID' => $id
            ],
        ])->fetch();


        $measure = self::getInstance($iblockId, $id)
            ->setName($skuMeasure['MEASURE_NAME'] ? : $defaultMeasure['MEASURE_TITLE'])
            ->setShortName($skuMeasure['MEASURE_SHORT_NAME'] ? : $defaultMeasure['SYMBOL_RUS'])
            ->setRatio($skuMeasure['MEASURE_RATIO'] ? : $defaultRatio);

        return self::$cache[$id] = $measure;
    }

    public function getIblockId()
    {
        return $this->iblockId;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShortName ()
    {
        return $this->shortName;
    }

    /**
     * @param $shortName
     * @return $this
     */
    public function setShortName ($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRatio ()
    {
        return $this->ratio;
    }

    /**
     * @param $ratio
     * @return $this
     */
    public function setRatio ($ratio)
    {
        $this->ratio = $ratio;
        return $this;
    }


}
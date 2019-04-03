<?php
/**
 * Created by IsWin.
 * Site: http://iswin.ru
 * Date: 04.04.18
 * Time: 3:32
 */


namespace UL\Main\Admins\Import;


use AB\Iblock\Element;
use Bitrix\Catalog\MeasureRatioTable;
use Bitrix\Catalog\ProductTable;
use UL\Main\CatalogHelper;
use Ul\Main\Measure\MeasureSettings;
use Bitrix\Main\Entity;

\CModule::IncludeModule('iblock');
\CModule::IncludeModule('ab.iblock');

class OffersMeasureSetter
{
    protected $limitSelect = 500;
    protected $iblockId;
    protected $productIds = array();
    /**
     * @var OffersMeasureSetter[]
     */
    protected static $instances = array();

    /**
     * OffersMeasureSetter constructor.
     * @param $iblockId - ID инфоблока родительского каталога (не торговых предложений)
     */
    public function __construct ($iblockId)
    {
        $this->setIblockId($iblockId);
    }

    /**
     * @param $iblockId
     * @return mixed|OffersMeasureSetter
     */
    public static function getInstance($iblockId)
    {
        if (isset(self::$instances[$iblockId])) {
            return self::$instances[$iblockId];
        }

        return self::$instances[$iblockId] = new OffersMeasureSetter($iblockId);
    }

    /**
     * @return OffersMeasureSetter[]
     */
    public static function getAllInstances()
    {
        return self::$instances;
    }


    /**
     * @return int
     */
    public function getLimitSelect ()
    {
        return $this->limitSelect;
    }

    /**
     * @param $limitSelect
     * @return $this
     */
    public function setLimitSelect ($limitSelect)
    {
        $this->limitSelect = $limitSelect;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIblockId ()
    {
        return $this->iblockId;
    }

    /**
     * @param $iblockId
     * @return $this
     */
    public function setIblockId ($iblockId)
    {
        $this->iblockId = $iblockId;
        return $this;
    }

    /**
     * @param $productIds
     * @return $this
     */
    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
        return $this;
    }

    /**
     * @param $productId
     * @return $this
     */
    public function addProductId($productId)
    {
        if (in_array($productId, $this->productIds)) {
            return $this;
        }
        
        $this->productIds[] = $productId;
        return $this;
    }

    public function getProductIds()
    {
        return $this->productIds;
    }

    public function exec()
    {

        $steps = array();
        $offset = 0;
        while ($offset < $this->getLimitSelect()) {
            $steps[] = array_slice($this->getProductIds(), $offset, $this->getLimitSelect());
            $offset += $this->getLimitSelect();
        }

        $skuIblocks = CatalogHelper::getSkuBlockByProductsBlock();
        $skuIblockId = $skuIblocks[$this->getIblockId()]['SKU_INFO']['IBLOCK_ID'];


        if (!$skuIblockId) {
            return false;
        }

        $defaultMeausureId = MeasureSettings::getDefaultMeasure()['ID'];
        $defaultRatio = MeasureSettings::getDefaultMeasureRatio();

        $measurePropCode = MeasureSettings::getMeasurePropCode();
        $measureRatioPropCode = MeasureSettings::getMeasureRatioPropCode();



        if ($steps) {
            foreach ($steps as $productIds) {


                $products = Element::getList([
                    'select' => [
                        'ID', 'NAME',
                        $measurePropCode => 'PROPERTY.' . $measurePropCode,
                        $measureRatioPropCode => 'PROPERTY.' . $measureRatioPropCode,
                    ],
                    'filter' => [
                        'IBLOCK_ID' => $this->getIblockId(),
                        '=ID' => $productIds
                    ],
                ]);

                while ($product = $products->Fetch()) {

                    $measureId = $product[$measurePropCode] ? : $defaultMeausureId;
                    $measureRatio = $product[$measureRatioPropCode] ? : $defaultRatio;

                    $map = array(
                        'MEASURE' => $measureId,
                        'RATIO' => $measureRatio
                    );

                    $offers = Element::getList([
                        'select' => [
                            'ID',
                            'PARENT_ID' => 'PROPERTY.CML2_LINK.ID',
                        ],
                        'filter' => [
                            'IBLOCK_ID' => $skuIblockId,
                            '@PROPERTY.CML2_LINK.ID' => $product['ID'],
                        ],
                    ]);

                    while ($offer = $offers->fetch()) {

                        if (!$map) {
                            continue;
                        }

                        $measureData = array(
                            'MEASURE' => $map['MEASURE']
                        );

                        $res = ProductTable::update($offer['ID'], $measureData);

                        if (!$res->isSuccess()) {
                            /** @todo нужна обработка ошибок */
                            var_dump($res->getErrorMessages());
                        }

                        $ratioData = array(
                            'RATIO' => $map['RATIO'],
                            'PRODUCT_ID' => $offer['ID'],
                            'IS_DEFAULT' => 'Y'
                        );


                        $ratio = MeasureRatioTable::query()
                            ->addSelect('ID')
                            ->addFilter('=PRODUCT_ID', $offer['ID'])
                            ->exec()
                            ->fetch();

                        if ($ratio) {
                            $res = MeasureRatioTable::update($ratio['ID'], $ratioData);
                        } else {

                            $res = MeasureRatioTable::add($ratioData);
                        }



                        if (!$res->isSuccess()) {
                            /** @todo нужна обработка ошибок */
                            var_dump($res->getErrorMessages());
                        }
                    }
                }

            }
        }
    }
}
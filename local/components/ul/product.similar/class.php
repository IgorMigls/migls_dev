<?php namespace UL\Catalog;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use PW\Tools\Debug;
use Ul\Main\Measure\MeasureSettings;

class SimilarList extends PopularList
{
	protected $USER;

	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component)
	{
		global $USER;
		parent::__construct($component);
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if (intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		return parent::onPrepareComponentParams($arParams);
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		if ($this->startResultCache($this->arParams['CACHE_TIME'], $this->arParams)) {

			$shopId = $this->arParams['SHOP_ID'];

            $defaultMeasure = MeasureSettings::getDefaultMeasure();
            $defaultRatio = MeasureSettings::getDefaultMeasureRatio();

			$oProducts = $this->getProducts($shopId);
			while ($product = $oProducts->fetch()) {
				$product['PRODUCT_PICTURE'] = $this->getDataFile($product['PRODUCT_PICTURE'], 180, 200);
				if (intval($product['PRICE_VAL']) > 0) {
					$product['PRICE_FORMAT'] = \SaleFormatCurrency($product['PRICE_VAL'], 'RUB', true);
				}

                $product['MEASURE_NAME'] = $product['MEASURE_NAME'] ? : $defaultMeasure['MEASURE_TITLE'];
                $product['MEASURE_SHORT_NAME'] = $product['MEASURE_SHORT_NAME'] ? : $defaultMeasure['SYMBOL_RUS'];
                $product['MEASURE_RATIO'] = $product['MEASURE_RATIO'] ? : $defaultRatio;

				$this->arResult['ITEMS'][] = $product;
			}

			$this->includeComponentTemplate();

		}
	}

}
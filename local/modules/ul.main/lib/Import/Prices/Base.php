<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.01.2017
 */

namespace UL\Main\Import\Prices;

use AB\Tools\Debug;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\IO;
use UL\Main\Admins\Import\OffersMeasureSetter;
use UL\Main\Import\Model\PriceTmpTable;
use UL\Main\Import\Model\QueueTable;
use Bitrix\Iblock;
use AB\Tools\Console\ProgressBar;
use UL\Main\CatalogHelper;

Loader::includeModule('iblock');
Loader::includeModule('catalog');

class Base
{
	protected $shopId = null;
	protected $iblockId = null;
	protected $catalogInfo = [];
	protected $root;
	/** @var IO\File */
	protected $FileImport;

	private $csvHeads = [];

	const PRICES_FOLDER = '/upload/PRICES';

	/**
	 * Prices constructor.
	 *
	 * @param $shopId
	 *
	 * @throws \Exception
	 */
	public function __construct($shopId)
	{
		$shopId = intval($shopId);

		if ($shopId == 0){
			throw new \Exception('shop id is null');
		}

		$this->setShopId($shopId);

		$this->root = Application::getDocumentRoot();
	}

	public function setFileImport()
	{
		$fileName = '/price_'.$this->getShopId().'.csv';

		$this->FileImport = new IO\File($this->root.self::PRICES_FOLDER.$fileName);
		$this->FileImport->getDirectory()->create();
		if (!$this->FileImport->isExists()){
			throw new \Exception('Нет файла для импорта.', 310);
		}
	}

	public function readFileToDB()
	{
		PriceTmpTable::dropTable();
		PriceTmpTable::createTable();

		$this->setFileImport();

		$File = new \SplFileObject($this->FileImport->getPath(), 'r');
		$File->setFlags(\SplFileObject::READ_CSV);
		$File->setCsvControl(';');

		foreach ($File as $k => $item) {
			if ($k == 0){
				foreach ($item as $l => $value) {
					switch ($value) {
						case 'ARTICLE':
							$this->csvHeads['ARTICLE'] = $l;
							break;
						case 'BARCODE':
							$this->csvHeads['BARCODE'] = $l;
							break;
						case 'QUANTITY':
							$this->csvHeads['QUANTITY'] = $l;
							break;
						case 'PRICE':
							$this->csvHeads['PRICE'] = $l;
							break;
						case 'NAME':
							$this->csvHeads['NAME'] = $l;
							break;
					}
				}
			} else {

				$save = [
					'ARTICLE' => $item[$this->csvHeads['ARTICLE']],
					'BARCODE' => $item[$this->csvHeads['BARCODE']],
					'QUANTITY' => $item[$this->csvHeads['QUANTITY']],
					'PRICE' => $item[$this->csvHeads['PRICE']],
					'NAME' => $item[$this->csvHeads['NAME']],
					'SHOP_ID' => $this->shopId,
				];

				PriceTmpTable::add($save);
			}
		}
	}

	public static function importRun($shop, $isCron = true)
	{
		if (intval($shop) == 0){
			throw new \Exception('Shop ID is null');
		}

		$CIBlockElement = new \CIBlockElement();
		$CCatalogProduct = new \CCatalogProduct();
		$Bar = new \PW\Tools\ProgressBar();

		$BaseImport = new \UL\Main\Import\Prices\Base($shop);
		$BaseImport->readFileToDB();

		$arSkuIblocks = CatalogHelper::getSkuIblocks();
		$arCatIblocks = CatalogHelper::getCatalogIblocks();
		$skuForCatalog = CatalogHelper::getSkuBlockByProductsBlock();

		$rs = PriceTmpTable::getList([
			'limit' => null,
			'count_total' => true,
		]);

		$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $rs->getCount());

		$arCatIblocksFilter = $arSkuIblocksFilter = [];
		foreach ($arCatIblocks as $iblock) {
			$arCatIblocksFilter[] = $iblock['ID'];
		}

		foreach ($arSkuIblocks as $iblock) {
			$arSkuIblocksFilter[] = $iblock['ID'];
		}

		$i = 0;
		while ($sku = $rs->fetch()) {
			$filter = [
//				'=CODE' => $sku['ARTICLE'],
				'=XML_ID' => $sku['BARCODE'],
				'=IBLOCK.IBLOCK_TYPE_ID' => 'catalog',
				'IBLOCK_ID' => $arCatIblocksFilter,
			];

			$arProduct = Iblock\ElementTable::getRow([
				'select' => ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID'],
				'filter' => $filter,
			]);


			/*if (empty($arProduct) || is_null($arProduct)){
				unset($filter['=XML_ID']);
				$arProduct = Iblock\ElementTable::getRow([
					'select' => ['ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID'],
					'filter' => $filter,
				]);
			}*/

			if (!is_null($arProduct)){
//				Debug::toLog($arProduct);
//				Debug::toLog($filter);


                $offersMeasureSetter = OffersMeasureSetter::getInstance($arProduct['IBLOCK_ID']);
                $offersMeasureSetter->addProductId($arProduct['ID']);


				$arSku = Iblock\ElementTable::getRow([
					'select' => ['ID', 'IBLOCK_ID'],
					'filter' => [
						'=CODE' => $sku['ARTICLE'],
						'IBLOCK_ID' => $arSkuIblocksFilter,
					],
				]);
				if (!is_null($arSku)){
					$skuShop = $CIBlockElement->GetList(
						array(),
						array('IBLOCK_ID' => $arSku['IBLOCK_ID'], '=ID' => $arSku['ID']),
						false,
						array('nTopCount' => 1),
						array('ID','IBLOCK_ID','PROPERTY_SHOP_ID')
					)->Fetch();
					if($skuShop['PROPERTY_SHOP_ID_VALUE'] != $shop){
						$arSku = null;
					}
				}

				if (!is_null($arSku)){
					\CIBlockElement::SetPropertyValuesEx($arSku['ID'], $arSku['IBLOCK_ID'], [
						'SHOP_ID' => $shop,
						'CML2_LINK' => $arProduct['ID'],
						'SECTION_PRODUCT' => $arProduct['IBLOCK_SECTION_ID'],
					]);
					$newSku = $arSku['ID'];
					$CIBlockElement->Update($newSku, ['ACTIVE' => 'Y']);

					$quantity = (float)$sku['QUANTITY'];
					if ($quantity == 0){
						$quantity = 50;
					}

					$CCatalogProduct->Update($newSku, ['QUANTITY' => $quantity]);
					\Bitrix\Catalog\Product\Sku::updateAvailable($newSku, 0, ['QUANTITY' => $quantity]);
					\CPrice::SetBasePrice($newSku, $sku['PRICE'], 'RUB');
				} else {

					$newSku = $CIBlockElement->Add([
						'IBLOCK_ID' => $skuForCatalog[$arProduct['IBLOCK_ID']]['SKU_INFO']['IBLOCK_ID'],
						'NAME' => $arProduct['NAME'],
						'CODE' => $sku['ARTICLE'],
						'PROPERTY_VALUES' => [
							'SHOP_ID' => $sku['SHOP_ID'],
							'CML2_LINK' => $arProduct['ID'],
							'SECTION_PRODUCT' => $arProduct['IBLOCK_SECTION_ID'],
						],
					], false, false);

					if (intval($newSku) == 0){
						$i++;
						if (!$isCron)
							$Bar->update($i);

						Debug::toLog($CIBlockElement->LAST_ERROR);

						continue;
					}
				}

				$quantity = (int)$sku['QUANTITY'];
				if ($quantity == 0){
					$quantity = 50;
				}

				$CCatalogProduct->Add([
					'ID' => $newSku,
					'QUANTITY' => $quantity,
				]);
				\Bitrix\Catalog\Product\Sku::updateAvailable($newSku, 0, ['QUANTITY' => $quantity]);
				\CPrice::SetBasePrice($newSku, $sku['PRICE'], 'RUB');
			}

			$i++;
			if (!$isCron)
				$Bar->update($i);
		}


		if (!$isCron)
			ProgressBar::showGood('Finito la comedia');

//		PriceTmpTable::dropTable();

	}

	/**
	 * @method getShopId
	 * @return null|int
	 * @throws \Exception
	 */
	public function getShopId()
	{
		if (intval($this->shopId) == 0)
			throw new \Exception('shop id is null');

		return $this->shopId;
	}

	/**
	 * @param int|null $shopId
	 *
	 * @return Base
	 */
	public function setShopId($shopId)
	{
		$this->shopId = $shopId;

		return $this;
	}

	/**
	 * @method getIblockId - get param iblockId
	 * @return int|null
	 */
	public function getIblockId()
	{
		return $this->iblockId;
	}

	/**
	 * @param int|null $iblockId
	 *
	 * @return Base
	 */
	public function setIblockId($iblockId)
	{
		$this->iblockId = $iblockId;

		return $this;
	}

	/**
	 * @method getCatalogInfo - get param catalogInfo
	 * @return array|mixed
	 */
	public function getCatalogInfo()
	{
		return $this->catalogInfo;
	}

	/**
	 * @param array|mixed $catalogInfo
	 *
	 * @return Base
	 */
	public function setCatalogInfo($catalogInfo)
	{
		$this->catalogInfo = $catalogInfo;

		return $this;
	}


}
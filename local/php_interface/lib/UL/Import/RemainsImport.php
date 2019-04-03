<?php
namespace UL\Import;

use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Loader;
use PW\Tools\Debug;
use Bitrix\Iblock\ElementTable;
use UL\Main\SkuSectionTable as SkuSection;

includeModules(['catalog', 'ul.main']);

class RemainsImport extends MainIblockImport
{
	protected $CCatalogProduct;

	public function __construct()
	{
		parent::__construct();
		$this->CCatalogProduct = new \CCatalogProduct();
	}

	public function checkElement($data = [])
	{
		return ElementTable::getRow([
			'select' => ['ID', 'PRODUCT_ID' => 'PRODUCT.ID', 'IBLOCK_SECTION_ID'=>'PRODUCT.IBLOCK_SECTION_ID'],
			'filter' => [
				'IBLOCK_ID' => $data['IBLOCK_ID'],
				'=CODE' => $data['CODE'],
			],
			'runtime' => [
				new ReferenceField(
					'PRODUCT',
					ElementTable::getEntity(),
					['=this.CODE' => 'ref.CODE', 'ref.IBLOCK_ID' => array('?i', $_SESSION['IBLOCK_PRODUCT'])]
				),
			],
		]);
	}

	public function saveElement($data = [])
	{
		$row = $this->checkElement($data);
		if (!$row || is_null($row)) {
			$data['ACTIVE'] = 'Y';
			$ID = $this->CIBlockElement->Add($data);
			$this->addNewProducts();
		} else {
			$ID = $row['ID'];
			$data['ACTIVE'] = 'Y';
			$data['PROPERTY_VALUES']['CML2_LINK'] = $row['PRODUCT_ID'];
			$data['PROPERTY_VALUES']['CATEGORY_PRODUCT'] = $row['IBLOCK_SECTION_ID'];
			$data['PROPERTY_VALUES']['SHOP_ID'] = $_SESSION['SHOP_ID_IMPORT'];
			$data['XML_ID'] = $row['PRODUCT_ID'];
			$this->CIBlockElement->Update($row['ID'], $data);
			$this->addUpdated();
		}

		if (intval($ID) > 0) {

			if (\CCatalogProduct::Add([
				'ID' => $ID,
				'QUANTITY' => $data['QUANTITY'],
			])
			) {
				\CPrice::SetBasePrice($ID, $data['PRICE'], 'RUB');
			}

			if(intval($row['IBLOCK_SECTION_ID']) > 0){
				$resultSkuSection = null;
				$section = SkuSection::getRow([
					'select' => ['ID'],
					'filter' => ['=SKU_ID' => $ID]
				]);
				$saveSection = [
					'SECTION_ID' => $row['IBLOCK_SECTION_ID'],
					'SKU_ID' => $ID,
					'SHOP_ID' => $_SESSION['SHOP_ID_IMPORT']
				];
				if(is_null($section)){
					$resultSkuSection = SkuSection::add($saveSection);
				} else {
					$resultSkuSection = SkuSection::update($section['ID'], $saveSection);
				}
			}

		} else {
			$this->errors[] = $this->CIBlockElement->LAST_ERROR;
		}
	}

}
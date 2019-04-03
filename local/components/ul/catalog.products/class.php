<?php namespace UL\Main;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use DigitalWand\MVC\BaseComponent;

Loader::includeModule('iblock');
Loader::includeModule('ab.iblock');

\CBitrixComponent::includeComponentClass("ul:mvc.base");

Loc::loadLanguageFile(__FILE__);

class CatalogProductComponent extends BaseComponent
{

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function actionIndex()
	{
	}

	public function actionCatalog($iblockId)
	{
		$iblockId = (int)$iblockId;

		$this->arResult['IBLOCK_ID'] = $iblockId;
		$iblockInfo = \Bitrix\Iblock\IblockTable::getRow([
			'select' => [
				'ID', 'NAME', 'IBLOCK_TYPE_ID', 'CODE', 'ACTIVE', 'LIST_PAGE_URL',
				'SECTION_PAGE_URL', 'PICTURE', 'DESCRIPTION', 'DESCRIPTION_TYPE',
			],
			'filter' => ['=ID' => $iblockId, '=ACTIVE' => 'Y'],
		]);

		$this->arResult['IBLOCK_INFO'] = $iblockInfo;
		$this->arResult['SHOPS'] = $_SESSION['REGIONS'];

		\CBitrixComponent::includeComponentClass('ul:products.category');
		$ProductCategory = new \Ul\Catalog\ProductCategory();
		$ProductCategory->onPrepareComponentParams([
			'DEPTH_LEVEL' => 2,
			'IBLOCK_TYPE' => 'catalog'
		]);

		$this->arResult['SECTIONS'] = $ProductCategory->getSections();
	}

	public function actionSection($data)
	{
		$this->arResult['SECTION'] = \Bitrix\Iblock\SectionTable::getRow([
			'select' => ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'PICTURE', 'CODE'],
			'filter' => ['=ID' => $data]
		]);

		$this->actionCatalog($this->arResult['SECTION']['IBLOCK_ID']);

		\CBitrixComponent::includeComponentClass('ul:products.category');
		$ProductCategory = new \Ul\Catalog\ProductCategory();
		$ProductCategory->onPrepareComponentParams([
			'DEPTH_LEVEL' => 2,
			'IBLOCK_TYPE' => 'catalog'
		]);

		$this->arResult['SECTIONS'] = $ProductCategory->getSections();

		$this->arResult['NAV_CHAIN'] = \CIBlockSection::GetNavChain($this->arResult['SECTION']['IBLOCK_ID'], $data, [
			'ID', 'NAME', 'IBLOCK_ID'
		], true);

	}
}
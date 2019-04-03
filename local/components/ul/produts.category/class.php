<?php namespace Ul\Catalog;
	/** @var \CBitrixComponent $this */
	/** @var array $arParams */
	/** @var array $arResult */
	/** @var string $componentPath */
	/** @var string $componentName */
	/** @var string $componentTemplate */
	/** @var \CBitrixComponent $component */
	/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock;

Loader::includeModule('iblock');

Loc::loadLanguageFile(__FILE__);

class ProductCategory extends \CBitrixComponent
{

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(intval($arParams['DEPTH_LEVEL']) == 0)
			$arParams['DEPTH_LEVEL'] = 1;

		if(strlen($arParams['IBLOCK_TYPE']) == 0)
			$arParams['IBLOCK_TYPE'] = 'catalog';

		return $arParams;
	}

	public function getCategories()
	{
		$query = new Entity\Query(Iblock\SectionTable::getEntity());
		$obSection = $query
			->setSelect([
				'ID','NAME','IBLOCK_ID','ACTIVE','DEPTH_LEVEL','CODE',
				'MAIN_IMG'=>'IBLOCK.PICTURE', 'MAIN_NAME'=>'IBLOCK.NAME',
				'TYPE_ID' => 'IBLOCK.IBLOCK_TYPE_ID'
			])
			->setFilter([
				'ACTIVE'=>'Y',
				'<=DEPTH_LEVEL'=> $this->arParams['DEPTH_LEVEL'],
				'IBLOCK.IBLOCK_TYPE_ID'=>$this->arParams['IBLOCK_TYPE']
			])
			->setLimit(100)
			->exec();

		$arSections = [];
		while ($sections = $obSection->fetch()){
			$arSections[$sections['TYPE_ID']]['MAIN_NAME'] = $sections['MAIN_NAME'];
			$arSections[$sections['TYPE_ID']]['PICTURE'] = $sections['MAIN_IMG'];
			$arSections[$sections['TYPE_ID']]['IBLOCK_ID'] = $sections['IBLOCK_ID'];
			$arSections[$sections['TYPE_ID']]['ITEMS'][] = $sections;
		}

		$CFile = new \CFile();
		foreach ($arSections as $k => $sect){
			if(intval($sect['PICTURE']) > 0){
				$sect['PICTURE'] = $CFile->ResizeImageGet(
					$sect['PICTURE'],
					['width'=>225, 'height'=>210],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}
			$arSections[$k] = $sect;
		}

		return $arSections;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{

		$this->arResult = $this->getCategories();

		unset($arSections);

		$this->includeComponentTemplate();
	}
}
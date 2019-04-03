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
/** @global \CUserTypeManager $USER_FIELD_MANAGER */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock;
use Bitrix\Main\Type\Dictionary;
use UL\DataCache;
use UL\Tools;

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

		if($arParams){
			$this->arParams = $arParams;
		}

		return $arParams;
	}

	public function getSections()
	{
		global $USER_FIELD_MANAGER;

		$cacheId = Tools::CACHE_KEY_IBLOCK_TYPE.$this->arParams['IBLOCK_TYPE'].md5(serialize($_SESSION['REGIONS']['SHOP_ID']));
		$cacheId .= $this->arParams['IS_BOTTOM_BLOCK'];

		$dataCache = new DataCache($this->arParams['CACHE_TIME'], '/ul/type_catalog', $cacheId);
		$clearCache = false;
		if($dataCache->getIsValid() && !$clearCache){
			$resultSections = $dataCache->getData();
		} else {
			$query = new Entity\Query(Iblock\SectionTable::getEntity());
			$obSection = $query
				->setSelect([
					'ID','NAME','IBLOCK_ID','ACTIVE','DEPTH_LEVEL','CODE',
					'MAIN_IMG'=>'IBLOCK.PICTURE',
					'MAIN_NAME'=>'IBLOCK.NAME',
					'TYPE_ID' => 'IBLOCK.IBLOCK_TYPE_ID',
					'IBLOCK_SECTION_ID',
				])
				->setFilter([
					'ACTIVE'=>'Y',
					'<=DEPTH_LEVEL'=> $this->arParams['DEPTH_LEVEL'],
					'IBLOCK.IBLOCK_TYPE_ID'=>$this->arParams['IBLOCK_TYPE']
				])
				->setOrder(['SORT'=>'ASC', 'ID'=>'DESC'])
				->setLimit(100)
				->exec();

			$arSections = [];
			while ($sections = $obSection->fetch()){
				$shopUrl = false;
				if(strlen($this->arParams['URL_TEMPLATE']) > 0){
					$shopUrl = str_replace('#CITY#', $this->request->get('CITY'), $this->arParams['URL_TEMPLATE']);
					$shopUrl = str_replace('#SHOP#', intval($this->request->get('SHOP_ID')), $shopUrl);
				}

				if((int)$this->arParams['SHOP_ID'] > 0 && (int)$this->arParams['CITY'] > 0 && (int)$this->arParams['CATALOG'] > 0){
					$sections['MAIN_URL'] = '/shop/'.$this->arParams['CITY'].'/'.$this->arParams['SHOP_ID'].'/'.$sections['IBLOCK_ID'].'/';
					$sections['URL_LIST'] = '/shop/'.$this->arParams['CITY'].'/'.$this->arParams['SHOP_ID'].'/'.$sections['IBLOCK_ID'].'/'.$sections['ID'].'/';
					$arSections[$sections['IBLOCK_ID']]['MAIN_URL'] = '/shop/'.$this->arParams['CITY'].'/'.$this->arParams['SHOP_ID'].'/'.$sections['IBLOCK_ID'].'/';
				} else {
					$sections['URL_LIST'] = '/catalog/'.$sections['IBLOCK_ID'].'/'.$sections['ID'].'/';
					$sections['MAIN_URL'] = '/catalog/'.$sections['IBLOCK_ID'].'/';
					$arSections[$sections['IBLOCK_ID']]['MAIN_URL'] = '/catalog/'.$sections['IBLOCK_ID'].'/';
				}


				$arSections[$sections['IBLOCK_ID']]['MAIN_NAME'] = $sections['MAIN_NAME'];
				$arSections[$sections['IBLOCK_ID']]['PICTURE'] = $sections['MAIN_IMG'];
				$arSections[$sections['IBLOCK_ID']]['IBLOCK_ID'] = $sections['IBLOCK_ID'];
				$arSections[$sections['IBLOCK_ID']]['ITEMS'][$sections['ID']] = $sections;
			}
			$CFile = new \CFile();
			foreach ($arSections as $k => $sect){
				if(intval($sect['PICTURE']) > 0){
					$sect['PICTURE'] = $CFile->ResizeImageGet(
						$sect['PICTURE'],
						['width'=>270, 'height' => 270],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
						true
					);
				}


				foreach ($sect['ITEMS'] as $id => $item){
					if($item['DEPTH_LEVEL'] > 1){
						$sect['ITEMS'][$item['IBLOCK_SECTION_ID']]['SUBSECTION'][] = $item;
						unset($sect['ITEMS'][$id]);
					}
				}

				$arSections[$k] = $sect;

			}

//			$arUFields = $USER_FIELD_MANAGER->GetUserFieldValue('ASD_IBLOCK','UF_MENU_IMG', $sections['IBLOCK_ID']);

			$resultSections = $arSections;

			/*$resultSections = [];
			if($this->arParams['IS_BOTTOM_BLOCK'] === 'Y'){
				$resultSections = $arSections;
			} else {
				$Dictionary = new Dictionary($arSections);
				foreach ($Dictionary as $id => $item) {
					if($item['DEPTH_LEVEL'] == 1){
						$resultSections[$item['ID']] = $Dictionary->get($item['ID']);
					}
				}
				foreach ($Dictionary as $id => $item) {
					if($item['DEPTH_LEVEL'] == 2){
						$resultSections[$item['IBLOCK_SECTION_ID']]['ITEMS'][] = $Dictionary->get($item['ID']);
					}
				}

			}*/

			$dataCache->writeVars($resultSections);
		}

		return $resultSections;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->arResult = $this->getSections();

		$this->includeComponentTemplate();
	}
}
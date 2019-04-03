<?php namespace UL\Main\Catalog;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main as BX;
use Bitrix\Main\Localization\Loc;
use Mig\BasketComponent;
use Soft\IBlock\ElementTable;
use UL\DataCache;

BX\Loader::includeModule('iblock');
BX\Loader::includeModule('ab.iblock');

Loc::loadLanguageFile(__FILE__);
\CBitrixComponent::includeComponentClass('mig:basket');

class CategoriesComponent extends \CBitrixComponent
{

	/** @var BX\Result */
	protected $result;

	/** @var BX\Entity\Query */
	protected $querySection;

	protected $iblockInfo = [];

	private static $urlCache = [];

	const CATALOG_IB_TYPE = 'catalog';

	private $cacheIblockInfo = [];

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->result = new BX\Result();
		$this->querySection = new BX\Entity\Query(\Bitrix\Iblock\SectionTable::getEntity());
	}

	protected function addError($msg)
	{
		$this->result->addError(new BX\Error($msg));
	}

	protected function getErrors()
	{
		return implode(', ', $this->result->getErrorMessages());
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

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

	/**
	 * @method makeUrl
	 * @param $data
	 * @param $template
	 *
	 * @return string
	 */
	protected function makeUrl($data, $template)
	{
		$url = '';
		$template = str_replace('#SITE_DIR#', '', $template);
		foreach ($data as $code => $val) {
			$template = str_replace('#'.$code.'#', $val, $template);
		}

		return $template;
	}

	/**
	 * @method setFilter
	 * @param $iblock
	 */
	protected function setFilter($iblock)
	{
		$this->querySection->setFilter(['ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y']);

		if (is_array($iblock)){
			$this->querySection->addFilter('@IBLOCK_ID', $iblock);
		} else {
			$this->querySection->addFilter('=IBLOCK_ID', (int)$iblock);
		}
	}

	protected function compileTreeSections(BX\DB\Result $sectionIterator)
	{
		$sectionList = $depthLevel = $sectionProducts = [];
		while ($section = $sectionIterator->fetch()) {

			$urlTemple = $this->iblockInfo[$section['IBLOCK_ID']]['SECTION_PAGE_URL'];
			if(strlen($this->arParams['URL_TEMPLATE']['SECTION_URL']) > 0){
				$urlTemple = $this->arParams['URL_TEMPLATE']['SECTION_URL'];
			}

			if(intval($this->arParams['SHOP_ID']) > 0){
				$section['SHOP_ID'] = $this->arParams['SHOP_ID'];
			}

			if(isset($this->iblockInfo[$section['IBLOCK_ID']])){
				$section['SECTION_PAGE_URL'] = $this->makeUrl($section, $urlTemple);
			}

			if($section['DEPTH_LEVEL'] >= 2){
				$existProduct = $this->existProductsInSection($section['SECTION_ID'], $section['IBLOCK_ID']);
				if(is_null($existProduct)){
					continue;
				}
			}


			$sectionList[$section['SECTION_ID']] = $section;
			$depthLevel[] = $section['DEPTH_LEVEL'];
		}

		$depthLevelResult = array_unique($depthLevel);
		rsort($depthLevelResult);

		$iMaxLevel = $depthLevelResult[0];

		for ($i = $iMaxLevel; $i > 1; $i--) {
			foreach ($sectionList as $iSectionId => $item) {
				if ($item['DEPTH_LEVEL'] == $i){
					$sectionList[$item['IBLOCK_SECTION_ID']]['SUB_SECTION'][] = $item;
					unset($sectionList[$iSectionId]);
				}
			}
		}



		if ($this->arParams['SORT'] === 'SORT'){
			usort($sectionList, array($this, 'sectionSort'));
		}

//		foreach ($sectionList as $k => $item) {
//			if(count($item['SUB_SECTION']) == 0){
//				unset($sectionList[$k]);
//			}
//		}


		return $sectionList;
	}

	public function existProductsInSection($sectionId, $iblockId)
	{
		if(!$this->cacheIblockInfo[$iblockId]){
			$this->cacheIblockInfo[$iblockId] = \CCatalogSku::GetInfoByIBlock($iblockId);
		}

		try {
			$product = ElementTable::getRow([
				'select' => ['ID'],
				'filter' => [
					'IBLOCK_ID' => $this->cacheIblockInfo[$iblockId]['IBLOCK_ID'],
					'PROPERTY.CML2_LINK.IBLOCK_SECTION_ID' => $sectionId,
					'PROPERTY.SHOP_ID' => $this->arParams['SHOP_ID']
				]
			]);
//			dump($product);

			return $product;
		} catch (\Exception $err){
//			dump($err);
		}

		return null;
	}

	/**
	 * @method getSectionsCatalog
	 *
	 * @return array
	 */
	public function getSectionsCatalog()
	{
		$this->arResult['SECTIONS'] = [];

		$this->querySection->setSelect([
			'SECTION_ID' => 'ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PICTURE', 'SORT',
			'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL', 'DESCRIPTION', 'CODE', 'XML_ID', 'DETAIL_PICTURE',
		])
			->setOrder(['DEPTH_LEVEL' => 'ASC'])
			->setLimit(null);

		$sectionIterator = $this->querySection->exec();

		return $this->compileTreeSections($sectionIterator);
	}

	/**
	 * @method sectionSort
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	private function sectionSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']){
			return 0;
		}
		if ($this->arParams['SORT_ORDER'] === 'ASC'){
			return ($a['SORT'] < $b['SORT']) ? -1 : 1;
		} else {
			return ($a['SORT'] > $b['SORT']) ? -1 : 1;
		}
	}

	/**
	 * @method getCatalogs
	 * @return array
	 * @throws \Exception
	 */
	public function getCatalogs()
	{
		$arIblocks = [];
		$iblockIterator = \Bitrix\Iblock\IblockTable::getList([
			'select' => [
				'ID', 'NAME', 'CODE', 'SORT',
				'LIST_PAGE_URL', 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL',
				'PICTURE', 'DESCRIPTION',
			],
			'filter' => ['=IBLOCK_TYPE_ID' => self::CATALOG_IB_TYPE, 'ACTIVE' => 'Y'],
			'order' => ['SORT' => 'ASC'],
		]);
		while ($rs = $iblockIterator->fetch()) {
			$this->arResult['IBLOCKS'][] = $rs['ID'];
			$rs['IBLOCK_ID'] = $rs['ID'];

			if(strlen($this->arParams['URL_TEMPLATE']['CATALOG_URL']) > 0){
				$rs['LIST_PAGE_URL'] = $this->arParams['URL_TEMPLATE']['CATALOG_URL'];
			}

			$rs['MAIN_CATALOG_URL'] = $this->makeUrl($rs, $rs['LIST_PAGE_URL']);

			$arIblocks[$rs['ID']] = $rs;
		}

		if (count($arIblocks) == 0)
			throw new \Exception('Каталоги не найдены');

		return $arIblocks;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		try {

			$cacheId = md5(serialize($this->arParams));
			$dataCache = new DataCache($this->arParams['CACHE_TIME'], '/ul/catalog/sections', $cacheId);

			if($dataCache->getIsValid() && $this->arParams['CACHE_TYPE'] != 'N'){
				$this->arResult['SECTIONS'] = $dataCache->getData();
			} else {
				$this->iblockInfo = $this->getCatalogs();
				$this->setFilter($this->arResult['IBLOCKS']);
				$sections = $this->getSectionsCatalog();
				$resultSection = [];
				foreach ($this->iblockInfo as $id => $block) {
					foreach ($sections as $idSection => $arSection) {
						if($arSection['IBLOCK_ID'] == $id){
							$block['SECTIONS'][$idSection] = $arSection;
							$resultSection[$id] = $block;
						}
					}
				}
				$this->arResult['SECTIONS'] = $resultSection;

//				dump($this->cacheIblockInfo);
//				dump($sectionList);

				unset($sections);
				$dataCache->writeVars($resultSection);
			}
		} catch (\Exception $err) {
			$this->addError($err->getMessage());
		}

		if ($this->result->isSuccess()){
			$this->arResult['COMPONENT_PARENT'] = $this->arParams;

			$this->includeComponentTemplate();
		} else {
			\ShowError($this->getErrors());
		}


		return $this->arResult['SECTIONS'];
	}
}
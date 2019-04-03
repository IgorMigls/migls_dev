<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 28.04.2017
 */

namespace AB\Tools\Console\Scripts;

use AB\Tools\Debug;
use Bitrix\Main;
use AB\Tools\Console\ProgressBar;
use Bitrix\Iblock;


Main\Loader::includeModule('ab.iblock');
Main\Loader::includeModule('iblock');
Main\Loader::includeModule('catalog');

class CopyProducts implements IConsole
{
	const CATALOG_IB = 44;

	private $params;
	private $selectIb = 0;
	private $CIBlockSection;
	private $CIBlockElement;
	private $sectionProducts;
	private $metaPropsCatalog;

	public function __construct($params = [])
	{
		global $argv;

		if (count($params) == 0 || is_null($params)){
			$this->params = $argv;
		}

		$this->params = $params;
		$this->root = Main\Application::getDocumentRoot();

		$this->CIBlockSection = new \CIBlockSection();
		$this->CIBlockElement = new \CIBlockElement();
	}

	protected function getSections()
	{

		$filter = ['=IBLOCK_ID' => $this->selectIb];

		$sectIterator = Iblock\SectionTable::getList([
			'select' => ['ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL', 'SORT'],
			'filter' => $filter,
			'order' => ['DEPTH_LEVEL' => 'DESC'],

		]);

		$ar_SectionList = array();
		$ar_DepthLavel = array();

		while ($ar_Section = $sectIterator->fetch()) {
			$ar_SectionList[$ar_Section['ID']] = $ar_Section;
			$ar_DepthLavel[] = $ar_Section['DEPTH_LEVEL'];
		}
		$ar_DepthLavelResult = array_unique($ar_DepthLavel);
		rsort($ar_DepthLavelResult);

		$i_MaxDepthLevel = $ar_DepthLavelResult[0];
		for ($i = $i_MaxDepthLevel; $i > 1; $i--) {
			foreach ($ar_SectionList as $i_SectionID => $ar_Value) {
				if ($ar_Value['DEPTH_LEVEL'] == $i){
					$ar_SectionList[$ar_Value['IBLOCK_SECTION_ID']]['SUB_SECTION'][] = $ar_Value;
					unset($ar_SectionList[$i_SectionID]);
				}
			}
		}
		usort($ar_SectionList, array($this, '__sectionSort'));

		return $ar_SectionList;
	}

	private function __sectionSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']){
			return 0;
		}

		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}

	public function getProducts($sectionId = false)
	{
		$elements = [];
		$productIterator = \CIBlockElement::GetList(
			array('ID' => 'ASC'),
			array('IBLOCK_ID' => $this->selectIb, 'SECTION_ID' => $sectionId),
			false,
			false,
			array(
				'ID', 'NAME', 'CODE', 'IBLOCK_ID', 'DETAIL_PICTURE',
			)
		);
		while ($item = $productIterator->Fetch()) {
			if ((int)$item['DETAIL_PICTURE'] > 0){
				$item['DETAIL_PICTURE'] = \CFile::MakeFileArray($item['DETAIL_PICTURE']);
			}
			$elements[] = $item;
		}

		return $elements;
	}

	public function getSectionProducts($arSections)
	{
		foreach ($arSections as $section) {
			$this->sectionProducts[$section['ID']] = $section;
			$this->sectionProducts[$section['ID']]['ELEMENTS'] = $this->getProducts($section['ID']);
			if (count($section['SUB_SECTION']) > 0){
				$this->sectionProducts[$section['ID']]['SUB_SECTION']['ELEMENTS'] = $this->getSectionProducts($section['SUB_SECTION']);
			}
		}
	}

	public function getSectionToCopy($sectionName)
	{
		return Iblock\SectionTable::getRow([
			'select' => ['ID'],
			'filter' => ['NAME' => $sectionName, 'IBLOCK_ID' => self::CATALOG_IB],
		]);
	}

	public function propertiesCatalog()
	{
		$oProps = Iblock\PropertyTable::getList([
			'filter' => ['IBLOCK_ID' => self::CATALOG_IB],
		]);
		while ($rs = $oProps->fetch()) {
			$this->metaPropsCatalog[$rs['CODE']] = $rs;
		}
	}

	protected function getProductNew($name)
	{
		return Iblock\ElementTable::getRow([
			'select' => ['ID', 'NAME', 'IBLOCK_ID', 'XML_ID'],
			'filter' => ['=NAME' => $name, '=IBLOCK_ID' => self::CATALOG_IB],
		]);
	}

	public function run($params)
	{
		global $USER;
		$USER->Authorize(880);

		$this->selectIb = (int)$params['-ib'];
		if ($this->selectIb > 0){
//			$arSections = $this->getSections();
//			$this->getSectionProducts($arSections);
//			$this->propertiesCatalog();

			$productIterator = \CIBlockElement::GetList(
				array('ID' => 'ASC'),
				array('IBLOCK_ID' => $this->selectIb),
				false,
				false,
				array(
					'ID', 'NAME', 'CODE', 'IBLOCK_ID', 'DETAIL_PICTURE',
				)
			);
			while ($item = $productIterator->Fetch()) {
//				ProgressBar::pre($item);
//				exit;

				$this->copyDetailPicture($item);
				$this->copyMorePhoto($item);
			}
		}
	}

	protected function copyMorePhoto($item)
	{
		$photoValues = [];
		$obPhotoValues = \CIBlockElement::GetProperty(
			$item['IBLOCK_ID'], $item['ID'],
			array(), array('CODE' => 'MORE_PHOTO')
		);
		$n = 0;
		while ($rs = $obPhotoValues->Fetch()) {
			$val = \CFile::MakeFileArray($rs['VALUE']);
			$val['COPY_FILE'] = 'N';
			$val['old_file'] = false;

			$photoValues['n'.$n] = ['VALUE'=>$val];
			$n++;
		}

		$newProduct = Iblock\ElementTable::getRow([
			'select' => ['ID', 'NAME', 'IBLOCK_ID', 'XML_ID'],
			'filter' => ['=XML_ID' => $item['ID'], '=IBLOCK_ID' => self::CATALOG_IB],
		]);
		if (!is_null($newProduct)){
			\CIBlockElement::SetPropertyValuesEx(
				$newProduct['ID'], $newProduct['IBLOCK_ID'], array(
				'MORE_PHOTO' => $photoValues,
			));
		}
//		Debug::toLog($photoValues);
	}

	protected function copyDetailPicture($item)
	{
		$picture = \CFile::GetFileArray($item['DETAIL_PICTURE']);
//				Debug::toLog($picture);
		$detailPicture = \CFile::MakeFileArray($picture['SRC']);
		$detailPicture['COPY_FILE'] = 'N';
		$detailPicture['old_file'] = false;

		$arSrc = array(
			'IBLOCK_ID' => self::CATALOG_IB,
			'DETAIL_PICTURE' => $detailPicture,
			'XML_ID' => $item['ID'],
		);

		$newProduct = $this->getProductNew($item['NAME']);
		if (!is_null($newProduct)){
			$res = $this->CIBlockElement->Update($newProduct['ID'], $arSrc);
			if (!$res){
				Debug::toLog($this->CIBlockElement->LAST_ERROR);
			}
		}
	}

	public function iterateUpdateProduct($arSection)
	{
		foreach ($arSection as $section) {
			foreach ($section['ELEMENTS'] as $item) {
				$arSrc = array(
					'IBLOCK_ID' => self::CATALOG_IB,
					'DETAIL_PICTURE' => $item['DETAIL_PICTURE'],
					'XML_ID' => $item['ID'],
				);
				$newProduct = $this->getProductNew($item['NAME']);
				if (!is_null($newProduct)){
					$this->CIBlockElement->Update($newProduct['ID'], $arSrc);
				}
			}
			if (count($arSection['SUB_SECTION']) > 0){
				$this->iterateUpdateProduct($arSection['SUB_SECTION']);
			}
		}
	}
}
<?php
namespace UL\Main\Help;
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
use Bitrix\Main;

Loc::loadLanguageFile(__FILE__);
Loader::includeModule('iblock');

class HelpComponent extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;

	protected static $IBLOCK_ID = 25;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams = [])
	{
		if (intval($arParams['IBLOCK_ID']) > 0){
			self::$IBLOCK_ID = $arParams['IBLOCK_ID'];
		}

		$this->arParams = $arParams;
		return $arParams;
	}

	/**
	 * @method getUser
	 * @return bool|\CDBResult|\CUser
	 */
	public function getUser()
	{
		global $USER;
		if (!is_object($USER))
			$USER = new \CUser();

		return $USER;
	}

	/**
	 * @method getSectionsAction
	 * @param array $data
	 *
	 * @return array
	 */
	public function getSectionsAction($data = [])
	{
		$filter = array('=IBLOCK_ID' => self::$IBLOCK_ID, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y');
		if (intval($data['sectionParent']) > 0){
			$filter['SECTION_ID'] = $data['sectionParent'];
		} else {
			$filter['DEPTH_LEVEL'] = 1;
		}
		$obSections = \CIBlockSection::GetList(
			array('SORT' => 'ASC', 'NAME' => 'ASC'),
			$filter,
			false,
			array('ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID')
		);
		$arSections = [];
		while ($s = $obSections->Fetch()) {
			$obElement = \CIBlockElement::GetList(
				array('SORT' => 'ASC', 'NAME' => 'ASC'),
				array('IBLOCK_ID' => self::$IBLOCK_ID, '=ACTIVE' => 'Y', '=SECTION_ID' => $s['ID']),
				false,
				array('nTopCount' => 15),
				array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT')
			);
			$arElements = [];
			while ($element = $obElement->Fetch()){
				$arElements[] = $element;
			}
			$s['elements'] = $arElements;
			$arSections[] = $s;
		}

		return $arSections;
	}

	/**
	 * @method getElements
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getElementsAction($data = [])
	{
		$sectionId = intval($data['sectionParent']);
		if ($sectionId == 0){
			throw new \Exception('Parent section id null');
		}

		$obElement = \CIBlockElement::GetList(
			array('SORT' => 'ASC', 'NAME' => 'ASC'),
			array('IBLOCK_ID' => self::$IBLOCK_ID, '=ACTIVE' => 'Y', '=SECTION_ID' => $sectionId),
			false,
			array('nTopCount' => 15),
			array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT')
		);
		$arElements = [];
		while ($element = $obElement->Fetch()){
			$arElements[] = $element;
		}

		return $arElements;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}
}

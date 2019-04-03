<?php namespace React\Doc;
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
use Bitrix\Iblock;

Loc::loadLanguageFile(__FILE__);
Loader::includeModule('iblock');

class SectionList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;

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
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		global $USER;

		if ($this->startResultCache(false, ($this->arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()))){

			if (!Loader::includeModule("iblock")){
				$this->abortResultCache();
				ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));

				return;
			}

			$arFilter = array(
				"ACTIVE" => "Y",
				"GLOBAL_ACTIVE" => "Y",
				"IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
			);
			$arFilter["<="."DEPTH_LEVEL"] = $this->arParams["TOP_DEPTH"];
			$arSelect = [
				'ID', 'NAME', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL',
				'IBLOCK_ID', 'IBLOCK_SECTION_ID',
			];
			$arSort = ['SORT' => 'ASC'];

			$obSection = Iblock\SectionTable::getList([
				'select' => $arSelect,
				'filter' => $arFilter,
				'order' => $arSort,
			]);
			while ($section = $obSection->fetch()) {
				$section['ITEMS'] = Iblock\ElementTable::getList([
					'select' => ['ID', 'NAME', 'CODE'],
					'filter' => ['IBLOCK_ID' => $this->arParams["IBLOCK_ID"], 'ACTIVE' => 'Y', 'IBLOCK_SECTION_ID' => $section['ID']],
					'order' => ['SORT' => 'ASC', 'ID' => 'ASC'],
				])->fetchAll();

				$this->arResult['SECTIONS'][] = $section;
			}

			$this->includeComponentTemplate();
		}
	}
}
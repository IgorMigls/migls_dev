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

Loc::loadLanguageFile(__FILE__);
Loader::includeModule('iblock');

class Element extends \CBitrixComponent
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
		$elementId = $this->request->get('ELEMENT_ID');
		$arFilter = [
			'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			'ACTIVE' => 'Y',
		];
		if(preg_match('#[a-zA-Z]+#', $elementId)){
			$arFilter['=CODE'] = $elementId;
		} elseif(intval($elementId) > 0) {
			$arFilter['=ID'] = $elementId;
		} else {
			return null;
		}

		$obElement = \CIBlockElement::GetList(
			[],
			$arFilter,
			false,
			['nTopCount' => 1],
			[
				'ID','NAME','IBLOCK_ID', 'DETAIL_TEXT', 'DETAIL_TEXT_TYPE'
			]
		);

		$this->arResult['ELEMENT'] = $obElement->GetNext();

		$this->includeComponentTemplate();
	}
}
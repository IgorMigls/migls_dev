<?php namespace UL\Products;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

\CBitrixComponent::includeComponentClass('ul:products.category');

class AllProducts extends \CBitrixComponent
{
	protected $USER;

	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component)
	{
		global $USER;
		parent::__construct($component);
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if (intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		if(intval($arParams['DEPTH_LEVEL']) == 0)
			$arParams['DEPTH_LEVEL'] = 1;

		if(strlen($arParams['IBLOCK_TYPE']) == 0)
			$arParams['IBLOCK_TYPE'] = 'catalog';

		return $arParams;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{

		$ProductCategory = new \UL\Catalog\ProductCategory($this);
		$this->arResult['SECTIONS'] = $ProductCategory->getSections();

		$this->includeComponentTemplate();
	}
}
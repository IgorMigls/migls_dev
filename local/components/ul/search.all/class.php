<?php 
namespace UL\Search;

/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;

class AllSearch extends \CBitrixComponent
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
		if(intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		return $arParams;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		$this->arResult["FORM_ACTION"] = htmlspecialcharsbx(str_replace("#SITE_DIR#", SITE_DIR, $this->arParams["PAGE"]));

		\CBitrixComponent::includeComponentClass('ul:shop.list');
		$Shop = new \UL\Shops\ShopList();
		$this->arResult['SHOPS'] = $Shop->getShops();

		$this->includeComponentTemplate();
	}

}
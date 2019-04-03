<?php namespace UL\Shops;
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
\CBitrixComponent::includeComponentClass('ul:shop.detail');

Loc::loadLanguageFile(__FILE__);

class BasketTimes extends ShopDetail
{

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
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{

		foreach ($_SESSION['REGIONS']['SHOP_ID'] as $shop) {
			$this->arResult['ALL_SHOPS'][$shop] = $this->getShopInfo($shop);
		}

		$this->includeComponentTemplate();
	}
}
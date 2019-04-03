<?php namespace UL\Main;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Iblock\Element;
use AB\Tools\Debug;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use DigitalWand\MVC\BaseComponent;
use function dump;
use Mig\BasketComponent;

Loader::includeModule('iblock');
Loader::includeModule('ab.iblock');

\CBitrixComponent::includeComponentClass('mig:basket');
\CBitrixComponent::includeComponentClass("ul:mvc.base");

//define('BX_DEBUG', true);
Loc::loadLanguageFile(__FILE__);
class ShopCatalogComponent extends BaseComponent
{

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

	public function actionIndex()
	{

	}

	public function actionDetailshop($shopId)
	{
		\CBitrixComponent::includeComponentClass('ul:shop.detail');
		$Shop = new \UL\Shops\ShopDetail();

		foreach ($_SESSION['REGIONS']['SHOP_ID'] as $shop) {
			$this->arResult['ALL_SHOPS'][$shop] = $Shop->getShopInfo($shop);
		}

		$this->arResult['SHOP_INFO'] = $this->arResult['ALL_SHOPS'][$shopId];
		$Basket = new BasketComponent();
		$shopData = $Basket->getShopInfo($this->arResult['SHOP_INFO']['ID']);
		$this->arResult['SHOP_INFO']['DELIVERY_TIME'] = $shopData['CALENDAR'];
		$this->arResult['SHOP_INFO']['CURRENT'] = $shopData['CURRENT'];

		$this->arResult['SECTIONS'] = $Shop->getCategories($shopId);


		/*$havka = $this->arResult['SECTIONS'][66]['ITEMS'];
		usort($havka, array($this, 'jratvaSort'));

		foreach ($havka as &$arItem) {
			if (intval($arItem['PICTURE']) > 0){
				$arItem['PICTURE'] = \CFile::GetFileArray($arItem['PICTURE']);
			}
			$arItem['URL'] = $this->arResult['SECTIONS'][66]['MAIN_URL'].$arItem['ID'].'/';
		}

		$this->arResult['HAVKA'] = $havka;
		$this->arResult['HAVKA_INFO'] = [
			'URL' => $this->arResult['SECTIONS'][66]['MAIN_URL'],
			'NAME' => $this->arResult['SECTIONS'][66]['MAIN_NAME'],
		];*/

//		dd($this->arResult['SECTIONS']);

		foreach ($this->arResult['SECTIONS'] as $SECTION) {
//			dump($SECTION);
			$this->arResult['IMAGES'][] = $SECTION;
		}
	}

	private function jratvaSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']){
			return 0;
		}

		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}

	public function actionCatalog($shopId, $iblockId)
	{
		\CBitrixComponent::includeComponentClass('ul:shop.detail');
		$Shop = new \UL\Shops\ShopDetail();

		foreach ($_SESSION['REGIONS']['SHOP_ID'] as $shop) {
			$this->arResult['ALL_SHOPS'][$shop] = $Shop->getShopInfo($shop);
		}

		$this->arResult['SHOP_INFO'] = $this->arResult['ALL_SHOPS'][$shopId];
		$this->arResult['IBLOCK_ID'] = $iblockId;
		$iblockInfo = \Bitrix\Iblock\IblockTable::getRow([
			'select' => [
				'ID', 'NAME', 'IBLOCK_TYPE_ID', 'CODE', 'ACTIVE', 'LIST_PAGE_URL',
				'SECTION_PAGE_URL', 'PICTURE', 'DESCRIPTION', 'DESCRIPTION_TYPE',
			],
			'filter' => ['=ID' => $iblockId, '=ACTIVE' => 'Y'],
		]);

		$this->arResult['IBLOCK_INFO'] = $iblockInfo;

		$Basket = new BasketComponent();
		$shopData = $Basket->getShopInfo($this->arResult['SHOP_INFO']['ID']);
		$this->arResult['SHOP_INFO']['DELIVERY_TIME'] = $shopData['CALENDAR'];
		$this->arResult['SHOP_INFO']['CURRENT'] = $shopData['CURRENT'];

		$this->arResult['SECTIONS'] = $Shop->getCategories($shopId);
	}

	public function actionSection($shopId, $iblockId, $section)
	{

		$this->actionCatalog($shopId, $iblockId);
		$this->arResult['SECTION_ID'] = $section;

		\CBitrixComponent::includeComponentClass('ul:shop.detail');
		$Shop = new \UL\Shops\ShopDetail();

		$Basket = new BasketComponent();
		$shopData = $Basket->getShopInfo($shopId);
		$this->arResult['SHOP_INFO']['DELIVERY_TIME'] = $shopData['CALENDAR'];
		$this->arResult['SHOP_INFO']['CURRENT'] = $shopData['CURRENT'];

		$this->arResult['SECTIONS'] = $Shop->getCategories($shopId);
	}

	public function actionSectionAjax($shopId, $iblockId, $section)
	{
		$this->arResult['IBLOCK_ID'] = $iblockId;
		$this->arResult['SECTION_ID'] = $section;
	}
}
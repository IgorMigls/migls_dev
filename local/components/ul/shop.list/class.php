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

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use Bitrix\Main\Data;
use PW\Tools\Debug;
use UL\DataCache;

Loc::loadLanguageFile(__FILE__);

Loader::includeModule('soft.iblock');
Loader::includeModule('iblock');

class ShopList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	protected $CIBlockElement;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
		$this->CIBlockElement = new \CIBlockElement();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams['ID_REGIONS'] = $_SESSION['REGIONS']['ID'];

		return $arParams;
	}

	public function getShops()
	{
		$cnt = 20;
		if ($this->arResult['COUNT']) {
			$cnt = $this->arResult['COUNT'];
		}

		if(empty($_SESSION['REGIONS']['SHOP_ID']))
			return null;

		$cache = new DataCache(86400, '/ul/shops', 'shop_list_small_'.md5(serialize($_SESSION['REGIONS']['CITY_ID'])));
		if($cache->getIsValid()){
			$arCities = $cache->getData();
		} else {
			$obCity = $this->CIBlockElement->GetList(
				array('SORT' => 'ASC'),
				array(
					'IBLOCK_ID' => 5,
					'ACTIVE' => 'Y',
					'INCLUDE_SUBSECTIONS' => 'Y',
					'=ID' => $_SESSION['REGIONS']['SHOP_ID'],
				    'SECTION_ID' => $_SESSION['REGIONS']['CITY_ID']
				),
				false,
				array('nTopCount' => $cnt),
				array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PICTURE', 'CODE', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'PROPERTY_NO_AVAILABLE')
			);
			$arCities = null;
			while ($city = $obCity->GetNext(true, false)) {
				$arCities[$city['ID']] = $city;
			}

			// [DETAIL_PAGE_URL] => /shop/28/150624/

			$cache->writeVars($arCities);
		}

		return $arCities;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{

		if(!$_SESSION['SHOPS']){
			$arShop = $this->getShops();
			$_SESSION['SHOPS'] = $arShop;
		}


		/*$clearCache = $this->request['clear_cache'] == 'Y' && $this->USER->IsAdmin() ? true : false;

		if ($Cache->initCache(86400, 'shop_list', '/ul/shop') && !$clearCache) {
			$arShop = $Cache->getVars();
		} else {
			$Cache->startDataCache();
			$TagCache->startTagCache('/ul/shop');

			$arShop = $this->getShops();

			$TagCache->registerTag($cacheId);
			$TagCache->endTagCache();
			$Cache->endDataCache($arShop);
		}*/

		$this->arResult['ITEMS'] = $_SESSION['SHOPS'];

		$this->includeComponentTemplate();
	}
}
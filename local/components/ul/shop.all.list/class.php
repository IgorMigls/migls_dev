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
use PW\Tools\Debug;
use UL\DataCache;

Loader::includeModule('iblock');

\CBitrixComponent::includeComponentClass('ul:shop.list');

Loc::loadLanguageFile(__FILE__);

class AllList extends ShopList
{

	/**
	 * @method getCityList
	 * @return null|array
	 */
	public function getCityList()
	{
		$filter = [
			'IBLOCK_ID' => 5,
			'ACTIVE' => 'Y',
//			'INCLUDE_SUBSECTIONS' => 'Y',
			'SECTION_ID' => $_SESSION['REGIONS']['CITY_ID'],
			'PROPERTY_OPENING_SOON' => 0,
//			'=ID' => $_SESSION['REGIONS']['SHOP_ID']
		];

		if($this->arParams['OPENING_SOON'] == 'Y'){
			unset($filter['PROPERTY_OPENING_SOON']);
			$filter['PROPERTY_OPENING_SOON'] = 1;
		}

		$obCity = $this->CIBlockElement->GetList(
			array('SORT' => 'ASC'),
			$filter,
			false,
			array('nTopCount' => 9),
			array(
				'ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PICTURE', 'CODE', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT',
				'PROPERTY_NO_AVAILABLE', 'PROPERTY_NO_AVAILABLE_TXT',
			)
		);
		$arCities = null;
		while ($city = $obCity->GetNext(true, false)) {
			if (intval($city['DETAIL_PICTURE']) > 0){
				$city['IMG'] = \CFile::ResizeImageGet(
					$city['DETAIL_PICTURE'],
					['width' => 250, 'height' => 80],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}

			$cnt = 0;
			foreach ($_SESSION['REGIONS']['SHOP_ID'] as $value) {
				if ($city['ID'] == $value){
					$cnt++;
					break;
				}
			}
			$city['HIDE'] = true;
			if ($cnt > 0 && empty($city['PROPERTY_NO_AVAILABLE_VALUE'])){
				$city['HIDE'] = false;
			}
			$arCities[$city['ID']] = $city;
		}

		return $arCities;
	}

	/**
	 * @method getList
	 * @return array|null
	 */
	public function getList()
	{

		$cacheId = md5(serialize($_SESSION['REGIONS']['CITY_ID'])).$this->arParams['OPENING_SOON'];

		$cache = new DataCache(3600, '/ul/shops', 'shop_list_'.$cacheId);
		if ($cache->getIsValid()){
			$arCities = $cache->getData();
		} else {
			$arCities = $this->getCityList();
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

		if (!empty($_SESSION['REGIONS']['CITY_ID'])){
			$this->arResult['ITEMS'] = $this->getList();
		}

		$this->includeComponentTemplate();
	}
}
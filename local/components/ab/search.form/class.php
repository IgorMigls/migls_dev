<?php namespace AB;
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
use Bitrix\Main\Type\Dictionary;
use UL\Main\CatalogHelper;
use UL\Main\Search;

Loc::loadLanguageFile(__FILE__);
Loader::includeModule('search');
Loader::includeModule('iblock');
Loader::includeModule('catalog');

class SearchComponent extends \CBitrixComponent
{
	protected $CIBlockElement;
	protected $CIBlockSection;
	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->CIBlockElement = new \CIBlockElement();
		$this->CIBlockSection = new \CIBlockSection();
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

	public function getResultAction($data = []){

		$query = $data['q'];
		$alt_query = '';
		\CUtil::decodeURIComponent($query);
		$arLang = \CSearchLanguage::GuessLanguage($query);
		if (is_array($arLang) && $arLang["from"] != $arLang["to"])
			$alt_query = \CSearchLanguage::ConvertKeyboardLayout($query, $arLang["from"], $arLang["to"]);

		if(strlen($alt_query) == 0)
			$alt_query = $query;

		$shopId = intval($data['shop']);
		if($shopId == 0){
			$shopId = $_SESSION['REGIONS']['SHOP_ID'];
		} else {
			$shopId = array($shopId);
		}
		$result = [];

		$CSearchTitle = new \CSearchTitle();
		$CSearchTitle->setMinWordLength(3);
		$CSearchTitle->_arPhrase = stemming_split($alt_query, LANGUAGE_ID);

		$querySearch = new Entity\Query(Search\TitleTable::getEntity());
		$querySearch->registerRuntimeField('CONTENT', new Entity\ReferenceField(
			'CONTENT',
			Search\SearchTable::getEntity(),
			['=this.SEARCH_CONTENT_ID' => 'ref.ID'],
			['join_type' => 'INNER']
		));
		$querySearch->registerRuntimeField('ELEMENT', new Entity\ReferenceField(
			'ELEMENT',
			\Bitrix\Iblock\ElementTable::getEntity(),
			['=this.CONTENT.ITEM_ID' => 'ref.ID']
		));

		$querySearch->setSelect([
			'ID' => 'CONTENT.ID',
			'ITEM_ID' => 'CONTENT.ITEM_ID',
			'TITLE' => 'CONTENT.TITLE',
			'PARAM1' => 'CONTENT.PARAM1',
			'PARAM2' => 'CONTENT.PARAM2',
			'IBLOCK_ID' => 'ELEMENT.IBLOCK_ID',
			'SECTION_ID' => 'ELEMENT.IBLOCK_SECTION.ID',
			'SECTION_NAME' => 'ELEMENT.IBLOCK_SECTION.NAME',
			'DETAIL_PICTURE' => 'ELEMENT.DETAIL_PICTURE'
		]);
		$querySearch->setFilter([
			'WORD' => $alt_query,
			'=CONTENT.MODULE_ID' => 'iblock',
			'=CONTENT.PARAM1' => 'catalog'
		]);
		$querySearch->setGroup([
			'CONTENT.ID', 'CONTENT.ITEM_ID', 'CONTENT.TITLE', 'CONTENT.PARAM1','CONTENT.PARAM2'
		]);
		$querySearch->setOrder([
			'POS' => 'ASC',
			'CONTENT.TITLE' => 'ASC'
		]);
		$querySearch->countTotal(true);
		$querySearch->setLimit(50);

		try{
			$oTitle = $querySearch->exec();
			if($oTitle->getCount() > 0){
				$arIblockSku = CatalogHelper::getSkuBlockByProductsBlock();
				while ($res = $oTitle->fetch()){
					$res['SKU_IBLOCK'] = $arIblockSku[$res['IBLOCK_ID']]['SKU_INFO']['IBLOCK_ID'];
					$res['MAIN_SECTION'] = $arIblockSku[$res['IBLOCK_ID']]['NAME'];
					$sku = $this->CIBlockElement->GetList(
						array(),
						array(
							'IBLOCK_ID' => $res['SKU_IBLOCK'],
							'=PROPERTY_CML2_LINK' => $res['ITEM_ID'],
							'ACTIVE' => 'Y',
							'PROPERTY_SHOP_ID' => $shopId
						),
						false,
						array('nTopCount' => 1),
						array('ID','NAME','IBLOCK_ID', 'PROPERTY_SHOP_ID')
					)->Fetch();
					if($sku){
						if(intval($res['DETAIL_PICTURE']) > 0){
							$res['IMG'] = \CFile::ResizeImageGet(
								$res['DETAIL_PICTURE'],
								['width' => 60, 'height' => 60],
								BX_RESIZE_IMAGE_EXACT
							);
						}

						$sku['PRICES'] = \CPrice::GetBasePrice($sku['ID']);
						$res['SKU'] = $sku;
						$result[] = $res;
					}

				}
			}

		} catch (\Exception $err){
			throw new \Exception('Ошибка при работе полиска', 503);
		}

		$items = array_slice($result, 0, 10);
		if(count($items) == 0){
			$items[] = [
				'TITLE' => 'Товары не найдены',
			];
		}
		return $items;
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
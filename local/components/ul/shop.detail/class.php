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

use AB\Iblock\Element as ABElement;
use AB\Iblock\Model\FileTable;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use function dump;
use PW\Tools\Debug;
use UL\DataCache;
use UL\Main\CatalogHelper;
use UL\Main\SkuSectionTable;
use \Bitrix\Iblock;
use Bitrix\Main\Web;
use UL\Tools;
use Bitrix\Main\Type;

includeModules(['ab.iblock', 'ul.main', 'iblock']);

Loc::loadLanguageFile(__FILE__);

\CBitrixComponent::includeComponentClass('ul:products.category');


class ShopDetail extends \CBitrixComponent
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
		$arParams['ID'] = intval($arParams['ID']);
		$arParams['SECTION_ID'] = intval($arParams['SECTION_ID']);

		return $arParams;
	}

	public function getIblockCatalogs()
	{
		$oIblock = Iblock\IblockTable::getList([
			'select' => ['IBLOCK_ID' => 'ID', 'MAIN_NAME' => 'NAME', 'SORT'],
			'filter' => ['=IBLOCK_TYPE_ID' => 'catalog'],
			'order' => ['SORT' => 'ASC', 'NAME' => 'ASC'],
		]);

		return $oIblock->fetchAll();
	}

	public function getCategories($shopId = null)
	{
		if((int)$shopId > 0){
			$this->arParams['ID'] = $shopId;
		}

		global $USER_FIELD_MANAGER;

		$arIblocks = $this->getIblockCatalogs();
		$i = 0;

		$result = [];
		$Uri = new Web\Uri($this->request->getRequestUri());

		$CParser = new \CTextParser();
		$dataCache = new DataCache(86400, '/ul/shop/sections', Tools::CACHE_KEY_SECTION_SHOP.$this->arParams['ID']);
		$clear = false;
		if ($dataCache->getIsValid() && !$clear){
			$result = $dataCache->getData();
		} else {
			foreach ($arIblocks as $iblock) {
//			if ($i > 0){
//				break;
//			}
				$catalogInfo = \CCatalogSku::GetInfoByIBlock($iblock['IBLOCK_ID']);
				if (intval($catalogInfo['IBLOCK_ID']) > 0){

					$entitySku = ABElement::getEntity($catalogInfo['IBLOCK_ID'], ['CML2_LINK', 'SHOP_ID']);
					$entitySku->addField(new Entity\ReferenceField(
						'PRODUCT',
						Iblock\ElementTable::getEntity(),
						['=this.PROPERTY.CML2_LINK.ID' => 'ref.ID']
					));
					$entitySku->addField(new Entity\ReferenceField(
						'SECTION_ELEMENT',
						Iblock\SectionElementTable::getEntity(),
						['=this.PROPERTY.CML2_LINK.ID' => 'ref.IBLOCK_ELEMENT_ID']
					));

					$query = new Entity\Query($entitySku);
					unset($entitySku);

					$query
						->setSelect([
							'IBLOCK_ID',
							'SHOP_ID' => 'PROPERTY.SHOP_ID.ID',
							'SECTION_ID' => 'SECTION_ELEMENT.IBLOCK_SECTION_ID',
							'SECTION_ID_PARENT' => 'SECTION_ELEMENT.IBLOCK_SECTION.IBLOCK_SECTION_ID',
						])
						->setFilter([
							'IBLOCK_ID' => $catalogInfo['IBLOCK_ID'],
							'=ACTIVE' => 'Y',
							'=PROPERTY.SHOP_ID.ID' => $this->arParams['ID'],
						])
						->setGroup('SECTION_ELEMENT.IBLOCK_SECTION_ID')
						->setLimit(null);

					$obSku = $query->exec();

					$fields = $USER_FIELD_MANAGER->GetUserFields('ASD_IBLOCK', $iblock['IBLOCK_ID']);

					$image = $imageLine = $icon = null;
					if((int)$fields['UF_IMG_SHOP']['VALUE'] > 0){
						$image = \CFile::GetFileArray($fields['UF_IMG_SHOP']['VALUE']);
					}
					if((int)$fields['UF_IMG_LINE']['VALUE'] > 0){
						$imageLine = \CFile::GetFileArray($fields['UF_IMG_LINE']['VALUE']);
					}
					if((int)$fields['UF_ICON']['VALUE'] > 0){
						$icon = \CFile::GetFileArray($fields['UF_ICON']['VALUE']);
					}

					$result[$iblock['IBLOCK_ID']] = [
						'MAIN_URL' => '/shop/'.$this->arParams['ID'].'/'.$iblock['IBLOCK_ID'].'/',
						'CATEGORY_NAME' => $CParser->html_cut($iblock['MAIN_NAME'], 25),
						'SORT' => $iblock['SORT'],
						'IMAGE_BLOCK' => $image,
						'IMAGE_LINE' => $imageLine,
						'ICON' => $icon,
						'ITEMS' => [],
					];

					$sections = [];
					$obSections = Iblock\SectionTable::getList([
						'select' => ['ID', 'NAME', 'DEPTH_LEVEL', 'SORT', 'IBLOCK_SECTION_ID', 'IBLOCK_ID'],
						'filter' => ['=IBLOCK_ID' => $iblock['IBLOCK_ID'], 'DEPTH_LEVEL' <= 2],
						'order' => ['SORT' => 'ASC']
					]);
					while ($rs = $obSections->fetch()){
						$sections[$rs['ID']] = $rs;
					}

					foreach ($sections as $k => $section) {
						if ($section['DEPTH_LEVEL'] > 1){
							$sections[$section['IBLOCK_SECTION_ID']]['SUBSECTION'][] = $section;
							unset($sections[$k]);
						}
					}

					$result[$iblock['IBLOCK_ID']]['ITEMS'] = $sections;

					/*while ($section = $obSku->fetch()) {
						$sectId = $section['SECTION_ID'];
						$obChain = \CIBlockSection::GetNavChain($iblock['IBLOCK_ID'], $sectId, ['NAME', 'ID', 'DEPTH_LEVEL', 'PICTURE', 'SORT', 'IBLOCK_ID']);
						while ($c = $obChain->Fetch()) {

//							$c['SUBSECTION'] = Iblock\SectionTable::getList([
//								'select' => ['ID', 'NAME'],
//								'filter' => ['=IBLOCK_ID' => $iblock['IBLOCK_ID'], 'IBLOCK_SECTION_ID' => $c['ID']],
//							])->fetchAll();
							$result[$iblock['IBLOCK_ID']]['ITEMS'][$c['ID']] = $c;
//							if ($c['DEPTH_LEVEL'] == 1){
//
//							}

						}
					}*/
				}

				$i++;
			}

			$dataCache->writeVars($result);
		}
//		dump($result);
		$this->arResult['SECTIONS'] = $result;

		return $this->arResult['SECTIONS'];
	}

	public function getShopInfo($shopId = null)
	{
		$ID = (int)$shopId;
		if ($ID == 0){
			$ID = $this->arParams['ID'];
		}
		$filter = ['=ID' => $ID, 'IBLOCK_ID' => CatalogHelper::SHOP_IB];

		$cache = new \AB\Tools\Helpers\DataCache(7200, '/ul/shop/', 'shop_info_'.$shopId);

		if($cache->isValid()){
			$arShop = $cache->getData();
		} else {
			$arShop = ABElement::getRow([
				'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE', 'TIMES' => 'PROPERTY.DELIVERY_TIME','DETAIL_TEXT'],
				'filter' => $filter,
			]);
			if (intval($arShop['DETAIL_PICTURE']) > 0){
				$arShop['IMAGE'] = \CFile::ResizeImageGet(
					$arShop['DETAIL_PICTURE'],
					['width' => 150, 'height' => 150],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
					true
				);
			}
			if (intval($arShop['TIMES']) > 0){
//				$arShop['DELIVERY_TIME'] = $this->getTimes($arShop['TIMES']);
			}

			$cache->addCache($arShop);
		}

		return $arShop;
	}

	public function getTimes($time)
	{
		$result = null;
		$oTimes = $this->CIBlockElement->GetList(
			array( 'SORT' => 'ASC', 'SECTION_ID' => 'ASC',),
			array(
				'IBLOCK_ID' => 8,/* '=ACTIVE' => 'Y',*/
				'INCLUDE_SUBSECTIONS' => 'Y', 'SECTION_ID' => $time
			),
			false,
			false,
			array(
				'ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID',
				'PROPERTY_TIME_FROM', 'PROPERTY_TIME_TO', 'PROPERTY_PRICE'
			)
		);
		while ($rs = $oTimes->Fetch()) {
			$sectionName = \Bitrix\Iblock\SectionTable::getRow([
				'select' => ['ID','NAME', 'CODE'],
				'filter' => ['=ID' => $rs['IBLOCK_SECTION_ID']]
			]);

			$result[$rs['IBLOCK_SECTION_ID']]['NAME'] = $sectionName['NAME'];
			$result[$rs['IBLOCK_SECTION_ID']]['CODE'] = $sectionName['CODE'];
			if(intval($rs['PROPERTY_PRICE_VALUE']) == 0){
				$rs['PRICE_FORMAT'] = 'бесплатно';
			} else {
				$rs['PRICE_FORMAT'] = Tools::formatPrice($rs['PROPERTY_PRICE_VALUE']);
			}
			$result[$rs['IBLOCK_SECTION_ID']]['ITEMS'][] = $rs;
		}

//		PR($result);

		$dateTimeNow = new Type\DateTime();
		$currentNumDay = $dateTimeNow->format('N');
		$endNumDate = 7;
//		$currentNumDay = 6;
		$times = [];
		$counter = 1;
		foreach ($result as $secId => $arItem){

			$iterNum = $arItem['CODE'];
			$d = new Type\Date();
			if ($iterNum < $currentNumDay){
				continue;
			} elseif ($iterNum == $currentNumDay){
				$arItem['NAME'] = 'Сегодня, '.$d->format('d ').FormatDate('F', $d->getTimestamp());
				$arItem['TIMESTAMP'] = $d->getTimestamp();

				foreach ($arItem['ITEMS'] as $index => $value) {
					$arItem['ITEMS'][$index]['DISABLED'] = false;
					preg_match('#^(\d+).*(\d+)$#', trim($value['PROPERTY_TIME_FROM_VALUE']), $hourMatch);
					$hourMatchFrom = (int)$hourMatch[1];
					if($hourMatchFrom > 0){
						$minHour = (int)date('H') + 2;
						if($minHour > $hourMatchFrom){
							$arItem['ITEMS'][$index]['DISABLED'] = true;
						}
					}
				}
				$times[0] = $arItem;
			} elseif ($iterNum > $currentNumDay) {
				$d = new Type\Date();
				$interval = $counter;
				$dd = $d->add($counter.' days');
				$arItem['NAME'] = $arItem['NAME'].', '.$dd->format('d ').FormatDate('F', $dd->getTimestamp());
				$arItem['TIMESTAMP'] = $dd->getTimestamp();
				$times[] = $arItem;
				$counter++;
			}
		}
		$counter = 1;
		foreach ($result as $secId => $arItem){
			$iterNum = $arItem['CODE'];
			$d = new Type\Date();
			if ($iterNum < $currentNumDay){
				$interval = $counter;
				$dd = $d->add($interval.' days');
				$arItem['NAME'] = $arItem['NAME'].', '.$dd->format('d ').FormatDate('F', $dd->getTimestamp());
				$arItem['TIMESTAMP'] = $dd->getTimestamp();
				$times[] = $arItem;
				$counter++;
			}
		}

		return $times;
	}

	public function getProducts($shopId)
	{
//		$q = Element::query();
//		$q->setSelect(['*'])
//			->setFilter([]);
	}

	public function jratvaSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']){
			return 0;
		}

		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{

		$this->getShopInfo();

		$this->getCategories();

		$havka = $this->arResult['SECTIONS'][66]['ITEMS'];
		usort($havka, array($this, 'jratvaSort'));

		foreach ($havka as &$arItem) {
			if (intval($arItem['PICTURE']) > 0){
				$arItem['PICTURE'] = \CFile::GetFileArray($arItem['PICTURE']);
			}
		}

		$this->arResult['HAVKA'] = $havka;
		$this->arResult['HAVKA_INFO'] = [
			'URL' => $this->arResult['SECTIONS'][66]['MAIN_URL'],
			'NAME' => $this->arResult['SECTIONS'][66]['MAIN_NAME'],
		];

		foreach ($_SESSION['REGIONS']['SHOP_ID'] as $shop) {
			$this->arResult['ALL_SHOPS'][$shop] = $this->getShopInfo($shop);
		}

		$this->arResult['SHOP_INFO'] = $this->arResult['ALL_SHOPS'][$this->arParams['ID']];

		$this->includeComponentTemplate();
	}
}
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
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Main\Web;
use Soft\SectionTable;

Loc::loadLanguageFile(__FILE__);

\CBitrixComponent::includeComponentClass('ul:category.product.list');

class CatalogElementsComponent extends CategoryProductsComponent
{
	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams = parent::onPrepareComponentParams($arParams);
		if((int)$arParams['PAGE_LIMIT'] == 0)
			$arParams['PAGE_LIMIT'] = 16;

		return $arParams;
	}


	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->getCatalogInfo($this->arParams['IBLOCK_ID']);

		$code = 'SECTIONS';
		if(!$this->arParams['CATEGORY_TREE']['SECTIONS']){
			$code = 'ITEMS';
		}


		$current = $this->arParams['CATEGORY_TREE'][$code][$this->arParams['CURRENT_SECTION']];

		if(empty($current)){
			foreach ($this->arParams['CATEGORY_TREE'][$code]['SUBSECTION'] as $SECTION) {
				if($SECTION['SECTION_ID'] == $this->arParams['CURRENT_SECTION']){
					$current = $SECTION;
					break;
				}
			}
		}


		if(count($current['SUBSECTION']) > 0){
			$this->sectionIterator = new Dictionary($current['SUBSECTION']);
		}
		unset($current['SUBSECTION']);
//		if((int)$current['PICTURE'] > 0){
//			$current['IMAGE'] = \CFile::ResizeImageGet(
//				$current['PICTURE'],
//				['width' => 550, 'height' => 70],
//				BX_RESIZE_IMAGE_EXACT
//			);
//		}

		$imgSection = SectionTable::getRow([
			'select' => ['ID','DETAIL_PICTURE'],
			'filter' => ['=ID' => $current['SECTION_ID']]
		]);
		if((int)$imgSection['DETAIL_PICTURE']){
			$current['IMAGE'] = \CFile::ResizeImageGet(
				$current['DETAIL_PICTURE'],
				['width' => 550, 'height' => 70],
				BX_RESIZE_IMAGE_EXACT
			);
		}

		$this->arResult['SECTION_INFO'] = $current;

		$sectionIds = [];
		foreach ($this->sectionIterator as $item) {
			$sectionIds[] = $item['SECTION_ID'] ? $item['SECTION_ID'] : $item['ID'];
		}

		if((int)$this->arParams['CURRENT_SECTION'] > 0 && count($sectionIds) == 0){
			$sectionIds[] = $this->arParams['CURRENT_SECTION'];
		}


		$sortUriPrice = new Web\Uri($this->request->getRequestUri());
		$sortUriDate = new Web\Uri($this->request->getRequestUri());

		if($this->request->get('order') == 'DESC'){
			$sortUriDate->addParams(['order' => 'ASC', 'sortBy'=>'date']);
			$sortUriPrice->addParams(['order' => 'ASC', 'sortBy'=>'price']);
		} else {
			$sortUriDate->addParams(['order' => 'DESC', 'sortBy'=>'date']);
			$sortUriPrice->addParams(['order' => 'ASC', 'sortBy'=>'price']);
		}

		$default = new Web\Uri($this->request->getRequestUri());
		$default->deleteParams(['sortBy','order']);

		$this->arResult['SORT_URL'] = [
			'DEFAULT' => ['URI'=>$default->getUri(), 'NAME' => 'Стандартно'],
			'date' => ['URI' => $sortUriDate->getUri(), 'NAME' => 'По дате'],
			'price' => ['URI' => $sortUriPrice->getUri(), 'NAME' => 'По цене']
		];

		try{
			$this->arResult['ITEMS'] = $this->getProductsSku($sectionIds, $this->arParams['PAGE_LIMIT']);
		} catch (\Exception $err){
			$this->errorCollection->setError(new Error($err->getMessage()));
		}
		if(count($this->arResult['ITEMS']) == 0){
			$this->errorCollection->setError(new Error('Товары не найдены'));
		}

		$this->arResult['URL_CHAIN'] = $this->compileChain();

		$oChain = \CIBlockSection::GetNavChain(
			$this->arResult['SECTION_INFO']['IBLOCK_ID'],
			$this->arResult['SECTION_INFO']['SECTION_ID'],
			array('ID','NAME','PICTURE','DETAIL_PICTURE')
		);
		while ($rsChain = $oChain->Fetch()){
			if((int)$rsChain['DETAIL_PICTURE'] > 0){
				$rsChain['DETAIL_IMG'] = \CFile::GetFileArray($rsChain['DETAIL_PICTURE']);
			}
			$this->arResult['SECTION_INFO']['CHAIN_IMG'][] = $rsChain;
		}

		$this->arResult['NAV'] = $this->nav;

		global $USER_FIELD_MANAGER;

		$fields = $USER_FIELD_MANAGER->GetUserFields('ASD_IBLOCK', $this->arResult['SECTION_INFO']['IBLOCK_ID']);
		$img = \CFile::GetFileArray($fields['UF_IMG_LINE']['VALUE']);
		$this->arResult['SECTION_INFO']['CHAIN_IMG'][0]['DETAIL_IMG'] = $img;

		if($this->errorCollection->count() > 0){
			$this->arResult['ERRORS'] = $this->getErrors();
			$this->includeComponentTemplate('error');
		} else {
			$this->includeComponentTemplate();
		}

	}
}
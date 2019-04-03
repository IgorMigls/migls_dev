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
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\BasketPropertyItem;
use Bitrix\Sale\PropertyValue;
use UL\Main\Personal\OrderNumberTable;

Main\Loader::includeModule('sale');
Main\Loader::includeModule('ab.iblock');

\CBitrixComponent::includeComponentClass('ul:order.detail');

Loc::loadLanguageFile(__FILE__);

class OrderMail extends DetailOrder
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
		$this->arResult['SUM'] = 0;
		$this->arResult['COUNT'] = 0;
		$this->arResult['STATUS'] = [];

		$numbers = OrderNumberTable::getList([
			'filter' => ['=ACCOUNT_NUMBER' => str_replace('_', '/', $this->arParams['ID'])],
		])->fetchAll();

		foreach ($numbers as $number) {
			$this->getOrder($number['ORDER_ID']);
			$shop =  \Bitrix\Iblock\ElementTable::getRow([
				'select' => ['NAME', 'DETAIL_PICTURE'],
				'filter' => ['IBLOCK_ID' => 5, '=ID' => $number['SHOP_ID']],
			]);
			if(intval($shop['DETAIL_PICTURE']) > 0){
				$shop['IMG'] = \CFile::ResizeImageGet(
					$shop['DETAIL_PICTURE'],
					['width' => 100, 'height' => 100],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}

			$order = \Bitrix\Sale\Order::load($number['ORDER_ID']);
			/** @var PropertyValue $item */
			foreach ($order->getPropertyCollection() as $item) {
				if($item->getField('CODE') == 'DELIVERY_TIME'){
					$shop['DELIVERY_TIME'] = $item->getValue();
				}

				if($item->getField('CODE') == 'PHONE'){
					$this->arResult['FIELDS']['PHONE'] = $item->getValue();
				}
			}

			$this->arResult['SHOP'][$number['ORDER_ID']] = $shop;
			$this->arResult['FIELDS']['ACCOUNT_NUMBER'] = $number['ACCOUNT_NUMBER'];
		}

		$this->arResult['SUM_FORMAT'] = \SaleFormatCurrency($this->arResult['SUM'], 'RUB', true);


		$arSections = [];
		foreach ($this->arResult['BASKET'] as $ordId => $arBasketSop) {

			foreach ($arBasketSop['ITEMS'] as $item) {

				$iblock = Element::getIblockByElementId($item['ELEMENT']['ELEMENT_ID']);

				$obReplaceRow = \UL\Main\Basket\Model\PropsTable::getList([
					'select' => ['*'],
					'filter' => ['=BASKET_ID' => $item['ID'], '=NAME' => 'REPLACE'],
				]);
				while ($r = $obReplaceRow->fetch()){
					$iblockSku = \CIBlockElement::GetIBlockByID($r['CODE']);
					$productReplace = \CIBlockElement::GetList(
						array(),
						array('=ID' => $r['CODE'], 'IBLOCK_ID' => $iblockSku),
						false,
						array('nTopCount' => 1),
						array(
							'ID','IBLOCK_ID','NAME',
							'PROPERTY_CML2_LINK.XML_ID',
							'PROPERTY_CML2_LINK.DETAIL_PICTURE'
						)
					)->Fetch();
					if($productReplace) {
						$productReplace['PRICES'] = \CPrice::GetBasePrice($r['CODE']);
					}

					if((int)$productReplace['PROPERTY_CML2_LINK_DETAIL_PICTURE'] > 0){
						$productReplace['IMG'] = \CFile::GetFileArray($productReplace['PROPERTY_CML2_LINK_DETAIL_PICTURE']);
						$productReplace['IMG']['RESIZE'] = \CFile::ResizeImageGet(
							$productReplace['PROPERTY_CML2_LINK_DETAIL_PICTURE'],
							['width' => 80, 'height' => 80],
							BX_RESIZE_IMAGE_PROPORTIONAL_ALT
						);
					}

					$item['REPLACES'][] = $productReplace;
				}
				$obSection = \CIBlockSection::GetNavChain($iblock, $item['ELEMENT']['SECTION_ID'], [
					'ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID',
				]);
				while ($rs = $obSection->Fetch()){
					$item['CHAIN'][$rs['ID']] = $rs;
				}

				$arValueWeight = \CIBlockElement::GetProperty($iblock, $item['ELEMENT']['ELEMENT_ID'], [], ['CODE' => 'VALUE_WEIGHT'])->Fetch();
				if($arValueWeight){
					$item['VALUE_WEIGHT'] = $arValueWeight['VALUE'];
				}


				$arSections[$ordId][$item['ELEMENT']['ELEMENT_ID']] = $item;
			}
		}

		$this->arResult['ORDER_SECTIONS'] = $arSections;

		$this->includeComponentTemplate();
	}
}
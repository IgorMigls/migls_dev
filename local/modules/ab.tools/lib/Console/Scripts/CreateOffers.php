<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 02.05.2017
 */

namespace AB\Tools\Console\Scripts;

use AB\Tools\Console\ProgressBar;
use AB\Tools\Debug;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::includeModule('catalog');
Loader::includeModule('sale');

class CreateOffers implements IConsole
{
	/**
	 * @var array - массив параметров CLI
	 */
	protected $params;
	private $CIBlockSection;
	private $CIBlockElement;
	private $CCatalogProduct;

	private static $cacheEnums = [];

	const CATALOG_IB = 44;
	const OFFERS_IB = 45;


	/**
	 * Creator constructor. В конструктор приходят все параметры из CLI
	 *
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		global $argv;

		if (count($params) == 0 || is_null($params)){
			$this->params = $argv;
		}

		$this->params = $params;
		$this->CIBlockSection = new \CIBlockSection();
		$this->CIBlockElement = new \CIBlockElement();
		$this->CCatalogProduct = new \CCatalogProduct();
	}

	/**
	 * @method description - недольшое описание комнады: для чего, для кого и пр.
	 * @return string
	 */
	public function description()
	{
		return 'add description';
	}

	/**
	 * @method getParams - get param params
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @method setParams - set param Params
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	public function getProducts()
	{
		$oProducts = \CIBlockElement::GetList(
			array('ID' => 'ASC'),
			array('IBLOCK_ID' => self::CATALOG_IB),
			false,
			false,
			array(
				'ID', 'NAME', 'IBLOCK_ID', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PREVIEW_TEXT_TYPE', 'DETAIL_TEXT_TYPE',
				'PROPERTY_OPISANIE', 'PROPERTY_DOPOPISANIE', 'PROPERTY_UF_DESCRIPTION', 'PROPERTY_DESCR',
				'PROPERTY_KEYWO', 'PROPERTY_UF_KEYWORDS', 'PROPERTY_DOPCOM'
			)
		);

		$items = [];
		while ($item = $oProducts->Fetch()) {
			$items[] = $item;
		}

		return $items;
	}

	private function deleteOffers()
	{
		ProgressBar::consoleLog('delete');

		$obOffersDel = ElementTable::getList([
			'filter' => ['=IBLOCK_ID' => self::OFFERS_IB],
			'select' => ['ID']
		]);
		while ($rs = $obOffersDel->fetch()){
			$this->CIBlockElement->Delete($rs['ID']);
		}

		ProgressBar::consoleLog();
		exit("OK\n\n");
	}

	/**
	 * @method run - Это основной метод для запуска скрипта
	 * @throws \Exception
	 */
	public function run($params)
	{
//		$this->deleteOffers();

		$products = $this->getProducts();

		$props = ['MATERIAL', 'SPALMESTO'];
		$offersProperties = [];
		foreach ($props as $code) {
			$obList = PropertyEnumerationTable::getList([
				'filter' => ['PROPERTY.CODE' => $code,'PROPERTY.IBLOCK_ID' => self::OFFERS_IB],
			]);
			while ($list = $obList->fetch()){
				$offersProperties[$code][$list['XML_ID']] = $list;
			}
		}

		$Bar = new ProgressBar();
		$Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, count($products));
		$step = 0;
		$errors = [];

		foreach ($products as $item) {
			set_time_limit(300);
//			$this->prepareProps($item);
			$matrix = $this->compileMatrixProps($item, $offersProperties);

			$price = \CPrice::GetBasePrice($item['ID']);

			$prevText = '';
			if(count($item['PROPERTY_DOPCOM_VALUE']) > 0){
				$prevText .= implode("\n", $item['PROPERTY_DOPCOM_VALUE']);
			}
			foreach ($matrix as $offer) {
				$offer['PREVIEW_TEXT'] = $prevText;
				$arOffer = ElementTable::getRow([
					'select' => ['ID'],
					'filter' => ['IBLOCK_ID' => self::OFFERS_IB, '=CODE' => $offer['CODE']]
				]);
				$Result = new Result();
				if(is_null($arOffer)){
					$res = $this->CIBlockElement->Add($offer);
					if(intval($res) > 0){
						$Result->setData(['ID' => $res]);
					} else {
						$Result->addError(new Error(strip_tags($this->CIBlockElement->LAST_ERROR)));
					}
				} else {
					$res = $this->CIBlockElement->Update($arOffer['ID'], $offer);
					if($res){
						$Result->setData(['ID' => $arOffer['ID']]);
					} else {
						$Result->addError(new Error(strip_tags($this->CIBlockElement->LAST_ERROR)));
					}
				}

				if($Result->isSuccess()){
					$data = $Result->getData();
					$this->CCatalogProduct->Add([
						'ID' => $data['ID'],
						'QUANTITY' => 50,
						'CAN_BUY_ZERO' => 'Y',
						'QUANTITY_TRACE' => 'N',
						'MEASURE' => 5,
						'PRICE_TYPE' => 'S',
					]);
					\CPrice::SetBasePrice($data['ID'], $price['PRICE'],'RUB');
				} else {
					$errors[] = implode(', ', $Result->getErrorMessages());
				}
			}
			$step++;
			$Bar->update($step);
		}

		ProgressBar::consoleLog();
		if(count($errors) > 0){
			foreach ($errors as $error) {
				ProgressBar::showError($error);
			}
		}
	}



	private function getPropsValues($code, $item)
	{
		$result = [];
		$obProp = \CIBlockElement::GetProperty($item['IBLOCK_ID'], $item['ID'], array(), array('CODE' => $code));
		while ($rs = $obProp->Fetch()) {
			$result[] = [
				'VALUE_ENUM' => $rs['VALUE_ENUM'],
				'VALUE' => $rs['VALUE'],
				'NAME' => $rs['NAME'],
				'CODE' => $rs['CODE'],
				'VALUE_XML_ID' => $rs['VALUE_XML_ID'],
				'ID' => $rs['ID']
			];
		}

		return $result;
	}

	private function compileMatrixProps($item, $offersProperties)
	{
		$arPropValues = [];

		//MATERIAL SPALMESTO
		$props = ['MATERIAL', 'SPALMESTO'];
		foreach ($props as $code) {
			$arPropValues[$code] = $this->getPropsValues($code, $item);
		}

		$matrix = [];
		if(count($arPropValues['MATERIAL']) > 0){
			foreach ($arPropValues['MATERIAL'] as $value) {
				$cnt = count($arPropValues['SPALMESTO']);
				for ($i = 0; $i < $cnt; $i++){
					$arName = [$item['NAME'], $value['VALUE_ENUM'], $arPropValues['SPALMESTO'][$i]['VALUE_ENUM']];
					$name = implode(' ', $arName);

					$spalMestoKey = $arPropValues['SPALMESTO'][$i]['VALUE_XML_ID'];
					$matrix[] = [
						'NAME' => $name,
						'CODE' => \CUtil::translit($name, 'ru'),
						'IBLOCK_ID' => self::OFFERS_IB,
						'PROPERTY_VALUES' => [
							'MATERIAL' => $offersProperties['MATERIAL'][$value['VALUE_XML_ID']]['ID'],
							'SPALMESTO' => $offersProperties['SPALMESTO'][$spalMestoKey]['ID'],
							'CML2_LINK' => $item['ID']
						]
					];
				}
			}
		}

		return $matrix;
	}

	protected function prepareProps($item)
	{
		$prevText = $detailText = '';
		$save = [];

		if (strlen($item['PREVIEW_TEXT']) > 0){
			$prevText = $item['PREVIEW_TEXT']."\n";
		}
		$prevTextProp = implode("\n", $item['PROPERTY_OPISANIE_VALUE']);
		$prevText .= $prevTextProp;

		if (strlen($item['DETAIL_TEXT']) > 0){
			$detailText = $item['DETAIL_TEXT']."\n";
		}
		$detailTextProp = implode("\n", $item['PROPERTY_DOPOPISANIE_VALUE']);
		$detailText .= $detailTextProp;

//			Debug::toLog($item);

		$save = [
			'IBLOCK_ID' => $item['IBLOCK_ID'],
			'NAME' => $item['NAME'],
			'PREVIEW_TEXT' => $prevText,
			'DETAIL_TEXT' => $detailText,
			'PREVIEW_TEXT_TYPE' => 'html',
			'DETAIL_TEXT_TYPE' => 'html',
		];

		$propsUpdate = [];
		if (strlen(trim($item['PROPERTY_UF_DESCRIPTION_VALUE'])) > 0 && strlen(trim($item['PROPERTY_DESCR_VALUE'])) == 0){
			$propsUpdate['DESCR'] = trim($item['PROPERTY_UF_DESCRIPTION_VALUE']);
		}
		if (strlen(trim($item['PROPERTY_UF_KEYWORDS_VALUE'])) > 0 && strlen(trim($item['PROPERTY_KEYWO_VALUE'])) == 0){
			$propsUpdate['KEYWO'] = trim($item['PROPERTY_UF_KEYWORDS_VALUE']);
		}

		if ($this->CIBlockElement->Update($item['ID'], $save)){
			if (count($propsUpdate) > 0){
				\CIBlockElement::SetPropertyValuesEx($item['ID'], $item['IBLOCK_ID'], $propsUpdate);
			}
		} else {
			Debug::toLog($this->CIBlockElement->LAST_ERROR);
		}

	}
}
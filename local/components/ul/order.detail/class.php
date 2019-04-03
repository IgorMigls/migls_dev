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
use Bitrix\Main\ArgumentException;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use Bitrix\Sale;
use UL\Main\Personal\OrderNumberTable;

Loader::includeModule('sale');
Loader::includeModule('ab.iblock');

Loc::loadLanguageFile(__FILE__);

class DetailOrder extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @throws ArgumentException
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
//		if (intval($arParams['ID']) == 0){
//			$arParams['ID'] = (int)$this->request->get('orderId');
//		}
		$arParams['ID'] = $this->request->get('orderId');
//		if ($arParams['ID'] == 0){
//			throw new ArgumentException('Order ID is null', 'ID');
//		}

		return $arParams;
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
			$this->arResult['SHOP'][$number['ORDER_ID']] = $shop;
			$this->arResult['FIELDS']['ACCOUNT_NUMBER'] = $number['ACCOUNT_NUMBER'];
		}

		$this->arResult['SUM_FORMAT'] = \SaleFormatCurrency($this->arResult['SUM'], 'RUB', true);


		unset($fields);

		$this->includeComponentTemplate();
	}

	public function getOrder($id)
	{
		$order = Sale\Order::load($id);
		$fields = $order->getFieldValues();
		$fields['DATE_FORMAT'] = $fields['DATE_INSERT']->format('d').' ';
		$fields['DATE_FORMAT'] .= \FormatDate('F', $fields['DATE_INSERT']->getTimestamp()).' ';
		$fields['DATE_FORMAT'] .= $fields['DATE_INSERT']->format('Y').', ';
		$fields['DATE_FORMAT'] .= $fields['DATE_INSERT']->format('H:i');

		$fields['ADDRESS_FORMAT'] = Order::getAddressForOrder($order->getId());

		$fields['STATUS'] = Sale\Internals\StatusLangTable::getRow([
			'filter' => ['STATUS_ID' => $fields['STATUS_ID']],
		]);

		/** @var Sale\BasketItem $basketItem */
		$basket = $order->getBasket()->getBasketItems();
//		dump($basket);

		foreach ($basket as $basketItem) {
			$basketFields = $basketItem->getFieldValues();

//			$ibLock = Element::getIblockByElementId($basketFields['PRODUCT_ID']);
//			$product = Element::getRow([
//				'select' => [
//					'IMG_ID' => 'PROPERTY.CML2_LINK.DETAIL_PICTURE',
//					'ELEMENT_ID' => 'PROPERTY.CML2_LINK.ID',
//					'PRODUCT_NAME' => 'PROPERTY.CML2_LINK.NAME',
//					'SECTION_ID' => 'PROPERTY.CML2_LINK.IBLOCK_SECTION_ID',
//					'IBLOCK_PRODUCT' => 'PROPERTY.CML2_LINK.IBLOCK_ID',
//					'BARCODE' => 'PROPERTY.CML2_LINK.XML_ID'
//				],
//				'filter' => ['IBLOCK_ID' => $ibLock, '=ID' => $basketFields['PRODUCT_ID']],
//			]);

			$img = explode('|', $basketFields['PRODUCT_XML_ID']);
			$product['IMG'] = \CFile::ResizeImageGet(
				$img[0],
				array('width' => 120, 'height' => 120),
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
				true
			);
			$product['ORIGINAL_IMG'] = \CFile::GetFileArray($img[0]);

			$product['ELEMENT_ID'] = $img[1];
			$basketFields['ELEMENT'] = $product;

			$basketFields['PROPS'] = $basketItem->getPropertyCollection()->getPropertyValues();


			$this->arResult['BASKET'][$id]['ITEMS'][] = $basketFields;

			$this->arResult['COUNT']++;
		}

		if($fields['CANCELED'] == 'Y'){
			$fields['STATUS']['NAME'] = 'Отменен';
		}

		$this->arResult['STATUS'][$id] = $fields['STATUS']['NAME'];
		$this->arResult['SUM'] += $fields['PRICE'];

		$this->arResult['FIELDS'] = $fields;
	}
}
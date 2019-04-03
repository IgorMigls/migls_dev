<?php namespace UL\Basket;
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
use Bitrix\Sale\Basket;

Loader::includeModule('sale');
Loader::includeModule('catalog');

Loc::loadLanguageFile(__FILE__);

class BasketList extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	private $siteId;
	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER;
		$this->USER = $USER;
		$this->siteId = \Bitrix\Main\Context::getCurrent()->getSite();
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

	public function addTestProduct()
	{
		$basket = Basket::create($this->siteId);
		$basket->setFUserId(\CSaleBasket::GetBasketUserID());
		$product = [
			'PRODUCT_ID'=>85460, 'NAME'=>'товар 1','PRICE'=>500,'CURRENCY'=>'RUB','QUANTITY'=>2,
		];
		$item = $basket->createItem('catalog', $product['PRODUCT_ID']);
		unset($product['ID']);
		$item->setFields($product);
		$basket->save();
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
//		$this->addTestProduct();

//		$basket = Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $this->siteId)->getOrderableItems();
//		/** @var \Bitrix\Sale\BasketItem $basketItem */
//		foreach ($basket->getBasketItems() as $basketItem) {
//			$this->arResult['ITEMS'][] = $basketItem->getFieldValues();
//		}
//
//		$this->arResult['PRICE'] = $basket->getPrice();
//		$this->arResult['CNT'] = count($this->arResult['ITEMS']);
//		$this->arResult['FORMAT_CNT'] = \UL\Tools::formatContProduct($this->arResult['CNT']);
//		$this->arResult['FORMAT_PRICE'] = \SaleFormatCurrency($this->arResult['PRICE'], 'RUB');

		$this->includeComponentTemplate();
	}
}
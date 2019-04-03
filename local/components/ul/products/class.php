<?php namespace UL\Products;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class ProductMain extends \CBitrixComponent
{
	protected $USER;

	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component)
	{
		global $USER;
		parent::__construct($component);
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		return $arParams;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
//		PR($this->request->toArray());

		if(!$this->request['CAT']){
			$this->includeComponentTemplate('all');
		} else {
			$this->includeComponentTemplate();
		}
	}

}
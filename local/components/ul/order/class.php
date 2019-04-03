<?php namespace UL\Order;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals;

class OrderStep extends \CBitrixComponent
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
		if (intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		return $arParams;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		$page = $this->request->get('page');

		if((int)$this->request->get('order') > 0){
			global $USER;
			$this->arResult['ORDER'] = Internals\OrderTable::getRow([
				'select'=>['*'],
				'filter' => ['=ID'=>$this->request->get('order'), 'USER_ID'=>$USER->GetID()]
			]);
		}

		$this->includeComponentTemplate($page);
	}
}
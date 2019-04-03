<?php namespace UL\Personal;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use PW\Tools\Debug;

class PersonalMain extends \CBitrixComponent
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
		return $arParams;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		$page = $this->request->get('page');

		if($this->request->get('orderId') && $this->request->get('repeat') == 'Y'){
			$order = Order::load($this->request->get('orderId'));
			$newOrder = $order->createClone();
			$res = $newOrder->save();
//			dd($res);

			if($res->isSuccess()){
				LocalRedirect('/personal/#/orders');
			}
		}

		$this->includeComponentTemplate($page);
	}
}
<?php namespace UL\Main\Personal;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

class AddressList extends \CBitrixComponent
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

	public function searchAddressAction($data = [])
	{

		$post = [
			'count' => 10,
			'query' => $data['query'],
		];

		switch ($data['name']) {
			case 'CITY':
				//{"count":10,"from_bound":{"value":"city"},"to_bound":{"value":"settlement"},"query":"мос"}:
				$post['from_bound'] = ['value' => 'city'];
				$post['to_bound'] = ['value' => 'settlement'];
				break;
			case 'STREET':
				//"count":10,"from_bound":{"value":"street"},"to_bound":{"value":"street"},"locations":[{"city":"Москва"}],"query":"fdnj"}:
				$post['from_bound'] = ['value' => 'street'];
				$post['to_bound'] = ['value' => 'street'];
				$post['locations'] = [
					array('city' => $data['addressItems']['CITY']),
				];
				break;
		}

		$Suggestions = new \UL\Suggestions();
		$items = $Suggestions->getAddress($post);
		$result = [];
		if ($data['addressItems']['CITY']){
			foreach ($items['suggestions'] as $k => &$value) {
				$val = '';
				//settlement_with_type
				if (strlen($value['data']['settlement_with_type']) > 0){
					$val = $value['data']['settlement_with_type'].' '.$value['data']['street_with_type'];

				} else {
					$val = $value['data']['street_with_type'];
				}

				$value['value'] = $val;
			}
		}


		return $items['suggestions'];
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{

		$this->includeComponentTemplate();

	}

}
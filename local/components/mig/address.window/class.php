<?php namespace Mig\Address;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Sale\OrderUserProperties;
use Bitrix\Sale\PropertyValueCollection;
use function check_email;
use function dump;
use function LocalRedirect;
use UL\Main\Personal\Address;
use UL\Suggestions;
use UL\Main\Map\Model\CordTable;

Loc::loadLanguageFile(__FILE__);

class WindowComponent extends \CBitrixComponent
{
	protected $postData;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
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

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method getAddressList
	 * @return array|null
	 * @throws \Exception
	 */
	public function getAddressList()
	{
		if (!$this->getUser()->IsAuthorized()){
			return null;
		}

		return Address::getProfilesByUser();
	}

	/**
	 * @method saveAddress
	 * @return int|null
	 * @throws Main\ObjectException
	 * @throws \Exception
	 */
	public function saveAddress()
	{
		$fields = $this->getPostData()['fields'];
		$Address = new Address();

		return $Address->saveAddressAction($fields);
	}

	/**
	 * @method loadAddress
	 * @return array|null
	 * @throws \Exception
	 */
	public function loadAddress()
	{
		$q = $this->request->get('search');
		$post = [
			'query' => $q,
			'restrict_value' => true,
			'count' => 5,
		];

		switch ($this->request->get('locations')){
			case 'city':
				$post['from_bound'] = ['value' => 'city'];
				$post['to_bound'] = ['value' => 'city'];
				$post['locations'] = [
					'city_type_full' => 'город',
				];
				break;
			case 'street':
				$post['from_bound'] = ['value' => 'street'];
				$post['to_bound'] = ['value' => 'street'];
				$post['locations'] = [
					'city' => $this->request->get('city')
				];
				break;
		}

		$suggest = new Suggestions();
		$list = $suggest->loadData($post);
		$result = null;
		foreach ($list as $item){
			$val = str_replace(['г ', 'гор ','г.','гор.'],'', $item['value']);
			$result[] = [
				'value' => trim($val),
				'unrestricted_value' => $item['unrestricted_value'],
			];
		}

		return $result;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
//		unset($_SESSION['REGIONS']);
		if(count($_SESSION['REGIONS']) == 0 || !isset($_SESSION['REGIONS'])){
			$this->arResult['NOT_ALLOWED'] = true;
		} else {
			$this->arResult['REGIONS'] = $_SESSION['REGIONS'];
		}

		$this->includeComponentTemplate();
	}

	/**
	 * @method setNewAddress
	 * @throws Main\ArgumentException
	 */
	public function setNewAddress()
	{
		$result = null;
		$data = $this->getPostData();

		if($data['set_region'] == 'Y'){
			$_SESSION['addressChange'] = 'Y';
			$uid = CordTable::uidCoors($data['CORDS']);


			$cacheIndexProducts = md5(serialize($_SESSION['REGIONS']['SHOP_ID']));
			$tags = new \Bitrix\Main\Data\TaggedCache();
			$tags->clearByTag($cacheIndexProducts);

			unset($_SESSION['REGIONS']);
			unset($_SESSION['SHOPS']);

			$result = CordTable::getRow([
				'select'=>['CITY_ID', 'SHOP_ID', 'ID'],
				'filter' => ['=UID' => $uid]
			]);

			if($result && !is_null($result)){
				TrimArr($result['SHOP_ID']);
				$_SESSION['REGIONS']['CITY_ID'] = $result['CITY_ID'];
				$shopIds = \UL\Main\Map\Model\MultiShopTable::getList([
					'select'=>['VALUE', 'ID'],
					'filter' => ['=ID'=>$result['SHOP_ID']]
				])->fetchAll();

				foreach ($shopIds as $id) {
					if(intval($id['VALUE']) > 0){
						$_SESSION['REGIONS']['AREAL'] = $id['ID'];
						$_SESSION['REGIONS']['SHOP_ID'][] = $id['VALUE'];
					}
				}

				if(strlen($data['ADDRESS']) > 0){
					$_SESSION['REGIONS']['ADDRESS'] = $data['ADDRESS'];
				}
			}

			$result['REGIONS'] = $_SESSION['REGIONS'];

			if(strlen($_SESSION['REGIONS']['ADDRESS']) == 0){
				$arCity = \Bitrix\Iblock\SectionTable::getRow([
					'filter'=>['IBLOCK_ID'=>5,'=ID'=>$_SESSION['REGIONS']['CITY_ID']],
					'select' =>['NAME']
				]);
				$_SESSION['REGIONS']['ADDRESS'] = 'Россия, '.$arCity['NAME'];
			}

			$result['BACK_URL'] = $_SERVER['HTTP_REFERER'];
			$result['BACK_URL'] = '/';
//			$result['BACK_URL'] = false;

			$cacheCity = md5(serialize($_SESSION['REGIONS']['CITY_ID']));
			$tags->clearByTag('shop_list_small_'.$cacheCity);
			$tags->clearByTag('shop_list_'.$cacheCity.'Y');
			$tags->clearByTag('shop_list_'.$cacheCity);
		}

		return $result;
	}

	public function saveAddressEmail()
	{
		$email = $this->getPostData()['email'];
		$result = [
			'error'=> null,
			'msg' => null
		];

		if(!check_email($email)){
			$result['error'] = 'Введите email правильно';
		} else {
			$result['msg'] = 'Спасибо за интерес к нашему сервису! Мы обязательно уведомим Вас, когда доставка по Вашему адресу станет возможна!';
		}

		return $result;
	}

	/**
	 * @method deleteAddress
	 * @throws \Exception
	 */
	public function deleteAddress()
	{
		$id = (int)$this->getPostData()['ID'];

		return Address::getInstance()->deleteAction(['ID' => $id]);
	}

	/**
	 * @method getPostData - get param postData
	 * @return mixed
	 */
	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * @method setPostData - set param PostData
	 * @param mixed $postData
	 */
	public function setPostData($postData)
	{
		$this->postData = $postData;
	}
}

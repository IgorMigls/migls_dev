<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 15.08.2016
 * Time: 17:53
 */

namespace UL\Main\Personal;

use Bitrix\Main\Entity;
use Bitrix\Sale;
use PW\Tools\Debug;
use Bitrix\Main\Type;

class Address extends Base
{

	const PERSON_TYPE = 1;

	/**
	 * @var null|Address
	 */
	private static $instance = null;

	/**
	 * @method getInstance - get param instance
	 * @return Address
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance))
			self::$instance = new static();

		return self::$instance;
	}



	/**
	 * @method getProfilesByUser
	 * @param int $id
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function getProfilesByUser($id = null)
	{
		$id = (int)$id;
		$filter = ['=USER_ID' => self::getUserId()];

		if($id > 0){
			$filter['=ID'] = $id;
		}

		$arProfiles = [];
		$obProfiles = Sale\Internals\UserPropsTable::getList([
			'select' => ['ID', 'NAME', 'USER_ID', 'PERSON_TYPE_ID'],
			'filter' => $filter,
		]);
		while ($profile = $obProfiles->fetch()) {
			$obVal = Sale\Internals\UserPropsValueTable::getList([
				'select' => ['*','CODE'=>'PROPERTY.CODE'],
				'filter' => ['=USER_PROPS_ID' => $profile['ID']]
			]);
			while ($arVal = $obVal->fetch()){
				if(!empty($arVal['CODE']))
					$profile['VALUES'][$arVal['CODE']] = $arVal;
			}
			$profile['VALUE_FORMAT'] = implode(', ', array(
				$profile['VALUES']['ZIP']['NAME'].' '.$profile['VALUES']['ZIP']['VALUE'],
				$profile['VALUES']['CITY']['NAME'].' '.$profile['VALUES']['CITY']['VALUE'],
				'ул.'.$profile['VALUES']['STREET']['VALUE'],
				$profile['VALUES']['HOUSE']['NAME'].' '.$profile['VALUES']['HOUSE']['VALUE'],
				$profile['VALUES']['FLOOR']['NAME'].' '.$profile['VALUES']['FLOOR']['VALUE'],
				$profile['VALUES']['APARTMENT']['NAME'].' '.$profile['VALUES']['APARTMENT']['VALUE'],
			));

			$arProfiles[] = $profile;
		}

		return $arProfiles;
	}

	/**
	 * @method getDataAction
	 * @return array
	 */
	public function getDataAction()
	{
		$props = static::getProperties();
		$profiles = [];

		if(intval(self::getUserId()) > 0)
			$profiles = static::getProfilesByUser();

		return [
			'profiles' => $profiles,
			'props' => $props,
		];
	}

	/**
	 * @method getProperties
	 * @return array
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getProperties()
	{
		$arProps = [];
		$oProps = Sale\Internals\OrderPropsTable::getList([
			'select' => [
				'ID', 'PERSON_TYPE_ID', 'NAME', 'TYPE', 'REQUIRED', 'DEFAULT_VALUE', 'SORT', 'CODE',
//				'VALUE_PROP_'=>'VALUE_PROP'
			],
			'filter' => ['=PERSON_TYPE_ID' => self::PERSON_TYPE, 'USER_PROPS' => 'Y', '!=CODE' => false],
			'order' => ['SORT' => 'ASC'],
			'runtime' => [
				new Entity\ReferenceField(
					'VALUE_PROP',
					Sale\Internals\UserPropsValueTable::getEntity(),
					['=this.ID' => 'ref.ORDER_PROPS_ID']
				),
			],
		]);
		while ($prop = $oProps->fetch()) {
			$arProps[$prop['CODE']] = $prop;
		}

		return $arProps;
	}

	/**
	 * @method saveAddressAction
	 * @param array $data
	 *
	 * @return int|null
	 * @throws \Bitrix\Main\ObjectException
	 * @throws \Exception
	 */
	public function saveAddressAction($data = [])
	{
		$bAdd = $bUpdate = false;

		$profileName = isset($data['PROFILE_NAME']['VALUE']) ? $data['PROFILE_NAME']['VALUE'] : $data['PROFILE_NAME'];
		$profileId = null;

		$save = [
			'NAME' => $profileName,
			'USER_ID' => self::getUserId(),
			'PERSON_TYPE_ID' => self::PERSON_TYPE,
			'DATE_UPDATE' => new Type\DateTime(),
		];


		if(intval($data['PROFILE_ID']) > 0) {
			$bUpdate = true;
			$rowProfile['ID'] = $data['PROFILE_ID'];
		} else {
			$rowProfile = Sale\Internals\UserPropsTable::getRow([
				'select' => ['ID'],
				'filter' => ['=USER_ID' => self::getUserId(), 'PERSON_TYPE_ID' => self::PERSON_TYPE, '=NAME' => $save['NAME']],
			]);
		}

		if (!is_null($rowProfile)) {
			$saveProfile = Sale\Internals\UserPropsTable::update($rowProfile['ID'], $save);
			$bUpdate = true;
		} else {
			$bAdd = true;
			$saveProfile = Sale\Internals\UserPropsTable::add($save);
		}
		if ($saveProfile->isSuccess()) {
			$profileId = $saveProfile->getId();
			unset($data['PROFILE_NAME']);
			unset($data['PROFILE_ID']);
			unset($data['ADDRESS_SELECT']);

			$itemProps = [];
			foreach ($data as $code => $val) {
				$itemProps[$code] = Sale\Internals\OrderPropsTable::getRow([
					'select' => ['ID','NAME'],
					'filter' => ['=CODE' => $code]
				]);

				if(is_array($val)){
					$value = $val['VALUE'];
				} else {
					$value = $val;
				}
				if(strlen($value) == 0){
					$value = $val;
				}

				$itemProps[$code]['VALUE'] = $value;
			}

			if ($bUpdate) {
				static::savePropertyProfile($profileId, $itemProps, $bUpdate);
			} else {
				static::savePropertyProfile($profileId, $itemProps, false);
			}
		}


		return (int)$profileId;
	}

	/**
	 * @method checkProp
	 * @param $profileId
	 * @param $propId
	 *
	 * @return array|null
	 */
	protected static function checkProp($profileId, $propId)
	{
		return Sale\Internals\UserPropsValueTable::getRow([
			'select' => ['ID'],
			'filter' => ['=USER_PROPS_ID' => $profileId, '=ORDER_PROPS_ID' => $propId],
		]);
	}

	/**
	 * @method savePropertyProfile
	 * @param $profileId
	 * @param $data
	 * @param bool $bUpdate
	 *
	 * @throws \Exception
	 */
	protected static function savePropertyProfile($profileId, $data, $bUpdate = false)
	{
		foreach ($data as $code => $item) {
			$savePropFields = [
				'USER_PROPS_ID' => $profileId,
				'ORDER_PROPS_ID' => $item['ID'],
				'NAME' => $item['NAME'],
				'VALUE' => $item['VALUE']
			];

			if($bUpdate){
				$checkProp = self::checkProp($profileId, $item['ID']);
				if (!is_null($checkProp)) {
					$saveProp = Sale\Internals\UserPropsValueTable::update($checkProp['ID'], $savePropFields);
				} else {
					$saveProp = Sale\Internals\UserPropsValueTable::add($savePropFields);
				}
			} else {
				$saveProp = Sale\Internals\UserPropsValueTable::add($savePropFields);
			}

			if(!$saveProp->isSuccess()){
				throw new \Exception(implode(',', $saveProp->getErrorMessages()));
			}
		}
	}

	/**
	 * @method deleteAction
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	public function deleteAction($data = [])
	{
		$ID = intval($data['ID']);
		if($ID == 0){
			throw new \Exception('Нет ID профиля');
		}

		Sale\Internals\UserPropsTable::delete($ID);
		$obVal = Sale\Internals\UserPropsValueTable::getList([
			'select' => ['ID'],
			'filter' => ['=USER_PROPS_ID' => $ID]
		]);
		while ($arVal = $obVal->fetch()){
			Sale\Internals\UserPropsValueTable::delete($arVal['ID']);
		}
	}

	/**
	 * @method saveEmailNoAddressAction
	 * @param array $data
	 *
	 * @return bool|null
	 * @throws \Exception
	 */
	public static function saveEmailNoAddressAction($data = [])
	{
		includeModules('iblock');

		$mail = $data['EMAIL'];
		if(!\check_email($mail)){
			throw new \Exception('E-mail не прошел проверку');
		}

		$xml_id = md5($mail);
		$iblock = 24;
		$CIBlockElement = new \CIBlockElement();
		$save = [
			'IBLOCK_ID' => $iblock,
			'NAME' => $mail,
			'DATE_ACTIVE_FROM' => date('d.m.Y H:i:s'),
			'XML_ID' => $xml_id,
			'PREVIEW_TEXT' => $data['address']
		];
		$result = null;

		$arEmail = \Bitrix\Iblock\ElementTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID' => $iblock, '=XML_ID' => $xml_id]
		]);
		if(!is_null($arEmail)){
			if(!$CIBlockElement->Update($arEmail['ID'], $save)){
				throw new \Exception(strip_tags($CIBlockElement->LAST_ERROR));
			}
			$result = $arEmail['ID'];
		} else {
			$res = $CIBlockElement->Add($save);
			if(intval($res) > 0){
				$result = $res;
			} else {
				throw new \Exception(strip_tags($CIBlockElement->LAST_ERROR));
			}
		}

		\CEvent::SendImmediate('UL_ADDRESS_NO_SEARCH', SITE_ID, [
			'EMAIL_FROM' => $mail,
			'ADDRESS' => $save['PREVIEW_TEXT'],
			'DATE' => date('d.m.Y H:i:s')
		]);


		return $result;
	}

	/**
	 * @method getAddressUserAction
	 * @return null
	 */
	public static function getAddressUserAction()
	{
		global $USER;

		if($USER->IsAuthorized()){
			return $USER->GetEmail();
		}

		return null;
	}
}
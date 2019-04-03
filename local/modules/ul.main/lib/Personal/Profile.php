<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 15.08.2016
 * Time: 11:50
 */

namespace UL\Main\Personal;

use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\UserTable;
use PW\Tools\Debug;

class Profile extends Base
{

	/**
	 * @method getDataAction
	 * @param array $data
	 *
	 * @return array|null
	 */
	public function getDataAction($data = [])
	{
		$userId = self::getUserId();
		if (!is_null($userId)){
			$arUser = UserTable::getRow([
				'select' => ['ID', 'LOGIN', 'EMAIL', 'NAME', 'LAST_NAME', 'PERSONAL_MOBILE', 'PERSONAL_BIRTHDAY', 'PERSONAL_PHOTO'],
				'filter' => ['=ID' => $userId],
			]);

			$arUser['BIRTHDAY']['MONTHS'] = self::generateMonths();
			if (!empty($arUser['PERSONAL_BIRTHDAY'])){
				/** @var Type\DateTime $date */
				$date = $arUser['PERSONAL_BIRTHDAY'];

				$arUser['BIRTHDAY']['CURRENT'] = [
					'd' => $date->format('d'),
					'm' => $arUser['BIRTHDAY']['MONTHS'][(int)$date->format('n') - 1],
					'y' => $date->format('Y'),
				];

			}

			if (intval($arUser['PERSONAL_PHOTO']) > 0){
				$arUser['PERSONAL_PHOTO'] = \CFile::ResizeImageGet(
					$arUser['PERSONAL_PHOTO'],
					['width' => 130, 'height' => 130],
					BX_RESIZE_IMAGE_EXACT,
					true
				);
			}

			return $arUser;
		}

		return null;
	}

	/**
	 * @method saveDataAction
	 * @param array $data
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function saveDataAction($data = [])
	{
		$CUser = new \CUser();
		$save = [];
		foreach ($data as $code => $value) {
			$save[$code] = $value;
		}

		$date = $data['BIRTHDAY']['CURRENT'];
		if (isset($date['d']) && isset($date['m']) && isset($date['y'])){
			$m = array_search($date['m'], self::generateMonths());
			$mm = '0'.($m + 1);
			$save['PERSONAL_BIRTHDAY'] = $date['d'].'.'.$mm.'.'.$date['y'];
		}

		if ($CUser->Update(intval($data['ID']), $save)){
			return $data['ID'];
		} else {
			throw new \Exception(strip_tags($CUser->LAST_ERROR));
		}
	}

	/**
	 * @method savePassAction
	 * @param array $data
	 *
	 * @return null
	 * @throws \Exception
	 */
	public function savePassAction($data = [])
	{
		if (strlen($data['PASSWORD']) < 6){
			throw new \Exception('Пароль должен содержать мин. 6 символов');
		}

		$CUser = new \CUser();
		if ($CUser->IsAdmin()){
			throw new \Exception('Админам нельзя менять здесь пароль');
		}

		$resAuth = $CUser->Login($data['LOGIN'], $data['OLD_PASSWORD']);
		if ($resAuth === true){
			if ($data['PASSWORD'] === $data['CONFIRM_PASSWORD']){
				if ($CUser->Update(self::getUser()->GetID(), ['PASSWORD' => $data['PASSWORD'], 'CONFIRM_PASSWORD' => $data['PASSWORD']])){
					return 1;
				} else {
					throw new \Exception(strip_tags($CUser->LAST_ERROR));
				}
			} else {
				throw new \Exception('Не совпадает новый пароль и его подтверждение');
			}
		} else {
			throw new \Exception('Неправильный старый пароль');
		}
	}

	/**
	 * @method savePhotoAction
	 * @return null
	 * @throws \Exception
	 */
	public function savePhotoAction()
	{
		/** @var \Bitrix\Main\HttpRequest $request */
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();
		$file = $request->getFile('file');
		$CUser = new \CUser();
		if ($CUser->Update(self::getUser()->GetID(), ['PERSONAL_PHOTO' => $file])){
			return self::getUser()->GetID();
		} else {
			throw new \Exception(strip_tags($CUser->LAST_ERROR));
		}
	}

	public function changeMailAction($data = [])
	{
		if (!check_email($data['EMAIL'])){
			throw new \Exception('Введите правильный текущий e-mail');
		}

		$arUser = UserTable::getRow([
			'filter' => ['=EMAIL' => $data['EMAIL']],
			'select' => ['ID'],
		]);

		$newEmailUser = UserTable::getRow([
			'filter' => ['=EMAIL' => $data['NEW_EMAIL']],
			'select' => ['ID'],
		]);

		if(!is_null($newEmailUser)){
			throw new \Exception('Введеный вами новый e-mail уже занят');
		}

		if(is_null($arUser)){
			throw new \Exception('Такой e-mail не найден');
		}
		global $USER;
		if($arUser['ID'] !== $USER->GetID()){
			throw new \Exception('Ваш текущий e-mail не совпадает с указанным');
		}

		if (strlen($data['PASSWORD']) < 6){
			throw new \Exception('Пароль должен содержать мин. 6 символов');
		}
//		return 1;
		$CUser = new \CUser();
		if ($CUser->IsAdmin()){
			throw new \Exception('Админам нельзя менять здесь почту');
		}

		$login = $CUser->GetLogin();
		$resAuth = $CUser->Login($login, $data['PASSWORD']);
		if ($resAuth === true){

			if (!check_email($data['NEW_EMAIL'])){
				throw new \Exception('Введите правильный e-mail');
			}

			if ($CUser->Update(self::getUser()->GetID(), ['EMAIL' => $data['NEW_EMAIL']])){
				return 1;
			} else {
				throw new \Exception(strip_tags($CUser->LAST_ERROR));
			}

		} else {
			throw new \Exception('Неправильный пароль');
		}
	}

}
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\UserTable;
use Bitrix\Main\Web;

global $USER;
//$APPLICATION->IncludeComponent('bitrix:system.auth.forgotpasswd', 'flat', array(), false);
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$post = $request->getPostList()->toArray();

$result = ['error' => null, 'status' => 0];
try {
	if ($post['TYPE'] == 'USER_LOGIN'){
		$email = htmlspecialcharsbx($post['USER_EMAIL']);
		if (strlen($email) == 0){
			throw new \Exception('Введите e-mail');
		}

		if (!check_email($email)){
			throw new \Exception('Введите e-mail правильно');
		}

		$arUser = UserTable::getRow([
			'select' => ['ID', 'LOGIN', 'EMAIL'],
			'filter' => ['=EMAIL' => $email, 'ACTIVE' => 'Y'],
		]);
		if (is_null($arUser)){
			throw new \Exception('Пользователь с таким e-mail не найден');
		} else {
			$res = $USER->SendPassword($arUser['LOGIN'], $arUser['EMAIL']);
			if ($res['TYPE'] == 'OK'){
//				$res['MESSAGE'] = strip_tags($res['MESSAGE']);
				$res['MESSAGE'] = 'На указанный Вами адрес электронной почты выслано письмо с инструкцией по сбросу пароля.';
				$result['data'] = $res;
				$result['status'] = 1;
			} else {
				throw new \Exception(strip_tags($res['MESSAGE']));
			}
		}

	}
} catch (\Exception $e) {
	$result['error'] = $e->getMessage();
}

echo Web\Json::encode($result);
exit;
<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 05.10.2016
 * Time: 14:33
 */

namespace UL\Handlers;


class OnUser
{
	public static function beforeRegister(&$arFields)
	{
		/** @var \Bitrix\Main\HttpRequest $request */
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();
		if($request->getPost('PERSONAL_MOBILE')){
			$arFields['PERSONAL_MOBILE'] = $request->getPost('PERSONAL_MOBILE');
		}
		if($request->getPost('PERSONAL_BIRTHDAY')){
			$arFields['PERSONAL_BIRTHDAY'] = $request->getPost('PERSONAL_BIRTHDAY');
		}
	}
}
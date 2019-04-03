<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main;

$request = Main\Context::getCurrent()->getRequest();
$file = $request->getFile('ava');

CBitrixComponent::includeComponentClass('mig:personal.main');
$PersonalComponent = new Mig\PersonalComponent();

$result = ['status' => 0, 'error' => null, 'data' => null];
if($file){
	$PersonalComponent->setPostData($file);
	try {
		$save = $PersonalComponent->saveAvatar();
		if($save['status'] === 200){
			$result['status'] = $save['status'];
			$result['data'] = $save['data'];
		}
	} catch (Exception $e){
		$result['error'] = $e->getMessage();
		$result['status'] = $e->getCode();
	}
}

echo Main\Web\Json::encode($result);
exit;
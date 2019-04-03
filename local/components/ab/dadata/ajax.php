<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CBitrixComponent::includeComponentClass('ab:dadata');
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$out = ['ERRORS' => null, 'DATA' => null, 'STATUS' => 0];

$DaData = new AB\DaData();

$signer = new \Bitrix\Main\Security\Sign\Signer();

$post = $request->getPostList()->toArray();
$Result = new \Bitrix\Main\Result();

try {
	$arParams = base64_decode($post['sign']);
	if (strlen($post['sign']) > 0){
		$signer->unsign($arParams, 'Auth_dadata');
	}

	$param = unserialize($arParams);
	$res = $DaData->send(['query'=>$post['query']], $param['TYPE'], $param['KEY']);
	if($res['suggestions']){
		$Result->setData($res);
	} else {
		$Result->addError(new \Bitrix\Main\Error('No result search'));
	}

} catch (\Exception $err) {
	$Result->addError(new \Bitrix\Main\Error($err->getMessage()));
}

if($Result->isSuccess()){
	$out['DATA'] = $Result->getData();
	$out['STATUS'] = 1;
} else {
	$out['DATA'] = null;
	$out['STATUS'] = 0;
	$out['ERRORS'] = $Result->getErrorMessages();
}

echo \Bitrix\Main\Web\Json::encode($out);
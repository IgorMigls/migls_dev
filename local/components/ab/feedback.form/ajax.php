<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter());

use Bitrix\Main\Loader;
use AB\FormIblock;
use AB\FromIblock\FeedbackComponent;
use Bitrix\Main\Web;

Loader::includeModule('ab.form_iblock');

$out = [];
try {
	$params = urldecode($request->getPost('ss'));
	$Protect = new FormIblock\Protect($params);
	$Protect->unSign();
	$params = unserialize(base64_decode($params));
	$out['data'] = $params;

	CBitrixComponent::includeComponentClass("ab:feedback.form");
	$FeedbackComponent = new FeedbackComponent();
	$FeedbackComponent->onPrepareComponentParams($params);
	$out = $FeedbackComponent->action('save')->getResult();

} catch (\Bitrix\Main\Security\Sign\BadSignatureException $e) {
	$out['status'] = 0;
	$out['errors'] = $e->getMessage();
}
echo Web\Json::encode($out);
exit;




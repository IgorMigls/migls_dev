<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
global $APPLICATION;

dump($request->toArray());
?>
		<? $APPLICATION->IncludeComponent(
			'bitrix:mobileapp.demoapi',
			'',
			array(
				'APP_DIR' => '/mob_app/',
				'DEMO_PAGE_ID' => $request->get('page')
			),
			false) ?>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
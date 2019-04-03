<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

CJSCore::Init(array("fx", "ls"));
$curPage = $request->getRequestedPage();
$Asset = \Bitrix\Main\Page\Asset::getInstance();
$dis = '/local/dist';
$classCheck = (int)$request->get('order') > 0 ? 'b-popu-card_check' : false;

if ($USER->IsAuthorized() && $request['logOut'] == 'Y'){
	$USER->Logout();
}
?>
<!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
	<meta property="og:title" content="Ulmart">
	<meta property="og:description" content="Ulmart">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="Ulmart">

	<?
	$Asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css');
	$Asset->addCss('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	$Asset->addCss('https://unpkg.com/element-ui/lib/theme-chalk/index.css');
	$Asset->addCss(SITE_TEMPLATE_PATH.'/js/main.css');
	$Asset->addCss(SITE_TEMPLATE_PATH.'/css/main_style.css');

	//	$Asset->addJs('/local/modules/ab.tools/asset/js/shim/es6-shim.min.js');
	//	$Asset->addJs('/local/modules/ab.tools/asset/js/shim/es6-sham.min.js');
	//	$Asset->addJs('http://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU');

	CUtil::InitJSCore(['lodash']);
	$Asset->addJs(SITE_TEMPLATE_PATH.'/js/jquery-3.2.1.min.js');
	$Asset->addJs(SITE_TEMPLATE_PATH.'/js/popper.min.js');
	$Asset->addJs(SITE_TEMPLATE_PATH.'/js/bootstrap.min.js');

	$Asset->addJs(SITE_TEMPLATE_PATH.'/manifest.js');
	$Asset->addJs(SITE_TEMPLATE_PATH.'/js/vendor.js');


	$Asset->addJs(SITE_TEMPLATE_PATH.'/js/main.js');

	?>
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body class="bg-img">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div id="b-layout">
	<div class="b-wrapper">
		<?
if($USER->IsAdmin())
	$APPLICATION->IncludeComponent('mig:delivery.admin', '', array(), false);
?>
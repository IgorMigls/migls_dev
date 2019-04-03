<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

CJSCore::Init(array("fx"));
$curPage = $request->getRequestedPage();
$Asset = \Bitrix\Main\Page\Asset::getInstance();
$dis = '/local/dist';

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
	$Asset->addCss($dis.'/css/animate.css');
	$Asset->addCss($dis.'/libs/css/normalize/normalize.min.css');
	$Asset->addCss($dis.'/libs/css/slick/slick.css');
	$Asset->addCss($dis.'/libs/css/jscrollpane/jquery.jscrollpane.css');
	$Asset->addCss($dis.'/libs/css/selectize/selectize.css');
	$Asset->addCss($dis.'/libs/css/magnificPopup/magnific-popup.css');
	$Asset->addCss($dis.'/css/fonts.css');
	$Asset->addCss($dis.'/css/styles.css');
	//	$Asset->addCss($dis.'/libs/css/font-awesome.min.css');
	$Asset->addCss('/bitrix/css/main/font-awesome.min.css');
	$Asset->addCss($dis.'/css/main.css');

	$Asset->addJs('http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
	$Asset->addJs('//code.jquery.com/ui/1.11.4/jquery-ui.js');

	$Asset->addJs($dis.'/libs/js/slick/slick.min.js');
	$Asset->addJs($dis.'/libs/js/jscrollpane/jquery.mousewheel.js');
	$Asset->addJs($dis.'/libs/js/jscrollpane/mwheelIntent.js');
	$Asset->addJs($dis.'/libs/js/jscrollpane/jquery.jscrollpane.min.js');
	$Asset->addJs($dis.'/libs/js/selectize/selectize.min.js');
	$Asset->addJs($dis.'/libs/js/magnificPopup/jquery.magnific-popup.min.js');
	$Asset->addJs($dis.'/libs/js/navgoco/jquery.navgoco.min.js');
	$Asset->addJs($dis.'/libs/js/jquery.zoom.min.js');
	$Asset->addJs('http://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU');
	$Asset->addJs('/local/dist/libs/js/is.min.js');
	$Asset->addJs('/local/dist/libs/js/jquery.mask.min.js');

	$Asset->addJs($dis.'/libs/js/angular/angular.min.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-animate.min.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-resource.min.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-sanitize.min.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-file-upload.min.js');
	$Asset->addJs($dis.'/libs/js/angular/ui-router.min.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-selectize.js');
	$Asset->addJs($dis.'/libs/js/angular/angular-jscrollpane.js');
	$Asset->addJs($dis.'/libs/js/angular/aService.js');



	$Asset->addJs($dis.'/js/common.js');
	$Asset->addJs($dis.'/js/index.js');
	$Asset->addJs($dis.'/js/script.js');

	$Asset->addJs($dis.'/js/app.js');
	?>
	<!--[if lt IE 9 ]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<!--<![endif]-->
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body class="bg-img2" ng-app="appUL">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div id="b-layout" class="bg-none">
	<div class="b-wrapper">
		<div class="order-wrapper">
			<div class="order__50">
				<div class="order1-wrapper">
					<div class="order1__head"><span class="callback b-ib">Обратный звонок</span>
						<a href="tel:+74956498570" class="b-ib callback__link"><span>+7 (927) 005 05 16</span></a>
					</div>
					<div class="order1__logo">
						<a href="/" class="b-ib"><img src="/local/dist/images/logo_2_migls.png" alt=""></a>
						<a href="/" class="b-ib b-button check__back">Вернуться на главную</a>
					</div>
					<? // Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
					$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						".default",
						Array(
							"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
							"AREA_FILE_SUFFIX" => "order_sect",      // Суффикс имени файла включаемой области
							"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
						)
					); ?>
<!--					<div class="order1__footer">© Интернет-гипермаркет «migls.ru». Все права защищены.</div>-->
				</div>
			</div>

			<div class="order__50">
				<div class="b-popup b-popup-card b-popup-order">
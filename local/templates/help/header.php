<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;
global $APPLICATION, $USER;

/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$curPage = $request->getRequestedPage();
$Asset = \Bitrix\Main\Page\Asset::getInstance();
$dis = '/local/dist';

if ($USER->IsAuthorized() && $request['logOut'] == 'Y'){
	$USER->Logout();
//	unset($_SESSION['REGIONS']);
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
	$Asset->addCss('/bitrix/css/main/font-awesome.min.css');
	$Asset->addCss($dis . '/libs/css/sweetalert.css');
	$Asset->addCss($dis.'/css/main.css');
	$Asset->addCss($dis.'/css/application.css');

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
	$Asset->addJs($dis.'/libs/js/sweetalert.js');
	$Asset->addJs('http://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU');
	$Asset->addJs('/local/dist/libs/js/is.min.js');
	$Asset->addJs('/local/dist/libs/js/jquery.mask.min.js');

	$Asset->addJs($dis.'/js/common.js');
	$Asset->addJs($dis.'/js/index.js');
	$Asset->addJs($dis.'/js/script.js');

	$Asset->addJs($dis . '/libs/react/react-with-addons.min.js');
	$Asset->addJs($dis . '/libs/react/react-dom.min.js');

	?>
	<!--[if lt IE 9 ]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<!--<![endif]-->
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div id="b-layout">
	<div class="b-wrapper">
		<!-- Header -->
		<header class="b-header">
			<div class="b-header__top b-ib-wrapper">
				<div class="b-header__logo b-ib"><a href="/"><img src="/local/dist/images/logo_2_migls.png" alt=""></a></div>
				<div class="b-header-search b-ib b-header-search_37">
					<form action="">
						<div class="b-header-search__left b-ib"></div>
						<div class="b-header-search__center b-ib">
							<input type="text" placeholder="Чем мы можем вам помочь?" value="" class="b-header-search__input accepted__input">
						</div>
						<div class="b-header-search__right b-ib">
							<button class="b-button b-button_green">Найти</button>
						</div>
					</form>
				</div>
				<div class="b-header-user b-ib b-header-user_46">
					<div class="b-header_ph b-ib"><span class="h-phone">+7 (927) 005 05 16</span></div>
<!--					--><?//$APPLICATION->IncludeComponent('ul:help.feedback','',[],false)?>
					<? $APPLICATION->IncludeComponent(
						"bitrix:system.auth.form",
						"flat",
						array(
							"COMPONENT_TEMPLATE" => "flat",
						),
						false
					); ?>
				</div>
			</div>
		</header>
		<div class="b-help-wrapper">
			<div class="b-mac animated fadeInRight"></div>
			<h2 class="help__h2 b-ib"><?$APPLICATION->ShowTitle()?></h2>
			<a href="/" class="b-button check__back">Вернуться к покупкам</a>
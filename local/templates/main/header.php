<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;

global $USER, $APPLICATION;

Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");
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

	CUtil::InitJSCore(["fx", 'vue', 'vue_resource', 'lodash']);

	$Asset->addCss($dis.'/css/animate.css');
	$Asset->addCss($dis.'/libs/css/normalize/normalize.min.css');
	$Asset->addCss($dis.'/libs/css/slick/slick.css');
	$Asset->addCss($dis.'/libs/css/jscrollpane/jquery.jscrollpane.css');
	$Asset->addCss($dis.'/libs/css/selectize/selectize.css');
	$Asset->addCss($dis.'/libs/css/magnificPopup/magnific-popup.css');
	$Asset->addCss($dis.'/css/fonts.css');
	$Asset->addCss($dis.'/css/styles.css');
//	$Asset->addCss('/bitrix/css/main/font-awesome.min.css');
//	$Asset->addString('<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.11/css/all.css" integrity="sha384-p2jx59pefphTFIpeqCcISO9MdVfIm4pNnsL08A6v5vaQc4owkQqxMV8kg4Yvhaw/" crossorigin="anonymous">');

	$Asset->addString('<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">');
	$Asset->addCss($dis . '/libs/css/sweetalert.css');
	$Asset->addCss($dis. '/libs/js/ion.rangeSlider/css/ion.rangeSlider.css');
	$Asset->addCss($dis. '/libs/js/ion.rangeSlider/css/ion.rangeSlider.skinModern.css');
	$Asset->addCss('https://unpkg.com/element-ui/lib/theme-chalk/index.css');
	$Asset->addCss('/local/dist/css/jquery.fancybox.min.css');

	$Asset->addCss($dis.'/css/main.css');
	$Asset->addCss($dis.'/css/application.css');
	$Asset->addCss($dis.'/js/globalApp.css');

//	$Asset->addJs('http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
	$Asset->addJs('//code.jquery.com/jquery-3.2.1.min.js');

	//	$Asset->addJs('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
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
	$Asset->addJs('https://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU');
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

	$Asset->addJs('/local/dist/js/jquery.fancybox.min.js');

	$Asset->addJs($dis . '/libs/js/aService.js');

	$Asset->addJs($dis.'/js/app.js');

//	$Asset->addJs($dis . '/libs/react/react-with-addons.min.js');
//	$Asset->addJs($dis . '/libs/react/react-dom.min.js');
//	$Asset->addJs($dis . '/libs/react/babel.min.js');
	$Asset->addJs($dis . '/libs/js/ion.rangeSlider/js/ion.rangeSlider.min.js');
//	$Asset->addJs($dis.'/js/require.min.js');
//	$Asset->addJs($dis.'/libs/js/vendor.lib.js');

	$Asset->addJs();

	if(defined('AB_DEBUG')){
		$Asset->addJs('https://unpkg.com/vue@2.5.2/dist/vue.js');
	} else {
		$Asset->addJs('https://unpkg.com/vue@2.5.2/dist/vue.min.js');
	}

	$Asset->addJs('https://unpkg.com/element-ui/lib/index.js');

	$Asset->addJs('/local/dist/js/globalApp.js');

	?>
	<!--[if lt IE 9 ]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<!--<![endif]-->
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body  ng-app="appUL">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div id="b-layout">
	<div class="b-wrapper">
		<!-- Header -->
		<header class="b-header">
			<div class="b-header__top b-ib-wrapper">
				<div class="b-header__logo b-ib"><a href="/"><img src="/local/dist/images/logo_2_migls.png" alt=""></a></div>
				<div class="b-header-search b-ib" style="margin-top: 13px;">
					<? $APPLICATION->IncludeComponent(
						"ul:search.all",
						"",
						array(
							"CATEGORY_0" => array(
								0 => "iblock_catalog",
							),
							"CATEGORY_0_TITLE" => "",
							"CATEGORY_0_iblock_catalog" => array(
								0 => "all",
							),
							"CHECK_DATES" => "Y",
							"CONTAINER_ID" => "title-search",
							"CONVERT_CURRENCY" => "N",
							"INPUT_ID" => "title-search-input",
							"NUM_CATEGORIES" => "1",
							"ORDER" => "rank",
							"PAGE" => "#SITE_DIR#search/index.php",
							"PREVIEW_HEIGHT" => "75",
							"PREVIEW_TRUNCATE_LEN" => "",
							"PREVIEW_WIDTH" => "75",
							"PRICE_CODE" => array(
								0 => "BASE",
							),
							"PRICE_VAT_INCLUDE" => "Y",
							"SHOW_INPUT" => "Y",
							"SHOW_OTHERS" => "N",
							"SHOW_PREVIEW" => "Y",
							"TOP_COUNT" => "10",
							"USE_LANGUAGE_GUESS" => "Y",
							"COMPONENT_TEMPLATE" => "top",
						),
						false
					); ?>

				</div>
				<div class="b-header-user b-ib" style="text-align: right;">
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
			<div class="b-header-main b-ib-wrapper">
				<div class="b-header-main__left b-ib">
					<nav class="b-nav b-ib">
						<? $APPLICATION->IncludeComponent(
							'ul:products.category',
							'top',
							array(
								"COMPONENT_TEMPLATE" => "top",
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "36000000",
								"DEPTH_LEVEL" => "2",
								"IBLOCK_TYPE" => "catalog",
							), false
						); ?>
						<div class="b-nav__item b-nav__item_subitems shops-m b-ib">
							<a href="javascript:" class="b-nav__link">Магазины</a>
							<div class="b-header-popup b-ib-wrapper">
								<div class="shop_items_top b-header-popup__wrapper">
									<div class="b-header-popup__right b-ib">
										<div class="b-custom-scroll js-custom-scroll">
											<div class="b-header-popup__shops">
												<? $APPLICATION->IncludeComponent('ul:shop.all.list', 'top', [], false) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--<div class="b-nav__item b-nav__item_subitems b-ib"><a href="rest.html" class="b-nav__link">Рестораны</a></div>
						<div class="b-nav__item b-nav__item_subitems b-ib"><a href="rec.html" class="b-nav__link">Рецепты</a></div>-->
					</nav>
				</div>
				<div class="b-header-main__right b-ib">
					<? $APPLICATION->IncludeComponent('mig:address.window', '', array(), false);?>
					<? $APPLICATION->IncludeComponent('mig:basket', '', [], false) ?>
				</div>
			</div>
		</header>

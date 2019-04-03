<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;
global $USER, $APPLICATION;
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

CJSCore::Init(array("fx"));
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
	$Asset->addCss($dis.'/css/fonts.css');
	$Asset->addCss($dis.'/css/styles.css');
	$Asset->addCss('/bitrix/css/main/font-awesome.min.css');
	$Asset->addCss('https://unpkg.com/element-ui/lib/theme-chalk/index.css');

	$Asset->addCss($dis.'/css/main.css');

	$Asset->addJs('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
	$Asset->addJs('//code.jquery.com/ui/1.11.4/jquery-ui.js');
//	$Asset->addJs('/local/modules/ab.tools/asset/js/shim/es6-shim.min.js');
//	$Asset->addJs('/local/modules/ab.tools/asset/js/shim/es6-sham.min.js');
	if(defined('AB_DEBUG')){
		$Asset->addJs('https://unpkg.com/vue@2.5.2/dist/vue.js');
	} else {
		$Asset->addJs('https://unpkg.com/vue@2.5.2/dist/vue.min.js');
	}
	$Asset->addJs('https://unpkg.com/element-ui@2.0.1/lib/index.js');
	$Asset->addJs('https://unpkg.com/vue-router/dist/vue-router.min.js');
	CUtil::InitJSCore(["fx", 'vue_resource', 'lodash']);

	if(!$USER->IsAuthorized()){
		LocalRedirect('/?auth=Y');
	}
	?>
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body class="bg-img">
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<div id="b-layout" class="bg-none">
	<div class="b-wrapper">
		<div class="order-wrapper">
			<div class="order__50">
				<div class="order1-wrapper">
					<div class="order1__head"><span class="callback b-ib">Обратный звонок</span>
						<a href="javascript:" class="b-ib callback__link">
							<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
							$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								".default",
								Array(
									"AREA_FILE_SHOW"      => "sect",     // Показывать включаемую область
									"AREA_FILE_SUFFIX"    => "phone_main",      // Суффикс имени файла включаемой области
									"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
								)
							);?>
						</a>
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
					<div class="order1__footer"><?=date('Y')?> © Интернет-гипермаркет «migls.ru». Все права защищены.</div>
				</div>
			</div>

			<div class="col__403">
				<div class="b-popup b-popup-card b-popup-order b-popu-card_check">
					<?$APPLICATION->IncludeComponent('mig:order.creator', '', array(), false)?>
				</div>
			</div>
		</div>
<?php
$pathModuleTheme = '/bitrix/themes/.default/pw.util';
$pathCss = $pathModuleTheme.'/css';
$pathJs = $pathModuleTheme.'/js/lib';

$arJSCoreConfig = array(
	'angular'=>array(
		'js'=>array(
			$pathJs.'/angular.min.js',
			$pathJs.'/angular-resource.min.js',
			$pathJs.'/angular-animate.min.js',
			$pathJs.'/angular-messages.min.js',
			$pathJs.'/angular-sanitize.min.js',
			$pathJs.'/bootstrap-growl_2.min.js',
			$pathJs.'/autofill-event.js',
			$pathJs.'/ajax_service.js',
		),
	),
	'bootstrapAngular'=>array(
		'js'=>$pathJs.'/ui-bootstrap-tpls-0.14.3.min.js'
	),
	'fontsAw'=>array(
		'css'=>$pathCss.'/font-awesome.min.css'
	),
	'bootstrap3'=>array(
		'css'=>$pathCss.'/bootstrap.min.css'
	),
	'fancyBox'=>array(
		'js'=>array(
			$pathJs.'/jquery.mousewheel-3.0.6.pack.js',
			$pathJs.'/fancy/jquery.fancybox.2.1.5.pack.js',
			$pathJs.'/fancy/fancy.service.js',
		),
		'css'=>$pathCss.'/fancy/jquery.fancybox.css'
	),
	'fancyBoxHelpers'=>array(
		'js'=>array(
			$pathJs.'/fancy/jquery.fancybox-buttons.js',
			$pathJs.'/fancy/jquery.fancybox-media.js',
			$pathJs.'/fancy/jquery.fancybox-thumbs.js',
		),
		'css'=>array(
			$pathCss.'/fancy/helpers/jquery.fancybox-buttons.css',
			$pathCss.'/fancy/helpers/jquery.fancybox-thumbs.css',
		)
	)
);
foreach ($arJSCoreConfig as $ext => $arExt)
{
	CJSCore::RegisterExt($ext, $arExt);
}
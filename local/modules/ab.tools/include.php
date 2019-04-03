<?php
include(dirname(__FILE__).'/../../vendor/autoload.php');

spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3){
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]).'.php',
	));
	$filePath = str_replace('AB'.DIRECTORY_SEPARATOR.'Tools'.DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = preg_replace('#AB\/Tools\/#', '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

//$root = \Bitrix\Main\Application::getDocumentRoot();
//$routesFile = $root.'/local/php_interface/ab.tools/routes.php';
//if(file_exists($routesFile) && is_readable($routesFile)){
//	/** @noinspection PhpIncludeInspection */
//	require_once $routesFile;
//}

//$ev = \Bitrix\Main\EventManager::getInstance();
//$ev->registerEventHandlerCompatible('fileman','OnBeforeHTMLEditorScriptRuns', 'ab.tools', '\AB\Tools\EventHandlers', 'onIncludeHTMLEditorScript');
$path = '/local/modules/ab.tools/asset';
$asset = [
	'react' => [
		'js' => [
			$path.'/js/shim/es6-shim.min.js',
			$path.'/js/shim/es6-sham.min.js',
			$path.'/js/react/react-with-addons.min.js',
			$path.'/js/react/react-dom.min.js',
			'/local/dist/vendor/main.lib.js',
		],
		'css' => [
			$path.'/css/preloaders.css',
			$path.'/css/animate.min.css',
		],
	],
	'shim' => [
		'js' => [
			$path.'/js/shim/es6-shim.min.js',
			$path.'/js/shim/es6-sham.min.js',
		],
	],
	'isjs' => [
		'js' => [$path.'/js/is.min.js'],
	],
	'sweetalert' => [
		'js' => [
			$path.'/js/sweetalert.min.js',
		],
		'css' => [
			$path.'/css/sweetalert.css',
		],
	],
	'mask' => ['js' => $path.'/js/jquery.mask.min.js'],
	'coreABTools' => ['js' => $path.'/js/coreABTools.js'],
	'vue' => [
		'js' => [
//			$path.'/js/vue/'.(defined('AB_DEBUG') ? 'vue.js' : 'vue.min.js'),
			$path.'/js/vue/vee-validate.min.js',
		],
	],
	'vue_ui' => [
		'js' => [$path.'/js/vue/ui/index.js'],
//		'js' => ['https://unpkg.com/element-ui@2.0.1/lib/index.js'],
		'css' => [$path.'/js/vue/ui/index.css']
	],
	'animatecss' => ['css' => $path.'/css/animate.min.css'],
	'font_fa' => ['css' => $path.'/css/font-awesome.min.css'],
	'jquery3'=>['js' => $path.'/js/jquery-3.2.1.min.js'],
	'vue_resource' => ['js' => $path. '/js/vue-resource1.3.4.min.js'],
	'vue_router' => ['js' => 'https://unpkg.com/vue-router/dist/vue-router.js'],
	'lodash' => ['js' => $path.'/js/lodash.min.js']
];
foreach ($asset as $name => $arItem) {
	CJSCore::RegisterExt($name, $arItem);
}
CUtil::InitJSCore(['coreABTools']);

use Symfony\Component\VarDumper\VarDumper;

/*if (!function_exists('dump')){
	function dump($var, $show = false)
	{
		global $USER;
		if (!is_object($USER))
			$USER = new \CUser();

		if ($USER->IsAdmin() || $show){
			$iteArgs = debug_backtrace();
			$bt = $iteArgs[0];
			$dRoot = $_SERVER["DOCUMENT_ROOT"];
			$dRoot = str_replace("/", "\\", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);
			$dRoot = str_replace("\\", "/", $dRoot);
			$bt["file"] = str_replace($dRoot, "", $bt["file"]);

			?>
			<div style='font-size:9pt; border: 1px solid #999'>
				<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?>
					[<?=$bt["line"]?>]
				</div>
				<? VarDumper::dump($var); ?>
			</div>
			<?
		}
	}
}*/
//if (!function_exists('dd')){
//	function dd($var, $show = false)
//	{
//		dump($var, $show);
//		exit();
//	}
//}
CUtil::InitJSCore(['coreABTools']);

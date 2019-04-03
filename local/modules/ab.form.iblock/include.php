<?php
spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3) {
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]) . '.php',
	));
	$filePath = str_replace('AB' . DIRECTORY_SEPARATOR . 'FormIblock' . DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = preg_replace('#AB\/FormIblock\/#', '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)) {
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

$path = str_replace(\Bitrix\Main\Application::getDocumentRoot(), '', dirname(__FILE__).'/tools');

$arJSCoreConfig = array(
	'jq3' => [
		'js' => [$path . '/js/jquery-3.1.0.min.js']
	],
	'bootstrap' => [
		'js' => [$path . '/js/bootstrap.min.js'],
		'css' => [$path . '/css/bootstrap.min.css']
	],
	'form_iblock' => [
		'js' => [
			$path . '/js/jquery.form.min.js',
			$path . '/js/service.js',
		],
	],


);
foreach ($arJSCoreConfig as $ext => $arExt) {
	CJSCore::RegisterExt($ext, $arExt);
}

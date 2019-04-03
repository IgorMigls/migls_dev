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
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]) . '.php'
	));
	$filePath = str_replace('UL' . DIRECTORY_SEPARATOR . 'Main'. DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = str_replace('Ul' . DIRECTORY_SEPARATOR . 'Main'. DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);


	if (is_readable($filePath) && is_file($filePath)) {

		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

use Bitrix\Main\Application;

define('PHPEXCEL_ROOT', Application::getDocumentRoot().'/local/php_interface/vendor');

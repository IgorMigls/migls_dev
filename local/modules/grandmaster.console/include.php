<?php
require_once(dirname(__FILE__).'/vendor/autoload.php');

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
	$filePath = str_replace('GrandMaster'.DIRECTORY_SEPARATOR.'Console'.DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = preg_replace('#GrandMaster\/Console\/#', '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

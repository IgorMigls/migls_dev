<?
\Bitrix\Main\Loader::registerAutoLoadClasses(
	"soft.iblock",
	array(
		"Soft\\IBlock\\ElementTable" => "lib/IBlock/ElementTable.php",
		"Soft\\IBlock\\Query" => "lib/IBlock/Query.php",
		"Soft\\IBlock\\PropertyEnumTable" => 'lib/IBlock/PropertyEnumTable.php',
		"Soft\\IBlock\\MultiPropTable" => "lib/IBlock/MultiPropTable.php",
		"Soft\\IBlock\\Tools\\Filter" => "tools/Filter.php",
	)
);
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
	$filePath = str_replace('Soft'.DIRECTORY_SEPARATOR,'',$filePath);
	$filePath = preg_replace('#Soft\/#','',$filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)) {
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});
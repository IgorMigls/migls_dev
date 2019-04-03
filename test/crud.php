<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use AB\Iblock\Element;
use Bitrix\Main\Loader;

includeModules(['ab.iblock', 'catalog']);

$nameRand = randString(10);
$save = [
	'NAME' => $nameRand,
	'IBLOCK_ID' => 4,
	'PREVIEW_TEXT' => 'Всякая хуйня',
	'ssdasd' => 123
];

$ID = 2806;
$result = Element::update($ID, $save);
<?php
define('AB_DEBUG', true);

require_once(__DIR__.'/../../../vendor/autoload.php');

function PR($o, $show = false)
{
	global $USER;
	if ($USER->GetID() == 1 || $show){
		$bt = debug_backtrace();
		$bt = $bt[0];
		$dRoot = $_SERVER["DOCUMENT_ROOT"];
		$dRoot = str_replace("/", "\\", $dRoot);
		$bt["file"] = str_replace($dRoot, "", $bt["file"]);
		$dRoot = str_replace("\\", "/", $dRoot);
		$bt["file"] = str_replace($dRoot, "", $bt["file"]);
		?>
		<div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
			<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?> [<?=$bt["line"]?>
				]
			</div>
			<pre style='padding:10px; text-align: left'><? print_r($o) ?></pre>
		</div>
		<?
	} else {
		return false;
	}
}

Bitrix\Main\Loader::includeModule('pw.util');
Bitrix\Main\Loader::includeModule('digitalwand.admin_helper');
Bitrix\Main\Loader::includeModule('ul.main');
Bitrix\Main\Loader::includeModule('ab.tools');

spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3){
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__.'/',
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]).'.php',
	));
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);
	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

$EventManager = \Bitrix\Main\EventManager::getInstance();

require_once($_SERVER['DOCUMENT_ROOT'].'/local/route/ajax/routes.php');

$iblocks = [
	'REMAINS' => 6,
	'SHOP' => 5,
	'PRODUCTS' => 4,
];
\UL\Tools::setIblocks($iblocks);

/**
 * @method includeModules
 * @param string|array $arModules
 */
function includeModules($arModules)
{
	if (!is_array($arModules))
		$arModules = array($arModules);

	foreach ($arModules as $module) {
		\Bitrix\Main\Loader::includeModule($module);
	}
}

$EventManager->addEventHandlerCompatible('sale', \Bitrix\Sale\EventActions::EVENT_ON_BASKET_ITEM_SAVED, ['\UL\Handlers\Basket', 'onBeforeElementSave']);

$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', ['\UL\Handlers\IblockElements', 'clearElementCache']);
$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementUpdate', ['\UL\Handlers\IblockElements', 'clearElementCache']);

$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockPropertyAdd', ['\UL\Handlers\IblockElements', 'clearCacheProperty']);
$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockPropertyUpdate', ['\UL\Handlers\IblockElements', 'clearCacheProperty']);
$EventManager->addEventHandlerCompatible('iblock', 'OnIBlockPropertyDelete', ['\UL\Handlers\IblockElements', 'clearCacheProperty']);

//$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementAdd', ['\UL\Handlers\IblockElements', 'createRemainSection']);
//$EventManager->addEventHandlerCompatible('iblock', 'OnAfterIBlockElementUpdate', ['\UL\Handlers\IblockElements', 'createRemainSection']);

$EventManager->addEventHandlerCompatible('main', 'OnBeforeUserRegister', ['\UL\Handlers\OnUser', 'beforeRegister']);
//$EventManager->addEventHandlerCompatible('main','OnBuildGlobalMenu', function (&$aGlobalMenu, &$aModuleMenu){
//	PR($aGlobalMenu);
//});

$EventManager->addEventHandlerCompatible('main', 'OnAfterUserRegister', function (&$arFields){
	if(intval($arFields['USER_ID']) > 0){
		$user = new CUser();
		$user->Update($arFields['USER_ID'], ['UF_FREE_DELIVERY' => 1]);
	}
});

function searchIndex ($arFields){
	$arCatalogs = \UL\Main\CatalogHelper::getCatalogIblocks();

	if(array_key_exists($arFields['PARAM2'], $arCatalogs)){
		unset($arFields["BODY"]);
	}
}

$EventManager->addEventHandlerCompatible('search', 'BeforeIndex', 'searchIndex');
$EventManager->addEventHandlerCompatible('search', 'OnBeforeIndexUpdate', 'searchIndex');

$EventManager->addEventHandler('sale', 'OnSaleOrderDeleted', ['UL\Handlers\OrderEvents', 'OnSaleOrderDeleted']);
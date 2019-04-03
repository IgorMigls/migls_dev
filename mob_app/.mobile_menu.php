<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/mobileapp/public/.mobile_menu.php");

$Dir = new Bitrix\Main\IO\Directory($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/mobileapp.demoapi/templates/.default/pages');
$items = [
	array(
		"text" => GetMessage("MOBILE_MENU_MAIN"),
		"data-url" => SITE_DIR . "mob_app/index.php",
		"class" => "menu-item",
		"id" => "main",

	),
];
foreach ($Dir->getChildren() as $k => $child) {
	$name = str_replace('.php', '', $child->getName());
	$items[] = array(
		"text" => $name,
		"data-url" => SITE_DIR . "mob_app/".$name.'/',
		"class" => "menu-item",
		"id" => $name,
	);
}

$arMobileMenuItems = array(
	array(
		"type" => "section",
		"text" => GetMessage("MOBILE_MENU_HEADER"),
		"sort" => "100",
		"items" => $items
	)
);
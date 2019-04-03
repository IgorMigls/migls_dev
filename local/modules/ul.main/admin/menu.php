<?php
use Bitrix\Main\Localization\Loc;
use UL\Main\Admins\MapEditHelper;
use UL\Main\Admins\MapListHelper;
use UL\Main\Admins;

Loc::loadLanguageFile(__FILE__);
\Bitrix\Main\Loader::includeModule('ul.main');

$menu = array(
	array(
		"parent_menu" => "global_menu_services",
		"section" => "ul_map",
		"sort" => 200,
		"text" => Loc::getMessage('UL_MAIN_MAP_MENU'),
		"url" => '',
		"icon" => "fileman_sticker_icon",
		"page_icon" => "fileman_sticker_icon",
		"more_url" => [
			MapEditHelper::getUrl()
		],
		"items_id" => "ul_map",
		"items" => [
			array(
				"sort" => 200,
				"url" => MapListHelper::getUrl(),
				"more_url" => array(
					MapEditHelper::getUrl()
				),
				"text" => Loc::getMessage('UL_MAIN_MAP_MENU_LIST'),
				"icon" => "iblock_menu_icon_iblocks", //highloadblock_menu_icon
				"page_icon" => "iblock_page_icon_iblocks",
			)
		]
	),
	array(
		"parent_menu" => "global_menu_store",
		"section" => "ul_import",
		"sort" => 10,
		"text" => Loc::getMessage('UL_MAIN_IMPORT'),
		"url" => '',
		"icon" => "update_marketplace_modules",
		"page_icon" => "update_marketplace_modules",
		"more_url" => [],
		"items_id" => "ul_import",
		"items" => [
			array(
				"sort" => 200,
				"url" => Admins\Import\PresetEditHelper::getUrl(),
				"more_url" => array(
					Admins\Import\PresetEditHelper::getUrl(),
				),
				"text" => Loc::getMessage('UL_MAIN_IMPORT_PRESET'),
				"icon" => "iblock_menu_icon_iblocks", //highloadblock_menu_icon
				"page_icon" => "iblock_page_icon_iblocks",
			),
			array(
				"sort" => 200,
				"url" => Admins\Import\SectionEditHelper::getUrl(),
				"more_url" => array(
					Admins\Import\SectionEditHelper::getUrl(),
				),
				"text" => Loc::getMessage('UL_MAIN_IMPORT_SECTION'),
				"icon" => "iblock_menu_icon_iblocks", //highloadblock_menu_icon
				"page_icon" => "iblock_page_icon_iblocks",
			),
			array(
				"sort" => 200,
				"url" => Admins\Import\ProductEditHelper::getUrl(),
				"more_url" => array(
					Admins\Import\ProductEditHelper::getUrl(),
				),
				"text" => Loc::getMessage('UL_MAIN_IMPORT_PRODUCT'),
				"icon" => "iblock_menu_icon_iblocks", //highloadblock_menu_icon
				"page_icon" => "iblock_page_icon_iblocks",
			),
			array(
				"sort" => 500,
				"url" => Admins\Import\PriceEditHelper::getUrl(),
				"more_url" => array(
					Admins\Import\PriceEditHelper::getUrl(),
				),
				"text" => Loc::getMessage('UL_MAIN_IMPORT_REMAINS'),
				"icon" => "iblock_menu_icon_iblocks", //highloadblock_menu_icon
				"page_icon" => "iblock_page_icon_iblocks",
			)
		]
	)
);

return $menu;
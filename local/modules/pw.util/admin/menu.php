<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);


return [
	"parent_menu" => "global_menu_services",
	"section" => "pw_generator_d7",
	"sort" => 200,
	"text" => Loc::getMessage('PW_MAP_D7_MENU'),
	"url" => 'pw_gen_d7.php?lang='.LANG,
	"icon" => "fileman_menu_icon",
	"page_icon" => "fileman_menu_icon",
	"more_url" => ['pw_gen_d7.php'],
	"items_id" => "pw_generator_d7",
];
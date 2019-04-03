<?require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$Dir = new Bitrix\Main\IO\Directory($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/mobileapp.demoapi/templates/.default/pages');
$items = [];
foreach ($Dir->getChildren() as $k => $child) {
	$name = str_replace('.php', '', $child->getName());
	$items[] = array(
		"text" => $name,
		"data-url" => SITE_DIR . "mob_app/".$name,
		"class" => "menu-item",
		"id" => $name,
	);
}
dump($items);
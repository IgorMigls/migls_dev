<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use UL\Main\Search\TitleIndexTable;

//TitleIndexTable::createTable();

$searchPhrase = 'рис';

$arItemsText = [];
$connect = \Bitrix\Main\Application::getConnection();
$sql = "SELECT *, MATCH (TEXT) AGAINST ('".$searchPhrase."') as REL
FROM `ul_search_title_index` ft
LEFT JOIN b_search_content as BST ON BST.ITEM_ID = ft.ITEM_ID AND BST.PARAM2 = ft.IBLOCK_ID
WHERE MATCH (TEXT) AGAINST ('".$searchPhrase."') > 0 OR TEXT LIKE '".$searchPhrase."%'
";
$obList = $connect->query($sql);
while ($list = $obList->fetch()){
	$arItemsText[] = $list;
}
PR($arItemsText);
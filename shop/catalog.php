<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>

<? $APPLICATION->IncludeComponent('ul:products', '', [
	'URL_TEMPLATE' => '/shop/#CITY#/#SHOP#/',
], false) ?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
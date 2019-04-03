<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");
?>
<?$APPLICATION->IncludeComponent('ul:personal.favorite', '', array(), false);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
if(!$USER->IsAuthorized()){
	LocalRedirect('/?auth=Y');
}
?>

<?$APPLICATION->IncludeComponent('mig:personal.main', '', array(), false)?>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>
<?//$APPLICATION->IncludeComponent('mig:personal.main', '', array(), false)?>


<? $APPLICATION->IncludeComponent(
	"ul:personal",
	"",
	Array()
); ?>


<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
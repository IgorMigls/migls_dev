<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>
<br />
<br />
<br />
<br />
<?$APPLICATION->IncludeComponent(
	"ab:feedback.form", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "form_iblock",
		"IBLOCK_ID" => "23",
		"FIELDS" => array(
			0 => "EMAIL",
			1 => "PHONE",
			2 => "FIO",
			3 => "PREVIEW_TEXT",
		),
		"REQUIRED" => array(
			0 => "0",
		),
		"EMAIL_EVENT" => "AB_FORMS",
		"NAME_ELEMENT" => "AB_CUR_DATE",
		"EMAIL_ADMIN" => "",
		"A_INSERT_LOGIN" => "0",
		"A_INSERT_EMAIL" => "0",
		"A_INSERT_PHONE" => "0",
		"RENAME_EMAIL" => "Мыло",
		"RENAME_IP" => "",
		"RENAME_PHONE" => "Мобила",
		"RENAME_FIO" => "",
		"RENAME_CODE" => "",
		"RENAME_PREVIEW_TEXT" => "Мессага",
		"RENAME_DETAIL_TEXT" => "",
		"RENAME_PREVIEW_PICTURE" => "",
		"RENAME_DETAIL_PICTURE" => "",
		"FORM_ID" => "form_request",
		"FORM_NAME_BLOCK" => "Обратная связь",
		"RENAME_NAME" => ""
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
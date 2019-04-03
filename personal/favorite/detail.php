<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();n
?>

<?$APPLICATION->IncludeComponent('ul:personal.favorite.detail','',array(
	'ID' => intval($request->get('id'))
), false)?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
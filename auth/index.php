<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");

$userName = CUser::GetFullName();
if (!$userName)
	$userName = CUser::GetLogin();
?><script>
	<?if ($userName):?>
	BX.localStorage.set("eshop_user_name", "<?=CUtil::JSEscape($userName)?>", 604800);
	<?else:?>
	BX.localStorage.remove("eshop_user_name");
	<?endif?>

	<?if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0 && preg_match('#^/\w#', $_REQUEST["backurl"])):?>
	document.location.href = "<?=CUtil::JSEscape($_REQUEST["backurl"])?>";
	<?endif?>
</script> <?
$APPLICATION->SetTitle("Авторизация");
?>
<p>
	Поздравляем! Регистрация прошла успешно, мы уже хотим оправдать Ваше доверие и ждем первого заказа!
</p>
<p>
 <a href="<?=SITE_DIR?>">Вернуться на главную страницу</a>
</p>
<p>
 <br>
</p>
<p>
	 <br>
</p><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

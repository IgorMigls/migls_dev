<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $APPLICATION;
$APPLICATION->SetTitle("Страница не найдена");?>

	<div class="b-error">
		<div class="b-error__text">
			<p>
				Неправильно набран адрес, или такой страницы больше не существует. <br>
				Вернитесь <a href="/" class="error__link">на главную </a>
				или <a href="/search/map/" class="error__link">воспользуйтесь картой сайта.</a>
			</p>
			<img src="/local/dist/images/404.jpg" alt=""><span class="error__span">ошибка</span>
		</div>
	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

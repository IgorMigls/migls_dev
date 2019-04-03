<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
CUtil::InitJSCore('ls');
$this->addExternalCss($templateFolder.'/index.css');

$this->addExternalJs('https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.min.js');
$this->addExternalJs('https://api-maps.yandex.ru/2.1/?lang=ru_RU');
$this->addExternalJs($templateFolder.'/index.js');
?>
<div id="main_order_form">
	<main-form :user="<?=$arResult['USER_ID']?>">
		<component :is="activeComponent">
			<span class="tip_field animated zoomIn" slot="name_tip">
				Название адреса, под которым он будет сохранен.
				Например, "мой дом" или "моя работа".
			</span>
			<span class="tip_field animated zoomIn" slot="promo_tip">
				При наличии купона Вы можете получить скидку или бесплатную доставку.
			</span>
		</component>
	</main-form>
</div>
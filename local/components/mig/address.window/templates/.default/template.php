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
$this->setFrameMode(true);
$this->addExternalCss($templateFolder.'/index.css');
$this->addExternalJs($templateFolder.'/index.js');
?>
<div class="b-header-location b-ib" id="address_app">
	<div class="b-header-location__current b-ib"><?=$arResult['REGIONS']['ADDRESS']?></div>
	<button class="b-button b-header-location__change" @click="showWindow">Сменить адрес</button>

	<address-window deny="<?=($arResult['NOT_ALLOWED'] ? 1 : 0)?>"></address-window>

</div>
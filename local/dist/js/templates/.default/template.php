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
?>
<!--<script type="text/babel" src="/local/src/js/react/form_view.js"></script>-->
<h1>Формы</h1>
<div id="profilesBlock" class="block_wrap mb10 subscribers">
	<div class="col-xs-6"><h4>Данные подписчиков</h4></div>
	<div id="profileTable"></div>
</div>
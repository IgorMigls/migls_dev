<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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
$this->addExternalCss('/local/components/ul/personal/templates/.default/style.css');
//$this->addExternalJs('/local/components/ul/personal/templates/.default/script.js');
//$this->addExternalJs('/local/components/ul/order/templates/.default/order.js');
$this->addExternalJs('/local/dist/js/OrderService.js');
$this->addExternalJs($templateFolder.'/core.order.js');
?>

<div class="b-popup-check">
	<div class="order__tabs">
		<a href="#/step1" class="step" ui-sref="step1" ui-sref-active="active_step">Шаг 1</a>
		<a href="#/step2" class="step" ui-sref="step2" ui-sref-active="active_step">Шаг 2</a>
		<a href="#/step3" class="step" ui-sref="step3" ui-sref-active="active_step">Шаг 3</a>
	</div>
	<ui-view></ui-view>
</div>
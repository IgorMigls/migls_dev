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
//$this->addExternalCss('https://cdnjs.cloudflare.com/ajax/libs/element-ui/1.4.2/theme-default/index.css');
$this->addExternalCss('/local/src/css/animate.min.css');
$this->addExternalCss($templateFolder.'/bx_admin_fix.css');
$this->addExternalJs($templateFolder.'/index.js');
$this->addExternalCss($templateFolder.'/index.css');
?>
<div id="creator_app">
	<form-creator></form-creator>
</div>
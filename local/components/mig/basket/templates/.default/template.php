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
$this->addExternalCss($templateFolder.'/index.css');
$this->addExternalCss($templateFolder.'/scroll.css');
$this->addExternalJs($templateFolder.'/index.js');
$this->setFrameMode(true);

$isAuth = $USER->IsAuthorized() ? 1 : 0;
?>.

<div id="basket_app">
	<basket :shops="<?=CUtil::PhpToJSObject($_SESSION['REGIONS']['SHOP_ID'])?>" :auth="<?=$isAuth?>"></basket>
</div>
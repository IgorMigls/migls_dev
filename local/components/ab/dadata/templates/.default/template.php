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
if($arParams['INCLUDE_UI_SCRIPT'] == 'Y'){
	$this->addExternalCss($componentPath.'/ui/jquery-ui.min.css');
	$this->addExternalJs($componentPath.'/ui/jquery-ui.min.js');
}
$this->addExternalJs($componentPath.'/ui/complite.js');
?>
<?if(count($arResult['ERRORS']) > 0){
	foreach ($arResult['ERRORS'] as $error) {
		ShowError($error);
	}
}?>

<input name="" id="" value="" />
<button type="button">Найти</button>
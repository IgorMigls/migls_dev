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
$this->setFrameMode(true);
$this->addExternalCss('/local/components/ul/personal/templates/.default/style.css');

CUtil::InitJSCore('ajax');
$this->addExternalJs('https://code.jquery.com/ui/1.12.0/jquery-ui.js');
$this->addExternalCss('//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css');
$this->addExternalJs('//api-maps.yandex.ru/2.1/?lang=ru_RU');
$this->addExternalJs('/local/dist/js/personal/address/app.js');
//$this->addExternalJs('/local/dist/libs/js/map/map.init.js');
?>
<div class="b-header-location b-ib">
	<div class="b-header-location__current b-ib"><?=$arResult['REGIONS']['ADDRESS']?></div>
	<div id="render_address" data-user="<?=$USER->IsAuthorized()?>">
		<div id="fountainG">
			<div id="fountainG_1" class="fountainG"></div>
			<div id="fountainG_2" class="fountainG"></div>
			<div id="fountainG_3" class="fountainG"></div>
			<div id="fountainG_4" class="fountainG"></div>
			<div id="fountainG_5" class="fountainG"></div>
<!--			<div id="fountainG_6" class="fountainG"></div>-->
<!--			<div id="fountainG_7" class="fountainG"></div>-->
<!--			<div id="fountainG_8" class="fountainG"></div>-->
		</div>
	</div>
</div>
<div class="hide_content">
	<div id="render_no_address"></div>
	<div class="b-popup b-popup-card b-popu-card_add-card" id="change_address_popup"></div>
</div>
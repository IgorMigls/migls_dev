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
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
//dump($arParams['INPUT_ID']);
?>
<section class="top_search_section"
	data-shop="<?=intval($arParams['SHOP_CURRENT']['ID'])?>"
	data-input="<?=$arParams['INPUT_ID']?>"
	id="top_search_app"
	data-action="<?=$arParams['FORM_ACTION']?>"
>
	<div class="b-header-search__center b-ib">
		<input id="<? echo $INPUT_ID ?>"
			type="text" name="q"
			value="<?= $request->get('q') ?>"
			size="40"
			placeholder="Например, говядина для шашлыка, 3 кг"
			class="b-header-search__input" autocomplete="off"/>
		<?if(intval($arParams['SHOP_CURRENT']['ID']) > 0):?>
			<input type="hidden" name="shop" value="<?=$arParams['SHOP_CURRENT']['ID']?>" />
		<?endif;?>
	</div>
	<div class="b-header-search__right b-ib">
		<button class="b-button b-button_green" name="s" type="submit">Найти</button>
	</div>
</section>

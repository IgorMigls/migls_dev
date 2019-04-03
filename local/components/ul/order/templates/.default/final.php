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
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>
<? if ($arResult['ORDER']['ID']): ?>
	<div class="check__title check__title_r">Спасибо за заказ!</div>
<? endif; ?>
<div class="check__cont">
	<? if ($arResult['ORDER']['ID']): ?>
		<span class="check__sm">Номер вашего заказа</span><span class="check__big"><?=$arResult['ORDER']['ID']?></span>
	<? else: ?>
		<span class="check__big" style="color: red">Заказ №<?=$request->get('order')?> не найден</span>
	<? endif; ?>
	<span class="check__sm">Наши менеджеры свяжутся <br> с вами а течение 5 минут</span></div>
<div class="check__reg">
	<!--<span class="check__log b-ib">Регистрация через </span>
		<ul class="socials b-ib">
		<li class="social__item"><a href="" class="social__link fb"></a></li>
		<li class="social__item"><a href="" class="social__link vk"></a></li>
		<li class="social__item"><a href="" class="social__link tw"></a></li>
	</ul>-->
</div>

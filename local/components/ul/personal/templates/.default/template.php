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
?>
<div class="b-lk-wrapper">
	<div class="b-lk__content">
		<div class="lk__col1">
			<div class="lk__title">Личный кабинет</div>
			<div class="lk__menu">
				<a href="#/profile" ui-sref="profile" ui-sref-active="lk__link_active" class="lk__link">
					<span class="lk__item"></span>Профиль
				</a>
				<a href="#/address" ui-sref="address" ui-sref-active="lk__link_active" class="lk__link ">
					<span class="lk__item"></span>Адреса
				</a>
				<a href="#/coupons" class="lk__link" ui-sref="coupons" ui-sref-active="lk__link_active">
					<span class="lk__item"></span>Скидочные купоны
				</a>
				<a href="#/orders" class="lk__link" ui-sref="orders" ui-sref-active="lk__link_active">
					<span class="lk__item"></span>История заказов
				</a>
				<a href="/personal/favorite/" class="lk__link"><span class="lk__item"></span>Избранное</a>
<!--				<a href="lk-6.html" class="lk__link"><span class="lk__item"></span>Уведомления</a>-->
<!--				<a href="lk-7.html" class="lk__link"><span class="lk__item"></span>Подписка</a>-->
			</div>
		</div>

		<div ui-view="personalView" class="lk__col2"></div>

<!--		<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>-->

	</div>
</div>

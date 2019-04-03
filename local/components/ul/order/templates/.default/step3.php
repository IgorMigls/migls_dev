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
<div class="order_step_1 order_step_2 order_step_3">
	<div class="check__title check__title_m">Завершение</div>
	<div class="check__total-items">
		<div class="check__total check__total_m">Итого: {{sumOrder}} &#8381</div>
		<div class="check__del">
			<span>Курьерская доставка по адресу:</span>
			<span>
				{{Properties.CITY.VALUE}}, ул.{{Properties.STREET.VALUE}},
				д.{{Properties.HOUSE.VALUE}}, кв.{{Properties.APARTMENT.VALUE}}
			</span>
		</div>
		<div class="check__friends">
			<div class="check__total">Итого:  <span>{{basket.FORMAT_CNT}} на сумму {{basket.SUM}}</span><span class="check-rub">&#8381</span></div>
			<div class="check__total">Доставка курьером <span>{{deliveryPriceFormat}}</span><span class="check-rub">&#8381</span></div>
		</div>
	</div>
	<div class="order1__form">

		<form action="">
			<label class="form__label">
				<input type="text" placeholder="Промокод" class="form__input form__input_middle">
				<div class="lable__tooltip"><span class="tooltip__content animated zoomIn">Хитроумные механизмы и примитивное электричество, удивительная атмосфера стимпанка и квестов Myst, уникальные в Казани аудиовизуальные эффекты и оригинальный сюжет. ... kk-kazan@mail.ru.</span></div>
			</label>
			<label class="form__label">
				<input type="text" ng-model="commentOrder" placeholder="Комментарии к заказу" class="form__input form__input_middle">
			</label>
		</form>
	</div>

	<div ng-if="Notes.errors.length > 0" class="alert alert-danger" role="alert">
		<p ng-repeat="msg in Notes.errors">{{msg}}</p>
	</div>

	<button class="b-button b-button_back b-button_big" type="button" ng-click="prevStep()"> Назад</button>
	<button class="b-button b-button_green b-button_check b-button_big" ng-if="success" ng-click="nextStep()">Завершить</button>
</div>

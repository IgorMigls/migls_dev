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
//$APPLICATION->IncludeComponent('ul:address.list','order_form', array(), $component);
?>
<div class="order_step_1">
	<div class="check__title check__title_m">Введите ваш адрес</div>
	<div class="order1__radio" ng-show="Profiles.length > 0">
		<label class="filter__label filter__label_radio">
			<input type="radio" ng-model="adr" value="N" class="filter__checkbox filter__checkbox_radio"><i></i>
			<div class="check__wrapper b-ib"><span class="history__order2">Новый адрес</span></div>
		</label>
		<label class="filter__label filter__label_radio">
			<input type="radio" ng-model="adr" value="Y" class="filter__checkbox filter__checkbox_radio"><i></i>
			<div class="check__wrapper b-ib"><span class="history__order2">Выбрать</span></div>
		</label>
	</div>

	<div class="order1__form">
		<div ng-if="Notes.note.length > 0" class="alert alert-success" role="alert">
			<p ng-repeat="msg in Notes.note">{{msg}}</p>
		</div>

		<div ng-if="Notes.errors.length > 0" class="alert alert-danger" role="alert">
			<p ng-repeat="msg in Notes.errors">{{msg}}</p>
		</div>
		<form method="post" ng-submit="nextStep()" novalidate name="formPropProfile" autocomplete="off">
			<div class="b-ib lk__profile" ng-show="adr == 'Y'">
				<div class="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-ib custom_profile">
					<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
						<!--<select ng-options="item as item.NAME for item in Profiles track by item.ID"
							ng-model="choseAddress"
							class="b-custom-select js-custom-select2" id="SelectAddress">
						</select>-->
						<selectize config='ConfigSelect'
							options='Profiles'
							ng-model="selectAddress"
							class="b-custom-select js-custom-select2"
							id="SelectAddress">
						</selectize>
						<!--selectize="Profiles"-->
					</div>
				</div>
			</div>
			<span class="lk__form-descr">Все поля обязательны для заполнения</span>
			<label class="form__label">
				<input type="text"
					placeholder="{{Properties.CITY.NAME}}"
					ng-model="Properties.CITY.VALUE"
					class="form__input form__input_middle" ng-required="true" name="CITY" address-suggestion="" typeLocation="city">
			</label>
			<label class="form__label">
				<input type="text" placeholder="{{Properties.STREET.NAME}}" ng-model="Properties.STREET.VALUE"
					class="form__input form__input_middle" ng-required="true" name="STREET"
					address-suggestion="" typeLocation="street">
			</label>
			<div class="form__col1">
				<label class="form__label">
					<input type="text" placeholder="{{Properties.HOUSE.NAME}}" ng-model="Properties.HOUSE.VALUE"
						class="form__input form__input_short" ng-required="true" name="HOUSE">
				</label>
				<label class="form__label">
					<input type="text" placeholder="{{Properties.APARTMENT.NAME}}" ng-model="Properties.APARTMENT.VALUE"
						class="form__input form__input_short" ng-required="true" name="APARTMENT">
				</label>
			</div>
			<div class="form__col1">
				<label class="form__label">
					<input type="text" placeholder="{{Properties.FLOOR.NAME}}" ng-model="Properties.FLOOR.VALUE"
						class="form__input form__input_short" ng-required="true" name="FLOOR" mask="999">
				</label>
				<label class="form__label">
					<input type="text" placeholder="{{Properties.ZIP.NAME}}" ng-model="Properties.ZIP.VALUE"
						class="form__input form__input_short" ng-required="true" name="ZIP" mask="999999">
				</label>
			</div>
			<label class="form__label">
				<input type="text" placeholder="Название" ng-model="Properties.PROFILE_NAME" 
					class="form__input form__input_middle form__input_tooltip" ng-required="true" name="PROFILE_NAME">
				<div class="lable__tooltip">
					<span class="tooltip__content animated zoomIn">
						Хитроумные механизмы и примитивное электричество, удивительная атмосфера стимпанка и квестов
						Myst, уникальные в Казани аудиовизуальные эффекты и оригинальный сюжет. ... kk-kazan@mail.ru.
					</span>
				</div>
			</label>
			<label class="form__label">
				<button type="button" ng-click="saveAddress()"
					ng-show="formPropProfile.$valid"
					class="b-button b-button_check b-button_green b-button_big b-button_width btn_address">
					Сохранить адрес
				</button>
				<button type="button" ng-click="deleteAddress(choseAddress)"
					class="b-button b-button_check b-button_green b-button_big b-button_del b-button_width btn_address"
					ng-show="savedAddress == 1 || (adr == 'Y' && selectAddress != 0)">
					Удалить адрес
				</button>
			</label>
			<label class="form__label">
				<input type="text" ng-model="Properties.PHONE.VALUE"
					mask="+7(999)999-99-99" placeholder="+7(___)___-__-__"
					class="form__input form__input_middle form__input_tooltip" name="PHONE">
			</label>
			<? if (!$USER->IsAuthorized()): ?>
				<label class="form__label">
					<input type="text" ng-model="Properties.FIO.VALUE"
						placeholder="Ф.И.О."
						class="form__input form__input_middle form__input_tooltip" ng-required="true" name="FIO">
				</label>
				<label class="form__label">
					<input type="text" ng-model="Properties.EMAIL.VALUE" placeholder="e-mail"
						class="form__input form__input_middle form__input_tooltip" ng-required="true" name="EMAIL">
				</label>
			<? endif; ?>
			<button type="submit" class="b-button b-button_green b-button_check b-button_big b-button_width">Дальше
			</button>
		</form>
	</div>
<!--		<pre>{{Properties | json}}</pre>-->
	<div class="hide_content">
		<div class="b-popup b-popup-card b-popu-card_add-rec profile_forms_popup win_custom" id="address_saved">
			<div class="b-popup-recovery">
				<button class="b-button b-button__close-popup"></button>
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_success">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<div class="icon_success"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="accepted__content">
					<div class="lk__add-address">
						<h2>Адрес сохранен</h2>
						<!--<button type="submit" class="b-button b-button_check b-button_green b-button_big">
							Закрыть
						</button>-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="ya_maps_hidden" check-address=""></div>
</div>
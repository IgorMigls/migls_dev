<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->addExternalJs('/local/components/ul/personal/templates/.default/script.js');
?>
<div class="address_wrap">
	<div class="lk__title lk__title_reset">Ваши адреса:</div>
	<div class="lk__address-wrapper">
		<div class="lk__address" ng-repeat="profile in Profiles" ng-if="profile.ID != 0">
			<button class="b-button b-button__edit"
			        type="button"
			        ng-click="editAddress($index, $event)">
			</button>
			<button class="b-button b-button__remove" type="button" ng-click="delAddress($index)"></button>
			<div class="lk__input1">{{profile.NAME}}</div>
			<div class="lk__input2">{{profile.VALUE_FORMAT}}</div>
		</div>
	</div>
	<div class="lk__title lk__title_reset">Добавить адрес:</div>

	<div class="lk__add-address no_popup">

		<div ng-if="Notes.note.length > 0" class="alert alert-success" role="alert">
			<p ng-repeat="msg in Notes.note track by $index">{{msg}}</p>
		</div>

		<div ng-if="Notes.errors.length > 0" class="alert alert-danger" role="alert">
			<p ng-repeat="msg in Notes.errors track by $index">{{msg}}</p>
		</div>

		<form method="post" ng-submit="saveAddress()" novalidate name="formPropProfile" id="formPropProfile" autocomplete="off">
			<span class="lk__form-descr"><i class="star">*</i> &ndash; обязательны для заполнения</span>
			<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.CITY.NAME}}</span>
				<input type="text"
				       placeholder="{{Properties.CITY.NAME}}"
				       ng-model="Properties.CITY.VALUE" autocomplete="off"
				       class="form__input form__input_middle" ng-required="true" name="CITY"
						address-sugestion="">
			</label>
			<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.STREET.NAME}}</span>
				<input type="text" placeholder="{{Properties.STREET.NAME}}"
				       ng-model="Properties.STREET.VALUE" ng-required="true" autocomplete="off"
				       class="form__input form__input_middle" name="STREET" address-sugestion="">
			</label>
			<div class="form__col1">
				<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.HOUSE.NAME}}</span>
					<input type="text" placeholder="{{Properties.HOUSE.NAME}}"
					       ng-model="Properties.HOUSE.VALUE" ng-required="true"
					       class="form__input form__input_short" name="HOUSE" />
				</label>
				<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.APARTMENT.NAME}}</span>
					<input type="text" placeholder="{{Properties.APARTMENT.NAME}}"
					       ng-model="Properties.APARTMENT.VALUE" ng-required="true"
					       class="form__input form__input_short" name="APARTMENT">
				</label>
			</div>
			<div class="form__col1">
				<label class="form__label"> <span class="span__label span__label_short">{{Properties.FLOOR.NAME}}</span>
					<input type="text" placeholder="{{Properties.FLOOR.NAME}}"
					       ng-model="Properties.FLOOR.VALUE"
					       class="form__input form__input_short" name="FLOOR" mask="999">
				</label>
				<label class="form__label"> <span class="span__label span__label_short">Поъдезд</span>
					<input type="text" placeholder="Поъдезд"
					       ng-model="Properties.ZIP.VALUE"
					       class="form__input form__input_short" name="ZIP" mask="999">
				</label>
			</div>
			<label class="form__label"> <span class="span__label">
					<i class="star">*</i>Название
					<div class="lable__tooltip">
						<span class="tooltip__content animated zoomIn">
							Название адреса, под которым он будет сохранен. Например, "мой дом" или "моя работа".
						</span>
					</div>
				</span>
				<input type="text" placeholder='Например, "мой дом" или "моя работа"'
				       ng-model="Properties.PROFILE_NAME" ng-required="true" name="PROFILE_NAME"
				       class="form__input form__input_middle form__input_tooltip">
			</label>
			<button type="submit" class="b-button b-button_check b-button_green b-button_big">Сохранить адрес</button>
		</form>
	</div>
	<!--	<pre>{{Profiles | json}}</pre>-->

	<div class="hide_content">
		<div class="b-popup b-popup-card b-popu-card_add-card" id="change_address_personal">
			<div class="b-popup-recovery">
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_reverse">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<div class="icon-g"></div>
							</div>
							<div class="recovery__title">Изменить адрес</div>
						</div>
					</div>
				</div>
				<div class="accepted__content">
					<div class="lk__add-address popup_addres">
						<form method="post" ng-submit="saveAddress()" novalidate name="formPropProfile">
							<span class="lk__form-descr"><i class="star">*</i> &ndash; обязательны для заполнения</span>
							<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.CITY.NAME}}</span>
								<input type="text"
								       placeholder="{{Properties.CITY.NAME}}"
								       ng-model="Properties.CITY.VALUE"
								       class="form__input form__input_middle" ng-required="true" name="CITY"
										address-sugestion="">
							</label>
							<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.STREET.NAME}}</span>
								<input type="text" placeholder="{{Properties.STREET.NAME}}"
								       ng-model="Properties.STREET.VALUE" ng-required="true"
								       class="form__input form__input_middle" name="STREET"
										address-sugestion="">
							</label>
							<div class="form__col1">
								<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.HOUSE.NAME}}</span>
									<input type="text" placeholder="{{Properties.HOUSE.NAME}}"
									       ng-model="Properties.HOUSE.VALUE" ng-required="true"
									       class="form__input form__input_short" name="HOUSE">
								</label>
								<label class="form__label"> <span class="span__label"><i class="star">*</i>{{Properties.APARTMENT.NAME}}</span>
									<input type="text" placeholder="{{Properties.APARTMENT.NAME}}"
									       ng-model="Properties.APARTMENT.VALUE" ng-required="true"
									       class="form__input form__input_short" name="APARTMENT">
								</label>
							</div>
							<div class="form__col1">
								<label class="form__label"> <span class="span__label span__label_short">{{Properties.FLOOR.NAME}}</span>
									<input type="text" placeholder="{{Properties.FLOOR.NAME}}"
									       ng-model="Properties.FLOOR.VALUE"
									       class="form__input form__input_short" name="FLOOR">
								</label>
								<label class="form__label"> <span class="span__label span__label_short">{{Properties.ZIP.NAME}}</span>
									<input type="text" placeholder="{{Properties.ZIP.NAME}}"
									       ng-model="Properties.ZIP.VALUE"
									       class="form__input form__input_short" name="ZIP">
								</label>
							</div>
							<label class="form__label">
								<span class="span__label">
									<i class="star">*</i>Название
									<div class="lable__tooltip">
										<span class="tooltip__content animated zoomIn">
											Название адреса, под которым он будет сохранен. Например, "мой дом" или "моя работа".
										</span>
									</div>
								</span>
								<input type="text" placeholder='Например, "мой дом" или "моя работа"'
								       ng-model="Properties.PROFILE_NAME" ng-required="true" name="PROFILE_NAME"
								       class="form__input form__input_middle form__input_tooltip">
							</label>
							<button type="submit" class="b-button b-button_check b-button_green b-button_big">Сохранить адрес</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="ul_map_personal" style="display: none"></div>

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
$this->setFrameMode(true);?>
<div class="b-header_bot b-ib b-nav__item_subitems">
	<div class="b-nav__link">Другое
		<div class="b-help" id="help_form_hover">
			<div class="ph-me">
				<span>Перезвоните мне</span>
				<a href="javascript:">Задать вопрос</a>
			</div>
			<div class="order1__form">
				<form action="">
					<div class="b-ib lk__profile"><span class="span_help">Город</span>
						<div class="b-header-popup__filter b-header-popup__filter_catalog b-header-popup__filter_lk b-header-popup__filter_lk_280 b-ib">
							<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
								<select class="b-custom-select js-custom-select2">
									<option selected="selected" value="Самара">Самара</option>
									<option value="Деревня кабачки">Деревня кабачки</option>
									<option value="Черпеповец">Черпеповец</option>
								</select>
							</div>
						</div>
					</div>
					<label class="form__label">
						<input type="text" placeholder="Ваш номер" class="form__input form__input_middle form__input_help"><span class="span_help">Телефон</span>
					</label>
					<label class="form__label">
						<input type="text" placeholder="Введите имя" class="form__input form__input_middle form__input_help"><span class="span_help">Имя</span>
					</label>
					<div class="help-wrapper">
						<div class="help__left b-ib"><span>Причина</span></div>
						<div class="help__right b-ib">
							<div class="order1__radio">
								<label class="filter__label filter__label_radio filter__label_radio-yellow">
									<input checked type="radio" name="adr" class="filter__checkbox filter__checkbox_radio"><i></i>
									<div class="check__wrapper b-ib"><span class="history__order2">Хочу сделать заказ</span></div>
								</label>
								<label class="filter__label filter__label_radio filter__label_radio-yellow">
									<input checked type="radio" name="adr" class="filter__checkbox filter__checkbox_radio"><i></i>
									<div class="check__wrapper b-ib"><span class="history__order2">Не пришел заказ</span></div>
								</label>
								<label class="filter__label filter__label_radio filter__label_radio-yellow">
									<input type="radio" name="adr" class="filter__checkbox filter__checkbox_radio"><i></i>
									<div class="check__wrapper b-ib"><span class="history__order2">Жалоба</span></div>
								</label>
							</div>
						</div>
					</div>
				</form>
				<button class="b-button b-button_green b-button_check b-button_big b-button_width"> Отправить заявку</button><span class="help__small">Оператор перезванивает в рабочее время в течение 2 часов</span>
			</div>
		</div>
	</div>
</div>

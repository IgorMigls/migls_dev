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
$this->setFrameMode(true);
//unset($_SESSION['REGIONS']);
if (count($_SESSION['REGIONS']) == 0 || !isset($_SESSION['REGIONS'])):
	$this->addExternalCss('/local/components/ul/personal/templates/.default/style.css');
	$this->addExternalCss('/local/components/ul/address.change/templates/.default/style.css');
	$this->addExternalJs('https://code.jquery.com/ui/1.12.0/jquery-ui.js');
	$this->addExternalCss('//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css');
	$this->addExternalJs('/local/dist/js/NoAddressWin.min.js');
	$this->addExternalJs('/local/dist/libs/js/map/map.init.js');
	$this->addExternalJs('/local/dist/libs/js/map/map.component.js');

	?>
	<div id="popupHello" class="b-popup b-popup-hello mfp-hide">
		<div class="b-popup-hello__wrapper b-ib-wrapper">
			<div class="b-popup-hello__left b-ib">
				<div class="b-popup-hello__items b-ib-wrapper">
					<div class="b-popup-hello__item b-ib" style="height: 121px"><img src="/local/dist/images/hello-1.png" /><span>Магазин</span></div>
					<div class="b-popup-hello__item b-ib" style="padding-top: 7px"><img src="/local/dist/images/hello-2.png" /><span>Наши<br> курьеры</span></div>
					<div class="b-popup-hello__item b-ib"><img src="/local/dist/images/hello-3.png" /><span>Заказ у<br> вас дома</span></div>
				</div>
				<ul class="b-popup-hello__list">
					<!--<li>Колбаски Рублевский МК Аджарские с травами для жарки, 0,3-0,5кг</li>
					<li>Соус Кинто Сацебели домашний 300г</li>
					<li>Цыпленок Фермерский урожай Тапака 0,4-0,7кг охлажденный</li>-->
				</ul>
			</div>
			<div class="b-popup-hello__right b-ib">
				<div class="b-popup-hello__title">
					Получите Ваши продукты<br>
					из местных магазинов
				</div>
				<div class="b-popup-hello-form">
					<div class="b-popup-hello-form__title">Введите Ваш адрес или выберите зону доставки</div>
					<span id="errors_address">Адрес не найден</span>

					<div class="b-popup-hello-form__item ui-widget">
						<input type="text" placeholder="Например: г. Самара, ул. Дыбенко, д. 30"
							value="" id="search_address_start" class="b-form-control" />
						<button type="button" class="b-button b-button_green" onclick="UL.Maps.searchAddress('#search_address_start')">
							Найти
						</button>
					</div>
					<div class="b-popup-hello-form__note">
						Информация необходима для отображения доступных магазинов
						<!--Пропустить ввод и
						<a href="javascript:" onclick="UL.Maps.setTestAddress()" class="js-close-popup">посмотреть
							сервис по пробному индексу</a>-->
					</div>
				</div>
			</div>

			<div id="ul_start_map"></div>
		</div>
	</div>
	<div class="hide_content">
		<div id="render_no_address"></div>
	</div>
	<script>
		$(function () {
			$.magnificPopup.open({
				items: {
					src: '#popupHello',
					type: 'inline'
				},
				midClick: false,
				closeOnBgClick: false,
				closeBtnInside: false,
				showCloseBtn: false,
				modal: true,
				key: 'hello'
			});
//			$('#search_address_start').
		});
	</script>
<? endif; ?>
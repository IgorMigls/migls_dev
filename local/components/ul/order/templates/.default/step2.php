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
?>
<div class="order-wrapper order-wrapper_reset">

	<div class="col__700 animated fadeInLeft" order-time-tab="">
<!--		<pre>{{currentShop | json}}</pre>-->
		<div class="col__700__top">
			<div class="order2__form">
				<div class="order__time-wrapper">
					<div class="interval__img b-ib"><a href=""><img src="{{currentShop.PICTURE.src}}" alt=""></a></div>
					<div class="descr__img b-ib"><span>{{currentShop.NAME}}</span><span></span></div>
				</div>
			</div>
		</div>
		<div class="col__700__bottom">
			<div class="col__700__tabs">
				<div class="jsTab" data-index="{{$index}}" ng-repeat="days in basket.DAYS_LIST">
					<span ng-if="$index == 0">Сегодня</span>
					<span ng-if="$index == 1">Завтра</span>
					<span ng-if="$index > 1">{{days.DAY}}</span>
					<span>{{days.NUM}} {{days.MONTH}}</span>
				</div>
			</div>
			<div class="col__700__cont">
				<div class="jsCont content_{{$index}}" ng-repeat="days in basket.DAYS_LIST">
					<table class="day__table">
						<!-- class="not-ev" не доступно -->
						<tr chose-time="" ng-repeat="time in DeliveryRaw.DATA.TIMES" class="set_times" ng-click="setTime(time, days)">
							<td>{{time}}</td>
							<td>{{DeliveryRaw.DATA.PRICE}} ₽</td>
							<td><span class="green-t" >выбрать</span></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<button class="b-button b-button_back b-button_big"> Показать еще</button>
	</div>



	<div class="order1__form order2__form">
		<div class="check__title check__title_m">Выберите время доставки</div>
		<form action="">
			<div class="order__time-wrapper" ng-repeat="shop in basket.ITEMS track by shop.SHOP_CODE">
				<div class="interval__img b-ib">
					<a href="javascript:" ng-click="setCurrentShop(shop.SHOP_CODE)">
						<img src="{{shop.PICTURE.src}}" height="auto" width="90" />
					</a>
				</div>
				<div class="descr__img b-ib">
					<span>{{shop.NAME}}</span>
					<span>{{shop.selected_day}}<br />{{shop.selected_time}}</span>
					<button type="button" class="b-button" ng-click="setCurrentShop(shop.SHOP_CODE)">
						выбрать время
					</button>
				</div>
				<span class="history__order5" ng-if="shop.INFO != ''">
					{{shop.INFO}}
				</span>
			</div>

			<div class="order1__radio"></div>
			<label class="filter__label filter__label_ok">
				<input type="checkbox" ng-model="Delivery.useMyEmail" value="Y" class="filter__checkbox filter__checkbox_radio"><i></i>
				<div class="check__wrapper b-ib">
					<span class="history__order2 history__order3">Разрешаю использовать мои контактные данные для отправки электронных писем</span>
				</div>
			</label>
			<button type="button" ng-click="prevStep()" class="b-button b-button_back b-button_big"> Назад</button>
			<button type="button" ng-click="nextStep()" class="b-button b-button_green b-button_check b-button_big b-button_width"> Дальше</button>
		</form>
<!--		<pre>{{basket | json}}</pre>-->
	</div>
</div>
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
$this->setFrameMode(true);
?>
<div class="b-custom-scroll js-custom-scroll" ng-controller="ShopListCtrl">
	<div class="b-header-popup__top b-ib-wrapper">
			<!--<div class="b-header-popup__top-left b-ib">
				<div class="b-header-popup__filter b-ib-wrapper">
					<div class="b-header-popup__filter-left b-ib">Сумма заказа</div>
					<div class="b-header-popup__filter-select b-ib">
						<select class="b-custom-select js-custom-select">
							<option selected="selected" value="5000">до 5000 руб.</option>
							<option value="10000">до 10000 руб.</option>
							<option value="15000">до 15000 руб.</option>
						</select>
					</div>
				</div>
			</div>-->
			<div class="b-header-popup__top-right b-ib">
				<div class="b-header-search b-ib">
					<form>
						<div class="b-header-search__left b-ib">Поиск</div>
						<div class="b-header-search__center b-ib">
							<input ng-model="searchShop.NAME" type="text" placeholder="Введите название магазина" class="b-header-search__input" />
						</div>
						<div class="b-header-search__right b-ib">
							<button class="b-button b-button_green b-button_dark_green">Найти</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<div class="b-header-popup__shops">
		<div class="b-header-popup__shop b-ib" ng-repeat="arItem in items | filter:searchShop.NAME">
			<a href="{{arItem.DETAIL_PAGE_URL}}">
				<img ng-src="{{arItem.IMG.src}}" alt="{{arItem.NAME}}" />
			</a>
		</div>
	</div>
</div>
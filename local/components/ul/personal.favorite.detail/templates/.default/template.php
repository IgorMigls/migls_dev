<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->addExternalCss('/local/components/ul/personal.favorite/templates/.default/style.css');
$this->addExternalJs('/local/components/ul/personal.favorite/templates/.default/script.js');
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

?>
<div class="b-lk-wrapper">
	<div class="b-lk__content">
		<div class="lk__col1">
			<div class="lk__title">Личный кабинет</div>
			<div class="lk__menu">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					".default",
					Array(   
						"AREA_FILE_SHOW"      => "sect",     // Показывать включаемую область    
						"AREA_FILE_SUFFIX"    => "personal_menu",      // Суффикс имени файла включаемой области    
						"EDIT_TEMPLATE"       => "php",         // Шаблон области по умолчанию 
					)
				);?>
			</div>
		</div>

		<div class="lk__col2" favorite-list="" detail="<?=$request->get('id')?>">
			<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>

			<div class="lk__title lk__title_reset" ng-init="listId = <?=$arParams['ID']?>">
				<a href="/personal/favorite/">Избранное</a><span class="last_fav_item_name">/ {{List.Items[0]['NAME']}}</span>
			</div>

			<div class="lk__new-list">
				<div class="lk__custom-lists">
					<ul>
						<li class="lk__lists__item" ng-repeat="itemList in List.Items">
							<span class="custom-num">{{itemList.CNT_PRODUCTS}}</span>
							<a  ng-if="!editListName" href="javascript:" class="lk__lists__link">{{itemList.NAME}}</a>
							<input type="text" class="form__input form__input_middle form__input_m"
							       ng-if="editListName" ng-model="editListName" id="edit_cur_list"/>
							<button ng-if="editListName" ng-click="saveList(editListName)" type="button" class="b-button b-button_green b-button_big">
								Сохранить
							</button>
							<span class="edit_item" ng-click="deleteList(itemList.ID)">Удалить</span>
							<span class="edit_item" ng-click="editList()" ng-if="!editListName">Переименовать</span>
							<span class="edit_item" ng-click="cancelEdit()" ng-if="editListName">Отменить</span>
						</li>
					</ul>
				</div>
				<div class="lk__select">
					<a href="javascript:" class="lk__select__link" ng-click="checkAllProducts()"
					   ng-class="{'active_cheked':checkedAll}">Выбрать все</a>
					<a href="javascript:" ng-if="listId" class="lk__select__link" ng-click="addToList()">Добавить в список</a>
					<a href="javascript:" class="lk__select__link" ng-click="delete()">Удалить из избранного</a>
				</div>
				<div class="lk__filter">
					<div class="b-filter__result b-filter__result_reset">
						<div class="b-header-popup__top-left b-header-popup_catalog-left b-ib">
							<div class="b-catalog__filter-left b-ib">Упорядочить</div>
							<div class="b-header-popup__filter b-header-popup__filter_catalog b-ib">
								<div class="b-header-popup__filter-select b-header-popup__filter-select_catalog b-ib">
									<select class="b-custom-select js-custom-select2">
										<option selected="selected" value="Стандартно">Стандартно</option>
										<option value="По дате">По дате</option>
										<option value="По цене">По цене</option>
									</select>
								</div>
							</div>
						</div>
						<div class="lk__total-sum b-ib">
							<button class="b-button b-button_green" ng-click="addAllToBasket()">Положить все в корзину </button>
							<div class="total-sum__items b-ib">{{Product.CNT_FORMAT}} на сумму <span>{{Product.SUM_FORMAT}}&#8381</span></div>
						</div>
					</div>
				</div>
				<div class="lk__fav-items">
					<div class="b_item_shop_favorite" ng-repeat="shopItem in Shops">
						<div class="shop_favorite_img"><img ng-src="{{shopItem.SHOP_PICTURE.src}}" /></div>

						<div class="fav-item item_{{$index}}" ng-repeat="Item in Product.ITEMS" id="item_{{Item.ID}}" ng-if="Item.SKU.SHOP_ID == shopItem.SHOP_ID">
							<hr class="separ">
							<div class="b-products-slider__item" ng-class="{'no_active': !Item.SKU.IN_THIS_SHOP}">
								<div class="b-product-preview b-product-preview_border b-product-preview_border-cart b-ib-wrapper">
									<div class="b-product__count b-ib">
										<span class="b-count__in" ng-if="Item.BASKET_ID">{{Item.BASKET_QUANTITY}}шт. в корзине</span>
									</div>
									<div class="b-product__star">
										<a href="javascript:" ng-click="starProduct($index)" class="prod__star"></a>
									</div>
									<div class="cart__col-1 b-ib">
										<div class="b-product-preview__pic b-ib height-auto">
											<a href="#/catalog/{{Item.PRODUCT_ID}}">
												<img src="{{Item.PICTURE.src}}">
											</a>
										</div>
									</div>
									<div class="cart__col-2 b-ib">
										<div class="b-product-preview__name b-ib height-auto">
											<label>
												<input type="checkbox" class="fav__checkbox" ng-model="Item.CHECKED" />
												{{Item.PRODUCT_NAME}}
											</label>
											<!--										<a href="#/catalog/{{Item.PRODUCT_ID}}">{{Item.PRODUCT_NAME}}</a>-->
										</div>
										<div class="b-product-preview__buy b-ib">
											<form class="index_products_basket" basket-add="">
												<div class="b-product-preview__price b-ib" ng-init="quantity = Item.BASKET_QUANTITY">
													{{Item.SKU.PRICE_FORMAT}} <span class="b-rouble">&#8381;</span>
												</div>
												<div class="b-product-preview__count b-product-preview__count_cart b-product-preview__count_short b-ib" ng-if="Item.SKU.IN_THIS_SHOP">
													<input type="text" value="1" class="quantity_input b-product-preview__input" ng-model="quantity" >
													<button class="b-button b-button_plus" ng-click="changeQuantity('+')">+</button>
													<button class="b-button b-button_minus" ng-click="changeQuantity('-')">–</button>
												</div>
												<div class="b-product-preview__count b-product-preview__count_cart b-product-preview__count_short b-ib" ng-if="!Item.SKU.IN_THIS_SHOP">
													<input type="text" disabled class="quantity_input b-product-preview__input" >
													<button class="b-button b-button_plus" disabled>+</button>
													<button class="b-button b-button_minus" disabled>–</button>
												</div>
												<div class="b-ib">
													<button type="button" ng-click="addBasket(Item.SKU.ID)" ng-if="Item.SKU.IN_THIS_SHOP"
														class="b-button b-button_green">
														В корзину
													</button>
													<button type="button" ng-if="!Item.SKU.IN_THIS_SHOP"
														class="b-button b-button_green">
														В корзину
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

<!--			<pre>{{List.Items | json}}</pre>-->

			<div class="hide_content">
				<div id="show_error">
					<div class="err_win">
						<div class="header_win"><div class="err_icon"></div></div>
						<div class="win_content">
							<h2>Ошибка</h2>
							<p class="error_msg">{{Error.msg}}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
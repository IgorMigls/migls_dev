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
$this->setFrameMode(true);
?>
<div class="b-header-cart b-ib" basket-small="" auth="<?=($USER->IsAuthorized() ? 1 : 0)?>">
	<span ng-if="basketItems.ITEMS">
		В корзине <a href="javascript:" ng-click="showBasket()">{{basketItems.FORMAT_CNT}}, {{basketItems.SUM}} руб.</a>
	</span>
	<span ng-if="!basketItems.ITEMS">Корзина пуста</span>
	<div id="basket_add_msg">
		<p>Товар {{current.NAME}} добавлен в корзину</p>
	</div>
	<div class="basket_popup_hide">
		<div id="show_cart">
			<div id="replacement">
				<div class="b-popup b-popup-card b-popu-card_cart b-popu-card_cart_ch animated noactive">
					<div class="b-popup-cart">
						<div class="b-popup-cart__c-wrapper">
							<div class="b-popup-cart__c-wrapper__head"><span>Выберите замену</span>
								<button class="b-button check__back" ng-click="closeReplaceWindow()">Не заменять</button>
							</div>
							<div class="b-popup-cart__search">
								<input type="text" ng-model="search" ng-change="searchReplace()" placeholder="Поиск" class="cart__input">
							</div>
							<div class="b-popup-cart__content-wrapper b-custom-scroll js-custom-scroll">
								<div class="b-popup-cart__content">
									<div class="cssload-container" ng-if="ReplacementItems.length == 0 && noSearchItems === false">
										<div class="cssload-loading"><i></i><i></i></div>
									</div>
									<div class="" ng-if=" ReplacementItems == null"> - Товары не найдены - </div>
									<div class="b-products-slider__item replace_item" ng-repeat="item in ReplacementItems">
										<div class="b-product-preview b-ib-wrapper b-product-preview_ashan">
											<div class="b-product-preview__pic b-ib">
												<a href="#/catalog/{{item.CML2_LINK}}">
													<img src="{{item.IMG.src}}" alt="">
												</a>
											</div>
											<div class="b-product-preview__name b-ib">
												<a href="#/catalog/{{item.CML2_LINK}}">{{item.PRODUCT_NAME}}</a>
											</div>
											<div class="b-product-preview__price b-ib">
												{{item.PRICE.FORMAT_VALUE}}
												<span class="b-rouble">&#8381;</span>
											</div>
											<div class="b-product-preview__buy b-ib">
												<form>
													<div class="b-product-preview__incart b-ib">
														<button ng-click="addReplacementItem(item)"
															type="button" class="b-button b-button_green">В корзину</button>
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
			</div>

			<? $APPLICATION->IncludeComponent('ul:shop.time.basket', '', array(), false); ?>

			<div class="b-popup b-popup-card b-popu-card_cart" id="cart_window">
				<div class="b-popup-cart">
<!--					<button class="b-button b-button__close-popup mfp-close"></button>-->
					<div class="b-popup-cart__content-wrapper basket_scroll" scroll-pane scroll-config="paneConfig"
					     id="basketScrollPane" scroll-timeout="100">
						<div ng-repeat="Shop in basketItems.ITEMS" class="{{Shop.NO_BUY_IN_SHOP ? 'no_buy_shop' : ''}}">

							<div class="b-popup-cart__head" ng-if="basketItems.ITEMS">
								<div class="b-products-block-top b-ib bg_reverse">
									<div class="cart__img-wrapper">
										<div class="cart__img b-ib"><img src="{{Shop.PICTURE.src}}" alt="" class="b-icon"></div>
										<div class="cart__prod-title b-ib">
											<div class="search__title">
												{{Shop.NAME}}
												<span ng-show="!Shop.NO_BUY_IN_SHOP" class="cart__title-item">, {{Shop.COUNT_IN_SHOP_FORMAT}} на сумму {{Shop.SUM_IN_SHOP_FORMAT}}</span>
												<span ng-show="!Shop.NO_BUY_IN_SHOP" class="check-rub-cart">&#8381</span>
											</div>
											<div class="cart__deliv" ng-show="!Shop.NO_SHOW_TIMES">
												<div class="cart__cl b-ib">
													<span class="cart__cl-1" ng-show="Shop.NEAR_DELIVERY">Ближайшая доставка: </span>
													<span class="cart__cl-1" ng-show="Shop.NEAR_DELIVERY">{{Shop.NEAR_DELIVERY}}</span>
												</div>
												<div class="cart__cl-50 b-ib b-products-block-top__right">
													<a href="javascript:" ng-click="showIntervals(Shop.SHOP_ID)"
															class="get_shop_interval b-button b-button_show b-button_show_small">Все интервалы</a>
												</div>
											</div>

										</div>
									</div>
								</div>
								<div class="not_how_times" ng-if="Shop.NO_SHOW_TIMES">
									<span>По вашему адресу доставка из этого магазина недоступна</span>
								</div>

								<div class="not_how_times" ng-if="Shop.SUM_IN_SHOP < 1000" style="font-size: 90%">
									<span>Мин. сумма заказа должна быть 1000р</span>
								</div>

								<div class="not_how_times free_delivery_msg" ng-if="Shop.FREE_DELIVERY">
									<span>Бесплатная доставка</span>
								</div>
							</div>

							<div class="b-popup-cart__content">
								<div class="cart__item" ng-repeat="basket in Shop.BASKET track by basket.ID">
									<div class="no_buy_block" ng-if="basket.NO_BUY_IN_SHOP"></div>
									<div class="b-products-slider__item">
										<div id="product{{basket.ID}}"
										     class="b-product-preview b-product-preview_border b-product-preview_border-cart b-ib-wrapper">
											<div class="b-product__count b-ib">
												<span class="b-count__in">
			                                       <button id="comm_product83" class="b-button" ng-click="addReplacement(basket)">
				                                      <i class="fa fa-refresh" aria-hidden="true"></i>
				                                       Добавить замены
			                                       </button>
												</span>
												<span class="b-count__in b-cont__in_cart">
			                                       <button id="comm_product{{basket.ID}}" data-toggle-element="#product{{basket.ID}}" data-toggle-class="open"
			                                               class="b-button js-toggle-class">
				                                       Добавить комментарий
			                                       </button>
												</span>
											</div>
											<div class="cart__col-1 b-ib">
												<div class="b-product-preview__pic b-ib">
													<a href="#{{basket.DETAIL_PAGE}}">
														<img ng-src="{{basket.PRODUCT_ITEM.IMG.src}}" alt="">
													</a>
												</div>
											</div>
											<div class="cart__col-2 b-ib">
												<div class="b-product-preview__name b-ib">
													<a href="#{{basket.DETAIL_PAGE}}">{{basket.PRODUCT_ITEM.PRODUCT_NAME}}</a>
												</div>
												<div class="b-product-preview__buy b-ib">
													<form>
														<div class="b-product-preview__price b-ib">
															{{basket.PRICE_FORMAT}} <span class="b-rouble">&#8381;</span>
														</div>
														<div class="b-product-preview__count b-product-preview__count_cart b-ib" basket-add="" basket="{{basket.PRODUCT_ITEM.PRODUCT_IB_ID}}">
															<input type="text" ng-init="quantity = basket.QUANTITY"
															       ng-model="quantity" class="b-product-preview__input">
															<button type="button" class="b-button b-button_plus"
															        ng-click="changeQuantity('+', basket)">+</button>
															<button type="button" class="b-button b-button_minus"
															        ng-click="changeQuantity('-', basket)">–</button>
														</div>
														<div class="b-product-del b-ib">
															<button type="button" class="b-button b-button__del {{basket.NO_BUY_IN_SHOP ? 'is_active' : false}}"
															        ng-click="delItem(basket)">Удалить</button>
														</div>
														<div class="b-product-textarea">
														<textarea name="comment" ng-model="basket.COMMENT" ng-init="basket.COMMENT = basket.COMMENTARY"
														          class="form__textarea form__textarea_cart"></textarea>
														</div>
														<div class="b-ib cart-com-buttons">
															<button type="button" ng-click="addCommentItem(basket)"
															        class="b-button b-button_green b-button_small">Сохранить</button>
															<button type="button"
															        class="b-button b-button_green b-button_small b-button_grey"
															        ng-click="disableComment('#product'+ basket.ID)">Отменить</button>
														</div>
													</form>
													<hr class="hr_replace" ng-if="basket.replace" />
													<span style="display:none" ng-init="isReplasedUse = 1"></span>
													<span class="cart__comments_span" ng-if="basket.replace">Замены:</span>
													<div class="replace_basket_item" ng-repeat="rep in basket.replace track by rep.ID">
														<span  class="cart__comments_span text basket_name_replace">{{rep.NAME}}</span>
														<span class="basket_del_replace cart__comments_span" ng-click="deleteReplace(basket, rep.ID)">
															<i class="fa fa-times-circle" aria-hidden="true"></i>
														</span>
													</div>
												</div>
												<div class="cart__comments" ng-show="basket.COMMENTARY" id="comment_show{{basket.ID}}">
													<span class="cart__comments_span">
														Комментарий: <i class="fa fa-pencil js-toggle-class"
																		aria-hidden="true"
																		data-toggle-element="#product{{basket.ID}}"
																		data-toggle-class="open"></i>
													</span>
													<span class="cart__comments_span text">{{basket.COMMENTARY}}</span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div ng-if="!basketItems.ITEMS">Корзина пуста</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="b-popup b-popup-card b-popu-card_check" id="cart_check_all">
				<div class="b-popup-check">
					<button class="b-button b-button__close-popup mfp-close" type="button"></button>
					<button class="b-button check__back mfp-close">Вернуться к покупкам</button>
					<div class="check__title">Ваш заказ</div>
					<div class="check__total-items">
						<div class="check__total">
							Итого: <span>{{basketItems.FORMAT_CNT}} на сумму {{basketItems.SUM}}</span>
							<span class="check-rub">&#8381</span>
						</div>
					</div>

					<div class="no_buy_order" ng-if="!basketItems.BUY_ORDER">
						Сумма покупок с каждого магазина должна быть не менее 1000 р
					</div>

					<a id="addToOrder" style="text-decoration: none; color: #fff" href="javascript:" ng-show="basketItems.BUY_ORDER"
					   class="b-button b-button_green b-button_check b-button_big"> Оформить заказ</a>
				</div>
			</div>

		</div>
	</div>
</div>
<?/*<div class="add_cart_check">
	<div class="b-popup b-popup-card b-popu-card_check" id="add_cart_check" ng-controller="BasketCtrl">
		<div class="b-popup-check">
			<button class="b-button b-button__close-popup mfp-close" type="button"></button>
			<button class="b-button check__back mfp-close">Вернуться к покупкам</button>
			<div class="check__title">Ваш заказ</div>
			<div class="check__total-items">
				<div class="check__total">
					Итого: <span>{{basketItems.FORMAT_CNT}} на сумму {{basketItems.SUM}}</span>
					<span class="check-rub">&#8381</span>
				</div>
			</div>
			<button class="b-button b-button_green b-button_check b-button_big"> Оформить заказ</button>
		</div>
	</div>
</div>*/?>

	<div class="popup_replace_2_overlay">
		<div class="popup_replace_2">
			<div class="replace_2_header">
				<div class="container"><div class="icon_head_replace2"></div></div>
			</div>
			<div class="replace_2_body">
				<div class="container">
					<h3>В магазине нет нужного товара или он плохого качества?</h3>
					<p> В этом случае закупщик сам выберет сопоставимую замену, если такая будет.
						Если у Вас имеются какие-то особые предпочтения на все или часть товаров,
						то мы рекомендуем лично настроить или запретить замены.
						Для этого в корзине наведите мышь на товар и нажмите "Добавить замены".
						Это не займет много времени и позволит нам оправдать Ваши ожидания!</p>
					<div class="btn_group">
						<a href="javascript:" id="continue_products" class="b-button replace_btn_ basket">Продолжить покупки</a>
						<a href="javascript:" id="confirm_orders" class="b-button replace_btn_ confirm_orders">Оформить заказ</a>
					</div>
				</div>
			</div>
		</div>
	</div>

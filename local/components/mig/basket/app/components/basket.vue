<template>
	<div class="b-header-cart b-ib">
		<span v-if="!loader">
			<span v-if="items">
				В корзине&nbsp;
				<a href="javascript:" @click.prevent="openBasketWindow">
					{{countFormat}}, {{sumFormat}}&nbsp;<i class="fa fa-rouble"></i>
				</a>
			</span>
			<span v-else>Корзина пуста</span>
		</span>
		<span v-else>Загрузка...</span>
		
		<modal :show="showBasketWindow" @close-modal="closeBasket" class-content="wrap_basket_modal">
			<transition name="custom-classes-transition" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
				
				<delivery-times v-show="showTimes"></delivery-times>
			
			</transition>
			
			<replace />
			
			<transition name="custom-classes-transition"
					enter-active-class="animated zoomIn"
					leave-active-class="animated zoomOut">
				<div v-show="mainWindowOpen">
					<scroll-bar class="basket_items_wrap" :settings="scrollSettings">
						<basket-items v-for="(shop, code) in items" :key="code" :shop="shop"></basket-items>
					</scroll-bar>
				</div>
			</transition>
			
			<transition name="custom-classes-transition"
					enter-active-class="animated zoomIn"
					leave-active-class="animated zoomOut">
				<div class="b-popup-card b-popu-card_check" id="cart_check_all" v-show="mainWindowOpen">
					<div class="b-popup-check">
						<button class="b-button b-button__close-popup mfp-close" type="button" @click="closeBasket"></button>
						<button class="b-button check__back mfp-close" @click="closeBasket">Вернуться к покупкам</button>
						<div class="check__title">Ваш заказ</div>
						<div class="check__total-items">
							<div class="check__total">
								Итого: <span>{{countFormat}} на сумму {{sumFormat}}</span>
								<span class="check-rub">&#8381;</span>
							</div>
						</div>
						
						<div class="no_buy_order" v-if="!orderAllowed">
							Сумма покупок с каждого магазина должна быть не менее 1000 р
						</div>
						
						<a href="javascript:" @click="orderApply"
								:class="['b-button b-button_green b-button_check', {'disabled': !orderAllowed}]">
							Оформить заказ
						</a>
					</div>
				</div>
			</transition>
			
			<transition name="custom-classes-transition"
					enter-active-class="animated fadeIn"
					leave-active-class="animated fadeOut">
				<div class="accept_order" v-if="!mainWindowOpen">
					<div class="accept_order__head">
						<div class="accept_order__head__icon">
							<img src="/local/dist/images/icon_replace_w.png" />
						</div>
					</div>
					<div class="accept_order__body">
						<div class="accept_order__content">
							<h3>В магазине нет нужного товара или он плохого качества?</h3>
							<p> В этом случае закупщик сам выберет сопоставимую замену, если такая будет.
								Если у Вас имеются какие-то особые предпочтения на все или часть товаров,
								то мы рекомендуем лично настроить или запретить замены.
								Для этого в корзине наведите мышь на товар и нажмите "Добавить замены".
								Это не займет много времени и позволит нам оправдать Ваши ожидания!</p>
						</div>
					</div>
					<div class="accept_order__footer">
						<div class="accept_order__content">
							<div class="btn_group">
								<a href="javascript:" @click="continueShopping" class="b-button replace_btn_ basket">
									<i class="fas fa-shopping-cart"></i> Продолжить покупки
								</a>
								<a href="javascript:" @click="goToOrderForm" class="b-button replace_btn_ confirm_orders">Оформить заказ</a>
							</div>
						</div>
					</div>
				</div>
			</transition>
			
			
		</modal>
	</div>
</template>
<script>
	import {mapActions, mapGetters} from 'vuex';
	import Modal from 'Utilities/Modal.vue';
	import BasketItems from './basketItems';
	import ScrollBar from 'ScrollBar'
	import replace from './replace.vue';
	import deliveryTimes from './deliveryTimes';

	export default {
		props: {
			shops: {type: Object|Array},
			auth: {type: Number|String|Boolean}
		},
		data() {
			return {
				mainWindowOpen: true,
				scrollSettings: {},
			}
		},
		methods: {
			...mapActions([
				'fetchBasket'
			]),
			
			openBasketWindow() {
				this.$store.commit('showBasketWindow', true);
				this.mainWindowOpen = true;
			},

			closeBasket(){
				this.$store.commit('showBasketWindow', false);
				this.mainWindowOpen = true;
			},

			orderApply(){
				if(this.orderAllowed === true){
					this.mainWindowOpen = false;
				}
				
			},
			continueShopping(){
				this.closeBasket();
			},
			goToOrderForm(){
				if(this.auth == 0){
					this.continueShopping();
					$('#btn_auth_form_top').click();
					return;
				}
				
				if(this.orderAllowed === true){
					window.location.assign('/personal/order/make/');
				}
			},
			
		},
		watch: {},
		created(){
			this.fetchBasket();
		},
		beforeUpdate() {},
		components: {
			Modal,
			BasketItems,
			ScrollBar,
			replace,
			deliveryTimes
		},
		computed: {
			...mapGetters([
				'items', 'totalSum', 'count', 'sumFormat', 'loader',
				'orderAllowed', 'showBasketWindow', 'showTimes'
			]),
			
			countFormat(){
				return this.count+ ' ' +this.$declOfNum(['товар','товара','товаров'], this.count);
			},
		},
	}
</script>
<style lang="scss">
	.accept_order__content .btn_group {
		.replace_btn_.basket {
			background-image: none !important;
			padding-right: 13px;
			padding-left: 13px;
			
			.fas {
				font-size: 110%;
			}
		}
	}
</style>
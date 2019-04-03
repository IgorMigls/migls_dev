<template>
	<div>
		<div class="step-3 final" v-if="orderNumber">
			<div class="check__title check__title_r">Спасибо за заказ!</div>
			<div class="check__cont">
				<div class="check__cont__item">
					<span class="check__sm">Номер вашего заказа:</span><span class="check__big">{{orderNumber}}</span>
				</div>
				<div class="check__cont__item">
					<span class="check__sm">
						Ожидайте звонка, наш оператор обязательно свяжется с Вами в<br>
						рабочее время для подтверждения заказа.
					</span>
				</div>
			</div>
		</div>
		<div class="step-3" v-loading="preloader.show" :element-loading-text="preloader.text" v-else>
			<div class="check__title">Завершение</div>
			<div class="step-3__info">
				<div class="address_block">
					<b>Адрес доставки:</b><br>
					<span>{{addressFormat}}</span>
				</div>
				
				<div class="sum_product">
					<span class="info_title">Товары:</span>&nbsp;<span class="info_price">{{productSum}} <i class="fa fa-rouble"></i></span>
				</div>
				<div class="sum_delivery">
					<span class="info_title">Доставка:</span>&nbsp;<span class="info_price">{{deliverySum}} <i class="fa fa-rouble"></i></span>
				</div>
				
				<div class="sum_total">
					<span class="info_title">Итого:</span>&nbsp;<span class="info_price">{{totalSum}} <i class="fa fa-rouble"></i></span>
				</div>
			</div>
			<div class="step-3__fields">
				<div class="cell">
					<label class="form__label">
					<span class="field_form_wrap">
						<input type="text" name="promo" v-model="form.promo" class="form__input" placeholder="Промокод" autocomplete="off">
						<span class="tip_hover"><slot name="promo_tip"></slot></span>
					</span>
					</label>
				</div>
				
				<div class="cell">
					<label class="form__label">
					<span class="field_form_wrap">
						<input type="text" name="comment" v-model="form.comment" class="form__input" placeholder="Комментарий к заказу" autocomplete="off">
					</span>
					</label>
				</div>
			
			</div>
			<div slot="order_actions">
				<button type="button" @click="prevStep" class="b-button b-button_back b-button_big">Назад</button>
				<button type="button" @click="makeOrder" class="b-button b-button_green b-button_check b-button_big">Оформить</button>
			</div>
		</div>
	</div>
</template>
<script>
	import { mapGetters, mapActions } from 'vuex';
	
	export default {
		data() {
			return {
				form: {
					promo: '',
					comment: ''
				},
				prices: {
					delivery: 0,
					products: 0,
					total: 0
				}
			}
		},
		methods: {
			...mapActions([
				'loadBasket', 'saveOrder'
			]),
			prevStep(){
				this.$store.commit('setStep', 2)
			},
			makeOrder(){
				this.$store.commit('addOrderData', {final: this.form});
				this.saveOrder();
			}
		},
		watch: {},
		created() {
			if(_.isEmpty(this.basketData)){
				this.loadBasket();
			}
		},
		components: {},
		computed: {
			...mapGetters([
				'preloader', 'basketData', 'delivery', 'orderAddress', 'orderNumber'
			]),
			addressFormat(){
				return `г.${this.orderAddress.CITY}, ул.${this.orderAddress.STREET}, д.${this.orderAddress.HOUSE}`
			},
			deliverySum(){
				let sum = 0;
				_.forEach(this.delivery, (el, code) => {
					sum += _.toNumber(el.price);
				});
				
				// if(this.prices.products >= 2000){
				// 	sum = 0;
				// }
				
				this.prices.delivery = sum;
				
				return BX.util.number_format(sum, 0, ',', ' ');
			},
			productSum(){
				let sum = 0;
				_.forEach(this.basketData.items, (el, code) => {
					if(el.CLOSED !== 3){
						sum += _.toNumber(el.SUM);
					}
				});
				this.prices.products = sum;
				
				return BX.util.number_format(sum, 2, ',', ' ');
			},
			
			totalSum(){
				// if(this.prices.products >= 2000){
				// 	this.prices.delivery = 0;
				// }
				this.prices.total = this.prices.products + this.prices.delivery;
				return BX.util.number_format(this.prices.total, 2, ',', ' ');
			}
		},
	}
</script>
<style lang="scss">
	.step-3 {
		&__info{
			display: flex;
			flex-direction: column;
			min-height: 160px;
			margin: 30px 0;
			
			.address_block {
				font-size: 16px;
				line-height: 1.5rem;
				flex: 7;
				margin-bottom: 25px;
			}
		}
		
		.sum_product, .sum_delivery {
			font-size: 16px;
			flex: 2;
		}
		
		.sum_delivery {
			flex: 10;
		}
		.info_price {
			font-size: 0.9em;
		}
		.info_title {
			font-size: 1.4em;
		}
		
		.sum_total {
			.info_title {
				font-size: 1.2em;
			}
			
			.info_price {
				font-size: 1.4em;
			}
		}
		
		.check__cont {
			display: flex;
			flex-direction: column;
		}
		
		.check__sm {
			font-size: 16px;
		}
	}
</style>

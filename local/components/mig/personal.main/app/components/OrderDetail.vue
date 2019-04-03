<template>
	<div v-loading="loader" :class="{'order_detail_wrap': !order}">
		<div class="order_detail"  v-if="order">
		<div class="order_detail-title">
			<h1>Заказ №{{orderNumber}}, {{order.DATE.day}} {{order.DATE.monthRu}} {{order.DATE.year}}</h1>
		</div>
		<div class="order_detail-body">
			<div class="order-left">
				<div class="order_detail-item" v-for="(shop, id) in order.ORDER" :key="id">
					<div class="order_detail-shop">
						<img :src="shop.SHOP.IMG.src" />
						<div class="order_detail-status"></div>
						<div class="order_detail-delivery">
							{{deliveryFormat(shop.PROPS)}}<br />
							Доставка на {{shop.PROPS.DELIVERY_TIME.VALUE}}
						</div>
						<p><b>Статус заказа: </b>{{ shop.FIELDS.STATUS_DATA.NAME }}</p>
					</div>
					
					<h3 class="basket__title" v-if="shop.BASKET.MAIN"><i class="far fa-clock"></i>&nbsp;В процессе покупки</h3>
					<div v-if="shop.BASKET.MAIN" class="order_detail-basket basket__wrap">
						<div class="order_detail-item_wrap" v-for="basketItem in shop.BASKET.MAIN" :key="basketItem.ID">
							<div class="order_detail-basket--item">
								<div class="basket-image" v-if="basketItem.IMG">
									<a :href="basketItem.IMG.SRC" data-fancybox><img :src="basketItem.IMG.RESIZE.src" /></a>
								</div>
								<div class="basket-info">
									{{basketItem.NAME}}
									<div class="basket_comment" v-if="basketItem.CUSTOM && basketItem.CUSTOM.COMMENT">
										<p class="title_comment">Комментарий:</p>
										<p>{{basketItem.CUSTOM.COMMENT}}</p>
									</div>
								</div>
								<div class="basket-quantity">
									x {{basketItem.QUANTITY}} {{ basketItem.MEASURE_SHORT_NAME }}
								</div>
								<div class="basket-price">
									{{basketItem.SUM_FORMAT}} ₽
								</div>
							</div>
							<div class="order_detail-basket--replace" v-if="basketItem.CUSTOM && basketItem.CUSTOM.REPLACE">
								<el-collapse>
									<el-collapse-item title="Рекомендуемая замена">
										<div class="order_detail-basket--item">
											<div class="basket-image" v-if="basketItem.CUSTOM.REPLACE.PICTURE">
												<a :href="basketItem.CUSTOM.REPLACE.ORIGINAL_IMG.SRC" data-fancybox>
													<img :src="basketItem.CUSTOM.REPLACE.PICTURE.src" />
												</a>
											</div>
											<div class="basket-info">
												{{basketItem.CUSTOM.REPLACE.NAME}}
											</div>
											<div class="basket-price" v-if="basketItem.CUSTOM.REPLACE.PRICE">
												{{basketItem.CUSTOM.REPLACE.PRICE}} ₽
											</div>
										</div>
									</el-collapse-item>
								</el-collapse>
							</div>
						</div>
					</div>
					
					<h3 class="basket__title" v-if="shop.BASKET.FOUNDED"><i class="fas fa-check"></i>&nbsp;Купленные</h3>
					<div class="order_detail-basket basket__wrap" v-if="shop.BASKET.FOUNDED">
						<div class="order_detail-item_wrap" v-for="basketItem in shop.BASKET.FOUNDED" :key="basketItem.ID">
							<div class="order_detail-basket--item">
								<div class="basket-image" v-if="basketItem.IMG">
									<a :href="basketItem.IMG.SRC" data-fancybox><img :src="basketItem.IMG.RESIZE.src" /></a>
								</div>
								<div class="basket-info">
									{{basketItem.NAME}}
									<div class="basket_comment" v-if="basketItem.CUSTOM && basketItem.CUSTOM.COMMENT">
										<p class="title_comment">Комментарий:</p>
										<p>{{basketItem.CUSTOM.COMMENT}}</p>
									</div>
								</div>
								<div class="basket-quantity">
									x {{basketItem.QUANTITY}} {{ basketItem.MEASURE_SHORT_NAME }}
								</div>
								<div class="basket-price">
									{{basketItem.SUM_FORMAT}} ₽
								</div>
							</div>
							<div class="order_detail-basket--replace" v-if="basketItem.CUSTOM && basketItem.CUSTOM.REPLACE">
								<el-collapse>
									<el-collapse-item title="Рекомендуемая замена">
										<div class="order_detail-basket--item">
											<div class="basket-image" v-if="basketItem.CUSTOM.REPLACE.PICTURE">
												<a :href="basketItem.CUSTOM.REPLACE.ORIGINAL_IMG.SRC" data-fancybox>
													<img :src="basketItem.CUSTOM.REPLACE.PICTURE.src" />
												</a>
											</div>
											<div class="basket-info">
												{{basketItem.CUSTOM.REPLACE.NAME}}
											</div>
											<div class="basket-price" v-if="basketItem.CUSTOM.REPLACE.PRICE">
												{{basketItem.CUSTOM.REPLACE.PRICE}} ₽
											</div>
										</div>
									</el-collapse-item>
								</el-collapse>
							</div>
						</div>
					</div>
					
					<h3 class="basket__title" v-if="shop.BASKET.REPLACES">
						<i class="fas fa-sync-alt"></i>&nbsp;Заменённые товары
					</h3>
					<div class="order_detail-basket basket__wrap">
						<div class="order_detail-item_wrap" v-for="basketItem in shop.BASKET.REPLACES"
								:key="'replaces_' + basketItem.ID" v-if="shop.BASKET.REPLACES">
							
							<div class="replaces_from" v-if="basketItem.REPLACE_FROM !== null">
								<!--<pre>{{basketItem.REPLACE_FROM}}</pre>-->
								<div class="order_detail-basket--item">
									<div class="basket-image" v-if="basketItem.REPLACE_FROM.PRODUCT.IMG">
										<a :href="basketItem.REPLACE_FROM.PRODUCT.DETAIL_PICTURE.SRC" data-fancybox>
											<img :src="basketItem.REPLACE_FROM.PRODUCT.IMG.src" />
										</a>
									</div>
									<div class="basket-info" v-html="basketItem.REPLACE_FROM.DATA.NAME"></div>
									<div class="basket-quantity">
										x {{basketItem.QUANTITY}} {{ basketItem.MEASURE_SHORT_NAME }}
									</div>
									<div class="basket-price">
										{{basketItem.REPLACE_FROM.SUM_FORMAT}} ₽
									</div>
								</div>
							</div>
							<p>Заменён на:</p>
							<div class="order_detail-basket--item">
								<div class="basket-image" v-if="basketItem.IMG">
									<a :href="basketItem.IMG.SRC" data-fancybox><img :src="basketItem.IMG.RESIZE.src" /></a>
								</div>
								<div class="basket-info">
									<span v-html="basketItem.NAME"></span>
									<div class="basket_comment" v-if="basketItem.CUSTOM && basketItem.CUSTOM.COMMENT">
										<p class="title_comment">Комментарий:</p>
										<p>{{basketItem.CUSTOM.COMMENT}}</p>
									</div>
								</div>
								<div class="basket-quantity">
									x {{basketItem.QUANTITY}} {{ basketItem.MEASURE_SHORT_NAME }}
								</div>
								<div class="basket-price">
									{{basketItem.SUM_FORMAT}} ₽
								</div>
							</div>
						</div>
					</div>
					
					<h3 class="basket__title" v-if="shop.FIELDS.DELETED"><i class="fas fa-reply"></i>&nbsp;Удалённые товары</h3>
					<div v-if="shop.FIELDS.DELETED" class="order_detail-basket basket__wrap">
						
						<div class="order_detail-item_wrap" v-for="basketItem in shop.FIELDS.DELETED" :key="'deleted_' + basketItem.ID">
							<div class="order_detail-basket--item">
								<div class="basket-image" v-if="basketItem.PRODUCT.IMG">
									<a :href="basketItem.PRODUCT.DETAIL_PICTURE.SRC" data-fancybox><img :src="basketItem.PRODUCT.IMG.src" /></a>
								</div>
								<div class="basket-info">
									{{basketItem.DATA.NAME}}
								</div>
								<div class="basket-quantity">
									x {{basketItem.DATA.QUANTITY}} {{ basketItem.DATA.MEASURE_SHORT_NAME }}
								</div>
								<div class="basket-price"></div>
							</div>
						</div>
					</div>
					
				</div>
				
				
			</div>
			<div class="order-right">
				<div class="b-popu-card_check b-popup-chekc_lk">
					<div class="b-popup-check">
						<div class="check__title">Ваш заказ</div>
						<div class="check__total-items">
							
							<div class="check__total">Товары:
								{{order.SUM_BASKET_FORMAT}} ₽
							</div>
							
							<div class="check__total">Доставка:
								{{order.SUM_DELIVERY}} ₽
							</div>
							
							<div class="check__total">Итого:
								{{order.SUM_FORMAT}} ₽
							</div>
						</div>
						<div class="comment__text" v-if="order.USER_DESCRIPTION">
							<b>Комментарий пользователя: </b>
							<p>{{order.USER_DESCRIPTION}}</p>
						</div>
						<button  v-if="!order.ORDER_BLOCKED" @click="cancelOrderAction"
								class="b-button b-button_green b-button_check b-button_big">Отменить заказ</button>
					</div>
				</div>
			</div>
		</div>
		
		<!--<pre>{{order}}</pre>-->
		<div class="order_detail-footer">
			<router-link class="b-button b-button_green" to="/orders">Назад</router-link>
		</div>
	</div>
	</div>
</template>
<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		data() {
			return {}
		},
		beforeUpdate() {
		},
		created() {
			this.loadOrder(this.$route.params.id);
		},
		methods: {
			...mapActions('orders', [
				'loadOrder', 'cancelOrder'
			]),
			deliveryFormat(props) {
				let out = `г.${props.CITY.VALUE}, ${props.STREET.VALUE}, д.${props.HOUSE.VALUE}, кв.${props.APARTMENT.VALUE}`;
				if(props.ZIP.VALUE.length > 0) {
					out += ', '+props.ZIP.VALUE + ' подъезд';
				}
				return out;
			},

			cancelOrderAction(){
				this.cancelOrder(this.$route.params.id).then(res => {
					this.$router.push('/orders');
				});
			}
		},
		components: {},
		computed: {
			...mapGetters('orders', [
				'order', 'loader'
			]),
			
			orderNumber(){
				let num = '', arNum = [];
				_.forEach(this.order.ORDER, (el, code) => {
					arNum.push(code);
				});
				
				return arNum.join('/');
			},
		}
	}

</script>
<style lang="scss">
	
	$borderColor: #e5e5e5;
	$border: 1px solid $borderColor;
	$greenColor: #34c263;
	$yellowColor: #f8be00;
	$redColor: #d60000;
	
	.order_detail_wrap {
		height: 100vh;
	}
	
	.order_detail {
		padding-top: 42px;
		padding-bottom: 30px;
		
		h1 {
			font-size: 18px;
			font-family: 'HelveticaNeueCyr-Thin';
			padding-bottom: 30px;
		}
		
		&-delivery {
			font-size: 15px;
			color: #898989;
			line-height: 22px;
			margin: 10px 0;
		}
		
		&-body {
			display: flex;
		}
		
		.order-left {
			width: 64%;
			padding-right: 15px;
		}
		.order-right {
			flex: 1;
		}
		
		&-item_wrap {
			margin-bottom: 50px;
			border-bottom: $border
		}
		
		&-basket {
			margin: 10px 0;
			padding-top: 10px;
			
			&--item {
				display: flex;
				align-items: center;
				justify-content: space-between;
				margin-bottom: 25px;
				
				div {
					margin-right: 15px;
				}
			}
		}
		.el-collapse {
			border: none;
			
			.el-collapse-item__header {
				line-height: 30px;
				height: auto;
			}
			
			.el-collapse-item__arrow {
				line-height: 30px;
				float: left;
			}
		}
		.basket-image {
			width: 100px;
			margin-right: 25px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		
		.basket-quantity {
			width: 75px;
			/*text-align: center;*/
		}
		
		.basket-price {
			width: 70px;
			text-align: right;
		}
		
		.basket-info {
			flex: 1;
			margin-right: 25px;
		}
		
		.b-popup-chekc_lk {
			margin-left: 0;
			padding-left: 30px;
		}
		.comment__text {
			line-height: 18px;
		}
		
		.basket_comment {
			color: #898989;
			font-size: 85%;
			
			p {
				margin: 6px 0;
			}
			
			.title_comment {
				margin: 0;
				padding: 15px 0 0 0;
				font-weight: bold;
			}
		}
		
		.replaces_from {
			text-decoration: line-through;
		}
		
		.basket__title {
			margin-top: 25px;
			font-size: 18px;
			
			.fas, .far {
				color: #fff;
				border-radius: 50%;
				padding: 4px;
				font-size: 14px;
			}
			
			.fa-check {
				background-color: $greenColor;
			}
			.fa-reply {
				background-color: $redColor;
			}
			.fa-sync-alt {
				background-color: $yellowColor;
			}
			
			.fa-clock {
				background-color: #898989;
			}
		}
		
		.basket__wrap {
			border-top: 2px dashed $borderColor;
		}
		
		.check__total {
			margin-bottom: 10px;
		}
	}
</style>

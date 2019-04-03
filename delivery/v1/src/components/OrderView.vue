<template>
	<div class="detail_view" v-if="detailOrder">
		<q-list style="width: 100%;">
			<q-item>
				<q-item-main>
					Заказ №{{detailOrder.ID}} от {{detailOrder.DATE_INSERT}}
				</q-item-main>
			</q-item>
			<q-item>
				<q-item-main label="Сумма">
					<b>Товаров: </b> {{basketPrice()}} р.<br />
					<b>Доставки: </b> {{deliveryPrice()}} р.<br />
					<b>Итого: </b> {{detailOrder.PRICE_FORMAT}} р.<br />
				</q-item-main>
				<!--<pre>{{ detailOrder }}</pre>-->
			</q-item>
			<q-item>
				<q-item-main label="Магазин:">
					{{detailOrder.PROPS.SHOP_CODE.VALUE}} {{detailOrder.PROPS.SHOP_ADDRESS.VALUE}}
				</q-item-main>
			</q-item>
			<q-item-separator />
			<q-item>
				<q-item-main label="Доставка:">{{addressFormat(detailOrder.PROPS)}}</q-item-main>
				<q-item-main label="Время доставки:">{{ detailOrder.PROPS.DELIVERY_TIME.VALUE }}</q-item-main>
			</q-item>
			
			<!--<pre>{{ detailOrder }}</pre>-->
		</q-list>
		
		<div class="products_wrap">
			<q-card v-for="item in detailOrder.BASKET" :key="item.ID">
				<div class="item_product">
					<q-card-media class="item_product_img" v-if="item.RESIZE"
							:style="{'background-image': `url(${item.RESIZE.src})`}">
					</q-card-media>
				</div>
				<div class="item_product_body">
					<q-card-title :class="[crossedClass(item)]"><span v-html="item.NAME"></span></q-card-title>
					<q-card-main>
						<!--<pre>{{ item }}</pre>-->
						<p :class="[crossedClass(item)]">Цена: {{item.BASKET_DATA.PRICE_FORMAT}}р / {{
							item.MEASURE_SHORT_NAME }}</p>
						<p :class="[crossedClass(item)]" v-if="!editOrder">Количество: {{item.BASKET_DATA.QUANTITY}}</p>
						<p v-else :class="[crossedClass(item), 'quantity_icons']">
							<span class="icon_quantity"><q-icon @click="minus(item)" name="remove_circle" color="secondary"></q-icon></span>
							<q-input readonly v-model="item.BASKET_DATA.QUANTITY" color="amber" type="number"
									numeric-keyboard-toggle align="center" :hide-underline="false" :error="quantityError" />
							<span class="icon_quantity"><q-icon @click="plus(item)" name="add_circle" color="secondary"></q-icon></span>
							<q-btn style="margin-left: 30px" color="negative" flat @click="deleteItem(item)">Удалить
							</q-btn>
						</p>
						
						<p :class="[crossedClass(item)]">Сумма: {{item.BASKET_DATA.SUM_FORMAT}}р</p>
						
						<div class="replaces_item_view" v-if="item.REPLACE">
							<h5>Замена</h5>
							
							<div>
								<q-card-media class="item_product_img" v-if="item.REPLACE.PICTURE">
									<img class="img-responsive" :src="item.REPLACE.PICTURE.src" />
								</q-card-media>
								<p>{{ item.REPLACE.NAME }}</p>
								<p>Цена: {{format(item.REPLACE.PRICE)}}р</p>
								<p>Количество: {{item.REPLACE.QUANTITY}}</p>
								<p>Сумма: {{item.REPLACE.PRICE * item.REPLACE.QUANTITY}}р</p>
								<!--<pre>{{ item.CUSTOM.REPLACE }}</pre>-->
							</div>
						</div>
						
						<div class="replaces_item_view" style="text-decoration:line-through" v-if="item.REPLACE_FROM !== null && item.REPLACE_FROM.ID">
							<!--<pre>{{ item.REPLACE_FROM }}</pre>-->
							<p>{{ item.REPLACE_FROM.DATA.NAME }}</p>
							<p>Цена: {{format(item.REPLACE_FROM.DATA.PRICE)}}р</p>
							<p>Количество: {{item.REPLACE_FROM.DATA.QUANTITY}}</p>
						</div>
					
					</q-card-main>
				</div>
			</q-card>
			
			<q-item-separator></q-item-separator>
			<h4 style="font-size: 20px; padding-left: 15px" v-if="detailOrder.REMOVED">Удаленные товары</h4>
			<q-card class="removed_products" v-for="item in detailOrder.REMOVED" :key="'remove_' + item.ID">
				<q-card-main>
					<p>{{ item.DATA.NAME }}</p>
					<!--<p>Цена: {{format(item.DATA.PRICE)}}р</p>-->
					<p>Количество: {{item.DATA.QUANTITY}}</p>
				</q-card-main>
			</q-card>
		
		</div>
		<!--<pre>{{detailOrder}}</pre>-->
		<q-fixed-position corner="bottom-right" :offset="[18, 18]" v-if="showCheckBtn">
			<q-btn round color="positive" icon="check_circle" @click="addToComplect(detailOrder.ID)" />
		</q-fixed-position>
		<q-fixed-position corner="bottom-right" :offset="[18, 18]" v-if="showToMyDelivery">
			<q-btn round color="positive" icon="favorite" @click="addToMyDelivery(detailOrder.ID)" />
		</q-fixed-position>
		<q-fixed-position corner="bottom-right" :offset="[18, 18]" v-if="showAbortDelivery">
			<q-btn round color="negative" icon="lock_open" @click="abortDelivery(detailOrder.ID)" />
		</q-fixed-position>
	</div>
</template>

<script>
	import {
		QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions, QBtn, QIcon, QCardMedia,
		QFixedPosition, QList, QItem, QItemSeparator, QItemMain, QInput
	} from 'quasar';

	import _ from 'lodash';
	import { mapActions, mapGetters } from 'vuex';
	import util from '../plugins/util';

	export default {
		name: "order-view",
		data() {
			return {
				quantityError: false,
				
			}
		},
		props: {},
		methods: {
			...mapActions([
				'getDetailOrder', 'lockOrder', 'setMyDelivery', 'abortMyDelivery',
				'updateQuantityFinal', 'deleteProductFinal',
			]),
			inputPlus(basket) {
				console.info(basket);
				return [ {
					icon: 'add',
					handler: this.plus
				} ]
			},
			addressFormat(props) {
				let arProps = [
					props.CITY.VALUE,
					props.STREET.VALUE,
					props.HOUSE.VALUE,
					props.APARTMENT.VALUE !== '' ? 'д.' + props.APARTMENT.VALUE : '',
					props.ZIP.VALUE !== '' ? 'подъезд ' + props.ZIP.VALUE : '',
				];

				return arProps.join(', ');
			},

			addToComplect(id) {
				this.lockOrder(id).then(res => {
					this.$router.push('/complect/' + id);
				});
			},

			saveComplection() {
				// console.info(this.$route.params.id);
			},

			crossedClass(item) {
				if (item.hasOwnProperty('CUSTOM') && item.CUSTOM instanceof Object) {
					return item.CUSTOM.REPLACE instanceof Object && item.CUSTOM.REPLACE.ACTIVE === 'Y' ? 'crossed-text' : '';
				}

				return '';
			},

			format(price) {
				return util.priceFormat(price)
			},

			addToMyDelivery(id) {
				this.setMyDelivery(id).then(res => {
					if (res.data.ERRORS === null) {
						this.$router.push('/myDelivery');
					}
				})
			},

			abortDelivery(id) {
				this.abortMyDelivery(id).then(res => {
					if (res.data.ERRORS === null) {
						this.$router.push('/myDelivery');
					}
				});
			},

			plus(basketItem) {
				let ratio = basketItem.MEASURE_RATIO > 0 ? basketItem.MEASURE_RATIO : 1;
				basketItem.BASKET_DATA.QUANTITY += ratio;
				// this.updateQuantityFinal(basketItem);
				this.updateQuantityFinal(basketItem).then(res => {
					this.getDetailOrder({ id: this.$route.params.id, name: this.$route.name }).then(res => {});
				})
			},

			minus(basketItem) {
				let ratio = basketItem.MEASURE_RATIO > 0 ? basketItem.MEASURE_RATIO : 1;
				basketItem.BASKET_DATA.QUANTITY -= ratio;

				if (basketItem.BASKET_DATA.QUANTITY <= 0) {
					basketItem.BASKET_DATA.QUANTITY = ratio;
				}

				if (basketItem.BASKET_DATA.QUANTITY > 0) {
					this.updateQuantityFinal(basketItem).then(res => {
						this.getDetailOrder({ id: this.$route.params.id, name: this.$route.name }).then(res => {});
					})
				}

			},

			deleteItem(basketItem) {
				if (confirm('Вы уверены, что хотите удалить товар?')) {
					this.deleteProductFinal(basketItem).then(res => {
						this.getDetailOrder({ id: this.$route.params.id, name: this.$route.name });
					});
				}

			},
			basketPrice() {
				let val = _.toNumber(this.detailOrder.PRICE) - _.toNumber(this.detailOrder.PRICE_DELIVERY);
				return util.priceFormat(val, 2);
			},
			deliveryPrice() {
				let val = _.toNumber(this.detailOrder.PRICE_DELIVERY);
				return util.priceFormat(val, 2);
			}
		},
		computed: {
			...mapGetters([
				'detailOrder',
			]),

			showCheckBtn() {
				return this.$route.name !== 'deliveryDetail' && this.$route.name !== 'myDeliveryDetail';
			},

			showToMyDelivery() {
				return this.$route.name === 'deliveryDetail';
			},

			showAbortDelivery() {
				return this.$route.name === 'myDeliveryDetail';
			},

			editOrder() {
				return this.$route.name === 'myDeliveryDetail';
			},
		},
		created() {
			this.getDetailOrder({ id: this.$route.params.id, name: this.$route.name });
		},
		components: {
			QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions, QBtn, QIcon, QCardMedia,
			QFixedPosition, QList, QItem, QItemSeparator, QItemMain, QInput
		},
	}
</script>

<style lang="scss">
	.item_product_img {
		text-align: center;
		display: flex;
		justify-content: center;
		height: 100%;
		background-position: center center;
		background-size: contain;
		background-repeat: no-repeat;
		max-width: 120px;
	}
	
	.detail_view, .detail_info {
		display: flex;
		flex-wrap: wrap;
		
		.q-card {
			min-width: 320px;
			font-size: 0.9rem;
			display: flex;
			flex-direction: row;
			width: 98%;
		}
		
	}
	
	.products_wrap {
		.item_product {
			flex: 2;
		}
		
		.item_product_body {
			flex: 4;
		}
		
		.q-card-title {
			font-size: 1.4rem;
		}
	}
	
	.replaces_item_view {
		border-top: 1px solid #999;
		
		h5 {
			font-size: 1.2rem;
			font-weight: 500;
		}
		
		p {
			margin-bottom: 10px;
		}
	}
	
	.crossed-text {
		text-decoration: line-through;
	}
	
	.removed_products {
		flex-direction: column !important;
	}
	
	.quantity_icons {
		display: flex;
		/*justify-content: space-around;*/
		align-items: center;
		cursor: pointer;
		
		.icon_quantity {
			font-size: 30px;
		}
	}
</style>

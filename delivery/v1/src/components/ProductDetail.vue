<template>
	<q-card class="product_detail" v-if="product.ID">
		
		<q-card-title>{{product.NAME}}</q-card-title>
		<q-card-media v-if="product.IMG" :style="{'background-image': `url(${product.IMG.SRC})`}" />
		<q-card-separator />
		<q-card-actions>
			<div class="product_detail-quantity" v-if="showQuantity">
				<q-input v-model="quantity" color="amber" dark type="number" numeric-keyboard-toggle rows="12" align="center"
						:before="inputMinus" :after="inputPlus" inverted :hide-underline="false" :error="quantityError" />
				<span>&nbsp;{{ product.MEASURE_SHORT_NAME }}</span>
				<span class="quantity_from_txt">из</span>
				<span class="quantity_from">{{product.BASKET_DATA.QUANTITY}}</span>
			</div>
			<q-btn class="btn_quantity" style="width: 100%" color="positive" @click="foundedSet" icon="check">Найдено</q-btn>
		</q-card-actions>
		<q-card-main>
			<div class="product_detail-sum">
				<b>Цена: </b>{{ product.BASKET_DATA.PRICE_FORMAT }} <i class="fa fa-rouble"></i><br />
				<b>Сумма: </b>{{sum}} <i class="fa fa-rouble"></i><br />
			</div>
		</q-card-main>
		<q-card-main>
			<div v-if="product.COMMENT">
				<b>Комментарий: </b>
				<p>{{ product.COMMENT }}</p>
			</div>
			<div v-if="product.REPLACE && !isFounded">
				<b>Рекомендуемая замена: </b>
				<q-card-title>{{product.REPLACE.NAME}}</q-card-title>
				<q-card-media v-if="product.REPLACE.PICTURE" :style="{'background-image': `url(${product.REPLACE.PICTURE.src})`}" />
				<q-card-actions>
					<q-btn color="secondary" @click="addReplace(product.REPLACE)" class="full-width" v-if="product.REPLACE">
						Заменить
					</q-btn>
				</q-card-actions>
			</div>
		
		</q-card-main>
		
		<q-card-separator />
		
		<q-card-actions>
			<q-btn flat @click="closeDetail" icon="keyboard_backspace">Назад</q-btn>
			<q-btn v-if="showReplaces" @click="openReplaces" color="primary">Найти замены</q-btn>
		</q-card-actions>
		
		<!--<pre>{{product}}</pre>-->
		
		<q-modal v-model="replaceWinOpen" :content-css="{minWidth: '80vw', minHeight: '80vh'}">
			<q-modal-layout>
				<q-toolbar slot="header">
					<q-btn flat round dense @click="closeReplaces" icon="reply" wait-for-ripple />
					<q-toolbar-title>Поиск замены</q-toolbar-title>
				</q-toolbar>
				
				
				<q-toolbar slot="header">
					<q-search class="full-width" inverted v-model="searchProduct" color="none" :after="searchIcon" :before="[]" />
				</q-toolbar>
				
				<div class="layout-padding">
					<div class="caption" v-if="searchReplaceItems.length > 0" v-for="item in searchReplaceItems">
						<q-card class="product_replace_item">
							<q-card-media v-if="item.PICTURE" class="item_img" :style="{backgroundImage: `url(${item.PICTURE.src})`}" />
							<q-card-title>{{item.NAME}}</q-card-title>
							
							<q-card-separator />
							
							<q-card-main class="product_replace_item-actions">
								<div class="product_replace_item-prices">{{item.PRICE_FORMAT}}
									<i class="fa fa-rouble"></i></div>
								<q-input type="number" v-model="quantity" readonly placeholder="Количество" />
								<q-btn color="positive" icon="add_shopping_cart" @click="addReplace(item)"></q-btn>
							</q-card-main>
						</q-card>
					</div>
					<div v-else>
						<p> - Продукты не найдены - </p>
					</div>
				</div>
			</q-modal-layout>
		</q-modal>
	
	</q-card>
</template>

<script>
	import {
		QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
		QBtn, QIcon, QCardMedia, QModal, QInput,
		QModalLayout, QToolbar, QToolbarTitle, QSearch,
	} from 'quasar';

	import { mapActions, mapGetters } from 'vuex';

	export default {
		name: "product-detail",
		props: {
			detail: { type: Object, required: true },
			showQuantity: {
				type: Boolean, default: () => {
					return true
				}
			},
			showReplaces: { type: Boolean },
		},
		data() {
			return {
				product: this.detail,
				quantity: 1,
				inputPlus: [ {
					icon: 'add',
					handler: this.plus
				} ],
				inputMinus: [ {
					icon: 'remove',
					handler: this.minus
				} ],
				quantityError: false,
				replaceWinOpen: false,
				searchProduct: '',
				searchIcon: [ {
					icon: 'search',
					handler: this.searchGo
				} ],
				isFounded: false
			}
		},
		methods: {
			...mapActions([
				'fetchReplaces', 'addReplaceToBasket'
			]),

			plus() {

				let ratio = this.product.MEASURE_RATIO > 0 ? this.product.MEASURE_RATIO : 1;

				if (this.quantity < this.detail.BASKET_DATA.QUANTITY) {
					this.quantity += ratio;
				}

			},
			minus() {
				let ratio = this.product.MEASURE_RATIO > 0 ? this.product.MEASURE_RATIO : 1;

				if (this.quantity > 0) {
					this.quantity -= ratio;
				}

			},
			foundedSet() {
				this.$emit('founded', { product: this.product, count: this.quantity, sum: this.sum });
				this.$emit('hide');
			},


			openReplaces() {
				let name = this.detail.NAME.split(' ');

				let send = {
					q: name[ 0 ],
					sku: this.detail.SKU_ID,
					product: this.detail.PRODUCT_ID,
					quantity: this.detail.BASKET_DATA.QUANTITY,
					orderId: this.detail.BASKET_DATA.ORDER_ID
				};
				this.replaceWinOpen = true;

				this.fetchReplaces(send);
			},

			closeReplaces() {
				this.replaceWinOpen = false;
			},

			searchGo() {
				if (this.searchProduct.length > 2) {
					let send = {
						q: this.searchProduct,
						sku: this.detail.SKU_ID,
						product: this.detail.PRODUCT_ID,
						orderId: this.detail.BASKET_DATA.ORDER_ID
					};
					this.fetchReplaces(send);
				}
			},

			addReplace(item) {
				// console.info(item);
				// return;
				this.addReplaceToBasket({ replace: item, basketItem: this.detail }).then(res => {
					if (res.data.ERRORS === null) {
						this.product = Object.assign({}, res.data.DATA.basketItem);
						this.closeReplaces();
					}
				});
			},

			closeDetail() {
				this.$emit('hide');
				this.closeReplaces();
			},

		},
		watch: {
			detail(item) {
				this.product = item;

				if (this.product.ID) {
					this.quantity = item.BASKET_DATA.QUANTITY;

					this.isFounded = this.founded.hasOwnProperty(this.product.BASKET_ID);
				}
			},

			quantity(val) {

				this.product.BASKET_DATA.SUM_FORMAT = this.detail.PRICE * val;


				// this.quantityError = this.sum > this.detail.SUM;
			},

		},
		created() {
		},
		beforeUpdate() {
		},
		components: {
			QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
			QBtn, QIcon, QCardMedia, QModal, QInput,
			QModalLayout, QToolbar, QToolbarTitle, QSearch,
		},
		computed: {
			...mapGetters([
				'searchReplaceItems', 'founded'
			]),
			sum() {
				// return this.detail.PRICE * this.quantity;
				return this.product.BASKET_DATA.SUM_FORMAT;
			},
		},
		mounted() {
		}
	}
</script>

<style scoped lang="scss">
	.product_detail {
		padding: 0 15px;
		
		.q-card-title {
			line-height: 24px;
		}
		
		.q-card-media {
			min-height: 300px;
			display: flex;
			background-position: center center;
			background-size: contain;
			background-repeat: no-repeat;
		}
		
		&-quantity {
			display: flex;
			width: 100%;
			align-items: center;
			text-align: center;
			
			.quantity_from_txt, .quantity_from {
				margin: auto 10px;
				font-size: 1.1rem;
			}
			
			.btn_quantity {
				height: 40px;
				margin-top: 6px;
				padding-left: 20px;
				padding-right: 20px;
			}
		}
		
		&-sum {
			padding: 15px;
		}
	}
	
	.product_replace_item {
		height: 350px;
		display: flex;
		flex-direction: column;
		padding-bottom: 15px;
		
		.item_img {
			display: flex;
			height: 100%;
			max-height: 200px;
			background-position: center center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		
		&-actions {
			display: flex;
			align-items: baseline;
		}
		
		&-prices {
			margin-right: 30px;
			font-size: 1.1rem;
		}
		
		.q-card-title {
			word-break: break-all;
			width: 100%;
			height: 85px;
			font-size: 16px;
			line-height: 1.4rem;
		}
	}
</style>

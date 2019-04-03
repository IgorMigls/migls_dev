<template>
	<!-- @keyup.esc.self="$emit('close-basket')" tabindex="-1" -->
	<div class="basket_shop_item">
		<div class="bs_shop_header bg_reverse">
			<div class="bs_shop_img" v-if="shop.PICTURE">
				<img :src="shop.PICTURE.src" />
			</div>
			<div class="bs_shop_info">
				<div class="search__title ng-binding">
					{{shop.NAME}},
					<span class="cart__title-item">{{shop.COUNT_FORMAT}} на сумму {{shop.SUM_FORMAT}}</span>
					<span class="check-rub-cart">₽</span>
				</div>
				<div class="cart__deliv">
					<div class="cart__cl b-ib" v-if="shop.CURRENT">
						<span class="cart__cl-1">
							Ближайшая доставка:<br />
							{{shop.CURRENT.NAME}}, {{shop.CURRENT.DATE_FORMAT.DAY}} {{shop.CURRENT.DATE_FORMAT.MONTH_LOCALE}}
						</span>
						<span class="cart__cl-1">{{shop.CURRENT.CURRENT.TIME_FROM}} &ndash; {{shop.CURRENT.CURRENT.TIME_TO}}</span>
					</div>
					<div class="cart__cl-50 b-ib b-products-block-top__right">
						<a href="javascript:" class="get_shop_interval b-button b-button_show b-button_show_small"
								@click="openDeliveryTimes(shop.CODE)">
							Все интервалы
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="bs_item_messages" v-if="shop.MESSAGE && shop.CLOSED < 3">
			<div :class="['bs_item_message', type]" :key="shop.CODE +' '+ type"
					v-for="(msg, type) in shop.MESSAGE"
					v-show="msg.show">
				{{msg.msg}}
			</div>
		</div>
		<div class="bs_shop_basket_items" v-for="(basket, productId) in shop.BASKET" :key="productId">
			<product :product-item="basket" @replace="getReplace"></product>
		</div>
	</div>
</template>
<script>
	
	import Product from './Product.vue';
	
	export default {
		props: {
			shop: {type: Object, required: true},
		},
		data() {
			return {
				replaceParams: {},
			}
		},
		methods: {
			getReplace(product) {
				this.replaceParams = {
					product: product.PRODUCT_ID,
					shop: product.SHOP_ID,
					sku: product.SKU_ID,
					shopCode: product.SHOP_CODE,
				};
				this.replaceShow = true;
			},

			saveReplace(productData){

				let {shopCode, product} = this.replaceParams;

				// console.info(shopCode, product, productData);

				if(this.basket.items.hasOwnProperty(shopCode)){
					
					let basketItem = this.basket.items[shopCode]['BASKET'][product];
					basketItem['REPLACE'] = {
						productId: productData.ID,
						name: productData.NAME
					};
					this.http.saveReplace({id: basketItem.ID, replace: productData}).then(res => {
						console.info(res.data);
						
						if(res.data.DATA === true){
							this.replaceShow = false;
						}
					});
				}
			},
			openDeliveryTimes(shopId) {
				this.$store.commit('showTimes', shopId);
			}
		},
		watch: {},
		created() {},
		beforeUpdate() {},
		components: {
			Product,
		},
		computed:{},
		mounted(){}
	}
</script>
<style scoped>
	.get_shop_interval:hover {
		color: #000;
	}
</style>
<template>
	<div class="favorite-product">
		<!--<pre>{{ product }}</pre>-->
		<hr class="separ">
		<div class="product_body">
			<div class="image">
				<img :src="product.IMG.src" v-if="product.IMG" />
			</div>
			<div class="name">
				<label>
					<input type="checkbox" v-model="product.CHECKED" @change="checkProductItem" />
					<span>{{ product.NAME }}</span>
				</label>
				
				<div class="price">
					<div class="price_val">{{ product.PRICE_FORMAT }} <span class="b-rouble">₽</span></div>
				</div>
				<div class="basket_action index_products_basket">
					<div class="b-product-preview__count b-ib">
						<input type="text" class="quantity_input b-product-preview__input" v-model="quantity" />
						<button type="button" class="b-button b-button_plus" @click="plus">+</button>
						<button type="button" class="b-button b-button_minus" @click="minus">–</button>
						<span class="input-measure">{{ product.MEASURE_SHORT_NAME }}</span>
					</div>
					<div class="b-product-preview__incart b-ib">
						<button type="button" class="b-button b-button_green add_basket_btn" @click="addToBasket">
							В корзину
						</button>
					</div>
				</div>
			</div>
			<span class="star_link"></span>
		
		</div>
	
	</div>
</template>

<script>

	export default {
		name: "productFavorite",
		props: {
			product: { type: Object, require: true },
			checkAll: { type: Boolean }
		},
		data() {
			return {
				quantity: 1
			}
		},
		methods: {
			addToBasket() {
				let send = {
					product: {
						ID: this.product.ID,
						PRODUCT_ID: this.product.PRODUCT_ID
					},
					quantity: this.quantity,
					sku: this.product.ID
				};
				window.MigBus.$emit('addToBasket', send);
			},

			plus() {
				this.quantity += this.product.MEASURE_RATIO;
			},
			minus() {
				let val = this.quantity - this.product.MEASURE_RATIO;
				if (val <= 0) {
					val = this.product.MEASURE_RATIO;
				}
				this.quantity = val;
			},

			checkProductItem(){
				if(this.product.CHECKED){
					this.$emit('check-product', this.product);
				} else {
					this.$emit('uncheck-product', this.product);
				}
			},
		},
		watch: {},
		computed: {},
		created() {
			let ratio = _.toNumber(this.product.MEASURE_RATIO);
			if (ratio !== 1) {
				this.quantity = ratio;
			}
		},
		mounted() {},
		components: {},
	}
</script>

<style lang="scss">
	.favorite-product {
		width: 48%;
		padding: 10px 20px;
		display: flex;
		flex-direction: column;
		
		.separ {
			height: 1px;
			width: 100%;
			background: #e5e5e5;
			border: none;
			margin-bottom: 3px;
		}
		
		.product_body {
			display: flex;
			width: 100%;
			padding: 15px;
			transition: box-shadow 0.2s ease-out;
			border-radius: 5px;
			min-height: 120px;
			
			&:hover {
				box-shadow: inset 0 0 0 3px #fdd000;
			}
			
			.image {
				width: 80px;
				margin-right: 15px;
				/*display: flex;
				flex-direction: column;
				justify-content: center;*/
			}
			.name {
				padding-top: 10px;
				min-height: 55px;
				font-size: 14px;
				color: #000;
				line-height: 1.1em;
				width: 70%;
				
				input {
					display: inline-block;
					margin-right: 10px;
				}
				
				label {
					display: flex;
					cursor: pointer;
				}
			}
			
			.star_link {
				background-image: url(/local/dist/images/sprite2.png);
				background-position: -575px -618px;
				width: 20px;
				height: 19px;
				display: inline-block;
				opacity: 1;
				-ms-filter: none;
				filter: none;
			}
			
			.star_link.star_null {
				background-position: -530px -618px !important;
			}
		}
		
		.price {
			margin-bottom: 20px;
			
			.price_val {
				padding-top: 12px;
				font-size: 24px;
				font-family: 'HelveticaNeueCyr-Thin';
				margin-left: 25px;
			}
		}
		
		.basket_action {
			display: flex;
			.add_basket_btn {
				width: auto !important;
			}
		}
		
	}
</style>
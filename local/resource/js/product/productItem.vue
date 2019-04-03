<template>
	<div class="b-products-slider__item js-products-slider-item" v-if="product">
		<div :class="['b-product-preview b-ib-wrapper']">
			
			<div class="b-product__count b-ib"><span class="b-count__in">{{product.BASKET_QUANTITY}} шт. в корзине</span></div>
			
			<div :class="['b-product__star', {'active' : inFavorite}]" @click="addToFavorite">
				<a href="javascript:" class="prod__star"></a>
			</div>
			<div class="b-product-preview__pic b-ib" v-if="product.PRODUCT_PICTURE">
				<a :href="'#/catalog/' + product.PRODUCT_ID">
					<img :src="product.PRODUCT_PICTURE.src" />
				</a>
			</div>
			
			<div class="b-product-preview__name b-ib">
				<a :href="'#/catalog/' + product.PRODUCT_ID">
					{{product.PRODUCT_NAME}}
				</a>
			</div>
			<div class="b-product-preview__price b-ib product_price_wrap">
				<div class="price_item_val">
					{{product.PRICE_FORMAT}} <span class="b-rouble">&#8381;</span> / {{product.SKU.MEASURE_SHORT_NAME}}
				</div>
				
				<!--<div class="b-product-preview__count b-ib" v-if="showQuantity">
					<input @input="watchQuantity" type="text" class="quantity_input b-product-preview__input" v-model="quantity" />
					<button  type="button" class="b-button b-button_plus" @click="plus">+</button>
					<button  type="button" class="b-button b-button_minus" @click="minus">–</button>
				</div>-->
			</div>
			<div class="b-product-preview__buy b-ib">
				<div class="b-product-preview__incart b-ib" v-if="!showQuantity">
					<button type="button" class="b-button b-button_green add_basket_btn" @click="addToBasket">В корзину</button>
				</div>
				<div class="b-product-preview-quantity" v-if="showQuantity">
					<a href="javascript:" class="q-minus" @click="minus"><i class="el-icon-minus"></i></a>
					<input @blur="watchQuantity" type="text" class="quantity_input b-product-preview__input" v-model="quantity" />
					<a href="javascript:" class="q-plus" @click="plus"><i class="el-icon-plus"></i></a>
					<span class="input-measure">{{product.SKU.MEASURE_SHORT_NAME}}</span>
				</div>
			</div>
		</div>
	</div>
</template>
<script>

	export default {
		props: {
			product: {type: Object, required: true}
		},
		data() {
			return {
				quantity: _.toNumber(this.product.SKU.MEASURE_RATIO),
				showQuantity: false,
				inFavorite: false
			}
		},
		beforeUpdate() {
		},
		created() {
		
		},
		methods: {
			plus(){
				this.quantity = _.toNumber(this.quantity) + _.toNumber(this.product.SKU.MEASURE_RATIO);
				this.addToBasket(true);
			},
			minus(){

				this.quantity = _.toNumber(this.quantity) - _.toNumber(this.product.SKU.MEASURE_RATIO);
				if(this.quantity > 0){
					this.addToBasket(true);
				} else {
					this.quantity = 0;
					this.deleteFormBasket(this.product.PRODUCT_ID);
				}

				this.quantity = _.toNumber(this.quantity);
			},
			addToBasket(noMsg = false){
				let send = {
					quantity: this.quantity,
					product: this.product,
					sku: this.product.ID,
				};
				if(noMsg === true){
					send.notify = false;
				}
				if(this.quantity === 0){
					this.quantity = _.toNumber(this.product.SKU.MEASURE_RATIO);
				}
				
				window.MigBus.$emit('addToBasket', send);
				
				this.showQuantity = true;
			},
			deleteFormBasket(productId){
				window.MigBus.$emit('deleteFormBasket', productId);
				this.showQuantity = false;
			},
			watchQuantity(ev){
				let val = _.toNumber(ev.target.value);
				let ratio = _.toNumber(this.product.SKU.MEASURE_RATIO);
				
                if(ev.target.value.length === 0){
                    this.quantity = ratio
                } else if(val === 0){
                    this.quantity = ratio;
                } else if ((val / ratio) % 1 != 0 ) {
                    this.quantity = _.toNumber(Math.ceil(val / ratio) * ratio);
                } else 	if(val > 0){
                    this.quantity = _.toNumber(val);
                }
                this.addToBasket(true);
			},
			
			addToFavorite(){
				//POST /rest2/basket/addToFavorite {ID: this.product.PRODUCT_ID}
				if(this.inFavorite !== true){
					Vue.http.post('/rest2/basket/addToFavorite', {ID: this.product.PRODUCT_ID, SKU: this.product.ID}).then(res => {
						if(res.data.ERRORS === null){
							this.inFavorite = true;
						}
					});
				} else {
					Vue.http.post('/rest2/basket/delFavorite', {ID: this.product.PRODUCT_ID}).then(res => {
						if(res.data.ERRORS === null){
							this.inFavorite = false;
						}
					});
				}
				
				
				// console.info(this.product.PRODUCT_ID);
			}
		},
		components: {},
		computed: {},
		mounted(){

			window.MigBus.$on('on-quantity-'+ this.product.PRODUCT_ID, (productChanged) => {
				this.quantity = productChanged.QUANTITY;
			});

			let q = _.toNumber(this.product.BASKET_QUANTITY);
			if(q > 0){
				this.showQuantity = true;
			}
			
			
			if(_.isNaN(q))
				q= 1;
			
			this.quantity = q;
		
			if(!_.isEmpty(this.product.FAVORITE)){
				this.inFavorite = true;
			}
		},
		watch: {
			quantity(val){
				if(val > 0){
					this.showQuantity = true;
				}
			}
		}
	}

</script>
<style lang="scss">
	.b-products-slider__item {
		
		&:hover .b-product__star.active {
		
		}
	}
	
</style>

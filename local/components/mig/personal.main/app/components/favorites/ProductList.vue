<template>
	<div class="b_item_shop_favorite">
		<h2 class="lk__list-title">{{ sectionActiveName }}</h2>
		<div class="lk__select">
			
			<!--<a href="javascript:" v-if="!activeList" class="lk__select__link" @click="fetchProducts(false)">Выбрать все</a>-->
			<a href="javascript:" :class="['lk__select__link', {'active_checked': checkAll}]"
					@click="checkAllProducts">
				Выбрать все
			</a>
			
			<el-select class="lk__select__link" v-model="sectionSelected" v-if="sections" :disabled="!checkedProductsExists"
					placeholder="Добавить в список" @change="changeSection" no-data-text="Списки не найдены">
				<el-option v-for="item in sections" :key="item.ID" :label="item.NAME" :value="item.ID" />
			</el-select>
			<!--<a href="javascript:" class="lk__select__link">Добавить в список</a>-->
			<el-button round @click="deleteOut" :disabled="!checkedProductsExists" class="lk__select__link">
				Удалить из избранного
			</el-button>
		</div>
		
		<div  v-for="(item, code) in productItems" :key="'favorite_' + code">
			<div class="shop_favorite">
				<div class="shop_favorite--title">
					<img :src="item.PICTURE.src" v-if="item.PICTURE instanceof Object && item.PICTURE.src" />
					<p v-else>{{ item.NAME }}</p>
				</div>
				<div class="favorite_list_wrap">
					<product-favorite v-for="product in item.ITEMS" :key="item.ID" :product="product.PRODUCT"
							@check-product="checkProduct" @uncheck-product="unCheckProduct" />
				</div>
			</div>
		</div>
	
	</div>
</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	import ProductFavorite from "./ProductFavorite";
	
	export default {
		name: "product-list",
		props: {},
		data() {
			return {
				sectionSelected: '',
				disabled: true,
				checkedProducts: {},
				checkAll: false
			}
		},
		methods: {
			...mapActions('favorite', [
				'fetchProducts', 'addProductToFavorite', 'fetchSections', 'deleteOutFavorite'
			]),

			changeSection(value){
				this.addProductToFavorite({
					listId: value,
					items: this.checkedProducts
				}).then(res => {
					if(res.data.ERRORS === null){
						this.fetchSections().then(() => {
							this.fetchProducts();
						});
						this.checkedProducts = {};
					}
				});
			},
			
			checkProduct(product = {}){
				if(this.productItems.hasOwnProperty(product.SHOP_CODE)){
					let itemRow = this.productItems[product.SHOP_CODE]['ITEMS'].filter((el) => {
						return el.ELEMENT_ID === product.PRODUCT_ID;
					});
					if(itemRow[0] instanceof Object){
						Vue.set(this.checkedProducts, product.ID, {productId: product.PRODUCT_ID, favoriteId: itemRow[0]['ID']});
					}
				}
			},
			
			unCheckProduct(product = {}){
				let items = this.checkedProducts;
				delete items[product.ID];
				this.checkedProducts = Object.assign({}, items);
			},

			deleteOut(){
				if(this.checkedProductsExists){
					this.deleteOutFavorite(this.checkedProducts).then(res => {
						if(res.data.ERRORS === null){
							this.checkedProducts = {};
						}
					});
				}
			},
			_selected(checkAll = false){
				let items = {};
				let productItems = Object.assign({}, this.productItems);
				_.forEach(productItems, (shop, code) => {
					_.forEach(shop.ITEMS, (product, index) => {
						items[product.ELEMENT_ID] = {productId: product.ELEMENT_ID, favoriteId: product['ID']};
						productItems[code]['ITEMS'][index]['PRODUCT'].CHECKED = this.checkAll;
					});
				});

				if(checkAll)
					this.checkedProducts = Object.assign({}, items);

				this.$store.commit('favorite/setProductList', Object.assign({}, productItems));
			},
			checkAllProducts() {
				this.checkedProducts = {};
				this.checkAll = !this.checkAll;
				this._selected(this.checkAll);
			}
		},
		watch: {
			activeList(){
				this.checkedProducts = {};
				this.checkAll = false;
				this._selected(false);
			}
		},
		computed: {
			...mapGetters('favorite', [
				'productItems', 'sections', 'activeList'
			]),
			
			checkedProductsExists(){
				return _.size(this.checkedProducts) > 0;
			},

			sectionActiveName() {
				let index = _.findIndex(this.sections, (o) => {
					return o.ID == this.activeList;
				});
				if(index !== -1){
					return this.sections[index]['NAME'];
				}
				return 'Вне списка';
			}
		},
		created() {
			this.fetchProducts();
		},
		mounted() {

		},
		components: { ProductFavorite },
	}
</script>

<style lang="scss">
	.b_item_shop_favorite {
		padding: 10px 0;
	}
	
	.shop_favorite {
		&--title {}
	}
	
	.favorite_list_wrap {
		display: flex;
		align-content: space-around;
		flex-wrap: wrap;
	}
	
	.lk__select {
		margin: 25px 0;
		display: flex;
		align-items: center;
	}
	.lk__list-title {
		/*font-size: 24px;*/
		/*font-family: 'HelveticaNeueCyr-Thin';*/
		display: inline-block;
		margin: 25px 0 0 0;
	}
	
	.active_checked.lk__select__link:first-child:before {
		background: #32b95c;
	}
</style>
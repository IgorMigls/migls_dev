<template>
	<div>
		<transition name="custom-classes-transition"
				enter-active-class="animated fadeIn"
				leave-active-class="animated fadeOut">
			
			<div v-if="!detailData">
				<div class="product_order_item" v-for="basket in buyItems" :key="basket.ID">
					<div class="container">
						<div class="row">
							<div class="product_order_img col-4" v-if="basket.PRODUCT_DATA && basket.PRODUCT_DATA.RESIZE">
								<img class="img-responsive" :src="basket.PRODUCT_DATA.RESIZE.src" />
							</div>
							<div class="product_order_info col-8">
								<a href="javascript:" @click="openDetail(basket)">{{basket.NAME}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="detail_product container" v-else>
				<div class="row">
					<div class="img_product col" v-if="detailData.PRODUCT_DATA && detailData.PRODUCT_DATA.RESIZE">
						<img :src="detailData.PRODUCT_DATA.RESIZE.src" :class="{'replace_old_img': detailData.REPLACE}" />
					</div>
					<div class="name_product col-7">
						<span  :class="{'replace_old_name': detailData.REPLACE}">
							{{detailData.NAME}}
						</span>
					</div>
				</div>
				<hr />
				<div class="row" v-if="detailData.REPLACE">
					<div class="img_product col" v-if="detailData.REPLACE.PICTURE">
						<img :src="detailData.REPLACE.PICTURE.src" />
					</div>
					<div class="name_product col-7">
						{{detailData.REPLACE.NAME}}
					</div>
				</div>
				<div class="row" v-if="!detailData.REPLACE">
					<a href="javascript:" class="btn btn-success btn-block" @click="founded">Найдено</a>
				</div>
				<div class="row btn-group">
					<button type="button" class="btn btn-info col-6" @click="detailData = false">Назад</button>
					<button type="button" class="btn btn-warning col-6" @click="openReplace(detailData)">Заменить</button>
				</div>
				<div class="row">
					<table class="table">
						<tr>
							<td>Количество:</td>
							<td>
								<div class="row align-items-center">
									<el-input-number v-model="quantity" :min="min" :max="detailData.QUANTITY" class="col"></el-input-number>
									<div class="col-4">/<b>{{detailData.QUANTITY}}</b></div>
								</div>
								
							</td>
						</tr>
						<tr>
							<td>Цена за шт:</td>
							<td v-if="!detailData.REPLACE">{{detailData.PRICE_FORMAT}}</td>
							<td v-else>{{detailData.REPLACE.PRICE_FORMAT}}</td>
						</tr>
						<tr>
							<td>Сумма:</td>
							<td v-if="detailData.REPLACE">{{replaceSum}}</td>
							<td v-else>{{sumFormat}}</td>
						</tr>
					</table>
				</div>
				
				<!--<pre>{{detailData}}</pre>-->
			</div>
		
		</transition>
		
		<modal :show="showReplace" @close-modal="closeReplace" class-content="wrap_basket_modal">
			<transition name="custom-classes-transition"
					enter-active-class="animated slideInLeft"
					leave-active-class="animated slideOutLeft">
				
				<div class="replace_wrap" v-show="showReplace">
					<div class="replace_wrap__search">
						<el-input placeholder="Поиск" class="replace_wrap__input" v-model="searchQuery">
							<el-button slot="prepend" @click="closeReplace">Закрыть</el-button>
							<el-button slot="append" @click="searchReplace" type="success" round>Найти</el-button>
						</el-input>
						<!--<input v-model="searchQuery" type="text" placeholder="Поиск" class="replace_wrap__input" />-->
					</div>
					<scroll-bar class="replace_wrap__products" v-if="replacesItems !== null">
						<div class="product_item" v-for="product in replacesItems" :key="'rep_'+product.ID">
							<div class="product_item__img" v-if="product.PICTURE">
								<img :src="product.PICTURE.src" />
							</div>
							<div class="product_item__name">{{product.NAME}}</div>
							<div class="product_item__price">
								<span>{{product.PRICE_FORMAT}}&nbsp;<i class="fa fa-rouble"></i></span>
								<button type="button" @click="addReplace(product)" class="b-button b-button_green btn btn-success">Заменить</button>
							</div>
						</div>
					</scroll-bar>
					<div class="replace_wrap__not-found" v-else>
						-- Товары не найдены --
					</div>
					
				</div>
			
			</transition>
		</modal>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	import ScrollProducts from 'ScrollBar';
	import detail from './productDetail';
	import Modal from 'Utilities/Modal.vue';
	import ScrollBar from 'ScrollBar';

	export default {
		name: "product-list",
		data() {
			return {
				detailData: false,
				quantity: 1,
				sum: 0,
				min: 0,
				showReplace: false,
				searchQuery: '',
				replaceSum: 0,
			}
		},
		props: {},
		methods: {
			openDetail(basketItem) {
				this.detailData = basketItem;
				this.sum = this.detailData.PRICE;
				if(this.foundList !== undefined && this.foundList.hasOwnProperty(basketItem.ID)){
					this.min = _.toInteger(this.foundList[basketItem.ID]['QUANTITY']);
					this.sum = this.foundList[basketItem.ID]['SUM'];
				}

				if(this.detailData.REPLACE){
					this.replaceSum = _.toNumber(this.detailData.REPLACE.PRICE) * this.quantity;
				}
			},
			
			changeQuantity(ev){
				let val = _.toInteger(ev.target.value);
				val = val < 0 ? 1 : val;
				val = val > this.detailData.QUANTITY ? this.detailData.QUANTITY : val;

				this.quantity = val;
			},

			founded(){
				let product = Object.assign({}, this.detailData);
				product.QUANTITY = this.quantity;
				product.SUM = this.sum;
				product.SUM_FORMAT = this.sumFormat;
				
				this.found(product);
				this.detailData = false;
			},
			openReplace(product = false){
				// console.info(product.CUSTOM.SKU_ID);
				this.showReplace = true;
				this.$store.commit('replaceCurrentProduct', product)
			},
			closeReplace(){
				this.showReplace = false;
				this.$store.commit('replaceCurrentProduct', false);
				this.searchQuery = '';
			},
			addReplace(product){
				let basketItem = Object.assign({}, this.detailData);
				basketItem.REPLACE = product;
				
				this.$store.commit('setReplaceProduct', basketItem);
				this.detailData = Object.assign({}, basketItem);

				if(this.detailData.REPLACE){
					this.replaceSum = _.toNumber(this.detailData.REPLACE.PRICE) * this.quantity;
				}
				
				
				this.closeReplace();
			},
			searchReplace(){

				let send = {
					q: _.escape(this.searchQuery),
				};
				this.searchReplaceItems(send);
			},
			
			...mapActions(['found', 'searchReplaceItems']),
		},
		computed: {
			...mapGetters([
				'detail', 'replacesItems', 'foundList', 'buyItems', 'replaceTab',
			]),
			sumFormat(){
				return BX.util.number_format(this.sum, 2, '.', ' ');
			}
		},
		created() {
		},
		components: {
			ScrollProducts,
			detail,
			Modal,
			ScrollBar
		},
		watch: {
			quantity(val){
				this.sum = this.detailData.PRICE * val;
			}
		}
	}
</script>

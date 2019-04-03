<template>
	<div class="order_detail_data">
		<div v-if="detail">
			<div class="detail_title">
				<div class="detail_title-number">
					<h2>Заказ № {{ detail.ACCOUNT_NUMBER }}</h2>
				</div>
				<div class="detail_title-btn">
					<el-button type="info"
							round v-if="detail.LOCKED_BY === null || detail.LOCKED_BY == 0"
							size="small" @click="locked">
						Забрать заказ
					</el-button>
					<el-button type="success" round v-if="detail.STATUS_ID === 'DA'" size="small" @click="onDelivery">
						На доставку
					</el-button>
				</div>
			</div>
			<div class="order_item">
				<h3>Данные</h3>
				<table class="table table-sm">
					<tbody>
					<tr v-for="property in detail.PROPS" :key="property.CODE">
						<td style="text-align: right; padding-right: 10px">{{property.NAME}}:</td>
						<td>{{property.VALUE}}</td>
					</tr>
					</tbody>
				</table>
				<hr />
				<router-link :to="detail.ID + '/products'" class="btn btn-primary btn-sm">Состав заказа</router-link>
			</div>
			
			<!--<pre>{{detail.BASKET}}</pre>-->
		</div>
		<div class="detail_btn_group">
			<el-button type="primary" @click="back" icon="el-icon-arrow-left" round>Назад</el-button>
		</div>
		
	</div>

</template>

<script>
	import { mapActions, mapGetters } from 'vuex';
	// import Modal from 'Utilities/Modal.vue';
	// import ScrollBar from 'ScrollBar';
	import productTabs from './product/productTabs';
	
	export default {
		
		data(){
			return {
				showReplace: false,
				searchQuery: '',
				items: [],
				showProducts: false
			}
		},
		
		name: "order-edit",
		
		methods: {
			...mapActions([
				'getOrder', 'lockedOrder', 'deliveryOrder', 'delProduct',
				'searchReplaceItems'
			]),
			back(){
				this.$router.push('/');
			},

			locked(){
				this.lockedOrder(this.detail.ID);
			},

			onDelivery(){
				this.deliveryOrder(this.detail.ID);
			},
			
			deleteProduct(product){
				this.delProduct({
					order: this.detail.ID,
					item: product
				});
			},
			
			openReplace(product = false){
				this.showReplace = true;
				this.$store.commit('replaceCurrentProduct', product)
			},
			
			closeReplace(){
				this.showReplace = false;
				this.$store.commit('replaceCurrentProduct', false);
				this.searchQuery = '';
			},

			addReplace(product){

			},

			searchReplace(){
				
				let send = {
					q: _.escape(this.searchQuery),
				};
				this.searchReplaceItems(send);
			},

			showProductsOpen(){
				this.showProducts = true;
			},
			
			closeProducts(){
				this.showProducts = false;
			}
		},
		computed: {
			...mapGetters([
				'detail', 'replacesItems'
			])
		},
		created() {
			this.getOrder(this.$route.params.id);
			// this.items = this.replaces;
		},
		components: {
			// Modal,
			// ScrollBar,
			productTabs
		},
		watch: {
			// searchResult(items){
			// 	this.items = items;
			// }
		},
		
	}
</script>
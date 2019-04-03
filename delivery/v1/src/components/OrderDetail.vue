<template>
	<div class="detail_view">
		<q-tabs class="shadow-2" align="justify" color="yellow-6">
			<q-tab :count="getCount(products)" class="text-grey-8" slot="title" icon="list" label="Сиписок" name="list" default />
			<q-tab :count="getCount(founded)" class="text-grey-8" slot="title" icon="done_all" label="Найдено" name="founded" />
			<q-tab :count="getCount(replaces)" class="text-grey-8" slot="title" icon="report_problem" label="Рассмотреть" name="report" />
			
			<q-tab-pane name="list">
				<div class="products_wrap">
					<product-list :product="item" v-for="(item, id) in products" :key="id"
							@open-detail-product="openDetail" @delete-product="deleteItemProduct"/>
					<q-modal maximized v-model="openDetailProduct">
						<product-detail :detail="detail" @hide="closeDetail" @founded="updateFoundedAction" show-replaces />
					</q-modal>
				</div>
			</q-tab-pane>
			
			
			<q-tab-pane name="founded">
				<div class="products_wrap">
					
					<product-list :product="item" v-for="(item, id) in founded" :key="'found_' + id" :deleteAction="false"
							:showBackBtn="true"
							@open-detail-product="openDetail" @delete-product="deleteItemProduct"
							@return-to-basket="returnToBasketList"/>
					
					<q-modal maximized v-model="openDetailProduct">
						<product-detail :detail="detail" @hide="closeDetail" :show-quantity="false" />
					</q-modal>
				</div>
			</q-tab-pane>
			<q-tab-pane name="report">
				<div class="products_wrap">
					<product-list :product="item" v-for="(item, id) in replaces" :key="'replace_' + id"
							@open-detail-product="openDetail" @delete-product="deleteItemProduct"/>
					
					<q-modal maximized v-model="openDetailProduct">
						<product-detail :detail="detail" @hide="closeDetail" @founded="updateFoundedAction" show-replaces />
					</q-modal>
				</div>
			</q-tab-pane>
			
			<!--<div class="save_block">
				<q-btn class="full-width" color="secondary" @click="saveComplection">Сохранить заказ</q-btn>
			</div>-->
			
			<order-info :detailOrder="detailOrder" />
			
		</q-tabs>
		<q-fixed-position corner="bottom-right" :offset="[18, 18]" v-if="getCount(products) == 0">
			<q-btn round color="positive" icon="local_shipping" @click="setForDelivery(detailOrder.ID)" />
		</q-fixed-position>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	import {
		QTabs, QTab, QTabPane, QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
		QBtn, QIcon, QCardMedia, QModal, QFixedPosition,
		QItem, QItemSeparator, QItemMain, QList,
		Alert
	} from 'quasar';
	
	// import _ from 'lodash';
	import ProductDetail from './ProductDetail';
	import 'quasar-extras/animate/bounceInRight.css'
	import 'quasar-extras/animate/bounceOutRight.css'
	import OrderInfo from "./OrderInfo";
	
	import ProductList from './ProductList';
	
	export default {
		name: "order-detail",
		props: {},
		data() {
			return {
				openDetailProduct: false,
				detail: {}
			}
		},
		methods: {
			...mapActions([
				'getDetailOrder', 'updateFounded', 'updateReplaces', 'addToDelivery',
				'deleteProduct', 'saveComplectionOrder', 'returnToBasket'
			]),

			openDetail(product) {
				this.openDetailProduct = true;
				this.detail = product;
			},

			closeDetail() {
				this.openDetailProduct = false;
				this.detail = {};
			},

			getCount(items) {
				
				let length = Object.keys(items).length;
				
				if (length === 0)
					return '0';
				return '' + length;
			},

			setForDelivery(id) {
				this.addToDelivery(id).then(res => {
					this.$router.push('/delivery');
				});
			},

			saveComplection() {
				
				if (this.$route.name === 'ComplectationDetail') {
					let send = {
						id: this.$route.params.id,
						items: {
							products: this.products,
							founded: this.founded,
							replaces: this.replaces
						}
					};
					
					this.saveComplectionOrder(send).then(res => {
						const alert = Alert.create({
							color: 'secondary',
							html: 'Заказ сохранен',
							icon: 'thumb_up',
							enter: 'bounceInRight',
							leave: 'bounceOutRight',
							position: 'top-right',
						});
						
						setTimeout(() => {
							alert.dismiss();
						}, 2000);
					})
				}
			},
			
			updateFoundedAction(data){
				this.updateFounded(data).then(res => {
					this.getDetailOrder({id: this.$route.params.id, name: this.$route.name});
				});
			},
			
			deleteItemProduct(item){
				this.deleteProduct({
					item, orderId: this.$route.params.id
				})
			},

			returnToBasketList(item = {}) {
				this.returnToBasket(item);
			}
		},
		watch: {
		},
		created() {
			this.getDetailOrder({id: this.$route.params.id, name: this.$route.name});
		},
		beforeUpdate() {
		},
		components: {
			OrderInfo,
			QTabs, QTab, QTabPane, QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
			QBtn, QIcon, QCardMedia, QModal,
			ProductDetail, QFixedPosition,
			QItem, QItemSeparator, QItemMain, QList,
			ProductList
		},
		computed: {
			...mapGetters([
				'detailOrder', 'products', 'founded', 'replaces'
			]),

			count() {
				if (_.isEmpty(this.products))
					return '0';
				return '' + _.size(this.products);
			}
		},
		mounted() {

		}
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
	
	.detail_view {
		.q-tabs {
			width: 100%;
			
		}
	}
	
	.q-card {
		
		flex-direction: column !important;
		
		.item_product {
		
		}
		
		.item_product_body {
			
			.q-card-title {
				font-size: 1rem;
				font-weight: 600;
				line-height: 1.4rem;
			}
			
			p {
				line-height: 14px;
			}
		}
	}
	
	.save_block {
		margin: 15px 2.5%;
		width: 95%;
	}

</style>

<template>
	<div>
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
				<h3>Состав:</h3>
				<table class="table table-responsive product_table">
					<thead>
					<tr>
						<th scope="col">Товар</th>
						<th scope="col">Цена</th>
						<th scope="col">Вес,г.</th>
						<th scope="col">Кол-во</th>
						<th scope="col">Сумма</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="basket in detail.BASKET" :key="basket.ID">
						<td>
							<div class="product_data">
								<div class="product_img" v-if="basket.PRODUCT_DATA.IMG">
									<img :src="basket.PRODUCT_DATA.RESIZE.src" />
								</div>
								<div class="product_title">{{basket.NAME}}</div>
							</div>
						</td>
						<td>
							{{basket.PRICE_FORMAT}}
						</td>
						<td>
							{{basket.WEIGHT}}
						</td>
						<td>
							{{basket.QUANTITY}}
						</td>
						<td>
							{{basket.SUM_FORMAT}}
						</td>
					</tr>
					</tbody>
				</table>
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
	
	export default {
		name: "order-view",
		data() {
			return {}
		},
		methods: {
			...mapActions([
				'getOrder', 'lockedOrder', 'deliveryOrder'
			]),
			back(){
				this.$router.push('/');
			},

			locked(){
				this.lockedOrder(this.detail.ID);
			},
			
			onDelivery(){
				this.deliveryOrder(this.detail.ID);
			}
		},
		watch: {},
		created() {
			this.getOrder(this.$route.params.id);
		},
		beforeUpdate() {},
		components: {},
		computed: {
			...mapGetters([
				'detail'
			])
		},
		mounted() {
		}
	}
</script>
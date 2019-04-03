<template>
	<div class="personal_orders">
		<h1>Ваши заказы</h1>
		<div class="lk__orders" v-loading="loader">
			<div class="lk__order" v-for="item in orderList" :key="item.ID">
				<div class="lk__history__col1">
					<router-link :to="'/orders/'+item.ID" class="history__order1">Заказ №{{item.ACCOUNT_NUMBER}}, {{item.ORDER_DATE}}</router-link>
					<span class="history__order2"></span>
				</div>
				<div class="lk__history__col2">
					<div class="span history__total">Сумма </div>
					<div class="span history__sub"><nobr>{{item.ORDER_SUM}} ₽</nobr></div>
				</div>
				<div class="lk__history__col3">{{ item.ORDER_STATUS }}</div>
			</div>
		</div>
		<!--<pre>{{orderList}}</pre>-->
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "personal-orders",
		props: {},
		data() {
			return {}
		},
		methods: {
			...mapActions('orders', [
				'getOrderList'
			]),
			
			
		},
		watch: {},
		created() {
			this.getOrderList();
		},
		beforeUpdate() {
		},
		components: {},
		computed: {
			...mapGetters('orders', [
				'orderList', 'loader'
			])
		},
		mounted() {
		}
	}
</script>

<style scoped lang="scss">
	.personal_orders {
		padding-top: 42px;
		padding-bottom: 30px;
		
		.lk__history__col1 {
			width: 63%;
		}
		
		.lk__orders {
			width: 98%;
		}
		
		h1 {
			font-size: 36px;
			font-family: 'HelveticaNeueCyr-Thin';
			padding-bottom: 30px;
		}
		
		.history__sub .fa {
			font-style: normal !important;
			font-weight: 200;
		}
		
		.lk__history__col2 {
			width: 16%;
		}
		
		.lk__history__col3 {
			padding-left: 15px;
			width: 18%;
			font-size: 14px;
			display: inline-block;
			vertical-align: top;
			text-align: right;
		}
	}
</style>
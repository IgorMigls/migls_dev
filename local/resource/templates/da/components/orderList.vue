<template>
	<div>
		<h2>Заказы на сборку</h2>
		<div v-for="item in orders" :key="item.id">
			<div class="order_item">
				<div class="order_item__title">
					<div class="number">
						<span class="number_item">№ {{item.ACCOUNT_NUMBER}} от {{item.DATE_INSERT}}</span>
					</div>
					<div class="price">
						{{item.PRICE_FORMAT}} <i class="fa fa-rouble"></i>
					</div>
				</div>
				<div class="order_item__body">
					<div class="user_info">
						{{item.USER_SHORT_NAME}}, {{item.USER_EMAIL}}, {{item.DATA.PROPS.PHONE.VALUE}}
						
						<div class="delivery_data row">
							<ul>
								<li><b>{{item.DATA.PROPS.SHOP_CODE.NAME}}</b>: {{item.DATA.PROPS.SHOP_CODE.VALUE}}, {{item.DATA.PROPS.SHOP_ADDRESS.VALUE}}</li>
								<li><b>Доставка: </b>{{item.DATA.PROPS.CITY.VALUE}}, ул.{{item.DATA.PROPS.STREET.VALUE}},
								д.{{item.DATA.PROPS.HOUSE.VALUE}}, кв.{{item.DATA.PROPS.APARTMENT.VALUE}},
									подъезд {{item.DATA.PROPS.ZIP.VALUE}}, этаж {{item.DATA.PROPS.FLOOR.VALUE}}</li>
							</ul>
						</div>
					</div>
					<div class="order_action">
						<div class="btn-group" role="group" aria-label="Basic example">
							<router-link :to="'/view/' + item.ID" class="btn btn-info">
								<i class="el-icon-document" aria-hidden="true"></i>
							</router-link>
							<router-link :to="'/edit/' + item.ID" class="btn btn-warning" v-if="type !== 'delivery'">
								<i class="el-icon-edit" aria-hidden="true"></i>
							</router-link>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--<pre>{{orders}}</pre>-->
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "order-list",
		data() {
			return {}
		},
		methods: {
			...mapActions([
				'getOrderList'
			])
		},
		watch: {},
		created() {
			this.getOrderList(this.$route.params);
		},
		beforeUpdate() {
		},
		components: {},
		computed: {
			...mapGetters([
				'orders', 'type'
			])
		},
		mounted() {
		}
	}
</script>
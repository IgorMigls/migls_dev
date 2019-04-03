<template>
	<div>
		<div class="product_order_item" v-for="basket in foundList" :key="basket.ID">
			<div class="container">
				<div class="row">
					<div class="product_order_img col-4" v-if="basket.PRODUCT_DATA.RESIZE">
						<img class="img-responsive" :src="basket.PRODUCT_DATA.RESIZE.src" />
					</div>
					<div class="product_order_info col-6">
						{{basket.NAME}}<br />
						{{basket.QUANTITY}} шт.<br />
						{{basket.SUM_FORMAT}} <i class="fa fa-rouble"></i>
					</div>
					<div class="product_order_info col-2">
						<el-button type="primary" @click="deleteItem(basket)" round icon="el-icon-delete"></el-button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "founded",
		data() {
			return {}
		},
		props: {},
		methods: {
			deleteItem(item){
				this.$store.commit('deleteFound', item);
			}
		},
		computed: {
			...mapGetters([
				'foundList'
			])
		},
		created() {},
		components: {},
	}
</script>
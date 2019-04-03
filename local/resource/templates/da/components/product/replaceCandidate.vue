<template>
	<div>
		<div class="product_order_item" v-for="basket in replaceTab" :key="basket.ID">
			<div class="container">
				<div class="row">
					<div class="product_order_img col-4" v-if="basket.PRODUCT_DATA.RESIZE">
						<img class="img-responsive replace_old_img" :src="basket.PRODUCT_DATA.RESIZE.src" />
					</div>
					<div class="product_order_info col-8">
						<span class="replace_old_name">
							{{basket.NAME}}<br />
							{{basket.QUANTITY}} шт.<br />
							{{basket.SUM_FORMAT}} <i class="fa fa-rouble"></i>
						</span>
					</div>
				</div>
				<br />
				<div class="row">
					<div class="product_order_img col-4" v-if="basket.REPLACE.PICTURE">
						<img class="img-responsive" :src="basket.REPLACE.PICTURE.src" />
					</div>
					<div class="product_order_info col-8">
						{{basket.REPLACE.NAME}}<br />
						{{basket.QUANTITY}} шт.<br />
						{{sum(basket)}} <i class="fa fa-rouble"></i>
					</div>
				</div>
				<hr />
				<br /><br />
			</div>
		</div>
	</div>
</template>

<script>
	import {mapActions, mapGetters} from 'vuex';
	
	export default {
		name: "replace-candidate",
		data() {
			return {}
		},
		props: {},
		methods: {
			sum(basket){
				if(basket.REPLACE){
					return _.toNumber(basket.REPLACE.PRICE) * basket.QUANTITY;
				}

				return '';
			}
		},
		computed: {
			...mapGetters([
				'replaceTab'
			]),
			
		},
		created() {
		},
		components: {},
	}
</script>
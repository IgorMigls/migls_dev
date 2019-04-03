<template>
	<q-card v-if="item">
		<div class="row">
			<div class="item_product col-4">
				<q-card-media class="item_product_img" v-if="item.RESIZE" :style="{'background-image': 'url('+ item.RESIZE.src +')'}" />
			</div>
			<div class="item_product_body col-8">
				<q-card-title>{{item.NAME}}</q-card-title>
				<q-card-main>
					<p>Цена: {{item.BASKET_DATA.PRICE_FORMAT}}р</p>
					<p>Количество: {{item.BASKET_DATA.QUANTITY}} {{ item.MEASURE_SHORT_NAME }}</p>
					<p>Сумма: {{item.BASKET_DATA.SUM_FORMAT}}р</p>
				</q-card-main>
			</div>
		</div>
		<q-card-separator />
		<q-card-actions>
			<q-btn flat @click="openDetail()">Подробнее</q-btn>
			<q-btn v-if="deleteAction" flat @click="deleteProduct()">Удалить</q-btn>
			<q-btn v-if="showBackBtn" flat icon="replay" @click="returnToBasketList" color="primary">Вернуть в список покупок</q-btn>
		</q-card-actions>
	</q-card>
</template>

<script>
	import {
		QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
		QBtn, QIcon, QCardMedia, QModal,
	} from 'quasar';

	export default {
		name: "product-list",
		props: {
			product: {type: Object, require: true},
			deleteAction: {
				type: Boolean, default: () => {
					return true;
				}
			},
			showBackBtn: {type: Boolean}
		},
		data() {
			return {
			}
		},
		methods: {
			openDetail() {
				this.$emit('open-detail-product', this.item);
			},

			deleteProduct() {
				this.$emit('delete-product', this.item);
			},
			
			returnToBasketList() {
				this.$emit('return-to-basket', this.item);
			}
		},
		watch: {},
		computed: {
			item(){
				return this.product;
			}
		},
		created() {
		},
		mounted() {

		},
		components: {
			QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions,
			QBtn, QIcon, QCardMedia, QModal,
		},
	}
</script>
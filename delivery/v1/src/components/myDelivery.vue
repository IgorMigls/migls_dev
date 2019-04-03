<template>
	<div class="layout-padding ">
		<q-card v-for="itemOrder in myDeliveryOrders" :key="itemOrder.ID">
			<q-card-title>№ {{itemOrder.ACCOUNT_NUMBER}} от {{itemOrder.DATE_INSERT}}
				<span slot="subtitle">{{itemOrder.USER_SHORT_NAME}}, {{itemOrder.USER_LOGIN}}</span>
			</q-card-title>
			<q-card-main>
				<q-list>
					<q-item>
						<q-item-main label="Магазин:">
							{{itemOrder.DATA.PROPS.SHOP_CODE.VALUE}} {{itemOrder.DATA.PROPS.SHOP_ADDRESS.VALUE}}
						</q-item-main>
					</q-item>
					<q-item-separator />
					<q-item>
						<q-item-main label="Доставка:">{{addressFormat(itemOrder.DATA.PROPS)}}</q-item-main>
					</q-item>
				</q-list>
			</q-card-main>
			<q-card-separator></q-card-separator>
			<q-card-actions>
				<q-btn flat color="primary">
					<router-link :to="'/myDelivery/'+ itemOrder.ID">Состав заказа</router-link>
				</q-btn>
			</q-card-actions>
		</q-card>
	</div>
</template>

<script>
	import {
		QList,
		QItem,
		QItemSeparator,
		QItemMain,
		QCard,
		QCardMain,
		QCardActions,
		QCardSeparator,
		QBtn,
		QCardTitle,
	} from 'quasar'

	import { mapActions, mapGetters } from 'vuex';

	export default {
		name: "my-delivery",
		props: {},
		data() {
			return {}
		},
		methods: {
			...mapActions([
				'getMyDelivery'
			]),
			addressFormat(props) {
				let arProps = [
					props.CITY.VALUE,
					props.STREET.VALUE,
					props.HOUSE.VALUE,
					props.APARTMENT.VALUE !== '' ? 'д.' + props.APARTMENT.VALUE : '',
					props.ZIP.VALUE !== '' ? 'подъезд ' + props.ZIP.VALUE : '',
				];

				return arProps.join(', ');
			}
		},
		watch: {},
		created() {
			this.getMyDelivery();
			this.$store.commit('titlePage', 'Мои заказы на доставку');
		},
		mounted() {

		},
		components: {
			QList,
			QItem,
			QItemSeparator,
			QItemMain,
			QCard,
			QCardMain,
			QCardActions,
			QCardSeparator,
			QBtn,
			QCardTitle,
		},
		computed: {
			...mapGetters([
				'myDeliveryOrders'
			])
		}
	}
</script>

<style lang="scss">

</style>
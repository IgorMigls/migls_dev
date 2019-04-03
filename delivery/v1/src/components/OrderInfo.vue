<template>
	<q-card v-if="detailOrder">
		<q-card-title>№ {{detailOrder.ACCOUNT_NUMBER}} от {{detailOrder.DATE_INSERT}}
			<span slot="subtitle">{{detailOrder.USER_SHORT_NAME}}, {{detailOrder.USER_LOGIN}}</span>
		</q-card-title>
		<q-card-main>
			<q-list>
				<q-item>
					<q-item-main label="Магазин:">
						{{detailOrder.PROPS.SHOP_CODE.VALUE}} {{detailOrder.PROPS.SHOP_ADDRESS.VALUE}}
					</q-item-main>
				</q-item>
				<q-item-separator />
				<q-item>
					<q-item-main label="Доставка:">{{addressFormat(detailOrder.PROPS)}}</q-item-main>
					<q-item-main label="Время доставки:">{{ detailOrder.PROPS.DELIVERY_TIME.VALUE }}</q-item-main>
				</q-item>
				<q-item-separator />
				<q-item>
					<q-item-main label="Комментарий:">{{ detailOrder.USER_DESCRIPTION }}</q-item-main>
				</q-item>
				<q-item-separator />
				<q-item>
					<q-item-main label="Сумма">
						<b>Товаров: </b> {{basketPrice()}} р.<br />
						<b>Доставки: </b> {{deliveryPrice()}} р.<br />
						<b>Итого: </b> {{detailOrder.PRICE_FORMAT}} р.<br />
					</q-item-main>
				</q-item>
			</q-list>
		</q-card-main>
		<q-card-separator></q-card-separator>
	</q-card>
</template>

<script>
	import {
		QItem, QItemSeparator, QItemMain,
		QList, QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions
	} from 'quasar';
	
	import _ from 'lodash';
	import util from '../plugins/util';
	
	export default {
		name: "order-info",
		data() {
			return {}
		},
		props: {
			detailOrder: {type: Object|Boolean}
		},
		methods: {
			addressFormat(props) {
				let arProps = [
					props.CITY.VALUE,
					props.STREET.VALUE,
					props.HOUSE.VALUE,
					props.APARTMENT.VALUE !== '' ? 'д.' + props.APARTMENT.VALUE : '',
					props.ZIP.VALUE !== '' ? 'подъезд ' + props.ZIP.VALUE : '',
				];

				return arProps.join(', ');
			},

			basketPrice() {
				let val = _.toNumber(this.detailOrder.PRICE) - _.toNumber(this.detailOrder.PRICE_DELIVERY);
				return util.priceFormat(val, 2);
			},
			deliveryPrice() {
				let val =  _.toNumber(this.detailOrder.PRICE_DELIVERY);
				return util.priceFormat(val, 2);
			}
		},
		computed: {
		
		},
		created() {
		},
		components: {
			QItem, QItemSeparator, QItemMain,
			QList, QCard, QCardTitle, QCardSeparator, QCardMain, QCardActions
		},
	}
</script>

/** @var o _ */
/** @var o Vue */
"use strict";

const TEST_MODE = true;
import _ from 'lodash';
import util from '../plugins/util';

import { SessionStorage } from 'quasar';

export default {
	titlePage(state, title){
		state.titlePage = title;
	},

	setOrders(state, data){
		state.orderList = Object.assign([], data);

		_.forEach(state.orderList, (el, index) => {
			state.maps.orderList[el.ID] = index;
		});
		SessionStorage.set('orderList', state.orderList);
	},

	updateOrders(state, data){
		state.orderList = Object.assign([], state.orderList, data);
	},

	updateDetailOrder(state, data){

		state.detail = Object.assign({}, state.detail, data);


		if(data.COMPLECTATION === null || !data.hasOwnProperty('COMPLECTATION') || !data.COMPLECTATION.PRODUCT_DATA){

			state.products = Object.assign({}, data.BASKET);
			state.replaces = {};
			state.founded = {};
		} else {
			state.products = data.COMPLECTATION.PRODUCT_DATA.products || {};
			state.replaces = data.COMPLECTATION.PRODUCT_DATA.replaces || {};
			state.founded = data.COMPLECTATION.PRODUCT_DATA.founded || {};
		}


	},

	setAuth(state, userId){
		state.userId = userId;
	},

	pickingUpdate(state, id) {
		let orderList = state.orderList;
		if (_.isEmpty(orderList) || orderList === false) {
			orderList = SessionStorage.get.item('orderList');
			this.commit('setOrders', orderList);
		}

		let index = state.maps.orderList[id];
		if(index !== undefined){
			state.picking[id] = orderList[index];
			orderList.splice(index, 1);
		}

		this.commit('setOrders', orderList);
	},

	setPicking(state, items){
		state.picking = items;
	},

	setForDelivery(state, items){
		state.delivery = items;
	},

	setForMyDelivery(state, items){
		state.myDelivery = items;
	},

	updateFounded(state, product){
		let basketId = product.product.BASKET_DATA.ID;
		let basketData = Object.assign({}, product.product.BASKET_DATA);
		let item = Object.assign({}, product.product);
		let count = _.toNumber(product.count);


		basketData.SUM = product.sum;
		basketData.SUM_FORMAT = util.priceFormat(product.sum);
		basketData.QUANTITY = count;

		item.QUANTITY = _.toNumber(item.QUANTITY);

		// если найдено не всё
		if(item.QUANTITY !== count){
			// если товар уже был в найденых и мы добиваем кол-во
			if(state.founded.hasOwnProperty(basketId)){

				let ratio = product.product.MEASURE_RATIO > 0 ? product.product.MEASURE_RATIO : 1;

				item = state.replaces[basketId];

				let basketDataReplaced = Object.assign({}, item['BASKET_DATA']);
				basketDataReplaced.QUANTITY = item.BASKET_DATA.QUANTITY - ratio;
				basketDataReplaced.SUM = basketDataReplaced.QUANTITY * _.toNumber(item.BASKET_DATA.PRICE);
				basketDataReplaced.SUM_FORMAT = util.priceFormat(basketDataReplaced.SUM);

				if(basketDataReplaced.QUANTITY > 0){
					state.replaces[basketId] = Object.assign({}, item, {BASKET_DATA: basketDataReplaced});
				} else {
					delete  state.replaces[basketId];
				}


				state.founded[basketId]['BASKET_DATA']['QUANTITY'] += ratio;

			} else { // или вообще только первый раз хуячим в список найденного
				state.founded[basketId] = Object.assign({}, item, {BASKET_DATA: basketData});

				let basketDataReplaced = Object.assign({}, item.BASKET_DATA);
				basketDataReplaced.QUANTITY = item.QUANTITY - basketData.QUANTITY;
				basketDataReplaced.SUM = basketDataReplaced.QUANTITY * _.toNumber(item.BASKET_DATA.PRICE);
				basketDataReplaced.SUM_FORMAT = util.priceFormat(basketDataReplaced.SUM);
				state.products[basketId] = Object.assign({}, item, {BASKET_DATA: basketDataReplaced});
			}


		} else { // если найдено всё
			delete state.replaces[basketId];
			delete state.products[basketId];
			state.founded[basketId] = product.product;
		}
	},

	updateReplaces(state, product){
		let detail = Object.assign({}, state.detail);

		if(detail.BASKET.hasOwnProperty(product.PRODUCT_ID)){
			detail['BASKET'][product.PRODUCT_ID] = product;
			state.detail = Object.assign({}, detail);

		}

		let productUpdate = Object.assign({}, state.products[product.PRODUCT_ID]);


		// productUpdate['QUANTITY'] = productUpdate['QUANTITY'] - product.QUANTITY;
		// productUpdate['SUM'] = productUpdate['SUM'] - product.SUM;
		// productUpdate['SUM_FORMAT'] = util.priceFormat(productUpdate['SUM']);

		state.replaces = Object.assign({}, state.replaces, {
			[product.PRODUCT_ID]: productUpdate
		});
		delete state.products[product.PRODUCT_ID];
	},

	searchReplaceItems(state, data){
		if(_.isEmpty(data)){
			state.searchReplaceItems = [];
		} else {
			state.searchReplaceItems = data;
		}
	},

	deleteProduct(state, id){
		let detail = Object.assign({}, state.detail);
		delete detail['BASKET'][id];
		state.detail = Object.assign({}, detail);

		let products = Object.assign({}, state.products);
		delete products[id];
		state.products = Object.assign({}, products);
	},

	updateCurrentProduct(state, data){
		let detail = Object.assign({}, state.detail);
		let replaces = Object.assign({}, state.replaces);

		let id = data.basketItem.BASKET_DATA.ID;

		let products = state.products;

		detail.PRICE = data.price;
		detail.PRICE_FORMAT = data.priceFormat;
		detail.TOTAL_PRICE_FORMAT = data.priceFormat;
		state.detail = Object.assign({}, detail);

		if(products.hasOwnProperty(id)){
			replaces[id] = data.basketItem;
			replaces[id]['DELETED'] = products[id];

			delete products[id];

			state.products = Object.assign({}, products);
			state.replaces = Object.assign({}, replaces);
		}

	}
}

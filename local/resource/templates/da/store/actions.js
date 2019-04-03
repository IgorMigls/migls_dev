/**
 * Created by dremin_s on 14.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import Vue from 'vue';
import VueResource from 'vueResource';
import httpUtil from 'sys/httpUtil';

Vue.use(VueResource);
Vue.use(httpUtil);

const url = action => {
	return '/rest2/da/order' + action;
};
const Api = {
	getOrderList: {method: 'GET', url: url('/getOrders{data}')},
	getOrderById: {method: 'GET', url: url('/getOrderById')},
	lockedOrder: {method: 'POST', url: url('/lockedOrder')},
	deliveryOrder: {method: 'POST', url: url('/deliveryOrder')},
	delProduct: {method: 'POST', url: url('/delProduct')},
	searchReplaceItems: {method: 'POST', url: url('/searchReplaceItems')},
};
const Rest = Vue.resource('', {sessid: BX.bitrix_sessid()}, Api);

export default {
	async getOrderList({ commit }, payload){
		commit('setLoaderList', true);
		let res = await Rest.getOrderList(payload);
		if(res.data.ERRORS === null){
			commit('setOrders', res.data.DATA);
		}
		commit('setLoaderList', false);
	},

	async getOrder({ commit }, id){
		commit('setLoaderList', true);
		let res = await Rest.getOrderById({id});
		if(res.data.ERRORS === null){
			commit('setDetailOrder', res.data.DATA);
			commit('buy', res.data.DATA.BASKET);
		}
		commit('setLoaderList', false);
	},

	async lockedOrder({ commit, dispatch }, id){
		let res = await Rest.lockedOrder({ id });
		if(res.data.DATA == true){
			dispatch('getOrder', id);
		}
	},

	async deliveryOrder({ commit, dispatch }, id){
		let res = await Rest.deliveryOrder({ id });
		if(res.data.DATA == true){
			dispatch('getOrder', id);
		}
	},

	async delProduct({ commit, dispatch }, payload){
		let res = await Rest.delProduct(payload);
		if(res.data.DATA == true){
			dispatch('getOrder', payload.order);
		}
	},

	async searchReplaceItems({ commit, state }, data){
		let product = state.currentReplaceProduct;
		let send = {
			q: data.q,
			sku: product.CUSTOM.SKU_ID,
			weight: product.WEIGHT,
			basketId: product.ID
		};

		let res = await Rest.searchReplaceItems(send);
		if(res.data.ERRORS === null){
			commit('replacesItems', res.data.DATA);
		}
	},

	found({ state, commit }, item){
		let founds = BX.localStorage.get('founds');
		if(_.isEmpty(founds)){
			founds = state.found;
		}

		founds = Object.assign({}, founds, {[item.ID]: item});

		BX.localStorage.set('founds', founds, 86400 * 16);
		commit('found', item);
	},
};
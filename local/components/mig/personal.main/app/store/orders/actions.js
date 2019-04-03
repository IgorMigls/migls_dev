/** @var o _ */
/** @var o Vue */
"use strict";
import Promise from 'promise/lib/es6-extensions';

const url = (action) => {
	return '/rest2/public/personal' + action;
};

const api = {
	getOrderList: {method: 'GET', url: url('/getOrderList')},
	getDetailOrder: {method: 'GET', url: url('/getDetailOrder')},
	cancelOrder: {method: 'POST', url: url('/cancelOrder')}
};

const Rest = Vue.resource('', {sessid: BX.bitrix_sessid()}, api);
export {Rest};


export default {

	async getOrderList({ commit }){
		commit('loader', true);
		let res = await Rest.getOrderList();
		if(res.data.ERRORS === null){
			commit('orderList', res.data.DATA);
		}

		commit('loader');
	},


	async loadOrder({ commit }, id){
		commit('loader', true);
		let res = await Rest.getDetailOrder({id});
		if(res.data.ERRORS === null){
			commit('order', res.data.DATA);
		}

		commit('loader');
	},

	async cancelOrder({ commit }, id){
		return await Rest.cancelOrder({orderId: id});
	},
};

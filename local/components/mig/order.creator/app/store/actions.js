/**
 * Created by dremin_s on 03.11.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

// import Promise from 'promise/lib/es6-extensions';

const url = (action) => {
	return '/rest2/public/order' + action;
};

const api = {
	getAddressList: {method: 'GET', url: url('/getAddressList')},
	searchCity: {method: 'GET', url: url('/searchCity')},
	searchStreet: {method: 'GET', url: url('/searchStreet')},
	saveProfile: {method: 'POST', url: url('/saveProfile')},
	getBasket: {method: 'GET', url: url('/getBasket')},
	saveOrder: {method: 'POST', url: url('/makeOrder')}
};
const Rest = Vue.resource('', {sessid: BX.bitrix_sessid()}, api);

export {Rest};

export default {
	async loadAddressList({ dispatch, commit }){
		let res = await Rest.getAddressList();
		if(res.data.DATA !== null){
			dispatch('searchAddressList', res.data.DATA);
		}
	},

	async loadMap({commit, dispatch}, mapClass){
		commit('preloader', {show: true, text: 'Загрузка адресов...'});
		try {
			let ready = await mapClass.loadMap();
			commit('setMap', ready);
			dispatch('loadAddressList');
		} catch (err){
			console.info(err);
			commit('preloader', false);
		}
	},

	searchAddressList({commit, state}, list){
		_.forEach(list, (el, code) => {
			el.address = el.VALUES.CITY.VALUE + ', ' + el.VALUES.STREET.VALUE + ', д.'+ el.VALUES.HOUSE.VALUE;
		});

		state.Map.generatorSearch(list).then(res => {
			commit('setAddressItems', res);
			commit('preloader', false);
		});
	},

	async saveProfile({commit, state}){
		let res = await Rest.saveProfile(state.order.address);
		if(res.data.DATA !== null){
			commit('addAddress', res.data.DATA);
		}
		return res;
	},

	async loadBasket({commit}){
		commit('preloader', {show: true, text:'Время доставки...'});
		let res = await Rest.getBasket();
		if(res.data.DATA !== null){
			commit('setBasket', res.data.DATA);
		}
		commit('preloader', false);
	},

	async saveOrder({commit, state}){
		commit('preloader', {show: true, text:'Оформление заказа...'});
		let res = await Rest.saveOrder({order: state.order});
		if(res.data.DATA !== null){
			commit('orderNumber', res.data.DATA);
			BX.localStorage.remove('order');
		}
		commit('preloader', {show: false});
	},
}
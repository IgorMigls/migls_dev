/** @var o _ */
/** @var o Vue */
"use strict";
import Vue from 'vue';
import VueResource from 'VueResource';
import _ from 'lodash';

Vue.use(VueResource);

import {Dialog} from 'quasar';
import Env from '../../env';
import {SessionStorage} from 'quasar';

if (process.env.NODE_ENV === 'production') {
	Env.api = '/rest2/delivery/v1';
}

const url = (action = '') => {
	// return '/rest2/delivery/v1' + action;
	// return '/rest' + action;
	return Env.api + action;
};

const actions = {
	isAuth: {method: 'POST', url: url('/isAuth')},
	login: {method: 'POST', url: url('/login')},
	getNewOrders: {method: 'GET', url: url('/getNewOrders')},
	getDetailOrder: {method: 'GET', url: url('/getDetail')},
	lockOrder: {method: 'POST', url: url('/lockOrder')},
	searchReplaceItems: {method: 'POST', url: url('/searchReplaceItems')},
	cancelOrder: {method: 'POST', url: url('/cancelOrder')},
	addReplaceToBasket: {method: 'POST', url: url('/addReplaceToBasket')},
	deleteProduct: {method: 'POST', url: url('/deleteProduct')},
	addToDelivery: {method: 'POST', url: url('/sendForDelivery')},
	saveComplectionOrder: {method: 'POST', url: url('/saveComplectationOrder')},
	copyProduct: {method: 'POST', url: url('/copyProduct')},
	setMyDelivery: {method: 'POST', url: url('/addToMyOrders')},
	abortMyDelivery: {method: 'POST', url: url('/abortDelivery')},
	returnToBasketList: {method: 'POST', url: url('/returnToBasketList')},
	updateQuantityFinal: {method: 'POST', url: url('/updateQuantityFinal')},
	deleteProductFinal: {method: 'POST', url: url('/deleteProductFinal')},
};

const Rest = Vue.resource('', {}, actions);
export {Rest};

export default {
	async getNewOrders({commit}, params = {}) {
		let res = await Rest.getNewOrders(params);
		if (res.data.ERRORS === null) {
			commit('setOrders', res.data.DATA);
		}
	},

	async getDetailOrder({commit}, post) {
		let res = await Rest.getDetailOrder(post);
		if (res.data.ERRORS === null) {
			commit('updateDetailOrder', res.data.DATA);
		}
	},

	async login({commit, dispatch}, data) {
		let res = await Rest.login(data);
		let err = false;
		if (res.data.DATA === null) {
			err = 'Доступ запрещен. Возможно неверный логин или пароль'
		}
		dispatch('isAuth', err);
	},

	async isAuth({commit, dispatch}, error = false) {
		let res = await Rest.isAuth();
		if (res.data.DATA !== null) {
			commit('setAuth', res.data.DATA);
		} else {
			Dialog.create({
				title: 'Авторизация',
				message: error || '',
				form: {
					email: {
						type: 'email',
						label: 'E-mail',
						model: ''
					},
					pass: {
						type: 'password',
						label: 'Пароль',
						model: '',
						min: 6,
					},
				},
				buttons: [{
					label: 'Ok',
					handler(data) {
						dispatch('login', data);
					}
				}]
			})
		}
	},

	async lockOrder({commit, dispatch}, id) {
		let res = await Rest.lockOrder({id});
		if (res.data.ERRORS === null) {
			commit('pickingUpdate', id);
		}

		return res;
	},

	async getPicking({commit}) {
		let res = await Rest.getNewOrders({type: 'complect'});
		if (res.data.ERRORS === null) {
			commit('setPicking', res.data.DATA);
		}
	},

	async getForDelivery({commit}) {
		let res = await Rest.getNewOrders({type: 'delivery'});
		if (res.data.ERRORS === null) {
			commit('setForDelivery', res.data.DATA);
		}
	},

	updateFounded({commit, state, dispatch}, payload) {
		let storeQuantity = _.toNumber(payload.product.BASKET_DATA.QUANTITY);
		let save = {
			orderId: payload.product.BASKET_DATA.ORDER_ID,
			count: payload.count,
			storeQuantity,
			basketId: payload.product.BASKET_DATA.ID,
			basketCustomId: payload.product.ID
		};

		dispatch('saveComplectionOrder', save).then(res => {
			if(res.data.ERRORS === null){
				dispatch('getDetailOrder', {id: save.orderId});
			}
		})
	},

	updateReplaces({commit}, payload) {
		commit('updateReplaces', payload);

		let save = {
			id: payload.product.BASKET_DATA.ORDER_ID,
			items: {
				products: state.products,
				founded: state.founded,
				replaces: state.replaces
			}
		};

		// dispatch('saveComplectionOrder', save); // todo врубить
	},

	async fetchReplaces({commit, dispatch}, payload) {
		let res = await Rest.searchReplaceItems(payload);

		// console.info(res.data.DATA);
		if (res.data.ERRORS === null) {
			commit('searchReplaceItems', res.data.DATA);
			dispatch('getDetailOrder', {id: payload.orderId});
		}
	},

	async cancelOrder({commit, dispatch}, id) {
		let res = await Rest.cancelOrder({id});
		if (res.data.ERRORS === null) {
			dispatch('getPicking');
		}
	},

	async addToDelivery({dispatch}, id) {
		let res = await Rest.addToDelivery({id});
		if(res.ERRORS === null){
			// dispatch('getForDelivery');
		}

		return res;
	},

	async addReplaceToBasket({commit, dispatch}, payload) {
		let send = {
			replace: payload.replace,
			basketItem: payload.basketItem
		};

		let res = await Rest.addReplaceToBasket(send);
		if (res.data.ERRORS === null) {
			commit('updateCurrentProduct', res.data.DATA);
			dispatch('getDetailOrder', {id: payload.basketItem.BASKET_DATA.ORDER_ID});
		}

		return res;
	},

	async deleteProduct({commit, dispatch, state}, data) {
		let res = await Rest.deleteProduct({item: data.item, orderId: data.orderId});

		if(res.data.ERRORS === null){
			//id=315&name=ComplectationDetail
			dispatch('getDetailOrder', {id: data.orderId, name: 'ComplectationDetail'});
		}

	},

	async saveComplectionOrder({ commit, state }, data){

		let res = await Rest.saveComplectionOrder(data);
		if(res.data.ERRORS === null){}

		return res;
	},

	async copyProduct({ commit, state }, data){
		let save = {
			count: data.count,
			basketId: data.basketItem.BASKET_ID,
			orderId: data.basketItem.BASKET_DATA.ORDER_ID
		};

		let res = await Rest.copyProduct(save);
	},

	async getMyDelivery({ commit, state }, data){
		let res = await Rest.getNewOrders({type: 'myDelivery'});
		if (res.data.ERRORS === null) {
			commit('setForMyDelivery', res.data.DATA);
		}

		return res;
	},

	async setMyDelivery({ commit, dispatch }, id){
		let res = await Rest.setMyDelivery({id});
		if(res.ERRORS === null){
			// dispatch('getMyDelivery');
		}

		return res;
	},

	async abortMyDelivery({ commit, dispatch }, id){
		let res = await Rest.abortMyDelivery({id});
		if(res.ERRORS === null){
			// dispatch('getMyDelivery');
		}

		return res;
	},

	async returnToBasket({ dispatch }, basketItem){
		let res = await Rest.returnToBasketList({
			basketId: basketItem.BASKET_ID,
			orderId: basketItem.BASKET_DATA.ORDER_ID
		});

		if(res.data.ERRORS === null){
			dispatch('getDetailOrder', {id: basketItem.BASKET_DATA.ORDER_ID});
		}

		return res;
	},

	async updateQuantityFinal({ commit, dispatch }, data){
		return await Rest.updateQuantityFinal({
			orderId: data.BASKET_DATA.ORDER_ID,
			basketId: data.BASKET_DATA.ID,
			quantity: data.BASKET_DATA.QUANTITY
		});
	},

	async deleteProductFinal({ dispatch }, data){
		return await Rest.deleteProductFinal({ basketId: data.BASKET_DATA.ID, orderId: data.BASKET_DATA.ORDER_ID});
	},
};

/**
 * Created by dremin_s on 05.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
const url = (action = '') => {
	return '/rest2/basket' + action;
};

const api = {
	getData: {method: 'GET', url: url('/getBasketData')},
	add: {method: 'POST', url: url('/addAction')},
	update: {method: 'POST', url: url('/updateQuantityAction')},
	deleteItem: {method: 'POST', url: url('/deleteAction')},
	saveComment: {method: 'POST', url: url('/saveComment')},
	showReplace: {method: 'GET', url: url('/getReplaceItems')},
	searchReplace: {method: 'GET', url: url('/searchReplace')},
	saveReplace: {method: 'POST', url: url('/saveReplace')},
	delReplace: {method: 'POST', url: url('/delReplace')}
};
const Rest = Vue.resource('', {}, api);
export { Rest };

export default {
	async fetchBasket({ commit }){
		commit('loader', true);

		let res = await Rest.getData();
		if(!_.isEmpty(res.data.DATA.items)){
			commit('setBasket', res.data.DATA);
			commit('calculate');
		}
		commit('loader', false);
	},

	async quantityUpdate({ commit }, data){
		let res = await Rest.update({productId: data.PRODUCT_ID, quantity: data.QUANTITY});
		if(res.data.ERRORS === null){
			commit('updateQuantity', data);
			commit('calculate');
		}
	},

	async deleteItem({ commit }, item){
		let res = await Rest.deleteItem({product: item.PRODUCT_ID});
		if(res.data.ERRORS === null){
			commit('deleteProduct', item);
			commit('calculate');
		}
	},

	async addBasketItem({ commit, dispatch }, data){
		const {item, notify} = data;
		let send = {
			product: item.product.PRODUCT_ID,
			sku: item.product.ID,
			quantity: item.quantity,
			shop: item.product.SHOP_ID
		};
		let res = await Rest.add(send);
		if(res.data.ERRORS === null){
			if(item.notify !== false || !item.hasOwnProperty('notify')){
				notify({
					title: 'Товар добавлен в корзину',
					type: 'success'
				});
			}

			dispatch('fetchBasket');
		}
	},

	async getReplaces({ commit }, item){
		commit('showTimes', false);
		let send = {
			product: item.PRODUCT_ID,
			shop: item.SHOP_ID,
			sku: item.SKU_ID
		};
		commit('currentReplaceBasket', item);

		let res = await Rest.showReplace(send);
		if(res.data.ERRORS === null){
			commit('setReplaceItems', res.data.DATA);

		}
	},

	async searchReplace({ commit, state }, query){
		commit('replaceLoader', {show: true});
		let {iblockId, section, skuIblock, shopId} = state.replaces;
		let send = {
			q: query,
			iblockId,
			section,
			skuIblock,
			shop: shopId
		};

		let res = await Rest.searchReplace(send);
		if(res.data.ERRORS === null){
			commit('searchResult', res.data.DATA);
		}

		commit('replaceLoader', {show: false, loaded: true});
	},

	async saveReplace({commit, state}, payload){
		let res = await Rest.saveReplace({place: payload, id: state.currentReplaceBasket.ID});
		if(res.data.DATA !== null){
			let product = Object.assign({}, state.currentReplaceBasket);
			product.REPLACE = res.data.DATA ;
			commit('setProduct', product);
		}
	},

	async deleteReplace({commit, state}, product) {
		let res = await Rest.saveReplace({place: false, id: product.ID});
		let productData = Object.assign({}, product);
		productData.REPLACE = false;
		commit('setProduct', productData);
	}

}

/** @var o _ */
/** @var o Vue */
"use strict";
import Promise from 'promise/lib/es6-extensions';

const url = (action) => {
	return '/rest2/public/personal' + action;
};

const api = {
	getSections: { method: 'GET', url: url('/getFavoriteSections') },
	fetchProducts: { method: 'GET', url: url('/getFavoriteProducts') },
	addSection: { method: 'POST', url: url('/addFavoriteSection') },
	deleteSection: { method: 'POST', url: url('/deleteFavoriteSection') },

	addToFavorite: { method: 'POST', url: url('/addToFavorite') },

	deleteOutFavorite: {method: 'POST', url: url('/deleteOutFavorite')}
};

const Rest = Vue.resource('', { sessid: BX.bitrix_sessid() }, api);
export { Rest };


export default {

	async fetchSections({ commit }) {
		commit('loader', true);
		let res = await Rest.getSections();
		if (res.data.ERRORS === null) {
			commit('sections', res.data.DATA);
		}

		commit('loader');

		return res;
	},

	async addSection({ commit }, payload) {
		commit('loader', true);
		let res = await Rest.addSection(payload);
		if (res.data.ERRORS == null) {
			commit('setSections', res.data.DATA.ITEMS);
		}
		commit('loader');

		return res;
	},

	async deleteSection({ commit }, payload) {
		if(confirm('Вы действительно хотите удалить этот список?')){
			commit('loader', true);
			let res = await Rest.deleteSection(payload);
			if (res.data.ERRORS == null) {
				commit('setSections', res.data.DATA);
			}
			commit('loader');
		}
	},

	async fetchProducts({ commit }, listId = 0) {
		commit('loader', true);

		let res = await Rest.fetchProducts({ listId });
		if (res.data.ERRORS === null) {
			commit('setProductList', res.data.DATA);
			if(listId === false || listId === 0){
				commit('activeList', false);
			} else {
				commit('activeList', listId);
			}
		}

		commit('loader');

		return res;
	},

	async addProductToFavorite({ commit }, products = {}) {

		let res = await Rest.addToFavorite({
			elementId: products.listId,
			items: products.items
		});
		return res;
	},

	async deleteOutFavorite({ commit, dispatch, state }, data){

	    let res = await Rest.deleteOutFavorite({items: data});
	    if(res.data.ERRORS === null){
		    dispatch('fetchSections').then(res => {
		    	dispatch('fetchProducts', state.activeList);
		    });
	    }

	    return res;
	},

};
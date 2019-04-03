/** @var o _ */
/** @var o Vue */
"use strict";
import Promise from 'promise/lib/es6-extensions';
import Map from 'Utilities/maps/Map';

const url = (action) => {
	return '/rest2/public/personal' + action;
};

const api = {
	getUser: {method: 'GET', url: url('/getUser')},
	loadAddressList: {method: 'GET', url: url('/getAddressList')},
	saveAddress: { method: 'POST', url: url('/saveAddress') },
	loadAddress: { method: 'GET', url: url('/loadAddress') },
	setNewAddress: { method: 'POST', url: url('/setNewAddress') },
	saveAddressEmail: { method: 'POST', url: url('/saveAddressEmail') },
	deleteAddress: { method: 'POST', url: url('/deleteAddress') },
};

const Rest = Vue.resource('', {sessid: BX.bitrix_sessid()}, api);
export {Rest};


export default {
	loadMap({ commit, state, rootState }) {
		const mapData = new Map({
			mainComponent: BX(rootState.mainComponentMap),
			mapId: rootState.mapId,
			hiddenMap: true,
			coordUrl: '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&v=2&sessid=' + BX.bitrix_sessid(),
		});
		mapData.loadMap().then(res => {
			commit('PersonalMap', res);
		});
	},

	async loadAddressList({ commit }){
		commit('loaderItems', true);
		let res = await Rest.loadAddressList();
		if(res.data.ERRORS === null){
			commit('updateAddressList', res.data.DATA);
		}
		commit('loaderItems');
	},

	async saveAddress({ commit, dispatch }, payload) {
		let res = await Rest.saveAddress({ fields: payload });
		if (res.data.DATA !== null) {
			dispatch('loadAddressList');
			commit('updateCurrentAddress', false);
		}
		commit('loading', false);

		return res;
	},

	async searchAddress({ state, commit, dispatch }, item) {
		commit('loading', true);
		let address = `г.${item.CITY}, ${item.STREET}, д.${item.HOUSE}`;
		let res = await state.PersonalMap.search(address);
		commit('loading', false);

		return res
	},

	loadAddressSearch({ commit }, query) {
		return Rest.loadAddress({ search: query });
	},

	loadCity({ commit }, query) {
		return Rest.loadAddress({ search: query, locations: 'city' });
	},

	loadStreet({ commit }, data){
		return Rest.loadAddress({ search: data.query, locations: 'street', city: data.city});
	},

	searchAddressInArea({ state }, query) {
		return new Promise((resolve, reject) => {
			state.PersonalMap.search(query).then(res => {
				if (res > 0) {
					Rest.setNewAddress({
						address: 'change',
						set_region: 'Y',
						CORDS: state.PersonalMap.currentPolygon.geometry.get(0),
						ADDRESS: query
					}).then(result => {
						resolve(result);
					})
				} else {
					reject(false)
				}
			}).catch(err => {
				reject(false)
			});
		});
	},

	async saveAddressEmail({ commit, state }, email) {
		return await Rest.saveAddressEmail({email});
	},

	async deleteAddress({ dispatch, commit }, item){
		commit('loaderItems', true);
		await Rest.deleteAddress(item);
		await dispatch('loadAddressList');
		commit('loaderItems');
	}
};
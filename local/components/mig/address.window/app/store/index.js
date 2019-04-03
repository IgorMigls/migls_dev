"use strict";

import Vuex from 'vuex';
import actions from './actions';
import mutations from './mutations';
import getters from './getters';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		openWindow: false,
		Map: false,
		addressList: null,
		currentAddress: false,
		openEditAddress: false,
		openWindowSearch: false,
		loading: false,
		addressSaved: false,
		loadAddressSearch: [],
		mainComponent: 'ya_maps_hello',
		mapId: 'map_hello'
	},
	getters,
	mutations,
	actions
});
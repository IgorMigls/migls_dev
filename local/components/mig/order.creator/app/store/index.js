/**
 * Created by dremin_s on 03.11.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import Vuex from 'vuex';
Vue.use(Vuex);

import actions from './actions';
import mutations from './mutation';
import getters from './getters';

const store = new Vuex.Store({
	state: {
		addressItems: [],
		basketData: {},
		activeStep: 1,
		Map: {},
		preloader: {show: false, text: 'Загрузка...'},
		order: {
			address: {},
			delivery: {},
			final: {}
		},
		basket: {},
		orderNumber: false
	},
	actions,
	mutations,
	getters
});

export {store}
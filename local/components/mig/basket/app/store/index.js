/**
 * Created by dremin_s on 05.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

import Vuex from 'vuex';
import actions from './actions';
import mutations from './mutations';
import getters from './getters';

const store = new Vuex.Store({
	state: {
		basket: {
			items: false,
			sum: 0,
			sumFormat: '',
			total: 0,
		},
		loader: false,
		orderAllowed: false,
		showBasketWindow: false,
		replaces: {
			items: false,
		},
		searchResult: [],
		currentReplaceBasket: false,
		showTimes: false,
		replaceLoader: {
			show: false,
			loaded: false
		}
	},
	actions,
	mutations,
	getters
});

export default store;
"use strict";
import Vue from 'vue';
import Vuex from 'vuex';
import actions from './actions';
import mutations from './mutations';
import getters from './getters';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		titlePage: '',
		orderList: [],
		picking: [],
		delivery: [],

		products: {},
		founded: {},
		replaces: {},

		detail: false,

		userId: null,

		maps: {
			orderList: {},
			picking: {},
			delivery: {}
		},
		searchReplaceItems: [],
		myDelivery: []
	},
	getters,
	mutations,
	actions
});

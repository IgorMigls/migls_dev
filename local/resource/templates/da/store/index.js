/**
 * Created by dremin_s on 14.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'VueRouter';
import actions from './actions';
import mutations from './mutations';
import getters from './getters';

Vue.use(Vuex);
Vue.use(VueRouter);

import orderList from '../components/orderList';
import orderView from '../components/orderView';
import orderEdit from '../components/orderEdit';
import productTabs from '../components/product/productTabs';


// import deliveryList from '../components/deliveryList';


// const found = BX.localStorage.get('founds');
const found = false;

export const store = new Vuex.Store({
	state: {
		orderList: [],
		loaderList: false,
		type: false,
		detail: false,
		currentReplaceProduct: false,
		replacesItems: false,
		buy: {},
		found: !_.isEmpty(found) ? found : {},
		replace: {}
	},
	actions,
	mutations,
	getters
});

export const router = new VueRouter({
	base: '/da/',
	mode: 'history',
	routes: [
		{path: '/:type?', component: orderList},
		{path: '/view/:id?', component:orderView},
		{path: '/edit/:id', component:orderEdit},
		{path: '/edit/:id/products', component: productTabs},
		// {path: '/edit/:id/products/:productId', component: productDetail},
	]
});
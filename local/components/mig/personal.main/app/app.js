/**
 * Created by dremin_s on 06.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import HttpUtil from 'Utilities/httpUtil';
Vue.use(HttpUtil);
import VueRouter from 'VueRouter';
import {store} from './store';
import PersonalMain from './PersonalMain';

import PersonalProfile from './components/PersonalProfile';
import PersonalAddress from './components/PersonalAddress';
import PersonalCoupons from './components/PersonalCoupons';

import PersonalFavorites from './components/favorites/PersonalFavorites';
import ProductsFavorites from './components/favorites/ProductList';

import PersonalOrders from './components/PersonalOrders';
import OrderDetail from './components/OrderDetail';

Vue.use(VeeValidate);

const router = new VueRouter({
	mode: 'history',
	base: '/lk',
	// linkExactActiveClass: 'lk__link_active',
	linkActiveClass: 'lk__link_active',
	routes: [
		{path: '/', redirect: '/profile'},
		{path: '/profile', component: PersonalProfile},
		{path: '/address', component: PersonalAddress},
		{path: '/coupons', component: PersonalCoupons},
		{path: '/orders', component: PersonalOrders},
		{path: '/orders/:id?', component: OrderDetail},
		{path: '/favorite/', component: PersonalFavorites},
	],
});

$(function () {
	new Vue({
		el: '#personal_app',
		store,
		router,
		components: {
			PersonalMain
		},
		render: h => h(PersonalMain)
	})
});
import Vue from 'vue'
import VueRouter from 'vue-router'
import Env from '../env';

Vue.use(VueRouter);


function load(component) {
	// '@' is aliased to src/components
	return () => import(`@/${component}.vue`)
}

if (process.env.NODE_ENV === 'production') {
	Env.baseRouterPath = '/delivery/admin/';
}

export default new VueRouter({
	/*
	 * NOTE! VueRouter "history" mode DOESN'T works for Cordova builds,
	 * it is only to be used only for websites.
	 *
	 * If you decide to go with "history" mode, please also open /config/index.js
	 * and set "build.publicPath" to something other than an empty string.
	 * Example: '/' instead of current ''
	 *
	 * If switching back to default "hash" mode, don't forget to set the
	 * build publicPath back to '' so Cordova builds work again.
	 */

	base: Env.baseRouterPath,
	// base: '/',
	mode: 'history',
	scrollBehavior: () => ({y: 0}),

	routes: [
		{path: '/', component: load('NewOrders'),},
		{path: '/order/:id', component: load('OrderView')},

		{path: '/complect', component: load('Complectation'), name: 'ComplectationList'},
		{path: '/complect/:id', component: load('OrderDetail'), name: 'ComplectationDetail'},

		{path: '/delivery', component: load('ForDelivery')},
		{path: '/delivery/:id', component: load('OrderView'), name: 'deliveryDetail'},

		{path: '/myDelivery', component: load('myDelivery')},
		{path: '/myDelivery/:id', component: load('OrderView'), name: 'myDeliveryDetail'},

		// Always leave this last one
		{path: '*', component: load('Error404')} // Not found
	]
})

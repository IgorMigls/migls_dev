/** @var o _ */
/** @var o Vue */
"use strict";

export default {

	orderList(state) {
		return state.orderList;
	},

	products(state) {
		return state.products;
	},

	founded(state) {
		return state.founded;
	},

	replaces(state) {
		return state.replaces;
	},

	detailOrder(state) {
		return state.detail;
	},

	userId(state){
		return state.userId;
	},

	titlePage(state){
		return state.titlePage;
	},

	picking(state){
		return state.picking;
	},

	searchReplaceItems(state){
		return state.searchReplaceItems;
	},

	deliverOrders(state){
		return state.delivery;
	},

	myDeliveryOrders(state){
		return state.myDelivery;
	},
};

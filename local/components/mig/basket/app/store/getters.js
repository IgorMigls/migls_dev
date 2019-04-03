/**
 * Created by dremin_s on 05.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

export default {

	items(state){
		return state.basket.items;
	},

	totalSum(state){
		return state.basket.sum;
	},

	sumFormat(state){
		return state.basket.sumFormat;
	},

	count(state){
		return state.basket.total;
	},

	loader(state){
		return state.loader;
	},

	orderAllowed(state){
		return state.orderAllowed;
	},

	showBasketWindow(state){
		return state.showBasketWindow;
	},

	replaces(state){
		return state.replaces.items;
	},

	searchResult(state){
		return state.searchResult;
	},

	currentReplaceBasket(state) {
		return state.currentReplaceBasket;
	},

	showTimes(state){
		return state.showTimes;
	},

	replaceLoader(state) {
		return state.replaceLoader;
	},
}
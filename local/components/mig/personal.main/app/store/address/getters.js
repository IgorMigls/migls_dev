/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	/**
	 *
	 * @param state
	 * @returns {{}|state.PersonalMap}
	 * @constructor
	 */
	PersonalMap(state){
		return state.PersonalMap;
	},

	/**
	 *
	 * @param state
	 * @returns {null|[]}
	 */
	addressList(state){
		return state.addressList;
	},

	currentAddress(state){
		return state.currentAddress;
	},

	loading(state){
		return state.loading;
	},

	loaderItems(state){
		return state.loaderItems;
	}
};
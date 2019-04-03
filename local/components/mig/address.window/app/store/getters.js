/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	openWindow(state){
		return state.openWindow;
	},

	addressList(state){
		return state.addressList;
	},

	currentAddress(state){
		return state.currentAddress;
	},

	openEditAddress(state){
		return state.openEditAddress;
	},

	openWindowSearch(state){
		return state.openWindowSearch;
	},

	loading(state){
		return state.loading;
	},

	addressSaved(state){
		return state.addressSaved;
	},

	loadAddress(state){
		return state.loadAddressSearch;
	}
};
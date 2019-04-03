/**
 * Created by dremin_s on 14.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	loaderList(state){
		return state.loaderList;
	},

	orders(state){
		return state.orderList;
	},
	type(state){
		return state.type;
	},
	detail(state){
		return state.detail;
	},

	replacesItems(state){
		return state.replacesItems;
	},

	foundList(state){
		return state.found;
	},

	buyItems(state){
		return state.buy;
	},

	replaceTab(state){
		return state.replace;
	}

};
/** @var o _ */
/** @var o Vue */
"use strict";

const TEST_MODE = false;

export default {
	orderList(state, items){
		state.orderList = items;
	},

	loader(state, val = false){
		state.loader = val;
	},

	order(state, detail){
		state.order = Object.assign({}, state.order, detail);
	}
}
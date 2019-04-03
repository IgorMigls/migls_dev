/** @var o _ */
/** @var o Vue */
"use strict";

export default {

	sections(state, items = []){
		state.sections = Object.assign([], state.sections, items);
		if(items === false)
			state.sections = [];
	},

	setSections(state, items){
		state.sections = items;
	},

	loader(state, val = false){
		state.loader = val;
	},

	setProductList(state, data) {
		state.productItems = Object.assign({}, data);
	},

	activeList(state, id){
		state.activeList = id;
	},
}
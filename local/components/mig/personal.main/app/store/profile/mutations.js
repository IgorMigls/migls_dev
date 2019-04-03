/** @var o _ */
/** @var o Vue */
"use strict";

const TEST_MODE = false;

export default {
	user(state, data){
		state.user = data;
	},

	userUpdate(state, data){
		state.user = Object.assign({}, state.user, data);
	}

}
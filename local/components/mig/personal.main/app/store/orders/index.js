/**
 * Created by dremin_s on 07.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

import actions from './actions';
import mutations from './mutations';
import getters from './getters';

export default {
	actions, mutations, getters,
	namespaced: true,
	state: {
		orderList: false,
		order: false,
		loader: false,

	}
};
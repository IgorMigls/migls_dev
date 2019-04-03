/**
 * Created by dremin_s on 30.10.2017.
 */
"use strict";

import Vuex from 'vuex';
import profileModule from './profile';
import addressModule from './address';
import ordersModule from './orders';
import favoriteModule from './favorite';

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		mainComponentMap: 'ya_maps_lk',
		mapId: 'map_lk',
	},
	modules: {
		profile: profileModule,
		address: addressModule,
		orders: ordersModule,
		favorite: favoriteModule
	}
});
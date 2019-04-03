/**
 * Created by dremin_s on 01.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import {store} from './store';
import addressWindow from './components/addressWindow';

import HttpUtil from 'Utilities/httpUtil';
Vue.use(HttpUtil);

$(function () {
	new Vue({
		el: '#address_app',
		store,
		components: {
			addressWindow
		},
		methods: {
			showWindow(){
				this.$store.commit('openWindowSearch', true);
				this.$store.dispatch('showWindow', true);
			}
		},

	});
});
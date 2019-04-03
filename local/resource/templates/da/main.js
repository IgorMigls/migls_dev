/**
 * Created by dremin_s on 14.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

import Vue from 'vue';
import {
	Pagination,
	Input, InputNumber,	Checkbox, CheckboxGroup, Option, OptionGroup,
	Button, ButtonGroup, Loading,
	Tabs, TabPane,
} from 'ElementUI';

Vue.use(Pagination);
Vue.use(Input);
Vue.use(InputNumber);
Vue.use(Checkbox);
Vue.use(CheckboxGroup);
Vue.use(Option);
Vue.use(OptionGroup);
Vue.use(Button);
Vue.use(ButtonGroup);
Vue.use(Loading);
Vue.use(Tabs);
Vue.use(TabPane);
Vue.prototype.$loading = Loading.service;


import {store, router} from "./store";
import {mapActions, mapGetters} from 'vuex';

$(function () {
	const App = new Vue({
		data: {},
		methods: {
			...mapActions([
				'getOrderList'
			])
		},
		watch: {
			'$route': function (route) {
				this.$store.commit('setType', route.params.type);
				this.getOrderList(route.params);
			}
		},
		computed: {
			...mapGetters([
				'loaderList'
			])
		},
		store,
		router
	}).$mount('#order_app');
});
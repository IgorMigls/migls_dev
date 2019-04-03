/**
 * Created by GrandMaster on 26.10.17.
 */
"use strict";
import Utils from 'sys/Utils';
Vue.use(Utils);
import HttpUtil from 'Utilities/httpUtil';
Vue.use(HttpUtil);

import store from './store';
import basket from './components/basket.vue';
import {mapActions} from 'vuex';

Vue.directive('blur-element', {
	inserted: function (el, modelData) {
		if(modelData.value === 0){
			$(el)
				.css({position: 'relative'})
				.append('<div class="el-loading-mask"></div>');
		}
	}
});
const Basket = new Vue({
	data: {},
	components: {
		basket
	},
	store,
	methods: {
		...mapActions(['addBasketItem'])
	},
	mounted(){
		window.MigBus.$on('addToBasket', (data) => {
			this.addBasketItem({item: data, notify: this.$notify});
		});
	}
});
$(function () {
	if(!window.hasOwnProperty('MigBus')){
		window.MigBus = new Vue();
	}
	Basket.$mount('#basket_app');
});

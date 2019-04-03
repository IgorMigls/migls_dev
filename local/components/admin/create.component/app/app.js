/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import formCreator from './components/form.vue';
import httpUtil from 'Utilities/httpUtil';
Vue.use(httpUtil);

// import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

const app = new Vue({
	components: {
		formCreator
	}
});

$(function () {
	app.$mount('#creator_app');
});

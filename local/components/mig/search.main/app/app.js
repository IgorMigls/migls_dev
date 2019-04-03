/**
 * Created by dremin_s on 05.02.2018.
 */
/** @var o _ */
/** @var o Vue */
"use strict";
import HttpUtil from 'Utilities/httpUtil';
Vue.use(HttpUtil);

const url = (action) => {
	return '/rest2/public/address' + action;
};

const api = {
	getAddressList: { method: 'GET', url: url('/getAddressList') },
	saveAddress: { method: 'POST', url: url('/saveAddress') },
	loadAddress: { method: 'GET', url: url('/loadAddress') }
};

const Rest = Vue.resource('', { sessid: BX.bitrix_sessid() }, api);
export { Rest };

$(function () {
	new Vue({
		el: '#top_search',
		data: {
			query: '',
		},
		methods: {
			submitSearch(){
				console.info(this.query);
			},

			querySearch(queryString, cb) {

				// console.info(results);
				// cb(results);
			},
		},
		components: {
		},

		created: {

		}
	})
});
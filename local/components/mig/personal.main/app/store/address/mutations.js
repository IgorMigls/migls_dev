/** @var o _ */
/** @var o Vue */
"use strict";

const TEST_MODE = false;

export default {
	PersonalMap(state, Map){
		state.PersonalMap = Map;
	},

	updateAddressList(state, items){
		state.addressList = items.map(el => {
			let val = Object.assign({}, el);
			let item = val.VALUES;
			val.ADDRESS_FORMAT = `г.${item.CITY.VALUE}, ${item.STREET.VALUE}, д.${item.HOUSE.VALUE}, кв.${item.APARTMENT.VALUE}`;
			return val;
		});
	},
	
	updateCurrentAddress(state, data){
		state.currentAddress = data;
	},

	loading(state, val){
		state.loading = val;
	},

	loaderItems(state, val = false){
		state.loaderItems = val;
	}
}
/** @var o _ */
/** @var o Vue */
"use strict";

const TEST_MODE = false;

export default {
	openWindow(state, show){
		state.openWindow = show;
	},

	Map(state, map){
		state.Map = map;
	},

	addressList(state, items){
		state.addressList = items.map(el => {
			let val = Object.assign({}, el);
			let item = val.VALUES;
			val.ADDRESS_FORMAT = `г.${item.CITY.VALUE}, ${item.STREET.VALUE}, д.${item.HOUSE.VALUE}, кв.${item.APARTMENT.VALUE}`;
			return val;
		});
	},

	currentAddress(state, item) {
		state.currentAddress = Object.assign({}, item);
	},

	openEditAddress(state, val){
		state.addressSaved = false;
		state.openEditAddress = val;
	},

	openWindowSearch(state, val){
		state.openWindowSearch = val;
	},

	loading(state, val){
		state.loading = val;
	},

	addressSaved(state, val){
		state.addressSaved = val;
	},

	loadAddress(state, items){
		state.loadAddressSearch = items;
	},

	setMapContainer(state, data){
		state.mapId = data.mapId;
		state.mainComponent = data.mainComponent;
	}
}
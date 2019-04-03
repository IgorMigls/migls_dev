/**
 * Created by GrandMaster on 03.11.17.
 */
/** @var o _ */
/** @var o $ */
"use strict";

export default {
	setStep(state, step){
		BX.localStorage.set('order', state.order, 3600);
		state.activeStep = step;
	},
	setMap(state, map){
		state.Map = map;
	},
	setAddressItems(state, list){
		state.addressItems = list;
	},
	addAddress(state, item){
		let index = _.findIndex(state.addressItems, {ID: item.ID});
		if(index > -1){
			state.addressItems[index] = item;
		} else {
			state.addressItems.push(item);
		}
	},

	preloader(state, prop = false){

		if(prop === false)
			prop = {show: false};

		state.preloader = Object.assign({}, state.preloader, prop);
	},

	addOrderData(state, data){
		state.order = Object.assign({}, state.order, data);
	},

	setBasket(state, data){
		state.basket = data;
	},

	setDelivery(state, data){
		state.order.delivery[data.shopCode] = data;
	},

	orderFormLocalStorage(state){
		let data = BX.localStorage.get('order');
		if(data !== null){
			state.order = data;
		}
	},

	orderNumber(state, num){
		state.orderNumber = num;
	},
}
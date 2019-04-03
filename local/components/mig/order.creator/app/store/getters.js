/**
 * Created by GrandMaster on 03.11.17.
 */
/** @var o _ */
/** @var o $ */
"use strict";

export default {
	activeStep(state){
		return state.activeStep;
	},

	activeComponent(state){
		let components = {1:'step-one', 2:'step-two', 3:'step-three'};
		if(components.hasOwnProperty(state.activeStep)){
			return components[state.activeStep];
		}

		return 'error'
	},

	map(state){
		return state.map;
	},

	addressList(state){
		return _.isEmpty(state.addressItems) ? false : state.addressItems;
	},

	preloader(state){
		return state.preloader;
	},

	orderAddress(state){
		return state.order.address;
	},

	basketData(state){
		return state.basket;
	},

	delivery(state){
		return state.order.delivery;
	},

	orderNumber(state){
		return state.orderNumber;
	}
}
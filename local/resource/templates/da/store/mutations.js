/**
 * Created by dremin_s on 14.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	setOrders(state, payload) {
		state.orderList = payload;
	},

	setLoaderList(state, show = false){
		state.loaderList = show;
	},

	setType(state, type){
		state.type = type;
	},

	setDetailOrder(state, data){
		state.detail = data;
	},

	replaceCurrentProduct(state, product){
		state.currentReplaceProduct = product;
	},

	replacesItems(state, data){
		state.replacesItems = data;
	},

	found(state, item){
		const buyLocal = BX.localStorage.get('buyItems');

		state.found = Object.assign({}, state.found, {[item.ID]: item});

		let buyItems = Object.assign({}, state.buy);


		if(buyItems.hasOwnProperty(item.ID)){
			if(buyItems[item.ID].QUANTITY === item.QUANTITY){
				delete buyItems[item.ID];
				// delete replaceLocal[item.ID];
				// delete state.replace[item.ID];

			} else {
				buyItems[item.ID].QUANTITY = buyItems[item.ID].QUANTITY - item.QUANTITY;
				// if(state.detail.BASKET.hasOwnProperty(item.ID)){
				// 	replaceLocal[item.ID] = Object.assign({}, item);
				// 	replaceLocal[item.ID]['QUANTITY'] = state.detail.BASKET[item.ID]['QUANTITY'] - item.QUANTITY;
				// 	replaceLocal[item.ID]['SUM'] = replaceLocal[item.ID]['QUANTITY'] * _.toNumber(replaceLocal[item.ID]['PRICE']);
				// }
			}


		}

		state.buy = Object.assign({}, buyItems);
	},

	buy(state, items){
		const buyLocal = BX.localStorage.get('buyItems');
		if(!_.isEmpty(buyLocal)){
			_.forEach(buyLocal, (el, id) => {
				if(!items.hasOwnProperty(id)){
					items[id] = el;
				}
			});
		} else {
			BX.localStorage.set('buyItems', items, 86400 * 16);
		}
		state.buy = items;
	},

	deleteFound(state, item){
		let dataItem = Object.assign({}, item);
		let found = Object.assign({}, state.found);
		let buy = Object.assign({}, state.buy);

		if(found.hasOwnProperty(item.ID)){
			state.detail.BASKET[item.ID]['QUANTITY'] = state.detail.BASKET[item.ID]['QUANTITY'] + _.toInteger(item.QUANTITY);
			delete found[item.ID];
			if(buy.hasOwnProperty(item.ID)){
				// buy.QUANTITY = state.buy.QUANTITY + _.toInteger(item.QUANTITY);
				buy.SUM = state.detail.BASKET[item.ID]['QUANTITY'] * _.toNumber(state.detail.BASKET[item.ID]['PRICE']);
			} else {
				buy[item.ID] = dataItem;
			}
		}

		state.buy = Object.assign({}, state.buy, buy);
		state.found = Object.assign({}, found);
	},

	setReplaceProduct(state, item){
		let buy = Object.assign({}, state.buy);
		buy[item.ID] = item;
		state.buy = Object.assign({}, state.buy, buy);

		let replaceLocal = BX.localStorage.get('replaceItems');
		if(replaceLocal === null)
			replaceLocal = {};


		replaceLocal[item.ID] = item;

		// BX.localStorage.set('replaceItems', replaceLocal, 86400 * 7);
		// todo вкключить запись в localStorage
		state.replace = Object.assign({}, state.replace, replaceLocal);
	},
}

/**
 * Created by dremin_s on 05.12.2017.
 */
/** @var o _ */
/** @var o Vue */
"use strict";

export default {
	/**
	 * Установка корзины из аякса
	 * @param state
	 * @param data Object
	 */
	setBasket(state, data){
		state.basket = data;
		state.replaceLoader = {show: false, loaded: false}
	},

	/**
	 * Прелоадер для корзины
	 * @param state
	 * @param flag Boolean
	 */
	loader(state, flag = false){
		state.loader = flag;
	},

	/**
	 * Пересчет корзины по изменениям товара,
	 * вызывать всегда после добавления/удадения товара или изменения количества
	 * @param state
	 */
	calculate(state){
		let totalSum = 0, allowedShop = 0, totalCount = 0;

		_.forEach(state.basket.items, (el, code) => {
			let sumShop = 0;

			_.forEach(el.BASKET, (item, id) => {
				if(el.CLOSED < 3){
					//sumShop += _.toInteger(item.QUANTITY) * _.toNumber(item.PRICE);
            		sumShop += parseFloat(item.QUANTITY) * _.toNumber(item.PRICE);
				}

				totalCount++;
			});


			if(sumShop >= 1000){
				allowedShop++;
			} else if(el.CLOSED < 3){
				allowedShop--
			}

			state.basket.items[code]['MESSAGE']['danger']['show'] = sumShop < 1000;
			state.basket.items[code]['SUM'] = sumShop;
			state.basket.items[code]['SUM_FORMAT'] = BX.util.number_format(sumShop, 2, '.', ' ');

			totalSum += sumShop;
		});

		state.orderAllowed = allowedShop > 0;
		state.basket.sum = totalSum;
		state.basket.sumFormat = BX.util.number_format(totalSum, 2, '.', ' ');

		if(_.size(state.basket.items) === 0){
			state.basket.items = false;
			state.showBasketWindow = false;
		}
		state.basket.total = totalCount;
	},

	/**
	 * Удаление товара
	 * @param state
	 * @param item
	 */
	deleteProduct(state, item){
		if(state.basket.items.hasOwnProperty(item.SHOP_CODE)){
			delete state.basket.items[item.SHOP_CODE]['BASKET'][item.PRODUCT_ID];
			if(_.size(state.basket.items[item.SHOP_CODE]['BASKET']) === 0){
				delete state.basket.items[item.SHOP_CODE];
			}
		}
	},

	/**
	 * Обновление товара
	 * @param state
	 * @param item
	 */
	setProduct(state, item){
		let basket = Object.assign({}, state.basket.items);
		if(basket.hasOwnProperty(item.SHOP_CODE)){
			basket[item.SHOP_CODE]['BASKET'][item.PRODUCT_ID] = Object.assign({}, basket[item.SHOP_CODE]['BASKET'][item.PRODUCT_ID], item);
		}
		state.basket.items = Object.assign({}, basket);
	},

	/**
	 * Обновление количества
	 * @param state
	 * @param item
	 */
	updateQuantity(state, item){
		let basket = Object.assign({}, state.basket.items);
		if(basket.hasOwnProperty(item.SHOP_CODE)){
			basket[item.SHOP_CODE]['BASKET'][item.PRODUCT_ID]['QUANTITY'] = _.toNumber(item.QUANTITY);
		}
		state.basket.items = Object.assign({}, basket);
		state.replaceLoader = {show: false, loaded: false}
	},

	/**
	 * Показ/скрытие корзины
	 * @param state
	 * @param show
	 */
	showBasketWindow(state, show = false){
		state.showBasketWindow = show;
	},

	setReplaceItems(state, data){
		state.replaces = data;
	},

	searchResult(state, results){
		state.searchResult = results;
	},

	addReplace(state, payload){

	},

	currentReplaceBasket(state, item){
		state.currentReplaceBasket = item;
	},

	showTimes(state, shopCode = false){
		if(state.basket.items.hasOwnProperty(shopCode)){
			state.replaces = false;
			setTimeout(() => {
				state.showTimes = state.basket.items[shopCode];
			}, 200);
		} else {
			state.showTimes = false;
		}
	},

	replaceLoader(state, data) {
		state.replaceLoader = data;
	},
}
/**
 * Created by dremin_s on 14.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import { combineReducers, createStore, applyMiddleware, compose } from 'redux';

const myMiddleware = (store) => {
	return (next) => {
		return (action) => {
			// action.params = store.getState().Params;
			return next(action);
		};
	};
};

const startState = {
	step1: {
		active: true,
		profiles: [],
		addressList: [],
		checkTypeAddress: 'new',
		props: {},
		suggestionsAddress: [],
		addressItems: {
			PROFILE_NAME: '',
			PHONE: '',
			ZIP: ''
		},
		isValid: false,
		fields: null
	},
	step2: {
		active: false,
		isValid: false,
		SHOPS: {},
		DAYS_LIST: {}
	},
	step3: {
		active: false
	},
	Data: {
		activeStep: 1,
		// testMode: true
	}
};

export default function configureStore(initialState = startState) {

	const Store = {
		Data(state = initialState.Data, action){
			switch (action.type) {
				case'SET_NEXT_STEP':
					return {...state, activeStep: action.step,};

				default:
					return state;
			}
		},
		step1(state = initialState.step1, action){
			let addressItems = state.addressItems;

			switch (action.type) {
				case'GET_ADDRESS_LIST':
					return {...state, profiles: action.items, addressList: action.address, props: action.props};

				case 'SET_ADDRESS_TYPE':
					$.each(addressItems, (code, el) => {
						addressItems[code] = '';
					});

					return {...state, checkTypeAddress: action.value, addressItems, profileId: null};

				case 'SET_ADDR_VALUE':
					addressItems[action.name] = action.value;
					return {...state, addressItems};

				case 'SET_VALID_FROM':
					return {...state, isValid: action.isValid, form: action.form};

				case'SET_DATA_STEP1':
					let activeStep = action.active;
					if (activeStep === undefined || activeStep === null)
						activeStep = false;

					let stepFields = state.fields;
					if (action.hasOwnProperty('data')) {
						stepFields = action.data;
					}

					return {...state, active: activeStep, fields: stepFields};

				case 'SET_PROFILE_VALUES':
					return {...state, addressItems: action.addressItems};

				case 'SET_TEST_STEP_1':
					return Object.assign({}, state, action.data);

				case 'SET_PROFILE_ID':
					return {...state, profileId: action.id};

				default:
					return state;
			}
		},
		step2(state = initialState.step2, action){

			switch (action.type) {

				case 'ACTIVE_STEP2':
					return {...state, active: action.active};

				case 'ORDER_DATA':
					return Object.assign({}, state, action.orderData);

				case 'SET_CURRENT_SHOP':
					let curShop = {times: {}, data: {}};
					if(state.SHOPS.hasOwnProperty(action.code)){
						curShop.data = state.SHOPS[action.code];
					}
					if(state.DAYS_LIST.hasOwnProperty(action.code)){
						curShop.times = state.DAYS_LIST[action.code];
					}
					return {...state, currentShop: curShop};

				case 'SET_TIME':
					let shops = state.SHOPS, code = action.arTime.shop.data.SHOP_CODE;
					if(shops.hasOwnProperty(code)){
						shops[code]['DELIVERY'] = {
							timestamp: action.arTime.shop.times[action.arTime.tab]['TIMESTAMP'],
							name: action.arTime.shop.times[action.arTime.tab]['NAME'],
							from: action.arTime.item.PROPERTY_TIME_FROM_VALUE,
							to: action.arTime.item.PROPERTY_TIME_TO_VALUE,
							price: action.arTime.item['PROPERTY_PRICE_VALUE']
						};
					}

					return {...state, SHOPS: shops};

				case 'SET_VALID_STEP_2':
					return {...state,  isValid: action.val};

				case 'SET_TEST_STEP_2':
					return Object.assign({}, state, action.data);

				default:
					return state;
			}
		},
		step3(state = initialState.step3, action){

			switch (action.type) {
				case 'ACTIVE_STEP3':
					return {...state, active: action.active};

				case 'ORDER_INFO':
					return {...state, order: action.order};

				default:
					return state;
			}
		},
		Loader(state = {show: false, text: false}, action){
			switch (action.type) {
				case 'SWITCH_LOADER':
					return {...state, show: action.show, text: action.text};
				default:
					return state;
			}
		},
	};

	let storeBuilder, env = process.env.NODE_ENV;
	const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

	if (env === 'dev') {
		storeBuilder = createStore(
			combineReducers(Store),
			composeEnhancers(applyMiddleware(myMiddleware))
		);
	} else {
		storeBuilder = createStore(
			combineReducers(Store),
			composeEnhancers(applyMiddleware(myMiddleware))
		);
	}

	return storeBuilder;
}

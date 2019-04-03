import ajaxService from 'ajaxService';
import {combineReducers} from 'redux';
import is from 'is_js';

/* ============================================ reducers ============================================================ */
const State = {
	cities: [],
};

const Model = (state = State, action) => {
	switch (action.type) {
		case 'GET_CITIES':
			return {...state, cities: action.Sections};
		default:
			return state;
	}
};

const rootReducer = combineReducers({Form: Model});

export {rootReducer};
/* ================================================================================================================== */


/* ===================================== actions ==================================================================== */
const Ajax = new ajaxService({
	baseURL: '/rest/help'
});

const FormCtrl = {
	mapStateToProps (state) {
		return state;
	},

	mapDispatchToProps (dispatch) {
		return {
			getCity: (parent = false) => {

			},
		}
	}
};
export {FormCtrl};
/* ================================================================================================================== */
import ajaxService from 'ajaxService';
import {combineReducers} from 'redux';
import is from 'is_js';

/* ============================================ reducers ============================================================ */
const State = {
	Sections: [],
};

const Model = {
	Help(state = State, action){
		switch (action.type) {
			case 'GET_MAIN_SECTIONS':
				return {...state, Sections: action.Sections};
			default:
				return state;
		}
	}
};

const rootReducer = combineReducers({
	Help: Model.Help,
});

export {rootReducer};
/* ================================================================================================================== */


/* ===================================== actions ==================================================================== */
const Ajax = new ajaxService({
	baseURL: '/rest/help'
});

const HelperCtrl = {
	mapStateToProps (state) {
		return state;
	},

	mapDispatchToProps (dispatch) {
		return {
			getSections: (parent = false) => {
				Ajax.get('/getSections', {params: {sectionParent: parent}}).then(result => {
					if (result.data.DATA != null) {
						return dispatch({type: 'GET_MAIN_SECTIONS', Sections: result.data.DATA});
					}
				});
			},
		}
	}
};
export {HelperCtrl};
/* ================================================================================================================== */
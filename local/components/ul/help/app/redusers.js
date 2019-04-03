import {combineReducers} from 'redux';

const State = {
	Sections: [],
	ElementList: [],
	CurrentElement: {},
	CurrentSection: {}
};

const Model = {
	Help(state = State, action){
		switch (action.type) {
			case 'SET_PARAMS_COMPONENT':
				return {...state, arParams: action.params};
			case 'GET_ELEMENTS':
				return {...state, ElementList: action.ElementList};
			case 'GET_SECTION':
				return {...state, CurrentSection: action.CurrentSection};
			case 'GET_MAIN_SECTINOS':
				return {...state, Sections: action.Sections};
			default:
				return state;
		}
	}
};

const rootReducer = combineReducers( {
	Help: Model.Help,
});

export default rootReducer;
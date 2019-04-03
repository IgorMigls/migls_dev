import 'es6-shim';
import {createStore, applyMiddleware} from 'redux';
import {Provider} from 'react-redux';
import {rootReducer} from './controller';
import Form from './Form';

const store = createStore(rootReducer);

$(function () {
	ReactDOM.render(
		<Provider store={store}>
			<Form />
		</Provider>,
		BX('help_form_hover')
	);
});
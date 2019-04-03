// import 'es6-shim';
import {createStore, applyMiddleware} from 'redux';
import {Provider} from 'react-redux';
import HelpApp from './Help';
import {rootReducer as Reducers} from './controller';
import thunk from 'redux-thunk'

const store = createStore(Reducers);

$(function () {
	ReactDOM.render(
		<Provider store={store}>
			<HelpApp />
		</Provider>,
		BX('helper_app')
	);
	setTimeout(() => {
		$(".acord-main").navgoco({accordion: true});
		$('.acord__link').click(function(event) {

			$('.acord__item-2').removeClass('jsAcordActive');
			if ($('.acord__item').hasClass('open')) {
				$('.b-mac').addClass('fadeOut');
			}
			else {
				$('.b-mac').removeClass('fadeOut');
			}
			$(this).parent().addClass('open');
		});
		$('.acord__item-2').click(function(event) {
			$('.acord__item-2').removeClass('jsAcordActive');
			$(this).addClass('jsAcordActive');
		});
	}, 500);
});
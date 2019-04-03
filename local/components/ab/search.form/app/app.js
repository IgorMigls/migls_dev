/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import Ajax from 'preloader/RestService';
import debounce from 'lodash/debounce';
// import Autosuggest from 'autocomplite/Autosuggest';
import AutosuggestBase from 'react-autosuggest';

const Rest = new Ajax({
	baseURL: '/rest/search/result'
});

const getSuggestionValue = suggestion => suggestion.TITLE;
const renderSuggestion = suggestion => {
	return (
		<div className="suggestion-content">
			<div className="img_product">
				{suggestion.IMG instanceof Object && suggestion.IMG.hasOwnProperty('src') &&
				<img src={suggestion.IMG.src} />
				}
			</div>
			<div className="title_product_s">{suggestion.TITLE}</div>
			<div className="price_product_s">
				{suggestion.hasOwnProperty('SKU') && suggestion.SKU.hasOwnProperty('PRICES') &&
					<span className="prices_search_block">
						{suggestion.SKU.PRICES.PRICE} <span className="b-rouble">&#8381;</span>
						<span className="prices_search_btn">В корзину</span>
					</span>
				}
			</div>
		</div>
	)
};

const shouldRenderSuggestions = (value) => {
	return value.trim().length > 2;
};

class Autosuggest extends AutosuggestBase{
	constructor(props){
		super(props);

		this.onSuggestionClick = this.onSuggestionClick.bind(this);
	}

	onSuggestionClick(event){
		const { alwaysRenderSuggestions, focusInputOnSuggestionClick } = this.props;
		const { sectionIndex, suggestionIndex } = this.getSuggestionIndices(
			this.findSuggestionElement(event.target)
		);
		const clickedSuggestion = this.getSuggestion(sectionIndex, suggestionIndex);
		const clickedSuggestionValue = this.props.getSuggestionValue(
			clickedSuggestion
		);

		this.maybeCallOnChange(event, clickedSuggestionValue, 'click');
		this.onSuggestionSelected(event, {
			suggestion: clickedSuggestion,
			suggestionValue: clickedSuggestionValue,
			suggestionIndex: suggestionIndex,
			sectionIndex,
			method: 'click'
		});

		if (!alwaysRenderSuggestions) {
			this.closeSuggestions();
		}

		if (focusInputOnSuggestionClick === true) {
			this.input.focus();
		} else {
			this.onBlur();
		}

		setTimeout(() => {
			this.justSelectedSuggestion = false;
		});
	};

}

class Search extends React.Component {
	constructor(props) {
		super(props);

		this.state = {
			query: '',
			results: [],
			value: '',
		};

		this.$input = $('#' + props.inputId);

		this.load = false;
		this.onChange = this.onChange.bind(this);
		this.onSuggestionsFetchRequested = this.onSuggestionsFetchRequested.bind(this);
		this.onSuggestionsClearRequested = this.onSuggestionsClearRequested.bind(this);
		this.onSuggestionSelected = this.onSuggestionSelected.bind(this);
	}

	onChange(event, {newValue}) {

		this.setState({
			value: newValue
		});
	};

	onSuggestionsFetchRequested({value}) {
		let searchResult = debounce(this.getResult.bind(this), 300);
		searchResult(value);
	};

	// Autosuggest will call this function every time you need to clear suggestions.
	onSuggestionsClearRequested() {
		this.setState({
			results: []
		});
	};

	getResult(query) {
		if (!this.load) {
			this.load = true;
			Rest.get('/getResult', {params: {q: query, shop: this.props.shop}}).then(res => {
				let data = res.data.DATA;
				this.setState({results: data});
				this.load = false;

			});
		}
	}

	onSuggestionSelected(ev, { suggestion, suggestionValue, suggestionIndex, sectionIndex, method }) {
		// ev.stopPropagation();
		console.info(this.state.value);


		return false;
	};

	render() {
		const {value, results} = this.state;

		const inputProps = {
			placeholder: 'Например, говядина для шашлыка, 3 кг',
			value,
			onChange: this.onChange,
			size: 40,
			autoComplete: 'off',
			className: 'b-header-search__input',
			name: 'q',
			defaultValue: window.location.search
		};

		return (
			<form method="GET" action={this.props.action} autoComplete="off">
				<div className="b-header-search__center b-ib">
					<Autosuggest
						suggestions={results}
						onSuggestionsFetchRequested={this.onSuggestionsFetchRequested}
						onSuggestionsClearRequested={this.onSuggestionsClearRequested}
						getSuggestionValue={getSuggestionValue}
						renderSuggestion={renderSuggestion}
						shouldRenderSuggestions={shouldRenderSuggestions}
						onSuggestionSelected={this.onSuggestionSelected}
						inputProps={inputProps}
						focusInputOnSuggestionClick={false}
						alwaysRenderSuggestions={true}
					/>
				</div>
				<button className="b-button b-button_green float_r" name="s" type="submit">Найти</button>
			</form>
		);
	}
}

$(function () {
	let $section = $('.top_search_section');
	if ($section.length > 0) {
		let prop = {
			shop: $section.data('shop'),
			inputId: $section.data('input'),
			action: $section.data('action')
		};

		ReactDOM.render(<Search {...prop} />, $section[0]);
	}
});
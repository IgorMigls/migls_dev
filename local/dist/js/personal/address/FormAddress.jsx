define(function (require) {
	'use strict';

	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');
	var ReactDOM = require('dom');

	// var NoAddressWin = require('jsx!personal/address/NoAddressWin');
	// var stateNoAddressWin = ReactDOM.render(<NoAddressWin/>, BX('render_no_address'));

	return React.createClass({

		getInitialState: function () {
			return {
				visible: false,
				validForm: false,
				Fields: {},
				Result: {},
				profileId: null,
				currentProfile: null
			}
		},

		componentWillUpdate: function (prop, state) {
			var magnificPopup = $.magnificPopup.instance;

			if (state.visible === true) {
				magnificPopup.open({
					items: {
						src: '#change_address_popup',
						type: 'inline',
					},
					enableEscapeKey: true,
					showCloseBtn: false,
					closeOnBgClick: true,
					mainClass: 'show_address_change',
				});
			}
			if (state.profileId > 0) {
				setTimeout(function () {
					magnificPopup.close();
					state.profileId = null;
				}, 2000);
			}
		},

		componentDidUpdate: function (prop, state) {

			if (is.propertyDefined(this.state.Fields.CITY, 'VALUE')) {
				$('#city_field').val(this.state.Fields.CITY.VALUE);
			} else if (is.propertyDefined(this.props.FieldVal, 'CITY')) {
				$('#city_field').val(this.props.FieldVal.CITY);
			}

			if (is.propertyDefined(this.state.STREET, 'VALUE')) {
				$('#street_field').val(this.state.STREET.VALUE);
			} else if (is.propertyDefined(this.props.street, 'VALUE')) {
				if (this.props.street.VALUE != null)
					$('#street_field').val(this.props.street.VALUE);
			}
		},

		componentDidMount: function () {
			Service.action('getData').get().then(function (result) {
				if (result.DATA.props != null) {

					if (is.empty(result.DATA.props.PROFILE_NAME) || is.undefined(result.DATA.props.PROFILE_NAME)) {

						if (is.propertyDefined(this.props.FieldVal, 'PROFILE_NAME') && !is.empty(this.props.FieldVal.PROFILE_NAME)) {
							result.DATA.props.PROFILE_NAME = this.props.FieldVal.PROFILE_NAME;
						} else {
							result.DATA.props.PROFILE_NAME = {VALUE: ''}
						}

					}

					this.setState({Fields: result.DATA.props});
					if (this.props.city != undefined) {
						$('#city_field').val(this.props.city.VALUE);
					}

					$('#city_field').autocomplete({
						source: function (request, response) {
							var post = {
								query: request.term,
								count: 10,
								from_bound: {value: "city"},
								to_bound: {value: "settlement"},
							};
							$.post('/service/UL/Suggestions/getAddress', JSON.stringify(post), function (result) {
								var sResult = [];
								if (result.DATA != null && !is.undefined(result.DATA.suggestions) && is.array(result.DATA.suggestions)) {
									$.each(result.DATA.suggestions, function (k, arItem) {
										sResult.push(arItem);
									});
									response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

									$('.ui-autocomplete').css({'z-index': '1080'});
								}
							}, 'json');
						},
						minLength: 3,
					}).bind("autocompleteselect", function (ev, ui) {

						var Fields = this.state.Fields;
						Fields[ev.target.name]['VALUE'] = ui.item.label;

						this.setState({Fields: Fields});
					}.bind(this));

					$('#city_field').on('click', function () {
						$(this).autocomplete('search');
					});

					$('#street_field').autocomplete({
						source: function (request, response) {

							var tmpCity = $('#city_field').val().split(',');
							var locationSearch = {};

							if (tmpCity.length == 1) {
								locationSearch = {city: tmpCity[0].trim()}
							} else if (tmpCity.length == 2) {
								locationSearch = {city: tmpCity[1].trim()}
							} else if (tmpCity.length > 2) {
								locationSearch = {area: tmpCity[1].trim()};
								locationSearch.area = locationSearch.area.replace(/р-н/gi, '').trim();
								if (tmpCity.length == 3) {
									locationSearch.settlement = tmpCity[2].trim();
									locationSearch.settlement = locationSearch.settlement.replace(/село/gi, '').trim();
								}
							}

							var post = {
								query: request.term,
								count: 10,
								locations: [locationSearch],
								from_bound: {value: "street"},
								to_bound: {value: "street"},
							};
							$.post('/service/UL/Suggestions/getAddress', JSON.stringify(post), function (result) {
								var sResult = [];
								if (result.DATA != null && !is.undefined(result.DATA.suggestions) && is.array(result.DATA.suggestions)) {
									$.each(result.DATA.suggestions, function (k, arItem) {
										sResult.push(arItem);
									});
									response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

									$('.ui-autocomplete').css({'z-index': '1080'});
								}
							}, 'json');
						},
						minLength: 2,
					}).bind("autocompleteselect", function (ev, ui) {
						var arTmp = ui.item.value.split(',');
						var valInput = arTmp[arTmp.length - 1].trim();


						valInput = valInput.replace(/ул /g, '');

						ui.item.value = valInput;

						var Fields = this.state.Fields;

						if (arTmp[0]) {
							Fields['STREET']['VALUE'] = valInput;
						}

						this.setState({Fields: Fields});
					}.bind(this));

				}
			}.bind(this));

		},

		changeField: function (ev) {
			var Fields = this.state.Fields;
			if (ev.target.name == 'PROFILE_NAME') {
				Fields['PROFILE_NAME']['VALUE'] = ev.target.value;
			}
			if (!is.empty(Fields[ev.target.name]) || !is.undefined(Fields[ev.target.name])) {
				Fields[ev.target.name]['VALUE'] = ev.target.value;
				this.setState({Fields: Fields});
			}
		},
		saveAddress: function () {
			var noValid = 0;

			$.each(this.state.Fields, function (code, arField) {
				if (is.undefined(arField.VALUE) || arField.VALUE == '') {
					noValid++;
				}
			});
			if (noValid > 0) {
				this.setState({validForm: false});
			} else {
				this.setState({validForm: true});
			}

			var Fields = this.state.Fields;

			if (this.props.currentProfile != undefined) {
				if (!is.empty(this.props.currentProfile) || !is.undefined(this.props.currentProfile.ID)) {
					Fields.PROFILE_ID = this.props.currentProfile.ID;
				}
			}

			console.info(Fields);

			return;


			Service.action('saveAddress').post(Fields).then(function (result) {
				if (result.STATUS == 1) {
					this.setState({profileId: result.DATA});
				}
				this.setState({Result: result});
			}.bind(this));
		},

		render: function () {
			var goodSave;
			if (this.state.profileId != null) {
				goodSave = (<span className="goodMsg">Адрес сохранен</span>);
			}

			if (!is.empty(this.state.Fields) && this.state.Fields !== false) {
				return (
					<div className="b-popup-recovery">
						<div className="b-popup-cart__head">
							<div className="b-products-block-top b-ib bg_reverse">
								<div className="cart__img-wrapper">
									<div className="cart__prod-title">
										<div className="icon-g"></div>
									</div>
									<div className="recovery__title">Сохранить адрес</div>
								</div>
							</div>
						</div>
						<div className="accepted__content">
							<div className="lk__add-address">
								<form method="post" noValidate="novalidate" autoComplete="off">
									{goodSave}
									<span className="lk__form-descr"><i className="star">*</i> - обязательны для заполнения</span>
									<label className="form__label">
										<span className="span__label">
											<i className="star">*</i>{this.state.Fields['CITY']['NAME']}
										</span>
										<input type="text" placeholder={this.state.Fields['CITY']['NAME']}
											className="form__input form__input_middle" name="CITY"
											id="city_field" ref="CITY" />
									</label>
									<label className="form__label">
										<span className="span__label">
											<i className="star">*</i>{this.state.Fields['STREET']['NAME']}
										</span>
										<input type="text" placeholder={this.state.Fields['STREET']['NAME']}
											className="form__input form__input_middle" name="STREET"
											id="street_field" />
									</label>
									<div className="form__col1">
										<label className="form__label">
											<span className="span__label span__label_short">
												<i className="star">*</i>{this.state.Fields['HOUSE']['NAME']}
											</span>
											<input type="text" placeholder={this.state.Fields['HOUSE']['NAME']}
												className="form__input form__input_short" name="HOUSE"
												onChange={this.changeField} value={this.state.Fields['HOUSE']['VALUE']} />
										</label>
										<label className="form__label">
											<span className="span__label span__label_short">
												<i className="star">*</i>{this.state.Fields['APARTMENT']['NAME']}
											</span>
											<input type="text" placeholder={this.state.Fields['APARTMENT']['NAME']}
												className="form__input form__input_short" name="APARTMENT"
												onChange={this.changeField} value={this.state.Fields['APARTMENT']['VALUE']} />
										</label>
									</div>
									<div className="form__col1">
										<label className="form__label">
											<span className="span__label span__label_short">{this.state.Fields['FLOOR']['NAME']}</span>
											<input type="text" placeholder={this.state.Fields['FLOOR']['NAME']}
												className="form__input form__input_short" name="FLOOR"
												onChange={this.changeField} value={this.state.Fields['FLOOR']['VALUE']} />
										</label>
										<label className="form__label">
											<span className="span__label span__label_short">{this.state.Fields['ZIP']['NAME']}</span>
											<input type="text" placeholder={this.state.Fields['ZIP']['NAME']}
												className="form__input form__input_short" name="ZIP"
												onChange={this.changeField} value={this.state.Fields['ZIP']['VALUE']} />
										</label>
									</div>
									<label className="form__label">
										<span className="span__label">
											<i className="star">*</i>
											Название
											<div className="lable__tooltip">
												<span className="tooltip__content animated zoomIn">
													Название адреса, под которым он будет сохранен. Например, "мой дом" или "моя работа".
												</span>
											</div>
										</span>

										<input type="text" placeholder="Название"
											name="PROFILE_NAME"
											className="form__input form__input_middle form__input_tooltip"
											onChange={this.changeField} value={this.state.Fields['PROFILE_NAME']['VALUE']} />
									</label>
									<input type="hidden" name={this.state.Fields['ZIP']['VALUE']} />
									<button onClick={this.saveAddress} type="button"
										className="b-button b-button_check b-button_green b-button_big">
										Сохранить адрес
									</button>
								</form>
							</div>
						</div>
					</div>
				);
			} else {
				return (<div className="b-popup-recovery">Загрузка...</div>);
			}
		}
	});
});
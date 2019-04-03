BX(function () {

	var Yandex = {
		polygons: [],
		currentPointObject: {},
		allMap: {}
	};


	var Service = new BX.AjaxService({mainUrl: '/rest/UL/Main/Personal/Address/'});

	// require(['jsx!personal/address/FormAddress'], function (FormAddress) {
		// var FormRender = ReactDOM.render(FormAddress, BX('change_address_popup'));
		// var FormRender = FormAddress.init();
	// });
	// var FormAddress = BX.UL.FormAddress;
	// var FormRender = ReactDOM.render(<FormAddress />, BX('change_address_popup'));

	var NoAddressWin = React.createClass({

		emailInput: null,
		Service: Service,

		getInitialState: function () {
			return {
				visible: false,
				email: '',
				validForm: false
			}
		},

		componentDidMount: function () {
			this.Service.action('getAddressUser').get().then(function (res) {
				if (res.DATA != null) {
					this.setState({email: res.DATA, validForm: true});
				}
			}.bind(this));
		},

		componentWillUpdate: function (prop, state) {
			if (state.visible === true) {
				$.magnificPopup.open({
					items: {
						src: '#render_no_address',
						type: 'inline'
					},
					enableEscapeKey: true,
					showCloseBtn: false,
					closeOnBgClick: true,
					mainClass: 'show_address_win'
				});
			}
			$('.success_txt').remove();
			if (this.emailInput === null) {
				this.emailInput = $('input[name=EMAIL_NO_ADDRESS]');
			}
		},

		changeEmail: function (ev) {
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if (re.test(ev.target.value)) {
				this.emailInput.css({'background-color': '#EBFFF2'});
				this.setState({validForm: true});
			} else {
				this.setErrorEmail();
			}

			this.setState({email: ev.target.value});
		},

		saveEmail: function (ev) {
			if (this.state.email == '') {
				this.setErrorEmail();
			}
			if (this.state.validForm === true) {
				this.Service.action('saveEmailNoAddress').post({EMAIL: this.state.email}).then(function (result) {
					if (result.DATA != null && result.STATUS == 1) {
						console.info(this.emailInput);
						this.emailInput.parent().prepend('<div class="success_txt">Адрес сохранен</div>');
					}
				}.bind(this));
			}
		},

		setErrorEmail: function (msg) {
			if (!msg || msg == '' || msg === false || msg === null) {
				msg = '';
			}
			if (this.emailInput.length > 0) {
				this.emailInput.css({'background-color': '#FFEFF1'});
			}
			this.setState({validForm: false});
		},

		prevWin: function () {
			$.magnificPopup.close();
			$('#render_address .b-header-location__change').click();
		},

		render: function () {
			return (
				<div className="no_address">
					<div className="mfp-close"/>
					<div className="header_win">
						<div className="no_address_icon"></div>
					</div>
					<div className="content accepted__content">
						<div className="lk__add-address">
							<h2>Ваш адрес не попадает <br />ни в одну из зон доставки :(</h2>
							<p>Оставьте e-mail, чтобы бы могли сообщить, <br />
								когда сервис станет доступен по этому адресу</p>
							<p>
								<input type="text" placeholder="sss@sss.ru" name="EMAIL_NO_ADDRESS"
									   className="form__input form__input_accepted" onChange={this.changeEmail}
									   value={this.state.email}/>
								<button type="submit" name="Login"
										className="b-button b-button_green b-button_check" onClick={this.saveEmail}>
									Отправить
								</button>
							</p>
							<p>Есть еще один адрес? <a href="javascript:" onClick={this.prevWin}>Попробуйте другой</a>
							</p>
						</div>
					</div>
				</div>
			);
		}

	});

	var stateNoAddressWin = ReactDOM.render(<NoAddressWin />, BX('render_no_address'));

	var YandexMap = React.createClass({
		componentDidMount: function () {
			ymaps.ready(function () {
				var urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();
				BX.ajax.loadJSON(urlCords, function (result) {
					Yandex.Map = new ymaps.Map("ya_maps", {
						// center: [55.756449, 37.617112],
						center: [53.195522, 50.101819],
						zoom: 11,
						behaviors: ['drag', 'rightMouseButtonMagnifier', 'scrollZoom'],
						controls: ['geolocationControl', 'rulerControl', 'zoomControl'],
						suppressObsoleteBrowserNotifier: true
					});

					Yandex.allMap = ymaps;

					if (result.DATA != null) {
						$.each(result.DATA, function (i, value) {
							var polyProp;
							if (!is.empty(value.CORDS)) {
								polyProp = JSON.parse(value.CORDS);
							} else {
								polyProp = {
									cords: [[]],
									options: {}
								};
							}

							var UlPolygon = new Yandex.allMap.Polygon(polyProp.cords, {}, polyProp.options);

							Yandex.Map.geoObjects.add(UlPolygon);

							UlPolygon.events.add('click', function () {
								var urlSave = '/local/components/ul/address.set/ajax.php?address=change&set_region=Y&sessid=' + BX.bitrix_sessid();

								var GeoAddress = Yandex.allMap.geocode(UlPolygon.geometry.get(0)[0], {results: 1});
								GeoAddress.then(function (rData) {
									var address = rData.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.AddressDetails.Country.AddressLine;
									$.post(urlSave, {
										CORDS: UlPolygon.geometry.get(0),
										ADDRESS: address
									}, function (data) {
										if (data.DATA != null) {
											window.location.assign('/');
										}
									}, 'json');
								});
							});

							Yandex.polygons.push(UlPolygon);
						});
					}

				});
			});
		},

		render: function () {
			return (<div className="b-popup-hello__ymap" id="ya_maps"></div>);
		}
	});

	var ContentWin = React.createClass({

		getInitialState: function () {
			return {
				currentAddress: '',
				addressList: []
			}
		},

		compareAddress: function (address) {

			if (is.object(address)) {
				address = $('#search_address_start').val();
			}

			if (!address || address == '' || is.undefined(address)) {
				address = this.state.currentAddress;
				if (address == '') {
					address = $('#search_address_start').val();
				}

			}
			else
				this.setState({currentAddress: address});

			if (!is.undefined(address)) {

				var GeocodeStart = Yandex.allMap.geocode(address, {results: 1});
				GeocodeStart.then(function (res) {
					Yandex.Map.geoObjects.remove(Yandex.currentPointObject);

					var searchInt = 0;
					for (var k in Yandex.polygons) {
						if (!is.undefined(Yandex.polygons[k])) {
							var arPolygon = Yandex.polygons[k];

							Yandex.currentPointObject = res.geoObjects;

							var contains = Yandex.allMap.geoQuery(res.geoObjects).searchIntersect(arPolygon);
							Yandex.Map.geoObjects.add(res.geoObjects);

							if (contains.getLength() == 1) {

								var urlSave = '/local/components/ul/address.set/ajax.php?address=change&set_region=Y&sessid=' + BX.bitrix_sessid();
								$.post(urlSave, {
									CORDS: arPolygon.geometry.get(0),
									ADDRESS: address
								}, function (data) {
									if (data.DATA != null) {
										window.location.assign('/');
									}
								}, 'json');

								searchInt++;
								break;
							}
						}
					}

					if (searchInt == 0) {
						// console.info(searchInt);
						stateNoAddressWin.setState({visible: true});
					} else if (searchInt >= 1) {
					}
				});
			}
		},

		componentDidMount: function () {
			this.setState({currentAddress: $('.b-header-location__current').text()});

			$("#search_address_start").autocomplete({
				source: function (request, response) {
					var post = {query: request.term, count: 10};
					$.post('/service/UL/Suggestions/getAddress', JSON.stringify(post), function (result) {
						var sResult = [];
						if (result.STATUS = 1 && is.array(result.DATA.suggestions)) {
							$.each(result.DATA.suggestions, function (k, arItem) {
								sResult.push(arItem.value);
							});
							response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

							$('.ui-autocomplete').css({'display': 'block', 'z-index': '1080'});
						}
					}, 'json');
				},
				minLength: 3,
				// select: function (event, ui) {
				// }
			}).bind("autocompleteselect", function (event, ui) {
				this.setState({currentAddress: ui.item.label});
				// this.compareAddress();
			}.bind(this));
		},

		setAddress: function (address) {
			if (address || address != '') {
				this.compareAddress(address);
			}
		},

		addNewAddress: function () {
			FormRender.setState({visible: true});
		},

		render: function () {
			return (
				<div className="b-popup-hello__right b-ib">
					<div className="b-popup-hello__title">Сменить адрес</div>
					<div className="b-popup-hello-form">
						<div className="b-popup-hello-form__note">Найти адрес</div>
						<div className="b-popup-hello-form__item b-popup-hello-form__item_reset">
							<input type="text" placeholder="Например, г. Москва, ул. Пушкина, 13"
								className="b-form-control" id="search_address_start"/>
							<button type="button" onClick={this.compareAddress} className="b-button b-button_green">
								Найти
							</button>
						</div>

						<div className="b-popup-hello-form__title">Ваши адреса</div>
						<AddressItem changeAddress={this.setAddress}/>

						<div className="b-button b-button_green" onClick={this.addNewAddress}>Добавить новый адрес</div>

					</div>
				</div>
			);
		}
	});

	var AddressItem = React.createClass({

		getInitialState: function () {
			return {
				profileList: []
			}
		},

		componentDidMount: function () {
			Service.action('getData').get().then(function (result) {
				if (result.DATA.profiles.length > 0) {
					this.setState({profileList: result.DATA.profiles});

					$('#js-address-scroll').jScrollPane({
						autoReinitialise: true
					});
				}
			}.bind(this));
		},

		render: function () {
			var temple = [];
			var compare = this.props.changeAddress;
			if (this.state.profileList.length > 0) {

				temple = this.state.profileList.map(function (profile) {
					var strSearch = profile.VALUES.CITY.VALUE;
					strSearch += ', ул.' + profile.VALUES.STREET.VALUE;
					strSearch += ', д.' + profile.VALUES.HOUSE.VALUE;

					return (
						<div key={profile.ID} className="b-button check__back address_item_popup">
							<div className="edit__icon address_item_popup"/>
							<div className="arrow_left_address"/>
							<div className="address_item_txt"
								 onClick={compare.bind(null, strSearch)}>{profile.VALUE_FORMAT}</div>
						</div>
					)
				});
			}
			return (<div className="b-popup-hello-form-adr-wrapper b-custom-scroll js-custom-scroll"
						 id="js-address-scroll">{temple}</div>);
		}
	});

	var Address = React.createClass({

		componentDidMount: function () {

		},

		showWindow: function () {
			var popup = $.magnificPopup.instance;
			popup.open({
					items: {
						src: '#change_address_win',
						type: 'inline',
					},
					enableEscapeKey: true,
					showCloseBtn: false,
					closeOnBgClick: true,
					mainClass: 'show_address_win'
				}
			)
		},

		render: function () {
			return (
				<span>
				<button onClick={this.showWindow} className="b-button b-header-location__change">Сменить адрес</button>
				<div className="hide_content">
					<div className="b-popup b-popup-hello" id="change_address_win">
						<div className="b-popup-hello__wrapper b-ib-wrapper b-popup-hello__wrapper_reset">
							<div className="b-popup-hello__left b-ib b-popup-hello__left_reset">
								<YandexMap />
							</div>
							<ContentWin />
						</div>
					</div>
				</div>
			</span>
			);
		}
	});

	// ReactDOM.render(<Address />, BX('render_address'));
	//var stateNoAddressWin = ReactDOM.render(<NoAddressWin />, BX('render_no_address'));
	// stateNoAddressWin.setState({visible: true});
	// $('.b-header-location__change').click();
});
define(function (require) {
	'use strict';
	var Ajax = require('AjaxService');
	var Service = new Ajax({mainUrl: '/rest/UL/Main/Personal/Address/'});
	var React = require('react');
	var ReactDOM = require('dom');

	var NoAddressWin = require('jsx!personal/address/NoAddressWin');
	var stateNoAddressWin = ReactDOM.render(<NoAddressWin/>, BX('render_no_address'));

	var AddressItems = require('jsx!personal/address/AddressItems');
	var FormAddress = require('jsx!personal/address/FormAddress');
	// var FormRender = ReactDOM.render(<FormAddress cityVal={this.cityChange} />, BX('change_address_popup'));

	var Yandex = {
		polygons: [],
		currentPointObject: {},
		allMap: {}
	};

	var YandexMap =  React.createClass({
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
								var urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();


								var GeoAddress = Yandex.allMap.geocode(UlPolygon.geometry.get(0)[0], {results: 1});
								GeoAddress.then(function (rData) {
									var address = rData.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.AddressDetails.Country.AddressLine;
									$.post(urlSave, {
										CORDS: UlPolygon.geometry.get(0),
										ADDRESS: address,
										addressChange: 'Y'
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

	return React.createClass({
		getInitialState: function () {
			return {
				currentAddress: '',
				addressList: [],
				visibleEdit: false,
				propEdit: {visible: false, currentProfile: false, Fields: false}
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

								var urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();
								$.post(urlSave, {
									CORDS: arPolygon.geometry.get(0),
									ADDRESS: address,
									addressChange: 'Y'
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
						stateNoAddressWin.setState({visible: true, addressStr: address});
					}
				});
			}
		},

		cityChange: function (ev) {

			return ev.target.value;
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
								sResult.push(arItem);
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
			// FormRender.setState({visible: true});
			ReactDOM.render(<FormAddress />, BX('change_address_popup'))
				.setState({visible: true});
		},

		editAddress: function (profile) {

			this.setState({propEdit: {visible: true, currentProfile: profile, Fields: profile.VALUES}});

			var FormEdit = ReactDOM.render(
				<FormAddress FieldVal={profile.VALUES} currentProfile={profile}
							 city={profile.VALUES.CITY} street={profile.VALUES.STREET}/>,
				BX('change_address_popup')
			);
			// FormEdit.setStat
			profile.VALUES.PROFILE_NAME = {VALUE: profile.NAME};

			FormEdit.setState({Fields: profile.VALUES, visible: true});
		},

		render: function () {
			var templeAddressList;
			if(this.props.auth && this.props.auth != ''){
				templeAddressList = [
					<div className="b-popup-hello-form__title">Ваши адреса</div>,
					<AddressItems changeAddress={this.setAddress} editAddress={this.editAddress}/>,
					<div className="b-button b-button_green" onClick={this.addNewAddress}>Добавить новый адрес</div>
				];
			}

			return (
				<div className="b-popup-hello__wrapper b-ib-wrapper b-popup-hello__wrapper_reset">
					<div className="b-popup-hello__left b-ib b-popup-hello__left_reset">
						<YandexMap />
					</div>
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

							{templeAddressList}

						</div>
					</div>
				</div>
			);
		}
	});
});
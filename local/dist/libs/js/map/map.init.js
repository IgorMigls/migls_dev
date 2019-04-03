if (UL === undefined) {
	var UL = {};
}

UL.Maps = {
	container: 'ul_start_map',
	isSetAdress: false,
	polygons: [],
	yMaps: {},
	currentPointObject: {},
	init: function () {
		var urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();

		$.getJSON(urlCords, function (result) {
			UL.Maps.Map = new ymaps.Map("ul_start_map", {
				// center: [55.756449, 37.617112],
				center: [53.195522, 50.101819],
				zoom: 11,
				behaviors: ['drag', 'rightMouseButtonMagnifier', 'scrollZoom'],
				controls: ['geolocationControl', 'rulerControl', 'zoomControl'],
				suppressObsoleteBrowserNotifier: true
			});

			UL.Maps.yMaps = ymaps;
			/*UL.Maps.yMaps.geolocation.get({
			 // Выставляем опцию для определения положения по ip
			 provider: 'yandex',
			 // Карта автоматически отцентрируется по положению пользователя.
			 mapStateAutoApply: true
			 }).then(function (result) {
			 UL.Maps.Map.geoObjects.add(result.geoObjects);
			 });*/
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

					var UlPolygon = new ymaps.Polygon(polyProp.cords, {}, polyProp.options);
					UL.Maps.Map.geoObjects.add(UlPolygon);
					UlPolygon.events.add('click', function () {
						var urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();

						var GeoAddress = UL.Maps.yMaps.geocode(UlPolygon.geometry.get(0)[0], {results: 1});
						GeoAddress.then(function (rData) {
							var address = rData.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.AddressDetails.Country.AddressLine;
							$.post(urlSave, {CORDS: UlPolygon.geometry.get(0), ADDRESS: address}, function (data) {
								if (data.DATA != null) {
									window.location.assign('/');
								}
							}, 'json');
						});
					});

					UL.Maps.polygons.push(UlPolygon);

				});
			}
		});
	},

	searchAddress: function (input, address) {
		var $err = $('#errors_address');
		$err.hide(0);

		// address = $('#search_address_start').val();
		// console.info(address);

		if (!address) {
			address = $(input).val();
		}
		if (address == '') {
			$err.text('Введите свой адрес');
		} else {

			// console.info(address);

			var GeocodeStart = UL.Maps.yMaps.geocode(address, {results: 1});
			GeocodeStart.then(
				function (res) {
					// var coordinates = res.geoObjects.get(0).geometry.getCoordinates();
					UL.Maps.Map.geoObjects.remove(UL.Maps.currentPointObject);
					var searchInt = 0;
					for (var k in UL.Maps.polygons) {
						if (!is.undefined(UL.Maps.polygons[k])) {
							var arPolygon = UL.Maps.polygons[k];

							UL.Maps.currentPointObject = res.geoObjects;

							var contains = UL.Maps.yMaps.geoQuery(res.geoObjects).searchIntersect(arPolygon);
							UL.Maps.Map.geoObjects.add(res.geoObjects);

							if (contains.getLength() == 1) {

								var urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();
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
						$err.text('Ваш адрес не входит в зону работы сервиса').show(0);
						$.magnificPopup.close();
						if( $.magnificPopup.instance.popupsCache.hasOwnProperty('hello')){
							delete $.magnificPopup.instance.popupsCache['hello'];
						}

						window.UL.noAddressWin(BX('render_no_address'), address);
						// window.UL.helloApp();

						/*$.magnificPopup.open({
							items: {
								src: '#popupHello',
								type: 'inline'
							},
							midClick: false,
							closeOnBgClick: false,
							closeBtnInside: false,
							showCloseBtn: false,
							modal: true,
							key: 'noaddress'
						});*/

					} else if (searchInt >= 1) {
						//console.info(urlSave);

					}
				},
				function (err) {

				}
			);
		}
	},

	setTestAddress: function () {
		this.searchAddress(null, 'Москва, тверская 1');
	},

	addAddressToInput: function (address) {
		this.insertAddres(address);
		this.searchAddress(false, address);
		$('.suggestions_address').hide(0);
	},

	insertAddres: function (address) {
		$('#search_address_start').val(address);
	},

	suggestions: function (link) {

		var value = link.target.value;
		var post = {query: value, count: 10};
		var htmlSuggest = '';

		if (!is.empty(value)) {
		} else {
			$('.suggestions_address').hide(0);
			$('.suggestions_address li').remove();
		}

	},
};
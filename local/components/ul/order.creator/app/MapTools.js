/**
 * Created by dremin_s on 03.04.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
/** @const ymaps */
/** @const BX */
"use strict";
import { RandString } from 'Tools';

class MapTools {
	constructor(address = {}, params = {}) {
		this.address = address;
		this.Yandex = {
			polygons: [],
			currentPointObject: {},
			allMap: {},
			Map: {},
		};

		this.mapId = RandString(10, 'all');
		this.mapId = 'map_search_order';

		let $mapContainer = $('#'+this.mapId);
		if($mapContainer.length === 0){
			$('body').append('<div id="'+this.mapId+'"></div>');
		}

		this.RestManager = this.address.RestManager;
		let options = {
			redirect: true
		};
		this.options = Object.assign({}, options, params);
		this.validAddress = true;
	}

	initMap(address = '') {
		if(address === '' || address === null || address === undefined){
			address = 'г.'+ this.address.city + ' ул.'+ this.address.street+ ' д.'+ this.address.house;
		}

		return ymaps.ready(() => {
			let urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();
			BX.ajax.loadJSON(urlCords, result => {

				this.Yandex.Map = new ymaps.Map(this.mapId, {
					// center: [55.756449, 37.617112],
					center: [53.195522, 50.101819],
					zoom: 9,
					behaviors: [],
					controls: [],
					suppressObsoleteBrowserNotifier: true
				});


				this.Yandex.allMap = ymaps;

				if (result.DATA !== null) {
					$.each(result.DATA, (i, value) => {
						let polyProp;
						if (!is.empty(value.CORDS)) {
							polyProp = JSON.parse(value.CORDS);
						} else {
							polyProp = {
								cords: [[]],
								options: {}
							};
						}
						let UlPolygon = new this.Yandex.allMap.Polygon(polyProp.cords, {}, polyProp.options);
						this.Yandex.Map.geoObjects.add(UlPolygon);
						this.Yandex.polygons.push(UlPolygon);
					});

					this.searchAddressInPolygons(address, this.Yandex.polygons);
				}
			});
		});
	}

	searchAddressInPolygons(address = '', polygons){

		if(address === '' || address === null || address === undefined){
			address = 'г.'+ this.address.city + ' ул.'+ this.address.street+ ' д.'+ this.address.house;
		}
		let currentPolygon;
		let GeocodeStart = ymaps.geocode(address, {results: 1});

		GeocodeStart.then(res => {
			this.Yandex.Map.geoObjects.remove(this.Yandex.currentPointObject);
			let searchInt = 0;

			for (let k in polygons) {
				if (!is.undefined(polygons[k])) {
					let arPolygon = polygons[k];

					this.Yandex.currentPointObject = res.geoObjects;

					let contains = this.Yandex.allMap.geoQuery(res.geoObjects).searchIntersect(arPolygon);
					this.Yandex.Map.geoObjects.add(res.geoObjects);

					if (contains.getLength() === 1) {
						searchInt++;
						currentPolygon = arPolygon;
						break;
					}
				}
			}

			if (searchInt === 0) {
				swal({
					title: '',
					text: 'Адрес не попадает в зону доставки',
					imageUrl: "/local/dist/images/x_win.png",
					imageSize: "112x112",
					customClass: 'error_window_custom',
					confirmButtonText: 'Закрыть',
				}, () => {
					window.location.reload();
				});
				$(document).trigger('map_valid_address', false);
				this.validAddress = false;
			} else {
				this.validAddress = false;
				$(document).trigger('map_valid_address', false);
				if (is.object(currentPolygon) && is.propertyDefined(currentPolygon, 'geometry')) {
					this.RestManager.post('/checkOrderCoords', {coords: currentPolygon.geometry.get(0)}).then(resPostCoord => {
						let swalOption = {
							title: '',
							imageUrl: "/local/dist/images/x_win.png",
							imageSize: "112x112",
							customClass: 'error_window_custom err_address_order',
							confirmButtonText: 'Закрыть',
							closeOnConfirm: false,
							allowEscapeKey: false,
						};
						let urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();
						$('#'+this.mapId).remove();
						switch (resPostCoord.data.DATA){
							case 2:
								swalOption.text = 'Адрес относится к области, в которой нет выбранного(-ых) вами магазина(-ов)';
								swal(swalOption);
								$('.err_address_order .sa-confirm-button-container .confirm').on('click', () => {
									$.post(urlSave, {CORDS: currentPolygon.geometry.get(0), ADDRESS: ''}, (data) => {
										if(this.options.redirect === true){
											window.location.replace('/?show_cart=Y');
										}
									}, 'json');
								});
								break;
							case 3:
								swalOption.text = 'Вы ввели адрес, относящийся к другой области доставки, цены и ассортимент могут быть изменены';
								swal(swalOption);
								$('.err_address_order .sa-confirm-button-container .confirm').on('click', () => {
									$.post(urlSave, {CORDS: currentPolygon.geometry.get(0), ADDRESS: ''},  (data) => {
										if(this.options.redirect === true){
											window.location.replace('/?show_cart=Y');
										}
									}, 'json');
								});
								break;
							default:
								$(document).trigger('map_valid_address', true);
								this.validAddress = true;
								break;
						}
					})
				}
			}
		});
	}

	getValidate() {
		return this.validAddress;
	}
}

export default MapTools;
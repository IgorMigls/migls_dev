/** @var o _ */
/** @var o Vue */
"use strict";
import Map from 'Utilities/maps/Map';
import Promise from 'Promise';

const url = (action) => {
	return '/rest2/public/address' + action;
};

const api = {
	getAddressList: { method: 'GET', url: url('/getAddressList') },
	saveAddress: { method: 'POST', url: url('/saveAddress') },
	loadAddress: { method: 'GET', url: url('/loadAddress') },
	setNewAddress: { method: 'POST', url: url('/setNewAddress') },
	saveAddressEmail: { method: 'POST', url: url('/saveAddressEmail') },
};

const Rest = Vue.resource('', { sessid: BX.bitrix_sessid() }, api);
export { Rest };

const changeAreaMap = (UlPolygon, mapData) => {
	let GeoAddress = mapData.geocode(UlPolygon.geometry.get(0)[0], { results: 1 });
	return GeoAddress.then(function (rData) {
		let address = rData.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.AddressDetails.Country.AddressLine;
		return Rest.setNewAddress({
			set_region: 'Y',
			address: 'change',
			CORDS: UlPolygon.geometry.get(0),
			ADDRESS: address,
		});
	});

	/*let urlSave = '/local/components/ul/address.set/ajax.php?address=change&set_region=Y&sessid=' + BX.bitrix_sessid();
	let GeoAddress = mapData.geocode(UlPolygon.geometry.get(0)[0], { results: 1 });
	GeoAddress.then(function (rData) {
		let address = rData.geoObjects.get(0).properties.get('metaDataProperty').GeocoderMetaData.AddressDetails.Country.AddressLine;
		$.post(urlSave, {
			CORDS: UlPolygon.geometry.get(0),
			ADDRESS: address
		}, function (data) {
			if (data.DATA != null) {
				window.location.assign('/');
			}
		}, 'json');
	});*/
};


export default {
	loadMap({ commit, state }) {
		const mapData = new Map({
			mainComponent: BX(state.mainComponent),
			mapId: state.mapId,
			hiddenMap: false,
			mapOptions: {
				center: [53.195522, 50.101819],
				zoom: 11,
				behaviors: ['drag', 'rightMouseButtonMagnifier', 'scrollZoom'],
				controls: ['geolocationControl', 'rulerControl', 'zoomControl'],
				suppressObsoleteBrowserNotifier: true
			},
			coordUrl: '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&v=2&sessid=' + BX.bitrix_sessid(),
			areaClickHandler: changeAreaMap
		});

		mapData.loadMap().then(res => {
			commit('Map', res);
			commit('loading', false);
		});
	},

	showWindow({ commit, dispatch }, show) {
		commit('loading', true);
		commit('openWindow', show);

		if (show === true) {
			setTimeout(() => {
				dispatch('loadMap');
			})
		}
	},

	async fetchAddressList({ commit }) {
		let result = await Rest.getAddressList();
		if (result.data.DATA !== null) {
			commit('addressList', result.data.DATA);
		}
	},

	async saveAddress({ commit, dispatch }, payload) {
		let res = await Rest.saveAddress({ fields: payload });
		if (res.data.DATA !== null) {
			commit('addressSaved', true);
			dispatch('fetchAddressList');
		}
		commit('loading', false);
	},

	searchAddress({ state, commit, dispatch }, item) {
		commit('loading', true);
		let address = `г.${item.CITY}, ${item.STREET}, д.${item.HOUSE}`;

		return state.Map.search(address);
	},

	loadAddressSearch({ commit }, query) {
		return Rest.loadAddress({ search: query });
	},

	loadCity({ commit }, query) {
		return Rest.loadAddress({ search: query, locations: 'city' });
	},

	loadStreet({ commit }, data){
		return Rest.loadAddress({ search: data.query, locations: 'street', city: data.city});
	},

	searchAddressInArea({ state }, query) {
		return new Promise((resolve, reject) => {
			state.Map.search(query).then(res => {
				if (res > 0) {
					Rest.setNewAddress({
						address: 'change',
						set_region: 'Y',
						CORDS: state.Map.currentPolygon.geometry.get(0),
						ADDRESS: query
					}).then(result => {
						resolve(result);
					})
				} else {
					reject(false)
				}
			}).catch(err => {
				reject(false)
			});
		});
	},

	async saveAddressEmail({ commit }, email) {
		return await Rest.saveAddressEmail({email});
	}
};
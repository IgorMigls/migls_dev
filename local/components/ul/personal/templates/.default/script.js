var options = {
	redirect: false
};
function MapTools(address, params) {
	this.address = address || {};
	params = params || {};

	this.Yandex = {
		polygons: [],
		currentPointObject: {},
		allMap: {},
		Map: {},
	};
	this.mapId = 'ul_map_personal';
	var $mapContainer = $('#' + this.mapId);
	if ($mapContainer.length === 0) {
		$('body').append('<div id="' + this.mapId + '"></div>');
	}

	this.options = params;

	var _this = this;
	angular.forEach(options, function (val, code) {
		_this.options[code] = val;
	});

	this.initMap = function (address = '') {

		return ymaps.ready(function () {
			var urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();
			$.getJSON(urlCords, result => {

				_this.Yandex.Map = new ymaps.Map(_this.mapId, {
					// center: [55.756449, 37.617112],
					center: [53.195522, 50.101819],
					zoom: 9,
					behaviors: [],
					controls: [],
					suppressObsoleteBrowserNotifier: true
				});


				_this.Yandex.allMap = ymaps;

				if (result.DATA !== null) {
					$.each(result.DATA, (i, value) => {
						var polyProp;
						if (!is.empty(value.CORDS)) {
							polyProp = JSON.parse(value.CORDS);
						} else {
							polyProp = {
								cords: [[]],
								options: {}
							};
						}
						var UlPolygon = new _this.Yandex.allMap.Polygon(polyProp.cords, {}, polyProp.options);
						_this.Yandex.Map.geoObjects.add(UlPolygon);
						_this.Yandex.polygons.push(UlPolygon);
					});

					if(address === undefined || address.length === 0){
						address = 'г.' + _this.getAddress('CITY') + ' '+ _this.getAddress('STREET') + ' ' +  _this.getAddress('HOUSE')
					}
					// _this.validateAddress(address, _this.Yandex.polygons);
					// _this.searchAddressInPolygons(address, _this.Yandex.polygons);
				}
			});
		});
	};

	this.store = {};
	this.validAddress = false;

	this.setAddress = function (key, val) {
		_this.store[key] = val;
	};

	this.getAddress = function (key) {
		if(key === undefined)
			return _this.store;

		return _this.store.hasOwnProperty(key) ? _this.store[key] : '';
	};

	this.validateAddress = function (address, polygons) {
		if(address === undefined || address.length === 0){
			address = 'г.' + _this.getAddress('CITY') + ' '+ _this.getAddress('STREET') + ' ' +  _this.getAddress('HOUSE')
		}

		var currentPolygon;
		var GeocodeStart = ymaps.geocode(address, {results: 1});
		if(polygons === undefined){
			polygons = _this.Yandex.polygons;
		}
		GeocodeStart.then(function (res){
			// console.info(_this.Yandex.Map);
			try{
				_this.Yandex.Map.geoObjects.remove(_this.Yandex.currentPointObject);
			} catch (err){
				// console.info(err);
			}

			var searchInt = 0;

			for (var k in polygons) {
				if (!is.undefined(polygons[k])) {
					var arPolygon = polygons[k];

					_this.Yandex.currentPointObject = res.geoObjects;

					var contains = _this.Yandex.allMap.geoQuery(res.geoObjects).searchIntersect(arPolygon);
					_this.Yandex.Map.geoObjects.add(res.geoObjects);

					if (contains.getLength() === 1) {
						searchInt++;
						currentPolygon = arPolygon;
						break;
					}
				}
			}

			// console.info(searchInt);
			if (searchInt === 0) {
				swal({
					title: '',
					text: 'Адрес не попадает в зону доставки',
					imageUrl: "/local/dist/images/x_win.png",
					imageSize: "112x112",
					customClass: 'error_window_custom',
					confirmButtonText: 'Закрыть',
				});

				_this.validAddress = false;
			} else {
				_this.validAddress = true;
			}

			$(document).trigger('map_valid_address', _this.validAddress);
		});

	}
}

var Map = new MapTools();

BX(function () {
	BX.appUL.config(function ($stateProvider, $urlRouterProvider, $aServiceProvider) {

		$urlRouterProvider.otherwise("/profile");

		$stateProvider
			.state('profile', {
				url: '/profile',
				views: {
					personalView: {
						controller: 'PersonalProfileCtrl',
						templateUrl: function (stateParams) {
							var url = '/personal/personal.php';
							return BX.util.add_url_param(url, {page: 'profile'});
						}
					}
				}
			})
			.state('address', {
				url: '/address',
				views: {
					personalView: {
						controller: 'PersonalAddressCtrl',
						templateUrl: function (stateParams) {
							var url = '/personal/personal.php';
							return BX.util.add_url_param(url, {page: 'address'});
						}
					}
				}
			})
			.state('coupons', {
				url: '/coupons',
				views: {
					personalView: {
						controller: 'PersonalCouponsCtrl',
						templateUrl: function (stateParams) {
							var url = '/personal/personal.php';
							return BX.util.add_url_param(url, {page: 'coupons'});
						}
					}
				}
			})
			.state('orders', {
				url: '/orders',
				views: {
					personalView: {
						controller: 'PersonalOrdersCtrl',
						templateUrl: function (stateParams) {
							var url = '/personal/personal.php';
							return BX.util.add_url_param(url, {page: 'orders'});
						}
					}
				}
			})
			.state('ordersDetail', {
				url: '/orders/:orderId/catalog/:id',
				views: {
					personalView: {

						templateUrl: function (stateParams) {
							var url = '/personal/personal.php';
							stateParams.page = 'orders.detail';
							return BX.util.add_url_param(url, stateParams);
						},
						controller: 'PersonalOrdersDetailCtrl'
					},
					product: {
						controller: 'ProductCtrl',
						templateUrl: function (stateParams) {
							var url = '/local/route/view/product.php';
							return BX.util.add_url_param(url, stateParams);
						}
					}
				}
			})
	});

	BX.appUL.controller('PersonalProfileCtrl', [
		'$scope', '$aService', '$rootScope', '$state', 'FileUploader',
		function ($scope, $aService, $rootScope, $stateParams, FileUploader) {
			$scope.User = {note: [], errors: []};
			$scope.Pass = {};

			var url = '/rest/UL/Main/Personal/Profile';
			var magnificPopup = $.magnificPopup.instance;

			$scope.loadData = function () {
				$aService.setAction(url + '/getData').get().then(function (result) {
					$scope.User = result.data.DATA;
					if (!$scope.User.BIRTHDAY.CURRENT) {
						$scope.User.BIRTHDAY.CURRENT = {
							m: $scope.User.BIRTHDAY.MONTHS[0]
						};
					}
				});
			};

			$scope.saveUser = function (UserData) {
				$scope.User.note = [];
				$scope.User.errors = [];
				$aService.setAction(url + '/saveData').post(UserData).then(function (result) {
					if (result.data.STATUS === 1) {
						$scope.User.note.push('Профиль обновлен');
					} else if (result.data.ERRORS.length > 0) {
						angular.forEach(result.data.ERRORS, function (value, k) {
							$scope.User.errors.push(value);
						});
					}
				});
			};

			$scope.uploader = new FileUploader({
				url: '/rest/UL/Main/Personal/Profile/savePhoto',
				autoUpload: true,
				removeAfterUpload: true
			});
			$scope.uploader.filters.push({
				name: 'imageFilter',
				fn: function (item /*{File|FileLikeObject}*/, options) {
					var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
					return '|jpg|png|jpeg|'.indexOf(type) !== -1;
				}
			});

			$scope.showForm = function () {
				$scope.User.errors = [];
				magnificPopup.open({
					items: {src: '#change_pass_profile'},
					type: 'inline',
					mainClass: 'mfp-with-zoom',
					zoom: {
						enabled: true,
						duration: 300,
						easing: 'ease-in-out'
					},
					callbacks: {
						beforeClose: function () {
							$scope.Pass = {};
						}
					}
				});
			};

			$scope.showChangeMail = function () {
				$scope.ChangeError = [];
				magnificPopup.open({
					items: {src: '#change_email_profile'},
					type: 'inline',
					mainClass: 'mfp-with-zoom',
					zoom: {
						enabled: true,
						duration: 300,
						easing: 'ease-in-out'
					},
					callbacks: {
						beforeClose: function () {
							$scope.Change = {};
							// $scope.$apply(function () {
							// 	$scope.ChangeError = [];
							// });
						}
					}
				});
			};

			$scope.saveEmailProfile = function () {
				$scope.ChangeError = [];
				$scope.User.note = [];
				if ($scope.EmailForm.$valid) {
					$aService.setAction('/rest/UL/Main/Personal/Profile/changeMail').post($scope.Change).then(function (result) {
						if (result.data.STATUS == 1) {
							$scope.User.note.push('Профиль обновлен');
							magnificPopup.close();
						} else if (result.data.ERRORS.length > 0) {
							angular.forEach(result.data.ERRORS, function (value, k) {
								$scope.ChangeError.push(value);
							});
						}
					});
				}
			};

			$scope.savePasswordForm = function () {
				$scope.User.note = [];
				$scope.User.errors = [];
				$scope.Pass.USER = $scope.User.ID;
				$scope.Pass.LOGIN = $scope.User.LOGIN;

				$aService.setAction(url + '/savePass').post($scope.Pass).then(function (result) {
					if (result.data.STATUS == 1) {
						$scope.User.note.push('Профиль обновлен');
						magnificPopup.close();
					} else if (result.data.ERRORS.length > 0) {
						angular.forEach(result.data.ERRORS, function (value, k) {
							$scope.User.errors.push(value);
						});
					}
				});
			};

			$scope.uploader.onBeforeUploadItem = function () {
				$rootScope.$broadcast('as:start');
			};

			$scope.uploader.onCompleteAll = function () {
				$rootScope.$broadcast('as:stop');
				$scope.loadData();
			};

			$scope.loadData();
		}
	]);

	BX.appUL.controller('PersonalAddressCtrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty',
		function ($scope, $aService, $rootScope, orderProperty) {
			var url = '/rest/UL/Main/Personal/Address';
			var magnificPopup = $.magnificPopup.instance;

			$scope.Profiles = [];
			$scope.Properties = {};
			$scope.Notes = {note: [], errors: []};

			$scope.loadData = function () {
				orderProperty.loadProperties().then(function (res) {
					$scope.Properties = res.get('Properties');
					$scope.Profiles = res.get('Profiles');
				});
			};

			/**
			 * saveAddress
			 */
			$scope.saveAddress = function () {
				$scope.Notes.note = [];
				$scope.Notes.errors = [];

				Map.setAddress('HOUSE', $scope.Properties.HOUSE.VALUE);

				Map.validateAddress();


				$(document).on('map_valid_address', function (ev, dada) {

					if(dada !== false){
						if ($scope.formPropProfile.$valid) {
							orderProperty
								.set('formAddress', $scope.formPropProfile)
								.saveAddress()
								.then(function (result) {
									if (result.data.DATA != null) {
										// $scope.Notes.note = [];
										$scope.Notes.note.push('Адрес сохранен');

										$('#formPropProfile').removeClass('ng-submitted').addClass('ng-pristine');
										$scope.loadData();
										$.magnificPopup.close();
										$scope.clearProps();
									} else if (result.data.ERRORS.length > 0) {
										angular.forEach(result.data.ERRORS, function (value, k) {
											$scope.Notes.errors.push(value);
										});
									}
								});
						}
					}
					$(document).off('map_valid_address');

				});

			};

			$scope.editAddress = function (index, $event) {
				$scope.Properties = orderProperty.editAddress(index).get('Properties');
				// $rootScope.$broadcast('change_address_popup');
				Map.setAddress('CITY', $scope.Properties.CITY.VALUE);
				Map.setAddress('STREET', $scope.Properties.STREET.VALUE);
				Map.setAddress('HOUSE', $scope.Properties.HOUSE.VALUE);

				magnificPopup.open({
					items: {src: '#change_address_personal'},
					type: 'inline',
					callbacks: {
						beforeClose: function () {
							// $scope.$apply($scope.clearProps);
							$scope.clearProps();
						}
					}
				});
			};

			$scope.delAddress = function (index) {
				orderProperty.delAddress(index).then(function (res) {
					$scope.loadData();
				});
			};

			$scope.clearProps = function () {
				angular.forEach($scope.Properties, function (value, k) {
					$scope.Properties[k]['VALUE'] = '';
				});

				$scope.Properties['PROFILE_ID'] = 0;
				$scope.Properties['PROFILE_NAME'] = '';
			};

			$scope.loadData();


			$rootScope.$on('address:add:CITY', function (ev, data) {
				$scope.Properties.CITY.VALUE = data;
			});
		}
	]);

	BX.appUL.controller('PersonalCouponsCtrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty',
		function ($scope, $aService, $rootScope, orderProperty) {

		}]);

	BX.appUL.controller('PersonalOrdersCtrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty',
		function ($scope, $aService, $rootScope, orderProperty) {
			var url = '/rest/UL/Main/Order/';

			$scope.Orders = [];

			$aService.setAction(url + 'getListOrders').get().then(function (result) {
				$scope.Orders = result.data.DATA;
			});

		}]);

	BX.appUL.controller('PersonalOrdersDetailCtrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty',
		function ($scope, $aService, $rootScope, orderProperty) {
			var url = '/rest/UL/Main/Order/';

			$scope.OrderDetail = {test: 'www'};

			$scope.btnTitle = 'Отменить заказ';
			$scope.statusOrder = false;

			// $aService.setAction(url + 'getListOrders').get().then(function (result) {
			// 	$scope.Orders = result.data.DATA;
			// });

			var cancelUrl = url + "cancel";
			$scope.cancel = function (id) {
				cancelUrl = BX.util.add_url_param(cancelUrl, {sessid: BX.bitrix_sessid(), id: id});
				$aService.setAction(cancelUrl).get().then(function (result) {
					if(result.data.DATA !== null){
						$scope.btnTitle = false;
						$scope.statusOrder = 'Отменен';
					}
				});
			};

		}]);

	BX.appUL.directive('addressSugestion', [function () {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {

				element.autocomplete({
					source: function (request, response) {
						var post = {query: request.term, count: 10, name: attr.name};

						if(attr.name === 'CITY'){
							Map.setAddress('CITY', null);
						}
						post.addressItems = {
							CITY: Map.getAddress('CITY')
						};
						$.post('/rest/personal/address/searchAddress', JSON.stringify(post), function (result) {
							var sResult = [];
							if (result.STATUS === 1 && is.array(result.DATA)) {
								$.each(result.DATA, function (k, arItem) {
									sResult.push(arItem.value);
								});
								response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);

							}
						}, 'json');
					},
					minLength: 3,
					select: function (event, ui) {
						switch (attr.name){
							case 'CITY':
								Map.setAddress(attr.name, ui.item.label);
								break;
							case 'STREET':
								Map.setAddress(attr.name, ui.item.label);
								break;
						}
						$scope.$emit('address:add:' + attr.name, ui.item.label);
					}
				});

				// console.info(element.attr('NAME'));


			},
			controller: ''
		}
	}]);


	Map.initMap();
	// ymaps.ready(UL.Maps.init);
});
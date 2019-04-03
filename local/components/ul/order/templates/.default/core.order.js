BX(function () {

	BX.appUL.config(function ($stateProvider, $urlRouterProvider) {

		$urlRouterProvider.otherwise("/step1");

		$stateProvider
			.state('step1', {
				url: '/step1',
				controller: 'OrderStep1Ctrl',
				templateUrl: function (stateParams) {
					var url = '/personal/order/make/create.php';
					return BX.util.add_url_param(url, {page: 'step1'});
				}
			})
			.state('step2', {
				url: '/step2',
				controller: 'OrderStep2Ctrl',
				templateUrl: function (stateParams) {
					var url = '/personal/order/make/create.php';
					return BX.util.add_url_param(url, {page: 'step2'});
				}
			})
			.state('step3', {
				url: '/step3',
				controller: 'OrderStep3Ctrl',
				templateUrl: function (stateParams) {
					var url = '/personal/order/make/create.php';
					return BX.util.add_url_param(url, {page: 'step3'});
				}
			})
			.state('final', {
				url: '/final',
				controller: function ($scope, $state) {
					$('.order__50 .order__tabs').hide(0);
					console.info($state);
				},
				templateUrl: function (stateParams) {
					var url = '/personal/order/make/create.php';
					return BX.util.add_url_param(url, {page: 'final'});
				}
			})
	});

	BX.appUL.controller('OrderStep1Ctrl', [
		'$scope', '$aService', '$rootScope', '$orderService', '$location', '$state',
		function ($scope, $aService, $rootScope, $orderService, $location, $state) {
			$scope.Profiles = $orderService.get('Profiles');
			$scope.Properties = $orderService.get('Properties');
			$scope.formPropProfile = {};
			$scope.successPopup = $.magnificPopup.instance;
			$scope.addressIsUse = false;

			$scope.chooseAddress = function (id) {
				if (id != 0) {
					angular.forEach($scope.Profiles, function (arVal, k) {
						if (arVal.ID == id) {
							angular.forEach($scope.Profiles[k]['VALUES'], function (val, i) {
								if (angular.isDefined($scope.Properties[i])) {
									$scope.Properties[i]['VALUE'] = val['VALUE'];
								}
							});

							$scope.Properties['PROFILE_ID'] = id;
							$scope.Properties['PROFILE_NAME'] = $scope.Profiles[k]['NAME'];

							$scope.choseAddress = $scope.Profiles[k];
							$orderService.set('Properties', $scope.Properties);
						}
					});

				} else {
					$orderService.clearPropValue();
					$scope.Properties = $orderService.get('Properties');
				}


				if (id != 0) {
					var addressForCheck = '';
					if (!BX.is.undefined($scope.choseAddress.VALUES.CITY) && !BX.is.empty($scope.choseAddress.VALUES.CITY)) {
						addressForCheck += 'г ' + $scope.choseAddress.VALUES.CITY.VALUE;
					}
					if (!BX.is.undefined($scope.choseAddress.VALUES.STREET) && !BX.is.empty($scope.choseAddress.VALUES.STREET)) {
						addressForCheck += ' ул ' + $scope.choseAddress.VALUES.STREET.VALUE;
					}
					if (!BX.is.undefined($scope.choseAddress.VALUES.HOUSE) && !BX.is.empty($scope.choseAddress.VALUES.HOUSE)) {
						addressForCheck += ' д ' + $scope.choseAddress.VALUES.HOUSE.VALUE;
					}
					$rootScope.$broadcast('ul:check_address', addressForCheck);
				}

				$orderService.set('CurrentProfile', $scope.choseAddress);

			};

			var currentProf = $orderService.get('CurrentProfile');
			if (currentProf != null && currentProf.VALUES != undefined) {
				$scope.adr = 'Y';
				$scope.Properties = currentProf.VALUES;
				$scope.chooseAddress(currentProf.ID);
			} else {
				$scope.adr = 'N';
			}

			if (is.empty($scope.Properties) && BX.is.undefined(currentProf.VALUES)) {
				$orderService.loadData().then(function (data) {
					$scope.Profiles = data.get('Profiles');
					$scope.Properties = data.get('Properties');
					$scope.selectAddress = 0;
				});
			}

			$scope.ConfigSelect = {
				valueField: 'ID',
				labelField: 'NAME',
				create: false,
				maxItems: 1,
				onChange: function (value) {
					$scope.$apply(function () {
						$scope.chooseAddress(value);
					});
				}
			};

			$scope.saveAddress = function () {
				$scope.focusName();

				console.info($scope.addressIsUse);
				if (!$scope.addressIsUse)
					return;

				$orderService.set('Properties', $scope.Properties);

				$orderService.saveAddress($scope.Properties.PROFILE_NAME).then(function (result) {
					$orderService.loadData().then(function (data) {
						$scope.Profiles = data.get('Profiles');
						$scope.Properties = data.get('Properties');
						$scope.chooseAddress(result.data.DATA);
						$scope.successPopup.open({
							items: {
								src: '#address_saved',
								type: 'inline',
								enableEscapeKey: true,
								showCloseBtn: false,
								closeOnBgClick: true,
								mainClass: 'show_cart_win'
							}
						});
					});
				});
			};

			$scope.focusName = function () {
				var addressForCheck = '';
				if (!BX.is.undefined($scope.Properties.CITY) && !BX.is.empty($scope.Properties.CITY)) {
					addressForCheck += 'г ' + $scope.Properties.CITY.VALUE;
				}
				if (!BX.is.undefined($scope.Properties.STREET) && !BX.is.empty($scope.Properties.STREET)) {
					addressForCheck += ' ул ' + $scope.Properties.STREET.VALUE;
				}
				if (!BX.is.undefined($scope.Properties.HOUSE) && !BX.is.empty($scope.Properties.HOUSE)) {
					addressForCheck += ' д ' + $scope.Properties.HOUSE.VALUE;
				}
				$rootScope.$broadcast('ul:check_address', addressForCheck);
			};

			$scope.deleteAddress = function (address) {
				$orderService.clearPropValue();
				// $scope.adr = 'N';
				angular.forEach($scope.Profiles, function (arValue, k) {
					if (arValue.ID == address.ID) {
						$orderService.delAddress(k).then(function () {
							$orderService.loadData().then(function (res) {
								$scope.Properties = res.get('Properties');
								$scope.Profiles = res.get('Profiles');
								$scope.selectAddress = 0;
							});
						});
					}
				});
			};

			$scope.$watch('adr', function () {
				if ($scope.adr == 'N') {
					$scope.choseAddress = $scope.Profiles[0];
					$scope.savedAddress = null;
					$scope.selectAddress = 0;
					$orderService.clearPropValue();
					$scope.Properties = $orderService.get('Properties');
					$orderService.set('CurrentProfile', $scope.choseAddress);
				}
			});

			$scope.nextStep = function () {
				if ($scope.formPropProfile.$valid) {

					$scope.focusName();
					if (!$scope.addressIsUse)
						return;

					$orderService.set('formPropProfile', $scope.formPropProfile);
					$state.go('step2');
				}
			};

			$rootScope.$on('ul_city_selected', function (ev, data) {
				$scope.Properties.CITY.VALUE = data.value;
				$orderService.set('Properties', $scope.Properties);
			});

			$rootScope.$on('ul_street_selected', function (ev, data) {
				$scope.Properties.STREET.VALUE = data.value;
				if ($scope.choseAddress != undefined) {
					if(!$scope.choseAddress)
						$scope.choseAddress.VALUES.STREET.VALUE = data.value;
					else{
						$scope.choseAddress = $scope.Properties;
					}
					$orderService.set('Properties', $scope.Properties);
					$orderService.set('CurrentProfile', $scope.choseAddress);
				}
			});

			$rootScope.$on('ul:addres_in_not_use', function () {
				$scope.chooseAddress(0);
			});
		}
	]);

	BX.appUL.controller('OrderStep2Ctrl', [
		'$scope', '$aService', '$rootScope', '$orderService', '$location', '$state',
		function ($scope, $aService, $rootScope, $orderService, $location, $state) {

			$scope.Profiles = $orderService.get('Profiles');
			$scope.Properties = $orderService.get('Properties');
			$scope.basket = $orderService.get('Basket');
			$scope.currentShop = {};
			$scope.Delivery = {};
			// $scope.isOpenTime = false;

			console.info($scope.basket);

			$scope.prevStep = function () {
				$state.go('step1');
			};


			if (BX.is.empty($orderService.get('formPropProfile')) || $orderService.get('formPropProfile').$valid === false) {
				$state.go('step1');
			}


			$scope.setTime = function (time, day) {

				$scope.Delivery.shop[$scope.currentShop['SHOP_CODE']] = {
					TIME: time,
					DAY: day,
					ID: $scope.currentShop.SHOP_ID
				};

				$scope.basket.ITEMS[$scope.currentShop['SHOP_CODE']]['selected_day'] = day.DAY + ' ' + day.NUM + ' ' + day.MONTH;
				$scope.basket.ITEMS[$scope.currentShop['SHOP_CODE']]['selected_time'] = time;

				$orderService.set('Delivery', $scope.Delivery);

				$rootScope.$broadcast('ul:order_time', $scope.currentShop.SHOP_ID);
			};

			$scope.nextStep = function () {
				$orderService.set('Basket', $scope.basket);
				$orderService.set('Delivery', $scope.Delivery);
				var valid = false;
				angular.forEach($scope.Delivery.shop, function (val, k) {
					if (BX.is.defined(val.DAY) && !BX.is.empty(val.DAY)) {
						valid = true;
					} else {
						valid = false;
					}
				});

				if (valid === true)
					$state.go('step3');
			};

			$scope.loadBasket = function () {
				$aService.setAction('/service/UL/Sale/Basket/getBasketForOrder').get().then(function (result) {
					$scope.basket = result.data.DATA;
					$orderService.set('Basket', $scope.basket);

					if (!is.empty($scope.basket)) {
						$scope.Delivery.shop = {};
						angular.forEach($scope.basket.ITEMS, function (item, idShop) {
							$scope.Delivery.shop[item.SHOP_CODE] = {};
							$scope.Delivery.shop[item.SHOP_CODE]['dateSelected'] = $scope.basket.DAYS_LIST[0];
							$scope.Delivery.shop[item.SHOP_CODE]['timeSelected'] = item.DELIVERY_TIME[0];
							$scope.Delivery.shop[item.SHOP_CODE]['ID'] = item.SHOP_ID;
						});
					}

					$orderService.set('Delivery', $scope.Delivery);
				});
			};

			$scope.loadBasket();

			$scope.setCurrentShop = function (index) {

				$orderService.loadDelivery().then(function (res) {
					$scope.DeliveryRaw = res.get('deliveryRaw');

					if (angular.isDefined($scope.basket.ITEMS[index])) {
						$scope.currentShop = $scope.basket.ITEMS[index];
						$scope.DeliveryRaw.DATA = {
							TIMES: $scope.currentShop.DELIVERY_TIME,
							PRICE: $scope.DeliveryRaw.ITEMS[$scope.currentShop.SHOP_ID]['PRICE']
						};

						$rootScope.$broadcast('ul:order_time', $scope.currentShop.SHOP_ID);
					}
				});
			};
		}
	]);

	BX.appUL.controller('OrderStep3Ctrl', [
		'$scope', '$aService', '$rootScope', '$orderService', '$location', '$state',
		function ($scope, $aService, $rootScope, $orderService, $location, $state) {
			$scope.Notes = {note: [], errors: []};
			$scope.success = false;
			$scope.success = true;

			$scope.commentOrder = '';
			$scope.Profiles = $orderService.get('Profiles');
			$scope.Properties = $orderService.get('Properties');
			$scope.basket = $orderService.get('Basket');
			$scope.Delivery = $orderService.get('Delivery');

			$scope.prevStep = function () {
				$state.go('step2');
			};

			// if (BX.is.empty($scope.basket)) {
				$aService.setAction('/service/UL/Sale/Basket/getBasketForOrder').get().then(function (result) {
					$scope.basket = result.data.DATA;
				});
			// }

			if (BX.is.empty($orderService.get('formPropProfile')) || $orderService.get('formPropProfile').$valid === false) {
				$state.go('step1');
				return;
			}

			if (BX.is.empty($scope.Delivery)) {
				$state.go('step2');
				return;
			}

			$scope.deliveryPriceFormat = $orderService.get('deliveryRaw').SUM_FORMAT;
			$scope.sumOrder = $scope.basket.SUM_RAW + $orderService.get('deliveryRaw').SUM;

			$scope.nextStep = function () {

				// if(orderProperty.get('addressFormStep') == null){
				// 	$scope.success = false;
				// }

				if ($scope.success) {
					$scope.Notes = {note: [], errors: []};

					var post = {
						PROPERTIES: $scope.Properties,
						DELIVERY: $orderService.get('Delivery'),
						COMMENT: $scope.commentOrder,
						DELIVERY_RAW: $orderService.get('deliveryRaw'),
					};

					$aService.setAction('/rest/UL/Main/Order/saveOrder').post(post).then(function (result) {
						if (result.data.ERRORS != null) {
							angular.forEach(result.data.ERRORS, function (val, k) {
								$scope.Notes.errors.push(val);
							});
						} else if (result.data.DATA != null) {
							// $location.url('/final');
							window.location.replace('/personal/order/make/?order=' + result.data.DATA + '&page=final');
						}
					});
				}
			};

			$scope.$watch('basket', function () {
				if (!is.empty($scope.basket)) {
					$scope.success = true;
					angular.forEach($scope.basket.ITEMS, function (arBasket, i) {
						if (arBasket.SUM_IN_SHOP > 1000) {
							$scope.success = true;
						} else {
							$scope.success = false;
						}
					});

					if ($scope.success === false) {
						$scope.Notes.errors.push("Для формления заказа сумма корзины по каждому магазину должна быть больше или равна 1 000р.\n" +
							"Вы не можете оформить заказ.");
					}
				}
			});
		}
	]);

	BX.appUL.directive('orderTimeTab', ['$timeout', function ($timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				$scope.shopId = 0;
				$scope.$on('ul:order_time', function (ev, shopId) {
					$('.set_times').removeClass('choose');
					$('.jsTab').removeClass('active');

					if ($scope.shopId == 0) {
						element.find('.jsTab').eq(0).addClass('active');
						element.find('.jsCont').eq(0).addClass('active');
					}

					if ($scope.shopId != shopId) {
						element.removeClass('open');
					}
					if (element.hasClass('open')) {
						element.removeClass('open');
					} else {
						element.addClass('open');
					}
					$scope.shopId = shopId;

				});

				$timeout(function () {

					element.find('.jsTab').eq(0).addClass('active');
					element.find('.jsCont').eq(0).addClass('active');

					element.find('.jsTab').on('click', function () {
						element.find('.jsTab').removeClass('active');
						element.find('.jsCont').removeClass('active');
						$(this).addClass('active');
						element.find('.jsCont.content_' + $(this).data('index')).addClass('active');
					});
				}, 300);
			}
		}
	}]);

	BX.appUL.directive('choseTime', ['$timeout', function ($timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				element.click(function () {
					$('.set_times').removeClass('choose');
					$(this).addClass('choose');
				});
			}
		}
	}]);

	BX.appUL.directive('addressSuggestion', ['$rootScope', '$q', '$orderService', function ($rootScope, $q, $orderService) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				var type = attr.typelocation,
					cityValue;
				var post = {
					count: 10
				};

				$scope.sendAddress = function (post, request) {
					post.query = request.term;
					var res = $q.defer();
					$.post('/service/UL/Suggestions/getAddress', JSON.stringify(post), function (result) {
						var sResult = [];
						if (result.DATA != null && !is.undefined(result.DATA.suggestions) && is.array(result.DATA.suggestions)) {
							$.each(result.DATA.suggestions, function (k, arItem) {
								sResult.push(arItem);
							});
							res.resolve(sResult);
						}
					}, 'json');

					return res.promise;
				};


				$(element).autocomplete({
					source: function (request, response) {
						var Properties = $orderService.get('Properties');
						switch (type) {
							case 'city':
								post.from_bound = {value: "city"};
								post.to_bound = {value: "settlement"};
								$scope.sendAddress(post, request).then(function (sResult) {
									response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);
								});

								break;
							case 'street':
								post.from_bound = {value: "street"};
								post.to_bound = {value: "street"};
								// $rootScope.$on('ul_city_selected', function (ev, data) {
								// 	console.info(data);
								//
								// });
								if (BX.is.defined(Properties.CITY) && !BX.is.empty(Properties.CITY.VALUE)) {
									var tmpCity = Properties.CITY.VALUE.split(',');
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
									post.locations = [locationSearch];
								}

								$scope.sendAddress(post, request).then(function (sResult) {
									response(sResult.length === 1 && sResult[0].length === 0 ? [] : sResult);
								});
								break;
						}

					},
					minLength: 3,
				}).bind("autocompleteselect", function (ev, ui) {

					if (type == 'street') {
						var arTmp = ui.item.value.split(',');
						var valInput = arTmp[arTmp.length - 1].trim();
						valInput = valInput.replace(/ул /g, '');
						ui.item.value = valInput;
						ui.item.label = valInput;
					}

					$scope.$emit('ul_' + type + '_selected', ui.item);

				}.bind(this));

				element.on('click', function () {
					$(this).autocomplete('search');
				});

			}
		}
	}]);

	BX.appUL.directive('checkAddress', ['$rootScope', '$q', '$orderService', '$aService',
		function ($rootScope, $q, $orderService, $aService) {
			return {
				restrict: 'AE',
				scope: true,
				link: function ($scope, element, attr) {
					var Yandex = {
						polygons: [],
						currentPointObject: {},
						allMap: {}
					};
					$scope.$parent.addressIsUse = false;

					ymaps.ready(function () {
						var urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();
						BX.ajax.loadJSON(urlCords, function (result) {
							Yandex.Map = new ymaps.Map(attr.id, {
								// center: [55.756449, 37.617112],
								center: [53.195522, 50.101819],
								zoom: 9,
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

									Yandex.polygons.push(UlPolygon);

								});
							}

						});
					});

					var currentPolygon;
					$scope.$on('ul:check_address', function (ev, address) {
						if (!BX.is.undefined(address) || address != '') {
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
											searchInt++;
											currentPolygon = arPolygon;
											break;
										}
									}
								}

								if (searchInt == 0) {
									swal({
										title: '',
										text: 'Адрес не попадает в зону доставки',
										imageUrl: "/local/dist/images/x_win.png",
										imageSize: "112x112",
										customClass: 'error_window_custom',
										confirmButtonText: 'Закрыть',
									});
									$rootScope.$broadcast('ul:addres_in_not_use');
									$scope.$parent.addressIsUse = false;
								} else {
									if (is.object(currentPolygon) && is.propertyDefined(currentPolygon, 'geometry')) {
										$aService.setAction('/rest/UL/Main/Map/CoordManager/checkOrderCoords')
											.post({coords: currentPolygon.geometry.get(0)})
											.then(function (res) {
												var swalOption = {
													title: '',
													imageUrl: "/local/dist/images/x_win.png",
													imageSize: "112x112",
													customClass: 'error_window_custom err_address_order',
													confirmButtonText: 'Закрыть',
													closeOnConfirm: false,
													allowEscapeKey: false,
												};

												var urlSave = '/local/components/ul/address.set/ajax.php?set_region=Y&sessid=' + BX.bitrix_sessid();

												switch (res.data.DATA){
													case 2:
														swalOption.text = 'Адрес относится к области, в которой нет выбранного(-ых) вами магазина(-ов)';
														swal(swalOption);

														$scope.$parent.addressIsUse = false;
														$('.err_address_order .sa-confirm-button-container .confirm').on('click', function () {
															$.post(urlSave, {CORDS: currentPolygon.geometry.get(0), ADDRESS: ''}, function (data) {
																window.location.replace('/?show_cart=Y');
															}, 'json');
														});
														break;
													case 3:
														swalOption.text = 'Вы ввели адрес, относящийся к другой области доставки, цены и ассортимент могут быть изменены';
														swal(swalOption);
														$scope.$parent.addressIsUse = false;
														$('.err_address_order .sa-confirm-button-container .confirm').on('click', function () {
															$.post(urlSave, {CORDS: currentPolygon.geometry.get(0), ADDRESS: ''}, function (data) {
																window.location.replace('/?show_cart=Y');
															}, 'json');
														});
														break;
													default:
														$scope.$parent.addressIsUse = true;
												}
											});
									}
									// $scope.$parent.addressIsUse = true;
								}
							});
						}
					});

				}
			}

		}])
});
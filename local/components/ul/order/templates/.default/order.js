BX(function () {

	BX.appUL.config(function ($stateProvider, $urlRouterProvider) {

		$urlRouterProvider.otherwise("/step1");

		$stateProvider
			.state('step1', {
				url: '/step1',
				controller: 'OrderCtrl',
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

	BX.appUL.controller('OrderCtrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty', '$location','$state','$timeout',
		function ($scope, $aService, $rootScope, orderProperty, $location, $state, $timeout) {
			$scope.Properties = orderProperty.get('Properties');
			$scope.savedAddress = null;

			if (is.empty($scope.Properties)) {
				orderProperty.loadProperties().then(function (res) {
					$scope.Properties = res.get('Properties');
					$scope.Profiles = res.get('Profiles');
				});
			}



			$scope.selectAddress = 0;

			$scope.chooseAddress = function (id) {
				if(id != 0){
					angular.forEach($scope.Profiles, function (arVal, k) {
						if(arVal.ID == id){
							angular.forEach( $scope.Profiles[k]['VALUES'], function (val, i) {
								if(angular.isDefined($scope.Properties[i])){
									$scope.Properties[i]['VALUE'] = val['VALUE'];
								}
							});

							$scope.Properties['PROFILE_ID'] = id;
							$scope.Properties['PROFILE_NAME'] = $scope.Profiles[k]['NAME'];

							$scope.choseAddress = $scope.Profiles[k];
							orderProperty.set('Properties', $scope.Properties);
						}
					});

				} else {
					orderProperty.clearPropValue();
					$scope.Properties = orderProperty.get('Properties');
				}
			};

			$scope.ConfigSelect = {
				valueField: 'ID',
				labelField: 'NAME',
				create: false,
				maxItems: 1,
				onChange: function(value){
					$scope.$apply(function () {
						$scope.chooseAddress(value);
					});
				}
			};

			$scope.addressFormStep = function () {
				orderProperty.set('addressFormStep', $scope.formPropProfile);
				if ($scope.formPropProfile.$valid) {
					orderProperty.set('Properties', $scope.Properties);

					console.info($scope.Properties);

					$state.go('step2');
				}
			};

			$scope.saveAddressDB = function (Properties) {
				orderProperty.set('Properties', $scope.Properties);
				orderProperty.set('formAddress', $scope.formPropProfile);
				orderProperty.saveAddress().then(function (res) {
					var id = res.data.DATA;
					if(id > 0){
						orderProperty.loadProperties().then(function (res) {
							$scope.Properties = res.get('Properties');
							$scope.Profiles = res.get('Profiles');
							angular.forEach($scope.Profiles, function (arVal, k) {
								if(id == arVal.ID){
									$scope.choseAddress = $scope.Profiles[k];
									$scope.selectAddress = id;
									$scope.savedAddress = 1;
								}
							});
						});
					}
				});
			};


			$scope.deleteAddress = function (address) {
				orderProperty.clearPropValue();
				$scope.adr = 'N';
				angular.forEach($scope.Profiles, function (arValue, k) {
					if (arValue.ID == address.ID) {
						orderProperty.delAddress(k).then(function (res) {
							orderProperty.loadProperties().then(function (res) {
								$scope.Properties = res.get('Properties');
								$scope.Profiles = res.get('Profiles');
							});
						});

					}
				});
			};

			$scope.$watch('adr', function () {
				if($scope.adr == 'N'){
					$scope.choseAddress = $scope.Profiles[0];
					$scope.savedAddress = null;
					$scope.selectAddress = 0;
					orderProperty.clearPropValue();
					$scope.Properties = orderProperty.get('Properties');
				}
			});

		}
	]);

	BX.appUL.controller('OrderStep2Ctrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty', '$location','$state',
		function ($scope, $aService, $rootScope, orderProperty, $location, $state)
		{
			$scope.Delivery = orderProperty.get('Delivery');
			$scope.DeliveryRaw = {};
			$scope.times = {};

			$scope.Profiles = orderProperty.get('Profiles');
			$scope.Properties = orderProperty.get('Properties');
			$scope.basket = orderProperty.get('Basket');
			$scope.currentShop = {};


			if (angular.isUndefined(orderProperty.get('Properties').PROFILE_ID) && !orderProperty.get('bNewOrder')) {
				// $state.go('step1',null,{location: '/step1'});
				// orderProperty.loadProperties().then(function (res) {
				// 	$scope.Properties = res.get('Properties');
				// 	$scope.Profiles = res.get('Profiles');
				// 	$scope.Properties = res.editAddress(0).get('Properties');
				// });
				$location.url('/step1');
			}

			if(is.empty($scope.basket)){
				orderProperty.loadBasket().then(function (result) {
					$scope.basket = result.get('Basket');
				});
			}

			$scope.setCurrentShop = function (index) {
				orderProperty.loadDelivery().then(function (res) {
					$scope.DeliveryRaw = res.get('deliveryRaw');
					if(angular.isDefined($scope.basket.ITEMS[index])){
						$scope.currentShop = $scope.basket.ITEMS[index];

						$scope.DeliveryRaw.DATA = {
							TIMES: $scope.currentShop.DELIVERY_TIME,
							PRICE: $scope.DeliveryRaw.ITEMS[$scope.currentShop.SHOP_ID]['PRICE']
						};
					}
				});
			};

			$scope.prev = function () {
				$location.url('/step1');
				// $state.go('step1',null,{location: '/step1'});
			};

			$scope.next = function () {
				orderProperty.set('Basket', $scope.basket);
				orderProperty.set('Delivery', $scope.Delivery);
				$state.go('step3',{page: 'step3'},{location: '/step3'});
			};

			$scope.setTime = function (time, day) {
				$scope.Delivery.shop[$scope.currentShop['SHOP_ID']] = {
					TIME: time,
					DAY: day
				};
			};

			$scope.$watch('basket', function () {
				if(is.empty($scope.Delivery) && !is.empty($scope.basket)){
					$scope.Delivery.shop = {};
					angular.forEach($scope.basket.ITEMS, function (item, idShop) {
						$scope.Delivery.shop[idShop] = {};
						$scope.Delivery.shop[idShop]['dateSelected'] = $scope.basket.DAYS_LIST[0];
						$scope.Delivery.shop[idShop]['timeSelected'] = item.DELIVERY_TIME[0];
					});
				}
			});


		}
	]);

	BX.appUL.controller('OrderStep3Ctrl', [
		'$scope', '$aService', '$rootScope', 'orderProperty', '$location','$state',
		function ($scope, $aService, $rootScope, orderProperty, $location, $state)
		{
			$scope.Notes = {note: [], errors: []};
			$scope.success = false;

			$scope.Profiles = orderProperty.get('Profiles');
			$scope.Properties = orderProperty.get('Properties');
			$scope.basket = orderProperty.get('Basket');

			// console.info(orderProperty.get('Properties'));

			if (angular.isUndefined(orderProperty.get('Properties').PROFILE_ID) && !orderProperty.get('bNewOrder')) {
				$location.url('/step1');
				// $state.go('step1',null,{location: '/step1'});
			}

			if(angular.isUndefined(orderProperty.get('addressFormStep')) || is.null(orderProperty.get('addressFormStep'))){
				$location.url('/step1');
			}

			if(is.empty($scope.basket)){
				orderProperty.loadBasket().then(function (result) {
					$scope.basket = result.get('Basket');
				});
			}

			$scope.prev = function () {
				$state.go('step2');
				// $location.url('/step2');
			};

			$scope.next = function () {

				if(orderProperty.get('addressFormStep') == null){
					$scope.success = false;
				}

				if($scope.success){
					$scope.Notes = {note: [], errors: []};

					var post = {
						PROPERTIES: $scope.Properties,
						DELIVERY: orderProperty.get('Delivery'),
						COMMENT: $scope.commentOrder
					};

					$aService.setAction('/rest/UL/Main/Order/saveOrder').post(post).then(function (result) {
						if(result.data.ERRORS != null){
							angular.forEach(result.data.ERRORS, function (val, k) {
								$scope.Notes.errors.push(val);
							});
						} else if (result.data.DATA != null){
							// $location.url('/final');
							window.location.replace('/personal/order/make/?order=' + result.data.DATA + '&page=final');
						}
					});
				}
			};

			$scope.$watch('basket', function () {
				if(!is.empty($scope.basket)){
					$scope.success = true;
					angular.forEach($scope.basket.ITEMS, function (arBasket, i) {
						if(arBasket.SUM_IN_SHOP > 1000){
							$scope.success = true;
						} else {
							$scope.success = false;
						}
					});

					if($scope.success === false){
						$scope.Notes.errors.push("Для формления заказа сумма корзины по каждому магазину должна быть больше или равна 1 000р.\n" +
							"Вы не можете оформить заказ.");
					}
				}
			});

			orderProperty.loadDelivery().then(function (res) {
				$scope.deliveryPrice = res.get('deliveryPrice');
				$scope.deliveryPriceFormat = res.get('deliveryPriceFormat');
			});
		}
	]);

	BX.appUL.directive('orderTimeTab', ['$timeout',function ($timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				$timeout(function () {
					element.find('.jsTab').eq(0).addClass('active');
					element.find('.jsCont').eq(0).addClass('active');

					element.find('.jsTab').click(function () {
						element.find('.jsTab').removeClass('active');
						element.find('.jsCont').removeClass('active');
						$(this).addClass('active');
						element.find('.jsCont.content_'+ $(this).data('index')).addClass('active');
					});
				}, 300);
			}
		}
	}]);

	BX.appUL.directive('choseTime', ['$timeout',function ($timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				element.click(function () {
					$('.set_times').removeClass('choose');
					$(this).closest('.set_times').addClass('choose');
				});
			}
		}
	}]);
});
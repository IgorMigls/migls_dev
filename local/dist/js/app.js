BX(function () {

	var swalOption = {
		title: '',
		imageUrl: "/local/dist/images/x_win.png",
		imageSize: "112x112",
		customClass: 'error_window_custom err_address_order',
		confirmButtonText: 'Закрыть',
	};

	BX.is = is;
	BX.is.defined = function (data) {
		return !BX.is.undefined(data);
	};

	BX.appUL = angular.module('appUL', [
		'ngResource', 'ngAnimate', 'ajax.service', 'ui.router', 'angularFileUpload',
		'selectize', 'ngJScrollPane'
	]);

	BX.appUL.config(function ($aServiceProvider) {
		$aServiceProvider.setParams({
			url: '/service/UL/Sale/Basket'
		});
	});

	BX.appUL.directive('basketAdd', ['$aService', '$rootScope', function ($aService, $rootScope) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				$aService.setUrlService('/rest2/basket');

				if (is.nan($scope.quantity) || is.undefined($scope.quantity)) {
					$scope.quantity = 1;
				}

                if (is.nan($scope.ratio) || is.undefined($scope.ratio)) {
                    $scope.ratio = 1;
                }
                
                $scope.changeTimeout = false;


				$scope.$on('ul:basket', function (ev, data) {
					if(attr.basket !== undefined){
						$.each(data.ITEMS, function (code, arShop) {
							$.each(arShop.BASKET, function (id, itemBasket) {
								if(parseInt(itemBasket.PRODUCT_ITEM.PRODUCT_IB_ID) === parseInt(attr.basket)){
									$scope.quantity = itemBasket.QUANTITY;
								}
							});
						});
					}
				});

                $scope.validateQuantity = function () {

                    $scope.ratio = parseFloat($scope.ratio);
                    $scope.quantity = Number($scope.quantity);

                    if (($scope.quantity / $scope.ratio) % 1 != 0) {

                        $scope.quantity = $scope.ratio * Math.ceil($scope.quantity / $scope.ratio);

                        if ($scope.quantity < $scope.ratio) {
                            $scope.quantity = $scope.ratio;
                        }
                    }
                };


				$scope.changeQuantity = function (type, recalc) {

					$scope.quantity = Number($scope.quantity);
					$scope.ratio = parseFloat($scope.ratio);

					if (type == '+') {
						$scope.quantity += $scope.ratio;
					} else {
						$scope.quantity -= $scope.ratio;
					}

					if ($scope.quantity <= 0) {
						$scope.quantity = $scope.ratio;
					}

					if ($scope.quantity >= 100) {
						$scope.quantity = 100;
					}

					if (recalc) {
						recalc.QUANTITY = $scope.quantity;
						$aService.action('recalcBasketItem').post(recalc).then(function (result) {
							if (result.data.STATUS == 1 && angular.isDefined(result.data.DATA)) {
								$rootScope.$broadcast('ul:basket', result.data.DATA);
								// $rootScope.$broadcast('reinit-pane', 'basketScrollPane');
							} else if(result.data.ERRORS !== null){
								swalOption.text =  result.data.ERRORS.join(', ');
								swal(swalOption);
							}
						});
					}
				};

				$scope.addBasket = function (id, productId) {

					var qElement = element.find('.quantity_input');


					var post = {
						quantity: $scope.quantity,
						product: {
							ID: id,
							PRODUCT_ID: productId
						},
						sku: id,
					};

					window.MigBus.$emit('addToBasket', post);

					// $aService
					// 	.action('addBasketById')
					// 	.post({PRODUCT: id, QUANTITY: $scope.quantity})
					// 	.then(function (result) {
					//
					// 		if (result.data.STATUS == 1) {
					// 			var q = parseInt(result.data.DATA.current.QUANTITY);
					//
					// 			$rootScope.$broadcast('ul:basketAdd', result.data.DATA);
					//
					// 			var prev = element.closest('.b-product-preview');
					//
					// 			prev.addClass('b-product-preview_actvie');
					//
					// 			if (prev.find('.b-product__count.b-ib .b-count__in').length == 0) {
					// 				prev.find('.b-product__count.b-ib')
					// 					.append('<span class="b-count__in"> ' + q + ' шт. в корзине</span>')
					// 			} else {
					// 				prev.find('.b-product__count.b-ib .b-count__in').text(' ' + q + ' шт. в корзине');
					// 			}
					// 		}else if(result.data.ERRORS !== null){
					// 			swalOption.text =  result.data.ERRORS.join(', ');
					// 			swal(swalOption);
					// 		}
					//
					// 	});
				};
			}
			// controller: 'BasketCtrl'
		}
	}]);

	BX.appUL.controller('BasketCtrl', ['$scope', '$aService', function ($scope, $aService) {
		$scope.basketItems = {};
		$scope.$on('ul:basketAdd', function (ev, data) {

			$scope.basketItems = data.items;
			$scope.basketCurrent = data.current;

			// $.magnificPopup.open({
			// 	items: {
			// 		src: '#add_cart_check',
			// 		type: 'inline',
			// 		enableEscapeKey: true,
			// 		showCloseBtn: false
			// 	}
			// });
		});

		$scope.$on('ul:basket', function (ev, data) {
			$scope.basketItems = data;
		});
		// $scope.$on('ul:basketReInit', function (ev, data) {});
	}]);

	BX.appUL.directive('basketSmall', ['$aService', '$rootScope', '$timeout', function ($aService, $rootScope, $timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				$aService.setUrlService('/service/UL/Sale/Basket');
				$scope.currentReplace = {};
				$scope.ReplacementItems = null;
				$scope.search = '';
				$scope.noSearchItems = false;

				$aService.action('getBasketUser').post().then(function (res) {
					$scope.basketItems = res.data.DATA;
				});

				$scope.paneConfig = {verticalDragMinHeight: 300};

				var popup = $.magnificPopup.instance;

				$rootScope.$on('$stateChangeStart',
					function (event, toState, toParams, fromState, fromParams) {
						if (toState.name == 'product') {
							popup.close();
						}
					});

				$scope.showBasket = function () {
					$.magnificPopup.instance.popupsCache = {};

					$aService.action('getBasketUser').post().then(function (res) {
						// $scope.basketItems = res.data.DATA;

						$rootScope.$broadcast('ul:basket', res.data.DATA);
						popup.open({
							items: {
								src: '#show_cart',
								type: 'inline',
								enableEscapeKey: true,
								showCloseBtn: false,
								closeOnBgClick: true,
								mainClass: 'show_cart_win'
							},
							key: 'basket',
							callbacks: {
								open: function () {
									$('#show_cart').closest('.mfp-content').css({'vertical-align': 'top'});
									var container = $('.b-popup-cart__content-wrapper');
									container.height($(window).height() - 70);

									$rootScope.$broadcast('reinit-pane', 'basketScrollPane');
									$('#replacement .b-popu-card_cart_ch').removeClass('fadeInLeft').addClass('noactive');

									// $scope.initScroll(container);
									// container.resize(function () {
									// 	$scope.initScroll($(this));
									// });
								}
							}
						});
					});

				};

				$scope.closeIntervals = function () {
					$('.shop_time_window').hide(0);
				};

				$scope.showIntervals = function(shopId){
					// popup.close();
					$scope.closeReplaceWindow();

					$('#shop_time_window_' + shopId).fadeIn(300);

					$('.b-button.interval__date').on('click', function (ev) {
						ev.preventDefault();
						$('.time_items.active').hide(0).removeClass('active');

						$('#' + $(this).data('timeId')).fadeIn(400, function () {
							$(this).addClass('active');
						});
					});


				};

				$scope.delItem = function (basket) {

					var parent = $('#pr_' + basket.PRODUCT_ITEM.PRODUCT_IB_ID);
					parent.find('.b-product__count').html('');
					parent.find('.b-product-preview_actvie').removeClass('b-product-preview_actvie');
					parent.find('.border_in_basket').removeClass('border_in_basket');

					$aService.action('delItem').post(basket).then(function (result) {
						if (result.data.STATUS == 1 && angular.isDefined(result.data.DATA)) {
							$rootScope.$broadcast('ul:basket', result.data.DATA);


							if (angular.isUndefined(result.data.DATA.ITEMS)) {
								popup.close();
							}

							$rootScope.$broadcast('reinit-pane', 'basketScrollPane');
						}
					});
				};

				$scope.addCommentItem = function (basket) {
					var dataPost = {
						NAME: 'COMMENT',
						VALUE: basket.COMMENT,
						CODE: 'COMMENT',
						BASKET_ID: basket.ID,
						SORT: 100
					};

					$aService.action('saveProperty').post(dataPost).then(function (result) {
						if (result.data.ERRORS == null) {
							$('#comm_product' + basket.ID).click();
							$('#comment_show' + basket.ID).show(0);
							basket.COMMENTARY = basket.COMMENT;
						}
					});
					$rootScope.$broadcast('reinit-pane', 'basketScrollPane');
				};

				$scope.disableComment = function (container) {
					$(container).removeClass('open');
					if ($(container).find('textarea').val() != '') {
						$(container).find('.cart__comments').show(0);
					}

				};

				$scope.toggleElem = function (container) {

					if (!$(container).hasClass('open_tg')) {
						$(container).fadeIn(200).addClass('open_tg');
					} else {
						$(container).fadeOut(200).removeClass('open_tg');
					}
					$rootScope.$broadcast('reinit-pane', 'basketScrollPane');
				};

				$scope.addReplacement = function (item) {
					$scope.closeIntervals();
					$aService.action('getReplacementAction').post(item).then(function (result) {
						$scope.ReplacementItems = result.data.DATA;
						$scope.currentReplace = item;

						if(result.data.DATA.length == 0){
							$scope.ReplacementItems = null;
						}

						var $replacement = $('#replacement .b-popu-card_cart_ch');
						if($replacement.hasClass('noactive') || $replacement.hasClass('fadeOutLeft')){
							$replacement.removeClass('fadeOutLeft').removeClass('noactive').addClass('fadeInLeft');
						} else {
							$replacement.removeClass('fadeInLeft').addClass('fadeOutLeft');
						}
					});
				};

				$scope.addReplacementItem = function (item) {
					$aService.action('replaceItemAction').post({replace: item, current: $scope.currentReplace}).then(function (result) {
						// $rootScope.$broadcast('ul:basketAdd', result.data.DATA);
						if(result.data.STATUS == 1){
							if(!is.object($scope.currentReplace.replace)){
								$scope.currentReplace.replace = {};
							}
							$scope.currentReplace.replace[result.data.DATA] = {
								ID: result.data.DATA,
								NAME: item.PRODUCT_NAME
							};
						}

						$('#replacement .b-popu-card_cart_ch').removeClass('fadeInLeft').addClass('fadeOutLeft');
					});
				};

				$scope.deleteReplace = function (basketItem, id) {
					$aService.action('deleteReplace').post({basket: basketItem, id: id}).then(function (result) {
						if(result.data.STATUS == 1){
							delete basketItem.replace[id];
							if(is.empty(basketItem.replace)){
								delete basketItem.replace;
							}
						}
					});
					$rootScope.$broadcast('reinit-pane', 'basketScrollPane');
				};

				$scope.closeReplaceWindow = function () {
					$('#replacement .b-popu-card_cart_ch').removeClass('fadeInLeft').addClass('fadeOutLeft');
				};

				$scope.searchReplace = function () {
					if($scope.search.length >= 3){
						$scope.ReplacementItems = [];
						$scope.noSearchItems = false;
						var process = false;
						setTimeout(function () {
							if(!process){
								process = true;
								$aService.action('searchReplace').post({q: $scope.search}).then(function (result) {
									if(result.data.DATA != null){
										angular.forEach(result.data.DATA, function (arItem, k) {
											$scope.ReplacementItems.push(arItem.SKU);
											$scope.noSearchItems = 1;
										});
										if(result.data.DATA.length == 0){
											$scope.noSearchItems = 0;
										}
										process = false;
									}
								});
							}

						}, 400);
					}
				};

				$scope.$on('ul:basketAdd', function (ev, data) {
					$scope.current = data.current;
					$scope.basketItems = data.items;

					$('#basket_add_msg')
						.hide(0)
						.fadeIn(300, function () {
							$timeout(function () {
								$('#basket_add_msg').fadeOut(300);
							}, 2000);
						});
				});

				// $scope.$watch('ReplacementItems', function () {
				// 	console.info($scope.ReplacementItems);
				// });

				$('#addToOrder').on('click', function (ev) {
					ev.preventDefault();
					popup.close();
					if(attr.auth == 0){
						setTimeout(function () {
							$('#btn_auth_form_top').click();
						}, 100);
					} else {
						var isReplaceUsed = 0;

						angular.forEach($scope.basketItems.ITEMS, function (Shop, k) {
							angular.forEach(Shop.BASKET, function (basket, kk) {
								if(BX.is.object(basket.replace)){
									isReplaceUsed++;
								}
							})
						});

						if(isReplaceUsed == 0){
							var $hren = $('.popup_replace_2_overlay');
							$hren.show(0);
							$('#continue_products').on('click', function () {
								$hren.hide(0);
								setTimeout(function () {
									$scope.showBasket();
								})
							});
							$('#confirm_orders').on('click', function () {
								window.location.assign('/personal/order/make/');
							});
						} else {
							window.location.assign('/personal/order/make/');
						}
					}
				});

				var regex = /show_cart/gi;
				if(regex.test(window.location.search)){
					$timeout(function () {
						$scope.showBasket();
					}, 800);
				}

			},
			controller: 'BasketCtrl'
		}
	}]);

	BX.appUL.directive('detailProduct', [
		'$location', '$timeout', '$state', '$rootScope',
		function ($location, $timeout, $state, $rootScope) {
			return {
				scope: true,
				restrict: 'AE',
				link: function ($scope, element, attr) {

					element.fadeIn(300);

					var bodyDoc = $('body, html');

					$scope.scrollNow = bodyDoc.scrollTop();
					bodyDoc.scrollTop(60);
					// bodyDoc.animate({
					// 	scrollTop: 60
					// }, 300);

					$scope.closeProduct = function () {
						if(window.location.pathname == '/personal/'){
							// var tmpLoc = $location.path().split('/');
							// tmpLoc.pop();
							// $location.path(tmpLoc.join('/'));

							$state.go('ordersDetail');
						} else {
							$state.go('index');
						}

						// $location.url('/catalog/');

						// $location.url('/');


						element.fadeOut(0);
						bodyDoc.scrollTop($scope.scrollNow);
						// bodyDoc.animate({
						// 	scrollTop: $scope.scrollNow
						// }, 300);


					};
					$scope.productSlider = element.find('.js-products-slider-' + $state.params.id);
					$scope.productSliderOptions = {
						dots: true,
						arrows: true,
						slide: '.js-products-slider-item',
						slidesToShow: 4,
						slidesToScroll: 1,
						adaptiveHeight: true
						// autoplaySpeed: 5000,
						// autoplay: true
					};

					if ($scope.productSlider.length > 0) {
						$scope.productSlider.slick($scope.productSliderOptions);
					}

					$scope.reviwe = $('.js-products-slider-6');
					$scope.reviweOptions = {
						dots: true,
						arrows: true,
						slide: '.js-products-slider-item',
						slidesToShow: 1,
						slidesToScroll: 1,
						variableWidth: true
						// autoplaySpeed: 5000
					};
					if ($scope.reviwe.length > 0) {
						$scope.reviwe.slick($scope.reviweOptions);
					}

					element.find('.js-tab').click(function () {
						var _this = $(this),
							tabs = _this.parents('.js-tabs'),
							tabsWrapper = element.find(tabs.data('tabsWrapper'));

						tabs.find('.js-tab').removeClass('active');
						tabsWrapper.find('.js-tab-content').removeClass('active');
						_this.addClass('active');
						$(_this.data('tab')).addClass('active');
						$scope.productSlider.slick('setPosition');
					});

					$('.zummer_img').zoom();
				},
				controller: 'ProductCtrl'
			}
		}]);


	BX.appUL.directive('zummer', [function () {
		return {
			scope: true,
			restrict: 'A',
			link: function ($scope, element, attr) {
				element.zoom({url: attr.original});
			}
		}
	}]);

	BX.appUL.controller('ProductCtrl', [
		'$scope', '$aService', '$rootScope',
		function ($scope, $aService, $rootScope) {
			$scope.Product = {};
		}
	]);


	BX.appUL.config(function ($stateProvider, $urlRouterProvider) {
		// $urlRouterProvider.otherwise("/");
		$stateProvider
			.state('product', {
				url: '/catalog/:id',
				views: {
					product: {
						controller: 'ProductCtrl',
						templateUrl: function (stateParams) {
							var url = '/local/route/view/product.php';
							url = BX.util.add_url_param(url, stateParams);
							url = BX.util.add_url_param(url, {back: window.location.pathname});
							return url;
						}
					}
				}
			})
			.state('index', {
				url: '/',
				views: {
					product: {
						controller: function ($scope) {

						},
						template: '<div></div>'
					}
				}
			})
	});

	// BX.appUL.config(function ($aServiceProvider) {
	// 	$aServiceProvider.setParams({
	// 		url: '/service/UL/Main/Services/Favorite'
	// 	});
	// });

	BX.appUL.directive('addFavorite', ['$aService', function ($aService) {
		return {
			scope: {addFavorite: '='},
			restrict: 'AE',
			link: function ($scope, element, attr) {

				$aService.setUrlService('/service/UL/Main/Services/Favorite');

				var inVaforite = false;

				$scope.addToFavorite = function (id) {
					if (!inVaforite) {
						$aService.action('addToFavorite').post({ID: id}).then(function (result) {
							if (result.data.STATUS === 1) {
								element.text('В избранном');
								element.addClass('in_favorite');
							} else {
								swal({
									title: '',
									// text: result.data.ERRORS.join(', '),
									text: 'Ошибка добавления в избранное',
									imageUrl: "/local/dist/images/x_win.png",
									imageSize: "112x112",
									customClass: 'error_window_custom',
									confirmButtonText: 'Закрыть',
								});
							}
						});
					}
				};

				$scope.getFavorite = function () {
					$aService.action('getFavorite').post({ID: $scope.addFavorite}).then(function (result) {
						if (result.data.DATA != null && angular.isDefined(result.data.DATA.ID)) {
							element.text('В избранном');
							inVaforite = true;
						}
					});
				};

				$scope.getFavorite();

				element.on('click', function (ev) {
					ev.preventDefault();
					$scope.addToFavorite($scope.addFavorite);
				});
			}
		}
	}]);

	BX.appUL.directive('favoriteProduct', ['$aService', function ($aService) {
		return {
			scope: {favoriteProduct: '='},
			restrict: 'AE',
			link: function ($scope, element, attr) {

				$aService.setUrlService('/service/UL/Main/Services/Favorite');

				var inVaforite = false;

				$scope.addToFavorite = function (id) {
					if (!inVaforite) {
						$aService.action('addToFavorite').post({ID: id}).then(function (result) {
							if (result.data.STATUS === 1) {
								element.addClass('active');
							} else {
								swal({
									title: '',
									text: result.data.ERRORS.join(', '),
									imageUrl: "/local/dist/images/x_win.png",
									imageSize: "112x112",
									customClass: 'error_window_custom',
									confirmButtonText: 'Закрыть',
								});
							}
						});
					}
				};

				$scope.getFavorite = function () {
					$aService.action('getFavorite').post({ID: $scope.favoriteProduct}).then(function (result) {
						if (result.data.DATA != null && angular.isDefined(result.data.DATA.ID)) {
							element.addClass('active');
							inVaforite = true;
						}
					});
				};

				// $scope.getFavorite();
				element.find('.prod__star').on('click', function (ev) {
					ev.preventDefault();
					$scope.addToFavorite($scope.favoriteProduct);
				});
			}
		}
	}]);

	BX.appUL.directive('selectizeEx', ['$timeout', function ($timeout) {
		return {
			scope: {selectizeEx: '='},
			restrict: 'A',
			link: function ($scope, element, attr) {
				$scope.$watch($scope.selectizeEx, function () {
					if (angular.isDefined($scope.selectizeEx) && $scope.selectizeEx.length > 0) {
						element.selectize({
							readOnly: true,
							// items: $scope.selectize,
							onDelete: function () {
								return false
							}
						});
					}
				});
			}
		}
	}]);

	BX.appUL.directive('mask', [function () {
		return {
			scope: {mask: '@'},
			restrict: 'AE',
			link: function ($scope, element, attr) {
				var options = {};
				var mask = $scope.mask;

				switch (attr.typemask) {
					case 'day':
						options = {
							'translation': {
								D: {pattern: /[1-3]/},
								M: {pattern: /[1-9]/}
							}
						};
						mask = 'DM';
						break;
				}


				element.mask(mask, {
					'translation': {
						D: {pattern: /[1-3]/},
						M: {pattern: /[1-9]/}
					}
				});
			}
		}
	}]);

	BX.appUL.factory('orderProperty', ['$aService', '$rootScope', '$q', function ($aService, $rootScope, $q) {

		var orderProperty = function () {

			var self = this;

			this.url = '/rest/UL/Main/Personal/Address';
			this.data = {
				Properties: {},
				Profiles: [],
				Basket: {},
				formAddress: {},
				Delivery: {},
				bNewOrder: false,
				deliveryPrice: 0,
				deliveryPriceFormat: '',
				deliveryRaw: {},
			};

			this.loadProperties = function () {
				this.Deferred = $q.defer();

				$aService.setAction(this.url + '/getData').get().then(function (result) {
					self.data.Properties = result.data.DATA.props;
					if (self.data.Profiles.length == 0) {
						self.data.bNewOrder = true;
					}

					var profiles = [];
					profiles.push({
						ID: 0,
						NAME: 'Выбрать',
						PERSON_TYPE_ID: 1,
						USER_ID: 0,
						VALUES: {},
						VALUE_FORMAT: 'Выбрать'
					});

					angular.forEach(result.data.DATA.profiles, function (val, k) {
						profiles.push(val);
					});

					if (angular.isDefined(self.data.formAddress) && !is.empty(self.data.formAddress)) {
						self.data.formAddress.$setPristine();
					}

					self.data.Profiles = profiles;
					self.Deferred.resolve(self);

				});

				return self.Deferred.promise;
			};

			this.saveAddress = function () {
				var defAddress = $q.defer(), result = null;
				if (this.get('formAddress') && this.get('formAddress').$valid) {
					$aService.setAction(this.url + '/saveAddress').post(this.get('Properties')).then(function (res) {
						result = res;
						defAddress.resolve(result);
					});
				}

				return defAddress.promise;
			};

			this.editAddress = function (index) {
				if (angular.isDefined(self.data.Profiles[index])) {
					angular.forEach(self.data.Profiles[index]['VALUES'], function (val, k) {
						if (angular.isDefined(self.data.Properties[k])) {
							self.data.Properties[k]['VALUE'] = val['VALUE'];
						}
					});
					self.data.Properties['PROFILE_ID'] = self.data.Profiles[index]['ID'];
					self.data.Properties['PROFILE_NAME'] = self.data.Profiles[index]['NAME'];
				}

				return this;
			};

			this.delAddress = function (index) {
				if (angular.isDefined(self.data.Profiles[index])) {
					return $aService.setAction(self.url + '/delete').post({ID: self.data.Profiles[index]['ID']});
				}
			};

			this.clearPropValue = function () {
				angular.forEach(self.data.Properties, function (value, k) {
					self.data.Properties[k]['VALUE'] = '';
				});

				self.data.Properties['PROFILE_ID'] = 0;
				self.data.Properties['PROFILE_NAME'] = '';
			};

			this.loadBasket = function () {
				var Deferred = $q.defer();

				$aService.setAction('/rest/UL/Sale/Basket/basketUser').get().then(function (result) {
					self.set('Basket', result.data.DATA);
					Deferred.resolve(self);
				});

				return Deferred.promise;
			};

			this.loadDelivery = function () {
				this.Deferred = $q.defer();

				if (!is.empty(this.get('Delivery'))) {
					var shopIds = [];
					angular.forEach(this.get('Delivery').shop, function (val, shopId) {
						shopIds.push(shopId)
					});
					$aService.setAction('/rest/UL/Main/Order/getDeliveryItems').post(shopIds).then(function (result) {
						if (result.data.DATA != null) {
							self.set('deliveryPrice', result.data.DATA.SUM);
							self.set('deliveryPriceFormat', result.data.DATA.SUM_FORMAT);
							self.set('deliveryRaw', result.data.DATA);
							self.Deferred.resolve(self);
						}
					});
				}

				return self.Deferred.promise;
			};


			this.get = function (prop) {

				if (angular.isDefined(self.data[prop])) {
					return self.data[prop];
				}

				return null;
			};

			this.set = function (k, val) {
				this.data[k] = val;

				return this;
			}
		};

		return new orderProperty();

	}]);


});

$(function () {
	$('#sortSelectCatalog').change(function () {
		window.location.assign($(this).val());
	});

	$('#basketScrollPane').height($(window).height() - 70);
});
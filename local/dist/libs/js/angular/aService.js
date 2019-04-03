angular.module('ajax.service', ['ngResource'])
	.provider('$aService', [function () {

		var options = {};

		this.addParam = function (k, val) {
			options[k] = val;
		};

		this.setParams = function (param) {
			options = param;
		};

		if(angular.isUndefined(options.url)){
			options.url = '/service';
		}


		this.$get = [
			'$resource', '$q', '$http', '$rootScope',
			function ($resource, $q, $http, $rootScope) {

				this.errors = {};
				this.url = '';
				this.ss = '';
				this.items = {};

				var self = this;

				this.addOption = function (k, val) {
					options[k] = val;
				};

				this.setUrlService = function (url) {
					options.url = url;
				};

				$http.defaults.transformRequest.push(function (data) {
					self.resetErrors();
					var post = angular.fromJson(data);

					var out = {};
					if(!is.undefined(post)){
						out = post;
					}

					if(!is.empty(self.ss)){
						out['sessin'] = self.ss;
					}

					$rootScope.$broadcast('as:start');

					return angular.toJson(out);
				});

				$http.defaults.transformResponse.push(function (data) {
					$rootScope.$broadcast('as:stop');
					return data;
				});


				this.setErrors = function (arErrors) {
					this.resetErrors();
					this.errors = arErrors;
					$rootScope.$broadcast('as:setErrors', arErrors);
				};

				this.addError = function (k, val) {

					if(is.undefined(this.errors[k]) || is.empty(this.errors[k])){
						this.errors[k] = [];
					}

					this.errors[k].push(val);

					$rootScope.$broadcast('as:addError', this.getErrors());
				};

				this.resetErrors = function () {
					this.errors = {};
					$rootScope.$broadcast('as:resetError');
				};

				this.getErrors = function (name) {
					if (name && is.propertyDefined(this.errors, name)) {
						return this.errors[name];
					}

					return this.errors;
				};

				this.action = function (action) {
					this.setUrl(options.url + '/' + action);

					return this;
				};

				/**
				 *
				 * @param action
				 * @returns {*}
				 */
				this.setAction = function(action){
					 self.setUrl(action);

					return this;
				};

				this.post = function(data){
					return $http.post(self.getUrl(), data);
				};

				this.get = function(data){
					this.setUrl(BX.util.add_url_param(this.getUrl(), data));
					return $http.get(this.getUrl());
				}

				this.setUrl = function (url) {
					this.url = url;
				};

				this.getUrl = function () {
					return this.url;
				}

				this.addItems = function (k, items) {
					this[k] = items;
				}

				this.getItems = function (k) {
					if(angular.isDefined(this.items[k])){
						return this.items[k];
					}

					return this.items;
				};

				return this;
			}
		];

	}])
	.directive('aServiceLoader', function ($aService, $timeout) {
		return {
			restrict: 'AE',
			scope: {aServiceLoader : '='},
			templateUrl: '/local/route/view/loader.html',
			link: function ($scope, element, attr) {

				if(is.propertyDefined($scope.aServiceLoader, 'overlay')){
					element.find('#as_overlay').hide(0);
				}

				var loaderEl = element.find('.as_loader .spinner');
				if(is.propertyDefined($scope.aServiceLoader,'loader')){
					if(is.propertyDefined($scope.aServiceLoader.loader, 'class')){
						loaderEl.html('<i class="'+ $scope.aServiceLoader.loader.class +'"></i>');
					} else if(is.propertyDefined($scope.aServiceLoader.loader, 'img')){
						loaderEl.html('<img id="as_spinner" src="'+ $scope.aServiceLoader.loader.img +'" />');
					}
				}

				$scope.loaderPosition = function () {
					var width, height, offsetTop, offsetLeft, loader = element.find('.as_loader');
					width = loader.width();
					height = loader.height();
					loader.css({
						'margin-top': '-'+ height/2 +'px',
						'margin-left': '-'+ width/2 + 'px'
					});
				};

				$scope.loaderShow = function () {
					if(is.propertyDefined($scope.aServiceLoader, 'overlay')){
						element.find('#as_overlay').css($scope.aServiceLoader.overlay).fadeIn(300, function () {
							element.find('.as_loader').fadeIn(300);
						});
					} else {
						element.find('.as_loader').fadeIn(300);
					}

					$scope.loaderPosition();
				};

				$scope.loaderHide = function () {
					if(is.propertyDefined($scope.aServiceLoader, 'overlay')){
						element.find('#as_overlay').css($scope.aServiceLoader.overlay).fadeOut(200, function () {
							element.find('.as_loader').fadeOut(100);
						});
					} else {
						element.find('.as_loader').fadeOut(100);
					}
				};

				$scope.$on('as:start', function () {
					$scope.loaderShow();
				});
				$scope.$on('as:stop', function () {
					$scope.loaderHide();
				});
			}
		}
	});
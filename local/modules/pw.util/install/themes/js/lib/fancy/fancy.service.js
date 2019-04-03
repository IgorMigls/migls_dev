angular.module('fancyBox', ['ngResource'])
	.provider('$fancyBox', function () {
		var fancyOptions = {
			padding: 0,
			autoSize: false,
			autoCenter: false,
			fitToView: true,
			width: 600,
			autoHeight: true
		};
		var fancyGroup;

		this.setOptions = function(group, options){
			fancyOptions = options;
			fancyGroup = group;
		};

		var _this = this;

		this.$get = [
			'$http','$q','$rootScope','$compile','$rootElement','$controller',
			function ($http, $q, $rootScope, $compile, $rootElement, $controller) {

				var Fancy = function () {
					this.options = fancyOptions;
					this.group = fancyGroup;

					var FancyNow = this;

					this.fancyApp = function (group, options) {
						var defer = $q.defer();

						if(options){
							angular.forEach(options, function (val, k) {
								FancyNow.options[k] = val;
							});
						}

						if(group)
							FancyNow.group = group;

						if(FancyNow.options.href && FancyNow.options.type == 'ajax'){
							$http({
								method: 'GET',
								url: FancyNow.options.href,
								headers: {'Content-Type':'application/x-www-form-urlencoded;charset=utf-8'}
							}).success(function (result) {
								defer.resolve(result);
							}).error(function (data) {
								defer.reject(data);
							});
						} else {
							defer.resolve(FancyNow.group);
						}

						return defer.promise;
					};

					this.open = function(group, options){
						var defer = $q.defer();

						this.fancyApp(group, options).then(function (data) {
							FancyNow.group = data;

							var newScope,
								modalHtml = $(FancyNow.group),
								CtrlName = 'FancyCtrl';

							if(angular.isDefined(FancyNow.options.Ctrl)){

								if(FancyNow.options.Ctrl.name)
									CtrlName = FancyNow.options.Ctrl.name;

								if(FancyNow.options.Ctrl.scope)
									newScope = FancyNow.options.Ctrl.scope;

							} else {
								newScope = $rootScope.$new(true);
							}

							CtrlName = 'FancyCtrl';
							modalHtml.attr('ng-controller', CtrlName);

							if(FancyNow.options.resolve && angular.isFunction(FancyNow.options.resolve)){

								angular.forEach(FancyNow.options.resolve(), function (value, k) {
									newScope[k] = value;
								});

							}

							FancyNow.Fancy = $.fancybox;

							newScope.$close = function () {
								FancyNow.Fancy.close();
							};

							newScope.$ok = function (data) {
								var defer = $q.defer();
								FancyNow.Fancy.close();
								//newScope['fancyResult'] = data;

								if(angular.isDefined(FancyNow.options.WelcomeCtrl)){
									FancyNow.options.WelcomeCtrl.scope['fancyResult'] = data;
								}
								defer.resolve(data);

								return defer.promise;
							};


							$rootElement.append($compile(modalHtml)(newScope));

							delete FancyNow.options.type;
							delete FancyNow.options.href;

							//$controller(CtrlName, {$scope: newScope});

							//var testCtrl = angular.element($rootElement.context.lastChild);
							//console.info(testCtrl.scope().activateView($rootElement.context.lastChild));

							FancyNow.Fancy.open($rootElement.context.lastChild, FancyNow.options);

							defer.resolve(newScope);
						});

						return defer.promise;
					};

				};

				return new Fancy();
			}
		];
	})
		.controller('FancyCtrl',[
			'$scope','$fancyBox','$compile',
			function ($scope, $fancyBox, $compile) {
				$scope.activateView = function (element) {
					$compile(element)($scope);
				}
			}
		])
		.directive('fancy', [function () {
				return {
					scope: true,
					restrict: 'A',
					link: function ($scope, element, attr) {

					},
					controller: 'FancyCtrl'
				}
			}]);
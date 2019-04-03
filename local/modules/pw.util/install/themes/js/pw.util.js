//BX(function () {
	BX.MapGenerator = angular.module('MapGenerator', ['ngResource','ui.bootstrap','ajax.service']);

	BX.MapGenerator.config(function (EAjaxProvider) {
		EAjaxProvider.setUrl('/local/modules/pw.util/tools/ajax.php');
		EAjaxProvider.setTrace(true);
	});

	BX.MapGenerator.directive('generator', ['EAjax', function (EAjax) {
		return{
			scope: true,
			restrict: 'E',
			link: function ($scope, element, attr) {

			},
			controller: function ($scope, EAjax) {

				$scope.Tables = [];
				$scope.Gen = {
					table: '',
					Fields: {
						validate: true
					},
					Lang: [],
					errors: [],
					test: false
				};

				if($scope.Gen.test === true){
					$scope.Gen.table = 'pw_landing_parts';

					$scope.Gen.Fields = {
						use_validators: true,
						module: 'pw.util',
						namespace: 'PW\\Landing',
						class: 'Parts',
						lib: '/'
					};
				}

				EAjax.setClassName('PW\\Tools\\Generator\\Map');

				$scope.getTables = function () {
					EAjax.get({ACTION: 'getAllTables'}).then(function (result) {
						$scope.Tables.items = result.DATA;
					});
				};

				$scope.chooseTable = function () {
					$scope.Gen.table = $scope.Tables.selected;
				};

				$scope.generateModel = function () {
					if($scope.PwGenerator_form.$valid === true){
						$scope.Gen.Fields.table = $scope.Gen.table;
						EAjax.post({
							ACTION:'generateModel',
							DATA: {FIELDS: $scope.Gen.Fields, LANG: $scope.Gen.TableFields}
						}).then(function (result) {
							if(result.DATA != null){
								$scope.Gen.Result = result.DATA;
							}
							$scope.Gen.errors = EAjax.getAjaxErrors();
						});
					}
				};

				$scope.getTables();
				
				$scope.$watch('Gen.Fields.lang', function () {

					if($scope.Gen.Fields.lang === true && $scope.Gen.Lang.length == 0 && $scope.Gen.table != ''){
						var getData = {
							ACTION:'getLangFields',
							DATA: {
								table: $scope.Gen.table
							}
						};
						EAjax.get(getData).then(function (result) {
							$scope.Gen.errors = EAjax.getAjaxErrors();
							$scope.Gen.TableFields = result.DATA;
						});
					}

				});

				$scope.$on('OnAjaxStart', function (ev, data) {
					if(data === true){
						$scope.Gen.errors = [];
					}
				});
			}
		}
	}]);
//});
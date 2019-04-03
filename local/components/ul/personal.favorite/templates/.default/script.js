BX(function () {
	BX.appUL.directive('favoriteList', ['$aService','$rootScope', function ($aService, $rootScope) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {
				var url = '/service/UL/Main/Services/Favorite';
				$scope.Error = {};
				$scope.List = {};
				$scope.Product = {};
				$scope.CurrentList = {};
				$scope.checkedAll = false;
				$scope.detailId = null;
				$scope.editListName = false;
				$scope.Shops = {};

				if(attr.detail && !BX.is.empty(attr.detail)){
					$scope.detailId = attr.detail;
				}

				$scope.addList = function (name) {
					if (name != '' && angular.isDefined(name)) {
						$aService.setAction(url + '/addList').post({NAME: name}).then(function (result) {
							if (result.data.ERRORS != null && result.data.ERRORS.length > 0) {
								$scope.Error.msg = result.data.ERRORS.join("\n");
								$scope.showErrorWin();
							} else if (result.data.DATA.ID > 0) {
								$scope.List.Items = result.data.DATA.ITEMS
							}
						});
					}
				};

				$scope.getList = function () {
					$aService.setAction(url + '/getMyList').get({LIST: $scope.detailId}).then(function (result) {
						if (result.data.DATA.length > 0) {
							$scope.List.Items = result.data.DATA;
							$scope.$watch('listId', function () {
								angular.forEach($scope.List.Items, function (item, k) {
									if (item.ID == $scope.listId) {
										$scope.setCurrentList(k);
									}
								});
							});
						} else if($scope.detailId != null){
							window.location.replace('/personal/favorite/');
						}
					});
				};

				$scope.getAllProducts = function () {
					// delete $scope.listId;
					$scope.getProducts(true);
				};

				$scope.getProducts = function (all) {
					var data = {};
					if (!all) {
						data.LIST = $scope.listId;
					} else {
						data = {};
					}

					$aService.setAction(url + '/getProducts').get(data).then(function (result) {
						$scope.Product = result.data.DATA;
						angular.forEach($scope.Product.ITEMS, function (item, k) {
							$scope.Shops[item.SKU.SHOP_ID] = item.SKU;
						});
					});
				};

				$scope.showErrorWin = function () {
					$scope.popupErr = $.magnificPopup.instance;
					$scope.popupErr.open({
						items: {
							src: '#show_error',
							type: 'inline'
						},
						mainClass: 'show_win_custom',
						enableEscapeKey: true,
						showCloseBtn: true,
						closeOnBgClick: true
					});
				};

				$scope.delete = function () {
					$scope.setCheckedProducts();
					$aService.setAction(url + '/remove').post($scope.Product.Selected).then(function (result) {
						if (result.data.STATUS == 1) {
							// $scope.getProducts();
							$scope.getList();
						}
					});
				};

				$scope.setCheckedProducts = function () {
					$scope.Product.Selected = {};
					angular.forEach($scope.Product.ITEMS, function (item, k) {
						if (item.CHECKED === true) {
							$scope.Product.Selected[item.ID] = item;
						}
					});
				};

				$scope.setCurrentList = function (index) {
					$scope.CurrentList = $scope.List.Items[index];
					$scope.getProducts();
				};

				$scope.addToList = function () {
					$scope.setCheckedProducts();
					$aService.setAction(url + '/addToList')
						.post({ITEMS: $scope.Product.Selected, LIST: $scope.CurrentList})
						.then(function (result) {
							if(result.data.STATUS == 1){
								// $scope.getProducts();
								$scope.getList();
							}
						});
				};

				$scope.addAllToBasket = function () {
					var itemsToBasket = {};
					angular.forEach($scope.Product.ITEMS, function (item, k) {
						if (item.SKU.IN_THIS_SHOP) {
							itemsToBasket[k] = item;
						}
					});
					$aService.setAction(url + '/addAllToBasket').post(itemsToBasket).then(function (result) {
						$aService.setAction('/service/UL/Sale/Basket/getBasketUser').post().then(function (res) {
							$rootScope.$broadcast('ul:basketAdd', {items: res.data.DATA});
						});
					});
				};

				$scope.showListWin = function () {
					$scope.setCheckedProducts();
					if(!is.empty($scope.Product.Selected)){
						$scope.listWin = $.magnificPopup.instance;
						$scope.listWin.open({
							items: {
								src: '#favorite_list_win_w',
								type: 'inline'
							},
							mainClass: 'show_win_custom',
							enableEscapeKey: true,
							showCloseBtn: true,
							closeOnBgClick: true
						});
					}
				};

				$scope.addProductsToCurrentList = function (listIndex) {
					$scope.CurrentList = $scope.List.Items[listIndex];
					$aService.setAction(url + '/addToList')
						.post({ITEMS: $scope.Product.Selected, LIST: $scope.CurrentList})
						.then(function (result) {
							if(result.data.STATUS == 1){
								$scope.getProducts(true);
								$scope.listWin.close();
								$scope.getList();
							}
						});
				};

				$scope.starProduct = function (index) {
					var id = $scope.Product.ITEMS[index]['ID'];
					$aService.setAction(url + '/changeOneProduct').post({item: $scope.Product.ITEMS[index]}).then(function (result) {
						if(result.data.DATA != null){
							$scope.Product.ITEMS[index]['IN_FAVORITE'] = result.data.DATA.IN_FAVORITE;
							if(result.data.DATA.ID){
								$scope.Product.ITEMS[index]['ID'] = result.data.DATA.ID;
							}
							if(result.data.DATA.IN_FAVORITE == 0)
								$('.fav-item.item_'+ index + ' .prod__star').addClass('star_null');
							else
								$('.fav-item.item_'+ index + ' .prod__star').removeClass('star_null');
						}
						// $scope.getList();
					});
				};

				$scope.checkAllProducts = function () {
					$scope.Product.Selected = {};
					$scope.checkedAll = !$scope.checkedAll;

					angular.forEach($scope.Product.ITEMS, function (item, k) {
						if (item.SKU.IN_THIS_SHOP) {
							if($scope.checkedAll){
								item.CHECKED = true;
								$scope.Product.Selected[item.ID] = item;
							} else {
								item.CHECKED = false;
								delete $scope.Product.Selected[item.ID];
							}
						}
					});

				};

				$scope.deleteList = function (id) {
					$aService.setAction(url + '/deleteList').post({LIST: id}).then(function (result) {
						if(result.data.STATUS == 1){
							$scope.getList();
						}
					});
				};

				$scope.editList = function () {
					$scope.editListName = $scope.CurrentList.NAME;
				};

				$scope.saveList = function () {
					var name = $('#edit_cur_list').val();
					$aService.setAction(url + '/editList').post({CURRENT: $scope.CurrentList, NAME: name}).then(function (result) {
						if(result.data.STATUS == 1){
							$scope.getList();
							$scope.cancelEdit();
						}
					});
				};

				$scope.cancelEdit = function () {
					$scope.editListName = false;
				};

				$scope.getList();
				$scope.getProducts(true);


			},
			controller: 'FavoriteCtrl'
		}
	}]);

	BX.appUL.controller('FavoriteCtrl', [
		'$scope', '$aService',
		function ($scope, $aService) {

		}
	])
});
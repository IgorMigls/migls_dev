BX(function () {
	BX.appUL.controller('ShopListCtrl', [
		'$scope', '$aService',
		function ($scope, $aService) {
			$scope.items = [];
			$aService.setUrlService('/service/UL/Shops/AllList');
			$scope.getShopList = function () {
				$aService.action('getList').post().then(function (result) {
					if (result.data.DATA) {
						angular.forEach(result.data.DATA, function (value, k) {
							if(value.HIDE == false){
								$scope.items.push(value);
							}
						});
					}
				});
			};

			$scope.getShopList();
		}
	]);
});
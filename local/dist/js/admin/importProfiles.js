BX(function () {
	BX.ProfileApp = angular.module('ProfileApp', ['ngResource', 'ngSanitize', 'ajax.service']);

	BX.ProfileApp.directive('importProfile', ['$aService', '$timeout', function ($aService, $timeout) {
		return {
			scope: true,
			restrict: 'AE',
			link: function ($scope, element, attr) {

				$scope.showSubItems = function ($event) {
					var link = $($event.target).closest('li').find('> a.handler_subitems');
					var UL = link.closest('li').find('> ul');

					if (UL.hasClass('active')) {
						link.find('.fa-plus').show(0);
						link.find('.fa-minus').hide(0);
						UL.fadeOut(100).removeClass('active');
					} else {
						link.find('.fa-minus').show(0);
						link.find('.fa-plus').hide(0);
						UL.fadeIn(300).addClass('active');
					}
				};

			},
			controller: function ($scope, $aService) {
				var url = '/rest/UL/Main/Admins/Import';
				$scope.MainSelected = {};

				$scope.Folder = {
					Items: {},
					Main: {}
				};
				$scope.Properties = {
					Import: [],
					Site: []
				};
				$scope.IblockId = {};
				$scope.obMenuBlocks = [];
				$scope.CurrentProp = {};
				$scope.SectionImport = {};
				$scope.processTable = [];
				$scope.showFoldersBlock = true;

				$scope.getProcessImport = function () {
					$aService.setAction(url + '/AjaxHandler/getProcessImport').get().then(function (result) {
						if(result.data.DATA != null && result.data.DATA != false){
							$scope.showFoldersBlock = false;
							$scope.processTable.push(result.data.DATA);
						}
					});
				};

				$scope.getFolder = function (folder) {
					if (folder.PATH != 0) {
						$scope.obMenuBlocks = [];

						$aService.setAction(url + '/AjaxHandler/getFolders').post({PATH: folder.PATH}).then(function (result) {
							$scope.Folder.Items = result.data.DATA.DIR;
							$scope.Properties.Import = result.data.DATA.PROPERTIES;
							$scope.IblockId = result.data.DATA.IBLOCK;

							if (result.data.DATA.PROP_SITE.length > 0) {
								angular.forEach(result.data.DATA.PROP_SITE, function (value, k) {
									$scope.obMenuBlocks.push({
										TEXT: value.NAME,
										SHOW_TITLE: false,
										ONCLICK: function () {
											$scope.$apply(function () {
												$scope.setProperty($scope.CurrentProp, value);
											});
										}
									});
								});
							}
						});
					}
				};

				$scope.showProperty = function (prop, $event) {
					$scope.CurrentProp = prop;
					if ($scope.obMenuBlocks.length > 0) {
						BX.adminShowMenu($event.target, $scope.obMenuBlocks);
					}
				};

				$scope.setProperty = function (propFolder, propBx) {
					var post = {
						SITE_PROP: propBx,
						IMPORT_PROP: propFolder,
						IBLOCK: $scope.IblockId
					};

					$aService.setAction(url + '/AjaxHandler/saveCompareProp').post(post).then(function (result) {
						if (result.data.DATA.SUCCESS === true) {
							angular.forEach($scope.Properties.Import, function (value, k) {
								if (value.NAME == result.data.DATA.SAVE.PROPERTY_IMPORT) {
									$scope.Properties.Import[k]['SAVED'] = result.data.DATA.SAVE.PROPERTY_NAME;
								}
							});
						}
					});
				};

				$scope.getMainFolders = function () {
					$aService.setAction(url + '/AjaxHandler/getMainFolders').get().then(function (result) {
						$scope.Folder.Main = result.data.DATA;
						$scope.MainSelected = $scope.Folder.Main[0];
					});
				};

				$scope.setCatalog = function (folder) {
					$aService.setAction(url + '/AjaxHandler/getIblockCatalog').post({NAME: folder}).then(function (result) {
						$scope.SectionImport.IBLOCK = result.data.DATA;
					});
				};

				$scope.importProduct = function (folder) {
					// $aService.setAction(url + '/AjaxHandler/setImportParams').post(folder).then(function (result) {
					// 	$scope.SectionImport.IBLOCK = result.data.DATA;
					// });

					$aService.setAction(url + '/AjaxHandler/addProductImport').post(folder).then(function (result) {
						if(result.data.DATA === 'OK'){
							$scope.getProcessImport();
						}
					});

					// console.info(folder);
				};

				$scope.importSection = function (iBlock, MainSelected) {
					// console.info(MainSelected);
					// console.info(iBlock);
					var post = {
						FOLDER: MainSelected,
						IBLOCK: iBlock
					};
					$aService.setAction(url + '/AjaxHandler/importSection').post(post).then(function (result) {
						// todo Сделать сообщения об ошибках или об успешном импорте
						if (result.data.DATA === true) {

						}
					});
				};

				$scope.importPrices = function (Folder) {
					console.info(Folder);
				};

				$scope.getMainFolders();
				$scope.getProcessImport();
			}
		}
	}]);

});
var SetFileUrl = function (data) {
	var fileName = '/upload/' + data;


	var mainFolder = $('#select_main_folder').val();
	var shop = $('#select_shop').val();

	if(mainFolder == '' || mainFolder == 0){
		alert('Выберите раздел для импорта');
		return;
	}
	if(shop == '' || shop == 0){
		alert('Выберите магазин');
		return;
	}

	var post = {file: fileName, folder: mainFolder, shopId: shop};

	BX.showWait(null, 'Чтение файла во временную таблицу');

	$('#open_dialog_button').hide(0);
	$.ajax({
		url: '/rest/UL/Main/Admins/Import/AjaxHandler/uploadMainTmp',
		data: JSON.stringify(post),
		dataType: 'json',
		contentType: 'application/json',
		success: function (result) {
			console.info(result);
			if(result.DATA == true){

			}
			BX.closeWait();
		},
		method: 'post',
		headers:{
			"Accept":'application/json, text/plain, */*'
		}
	});

};
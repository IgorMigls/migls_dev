<?
/** @global CMain $APPLICATION */
/** @global CUser $USER */
$Asset = \Bitrix\Main\Page\Asset::getInstance();

$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS('/bitrix/css/main/font-awesome.min.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/admin/bootstrap_admin_fix.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/admin/profile_import.css');

$Asset->addJs('http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
$Asset->addJs('/local/dist/libs/js/is.min.js');

$Asset->addJs('/local/dist/libs/js/angular/angular.min.js');
$Asset->addJs('/local/dist/libs/js/angular/angular-resource.min.js');
$Asset->addJs('/local/dist/libs/js/angular/angular-sanitize.min.js');
//$Asset->addJs('/local/dist/libs/js/angular/angular-file-upload.min.js');
$Asset->addJs('/local/dist/libs/js/angular/aService.js');
$Asset->addJs('/local/dist/js/admin/importProfiles.js');

CUtil::InitJSCore(['ajax']);

use UL\Main\Import;
?>
<div ng-app="ProfileApp">
	<import-profile>
		<div class="container" ng-if="showFoldersBlock">
			<div class="row">
				<select ng-options="item as item.NAME for item in Folder.Main track by item.PATH"
					ng-model="MainSelected" ng-change="setCatalog(MainSelected.NAME)"></select>
			</div>
			<div class="row item_rows" ng-if="SectionImport.IBLOCK">
				<input type="button" name="save" value="Загрузить" title="Загрузить"
					class="adm-btn-save" ng-click="importProduct(MainSelected)" />
			</div>
		</div>
		<div ng-if="processTable.length > 0">
			<table class="adm-list-table">
				<thead>
				<tr class="adm-list-table-header">
					<td class="adm-list-table-cell adm-list-table-cell-sort">
						<div class="adm-list-table-cell-inner">ID</div>
					</td>
					<td class="adm-list-table-cell adm-list-table-cell-sort">
						<div class="adm-list-table-cell-inner">Каталог</div>
					</td>
					<td class="adm-list-table-cell adm-list-table-cell-sort">
						<div class="adm-list-table-cell-inner">Начало импорта</div>
					</td>
					<td class="adm-list-table-cell adm-list-table-cell-sort">
						<div class="adm-list-table-cell-inner">Статус</div>
					</td>
				</tr>
				</thead>
				<tbody>
				<tr class="adm-list-table-cell adm-list-table-popup-block" ng-repeat="el in processTable">
					<td class="adm-list-table-cell">{{el.ID}}</td>
					<td class="adm-list-table-cell">{{el.FILE}}</td>
					<td class="adm-list-table-cell">{{el.LAST_IMPORT}}</td>
					<td class="adm-list-table-cell">{{el.IN_PROCESS === 'Y' ? 'В процессе' : 'Завершен'}}</td>
				</tr>
				</tbody>
			</table>
		</div>

		<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>
	</import-profile>
</div>

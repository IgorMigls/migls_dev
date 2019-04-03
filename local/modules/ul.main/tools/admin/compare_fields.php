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
		<div class="container">
			<div class="row">
				<select ng-options="item as item.NAME for item in Folder.Main track by item.PATH"
					ng-model="MainSelected" ng-change="getFolder(MainSelected)"></select>
			</div>
			<div class="row item_rows">
				<table class="table table-striped table-hover table-condensed">
					<thead>
					<tr class="warning">
						<th>Свойство из файла</th>
						<th>Свойство на сайте</th>
					</tr>
					</thead>
					<tbody>
					<tr ng-repeat="prop in Properties.Import">
						<td>{{prop.NAME}}</td>
						<td>
							<a href="javascript:" ng-click="showProperty(prop, $event)">
								<span ng-if="!prop.SAVED">Выбрать</span>
								<span ng-if="prop.SAVED">{{prop.SAVED}}</span>
							</a>
						</td>
					</tr>
					</tbody>
					<tfoot>

					</tfoot>
				</table>
			</div>
<!--    		<pre>{{IblockId | json}}</pre>-->
<!--			<pre>{{Properties.Import | json}}</pre>-->
		</div>

		<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>

	</import-profile>
</div>

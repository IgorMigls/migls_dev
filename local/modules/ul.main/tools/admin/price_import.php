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
use Bitrix\Iblock;

includeModules(['ab.iblock', 'iblock']);

$arShops = [];
$obElements = AB\Iblock\Element::getList([
	'filter' => ['IBLOCK_ID' => 5],
	'select' => ['ID', 'NAME', 'SECTION_NAME' => 'IBLOCK_SECTION.NAME'],
]);
while ($el = $obElements->fetch()) {
	$arShops[$el['ID']] = $el['NAME'].' '.$el['SECTION_NAME'];
}

?>
<div ng-app="ProfileApp">
	<import-profile>
		<div class="container">
			<div class="row">
				<select ng-options="item as item.NAME for item in Folder.Main track by item.PATH"
					ng-model="MainSelected" id="select_main_folder"></select>
				<select name="select_shop" id="select_shop">
					<option value="0"> - Выберите магазин -</option>
					<? foreach ($arShops as $id => $shop): ?>
						<option value="<?=$id?>"><?=$shop?></option>
					<? endforeach; ?>
				</select>
			</div>

			<? CAdminFileDialog::ShowScript(Array
				(
					"event" => "OpenImage",
					"arResultDest" => Array("FUNCTION_NAME" => "SetFileUrl"),
					"arPath" => Array('PATH' => '/upload/'),
					"select" => 'F',
					"operation" => 'O',
					"showUploadTab" => true,
					"showAddToMenuTab" => false,
					"fileFilter" => 'csv',
					"allowAllFiles" => false,
					"saveConfig" => true,
				)
			); ?>
			<div class="row item_rows">
				<a href="javascript:" onclick="OpenImage()" class="adm-btn" id="open_dialog_button">
					Выбрать файл
				</a>
			</div>

			<div class="row item_rows" ng-if="SectionImport.IBLOCK">
				<input type="button" name="save" value="Загрузить" title="Загрузить"
					class="adm-btn-save" ng-click="importPrices(MainSelected)" />
			</div>
		</div>

		<div a-service-loader="{overlay: {background: '#fff', opacity: '0.7'}, loader:{class:'fa fa-circle-o-notch fa-spin fa-2x fa-fw'}}"></div>

	</import-profile>
</div>

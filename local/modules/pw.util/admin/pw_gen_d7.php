<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
global $USER, $APPLICATION, $USER_FIELD_MANAGER;
IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;

$moduleId = 'pw.util';
Bitrix\Main\Loader::IncludeModule($moduleId);

$modulePermissions = $APPLICATION->GetGroupRight($moduleId);
if ($modulePermissions == "D"){
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
if (!\Bitrix\Main\Loader::IncludeModule($moduleId)) {
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$Asset = \Bitrix\Main\Page\Asset::getInstance();
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();


$srcPath = '/bitrix/themes/.default/'.$moduleId;
CUtil::InitJSCore(array('core', 'jquery'));

$APPLICATION->SetAdditionalCSS($srcPath.'/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS($srcPath.'/css/font-awesome.min.css');
$APPLICATION->SetAdditionalCSS($srcPath.'/css/'.$moduleId.'.css');

$Asset->addJs($srcPath.'/js/lib/angular.min.js');
$Asset->addJs($srcPath.'/js/lib/angular-resource.min.js');
$Asset->addJs($srcPath.'/js/lib/angular-animate.min.js');
$Asset->addJs($srcPath.'/js/lib/ui-bootstrap-tpls-0.14.3.min.js');
$Asset->addJs($srcPath.'/js/lib/ajax_service.js');
$Asset->addJs($srcPath.'/js/'.$moduleId.'.js');

$errors = array();

$aTabs = array(array(
		"DIV" => "edit1",
		"TAB" => Loc::getMessage('PW_GEN_D7_ADMIN_TAB'),
		"ICON"=>"ad_contract_edit",
		"TITLE"=> Loc::getMessage('PW_GEN_D7_ADMIN_TAB')
));

$tabControl = new CAdminForm("PwGenerator", $aTabs);

//view

if ($_REQUEST["mode"] == "list"){
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
}else{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
}

if (!empty($errors)){
	\CAdminMessage::ShowMessage(join("\n", $errors));
}

$tabControl->BeginPrologContent();

echo $USER_FIELD_MANAGER->ShowScript();

echo \CAdminCalendar::ShowScript();

$tabControl->EndPrologContent();
$tabControl->BeginEpilogContent();
?>
<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">

<?$tabControl->EndEpilogContent();?>

<? $tabControl->Begin(array(
	"FORM_ACTION" => '',
	'FORM_ATTRIBUTES'=>'novalidate="novalidate"'
));?>
<? $tabControl->BeginNextFormTab();
$tabControl->BeginCustomField('GEN', false)?>
	<tr>
		<td colspan="2">
			<div class="row">
				<div class="container">
					<uib-alert ng-repeat="alert in Gen.errors" type="{{alert.type}}">{{alert.msg}}</uib-alert>
					<!--<pre>{{Gen | json}}</pre>-->
					<div class="form-horizontal" name="GenForm" id="GenForm">
						<div class="form-group table_search">
							<label for="inputNamespace" class="col-sm-3 control-label">Таблица</label>
							<div class="form-inline col-sm-9">
								<div class="form-group">
									<input type="text"
									       ng-model="Gen.table"
									       uib-typeahead="table for table in Tables.items | filter:$viewValue | limitTo:20"
									       class="form-control" name="table" required/>
									<!--<button type="button" class="btn btn-primary"
											ng-disabled="!Tables.selected" ng-click="chooseTable()">
										Выбрать таблицу
									</button>-->
								</div>
							</div>
						</div>
						<div ng-if="Gen.table"><!--ng-if="Gen.table"-->
							<hr />
							<div class="form-group" ng-class="{'has-error':PwGenerator_form.module.$invalid}">
								<label for="inputmodule" class="col-sm-3 control-label">ИД модуля</label>
								<div class="col-sm-9">
									<input type="text" ng-model="Gen.Fields.module" class="form-control"
									       id="inputmodule" placeholder="Пример: pw.util" required name="module" />
								</div>
							</div>
							<div class="form-group" ng-class="{'has-error':PwGenerator_form.namespace.$invalid}">
								<label for="inputNamespace" class="col-sm-3 control-label">Namespace</label>
								<div class="col-sm-9">
									<input type="text" ng-model="Gen.Fields.namespace" class="form-control"
									       id="inputNamespace" placeholder="Пример: Esd\Tools\Util" required name="namespace" />
								</div>
							</div>
							<div class="form-group" ng-class="{'has-error':PwGenerator_form.class.$invalid}">
								<label for="inputClass" class="col-sm-3 control-label">Класс</label>
								<div class="col-sm-9">
									<input type="text" ng-model="Gen.Fields.class" class="form-control"
									       id="inputClass" placeholder="Пример: Generator" required name="class" />
								</div>
							</div>
							<div class="form-group" ng-class="{'has-error':PwGenerator_form.lib.$invalid}">
								<label for="inputPath" class="col-sm-3 control-label">
									Местоположение файла<br />относительно папки /lib
								</label>
								<div class="col-sm-9">
									<input type="text" ng-model="Gen.Fields.lib" class="form-control"
									       id="inputPath" placeholder="Пример: /Tools" required name="lib" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
									<label class="control-label">
										<input type="checkbox" ng-model="Gen.Fields.use_validators" class="form-control" name="validate" />
										Создать валидаторы
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
									<label class="control-label">
										<input type="checkbox" ng-model="Gen.Fields.lang" class="form-control" name="lang" />
										Заполнить лэнг-файлы модели
									</label>
								</div>
							</div>
							<div class="form-group" uib-collapse="!Gen.Fields.lang">
								<div class="form-group">
									<label for="lang_prefix" class="col-sm-3 control-label">
										Префикс для ключей лэнг-массива $MESS
									</label>
									<div class="col-sm-9">
										<input type="text" ng-model="Gen.Fields.lang_prefix" class="form-control"
										       id="lang_prefix" placeholder="Пример: PW_LANDING" name="lang_prefix" />
									</div>
								</div>
								<div class="col-sm-9 col-sm-offset-3">
									<table class="table table-hover">
										<thead>
										<tr>
											<th>Поле</th>
											<th>Название</th>
										</tr>
										</thead>
										<tbody>
										<tr ng-repeat="row in Gen.TableFields">
											<td>{{row.CODE}}</td>
											<td><input type="text" ng-model="row.TITLE" /></td>
										</tr>
										<tr ng-if="Gen.TableFields.length == 0 || !Gen.TableFields">
											<td colspan="2" > - Нет данных - </td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>

							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
									<button class="btn btn-success" type="button" ng-click="generateModel()">
										Создать модель
									</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-9 col-sm-offset-3">
									<uib-alert ng-if="Gen.Result" type="success">
										Файлы созданы и находтся:
										<ul>
											<li ng-repeat="file in Gen.Result">{{file}}</li>
										</ul>
									</uib-alert>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
<?$tabControl->EndCustomField('GEN');?>
	<div ng-app="MapGenerator" class="map_generator">
		<generator>
			<?$tabControl->Show();?>
		</generator>
	</div>
<?
if ($_REQUEST["mode"] == "list")
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
else
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
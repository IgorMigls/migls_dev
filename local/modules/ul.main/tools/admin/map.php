<?
/** @global CMain $APPLICATION */
/** @global CUser $USER */
$Asset = \Bitrix\Main\Page\Asset::getInstance();
$Asset->addJs('http://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
$Asset->addJs('http://api-maps.yandex.ru/2.1/?load=package.full&lang=ru-RU');
//$Asset->addJs('/local/dist/libs/js/map/ya.map.min.js');
//$Asset->addJs('/local/dist/libs/js/map/jquery.simple-color.min.js');
$Asset->addJs('/local/dist/libs/js/color-picker/js/jquery.colorPicker.min.js');
$Asset->addJs('/local/dist/libs/js/is.min.js');
$Asset->addJs('/local/dist/libs/js/map/adminMap.js');
//$Asset->addCss('/local/dist/libs/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/bootstrap.min.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/js/color-picker/css/colorPicker.css');
$APPLICATION->SetAdditionalCSS('/local/dist/libs/css/admin/map.css');
CUtil::InitJSCore(['ajax']);
?>
<div id="ris_map"></div>
<br />
<div id="formpolygon" class="container">
	<strong style="text-align: center; display: block">Форма ввода параметров многоугольника</strong><br/>
	<? $left = 4;
	$right = 8; ?>
	<div class="row">
		<div class="form-horizontal">
			<div class="form-group">
				<label for="color_polygon" class="col-sm-<?= $left ?> control-label">Цвет заливки</label>
				<div class="col-sm-<?= $right ?>">
					<input type="text" id="color_polygon" class='simple_color' value="#0000ff"/><br/>
				</div>
			</div>

			<div class="form-group">
				<label for="fillopacity_polygon" class="col-sm-<?= $left ?> control-label">Уровень прозрачности
					заливки</label>
				<div class="col-sm-<?= $right ?>">
					<input type="text" id="fillopacity_polygon" value="0.5"/><br/>
				</div>
			</div>

			<div class="form-group">
				<label for="width_line" class="col-sm-<?= $left ?> control-label">Толщина линии обводки</label>
				<div class="col-sm-<?= $right ?>">
					<input type="text" name="width_line" id="width_line" size="2" value="2"/><br/>
				</div>
			</div>
			<div class="form-group">
				<label for="color_line" class="col-sm-<?= $left ?> control-label">Цвет линии обводки</label>
				<div class="col-sm-<?= $right ?>">
					<input type="text" id="color_line" class='simple_color' value="#cc3333"/><br/>
				</div>
			</div>

			<div class="form-group">
				<label for="opacity_line" class="col-sm-<?= $left ?> control-label">Уровень прозрачности обводки</label>
				<div class="col-sm-<?= $right ?>">
					<input type="text" id="opacity_line" value="0.7"/><br/>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-offset-<?=$left?>" style="padding-bottom: 30px">
			<input type="button" value="Добавить" id="addPolygon"/>
			<input type="button" value="Удалить" id="dellPolygon"/>
		</div>
		<div class="col-sm-offset-<?=$left?>">
			<input type="button" value="Завершить редактирование" class="adm-btn-save" id="stopEditPolygon"/>
		</div>
	</div>
	<div class="row">
		<div id="geometry"></div>
		<textarea style="display: none" id="CORDS" name="FIELDS[CORDS]"></textarea>
	</div>
</div>
<script>
	BX(function () {
		var YMap = new BX.AdminYMap(<?=CUtil::PhpToJSObject(['ID'=>intval($_GET['ID'])])?>);
		ymaps.ready(YMap.init);

		$('.simple_color').colorPicker();
	});
</script>
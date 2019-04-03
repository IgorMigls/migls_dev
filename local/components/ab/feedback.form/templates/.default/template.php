<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Iblock\PropertyTable;

//PR($arResult['SS']);
?>
<div class="form_wrap">
	<h2><?= $arParams['FORM_NAME_BLOCK'] ?></h2>
	<form method="post" id="<?= $arParams['FORM_ID'] ?>" class="form_items">
		<? foreach ($arResult['FORM_FIELDS'] as $code => $field): ?>
			<div class="form-group">
				<? switch ($field['PROPERTY_TYPE']) {
					case PropertyTable::TYPE_FILE:
						?>

						<? break;
					case 'TEXT':
						?>
						<label for="FIELD_<?= $code ?>">
							<?= $field['NAME'] ?>
							<?= $field['REQUIRED'] ? '<span class="red_star">*</span>' : false ?>
						</label>
						<textarea name="<?= $code ?>"
						          id="FIELD_<?= $code ?>"
						          class="form-control"
							<?= $field['REQUIRED'] ? 'required' : false ?>></textarea>
						<? break;
					default: ?>
						<label for="FIELD_<?= $code ?>">
							<?= $field['NAME'] ?>
							<?= $field['REQUIRED'] ? '<span class="red_star">*</span>' : false ?>
						</label>
						<input name="<?= $code ?>"
							<?= $field['REQUIRED'] ? 'required' : false ?>
							   type="text"
							   class="form-control"
							   id="FIELD_<?= $code ?>"/>
						<? break;
				} ?>
			</div>
		<? endforeach; ?>
		<div class="form-group">
			<button type="button" id="<?= $arParams['FORM_ID'] ?>_save" class="btn btn-success" onclick="BX.FormIblock.send()">
				<?= $arParams['BTN_SAVE'] ?>
			</button>
		</div>
		<input type="hidden" name="ss" value="<?= $arResult['SS'] ?>"/>
		<?= bitrix_sessid_post() ?>
	</form>
</div>
<?$params = [
	'formId' => $arParams['FORM_ID'],
];?>
<script type="text/javascript">
	BX(function () {
		BX.FormIblock.init(<?=CUtil::PhpToJSObject($params)?>);
	});
</script>
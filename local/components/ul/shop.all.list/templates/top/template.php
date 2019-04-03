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
$CFile = new CFile();
?>
<? foreach ($arResult['ITEMS'] as $k => $arElement) {
	if ($arElement['HIDE'] != 1) {
		if (intval($arElement['DETAIL_PICTURE']) > 0) {
			$arElement['IMG'] = $CFile->ResizeImageGet(
				$arElement['DETAIL_PICTURE'],
				['width' => 120, 'height' => 80],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
				true
			);
		} else {
			$arElement['IMG']['src'] = '/local/dist/images/demo/main-sections_karusel.jpg';
		}
		?>
		<div class="b-header-popup__shop b-ib">
			<a href="<?= $arElement['DETAIL_PAGE_URL'] ?>">
				<img src="<?= $arElement['IMG']['src'] ?>" alt="<?= $arElement['NAME'] ?>">
			</a>
		</div>
	<?
	}
}
?>

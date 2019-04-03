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
?>
<div id="<?=$arParams['SECTION_OPEN']?>" class="b-main-sections__section b-ib">
	<div class="b-main-sections__title"><?=$arParams['SECTION_NAME'] ? $arParams['SECTION_NAME'] : 'Магазины'?></div>
	<div class="b-main-sections__items b-ib-wrapper">
		<?foreach ($arResult['ITEMS'] as $k => $arItem):?>
			<?if($arItem['HIDE'] === true):?>
				<div class="b-main-sections__item b-main-sections__item_disabled b-ib">
					<img src="<?=$arItem['IMG']['src']?>" alt="">
					<?if(strlen($arItem['PROPERTY_NO_AVAILABLE_TXT_VALUE']['TEXT']) > 0):?>
						<div class="b-main-sections__disabled"><?=$arItem['PROPERTY_NO_AVAILABLE_TXT_VALUE']['TEXT']?></div>
					<?else:?>
						<div class="b-main-sections__disabled">
							<?if($arParams['OPENING_SOON'] == 'Y'){?>
								Скоро открытие
							<?} else {?>
								недоступно <br>в выбранной зоне
							<?}?>

						</div>
					<?endif;?>
				</div>
			<?else:?>
				<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-main-sections__item b-ib">
					<img src="<?=$arItem['IMG']['src']?>" alt="">
				</a>
			<?endif;?>
		<?endforeach;?>
	</div>
		<div class="b-main-sections__more">
			<?if(count($arResult['ITEMS']) > 3):?>
				<button data-toggle-element="#<?=$arParams['SECTION_OPEN']?>" data-toggle-class="open" class="b-button js-toggle-class"></button>
			<?else:?>
				<div style="height: 30px"></div>
			<?endif;?>
		</div>
</div>

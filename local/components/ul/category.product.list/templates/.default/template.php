<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
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
<div class="b-content__bread">
	<ul class="b-bread">
		<li class="bread__list"><a href="/" class="bread__item">Главная</a></li>
		<? foreach ($arResult['URL_CHAIN'] as $k => $item) { ?>
			<li class="bread__list">
				<? if ($k + 1 < count($arResult['URL_CHAIN'])){ ?>
					<a href="<?=$item['URL']?>" class="bread__item"><?=$item['NAME']?></a>
				<? } else {
					echo $item['NAME'];
				} ?>
			</li>
		<? } ?>
	</ul>
</div>

<div id="popular_products_app">
	<? foreach ($arResult['SECTIONS_PRODUCT'] as $arSection):?>
		<div class="b-catalog__content b-ib b-catalog__all">
			<div class="b-content__block b-ib-wrapper">
				<div class="b-products-block-top b-ib-wrapper bg_reverse"
						style="background:url(<?=$arSection['LINE_IMG']['SRC']?>) no-repeat right, linear-gradient(to left, #fac10e 0%, #fbc902 30%, #fcd000 65%, #ffe800 100%)">
					<div class="all_products_line">
						<div class="b-products-block-top__left b-ib">
							<div class="b-products-block-top__title"><?=$arSection['NAME']?></div>
						</div>
						<div class="b-products-block-top__right b-ib">
							<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="b-button b-button_show">Смотреть все</a>
						</div>
					</div>
				</div>
			</div>
			<!--		--><?//dump($arSection['PRODUCTS'])?>
			<div class="b-all__item">
				<? foreach ($arSection['PRODUCTS'] as $arElement):
					$q = (float)$arElement['BASKET']['QUANTITY'];
					$arElement['PRODUCT_PICTURE'] = $arElement['IMAGE'];
					$arElement['ID'] = $arElement['SKU_ID'];
					unset($arElement['IMAGE'], $arElement['SKU_ID']);
				?>

					<product-item :product="<?=CUtil::PhpToJSObject($arElement)?>"></product-item>

				<? endforeach; ?>
			</div>
		</div>
	<? endforeach; ?>
</div>


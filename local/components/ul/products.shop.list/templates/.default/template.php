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


<? foreach ($arResult['SHOP_DATA'] as $shopId => $data):
//	if($shopId == 227676)
//		continue;
	?>
	<div class="b-catalog__content b-ib b-catalog__all popular_products_app">

		<div class="b-content__block b-ib-wrapper">
			<div class="b-products-block-top b-ib-wrapper bg_reverse"
					style="background:url(<?=$arSection['LINE_IMG']['SRC']?>) no-repeat right, linear-gradient(to left, #fac10e 0%, #fbc902 30%, #fcd000 65%, #ffe800 100%)">
				<div class="all_products_line">
					<div class="b-products-block-top__left b-ib">
						<div class="b-products-block-top__title"><?=$data['NAME']?></div>
					</div>
					<div class="b-products-block-top__right b-ib">
						<a href="<?=$data['SHOP_URL']?>" class="b-button b-button_show">Смотреть все</a>
					</div>
				</div>
			</div>
		</div>
		<div class="b-all__item">
			<? foreach ($data['ITEMS'] as $id => $arElement) {
				$q = (float)$arElement['SKU']['BASKET']['QUANTITY'];
				$arElement['PRODUCT_PICTURE'] = $arElement['IMAGE'];
				$arElement['PRICE_FORMAT'] = SaleFormatCurrency($arElement['SKU']['PRICE_VAL'], 'RUB', true);
				$arElement['ID'] = $arElement['SKU']['ID'];
				$arElement['PRODUCT_NAME'] = $arElement['NAME'];
				?>

				<product-item :product="<?=CUtil::PhpToJSObject($arElement)?>" key="<?=$shopId?>_<?=$id?>"></product-item>
			<? } ?>
		</div>
	</div>
<? endforeach; ?>

<? // PR($arResult['SHOP_DATA']); ?>

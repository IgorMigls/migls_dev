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
if (count($arResult['SHOP']) > 0){
	?>
	<div class="b-main-products">
		<div class="b-ib-wrapper b-id-wrapper_shadow">
			<div class="b-products-block-top b-ib-wrapper">
				<div class="b-products-block-top__left b-ib">
					<div class="b-products-block-top__title">
						<span class="b-icon_hot"></span>
						Популярные товары
					</div>
				</div>
				<div class="b-products-block-top__right b-ib">
<!--					<a href="" class="b-button b-button_show">Смотреть все</a>-->
				</div>
			</div>


			<div class="b-products-slider slider_popular">
				<?
				$i = 0;
				foreach ($arResult['SHOP'] as $k => $arShop):
					if($i > 0)
						break;
					?>
					<div class="b-products-block-tabs-content__item b-ib-wrapper" id="popular_products_app">
						<div class="b-products-slider__slider js-products-slider-detail_shop">
							<? foreach ($arShop['ITEMS'] as $l => $arProduct) { ?>
								<div class="b-products-slider__item js-products-slider-item">
									<product-item :product="<?=CUtil::PhpToJSObject($arProduct)?>"></product-item>
								</div>
								<?
							} ?>
						</div>
					</div>
					<?
					$i++;
				endforeach; ?>
			</div>
		</div>
	</div>
<? } ?>
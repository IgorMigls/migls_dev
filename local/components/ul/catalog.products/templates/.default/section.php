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
/** @var CBitrixComponent $component */ ?>
<?// PR($arParams); ?>
<div class="b-catalog b-ib-wrapper">
	<div class="b-catalog__left b-ib-wrapper">
		<? $APPLICATION->IncludeComponent('ul:catalog.categories', '', array(
			'CITY_ID' => $arResult['SHOPS']['CITY_ID'],
			'SHOP_ID' =>$arResult['SHOPS']['SHOP_ID'],
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
			'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
			'URL_TEMPLATE' => $arParams['URLS'],
			'SECTION' => $arResult['SECTION'],
			'SECTIONS' => $arResult['SECTIONS']
		), $component) ?>
	</div>

	<!-- контент -->
	<div class="b-catalog__right b-ib-wrapper">

		<div class="b-content__bread">
			<ul class="b-bread">
				<li class="bread__list"><a href="/" class="bread__item">Главная</a></li>
				<li class="bread__list">
					<a href="/catalog/<?=$arResult['IBLOCK_INFO']['ID']?>/"><?=$arResult['IBLOCK_INFO']['NAME']?></a>
				</li>

				<? foreach ($arResult['NAV_CHAIN'] as $k => $item) {?>
					<li class="bread__list">
						<a href="/catalog/<?=$item['IBLOCK_ID']?>/<?=$item['ID']?>/">
							<?=$item['NAME']?>
						</a>
					</li>
				<?}?>
			</ul>
		</div>

		<?$APPLICATION->IncludeComponent('ul:products.shop.list','', array(
			'CITY_ID' => $arResult['SHOPS']['CITY_ID'],
			'SHOP_ID' =>$arResult['SHOPS']['SHOP_ID'],
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
			'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
			'URL_TEMPLATE' => $arParams['URLS'],
			'SHOP_FOLDER' => $arParams['SHOP_FOLDER'],
			'SECTION' => $arResult['SECTION']
		), $component)?>


	</div>
</div>
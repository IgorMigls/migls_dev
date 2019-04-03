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
$deliverCurrentTime = $arParams['SHOP_INFO']['DELIVERY_TIME'];
$deliverCurrent = $arParams['SHOP_INFO']['CURRENT'];
$this->setFrameMode(true);
$this->addExternalCss($componentPath.'/templates/.default/style.css');
$this->addExternalJs($componentPath.'/templates/.default/script.js');
$nearDelivery = false;
/*foreach ($deliverCurrentTime[0]['ITEMS'] as $item) {
	if ($item['DISABLED'] === false){
		$nearDelivery = 'Сегодня '.$item['PROPERTY_TIME_FROM_VALUE'].' &mdash; '.$item['PROPERTY_TIME_TO_VALUE'];
		break;
	}
}
if ($nearDelivery === false){
	$item = $deliverCurrentTime[1]['ITEMS'][0];
	$nearDelivery = 'Завтра '.$item['PROPERTY_TIME_FROM_VALUE'].' &mdash; '.$item['PROPERTY_TIME_TO_VALUE'];
}*/
//dump($deliverCurrent);
?>
	<div class="b-sidebar__delivery">
		<div class="b-delivery__content">
			<div class="b-img-wrapper">
				<img src="<?=$arParams['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arParams['SHOP_INFO']['NAME']?>" />
			</div>
		</div>
		<div class="b-delivery__close">
			<? if ($deliverCurrent): ?>
				Ближайшая доставка:
				<span>
					<?=$deliverCurrent['NAME']?>,
					<?=$deliverCurrent['DATE_FORMAT']['DAY']?> <?=$deliverCurrent['DATE_FORMAT']['MONTH_LOCALE']?>
					<p style="margin-top: 7px"><?=$deliverCurrent['CURRENT']['TIME_FROM']?> &ndash; <?=$deliverCurrent['CURRENT']['TIME_TO']?></p>
				</span>
			<? endif; ?>
		</div>
		<div class="b-delivery__interval">
			<a href="javascript:" id="show_all_interval" class="b-button b-button_delivery">Все интервалы</a>
			<!--		<a href="javascript:" class="b-button b-button_delivery">Дополнительно</a>-->
		</div>
	</div>
<?
//dump($arParams);
$arResult['SECTIONS'] = $arParams['SECTIONS'];

//dump($arResult);
?>
	<div class="b-sidebar__catalog-wrapper">
		<div class="b-sidebar__catalog b-sidebar__catalog_white">
			<div class="b-catalog__favorite">
				<ul class="catalog__acordion catalog__acordion_new-icons">
					<?if($USER->IsAuthorized()):?>
						<li class="catalog__list">
							<a href="/lk/favorite" class="catalog__title result__item favorite_item">
								<span></span>Избранное
							</a>
						</li>
						<br /><br />
					<?endif;?>
					<? foreach ($arResult['SECTIONS'] as $iblockId => $arType): ?>
						<? if (count($arType['ITEMS']) > 0){ ?>
							<li class="catalog__list <?=($arResult['COMPONENT_PARENT']['IBLOCK_ID'] == $iblockId ? 'open_item_menu' : false)?>">

								<a href="#" class="catalog__title catalog__title_grey result__item">
									<? if (!empty($arType['ICON']['SRC'])): ?>
										<i style="background: url(<?=$arType['ICON']['SRC']?>) no-repeat center center"></i>
									<? endif; ?>
									<span><?=$arType['CATEGORY_NAME']?></span>
								</a>
								<ul class="catalog__sub">
									<? foreach ($arType['ITEMS'] as $arItem):
										$url = '/shop/'.$arParams['SHOP_INFO']['ID'].'/';
										$url .= $arItem['IBLOCK_ID'].'/'.$arItem['ID'].'/';
										if (count($arItem['SUBSECTION']) > 0){
											$url = 'javascript:';
										}
										?>
										<li class="catalog__item__sub">
											<a href="<?=$url?>"
													class="catalog__item <?=($arParams['SECTION']['ID'] == $arItem['SECTION_ID'] ? 'active_category' : false)?>">
												<?=$arItem['NAME']?>
											</a>
											<? if (count($arItem['SUBSECTION']) > 0):?>
												<ul class="subcategory">
													<? foreach ($arItem['SUBSECTION'] as $sub) {
														$url = '/shop/'.$arParams['SHOP_INFO']['ID'].'/';
														$url .= $sub['IBLOCK_ID'].'/'.$sub['ID'].'/';
														?>
														<li>
															<a class="catalog__item" href="<?=$url?>"><?=$sub['NAME']?></a>
														</li>
													<? } ?>
												</ul>
											<? endif; ?>
										</li>
									<? endforeach; ?>
								</ul>
							</li>
						<? } ?>

					<? endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

<? include($_SERVER['DOCUMENT_ROOT'].$componentPath.'/include/timeIntervalPopup.php'); ?>
<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$deliverCurrentTime = $arResult['SHOP_INFO']['DELIVERY_TIME'];
$this->addExternalJs($componentPath.'/templates/.default/script.js');
$this->addExternalCss($componentPath.'/templates/.default/style.css');
?>
<div class="b-sidebar__delivery">
	<div class="b-delivery__content">
		<div class="b-img-wrapper">
			<a href="/shop/<?=$_REQUEST['CITY']?>/<?=$arResult['SHOP_INFO']['ID']?>/">
				<img src="<?=$arResult['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arResult['SHOP_INFO']['NAME']?>" />
			</a>
		</div>
	</div>
	<div class="b-delivery__close">Ближайшая доставка: <span>15:00-17:00</span></div>
	<div class="b-delivery__interval">
		<a href="javascript:" id="show_all_interval" class="b-button b-button_delivery">Все интервалы</a>
		<a href="javascript:" class="b-button b-button_delivery">Дополнительно</a>
	</div>
</div>
<div class="hide_content">
	<div id="shop_times1">
		<div class="b-popup b-popup-card b-popu-card_interval">
			<div class="b-popup-interval">
				<button class="b-button b-button__close-popup"></button>
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_reverse">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<div class="search__title">Интервалы</div>
							</div>
						</div>
					</div>
				</div>
				<div class="interval__content">
					<div class="interval__img-wrapper">
						<div class="interval__img">
							<a href="javascript:"><img src="<?=$arResult['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arResult['SHOP_INFO']['NAME']?>"></a>
						</div>
					</div>
					<div class="interval__table">
						<div class="interval_wrap">
							<? foreach ($deliverCurrentTime as $num => $arTime): ?>
								<div class="int_date_item">
									<button type="button" data-time-id="time_item_<?=$num?>" class="b-button interval__date">
										<?=$arTime['NAME']?>
									</button>
									<div id="time_item_<?=$num?>" class="time_items<?=($num == 0 ? ' active' : false)?>">
										<?
										$d = new \DateTime();
										foreach ($arTime['ITEMS'] as $k => $time) {
											$timeFrom = str_replace('-', ':', trim($time['PROPERTY_TIME_FROM_VALUE']));
											$timeTo = str_replace('-', ':', trim($time['PROPERTY_TIME_TO_VALUE']));
											$matchFrom = explode(':', $timeFrom);
											$time['PRICE_FORMAT'] = $time['PRICE_FORMAT'].' <span>&#8381</span>';
											if (!empty($matchFrom[0]) && $d->format('N') == $arTime['CODE']){
												$timeStr = $d->format('d.m.Y ').$matchFrom[0];

												if ($matchFrom[1] == '00' || $matchFrom[1] == '0' || empty($matchFrom[1])){
													$timeStr .= ':00:00';
													$dateItem = new \DateTime($timeStr);
													if ($dateItem < $d){
														$time['PRICE_FORMAT'] = 'недоступно';
													}
												}
											}
											?>
											<div class="time_item_item">
												<span class="interval__time"><?=$timeFrom?> - <?=$timeTo?></span>
												<span class="interval__price"><?=$time['PRICE_FORMAT'];?></span>
											</div>
										<? } ?>
									</div>
								</div>
							<? endforeach; ?>
						</div>
						<a href="javascript:" id="all_shop_link_time">Другие магазины</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="all_shop_times">
		<div class="b-popup b-popup-card b-popu-card_intervals">
			<div class="b-popup-intervas">
				<button class="b-button b-button__close-popup"></button>
				<div class="b-popup-cart__head">
					<div class="b-products-block-top b-ib bg_reverse">
						<div class="cart__img-wrapper">
							<div class="cart__prod-title">
								<div class="search__title">Интервалы</div>
							</div>
						</div>
					</div>
				</div>
				<div class="intervals__content">
					<? foreach ($arResult['ALL_SHOPS'] as $id => $arShop): ?>
						<div class="intervals__coll b-ib">
							<div class="interval__img-wrapper">
								<div class="interval__img interval__img-100">
									<a href="javascript:">
										<img src="<?=$arShop['IMAGE']['src']?>" alt="">
									</a>
								</div>
							</div>
							<?
							$d = new \DateTime();
							foreach ($arShop['DELIVERY_TIME'] as $arTime) {
								if ($arTime['CODE'] == $d->format('N')){
									?>
									<? foreach ($arTime['ITEMS'] as $time) {
										?>
										<div class="intervals">

											<div class="intervals__time b-ib">
												<?
												$timeFrom = str_replace('-', ':', trim($time['PROPERTY_TIME_FROM_VALUE']));
												$timeTo = str_replace('-', ':', trim($time['PROPERTY_TIME_TO_VALUE']));
												?>
												<span><?=$timeFrom?> - <?=$timeFrom?></span>
												<span><?=$arTime['NAME']?></span>
											</div>

											<?
											$bAllow = true;
											$matchFrom = explode(':', $timeFrom);
											$time['PRICE_FORMAT'] = $time['PRICE_FORMAT'].' <span>&#8381</span>';
											if (!empty($matchFrom[0]) && $d->format('N') == $arTime['CODE']){
												$timeStr = $d->format('d.m.Y ').$matchFrom[0];

												if ($matchFrom[1] == '00' || $matchFrom[1] == '0' || empty($matchFrom[1])){
													$timeStr .= ':00:00';
													$dateItem = new \DateTime($timeStr);
													if ($dateItem < $d){
														$time['PRICE_FORMAT'] = 'недоступно';
														$bAllow = false;
													}
												}
											}
											?>
											<?if($bAllow):?>
												<div class="intervals__price b-ib">
													<?=$time['PRICE_FORMAT']?>
												</div>
											<?else:?>
												<?=$time['PRICE_FORMAT']?>
											<?endif;?>
										</div>
									<? } ?>
								<? } ?>
							<? } ?>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
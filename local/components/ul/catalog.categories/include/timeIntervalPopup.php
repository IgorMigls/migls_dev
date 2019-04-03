<?php
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
/** @var array $deliverCurrentTime */
$deliverCurrentTime = $arParams['SHOP_INFO']['DELIVERY_TIME'];
?>
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
							<a href="javascript:"><img src="<?=$arParams['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arParams['SHOP_INFO']['NAME']?>"></a>
						</div>
					</div>
					<div class="interval__table">
						<div class="interval_wrap">
							<?//dump($deliverCurrentTime); ?>
							<? foreach ($deliverCurrentTime as $num => $arTime):?>
								<div class="int_date_item">
									<button type="button" data-time-id="time_item_<?=$num?>" class="b-button interval__date <?=($num == 0 ? 'timer_shop_hover' : false)?>">
										<?=$arTime['NAME']?>, <?=$arTime['FORMAT']['DAY']?> <?=$arTime['FORMAT']['MONTH_LOCALE']?>
									</button>
									<div id="time_item_<?=$num?>" class="time_items<?=($num == 0 ? ' active' : false)?>">
										<?
										foreach ($arTime['TIMES'] as $k => $time) {

											$time['PRICE_FORMAT'] = $time['PRICE'].' <span>&#8381</span>';
											if($time['ACTIVE'] == 'N' || $time['CLOSED_BY_ADMIN'] == 'Y'){
												$time['PRICE_FORMAT'] = 'недоступно';
											}
											?>
											<div class="time_item_item">
												<span class="interval__time"><?=$time['TIME_FROM']?> - <?=$time['TIME_TO']?></span>
												<span class="interval__price"><?=$time['PRICE_FORMAT'];?></span>
											</div>
										<? } ?>
									</div>
								</div>
							<? endforeach; ?>
						</div>
<!--						<a href="javascript:" id="all_shop_link_time">Другие магазины</a>-->
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
					<? foreach ($arParams['ALL_SHOPS'] as $id => $arShop): ?>
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
											<? if ($bAllow): ?>
												<div class="intervals__price b-ib">
													<?=$time['PRICE_FORMAT']?>
												</div>
											<? else: ?>
												<?=$time['PRICE_FORMAT']?>
											<? endif; ?>
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

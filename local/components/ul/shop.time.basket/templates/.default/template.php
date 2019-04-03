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
$this->setFrameMode(true);
?>
<div class="hide_content___">
	<?foreach ($arResult['ALL_SHOPS'] as $arShop):
		$deliverCurrentTime = $arShop['DELIVERY_TIME'];
		?>
		<div id="shop_time_window_<?=$arShop['ID']?>" style="display: none" class="shop_time_window">
			<div class="b-popup b-popup-card b-popu-card_interval">
				<div class="b-popup-interval">
					<button class="b-button b-button__close-popup"></button>
					<div class="b-popup-cart__head">
						<div class="b-products-block-top b-ib bg_reverse">
							<div class="cart__img-wrapper">
								<div class="cart__prod-title">
									<div class="search__title">Интервалы</div>
									<a href="javascript:" class="close_int_basket" ng-click="closeIntervals()"></a>
								</div>
							</div>
						</div>
					</div>
					<div class="interval__content">
						<div class="interval__img-wrapper">
							<div class="interval__img">
								<a href="javascript:">
									<img src="<?=$arShop['IMAGE']['src']?>" alt="<?=$arShop['NAME']?>">
								</a>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	<?endforeach;?>
</div>

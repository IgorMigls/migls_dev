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
//PR($arResult['FIELDS']['CANCELED']);
use UL\Tools;
?>
<div class="detail_order">
	<div class="lk__history">
		<div class="lk__history__col1"><span class="history__order1">
				Заказ №<?=$arResult['FIELDS']['ACCOUNT_NUMBER']?>, <?=$arResult['FIELDS']['DATE_FORMAT']?></span>
			<span class="history__order2"><?=$arResult['FIELDS']['ADDRESS_FORMAT']?></span>
			<?/*<div class="favourite__print">
				<a href="?orderId=<?=$arResult['FIELDS']['ID']?>&repeat=Y#/orders/<?=$arResult['ID']?>/catalog/" class="repeat">Повторить</a>
				<a href="" class="favourite">Сохранить</a>
				<a href="" class="print">Распечатать</a>
			</div>*/?>
		</div>
		<div class="lk__history__col2">
			<div class="span history__total">Сумма </div>
			<div class="span history__sub">
				<nobr><?=$arResult['SUM_FORMAT']?>&#8381;</nobr>
			</div>
		</div>
	</div>
	<div class="order_items_wrap">
		<div class="order_left_col">
			<? foreach ($arResult['SHOP'] as $orderId => $item) {?>
				<div class="history_items_order_wrapper">
					<div class="order_shop_item">
						<img src="<?=$item['IMG']['src']?>" />
						<div class="status_order_name" ng-if="!statusOrder">
							<?=$arResult['STATUS'][$orderId]?>
						</div>
						<div class="status_order_name" ng-if="statusOrder">
							{{statusOrder}}
						</div>
					</div>
					<?foreach ($arResult['BASKET'][$orderId]['ITEMS'] as $arItem):?>
						<div class="lk__history-item">
							<div class="b-products-slider__item">
								<div class="history__items b-ib-wrapper">
									<div class="cart__col-1 b-ib">
										<div class="b-product-preview__pic height-auto b-ib">
											<a class="img_order_product"
													style="height: 85px; overflow: hidden; background-image: url(<?=$arItem['ELEMENT']['IMG']['src']?>)"
													href="#/orders/<?=$arParams['ID']?>/catalog/<?=$arItem['ELEMENT']['ELEMENT_ID']?>">
											</a>
										</div>
									</div>
									<div class="cart__col-2 b-ib">
										<div class="b-product-preview__name b-ib height-auto">
											<a href="#/orders/<?=$arParams['ID']?>/catalog/<?=$arItem['ELEMENT']['ELEMENT_ID']?>">
												<?=$arItem['NAME']?>
											</a>
										</div>
										<div class="b-product-preview__buy d-block b-ib">
											<div class="b-product-preview__price b-ib"><?=Tools::formatPrice($arItem['PRICE'])?>
												<span class="b-rouble">&#8381;</span></div>
											<div class="b-item__count b-ib"><?=floatval($arItem['QUANTITY'])?>шт.</div>
										</div>
										<!--<div class="cart__comments">
											<span class="cart__comments_span">Комментарий:</span>
											<span class="cart__comments_span">Только сегодняшнее! Не вчерашнее.</span>
										</div>-->
									</div>
								</div>
							</div>
						</div>
					<?endforeach;?>
				</div>
			<?}?>
		</div>
		<div class="order_right_col">
			<div class="b-popu-card_check b-popup-chekc_lk">
				<div class="b-popup-check">
					<div class="check__title">Ваш заказ</div>
					<div class="check__total-items">
						<div class="check__total">Итого: <span>
								<?=Tools::formatContProduct($arResult['COUNT'])?>
								на сумму <?=$arResult['SUM_FORMAT']?></span><span class="check-rub">&#8381</span>
						</div>
					</div>
					<?if($arResult['FIELDS']['CANCELED'] !== 'Y'):?>
						<button ng-if="btnTitle" ng-click="cancel('<?=implode('|', array_keys($arResult['BASKET']))?>')"
								class="b-button b-button_green b-button_check b-button_big">
							{{btnTitle}}
						</button>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
</div>

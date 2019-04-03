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
$this->addExternalJs('/local/components/ul/products.category/templates/left_all/script.js');
$deliverCurrentTime = $arResult['SHOP_INFO']['DELIVERY_TIME'];
//dump($deliverCurrentTime);
?>
<div class="b-catalog b-ib-wrapper shop_detail">
	<div class="b-catalog__left b-ib-wrapper">
		<div class="b-sidebar__delivery">
			<div class="b-delivery__content">
				<div class="b-img-wrapper">
					<img src="<?=$arResult['SHOP_INFO']['IMAGE']['src']?>" alt="<?=$arResult['SHOP_INFO']['NAME']?>" />
				</div>
			</div>
			<div class="b-delivery__close">Ближайшая доставка: <span>15:00-17:00</span></div>
			<div class="b-delivery__interval">
				<a href="javascript:" id="show_all_interval" class="b-button b-button_delivery">Все интервалы</a>
				<a href="javascript:" class="b-button b-button_delivery">Дополнительно</a>
			</div>
		</div>
		<div class="b-sidebar__catalog-wrapper">
			<div class="b-sidebar__catalog b-sidebar__catalog_white">
				<div class="b-catalog__favorite">
					<ul class="catalog__acordion">
						<!--<li class="catalog__list"> <a href="#" class="catalog__title result__item">Товары со скидками</a>
							<ul class="catalog__sub">
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
							</ul>
						</li>
						<li class="catalog__list"> <a href="#" class="catalog__title result__item">Акции</a>
							<ul class="catalog__sub">
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
							</ul>
						</li>
						<li class="catalog__list"> <a href="#" class="catalog__title result__item">Избранное</a>
							<ul class="catalog__sub">
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
								<li class="catalog__item__sub"> <a href="" class="catalog__item">Овощи, фрукты, зелень, грибы</a></li>
							</ul>
						</li>-->
						<? foreach ($arResult['SECTIONS'] as $section): ?>
							<? if (count($section['ITEMS'])): ?>
								<li class="catalog__list">
									<a href="<?=$section['URL']?>" class="catalog__title catalog__title_grey result__item"><?=$section['CATEGORY_NAME']?></a>

									<ul class="catalog__sub">
										<? foreach ($section['ITEMS'] as $sId => $item) { ?>
											<li class="catalog__item__sub catalog__item__sub_orange">
												<a href="<?=$section['MAIN_URL']?><?=$item['ID']?>/" class="catalog__item"><?=$item['NAME']?></a>
											</li>
										<? } ?>
									</ul>
								</li>
							<? endif; ?>
						<? endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="b-catalog__right b-ib-wrapper">
		<div class="b-catalog__ashan">
			<div class="b-main__grid">
				<div class="b-grid__row b-ib-wrapper">
					<div class="b-grid__left b-ib" style="height: 593px;">
						<div class="b-grid__col"><span class="b-grid__layer b-grid__layer_blue"></span>
							<div class="b-grid__menu b-grid__menu_left">
								<div class="b-grid__title">
									<a href="<?=$arResult['HAVKA'][0]['URL']?>" class="b-grid__title__link b-grid__i1">
										<?=$arResult['HAVKA'][0]['NAME']?>
									</a>
								</div>

								<? if (count($arResult['HAVKA'][0]['SUBSECTION']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['HAVKA'][0]['SUBSECTION'] as $sub): ?>
											<li class="b-grid__item">
												<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>

							</div>
							<img src="<?=$arResult['HAVKA'][0]['PICTURE']['SRC']?>" alt="" class="row__img">
						</div>
					</div>
					<div class="b-grid__right b-ib" style="width: 364px;">
						<div class="b-grid__col" style="height: 206px">
							<span class="b-grid__layer b-grid__layer_orange"></span>
							<img src="<?=$arResult['HAVKA'][1]['PICTURE']['SRC']?>" alt="" class="row__img">
							<div class="b-grid__menu">
								<div class="b-grid__title">
									<a href="<?=$arResult['HAVKA'][1]['URL']?>" class="b-grid__title__link b-grid__i2">
										<?=$arResult['HAVKA'][1]['NAME']?>
									</a>
								</div>

								<? if (count($arResult['HAVKA'][1]['SUBSECTION']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['HAVKA'][1]['SUBSECTION'] as $sub): ?>
											<li class="b-grid__item">
												<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							</div>
						</div>
						<div class="b-grid__col"><span class="b-grid__layer b-grid__layer_green"></span>
							<img src="<?=$arResult['HAVKA'][2]['PICTURE']['SRC']?>" alt="" class="row__img">
							<div class="b-grid__menu">
								<div class="b-grid__title">
									<a href="<?=$arResult['HAVKA'][2]['URL']?>" class="b-grid__title__link b-grid__i3">
										<?=$arResult['HAVKA'][2]['NAME']?>
									</a>
								</div>

								<? if (count($arResult['HAVKA'][2]['SUBSECTION']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['HAVKA'][2]['SUBSECTION'] as $sub): ?>
											<li class="b-grid__item">
												<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							</div>
						</div>
						<div class="b-grid__row b-ib-wrapper">
							<div class="b-grid__col b-ib" style="width: 182px">
								<span class="b-grid__layer b-grid__layer_red"></span>
								<img src="<?=$arResult['HAVKA'][3]['PICTURE']['SRC']?>" alt="" class="row__img">
								<div class="b-grid__menu b-grid__menu_top">
									<div class="b-grid__title">
										<a href="<?=$arResult['HAVKA'][3]['URL']?>" class="b-grid__title__link b-grid__i4">
											<?=$arResult['HAVKA'][3]['NAME']?>
										</a>
									</div>

									<? if (count($arResult['HAVKA'][3]['SUBSECTION']) > 0): ?>
										<ul class="b-grid__ul">
											<? foreach ($arResult['HAVKA'][3]['SUBSECTION'] as $sub): ?>
												<li class="b-grid__item">
													<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
												</li>
											<? endforeach; ?>
										</ul>
									<? endif; ?>
								</div>
							</div>
							<div class="b-grid__col b-ib" style="width: 182px; height: 182px">
								<span class="b-grid__layer b-grid__layer_dark"></span>
								<img src="<?=$arResult['HAVKA'][4]['PICTURE']['SRC']?>" alt="" class="row__img">
								<div class="b-grid__menu b-grid__menu_top">
									<div class="b-grid__title">
										<a href="<?=$arResult['HAVKA'][4]['URL']?>" class="b-grid__title__link b-grid__i5">
											<?=$arResult['HAVKA'][4]['NAME']?>
										</a>
									</div>

									<? if (count($arResult['HAVKA'][4]['SUBSECTION']) > 0): ?>
										<ul class="b-grid__ul">
											<? foreach ($arResult['HAVKA'][4]['SUBSECTION'] as $sub): ?>
												<li class="b-grid__item">
													<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
												</li>
											<? endforeach; ?>
										</ul>
									<? endif; ?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="b-grid__row b-ib-wrapper">
					<div class="b-grid__left b-grid__left_w b-ib">
						<div class="b-grid__row b-ib-wrapper">
							<div class="b-grid__col b-ib view__all">
								<div class="b-grid__menu_bottom">
									<div class="b-grid__title">
										<div class="b-grid__title__head">Смотрите все категории товаров</div>
									</div>
									<div class="b-products-block-top__right b-ib">
										<a href="<?=$arResult['HAVKA_INFO']['URL']?>" class="b-button b-button_show b-button_single">Смотреть</a>
									</div>
								</div>
							</div>
							<div class="b-grid__col b-ib mini__slide">
								<div class="b-products-slider">
									<div class="b-products-slider__slider js-products-slider-4">
										<? foreach ($arResult['HAVKA'] as $k => $arSection):
											if ($k > 4):
												if ($arSection['PICTURE']['ID'] > 0){
													$img = CFile::ResizeImageGet(
														$arSection['PICTURE']['ID'],
														['width' => 86, 'height' => 65],
														BX_RESIZE_IMAGE_EXACT,
														true
													);
												}
												?>
												<div class="b-products-slider__item js-products-slider-item">
													<div class="b-mini__preview">
														<div class="mini-preview__pic b-product-preview__pic_margin b-ib">
															<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$arSection['ID']?>/">
																<img src="<?=$img['src']?>" alt="">
															</a>
														</div>
														<div class="b-product-preview__name b-ib">
															<a href="<?=$arResult['HAVKA_INFO']['URL']?><?=$arSection['ID']?>/">
																<?=$arSection['NAME']?>
																<div class="b-product-preview__count b-ib"></div>
															</a>
														</div>
													</div>
												</div>
											<?endif;
										endforeach; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="b-grid__row">
							<div class="b-grid__col b-ib">
								<div class="discounts">
									<span class="discount__text">До конца лета </span>
									<span class="discount__text">на всю органическую еду</span>
									<span class="discount__per">скидка 5%</span>
									<div class="b-products-block-top__right b-ib b-products__align-right">
										<a href="" class="b-button b-button_delivery b-button_single">Смотреть</a>
									</div>
								</div>
								<img src="/local/dist/images/row-6.jpg" alt="" class="row__img">
							</div>
						</div>
						<div class="b-grid__col"></div>
					</div>
				</div>
			</div>
		</div>
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
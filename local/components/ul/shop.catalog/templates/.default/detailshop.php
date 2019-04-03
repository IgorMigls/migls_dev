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
//dump($arResult['IMAGES']);
?>
<div class="b-catalog b-ib-wrapper shop_detail">
	<div class="b-catalog__left b-ib-wrapper">
		<? $categories = $APPLICATION->IncludeComponent('ul:catalog.categories', 'shop_inner', array(
			'CITY_ID' => $arResult['SHOPS']['CITY_ID'],
			'SHOP_ID' => $arResult['SHOP_INFO']['ID'],
			'IBLOCK_ID' => $arResult['IBLOCK_ID'],
			'IBLOCK_INFO' => $arResult['IBLOCK_INFO'],
			'URL_TEMPLATE' => $arParams['URLS'],
			'SHOP_INFO' => $arResult['SHOP_INFO'],
			'ALL_SHOPS' => $arResult['ALL_SHOPS'],
			'SECTIONS' => $arResult['SECTIONS']
		), $component) ?>
	</div>
	<div class="b-catalog__right b-ib-wrapper">
		<div class="b-catalog__ashan">
			<div class="b-main__grid">
				<div class="b-grid__row b-ib-wrapper">
					<div class="b-grid__left b-ib" style="height: 593px;">
						<div class="b-grid__col"><span class="b-grid__layer b-grid__layer_blue"></span>
							<div class="b-grid__menu b-grid__menu_left">
								<div class="b-grid__title">
									<a href="<?=$arResult['IMAGES'][0]['MAIN_URL']?>" class="b-grid__title__link b-grid__i1">
										<?=$arResult['IMAGES'][0]['CATEGORY_NAME']?>
									</a>
								</div>

								<? if (count($arResult['IMAGES'][0]['ITEMS']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['IMAGES'][0]['ITEMS'] as $sub): ?>
											<li class="b-grid__item">
												<a href="<?=$arResult['IMAGES'][0]['MAIN_URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>

							</div>
							<img src="<?=$arResult['IMAGES'][0]['IMAGE_BLOCK']['SRC']?>" alt="" class="row__img">
						</div>
					</div>
					<div class="b-grid__right b-ib" style="width: 364px;">
						<div class="b-grid__col" style="height: 206px">
							<span class="b-grid__layer b-grid__layer_orange"></span>
							<img src="<?=$arResult['IMAGES'][1]['IMAGE_BLOCK']['SRC']?>" alt="" class="row__img">
							<div class="b-grid__menu">
								<div class="b-grid__title">
									<a href="<?=$arResult['IMAGES'][1]['MAIN_URL']?>" class="b-grid__title__link b-grid__i2">
										<?=$arResult['IMAGES'][1]['CATEGORY_NAME']?>
									</a>
								</div>

								<? if (count($arResult['IMAGES'][1]['ITEMS']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['IMAGES'][1]['ITEMS'] as $sub):?>
											<li class="b-grid__item">
												<a href="<?=$arResult['IMAGES'][1]['MAIN_URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							</div>
						</div>
						<div class="b-grid__col"><span class="b-grid__layer b-grid__layer_green"></span>
							<img src="<?=$arResult['IMAGES'][2]['IMAGE_BLOCK']['SRC']?>" alt="" class="row__img">
							<div class="b-grid__menu">
								<div class="b-grid__title">
									<a href="<?=$arResult['IMAGES'][2]['MAIN_URL']?>" class="b-grid__title__link b-grid__i3">
										<?=$arResult['IMAGES'][2]['CATEGORY_NAME']?>
									</a>
								</div>

								<? if (count($arResult['IMAGES'][2]['ITEMS']) > 0): ?>
									<ul class="b-grid__ul">
										<? foreach ($arResult['IMAGES'][2]['ITEMS'] as $sub): ?>
											<li class="b-grid__item">
												<a href="<?=$arResult['IMAGES'][2]['MAIN_URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
											</li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							</div>
						</div>
						<div class="b-grid__row b-ib-wrapper">
							<div class="b-grid__col b-ib" style="width: 182px; height: 182px">
								<span class="b-grid__layer b-grid__layer_red"></span>
								<img src="<?=$arResult['IMAGES'][3]['IMAGE_BLOCK']['SRC']?>" alt="" class="row__img">
								<div class="b-grid__menu b-grid__menu_top">
									<div class="b-grid__title">
										<a href="<?=$arResult['IMAGES'][3]['MAIN_URL']?>" class="b-grid__title__link b-grid__i4">
											<?=$arResult['IMAGES'][3]['CATEGORY_NAME']?>
										</a>
									</div>

									<? if (count($arResult['IMAGES'][3]['ITEMS']) > 0): ?>
										<ul class="b-grid__ul">
											<? foreach ($arResult['IMAGES'][3]['ITEMS'] as $sub): ?>
												<li class="b-grid__item">
													<a href="<?=$arResult['IMAGES'][3]['MAIN_URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
												</li>
											<? endforeach; ?>
										</ul>
									<? endif; ?>
								</div>
							</div>
							<div class="b-grid__col b-ib" style="width: 182px; height: 182px">
								<span class="b-grid__layer b-grid__layer_dark"></span>
								<img src="<?=$arResult['IMAGES'][4]['IMAGE_BLOCK']['SRC']?>" alt="" class="row__img">
								<div class="b-grid__menu b-grid__menu_top">
									<div class="b-grid__title">
										<a href="<?=$arResult['IMAGES'][4]['MAIN_URL']?>" class="b-grid__title__link b-grid__i5">
											<?=$arResult['IMAGES'][4]['CATEGORY_NAME']?>
										</a>
									</div>

									<? if (count($arResult['IMAGES'][4]['ITEMS']) > 0): ?>
										<ul class="b-grid__ul">
											<? foreach ($arResult['IMAGES'][4]['ITEMS'] as $sub): ?>
												<li class="b-grid__item">
													<a href="<?=$arResult['IMAGES'][4]['MAIN_URL']?><?=$sub['ID']?>/" class="b-grid__link"><?=$sub['NAME']?></a>
												</li>
											<? endforeach; ?>
										</ul>
									<? endif; ?>

								</div>
							</div>
						</div>
					</div>
				</div>
				<?/*<div class="b-grid__row b-ib-wrapper">
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
										<? foreach ($arResult['IMAGES'] as $k => $arSection):
											if ($k > 4):
												$img = null;
												if ($arSection['IMAGE_BLOCK']['ID'] > 0){
													$img = CFile::ResizeImageGet(
														$arSection['IMAGE_BLOCK']['ID'],
														['width' => 86, 'height' => 65],
														BX_RESIZE_IMAGE_EXACT,
														true
													);
												}
												?>
												<div class="b-products-slider__item js-products-slider-item">
													<div class="b-mini__preview">
														<div class="mini-preview__pic b-product-preview__pic_margin b-ib">
															<a href="<?=$arSection['MAIN_URL']?>">
																<img src="<?=$img['src']?>" alt="" />
															</a>
														</div>
														<div class="b-product-preview__name b-ib">
															<a href="<?=$arSection['MAIN_URL']?>">
																<?=$arSection['CATEGORY_NAME']?>
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
						<!--<div class="b-grid__row">
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
						</div>-->
						<div class="b-grid__col"></div>
					</div>
				</div>*/?>
			</div>
		</div>
		<?//dump($arResult)?>
		<? /*$APPLICATION->IncludeComponent(
			'ul:products.popular',
			'shop_detail', [
				'IBLOCK_SKU_ID' => 107,
				'SHOP_CURRENT' => $arResult['SHOP_INFO']['ID']
			],
			false
		) */?>

	</div>
</div>
<?
if(empty($arResult['SHOP_INFO']) || !in_array($arResult['SHOP_INFO']['ID'],$_SESSION['REGIONS']['SHOP_ID'])){?>
	<script>
		swal({
			title: 'Внимание',
			type: 'warning',
			text: 'Вы перешли в магазин, которого нет в текущей зоне доставки',
			backdrop: 'rgba(255,156,34,0.69)',
			confirmButtonColor: '#2dc467',
			customClass: 'shop_err'
		}, function () {
			window.location.assign('/');
		});
	</script>
<?
}
?>

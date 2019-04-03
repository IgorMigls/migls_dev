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
//PR($arResult);
$food = [
	36, 37, 42, 44, 46, 48, 50, 52, 54, 56, 58, 62, 64
];
?>
<?$i = 0?>
<div class="b-main-category b-ib-wrapper">
	<div class="b-main-category__title b-title_h4">Категории</div>
	<div class="b-main-category-items">
		<div class="b-main-category-items__row">
			<div class="b-main-category-items__item b-main-category-items__item_55">
				<div style="left: 5%; top: 50px;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_1.jpg" alt=""></div>
				<div class="b-main-category-list b-main-category-list_offset_40 b-ib-wrapper">
					<h4 class="b-main-category-list__title">
						<a href="<?=$arResult[66]['MAIN_URL']?>">Продукты питания</a>
					</h4>
					<ul class="b-main-category-list__block b-ib">
						<?$k = 0;?>
						<?foreach ($arResult[66]['ITEMS'] as $k => $arItem):?>
							<?if($k < 6):?>
								<li class="b-main-category-list__item"><a href="<?=$arItem['URL_LIST']?>"><?=$arItem['NAME']?></a></li>
							<?endif;?>
						<?$k++; endforeach;?>
					</ul>
					<ul class="b-main-category-list__block b-ib">
						<?foreach ($arResult[66]['ITEMS'] as $k => $arItem):?>
							<?if($k >= 6):?>
								<li class="b-main-category-list__item"><a href="<?=$arItem['URL_LIST']?>"><?=$arItem['NAME']?></a></li>
							<?endif;?>
						<?$k++; endforeach;?>
					</ul>
				</div>
			</div>
			<div class="b-main-category-items__item b-main-category-items__item_45">
				<div style="bottom: 10px; right: 0;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_2.jpg" alt=""></div>
				<div class="b-main-category-list">
					<h4 class="b-main-category-list__title"><a href="<?=$arResult[32]['MAIN_URL']?>"><?=$arResult[32]['MAIN_NAME']?></a></h4>
					<ul class="b-main-category-list__block">
						<?foreach ($arResult[32]['ITEMS'] as $k => $arItem):?>
							<li class="b-main-category-list__item"><a href="<?=$arItem['URL_LIST']?>"><?=$arItem['NAME']?></a></li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
		</div>
		<div class="b-main-category-items__row">
			<div class="b-main-category-items__item b-main-category-items__item_55">
				<div style="right: 0; bottom: 0;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_3.jpg" alt=""></div>
				<div class="b-main-category-list b-ib-wrapper">
					<h4 class="b-main-category-list__title"><a href="<?=$arResult[34]['MAIN_URL']?>"><?=$arResult[34]['MAIN_NAME']?></a></h4>
					<ul class="b-main-category-list__block b-ib">
						<?foreach ($arResult[34]['ITEMS'] as $k => $arItem):?>
							<li class="b-main-category-list__item"><a href="<?=$arItem['URL_LIST']?>"><?=$arItem['NAME']?></a></li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
			<div class="b-main-category-items__item b-main-category-items__item_45">
				<div style="bottom: 0; right: 0;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_4.jpg" alt=""></div>
				<div class="b-main-category-list">
					<h4 class="b-main-category-list__title"><a href="<?=$arResult[30]['MAIN_URL']?>"><?=$arResult[30]['MAIN_NAME']?></a></h4>
					<ul class="b-main-category-list__block">
						<?foreach ($arResult[30]['ITEMS'] as $k => $arItem):?>
							<li class="b-main-category-list__item"><a href="<?=$arItem['URL_LIST']?>"><?=$arItem['NAME']?></a></li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
		</div>
		<div class="b-main-category-items__row">
			<div class="b-main-category-items__item b-main-category-items__item_40">
				<div style="right: 10px; bottom: 20px;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_5.jpg" alt=""></div>
				<pb-main-category-list class="b-ib-wrapper">
					<h4 class="b-main-category-list__title"><a href="">Зоомагазин</a></h4>
					<ul class="b-main-category-list__block b-ib">
						<li class="b-main-category-list__item"><a href="">Продукты, напитки</a></li>
						<li class="b-main-category-list__item"><a href="">Красота и здоровье</a></li>
						<li class="b-main-category-list__item"><a href="">Одежда, белье, бандажи</a></li>
						<li class="b-main-category-list__item"><a href="">Товары для прогулок и поездок</a></li>
					</ul>
				</pb-main-category-list>
			</div>
			<div class="b-main-category-items__item b-main-category-items__item_40">
				<div style="right: 20px; bottom: 10px;" class="b-main-category__pic"><img src="/local/dist/images/demo/main-sections_6.jpg" alt=""></div>
				<div class="b-main-category-list b-ib-wrapper">
					<h4 class="b-main-category-list__title"><a href="">Электротовары</a></h4>
					<ul class="b-main-category-list__block b-ib">
						<li class="b-main-category-list__item"><a href="">Продукты, напитки</a></li>
						<li class="b-main-category-list__item"><a href="">Красота и здоровье</a></li>
						<li class="b-main-category-list__item"><a href="">Мясо, птица, рыба и морепродукты</a></li>
						<li class="b-main-category-list__item"><a href="">Товары для прогулок и поездок</a></li>
						<li class="b-main-category-list__item"><a href="">Яйцо</a></li>
					</ul>
				</div>
			</div>
			<div class="b-main-category-items__item b-main-category-items__item_20">
				<div class="b-main-category-list b-ib-wrapper">
					<h4 class="b-main-category-list__title"><a href="">Остальное</a></h4>
					<ul class="b-main-category-list__block b-ib">
						<li class="b-main-category-list__item"><a href="">Продукты, напитки</a></li>
						<li class="b-main-category-list__item"><a href="">Красота и здоровье</a></li>
						<li class="b-main-category-list__item"><a href="">Мясо, птица, рыба и морепродукты</a></li>
						<li class="b-main-category-list__item"><a href="">Товары для прогулок и поездок</a></li>
						<li class="b-main-category-list__item"><a href="">Яйцо</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

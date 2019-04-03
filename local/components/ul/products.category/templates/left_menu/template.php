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
$this->addExternalJs($componentPath.'/templates/left_all/script.js');
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>
<div id="sidebar" class="b-sidebar__catalog-wrapper">
	<div class="b-sidebar__catalog__head">
		<div class="b-ctalog__title b-ib">Каталог товаров</div>
		<div class="b-main-sections__more b-main-sections__more_sidebar b-ib">
			<button data-toggle-element="#sidebar" data-toggle-class="open" class="b-button js-toggle-class"></button>
		</div>
	</div>
	<div class="b-sidebar__catalog b-sidebar__catalog_white b-sidebar__off">
		<div class="b-catalog__favorite">
			<ul class="catalog__acordion catalog__acordion_new-icons">
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
				<?foreach ($arResult as $code => $arType):?>
					<li class="catalog__list <?=($request->get('CATALOG') == $code ? 'open_item_menu' : false)?>">
						<a href="#" class="catalog__title catalog__title_grey result__item"><?=$arType['MAIN_NAME']?></a>
						<ul class="catalog__sub">
							<?foreach ($arType['ITEMS'] as $arItem):?>
								<li class="catalog__item__sub">
									<a href="<?= $arItem['URL_LIST'] ?>" class="catalog__item"><?= $arItem['NAME'] ?></a>
								</li>
							<?endforeach;?>
						</ul>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	</div>
</div>

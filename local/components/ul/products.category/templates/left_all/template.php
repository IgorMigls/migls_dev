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
//PR($arParams['URL_TEMPLATE']);
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
//PR($request->toArray());
?>
<div class="b-sidebar__catalog-wrapper">
	<div class="b-sidebar__catalog b-sidebar__catalog_white">
		<div class="b-catalog__favorite">
			<ul class="catalog__acordion catalog__acordion_new-icons">
				<?foreach ($arResult as $code => $arType):?>
					<li class="catalog__list <?=($request->get('CATALOG') == $code ? 'open_item_menu' : false)?>">
						<a href="#" class="catalog__title catalog__title_grey result__item">
							<?=$arType['MAIN_NAME']?>
						</a>
						<?if(count($arType['ITEMS']) > 0){?>
							<ul class="catalog__sub">
								<?foreach ($arType['ITEMS'] as $arItem):?>
									<li class="catalog__item__sub">
										<a href="<?=$arItem['URL_LIST']?>" class="catalog__item"><?=$arItem['NAME']?></a>
									</li>
								<?endforeach;?>
							</ul>
						<?}?>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	</div>
</div>

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
//dump($arParams['SECTIONS']);
//dump($arResult);
$arParams['SHOW_MAIN_HEAD'] = 'Y';
?>
<div class="b-sidebar__catalog-wrapper">
	<?if($arParams['SHOW_MAIN_HEAD'] === 'Y'):?>
		<div class="b-sidebar__catalog__head">
			<div class="b-ctalog__title b-ib">Каталог магазинов</div>
			<div class="b-main-sections__more b-main-sections__more_sidebar b-ib" style="margin-left: 20px">
				<button data-toggle-element="#sidebar" data-toggle-class="open" class="b-button js-toggle-class"></button>
			</div>
		</div>
	<?endif;?>
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
					<? foreach ($arResult['SECTIONS'] as $arItem) {
						$href = count($arItem['ITEMS']) == 0 ? $arItem['MAIN_URL'] : 'javascript:';
						?>
						<li class="catalog__list">
							<a href="<?=$href?>" class="catalog__title catalog__title_grey result__item ">
								<? if (!empty($arItem['ICON']['SRC'])): ?>
									<i style="background: url(<?=$arItem['ICON']['SRC']?>) no-repeat center center"></i>
								<? endif; ?>
								<span><?=$arItem['MAIN_NAME']?></span>
							</a>
							<? if (count($arItem['ITEMS']) > 0){?>
								<ul class="catalog__sub">
									<? foreach ($arItem['ITEMS'] as $subSection):

										$url = $arItem['MAIN_URL'].($subSection['ID'] ? $subSection['ID'] : $subSection['SECTION_ID']).'/';

										$subsections = $subSection['SUBSECTION'];
										if(count($subsections) == 0){
											$subsections = $subSection['SUB_SECTION'];
										}

										if(count($subsections) > 0){
											$url = 'javascript:';
										}
										?>
										<li class="catalog__item__sub">
											<a href="<?=$url?>" class="catalog__item">
												<?=$subSection['NAME']?>
											</a>
											<?if(count($subsections) > 0):?>
												<ul class="subcategory">
													<? foreach ($subsections as $sub) {
														$id = (int)$sub['ID'];
														if($id == 0){
															$id = $sub['SECTION_ID'];
														}
														$url = '/catalog/';
														$url .= $sub['IBLOCK_ID'].'/'.$id.'/';
														?>
														<li>
															<a class="catalog__item" href="<?=$url?>"><?=$sub['NAME']?></a>
														</li>
													<?}?>
												</ul>
											<?endif;?>
										</li>
									<? endforeach; ?>
								</ul>
							<? } ?>
						</li>
					<?}?>
				</ul>
			</div>
		</div>
	</div>
</div>


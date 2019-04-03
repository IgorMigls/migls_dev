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
?>
<div class="b-nav__item b-nav__item_subitems b-ib">
	<a href="javascript:" class="b-nav__link">Каталог магазинов</a>
	<div class="b-header-popup b-ib-wrapper b-header-popup_menu">
		<div class="b-header-popup__wrapper b-header-popup__wrapper_reset">
			<div class="b-header-popup__left b-ib">
				<div class="b-menu__left b-ib">
					<ul class="ulmart__menu">
						<?foreach ($arResult as $arSection):?>
							<li class="menu__item">
								<a href="<?=$arSection['MAIN_URL']?>" class="menu__link"><?=$arSection['MAIN_NAME']?></a>

								<?if(count($arSection['ITEMS'])):
									$sectionStyle = '';
									if(strlen($arSection['PICTURE']['src']) > 0){
										$sectionStyle = 'background:url('.$arSection['PICTURE']['src'].') #fff no-repeat bottom right;';
									} ?>
									<ul class="menu__sub bg__class" style="<?=$sectionStyle?>">
										<?foreach ($arSection['ITEMS'] as $subSection){?>
											<li class="sub__menu__item">
												<a href="<?=$subSection['URL_LIST']?>" class="sub__menu__link"><?=$subSection['NAME']?></a>
											</li>
										<?}?>
									</ul>
								<?endif;?>

							</li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
			<?/*<div class="b-header-popup__right b-ib">
				<?$APPLICATION->IncludeComponent(
					'ul:shop.all.list',
					'top_2',
					array(),
					false
				)?>
			</div>*/?>
		</div>
	</div>
</div>

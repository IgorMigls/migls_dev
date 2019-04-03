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
//$this->setFrameMode(true);
?>
<br />
<ul>
	<?foreach ($arResult['SECTIONS'] as $k => $arSection):?>
		<li>
			<a href="#"><?=$arSection['NAME']?></a>
			<?if(count($arSection['ITEMS']) > 0):?>
				<ul>
				<?foreach ($arSection['ITEMS'] as $n => $arItem):?>
					<li><a href="/docs/<?=$arItem['CODE']?>/"><?=$arItem['NAME']?></a></li>
				<?endforeach;?>
				</ul>
			<?endif;?>
		</li><br />
	<?endforeach;?>
</ul>

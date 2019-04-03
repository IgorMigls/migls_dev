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

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	$this->addExternalCss($templateData['TEMPLATE_THEME']);
}
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
//dump($arResult);
?>
<div class="b-sidebar__filter">
	<div class="b-sidebar__filter__head b-wave">
		<div class="filter__head__left b-ib">Фильтр</div>
		<div class="filter__head__right b-ib">
			<a type="button" href="<?=$request->getRequestedPageDirectory().'/'?>"
			        onclick="smartFilter.resetAll(this)"
			        class="b-button reset__filetr js_resetFilter">
				сбросить
			</a>
		</div>
	</div>

	<!--<div class="b-sidebar__filter__content b-wave">
		<div class="filter__head__left b-ib"> <span class="filter__head__par">Объем, л</span></div>
		<div class="filter__head__right b-ib">
			<button type="button" onclick="resetSlider()" class="b-button reset__filetr">сбросить</button>
		</div>
		<div class="filter__content">
			<div class="filter__values"><span class="slider__values first__value">1</span>
				<input type="text" id="amount" class="fluide__value slider__values">
			</div>
			<div id="slider-range"></div>
			<div class="filter__values"><span class="slider__values">1</span><span class="slider__values second__value">50</span><span class="slider__values third__value">100</span></div>
		</div>
	</div>-->
	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">

		<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
		<?endforeach;?>
		<?foreach($arResult["ITEMS"] as $key => $arItem){
			if(count($arItem["VALUES"]) == 0)
				continue;
			?>
			<?$arCur = current($arItem["VALUES"]);?>
			<div class="b-sidebar__filter__content b-wave">
				<div class="filter__head__left b-ib"> <span class="filter__head__par"><?=$arItem['NAME']?></span></div>
				<div class="filter__head__right b-ib">
					<button type="button" class="b-button reset__filetr js_resetFilter" onclick="smartFilter.resetItems(this)">сбросить</button>
				</div>
				<div class="filter__content">
					<?switch ($arItem["CODE"]) {
						case 'richness':
						case 'scope':
//							dump($arItem);

							$tempVals = $arItem['VALUES'];
							unset($arItem['VALUES']);
							foreach ($tempVals as $c => $val){
								$code = str_replace(',', '.', $c);
								$arItem['VALUES'][$code] = $val;
							}

							ksort($arItem['VALUES']);
							?>
						<label class="filter__label slider_items__filter">
							<?foreach($arItem["VALUES"] as $val => $ar):?>
								<input type="hidden" value="<?=($ar["CHECKED"] ? 'Y' : '')?>" name="<? echo $ar["CONTROL_NAME"] ?>"
									class="slider_selected_<?=$arItem["CODE"]?> slider_selected_<?=$val?>" id="<?=$ar["CONTROL_ID"]?>">
							<?endforeach;?>
							<input type="hidden" id="range_<?=$arItem["CODE"]?>" class="slider_item__hidden" value="" />
						</label>
							<script>
								$('#range_<?=$arItem["CODE"]?>').ionRangeSlider({
									type: "double",
									values: <?=CUtil::PhpToJSObject(array_keys($arItem['VALUES']))?>,
									onFinish: function (data) {
//										console.info(data);

										var $items = $('.slider_selected_<?=$arItem["CODE"]?>');
										$items.val('');

										var $current = $items.eq(data.from);
										for (var index = data.from; index <= data.to; index++){
											$items.eq(index).val('Y');
										}

										smartFilter.click($current[0]);
									},
									onStart: function (data) {
//										console.info(window.location.search.split('&'));
									},
									grid: true,
									grid_num: 4
								});


								var selectedIndexForm = 0, selectedIndexTo = 0;
								var lengthItems = $('.slider_selected_<?=$arItem["CODE"]?>').length;
								selectedIndexTo = lengthItems;
								for(var i = 0; i <= lengthItems; i++){
									if($('.slider_selected_<?=$arItem["CODE"]?>').eq(i).val() == 'Y'){
										selectedIndexForm = i;
										break;
									}
								}

								for(var k = lengthItems; k >= 0; k--){
									if($('.slider_selected_<?=$arItem["CODE"]?>').eq(k).val() == 'Y'){
										selectedIndexTo = k;
										break;
									}
								}

								var slider = $('#range_<?=$arItem["CODE"]?>').data("ionRangeSlider");
								slider.update({
									from: selectedIndexForm,
									to: selectedIndexTo
								});

							</script>
							<?break;
						default:?>
						<div <?if(count($arItem["VALUES"]) > 6):?> class="b-custom-scroll js-custom-scroll"<?endif;?>>
							<?foreach($arItem["VALUES"] as $val => $ar):?>
								<label class="filter__label" onclick="smartFilter.click(document.getElementById('<? echo $ar["CONTROL_ID"] ?>'))">
									<input type="checkbox"
									       class="filter__checkbox"
									       value="<? echo $ar["HTML_VALUE"] ?>"
									       name="<? echo $ar["CONTROL_NAME"] ?>"
									       id="<? echo $ar["CONTROL_ID"] ?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
									>
									<i></i><?=$ar["VALUE"];?>
								</label>
							<?endforeach;?>
						</div>
							<?break;
					}?>
				</div>
			</div>
			<div class="filter__result__products b-ib-wrapper bx-filter-popup-result">
				<div class="relult__products b-ib">
					Выбрано: <span class="products__cont"><?=intval($arResult["ELEMENT_COUNT"])?></span> продуктов
				</div>
				<div class="relult__products__submit b-ib">
					<button data-url=""
					        type="button"
					        class="b-button b-button_green"
					        id="filter_res_btn"
					        onclick="smartFilter.showResultProduct(this)">
						Показать
					</button>
				</div>
			</div>
			<?/*<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
				<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
				<span class="arrow"></span>
				<br/>
				<a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
			</div>*/?>
		<?}?>
	</form>
	<script type="text/javascript">
		var smartFilter = new JCSmartFilter(
			'<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>',
			'<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>',
			<?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>
		);
	</script>

	<!--<div id="js_filter1" class="b-sidebar__filter__content b-wave">
		<div class="filter__head__left b-ib"> <span class="filter__head__par">Бренды</span></div>
		<div class="filter__head__right b-ib">
			<button type="button" class="b-button reset__filetr js_resetFilter">сбросить</button>
		</div>
		<div class="filter__content">
			<div class="b-custom-scroll js-custom-scroll">
				<form action="">
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Натурально и вкусно
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Полезные продукты
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
					<label class="filter__label">
						<input type="checkbox" class="filter__checkbox"><i></i>Lacticia
					</label>
				</form>
			</div>
		</div>
		<div class="filter__result__products b-ib-wrapper">
			<div class="relult__products b-ib">Выбрано<span class="products__cont">12</span>продуктов</div>
			<div class="relult__products__submit b-ib">
				<button type="submit" class="b-button b-button_green">Показать</button>
			</div>
		</div>
	</div>
	<div class="b-sidebar__filter__content b-wave">
		<div class="filter__head__left b-ib"> <span class="filter__head__par">Сырье</span></div>
		<div class="filter__head__right b-ib">
			<button type="button" class="b-button reset__filetr js_resetFilter">сбросить</button>
		</div>
		<div class="filter__content">
			<form action="">
				<label class="filter__label">
					<input type="checkbox" class="filter__checkbox"><i></i>Козье
				</label>
				<label class="filter__label">
					<input type="checkbox" class="filter__checkbox"><i></i>Коровье
				</label>
			</form>
		</div>
	</div>
	<div class="b-sidebar__filter__content b-wave">
		<div class="filter__head__left b-ib"> <span class="filter__head__par">Способ</span></div>
		<div class="filter__head__right b-ib">
			<button type="button" class="b-button reset__filetr js_resetFilter">сбросить</button>
		</div><span class="filter__head__par_bottom">тепловой обработнки</span>
		<div class="filter__content">
			<form action="">
				<label class="filter__label">
					<input type="checkbox" class="filter__checkbox"><i></i>Стерилизованное
				</label>
				<label class="filter__label">
					<input type="checkbox" class="filter__checkbox"><i></i>Ультростерилизовнанное
				</label>
			</form>
		</div>
	</div>
	<div class="b-sidebar__filter__content b-wave">
		<div class="filter__head__left b-ib"> <span class="filter__head__par">Страна</span></div>
		<div class="filter__head__right b-ib">
			<button type="button" class="b-button reset__filetr js_resetFilter">сбросить</button>
		</div>
		<div class="filter__content">
			<form action="">
				<label class="filter__label">
					<input type="checkbox" class="filter__checkbox"><i></i>Россия
				</label>
			</form>
		</div>
	</div>-->
</div>

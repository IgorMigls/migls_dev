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
$this->addExternalCss($templateFolder.'/index.css');
$this->addExternalJs($templateFolder.'/index.js');

$INPUT_ID = trim($arParams["INPUT_ID"]);
if (strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);
?>
<div id="top_search" data-shop="<?=$arParams['SHOP_CURRENT']['ID']?>">
	<form class="b-header-search__center b-ib" @submit.prevent="submitSearch">
		<el-autocomplete class="b-header-search__center b-ib" v-model="query" :fetch-suggestions="querySearch"
			placeholder="Например, говядина для шашлыка, 3 кг" :trigger-on-focus="false">

			<template slot="append">
				<button class="b-button b-button_green" name="s" type="submit">Найти</button>
			</template>

		</el-autocomplete>
	</form>
</div>

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
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
/** @var \Bitrix\Main\HttpRequest $request */
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$INPUT_ID = trim($arParams["INPUT_ID"]);
if (strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);
$this->setFrameMode(true);

$uri = new \Bitrix\Main\Web\Uri($request->getRequestUri());
$actionUri = new \Bitrix\Main\Web\Uri('/search/');
?>
<span search-title=""
     page="<? echo CUtil::JSEscape(POST_FORM_ACTION_URI) ?>"
     container="<? echo $arParams["~CONTAINER_ID"] ?>"
     inputid="<? echo $INPUT_ID ?>">

	<form action="<?=$actionUri->getUri()?>" class="b-header-search__center b-ib">
		<input id="<? echo $INPUT_ID ?>"
		       type="text" name="q"
		       value="<?= $request->get('q') ?>"
		       size="40"
		       placeholder="Например, говядина для шашлыка, 3 кг"
		       class="b-header-search__input" autocomplete="off"/>
		<?if(intval($arParams['SHOP_CURRENT']['ID']) > 0):?>\
			<input type="hidden" name="shop" value="<?=$arParams['SHOP_CURRENT']['ID']?>" />
		<?endif;?>
	</form>
	<div class="b-header-search__right b-ib">
		<button class="b-button b-button_green" name="s" type="submit">Найти</button>
	</div>
</span>

<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

\Bitrix\Main\Loader::includeModule('ab.iblock');
\Bitrix\Main\Loader::includeModule('iblock');

use Bitrix\Iblock\IblockTable;
use AB\Iblock\Element;

PR($_SESSION['REGIONS']['SHOP_ID']);

$arIblocks = [];
$oIblocks = IblockTable::getList([
	'select' => ['ID'],
	'filter' => ['IBLOCK_TYPE_ID'=>'remains']
]);
while ($block = $oIblocks->fetch()){
	$arIblocks[] = $block['ID'];
}
PR($arIblocks);

foreach ($arIblocks as $arIblock) {
	$q = Element::query();
	$q->setFilter(array(
		'IBLOCK_ID'=>$arIblock,
		'!=PROPERTY.CML2_LINK.ID' => false,
		'=PROPERTY.SHOP_ID.ID' => $_SESSION['REGIONS']['SHOP_ID']
	));
	$q->setSelect(array('ID'));


	PR($q->getQuery());
}

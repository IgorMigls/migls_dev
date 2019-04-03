<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use AB\Tools\Console\ProgressBar;
use UL\Main\Search\TitleIndexTable;

includeModules(['ul.main','catalog', 'iblock', 'ab.tools']);

$iblocks = \UL\Main\CatalogHelper::getCatalogIblocks();
foreach ($iblocks as $value) {
	$oList = \Bitrix\Iblock\ElementTable::getList([
		'filter' => ['=IBLOCK_ID' => $value['ID']],
		'select' => [
			'ID','NAME','IBLOCK_ID'
		]
	]);
	while ($item = $oList->fetch()){
		$save = [
			'ITEM_ID' => $item['ID'],
			'IBLOCK_ID' => $value['ID'],
			'TEXT' => strtoupper($item['NAME'])
		];

		$row = TitleIndexTable::getRow([
			'select'=> ['ID'],
			'filter' => ['=ITEM_ID' => $item['ID'], '=IBLOCK_ID' => $value['ID']]
		]);
		if(!is_null($row)){
			TitleIndexTable::update($row['ID'], $save);
		} else {
			TitleIndexTable::add($save);
		}
	}
}
ProgressBar::showGood('FINITO');
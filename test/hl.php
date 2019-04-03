<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Main\Entity;

includeModules('highloadblock');

$arBlock = HL::getRowById(2);
$entity = HL::compileEntity($arBlock);
$q = new Entity\Query($entity);
$q->setSelect(['*'])
	->setFilter(['UF_TEST1'=>'asdasd', 'UF_TEST2'=>'123123']);

PR($q->getQuery());
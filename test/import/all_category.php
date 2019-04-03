<?php
$_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__).'/../..';
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require_once($DOCUMENT_ROOT.'/bitrix/modules/main/include/prolog_before.php');

use PW\Tools\Debug;
use UL\Main\Import;

includeModules(['ul.main','iblock']);

$iblock = 4;

Debug::startMemory();
Debug::startTime();

$AllCategory = new Import\AllCategory($iblock, '/upload/base_ahan/Продукты питания');
$AllCategory->run();

//PR($AllCategory->recursiveDirectoryIterator());

Debug::getMemory();
Debug::getTime();
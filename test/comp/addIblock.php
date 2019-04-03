<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main;
use Bitrix\Main\Loader;
use AB\FormIblock\Helper;

Loader::includeModule('iblock');
Loader::includeModule('ab.form_iblock');

$fields = Helper::getIbFields();

PR($fields);
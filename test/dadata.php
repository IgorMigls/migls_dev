<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
use Bitrix\Main\Web;

$Suggestions = new UL\Suggestions();
$Suggestions->setType('address');

$param = ['query'=>'г Самара, ул. пр', 'count' => 20, 'only'=>'street'];

$res = $Suggestions->send($param);

PR($res);
<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$RestManager = new AB\Tools\Rest\Manager();
$RestManager->parseUrl();
echo $RestManager->init()->getResult();
exit;
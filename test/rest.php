<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main;

//Main\Loader::includeModule('')

$server = Main\Context::getCurrent()->getServer();
dump($server);

$url = $server->get('REQUEST_SCHEME').'://'.$server->get('SERVER_NAME').'/api/v1';
$client = new \Sarasvati\Test\Api\Client($url);

$client->auth();
<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION;
$APPLICATION->RestartBuffer();
use Bitrix\Main;
use Symfony\Component\HttpFoundation\Request;
use AB\Tools\HttpFoundation;

Main\Loader::includeModule('ab.tools');

$request = Request::createFromGlobals();
$request->headers->set('accept', 'application/json');
$request->headers->set('content-type', 'application/json');

$router = new HttpFoundation\RequestController(['baseUrl' => '/rest2']);

$router->map('/basket/{method}', 'Mig\BasketComponent', [
	'_component' => 'mig:basket'
]);
$router->map('/public/order/{method}', 'Mig\Order\CreatorComponent', [
	'_component' => 'mig:order.creator'
]);

$router->map('/component_creator/{method}', 'DigitalWand\AdminHelper\ComponentCreator', [
	'_component' => 'admin:create.component'
]);
$router->map('/da/order/{method}', 'Mig\DeliveryAdmin\MainComponent', [
	'_component' => 'mig:delivery.admin'
]);
$router->map('/public/address/{method}', 'Mig\Address\WindowComponent', [
	'_component' => 'mig:address.window'
]);
$router->map('/public/searching/{method}', 'Mig\Search\SearchTitle', [
	'_component' => 'mig:address.window'
]);
$router->map('/public/personal/{method}', 'Mig\PersonalComponent', [
	'_component' => 'mig:personal.main'
]);

$router->map('/delivery/v1/{method}', 'Mig\Mobile\DeliveryComponent', [
	'_component' => 'mig:mobile.delivery',
	'_auth' => true
]);

$response = $router->handle($request);

foreach ($response->headers as $name => $header) {
	header($name.':'.implode(';', $header));
}

echo $response->getContent();

//dump(Main\Web\Json::decode($response->getContent()));

exit;

<?php
use UL\Ajax\Routes;

$Routes = Routes::getInstance();

$Routes
	->addRout([
		'CLASS'=>'DetailProduct',
		'NAMESPACE'=>'UL\Products',
		'COMPONENT'=>'ul:product.detail',
		'PARAMS'=>'params',
		'ACTIONS'=>[
			'getProduct',
		]
	])
	->addRout([
		'CLASS'=>'AllList',
		'NAMESPACE'=>'UL\Shops',
		'COMPONENT'=>'ul:shop.all.list',
		'PARAMS'=>'params',
		'ACTIONS'=>[
			'getList',
		]
	]);

<?php
use AB\Tools\Rest\Router;

Router::instance()
	->addRoute(['class' => 'UL\Main\Personal\Profile'], '/rest/profile')
	->addRoute(['class' => '\UL\Products\ProductMain', 'component' => 'ul:products'], '/rest/prof')
	->addRoute(['class' => '\UL\Main\Help\HelpComponent', 'component' => 'ul:help'], '/rest/help');

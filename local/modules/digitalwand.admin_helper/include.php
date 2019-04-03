<?php
spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3) {
		return;
	}
	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]) . '.php'
	));
	$filePath = str_replace('DigitalWand/AdminHelper/','',$filePath);
	$filePath = preg_replace('#DigitalWand\/AdminHelper\/#i','',$filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)) {
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

$pathToVendor = '/local/modules/digitalwand.admin_helper/asset/vendor';
$jsLibs = [
	'bootstrap' => [
		'css' => [
			'/local/templates/esd_main/css/bootstrap.min00.css',
			'/local/src/css/admin_bootstrap_debug.css',
		]
	],
	'admin_helper' => [
		'js' => [
			'/local/src/js/shim/es6-shim.min.js',
			'/local/src/js/shim/es6-sham.min.js',
			'/local/src/js/react/react-with-addons.min.js',
			'/local/src/js/react/react-dom.min.js',
			'/local/src/js/is.min.js',
			'/local/src/js/sweet_alert/sweetalert.min.js',
			'/local/modules/digitalwand.admin_helper/asset/builds/ComponentCreate.js'
		],
		'css' => [
			'/local/src/js/sweet_alert/sweetalert.css',
			'/local/src/css/animate.min.css',
			'/local/src/css/preloaders.css',
			'/local/templates/esd_main/css/font-awesome.min.css',
			'/local/modules/digitalwand.admin_helper/asset/css/digitalwand.admin_helper.css',
		],
	],
	'admin_widgets' => [
		'js' => ['/local/modules/digitalwand.admin_helper/asset/js/widgets.js']
	],
	'vue' => [
//		'js' => [$pathToVendor.(AB_DEBUG == true ? '/vue.js' : '/vue.min.js')]
		'js' => [(AB_DEBUG === true ? 'https://unpkg.com/vue/dist/vue.js' : 'https://unpkg.com/vue/dist/vue.min.js')]
	],
	'vue_router' => [
		'js' => [$pathToVendor.(defined('DEV_MODE') ? '/vue-router.js' : '/vue-router.min.js')]
	],
	'vuex' => ['js' => 'https://unpkg.com/vuex@3.0.0/dist/'.(defined('DEV_MODE') ? 'vuex.js' : 'vuex.min.js')],
	'vue_resource' => ['js' => $pathToVendor.'/vue-resource1.3.4.min.js'],
	'lodash' => ['js' => $pathToVendor.(defined('DEV_MODE') ? '/lodash.js' : '/lodash.min.js')],
	'element_ui' => [
		'js' => [$pathToVendor.'/element_ui/index.js'],
		'css' => [$pathToVendor.'/element_ui/index.css']
	],
];
foreach ($jsLibs as $name => $lib) {
	CJSCore::RegisterExt($name, $lib);
}
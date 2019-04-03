<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 07.09.2016
 * Time: 16:33
 */

namespace UL\Main\Admins\Import;

use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget;

class PriceAdminInterface extends AdminInterface
{
	/**
	 * @method fields
	 * @return mixed
	 */
	public function fields()
	{
		return array(
			'FOLDERS'=> [
				'NAME' => 'Остатки',
				'FIELDS' => [
					'CONDITION' => array(
						'WIDGET' => new Widget\AreaWidget(array(
							'BX_LIBS' => ['jquery'],
//							'FILE'=>'/local/modules/ul.main/tools/admin/price_import.php',
							'HTML' => '<div id="remain_file_import"></div>',
							'css' => [
								'/local/modules/ab.tools/asset/css/sweetalert.css',
								'/local/modules/ab.tools/asset/css/preloaders.css',
								'/local/modules/ul.main/asset/css/ul.main.import.remain.css'
							],
							'js' => [
								'/local/modules/ab.tools/asset/js/shim/es6-shim.min.js',
								'/local/modules/ab.tools/asset/js/shim/es6-sham.min.js',
								'/local/modules/ab.tools/asset/js/sweetalert.min.js',
								'/local/modules/ab.tools/asset/js/is.min.js',
								'/local/modules/ab.tools/asset/js/react/react-with-addons.min.js',
								'/local/modules/ab.tools/asset/js/react/react-dom.min.js',
								'/local/modules/ul.main/asset/js/ul.main.import.remain.js'
							]
						)),
						'READONLY' => false,
						'FILTER' => false,
						'NO_SAVE' => true,
						'HEADER' => false
					),
				]
			]
		);
	}

	/**
	 * @method helpers
	 * @return mixed
	 */
	public function helpers()
	{
		return [
			'UL\Main\Admins\Import\PriceEditHelper'
		];
	}

}
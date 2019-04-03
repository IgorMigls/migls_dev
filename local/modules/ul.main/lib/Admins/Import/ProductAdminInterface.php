<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 07.09.2016
 * Time: 13:19
 */

namespace UL\Main\Admins\Import;

use DigitalWand\AdminHelper\Widget;
use DigitalWand\AdminHelper\Helper\AdminInterface;

class ProductAdminInterface extends AdminInterface
{
	/**
	 * @method fields
	 * @return mixed
	 */
	public function fields()
	{
		return array(
			'FOLDERS'=> [
				'NAME' => 'Имопрт товаров',
				'FIELDS' => [
					'CONDITION' => array(
						'WIDGET' => new Widget\AreaWidget(array('FILE'=>'/local/modules/ul.main/tools/admin/import_product.php')),
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
		return array(
			'UL\Main\Admins\Import\ProductEditHelper'
		);
	}

}
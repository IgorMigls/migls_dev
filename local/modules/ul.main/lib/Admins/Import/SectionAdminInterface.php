<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 06.09.2016
 * Time: 17:40
 */

namespace UL\Main\Admins\Import;

use DigitalWand\AdminHelper\Widget;
use DigitalWand\AdminHelper\Helper\AdminInterface;

class SectionAdminInterface extends AdminInterface
{

	/**
	 * @method fields
	 * @return mixed
	 */
	public function fields()
	{
		return array(
			'SECTION' => [
				'NAME' => 'Импорт категорий',
				'FIELDS' => [
					'SECTIONS' => [
//						'TITLE' => 'Сопоставление свойств товара и колонок файла импорта',
						'WIDGET' => new Widget\AreaWidget(array('FILE'=>'/local/modules/ul.main/tools/admin/import_sections.php')),
						'READONLY' => false,
						'FILTER' => false,
						'NO_SAVE' => true,
						'HEADER' => false
					]
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
			'UL\Main\Admins\Import\SectionEditHelper'
		);
	}
}
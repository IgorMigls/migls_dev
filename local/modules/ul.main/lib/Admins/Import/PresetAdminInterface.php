<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 05.09.2016
 * Time: 14:13
 */

namespace UL\Main\Admins\Import;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget;
use UL\Main\Import\Model\PresetTable;

Loc::loadMessages(__FILE__);

//PresetTable::dropTable();
//PresetTable::createTable();

Loader::includeModule('iblock');

class PresetAdminInterface extends AdminInterface
{
	/**
	 * @method fields
	 * @return mixed
	 */
	public function fields()
	{
		return [
			'FOLDERS'=> [
				'NAME' => 'Свойства',
				'FIELDS' => [
					'CONDITION' => array(
						'TITLE' => 'Сопоставление свойств товара и колонок файла импорта',
						'WIDGET' => new Widget\AreaWidget(array('FILE'=>'/local/modules/ul.main/tools/admin/compare_fields.php')),
						'READONLY' => false,
						'FILTER' => false,
						'NO_SAVE' => true,
						'HEADER' => false
					),
				]
			]
		];
	}

	/**
	 * @method helpers
	 * @return array
	 */
	public function helpers()
	{
		return array(
			'\UL\Main\Admins\Import\PresetEditHelper'
		);
	}
}
<?php

namespace UL\Main\Admins;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget;

Loc::loadMessages(__FILE__);


class MapAdminInterface extends AdminInterface
{
	public function fields()
	{
		global $APPLICATION;
		$APPLICATION->SetTitle('Рисовалка областей');

		return array(
			'MAIN'=>[
				'NAME'=>'Карта',
				'FIELDS'=>array(
					'ID' => array(
						'WIDGET' => new Widget\NumberWidget(),
						'READONLY' => true,
						'FILTER' => true,
						'HIDE_WHEN_CREATE' => true
					),
					'TITLE_REGION' => array(
						'WIDGET' => new Widget\StringWidget(),
						'READONLY' => false,
						'FILTER' => true,
					),
					'SHOP_ID' => array(
						'WIDGET' => new Widget\MultiShopWidget(['IBLOCK_ID'=>5, 'WINDOW_WIDTH'=>1024, 'WINDOW_HEIGHT'=>600]),
						'READONLY' => false,
						'FILTER' => true,
						'MULTIPLE' => 'Y'
					),
					/*'SHOP_ID' => array(
						'WIDGET' =>  new Widget\IblockElementWidget(['IBLOCK_ID'=>5, 'WINDOW_WIDTH'=>1024, 'WINDOW_HEIGHT'=>600]),
						'READONLY' => false,
						'FILTER' => true,
					),*/
					'CITY_ID' => array(
						'WIDGET' => new Widget\SectionWidget(),
						'READONLY' => false,
						'FILTER' => true,
					),
					'CORDS' => array(
						'WIDGET' => new Widget\AreaWidget(['FILE'=>'/local/modules/ul.main/tools/admin/map.php']),
						'FILTER' => false,
						'TITLE' => 'Координаты'
					)
				)
			]
		);
	}

	public function helpers()
	{
		return array(
			'\UL\Main\Admins\MapListHelper',
			'\UL\Main\Admins\MapEditHelper'
		);
	}


}
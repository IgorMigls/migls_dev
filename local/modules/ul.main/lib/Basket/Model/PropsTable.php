<?php namespace UL\Main\Basket\Model;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class PropsTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 'b_sale_basket_props';
	}

	public static function getMap()
	{
		$map = array(
			'ID' => new Entity\IntegerField('ID', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_ID_FIELD'),
					'primary' => true,
					'autocomplete' => true,
				)
			),
			'BASKET_ID' => new Entity\IntegerField('BASKET_ID', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_BASKET_ID_FIELD'),
					'required' => true,
				)
			),
			'NAME' => new Entity\StringField('NAME', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_NAME_FIELD'),
					'required' => true,
				)
			),
			'VALUE' => new Entity\StringField('VALUE', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_VALUE_FIELD'),
				)
			),
			'CODE' => new Entity\StringField('CODE', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_CODE_FIELD'),
				)
			),
			'SORT' => new Entity\IntegerField('SORT', array(
					'title' => Loc::getMessage('B_SALE_BASKET_PROPS_ENTITY_SORT_FIELD'),
					'required' => true,
				)
			),
		);

		return $map;
	}

    
	public static function validateName ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateValue ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateCode ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

}
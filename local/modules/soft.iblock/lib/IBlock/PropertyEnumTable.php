<?php namespace Soft\IBlock;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

class PropertyEnumTable extends Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_property_enum';
	}

	/**
	 * Returns entity map definition.
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_ID_FIELD'),
			)),
			new Entity\IntegerField('PROPERTY_ID', array(
				'required' => true,
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_PROPERTY_ID_FIELD'),
			)),
			new Entity\StringField('VALUE', array(
				'required' => true,
				'validation' => array(__CLASS__, 'validateValue'),
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_VALUE_FIELD'),
			)),
			new Entity\BooleanField('DEF', array(
				'values' => array('N', 'Y'),
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_DEF_FIELD'),
			)),
			new Entity\IntegerField('SORT', array(
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_SORT_FIELD'),
			)),
			new Entity\StringField('XML_ID', array(
				'required' => true,
				'validation' => array(__CLASS__, 'validateXmlId'),
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_XML_ID_FIELD'),
			)),
			new Entity\StringField('TMP_ID', array(
				'validation' => array(__CLASS__, 'validateTmpId'),
				'title' => Loc::getMessage('PROPERTY_ENUM_ENTITY_TMP_ID_FIELD'),
			)),
			new Entity\ReferenceField(
				'PROPERTY',
				'\Bitrix\Iblock\Property',
				array('=this.PROPERTY_ID' => 'ref.ID')
			)
		);
	}

	/**
	 * Returns validators for VALUE field.
	 *
	 * @return array
	 */
	public static function validateValue()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for XML_ID field.
	 *
	 * @return array
	 */
	public static function validateXmlId()
	{
		return array(
			new Entity\Validator\Length(null, 200),
		);
	}
	/**
	 * Returns validators for TMP_ID field.
	 *
	 * @return array
	 */
	public static function validateTmpId()
	{
		return array(
			new Entity\Validator\Length(null, 40),
		);
	}
}
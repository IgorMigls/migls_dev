<?php namespace Soft\IBlock;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class MultiPropTable extends Entity\DataManager
{
	/**
	 * @method getMap
	 * @param bool $isList
	 * @return array
	 */
	public static function getMap($isList = false)
	{
		$maps = array(
			new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_ID_FIELD'),
			)),
			new Entity\IntegerField('IBLOCK_ELEMENT_ID', array(
				'required' => true,
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_IBLOCK_ELEMENT_ID_FIELD'),
			)),
			new Entity\IntegerField('IBLOCK_PROPERTY_ID', array(
				'required' => true,
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_IBLOCK_PROPERTY_ID_FIELD'),
			)),
			new Entity\TextField('VALUE', array(
				'required' => true,
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_VALUE_FIELD'),
			)),
			new Entity\IntegerField('VALUE_ENUM', array(
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_VALUE_ENUM_FIELD'),
			)),
			new Entity\FloatField('VALUE_NUM', array(
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_VALUE_NUM_FIELD'),

			)),
			new Entity\StringField('DESCRIPTION', array(
				'validation' => array(__CLASS__, 'validateDescription'),
				'title' => Loc::getMessage('ELEMENT_PROP_M2_ENTITY_DESCRIPTION_FIELD'),
			)),
		);
		if($isList){
			$maps[] = new Entity\ReferenceField(
				'VALUE_LIST',
				'\Soft\IBlock\PropertyEnumTable',
				array(
					'=this.IBLOCK_PROPERTY_ID'=>'ref.PROPERTY_ID',
					'=this.VALUE_ENUM'=>'ref.ID'
				)
			);
		}
		return $maps;
	}
	/**
	 * Returns validators for DESCRIPTION field.
	 * @return array
	 */
	public static function validateDescription()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}
}
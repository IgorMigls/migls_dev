<?php namespace Soft;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

/**
 * Class PropertyUtmTable
 * @package Alvitek\Blocks
 */
class PropertyUtmTable extends Entity\DataManager
{
	protected static $tableName = 'b_iblock_element_prop_m';
	protected static $iblockId = 0;

	/**
	 * PropertyUtmTable constructor.
	 *
	 * @param int $iblockId
	 * @throws IBlockException
	 */
	public function __construct($iblockId = 0)
	{
		if(intval($iblockId) == 0)
			throw new IBlockException('IBLOCK_ID is null');

		self::$iblockId = $iblockId;
	}

	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_m'.self::$iblockId;
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
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

		return $maps;
	}

	/**
	 * @method isUtm
	 * @return bool
	 */
	public static function isUtm()
	{
		return true;
	}

	/**
	 * @method getEntity
	 * @param null $iblockId
	 *
	 * @return Entity\Base
	 * @throws IBlockException
	 */
	public static function getEntity($iblockId = null)
	{
		if(intval($iblockId) > 0)
			self::$iblockId = $iblockId;

		if(intval(self::$iblockId) == 0)
			throw new IBlockException('IBLOCK_ID is null');

		return parent::getEntity();
	}

	/**
	 * @method getIblockId - get param iblockId
	 * @return int
	 */
	public static function getIblockId()
	{
		return self::$iblockId;
	}
}
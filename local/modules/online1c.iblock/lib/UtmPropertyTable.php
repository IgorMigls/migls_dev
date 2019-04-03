<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Main\Entity;

class UtmPropertyTable
{
	/** @var string  */
	protected static $tableName = 'b_iblock_element_prop_m';

	/** @var int  */
	protected $iblockId = 0;

	/** @var  Entity\Base */
	protected $utmEntity;

	protected $propertyId = null;

	public function __construct($iblockId = 0, $propId = null)
	{
		if (intval($iblockId) == 0)
			throw new IblockException('IBLOCK_ID for utm is null');

		$this->iblockId = $iblockId;
		$this->propertyId = $propId;
	}

	/**
	 * @method getTableName
	 * @return string
	 */
	public function getTableName()
	{
		return 'b_iblock_element_prop_m'.$this->iblockId;
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		$maps = array(
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'IBLOCK_ELEMENT_ID' => new Entity\IntegerField('IBLOCK_ELEMENT_ID', array(
				'required' => true,
			)),
			'IBLOCK_PROPERTY_ID' => new Entity\IntegerField('IBLOCK_PROPERTY_ID', array(
				'required' => true,
			)),
			'VALUE' => new Entity\TextField('VALUE', array(
				'required' => true,
			)),
			'VALUE_ENUM' => new Entity\IntegerField('VALUE_ENUM'),
			'VALUE_NUM' => new Entity\FloatField('VALUE_NUM'),
			'DESCRIPTION' => new Entity\StringField('DESCRIPTION', array(
				'validation' => array(__CLASS__, 'validateDescription'),
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
	 * @method compile
	 * @return $this
	 */
	public function compile()
	{
		$name = 'PropertyMulti'.$this->iblockId.'_'.$this->propertyId;
		$fullName = '\\'.__NAMESPACE__.'\\'.$name;
		if(Entity\Base::isExists($fullName)){
			$this->utmEntity = Entity\Base::getInstance($fullName);
		} else {
			$this->utmEntity = Entity\Base::compileEntity($name, static::getMap(), [
				'namespace' => __NAMESPACE__,
				'table_name' => $this->getTableName()
			]);
		}

		return $this;
	}

	/**
	 * @method getUtmEntity - get param utmEntity
	 * @return Entity\Base
	 */
	public function getUtmEntity()
	{
		return $this->utmEntity;
	}

}
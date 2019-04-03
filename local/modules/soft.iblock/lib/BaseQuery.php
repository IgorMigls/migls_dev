<?php namespace Soft;

use Bitrix\Main\Entity;

/**
 * Class BaseQuery
 * @package Alvitek\Blocks
 */
class BaseQuery
{
	/** @var  Entity\Query */
	protected static $query;
	protected static $propertyFields;
	private static $instance = null;
	private static $propertyEnums = [];
	private static $propertyEnumsVal = [];

	/**
	 * @method instance
	 * @return BaseQuery|null
	 */
	public static function instance()
	{
		if(is_null(self::$instance)){
			self::$instance = new BaseQuery();
		}

		return self::$instance;
	}

	/**
	 * @method getQuery
	 * @return Entity\Query
	 */
	public static function getQuery()
	{
		return self::$query;
	}

	/**
	 * @method setQuery - set param Query
	 * @param Entity\Query $query
	 */
	public static function setQuery(Entity\Query $query)
	{
		self::$query = $query;
	}

	/**
	 * @method getPropertyFields - get param propertyFields
	 * @return mixed
	 */
	public static function getPropertyFields()
	{
		return self::$propertyFields;
	}

	/**
	 * @method setPropertyFields - set param PropertyFields
	 * @param mixed $propertyFields
	 */
	public static function setPropertyFields($propertyFields)
	{
		self::$propertyFields = $propertyFields;
	}

	public static function addPropertyFields($k, $v)
	{
		self::$propertyFields[$k] = $v;
	}

	public static function getPropField($key)
	{
		return self::$propertyFields[$key];
	}

	/**
	 * @method getPropertyEnums - get param propertyEnums
	 * @return array
	 */
	public static function getPropertyEnums()
	{
		return self::$propertyEnums;
	}

	/**
	 * @method setPropertyEnums - set param PropertyEnums
	 * @param array $propertyEnums
	 */
	public static function setPropertyEnums($propertyEnums)
	{
		self::$propertyEnums = $propertyEnums;
	}

	public static function addEnumProperty($arEnums = [])
	{
		if(count($arEnums) > 0)
			self::$propertyEnums = array_merge(self::$propertyEnums, $arEnums);

		self::$propertyEnums  = array_unique(self::$propertyEnums);
	}

	/**
	 * @method getPropertyEnumsVal - get param propertyEnumsVal
	 * @return array
	 */
	public static function getPropertyEnumsVal()
	{
		return self::$propertyEnumsVal;
	}

	/**
	 * @method setPropertyEnumsVal - set param PropertyEnumsVal
	 * @param array $propertyEnumsVal
	 */
	public static function setPropertyEnumsVal($propertyEnumsVal)
	{
		self::$propertyEnumsVal = $propertyEnumsVal;
	}

}
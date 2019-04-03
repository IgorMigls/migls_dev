<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Catalog\CatalogIblockTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Online1c\Iblock\Helpers\MainHelper;


class Element extends \Bitrix\Iblock\ElementTable
{
	private static $iblockId = null;
	private static $properties = null;
	private static $queryParams = null;
	private static $queryProps = null;
	private static $usePropertyInfo = false;

	/**
	 * @method getEntity
	 * @param null $iblockId
	 * @param bool $props
	 *
	 * @return Entity\Base
	 */
	public static function getEntity($iblockId = null, $props = false)
	{
		if(intval($iblockId) > 0){
			static::$iblockId = $iblockId;
		}
		if($props && (int)static::$iblockId > 0){
			self::$properties = $props;
		}

		if(!is_null(static::$iblockId)){

			$base = new BaseIblockEntity(static::$iblockId);
			$elementEntity = $base->compile()->getEntityElement();

			if(!is_null(self::$properties)){
				$propEntity = new BaseProperty(static::$iblockId, self::$properties);
				if(is_array(self::$queryProps) && count(self::$queryProps) > 0){
					$propEntity->setQueryProps(self::$queryProps);
				}
				$propEntity->setUsePropertyInfo(self::$usePropertyInfo);
				$propEntity->compile();

				$elementEntity->addField(new Entity\ReferenceField(
					'PROPERTY',
					$propEntity->getEntityProperty(),
					['=this.ID' => 'ref.IBLOCK_ELEMENT_ID'],
					['join_type' => 'LEFT']
				));
			}

			return $elementEntity;

		} else {
			return \Bitrix\Iblock\ElementTable::getEntity();
		}
	}

	public static function getMap()
	{
		$map = parent::getMap();
		$map['DETAIL_PICTURE_FILE'] = new Entity\ReferenceField(
			'DETAIL_PICTURE_FILE',
			FileTable::getEntity(),
			['=this.DETAIL_PICTURE' => 'ref.ID']
		);
		$map['PREVIEW_PICTURE_FILE'] = new Entity\ReferenceField(
			'PREVIEW_PICTURE_FILE',
			FileTable::getEntity(),
			['=this.PREVIEW_PICTURE' => 'ref.ID']
		);

		return $map;
	}


	/**
	 * @method query
	 * @param array $params
	 *
	 * @return ElementQuery
	 */
	public static function query($params = [])
	{
		$query = new ElementQuery(static::getEntity($params['IBLOCK_ID'], $params['PROPERTIES']));

		return $query;
	}

	/**
	 * @method getList
	 * @param array $parameters
	 *
	 * @return \Bitrix\Main\DB\Result
	 */
	public static function getList(array $parameters = array())
	{
		$iblockId = (int)$parameters['filter']['IBLOCK_ID'];
		if($iblockId  > 0){
			static::$iblockId = $iblockId;
			static::$queryParams = MainHelper::minimizeInputParams($parameters);
			$propCodes = MainHelper::getParamCodes(static::$queryParams);
			self::$queryProps = $propCodes['props'];

			$tmpProps = [];
			foreach (self::$queryProps as $prop) {
				$tmpProps[] = $prop[1];
			}
			$tmpProp = array_unique($tmpProps);
			self::$properties = $tmpProps;
		}

		self::$usePropertyInfo = (bool)$parameters['property_info'];
		unset($parameters['property_info']);

		$obList = parent::getList($parameters);

		return $obList;
	}

	/**
	 * @method getIblockByElement
	 * @param $elementId
	 *
	 * @return null|int
	 * @throws IblockException
	 */
	public static function getIblockByElement($elementId)
	{
		$elementId = (int)$elementId;
		$iblockId = null;
		if($elementId == 0)
			throw new IblockException('element id is null');

		$row = \Bitrix\Iblock\ElementTable::getRow([
			'select' => ['IBLOCK_ID'],
			'filter' => ['=ID' => $elementId]
		]);
		if(is_null($row))
			throw new IblockException('element ID='.$elementId.' is not exist');

		$iblockId = $row['IBLOCK_ID'];

		return $iblockId;
	}

	/**
	 * @method getPropertyValue
	 * @param $elementId
	 * @param $code
	 * @param null $iblockId
	 *
	 * @return null
	 * @throws IblockException
	 */
	public static function getPropertyValue($elementId, $code, $iblockId = null)
	{
		$result = null;
		$elementId = (int)$elementId;
		$iblockId = (int)$iblockId;
		if($elementId == 0)
			throw new IblockException('element id is null');

		if(strlen($code) == 0)
			throw new IblockException('property code is null');

		if($iblockId == 0){
			$iblockId = static::getIblockByElement($elementId);
		}

		$rowProperty = self::getRow([
			'select' => [$code => 'PROPERTY.'.$code],
			'filter' => ['=ID'=>$elementId, 'IBLOCK_ID' => $iblockId],
		]);

		if(!is_null($rowProperty)){
			$metaProperty = BaseProperty::getMetaProperty($iblockId);
			$result = $metaProperty[$code];
			$result['VALUE'] = $rowProperty[$code];
			return $result;
		}

		return null;
	}

}
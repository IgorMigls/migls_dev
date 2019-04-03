<?php namespace Soft\IBlock;

use Bitrix\Main;
use Bitrix\Main\Entity;

/**
 * Class ElementTable
 * @package Soft\IBlock
 */
class ElementTable extends Entity\DataManager
{
	protected static $cacheMetadata = true;
	protected static $_iblockId = null;
	protected static $paging = null;

	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element';
	}

	/**
	 * @method getMap
	 * @return array|null
	 */
	public static function getMap()
	{
		$map = array(
			new Entity\IntegerField('ID',array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			)),
			new Entity\StringField('NAME'),
			new Entity\StringField('CODE'),
			new Entity\IntegerField('PREVIEW_PICTURE'),
			new Entity\IntegerField('DETAIL_PICTURE'),
			new Entity\StringField('PREVIEW_TEXT'),
			new Entity\StringField('DETAIL_TEXT'),
			new Entity\IntegerField('IBLOCK_ID'),
			new Entity\ReferenceField(
				'IBLOCK',
				'\Bitrix\Iblock\IblockTable',
				array('=this.IBLOCK_ID' => 'ref.ID')
			),
			new Entity\BooleanField('ACTIVE', array(
				'values' => array('N','Y'),
			)),
			new Entity\DatetimeField('ACTIVE_FROM'),
			new Entity\DatetimeField('ACTIVE_TO'),
			new Entity\IntegerField('IBLOCK_SECTION_ID'),
			new Entity\IntegerField('SORT'),
			new Entity\StringField('XML_ID'),
			new Entity\IntegerField('WF_STATUS_ID'),
			new Entity\IntegerField('WF_PARENT_ELEMENT_ID'),
			new Entity\DatetimeField('DATE_CREATE'),
			new Entity\DatetimeField('TIMESTAMP_X'),
			new Main\Entity\IntegerField('MODIFIED_BY'),
			new Main\Entity\ReferenceField(
				'MODIFIED_BY_USER',
				'Bitrix\Main\UserTable',
				array('=this.MODIFIED_BY' => 'ref.ID'),
				array('join_type' => 'LEFT')
			),
			new Entity\ExpressionField(
				'DETAIL_PAGE_URL',
				"''"
			)
		);
		return $map;
	}

	/**
	 * @method getMetadata
	 * @param null $iblockId
	 * @return array
	 */
	public static function getMetadata($iblockId = null)
	{
		Main\Loader::includeModule('iblock');
		if (empty($iblockId)) {
			$iblockId = static::$_iblockId;
		}
		$result = array();
		$obCache = \Bitrix\Main\Data\Cache::createInstance();

		$CacheTag = new \Bitrix\Main\Data\TaggedCache();
		$cache_time = 86400 * 30;
		$cache_id = 'd7_iblock_'.$iblockId;
		$cacheDir = '/esd/iblock_'.$iblockId;

		if (static::$cacheMetadata && $obCache->initCache($cache_time, $cache_id, $cacheDir)) {
			$result = $obCache->GetVars();
		} else {
			$rs = \Bitrix\Iblock\PropertyTable::getList(array(
				'filter' => array('IBLOCK_ID' => $iblockId, 'ACTIVE'=>'Y'),
				'select'=>array(
					'ID','IBLOCK_ID','NAME','CODE','PROPERTY_TYPE','LIST_TYPE',
					'MULTIPLE','LINK_IBLOCK_ID','IS_REQUIRED',
//					'USER_TYPE','USER_TYPE_SETTINGS'
				)
			));

			while ($arProp = $rs->fetch()) {
				$code = strlen($arProp['CODE']) > 0 ? $arProp['CODE'] : $arProp['ID'];
				$result[$code] = $arProp;
			}

			if (static::$cacheMetadata) {
				$obCache->startDataCache();

				$CacheTag->startTagCache($cacheDir);
				$CacheTag->registerTag($cache_id);
				$CacheTag->endTagCache();

				$obCache->endDataCache($result);
			}
		}
		return $result;
	}

	public static function createEntityProp($iBlock)
	{
		$fullEntityName = 'Property'.$iBlock.'Table';
		if(Entity\Base::isExists($fullEntityName)){
			$propertyEntity = Entity\Base::getInstance($fullEntityName);
		} else {
			$propertyEntity = Entity\Base::compileEntity(
				$fullEntityName,
				array(),
				array('table_name'=>'b_iblock_element_prop_s'.$iBlock)
			);
		}

		return $propertyEntity;
	}

	/**
	 * Create dynamically class for requested iblock
	 * @param int $iblockId
	 * @param array $parameters
	 * @return \Bitrix\Main\Entity\Base
	 * @throws Main\ArgumentException
	 */
	public static function compileLinkEntity($iblockId, $parameters = array())
	{
		$iblockId = intval($iblockId);
		if ($iblockId <= 0) {
			throw new Main\ArgumentException('$iblockId should be integer');
		}

		$entityName = 'Iblock' . $iblockId . 'Table';

		if(Entity\Base::isExists($entityName)){
			$linkEntity = Entity\Base::getInstance($entityName);
			foreach (self::getMap() as $field) {
				$linkEntity->addField($field);
			}
		} else {
			$linkEntity = Entity\Base::compileEntity(
				$entityName,
				self::getMap(),
				array('table_name'=>self::getTableName())
			);
		}

		return $linkEntity;
	}

	/**
	 * @method compileEntityProperty
	 * @param $iBlock
	 * @param array $select
	 * @param Query $MainQuery
	 * @return Entity\Base
	 * @throws Main\ArgumentException
	 */
	public static function compileEntityProperty($iBlock, $select = array(), \Soft\IBlock\Query $MainQuery)
	{
		static::$_iblockId = $iBlock;

		$propertyEntity = static::createEntityProp($iBlock);

		$propertyEntity->addField(new Entity\IntegerField('IBLOCK_ELEMENT_ID'));
		$metaIBlock = self::getMetadata($iBlock);

		foreach ($select as $val) {
			$arVal = explode('.',$val);
			$codeProp = $arVal[0];

			if($codeProp == 'PROPERTY')
				$codeProp = $arVal[1];

			if($metaIBlock[$codeProp]['MULTIPLE'] == 'N'){
				if(array_key_exists($codeProp, $metaIBlock)){
					static::addFiled($propertyEntity, $metaIBlock[$codeProp], $select, $MainQuery);
				}
			} else {
				$codePropEntity = str_replace('_ENTITY','',$codeProp);
				if(array_key_exists($codePropEntity, $metaIBlock)){
					static::addFiled($propertyEntity, $metaIBlock[$codePropEntity], $select, $MainQuery);
				}
			}
		}
//		PR($propertyEntity);
		return $propertyEntity;
	}

	public static function addFiled(Entity\Base $propEntity, array $arProp, $select = array(), $MainQuery = null)
	{
		$result = array();

		if($arProp['MULTIPLE'] == 'N'){
			switch($arProp['PROPERTY_TYPE']){
				case 'N':
					$propEntity->addField(new Entity\IntegerField($arProp['CODE'], array(
						'title'=>$arProp['NAME'],
						'column_name'=>'PROPERTY_'.$arProp['ID'],
						'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
					)));
					break;
				case 'L':
					$ref = static::isRef($arProp['CODE'], $select);
					if($ref){
						$propEntity->addField(new Entity\IntegerField($arProp['CODE'].'_VALUE', array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						)));
						$propEntity->addField(new Entity\ReferenceField(
							$arProp['CODE'],
							'\Soft\IBlock\PropertyEnumTable',
							array('=this.'.$arProp['CODE'].'_VALUE'=>'ref.ID')
						));
					} else {
						$propEntity->addField(new Entity\IntegerField($arProp['CODE'], array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						)));
					}
					break;
				case 'E':
					$ref = static::isRef($arProp['CODE'], $select);
					$linkIBlock = intval($arProp['LINK_IBLOCK_ID']);
					if($ref){
						if($linkIBlock > 0){
							$propEntity->addField(new Entity\IntegerField($arProp['CODE'].'_VALUE', array(
								'title'=>$arProp['NAME'],
								'column_name'=>'PROPERTY_'.$arProp['ID'],
								'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
							)));
							$linkEntity = static::compileLinkEntity($linkIBlock);
							$propEntity->addField(new Entity\ReferenceField(
								$arProp['CODE'],
								$linkEntity,
								array('=this.'.$arProp['CODE'].'_VALUE'=>'ref.ID')
							));
							$linkSelect = array();
							foreach ($select as $val) {
								if(preg_match('/'.$arProp['CODE'].'/i', $val)){
									$arVal = explode('.',$val);

									if(count($arVal) > 1 && in_array('PROPERTY', $arVal)){
										array_shift($arVal);
										$linkSelect[] = implode('.', $arVal);
										$linkPropEntity = static::compileEntityProperty($linkIBlock, $linkSelect, $MainQuery);
									}
								}
							}
							if($linkPropEntity instanceof Entity\Base){
								$linkEntity->addField(new Entity\ReferenceField(
									'PROPERTY',
									$linkPropEntity,
									array(
										'ref.IBLOCK_ELEMENT_ID' => 'this.ID',
									),
									array('join_type' => 'LEFT')
								));
							}
						} else {

						}
					} else {
						$propEntity->addField(new Entity\IntegerField($arProp['CODE'], array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						)));
					}
					break;
				default:
					$propEntity->addField(new Entity\StringField($arProp['CODE'], array(
						'title'=>$arProp['NAME'],
						'column_name'=>'PROPERTY_'.$arProp['ID'],
						'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
					)));
					break;
			}
		} else {
			$isList = $arProp['PROPERTY_TYPE'] == 'L' ? true : false;
			$propEntity->addField(new Entity\TextField($arProp['CODE'], array(
				'title'=>$arProp['NAME'],
				'column_name'=>'PROPERTY_'.$arProp['ID'],
				'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false,
				'fetch_data_modification' => function () use($arProp, $isList){
					return array(
						function ($value, $field, $data, $alias)use($arProp, $isList) {
//							$result = unserialize($value);
//							if(count($result['VALUE']) == 0 && $arProp){
								$result = self::modifierResultMulti($data, $arProp, $isList);
//							}
							return $result;
						}
					);
				}
			)));

			$propEntity->addField(new Entity\ReferenceField(
				$arProp['CODE'].'_ENTITY',
				static::getMultiEntity($arProp['IBLOCK_ID'], $isList),
				array(
					'=this.IBLOCK_ELEMENT_ID'=>'ref.IBLOCK_ELEMENT_ID',
					'ref.IBLOCK_PROPERTY_ID' =>array('?i', $arProp['ID'])
				),
				array('join_type' => 'LEFT')
			));
		}

		return $result;
	}

	/**
	 * @method isRef
	 * @param $code
	 * @param array $select
	 * @return bool
	 */
	public static function isRef($code, array $select)
	{
		$ref = false;
		foreach ($select as $val) {
			if(preg_match('/'.$code.'/i', $val)){
				$arVal = explode('.',$val);
				if(count($arVal) > 1){
					$ref = true;
					break;
				}
			}
		}
		return $ref;
	}

	/**
	 * @deprecated
	 * @method setPropMap
	 * @param $select
	 * @param Query $MainQuery
	 * @return array
	 * @throws Main\ArgumentException
	 */
	protected static function setPropMap($select, Query $MainQuery)
	{
		$metaIBlock = self::getMetadata(static::$_iblockId);
		$arMetaSelect = array();
		trimArr($select);
		PR($select);
		if(count($select) > 0){
			foreach ($select as $code) {
				$arCode = explode('.',$code);
				if(is_array($arCode)){
					$arMetaSelect[$code] = $metaIBlock[$arCode[0]];
				} else {
					$arMetaSelect[$code] = $metaIBlock[$code];
				}
			}
		} else {
			$arMetaSelect = $metaIBlock;
		}

		$arMap = array(
			new Entity\IntegerField('IBLOCK_ELEMENT_ID',array('primary' => true)),
		);

		foreach ($arMetaSelect as $code => $arProp) {
			if($arProp['MULTIPLE'] == 'N'){
				switch($arProp['PROPERTY_TYPE']){
					case 'E':
						$codeElProp = $code;
						$arSelect = $MainQuery->getSelect();
						$arLinkSelectProp = $LinkEntity = null;
						foreach ($arSelect as $k => $val) {
							if(preg_match('/'.$code.'.(.*)/i', $val, $mathCode)){
								$linkSelectTmp = explode('.',$mathCode[1]);
								if($linkSelectTmp[0] == 'PROPERTY'){
									$arLinkSelectProp[] = $linkSelectTmp[1];
								}
								if(intval($arProp['LINK_IBLOCK_ID']) > 0){
									$codeElProp = $code.'_VALUE';

									$LinkEntity = self::compileLinkEntity($arProp['LINK_IBLOCK_ID']);
									$arMap[] = new Entity\ReferenceField(
										$code,
										$LinkEntity->getDataClass(),
										array('=this.'.$codeElProp =>'ref.ID')
									);
								} else {
									$arLinkSelectTmp = explode('.',$mathCode[0]);
									$MainQuery->delSelect($val);
									$val = 'PROPERTY.'.$arLinkSelectTmp[0];
									$MainQuery->addSelect($val, $arLinkSelectTmp[0]);
									$arMap[] = new Entity\IntegerField($code, array(
										'title'=>$arProp['NAME'],
										'column_name'=>'PROPERTY_'.$arProp['ID'],
										'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
									));
								}
							}
						}

						if(intval($arProp['LINK_IBLOCK_ID']) > 0){
							if(!is_null($arLinkSelectProp) && $LinkEntity instanceof Entity\Base){

								$propertyEntityLink = self::compileEntityProperty($arProp['LINK_IBLOCK_ID'], array(), $MainQuery);

								$LinkEntity->addField(new Entity\ReferenceField(
									'PROPERTY',
									$propertyEntityLink->getDataClass(),
									array(
										'ref.IBLOCK_ELEMENT_ID' => 'this.ID',
									),
									array('join_type' => 'LEFT')
								));
							}
						}
						$arMap[] = new Entity\IntegerField($codeElProp, array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						));
						break;
					case 'L':
						$arMap[] = new Entity\IntegerField($code.'_ENUM_ID', array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						));

						$arMap[] = new Entity\ReferenceField(
							$code,
							'\Soft\IBlock\PropertyEnumTable',
							array('=this.'.$code.'_ENUM_ID'=>'ref.ID')
						);
						break;
					case 'N':
						$arMap[] = new Entity\IntegerField($code, array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						));
						break;
					case 'S':
					default:
						$arMap[] = new Entity\StringField($code, array(
							'title'=>$arProp['NAME'],
							'column_name'=>'PROPERTY_'.$arProp['ID'],
							'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
						));
						break;
				}
			} elseif($arProp['MULTIPLE'] == 'Y') {

			}
		}
		return $arMap;
	}

	public static function getMultiEntity($iBlock, $isList = false)
	{
		$iBlock = intval($iBlock);
		if($iBlock == 0)
			throw new Main\ArgumentException('$iblockId should be integer');

		$class = 'MultiProperty'.$iBlock;

		if(Entity\Base::isExists($class)){
			$multiEntity = Entity\Base::getInstance($class);
			if($isList){
				$multiEntity->addField(new Entity\ReferenceField(
					'VALUE_LIST',
					'\Soft\IBlock\PropertyEnumTable',
					array(
						'=this.IBLOCK_PROPERTY_ID'=>'ref.PROPERTY_ID',
						'=this.VALUE_ENUM'=>'ref.ID'
					)
				));
			}
			return $multiEntity;
		} else {
			return Entity\Base::compileEntity(
				'MultiProperty'.$iBlock,
				MultiPropTable::getMap($isList),
				array('table_name'=>'b_iblock_element_prop_m'.$iBlock)
			);
		}
	}

	/**
	 * @method modifierResultMulti
	 * @param array $data
	 * @param array $arProp
	 * @param bool $list
	 * @return bool
	 * @throws Main\ArgumentException
	 */
	public static function modifierResultMulti(array $data, array $arProp, $list = false)
	{
		$result = false;
		$arRes = self::getMultiValues($data, $arProp, $list);
		if($arRes){
			foreach ($arRes as $k => $arVal) {
				if(isset($arVal['ENUM']))
					$result['VALUE'][$k] = $arVal['ENUM'];
				else
					$result['VALUE'][$k] = $arVal['VALUE'];
				$result['DESCRIPTION'][$k] = $arVal['DESCRIPTION'];
				$result['ID'][$k] = $arVal['ID'];
			}

			if($result)
				self::updateMultiValues($data, $arProp, $result);

			return $result;
		}
		return false;
	}

	/**
	 * @method getMultiValues
	 * @param $data
	 * @param $arProp
	 * @param bool $list
	 * @return array
	 * @throws Main\ArgumentException
	 */
	public static function getMultiValues($data, $arProp, $list = false)
	{
		$query = new Entity\Query(self::getMultiEntity($arProp['IBLOCK_ID']));
		$query
			->setSelect(array('ID','VALUE','DESCRIPTION'))
			->setFilter(array('IBLOCK_ELEMENT_ID'=>$data['ID'], 'IBLOCK_PROPERTY_ID'=>$arProp['ID']));

		if($list){
			$query->registerRuntimeField(false, new Entity\ReferenceField(
				'ENUM_ENTITY',
				'\Soft\IBlock\PropertyEnumTable',
				array('=this.VALUE'=>'ref.ID')
			));
			$query->addSelect('ENUM_ENTITY.VALUE','ENUM');
		}
		$arList = $query->exec()->fetchAll();

		return $arList;

//		$strSql =  "SELECT ID, VALUE, DESCRIPTION
//					FROM b_iblock_element_prop_m" . $arProp['IBLOCK_ID'] . "
//						WHERE
//							IBLOCK_ELEMENT_ID = " . $data['ID'] . "
//						AND IBLOCK_PROPERTY_ID = " . $arProp['ID'] . "
//					ORDER BY ID";
//		return \Bitrix\Main\Application::getConnection()->query($strSql)->fetchAll();
	}

	/**
	 * @method updateMultiValues
	 * @param array $data
	 * @param $arProp
	 * @param array $result
	 * @return Main\DB\Result
	 */
	public static function updateMultiValues(array $data, $arProp, array $result)
	{
		$connect = \Bitrix\Main\Application::getConnection();
		$resultStr = serialize($result);
		$sTableUpdate = 'b_iblock_element_prop_s'.$arProp['IBLOCK_ID'];
		$strPrepare = $connect->getSqlHelper()->prepareAssignment($sTableUpdate, 'PROPERTY_'.$arProp['ID'], $resultStr);
		$strSqlUpdate = "
			UPDATE b_iblock_element_prop_s".$arProp['IBLOCK_ID']."
			SET ".$strPrepare." WHERE IBLOCK_ELEMENT_ID = ".intval($data["ID"]);
		$resUpdate = $connect->query($strSqlUpdate);
		return $resUpdate;
	}

	/**
	 * Executes the query and returns selection by parameters of the query. This function is an alias to the Query object functions
	 *
	 * @param array $parameters Array of query parameters, available keys are:
	 * 		"select" => array of fields in the SELECT part of the query, aliases are possible in the form of "alias"=>"field"
	 * 		"filter" => array of filters in the WHERE part of the query in the form of "(condition)field"=>"value"
	 * 		"group" => array of fields in the GROUP BY part of the query
	 * 		"order" => array of fields in the ORDER BY part of the query in the form of "field"=>"asc|desc"
	 * 		"limit" => integer indicating maximum number of rows in the selection (like LIMIT n in MySql)
	 * 		"offset" => integer indicating first row number in the selection (like LIMIT n, 100 in MySql)
	 *		"runtime" => array of entity fields created dynamically
	 * @return Main\DB\Result
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getList(array $parameters = array())
	{
		$query = new Query(static::getEntity());

		if(!isset($parameters['select']))
		{
			$query->setSelect(array('*'));
		}

		foreach($parameters as $param => $value)
		{
			switch($param)
			{
				case 'select':
					$query->setSelect($value);
					break;
				case 'filter':
					$query->setFilter($value);
					break;
				case 'group':
					$query->setGroup($value);
					break;
				case 'order';
					$query->setOrder($value);
					break;
				case 'limit':
					$query->setLimit($value);
					break;
				case 'offset':
					$query->setOffset($value);
					break;
				case 'count_total':
					$query->countTotal($value);
					break;
				case 'runtime':
					foreach ($value as $name => $fieldInfo)
					{
						$query->registerRuntimeField($name, $fieldInfo);
					}
					break;
				case 'data_doubling':
					if($value)
						$query->enableDataDoubling();
					else
						$query->disableDataDoubling();
					break;
				default:
					throw new Main\ArgumentException("Unknown parameter: ".$param, $param);
			}
		}
		$obResult = $query->exec();

		$offset = $query->getOffset();
		if($offset > 0){
			$query->setPaging($parameters, $query);
		}

		return $obResult;
	}

	/**
	 * @method getIBlock
	 * @param int $productId
	 * @return int|null
	 */
	public static function getIBlock($productId = 0)
	{
		if($productId > 0){
			$arElement = \Bitrix\Iblock\ElementTable::getRow(array(
				'select'=>array('IBLOCK_ID'),
				'filter'=>array('=ID'=>$productId)
			));
			return intval($arElement['IBLOCK_ID']);
		}
		return null;
	}
}
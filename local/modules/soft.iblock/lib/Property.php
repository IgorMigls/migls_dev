<?php namespace Soft;

use Bitrix\Main\Entity;
use PW\Tools\Debug;

/**
 * Class Property
 * @package Alvitek\Blocks
 */
class Property extends BaseQuery
{
	protected $propSelect;
	protected $property;
	protected $allParams;
	/** @var  Entity\Base */
	protected $entity;

	private $iblock = 0;
	private $delProps;
	private $propTypes;
	private static $usePropCache = true; // TODO включить в продакшене кеш !
	private static $cachePropId = 'pw_iblock_meta_props_';
	private static $cachePropTime = 86400;
	private static $cachePropDir = '/pw/IBlock'; // TODO сменить папку
	private $propertyEnums;
	private $propCodes;

	/**
	 * Property constructor.
	 *
	 * @param array $propSelect
	 * @param array $params
	 */
	public function __construct($propSelect = [], $params = [])
	{
		$this->setPropSelect($propSelect);
		$this->allParams = $params;
	}

	/**
	 * @method compileProperty
	 * @param int $iblock
	 *
	 * @return array
	 * @throws IBlockException
	 */
	public function compileProperty($iblock = 0)
	{
		$this->iblock = $iblock;
		$meta = $this->getMetaProperty($iblock);
		$paramsList = $this->allParams;

		foreach ($this->getPropSelect() as $code => $value) {
			if(empty($meta[$code])){
				foreach ($meta as $k => $prop) {
					preg_match('#'.$k.'#i', $code, $matchCode);
					if($matchCode[0]){
						$this->property[$matchCode[0]] = $meta[$matchCode[0]];
					} /*else {
						foreach ($paramsList as $type => $arVal) {
							$paramsList[$type] = $this->deleteParams($code, $arVal);
						}
					}*/
				}
			} else {
				$this->property[$code] = $meta[$code];
			}
		}

		$this->allParams = $paramsList;
		$this->compileEntity($iblock);
		foreach ($this->property as $code => $arProperty) {
			if($arProperty['MULTIPLE'] == 'N'){
				switch($arProperty['PROPERTY_TYPE']){
					case 'L':
						$this->listProperty($code);
						break;
					case 'E':
						$this->linkProperty($code);
						break;
					default:
						$this->scalarProperty($code);
				}
			} else {
				$this->compileMultiEntity($arProperty);
			}
		}

		return [
			'ENTITY'=>$this->entity,
			'PARAMETERS'=>$this->allParams,
			'ENUMS'=>$this->propertyEnums
		];
	}

	/**
	 * @method addFieldToEntity
	 * @param array $arProperty
	 * @param string $code
	 */
	public function addFieldToEntity($arProperty = [], $code = '')
	{
		if($arProperty['MULTIPLE'] == 'N'){
			switch($arProperty['PROPERTY_TYPE']){
				case 'L':
					$this->listProperty($code);
					break;
				case 'E':
					$this->linkProperty($code);
					break;
				default:
					$this->scalarProperty($code);
			}
		} else {
			$this->compileMultiEntity($arProperty);
		}
	}

	/**
	 * @method scalarProperty
	 * @param $code
	 */
	public function scalarProperty($code)
	{
		$arProp = $this->property[$code];

		if($arProp['PROPERTY_TYPE'] == 'N'){
			$field = new Entity\IntegerField($code, [
					'title'=>$arProp['NAME'],
					'column_name'=>'PROPERTY_'.$arProp['ID'],
					'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
			]);
			$field->addFetchDataModifier(function ($value, $field, $data, $alias) use($arProp){
				$result = 0;
				$val = explode('.', $value);

				if(count($val) > 1){
					if(intval($val[1]) > 0)
						$result = floatval($value);
					else
						$result = intval($value);
				} else {
					$result = intval($value);
				}
				return $result;
			});
		} else {
			$field = new Entity\StringField($code, [
					'title'=>$arProp['NAME'],
					'column_name'=>'PROPERTY_'.$arProp['ID'],
					'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
			]);
		}

		switch(strtoupper($arProp['USER_TYPE'])){
			case 'HTML':
				$field->addFetchDataModifier(function ($value, $field, $data, $alias) use($arProp){
					$result = null;
					if(strlen($value) > 0){
						$res = unserialize($value);
						$result = $res['TEXT'];
					}

					return $result;
				});
				break;
		}

		$this->entity->addField($field);
	}

	/**
	 * @method listProperty
	 * @param $code
	 *
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function listProperty($code)
	{
		$arProp = $this->property[$code];
		$this->propertyEnums[] = $arProp['ID'];
		$field = null;

		if(is_array($this->propSelect[$code])){
			if(!$this->entity->hasField($code)){
				$singleField = $code.'_SINGLE';
				$this->entity->addField(new Entity\IntegerField($singleField, [
						'title'=>$arProp['NAME'],
						'column_name'=>'PROPERTY_'.$arProp['ID'],
						'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
				]));
				$field = new Entity\ReferenceField(
						$code,
						\Bitrix\Iblock\PropertyEnumerationTable::getEntity(),
						['=this.'.$singleField => 'ref.ID']
				);
			}
		} else {
			$field = new Entity\IntegerField($code, [
					'title'=>$arProp['NAME'],
					'column_name'=>'PROPERTY_'.$arProp['ID'],
					'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
			]);
		}

		if($field instanceof Entity\Field){
			$field->addFetchDataModifier(
					function ($value, $field, $data, $alias) use($arProp) {
						$result = null;
						$enums = BaseQuery::getPropertyEnumsVal();
						$valNow = $data[$alias];
						$result = $enums[$arProp['CODE']][$valNow];

						return $result;
					}
			);
			$this->entity->addField($field);
		}
	}

	/**
	 * @method linkProperty
	 * @param $code
	 */
	public function linkProperty($code)
	{
		$arProp = $this->property[$code];
		if(is_array($this->propSelect[$code])){
			$singleField = $code.'_SINGLE';
			$this->entity->addField(new Entity\IntegerField($singleField, [
				'title'=>$arProp['NAME'],
				'column_name'=>'PROPERTY_'.$arProp['ID'],
				'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
			]));

			if(intval($arProp['LINK_IBLOCK_ID']) > 0){
				$linkParams = $this->prepareLinkParams($this->allParams, $code);
				$linkParams['filter']['IBLOCK_ID'] = $arProp['LINK_IBLOCK_ID'];
				$BaseLink = new BaseController($linkParams, 'ElementLink'.$arProp['LINK_IBLOCK_ID']);
				$linkEntity = $BaseLink->init();
				$this->entity->addField(new Entity\ReferenceField(
					$code,
					$linkEntity,
					['=this.'.$singleField => 'ref.ID']
				));
			} else {
				$this->entity->addField(new Entity\ReferenceField(
					$code,
					IblockElementTable::getEntity(),
					['=this.'.$singleField => 'ref.ID']
				));
			}
		} else {
			$this->entity->addField(new Entity\IntegerField($code, [
				'title'=>$arProp['NAME'],
				'column_name'=>'PROPERTY_'.$arProp['ID'],
				'required'=>$arProp['IS_REQUIRED'] == 'Y' ? true : false
			]));
		}
	}

	/**
	 * @method getMetaProperty
	 * @param int $iBlock
	 *
	 * @return array
	 * @throws IBlockException
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function getMetaProperty($iBlock)
	{
		if(intval($iBlock) == 0)
			throw new IBlockException('Нет ИД инфоблока', 100);

		$metaData = [];

		$DataCache = \Bitrix\Main\Data\Cache::createInstance();
		$TagCache = new \Bitrix\Main\Data\TaggedCache();

		$cacheId = self::$cachePropId.$iBlock;
		$cacheTime = self::$cachePropTime * 30;
		$cacheDir = self::$cachePropDir;
//		$TagCache->clearByTag($cacheId);

		if($DataCache->initCache($cacheTime, $cacheId, $cacheDir) && self::$usePropCache){
			$metaData = $DataCache->getVars();
		} else {
			$DataCache->startDataCache();
			$TagCache->startTagCache($cacheDir);

			$obProps = \Bitrix\Iblock\PropertyTable::getList([
				/*'select'=>[
					'ID','IBLOCK_ID','CODE','PROPERTY_TYPE','MULTIPLE','ACTIVE',
					'LINK_IBLOCK_ID', 'IS_REQUIRED','USER_TYPE','NAME'
				],*/
				'select'=>['*'],
				'filter'=>['IBLOCK_ID'=>$iBlock]
			]);
			while($prop = $obProps->fetch()){
				$metaData[$prop['CODE']] = $prop;
			}

			$TagCache->registerTag($cacheId);
			$TagCache->endTagCache();
			$DataCache->endDataCache($metaData);
		}

		$this->propTypes = [];
		foreach ($metaData as $code => $prop) {
			switch($prop['PROPERTY_TYPE']){
				case 'L':
					$this->propTypes['LIST'][] = $prop;
					break;
				case 'E':
					$this->propTypes['ELEMENT'][] = $prop;
					break;
				default:
					$this->propTypes['SCALAR'][] = $prop;
					break;
			}
			$this->propCodes[] = $code;
		}
//		PR($metaData);

		return $metaData;
	}

	/**
	 * @method deleteParams
	 * @param $code
	 * @param $params
	 *
	 * @return mixed
	 */
	public function deleteParams($code, $params)
	{
		foreach ($params as $k => $param) {
			if(preg_match('#'.$code.'#i', $param)){
				unset($params[$k]);
			}
		}

		return $params;
	}

	/**
	 * @method compileEntity
	 * @param int $iblock
	 *
	 * @return Entity\Base
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function compileEntity($iblock = 0)
	{
		if(intval($iblock) == 0)
			$iblock = $this->iblock;

		$name = 'Property'.$iblock.'Table';
		if(Entity\Base::isExists(__NAMESPACE__.'\\'.$name)){
			$propertyEntity = Entity\Base::getInstance(__NAMESPACE__.'\\'.$name);
		} else {
			$propertyEntity = Entity\Base::compileEntity(
				$name,
				['IBLOCK_ELEMENT_ID'=>new Entity\IntegerField('IBLOCK_ELEMENT_ID')],
				['table_name'=>'b_iblock_element_prop_s'.$iblock, 'namespace'=>__NAMESPACE__]
			);
		}
		$this->entity = $propertyEntity;

		return $propertyEntity;
	}

	/**
	 * @method compileMultiEntity
	 * @param array $arProperty
	 *
	 * @throws IBlockException
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function compileMultiEntity($arProperty = [])
	{
//		PR($arProperty);
		$utmEntity = PropertyUtmTable::getEntity($arProperty['IBLOCK_ID']);

		if($arProperty['PROPERTY_TYPE'] == 'L'){
			$this->propertyEnums[] = $arProperty['ID'];
			if(!$utmEntity->hasField('L')){
				$utmEntity->addField(new Entity\ReferenceField(
					'L',
					'\Bitrix\Iblock\PropertyEnumerationTable',
					array(
						'=this.IBLOCK_PROPERTY_ID'=>'ref.PROPERTY_ID',
						'=this.VALUE_ENUM'=>'ref.ID'
					)
				));
			}
		}

		if($arProperty['PROPERTY_TYPE'] == 'E'){
			if(!$utmEntity->hasField('E')){
				$utmEntity->addField(new Entity\ReferenceField(
					'E',
					IblockElementTable::getEntity(),
					array(
						'=this.VALUE'=>'ref.ID',
						'=this.IBLOCK_PROPERTY_ID'=>array('?i', $arProperty['ID']),
					)
				));
			}
		}

		$code = $arProperty['CODE'];

		$this->entity->addField(new Entity\ReferenceField(
				$code,
				$utmEntity,
				array(
					'=this.IBLOCK_ELEMENT_ID'=>'ref.IBLOCK_ELEMENT_ID',
					'ref.IBLOCK_PROPERTY_ID'=>array('?i', $arProperty['ID'])
				),
				array('join_type' => 'LEFT')
		));

		$fieldVal = new Entity\TextField($code.'_MVAL', [
			'title'=>$arProperty['NAME'],
			'column_name'=>'PROPERTY_'.$arProperty['ID'],
			'required'=>$arProperty['IS_REQUIRED'] == 'Y' ? true : false,
		]);

		$fieldVal->addFetchDataModifier(
				function ($value, $field, $data, $alias) use ($arProperty) {
					$result = unserialize($value);
					if (count($result) == 0){
						$result = self::modifierResultMulti($data, $arProperty);
					}

					if ($arProperty['PROPERTY_TYPE'] == 'L'){
						$enums = BaseQuery::getPropertyEnumsVal();
						foreach ($result['VALUE'] as $k => $value) {
							$result['VALUE'][$k] = $enums[$arProperty['CODE']][$value];
						}
					}
					return $result;
				}
		);

		$this->entity->addField($fieldVal);
	}

	/**
	 * @method prepareLinkParams
	 * @param $allParams
	 * @param $code
	 *
	 * @return array
	 */
	private function prepareLinkParams($allParams, $code)
	{
		$linkParams = [];

		foreach ($allParams['select'] as $k => $value) {
			if(preg_match('#'.$code.'.(.*)#i', $value, $matchVal)){
				$linkParams['select'][] = $matchVal[1];
			}
		}

		foreach ($allParams['group'] as $k => $value) {
			if(preg_match('#'.$code.'.(.*)#i', $value, $matchVal)){
				$linkParams['select'][] = $matchVal[1];
			}
		}

		foreach ($allParams['filter'] as $k => $value) {
			if(preg_match('#'.$code.'.(.*)#i', $k, $matchVal)){
				$linkParams['select'][] = $matchVal[1];
			}
		}

		foreach ($allParams['order'] as $k => $value) {
			if(preg_match('#'.$code.'.(.*)#i', $k, $matchVal)){
				$linkParams['select'][] = $matchVal[1];
			}
		}

		return $linkParams;
	}

	/**
	 * @method modifierResultMulti
	 * @param array $data
	 * @param array $arProp
	 *
	 * @return bool
	 */
	public static function modifierResultMulti(array $data, array $arProp)
	{
		$result = false;
		$arRes = self::getMultiValues($data, $arProp);
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
	 * @param array $data
	 * @param array $arProp
	 *
	 * @return array
	 */
	public static function getMultiValues($data = [], $arProp = [])
	{
		$strSql =  "SELECT ID, VALUE, DESCRIPTION
					FROM b_iblock_element_prop_m" . $arProp['IBLOCK_ID'] . "
						WHERE
							IBLOCK_ELEMENT_ID = " . $data['ID'] . "
						AND IBLOCK_PROPERTY_ID = " . $arProp['ID'] . "
					ORDER BY ID";

		return \Bitrix\Main\Application::getConnection()->query($strSql)->fetchAll();
	}

	/**
	 * @method updateMultiValues
	 * @param array $data
	 * @param array $arProp
	 * @param array $result
	 *
	 * @return \Bitrix\Main\DB\Result
	 */
	public static function updateMultiValues(array $data, $arProp = [], array $result)
	{
		$resUpdate = null;
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
	 * @method getPropSelect - get param propSelect
	 * @return mixed
	 */
	public function getPropSelect()
	{
		return $this->propSelect;
	}

	/**
	 * @method setPropSelect - set param PropSelect
	 * @param mixed $propSelect
	 */
	public function setPropSelect($propSelect)
	{
		$this->propSelect = $propSelect;
	}

	/**
	 * @method getUsePropCache - get param usePropCache
	 * @return boolean
	 */
	public static function getUsePropCache()
	{
		return self::$usePropCache;
	}

	/**
	 * @method setUsePropCache - set param UsePropCache
	 * @param boolean $usePropCache
	 */
	public static function setUsePropCache($usePropCache)
	{
		self::$usePropCache = $usePropCache;
	}

	/**
	 * @method getCachePropId - get param cachePropId
	 * @return string
	 */
	public static function getCachePropId()
	{
		return self::$cachePropId;
	}

	/**
	 * @method setCachePropId - set param CachePropId
	 * @param string $cachePropId
	 */
	public static function setCachePropId($cachePropId)
	{
		self::$cachePropId = $cachePropId;
	}

	/**
	 * @method getCachePropTime - get param cachePropTime
	 * @return int
	 */
	public static function getCachePropTime()
	{
		return self::$cachePropTime;
	}

	/**
	 * @method setCachePropTime - set param CachePropTime
	 * @param int $cachePropTime
	 */
	public static function setCachePropTime($cachePropTime)
	{
		self::$cachePropTime = $cachePropTime;
	}

	/**
	 * @method getCachePropDir - get param cachePropDir
	 * @return string
	 */
	public static function getCachePropDir()
	{
		return self::$cachePropDir;
	}

	/**
	 * @method setCachePropDir - set param CachePropDir
	 * @param string $cachePropDir
	 */
	public static function setCachePropDir($cachePropDir)
	{
		self::$cachePropDir = $cachePropDir;
	}

	/**
	 * @method getEntity - get param entity
	 * @return Entity\Base
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * @method setEntity - set param Entity
	 * @param Entity\Base $entity
	 */
	public function setEntity($entity)
	{
		$this->entity = $entity;
	}

	/**
	 * @method getProperty - get param property
	 * @return mixed
	 */
	public function getProperty()
	{
		return $this->property;
	}

	/**
	 * @method setProperty - set param Property
	 * @param mixed $property
	 */
	public function setProperty($property)
	{
		$this->property = $property;
	}

	/**
	 * @method setAllParams - set param AllParams
	 * @param array $allParams
	 */
	public function setAllParams($allParams)
	{
		$this->allParams = $allParams;
	}

	/**
	 * @method getAllParams - get param allParams
	 * @return array
	 */
	public function getAllParams()
	{
		return $this->allParams;
	}


}
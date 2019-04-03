<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use AB\Tools\Debug;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\Entity;
use Bitrix\Iblock\PropertyTable;
use Online1c\Iblock\Helpers\IblockCache;

class BaseProperty
{
	const CACHE_META = 'meta_property_';
	const REF_PREFIX = '_BIND';

	private $iblockId = null;
	private static $cacheOff = false;

	private $propCode = '*';
	private $selectedProps = [];
	/** @var  Entity\Base */
	private $entityProperty;
	private $enumData = [];
	private $queryProps = null;
	private $usePropertyInfo = false;
	private $metaProps = [];

	/** @var  Entity\Base */
	private $propEntity;

	public function __construct($iblockId, $props = '*')
	{
		$this->iblockId = (int)$iblockId;
		if ($this->iblockId == 0)
			throw new IblockException('iblock for property entity  is null');

		$this->propCode = $props;
	}

	protected function prepareProps()
	{
		$arMetaProps = static::getMetaProperty($this->iblockId);
		$this->metaProps = $arMetaProps;
		if (is_array($this->propCode)){
			foreach ($this->propCode as $code) {
				$code = preg_replace('#'.self::REF_PREFIX.'$#', '', $code);
				if (!empty($arMetaProps[$code])){
					$this->selectedProps[$code] = $arMetaProps[$code];
				}
			}
		} elseif ($this->propCode === '*') {
			$this->selectedProps = $arMetaProps;
		} else {
			throw new IblockException('Неверный формат выбираемых свойств');
		}
	}

	public function compile()
	{
		$this->prepareProps();

		$name = 'ElementProperty'.$this->iblockId;
		if (Entity\Base::isExists(__NAMESPACE__.'\\'.$name)){
			$this->entityProperty = Entity\Base::getInstance(__NAMESPACE__.'\\'.$name);
		} else {
			$this->entityProperty = Entity\Base::compileEntity(
				$name,
				null, [
					'namespace' => __NAMESPACE__,
					'table_name' => 'b_iblock_element_prop_s'.$this->iblockId,
				]
			);
		}


		if (!$this->entityProperty instanceof Entity\Base){
			throw new IblockException('Формат сущности свойств не соответствует стандарту');
		}

		$this->entityProperty->addField(new Entity\IntegerField('IBLOCK_ELEMENT_ID'));

		foreach ($this->selectedProps as $code => $arProperty) {
			$this->addPropertyField($arProperty);
		}

		return $this;
	}

	public function addPropertyField($arProperty)
	{
		$this->addEnumValues($arProperty);

		if ($arProperty['MULTIPLE'] !== 'Y'){

			switch ($arProperty['PROPERTY_TYPE']) {

				case PropertyTable::TYPE_NUMBER:
					$this->entityProperty->addField(new Entity\IntegerField($arProperty['CODE'], [
						'title' => $arProperty['NAME'],
						'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
						'column_name' => 'PROPERTY_'.$arProperty['ID'],
					]));
					break;

				case PropertyTable::TYPE_LIST:
					$this->entityProperty->addField(
						new Entity\IntegerField($arProperty['CODE'], [
							'title' => $arProperty['NAME'],
							'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
							'column_name' => 'PROPERTY_'.$arProperty['ID'],
							'fetch_data_modification' => function () use ($arProperty) {
								return array(
									function ($value) use ($arProperty) {
										if (!empty($this->enumData[$arProperty['ID']][$value])){
											return $this->enumData[$arProperty['ID']][$value];
										}

										return $value;
									},
								);
							},
						])
					);
					$this->entityProperty->addField(new Entity\ReferenceField(
						$arProperty['CODE'].self::REF_PREFIX,
						PropertyEnumerationTable::getEntity(),
						['=this.'.$arProperty['CODE'] => 'ref.ID', 'ref.PROPERTY_ID' => array('?i', $arProperty['ID']),]
					));
					break;

				case PropertyTable::TYPE_SECTION:
					$this->entityProperty->addField(new Entity\IntegerField($arProperty['CODE'], [
						'title' => $arProperty['NAME'],
						'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
						'column_name' => 'PROPERTY_'.$arProperty['ID'],
					]));

					$iblockReferenceId = intval($arProperty['LINK_IBLOCK_ID']);
					if($iblockReferenceId > 0){
						$this->entityProperty->addField(new Entity\ReferenceField(
							$arProperty['CODE'].self::REF_PREFIX,
							SectionTable::getEntity($iblockReferenceId),
							['=this.'.$arProperty['CODE'] => 'ref.ID']
						));
					}
					break;

				case PropertyTable::TYPE_FILE:
					$this->entityProperty->addField(new Entity\IntegerField($arProperty['CODE'], [
						'title' => $arProperty['NAME'],
						'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
						'column_name' => 'PROPERTY_'.$arProperty['ID'],
					]));
					$this->entityProperty->addField(new Entity\ReferenceField(
						$arProperty['CODE'].self::REF_PREFIX,
						FileTable::getEntity(),
						['=this.'.$arProperty['CODE'] => 'ref.ID']
					));
					break;

				case PropertyTable::TYPE_ELEMENT:

					$this->entityProperty->addField(new Entity\IntegerField($arProperty['CODE'], [
						'title' => $arProperty['NAME'],
						'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
						'column_name' => 'PROPERTY_'.$arProperty['ID'],
					]));

					$iblockReferenceId = intval($arProperty['LINK_IBLOCK_ID']);

					if($iblockReferenceId > 0 && $iblockReferenceId !== $this->iblockId){
						$refProps = false;
						foreach ($this->metaProps as $code => $propMetaItem) {
							if(in_array($code, $this->queryProps)){
								$refProps[] = $code;
							}
						}

						$this->entityProperty->addField(new Entity\ReferenceField(
							$arProperty['CODE'].self::REF_PREFIX,
							Element::getEntity($iblockReferenceId, $refProps),
							['=this.'.$arProperty['CODE'] => 'ref.ID']
						));
					}

					break;

				default:
					$this->entityProperty->addField(new Entity\StringField($arProperty['CODE'], [
							'title' => $arProperty['NAME'],
							'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
							'column_name' => 'PROPERTY_'.$arProperty['ID'],
						]
					));
					break;
			}

		} else {
			$UtmPropertyTable = new UtmPropertyTable($this->iblockId, $arProperty['ID']);
			$utmEntity = $UtmPropertyTable->compile()->getUtmEntity();
			$this->entityProperty->addField(new Entity\TextField($arProperty['CODE'], [
				'title' => $arProperty['NAME'],
				'required' => $arProperty['IS_REQUIRED'] == 'Y' ? true : false,
				'column_name' => 'PROPERTY_'.$arProperty['ID'],
//				'serialized' => true,
				'fetch_data_modification' => function () use ($arProperty) {
					return array(
						function ($value, $field, $data, $alias) use ($arProperty) {
							$result = unserialize($value);
							if (count($result['VALUE']) == 0){
								$result = self::modifierResultMulti($data, $arProperty);
							}

							switch ($arProperty['PROPERTY_TYPE']) {
								case PropertyTable::TYPE_LIST:
									foreach ($result['VALUE'] as $idEnum) {
										$result['ENUM'][] = $this->enumData[$arProperty['ID']][$idEnum];
									}
									break;
							}

							return $result;
						},
					);
				},
			]));
			$bindName = 'BIND';
			switch ($arProperty['PROPERTY_TYPE']) {
				case PropertyTable::TYPE_LIST:
					$utmEntity->addField(new Entity\ReferenceField(
						$bindName,
						PropertyEnumerationTable::getEntity(),
						['=this.VALUE' => 'ref.ID']
					));
					break;
				case PropertyTable::TYPE_SECTION:
					$iblockReferenceId = intval($arProperty['LINK_IBLOCK_ID']);
					$utmEntity->addField(new Entity\ReferenceField(
						$bindName,
						SectionTable::getEntity($iblockReferenceId),
						['=this.VALUE' => 'ref.ID']
					));
					break;
				case PropertyTable::TYPE_FILE:
					$utmEntity->addField(new Entity\ReferenceField(
						$bindName,
						FileTable::getEntity(),
						['=this.VALUE' => 'ref.ID']
					));
					break;
				case PropertyTable::TYPE_ELEMENT:
					$utmEntity->addField(new Entity\ReferenceField(
						$bindName,
						Element::getEntity(),
						['=this.VALUE' => 'ref.ID']
					));
//					PR($utmEntity);

					break;
			}

			/*$utmEntity->addField(new Entity\ReferenceField(
				'PROP_UTM',
				$this->entityProperty,
				['=this.IBLOCK_PROPERTY_ID' => array('?i', $arProperty['ID']), '=this.IBLOCK_ELEMENT_ID' => 'ref.IBLOCK_ELEMENT_ID']
			));

			$chains = $this->getCurrentPropChain($arProperty);
			if (is_null($chains) || !$chains) {
				$chains = array('VALUE');
			}
			$cloneChain = $chains;

			$dataClass = get_class($utmEntity->getField('VALUE'));

			$this->entityProperty->addField(new Entity\ExpressionField(
				$arProperty['CODE'].self::REF_PREFIX,
				'%s',
				$utmEntity->getFullName().':'.'PROP_UTM.'.implode('.', $chains)
//				array('data_type' => $dataClass)
			));*/

			$this->entityProperty->addField(new Entity\ReferenceField(
				$arProperty['CODE'].self::REF_PREFIX,
				$utmEntity,
				['ref.IBLOCK_PROPERTY_ID' => array('?i', $arProperty['ID']), '=this.IBLOCK_ELEMENT_ID' => 'ref.IBLOCK_ELEMENT_ID']
			));
			unset($utmEntity);
		}

		if($this->getUsePropertyInfo()){
			$this->entityProperty->getField($arProperty['CODE'])->addFetchDataModifier(function ($value) use($arProperty) {
				$arProperty['VALUE'] = $value;
				return $arProperty;
			});
		}
	}


	private function getCurrentPropChain($arProperty)
	{
		if (!is_null($this->queryProps) && count($this->queryProps) > 0){
			foreach ($this->queryProps as $queryProp) {
				$code = array_shift($queryProp);
				$code = preg_replace('#_BIND$#', '', $code);
				if ($arProperty['CODE'] === $code && count($queryProp) > 0 && $queryProp[0] === 'BIND'){
					return $queryProp;
				}
			}
		}

		return null;
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
		$arRes = static::getMultiValues($data, $arProp);
		if ($arRes){
			foreach ($arRes as $k => $arVal) {
				if (isset($arVal['ENUM']))
					$result['VALUE'][$k] = $arVal['ENUM'];
				else
					$result['VALUE'][$k] = $arVal['VALUE'];
				$result['DESCRIPTION'][$k] = $arVal['DESCRIPTION'];
				$result['ID'][$k] = $arVal['ID'];
			}

			if ($result)
				static::updateMultiValues($data, $arProp, $result);

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
		$strSql = "SELECT ID, VALUE, DESCRIPTION
					FROM b_iblock_element_prop_m".$arProp['IBLOCK_ID']."
						WHERE
							IBLOCK_ELEMENT_ID = ".$data['ID']."
						AND IBLOCK_PROPERTY_ID = ".$arProp['ID']."
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

	protected function addEnumValues($arProperty)
	{
		$cache = new IblockCache('enum_'.$arProperty['ID'], 86400 * 30, '/enums');
		if (self::$cacheOff === false && $cache->isValid()){
			$this->enumData[$arProperty['ID']] = $cache->getData();
		} else {
			$obEnum = PropertyEnumerationTable::getList([
				'filter' => ['=PROPERTY_ID' => $arProperty['ID']],
			]);
			while ($rs = $obEnum->fetch()) {
				$this->enumData[$arProperty['ID']][$rs['ID']] = $rs;
			}
			$cache->addCache($this->enumData[$arProperty['ID']]);
		}
	}

	/**
	 * @method getMetaProperty
	 * @param $iblockId
	 *
	 * @return null
	 * @throws IblockException
	 */
	public static function getMetaProperty($iblockId)
	{
		if (intval($iblockId) == 0)
			throw new IblockException('iblock for meta data is null');

		if(ADMIN_SECTION)
			self::$cacheOff = true;

		$cache = new IblockCache(self::CACHE_META.$iblockId, 86400 * 30, '/props');
		if (self::$cacheOff === false && $cache->isValid()){
			return $cache->getData();
		} else {
			$metaData = null;
			$select = [
				'ID', 'NAME', 'ACTIVE', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'XML_ID',
				'FILE_TYPE', 'LINK_IBLOCK_ID', 'IS_REQUIRED', 'VERSION',
			];
			$select = ['*'];
			$oProps = PropertyTable::getList([
				'select' => $select,
				'filter' => ['=IBLOCK_ID' => $iblockId, '=ACTIVE' => 'Y'],
			]);
			while ($prop = $oProps->fetch()) {
				$metaData[$prop['CODE']] = $prop;
			}
			if(!ADMIN_SECTION)
				$cache->addCache($metaData);

			return $metaData;
		}
	}

	/**
	 * @method getEntityProperty - get param entityProperty
	 * @return Entity\Base
	 */
	public function getEntityProperty()
	{
		return $this->entityProperty;
	}

	/**
	 * @method getQueryProps - get param queryProps
	 * @return null
	 */
	public function getQueryProps()
	{
		return $this->queryProps;
	}

	/**
	 * @param null $queryProps
	 *
	 * @return BaseProperty
	 */
	public function setQueryProps($queryProps)
	{
		$this->queryProps = $queryProps;

		return $this;
	}

	/**
	 * @method getUsePropertyInfo - get param usePropertyInfo
	 * @return bool
	 */
	public function getUsePropertyInfo()
	{
		return $this->usePropertyInfo;
	}

	/**
	 * @param bool $usePropertyInfo
	 *
	 * @return BaseProperty
	 */
	public function setUsePropertyInfo($usePropertyInfo)
	{
		$this->usePropertyInfo = $usePropertyInfo;

		return $this;
	}


}
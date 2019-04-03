<?php namespace Soft;

use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use PW\Tools\Debug;
use UL\Products\ProductList;

/**
 * Class BaseController
 * @package Alvitek\Blocks
 */
class BaseController extends BaseQuery
{
	protected $iblock = false;
	protected $params;
	protected $mapElement;
	/** @var Entity\Base */
	protected $entityElement;

	private static $operandsInProp = '<>=!%@';

	const mainEntityName = 'Element';

	/**
	 * BaseController constructor.
	 *
	 * @param array $params
	 */
	public function __construct(array $params = [])
	{
		Loader::includeModule('iblock');

		if(isset($params['filter']) && intval($params['filter']['IBLOCK_ID']) > 0){
			$this->iblock = $params['filter']['IBLOCK_ID'];
		}

		$this->params = $params;
		$this->mapElement = IblockElementTable::getMap();
	}

	/**
	 * @method init
	 *
	 * @return Entity\Base
	 */
	public function init()
	{
		$params = $this->getParams();

		$linkEntityName = 'Element'.$params['filter']['IBLOCK_ID'];

		$this->entityElement = self::compileEntityElement($linkEntityName);

		$filter = $this->prepareFilterProp(array_keys($this->getParams('filter')));

		$this->prepareParam($filter);
		$this->prepareParam(array_keys($this->getParams('order')));
		$this->prepareParam(array_values($this->getParams('select')));
		$this->prepareParam(array_values($this->getParams('group')));

		unset($this->mapElement);

		$Property = new Property($this->getPropertyFields(), $params);

		$propertyEntity = $Property->compileProperty($params['filter']['IBLOCK_ID']);

		$this->entityElement->addField(new Entity\ReferenceField(
				'PROPERTY',
				$propertyEntity['ENTITY'],
				array('ref.IBLOCK_ELEMENT_ID' => 'this.ID'),
				array('join_type' => 'LEFT')
		));
		$this->setParams($propertyEntity['PARAMETERS']);

		self::addEnumProperty($propertyEntity['ENUMS']);

		return $this->entityElement;
	}

	/**
	 * @method appendQuery
	 * @param Entity\Base $entity
	 *
	 * @return Entity\Query
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function appendQuery(Entity\Base $entity)
	{
		$query = new Entity\Query($entity);
		foreach($this->getParams() as $param => $value) {
			switch($param) {
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
					foreach ($value as $name => $fieldInfo) {
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
					throw new \Bitrix\Main\ArgumentException("Unknown parameter: ".$param, $param);
			}
		}

		return $query;
	}

	private static function delOperands($fieldName)
	{
		return preg_replace('/^['.self::$operandsInProp.']+/i','', $fieldName);
	}

	/**
	 * @method prepareParam
	 * @param array $arParam
	 */
	protected function prepareParam($arParam = [])
	{
		foreach ($arParam as $value) {
			$arVal = explode('.', $value);
			$this->addPropField($arVal);
		}
	}

	private static function getLogicVal(array $filter, &$result = array())
	{
		foreach ($filter as $key => $val) {
			if(is_array($val) && is_int($key)){
				self::getLogicVal($val, $result);
			} else {
				$result[$key] = $val;
			}
		}
		if(count($result) > 0){
			unset($result['LOGIC']);
			return $result;
		}
		else
			return null;
	}

	private function prepareFilterProp(&$filter, $value = NULL) {
		$value = $value?:$filter;
		foreach ($value as $k => $v) {
			if (is_numeric($k) && $k == intval($k) && is_array($v) && count($v) > 0) {
				unset($filter[$k]);
				$this->prepareFilterProp($filter, $v);
			} else {
				$filter[$k] = $v;
			}
		}
		return $filter;
	}

	/**
	 * @method addPropField
	 * @param array $arValues
	 * @param bool|false $alias
	 */
	protected function addPropField($arValues = [], $alias = false)
	{
		if(count($arValues) > 1 && $arValues[0] == 'PROPERTY'){
			array_shift($arValues);
			if(count($arValues) == 1){

				if(intval($alias) > 0 || !$alias){
					$alias = $arValues[0];
				}
				if(!is_array(self::getPropField($alias)))
					self::addPropertyFields($alias, $arValues[0]);
			} else {
				$k = $arValues[0];
				unset($arValues[0]);

				if(intval($alias) > 0 || !$alias){
					$alias = $k;
				}

				self::addPropertyFields($alias, $arValues);
			}
		} else {
			$fieldName = $arValues[0];
			if(array_key_exists($fieldName, $this->mapElement)){
				try{
					$this->entityElement->getField($fieldName);
				} catch(\Exception $e){
					$this->entityElement->addField($this->mapElement[$fieldName]);
				}
			}
		}

		$this->entityElement->addField(new Entity\IntegerField('IBLOCK_SECTION_ID'));
	}

	/**
	 * @method getParams
	 * @param string $key
	 *
	 * @return array
	 */
	public function getParams($key = ''){
		if(strlen($key) > 0){
			if(!is_array($this->params[$key]))
				$this->params[$key] = [];

			return $this->params[$key];
		}
		return $this->params;
	}

	/**
	 * @method setParams - set param Params
	 * @param array $params
	 */
	public function setParams($params)
	{
		if(intval($params['filter']['IBLOCK_ID']) > 0){
			$this->iblock = $params['filter']['IBLOCK_ID'];
		}

		$this->params = $params;
	}

	/**
	 * @method compileEntityElement
	 * @param string $name
	 *
	 * @return Entity\Base
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function compileEntityElement($name = '')
	{
		if(strlen($name) == 0){
			$name = self::mainEntityName;
		}


		$fullName = '\\'.__NAMESPACE__.'\\'.$name;
		if(Entity\Base::isExists($fullName)){
			$entity = Entity\Base::getInstance($fullName);
		} else {
			$entity = Entity\Base::compileEntity(
					$name,
					\Bitrix\Iblock\ElementTable::getMap(),
					['namespace'=>__NAMESPACE__, 'table_name'=>'b_iblock_element']
			);
		}

		return $entity;
	}

	/**
	 * @method getEntityElement - get param entityElement
	 * @return Entity\Base
	 */
	public function getEntityElement()
	{
		return $this->entityElement;
	}

	/**
	 * @method getIblock - get param iblock
	 * @return boolean|int
	 */
	public function getIblock()
	{
		return $this->iblock;
	}
}
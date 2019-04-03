<?php namespace Esd\Tools\Grid;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;
use Esd\Debug;
use Esd\HL\MainTable;
use PW\Tools\Generator\Map;

Loader::includeModule('esd.hl');
Loader::includeModule('soft.iblock');

/**
 * Class Grids
 * @package Esd\Tools\Grid
 */
class Grids
{
	protected $arParams = [];
	protected $tableName;
	protected $filter = [];
	protected $sort = [];
	protected $select = [];
	/** @var  Entity\Base */
	protected $entity;
	protected $entityName;
	protected $limit = 20;
	protected $query;


	public function __construct(array $arParams = [], $component = false)
	{
		if(count($arParams['SELECT']) == 0){
			$this->select = ['*'];
		} else {
			$this->select = $arParams['SELECT'];
		}

		if(intval($arParams['LIMIT']) > 0){
			$this->limit = $arParams['LIMIT'];
		}

		if(count($arParams['FILTER']) > 0)
			$this->filter = $arParams['FILTER'];

		if(count($arParams['SORT']) > 0){
			foreach ($arParams['SORT'] as $order => $sort) {
				$this->sort[$order] = $sort;
			}
		}

		$this->arParams = $arParams;
	}

	public function createEntity($table)
	{
		$table = strtolower($table);
		if (intval($table) > 0){
			$MainTable = new MainTable($table);
			$this->entity = $MainTable->getHLEntity();
		} elseif(preg_match('#^[a-z_]+#', $table)){
			$this->tableName = trim($table);
			$Generator = new Map(array('table'=>$this->tableName));
			$this->entityName = Entity\Base::snake2camel($this->tableName);

			if(!Entity\Base::isExists($this->entityName)){
				$this->entity = Entity\Base::compileEntity(
					$this->entityName,
					$Generator->generator(),
					['namespace'=>__NAMESPACE__]
				);
			} else {
				$this->entity = Entity\Base::getInstance($this->entityName);
			}
		}
	}

	public function getGridList()
	{
//		Debug::startSql();
		$this->query = new Entity\Query($this->entity);

		if(count($this->arParams['REFERENCE']) > 0){
			foreach ($this->arParams['REFERENCE'] as $ref){
				$this->query->registerRuntimeField(null, $ref);
			}
		}
		$obList = $this->query
			->setSelect($this->select)
			->setFilter($this->filter)
			->setOrder($this->sort)
			->setLimit($this->limit)
			->exec();
//		Debug::getSql($obList);

		PR($obList->fetchAll());
	}

	/**
	 * @method getFilter - get param filter
	 * @return array
	 */
	public function getFilter()
	{
		return $this->filter;
	}

	/**
	 * @method setFilter - set param Filter
	 * @param array $filter
	 */
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}

	/**
	 * @method getSort - get param sort
	 * @return array
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @method setSort - set param Sort
	 * @param array $sort
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;
	}

	/**
	 * @method getSelect - get param select
	 * @return array
	 */
	public function getSelect()
	{
		return $this->select;
	}

	/**
	 * @method setSelect - set param Select
	 * @param array $select
	 */
	public function setSelect($select)
	{
		$this->select = $select;
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
	 * @method getEntityName - get param entityName
	 * @return mixed
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}

	/**
	 * @method setEntityName - set param EntityName
	 * @param mixed $entityName
	 */
	public function setEntityName($entityName)
	{
		$this->entityName = $entityName;
	}

	/**
	 * @method getLimit - get param limit
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * @method setLimit - set param Limit
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	/**
	 * @method getQuery - get param query
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @method setQuery - set param Query
	 * @param mixed $query
	 */
	public function setQuery($query)
	{
		$this->query = $query;
	}

	/**
	 * @method getTableName - get param tableName
	 * @return mixed
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * @method setTableName - set param TableName
	 * @param mixed $tableName
	 */
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

	/**
	 * @method addParam
	 * @param $key
	 * @param $value
	 */
	public function addParam($key, $value)
	{
		$this->arParams[$key] = $value;
	}
}
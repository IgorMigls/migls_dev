<?php namespace Soft\IBlock;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Entity;

/**
 * Class Query
 * @package Soft\IBlock
 */
class Query extends Entity\Query
{
	private static $operandsInProp = '<>=!%@';

	/**
	 * @method exec
	 * @return \Bitrix\Main\DB\Result
	 */
	public function exec()
	{
		$isProp = false;
		$filter = $this->getFilter();
		$iBlock = intval($filter['IBLOCK_ID']);

		$arParamQuery = $this->prepareQueryProp();

		$propertyEntity = null;
		if(!is_null($arParamQuery) && $iBlock > 0){
			$isProp = true;
			$propertyEntity = ElementTable::compileEntityProperty($iBlock, $arParamQuery, $this);
		}

		if($propertyEntity instanceof Entity\Base){
			$this->entity->addField(new Entity\ReferenceField(
				'PROPERTY',
				$propertyEntity,
				array(
					'ref.IBLOCK_ELEMENT_ID' => 'this.ID',
				),
				array('join_type' => 'LEFT')
			));
		}

		return parent::exec();
	}

	public function setPaging(array $params, Query $query)
	{
		$offset = intval($params['offset']);
		if($offset == 0)
			throw new ArgumentException('offset param should be integer');

		$pagingGet = isset($params['paging']) ? $params['paging'] : 'page';

		$queryCnt = clone $query;
		$queryCnt->registerRuntimeField(false, new Entity\ExpressionField('CNT', 'COUNT(DISTINCT %s)', array('ID')));
		$queryCnt->setSelect(array('CNT'));
		$arCnt = $queryCnt->exec()->fetch();
	}

	public function delSelect($key = '')
	{
		if($keySelect = array_search($key, $this->select)){
			unset($this->select[$keySelect]);
		}
	}

	private static function delOperands($fieldName)
	{
		return preg_replace('/^['.self::$operandsInProp.']+/i','', $fieldName);
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

	private function prepareQueryProp()
	{
		$filter = $this->getFilter();
		$order = $this->getOrder();
		$select = $this->getSelect();
		$group = $this->getGroup();

		if(!array_key_exists('WF_PARENT_ELEMENT_ID', $filter)){
			$this->addFilter('WF_PARENT_ELEMENT_ID', false);
		}
		$this->prepareFilterProp($filter);

		$arParamQuery = null;
		$filterTmp = array_merge(array_keys($filter), array_keys($order));
		$selectTmp = array_merge(array_values($select), array_values($group));

		$arParamQueryTmp = array_unique(array_merge($filterTmp, $selectTmp));

		foreach ($arParamQueryTmp as $paramCode) {
			if(preg_match('/PROPERTY/i', $paramCode)){
				$codeProp = self::delOperands($paramCode);
				$codeProp = preg_replace('/^PROPERTY./', '', $codeProp);
				$arParamQuery[] = $codeProp;
			}
		}

		return $arParamQuery;
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

}
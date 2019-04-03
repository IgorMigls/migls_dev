<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 02.08.2016
 * Time: 12:46
 */

namespace UL\Main\Services;

use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use UL\Main\MysqlHelper;

class FavoriteTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return null
	 */
	public static function getTableName()
	{
		return 'ul_favorites';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		global $USER;

		return [
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'ELEMENT_ID' => new Entity\IntegerField('ELEMENT_ID', array(
				'required' => true,
			)),
			'USER_ID' => new Entity\IntegerField('USER_ID', array(
				'required' => true,
				'default_value' => $USER->GetID()
			)),
			'DATE_X' => new Entity\DatetimeField('DATE_X', array(
				'default_value' => new Type\DateTime(),
			)),
			'LIST_ID' => new Entity\IntegerField('LIST_ID'),
			'LIST' => new Entity\ReferenceField(
				'LIST',
				ListTable::getEntity(),
				['=this.LIST_ID' => 'ref.ID']
			),
			'SHOP_ID' => new Entity\IntegerField('SHOP_ID', array()),
		];
	}

	/**
	 * @method add
	 * @param array $data
	 *
	 * @return Entity\AddResult
	 */
	public static function add(array $data)
	{
		$res = parent::add($data);
		if($res->isSuccess() && intval($data['LIST_ID']) > 0){
			$list['CNT_PRODUCTS'] = self::getCountInList($data['LIST_ID']);
			ListTable::update($data['LIST_ID'], $list);
		}

		return $res;
	}

	/**
	 * @method update
	 * @param mixed $primary
	 * @param array $data
	 *
	 * @return Entity\UpdateResult
	 */
	public static function update($primary, array $data)
	{
		$res = parent::update($primary, $data);
		if($res->isSuccess() && intval($data['LIST_ID']) > 0){
			$list['CNT_PRODUCTS'] = self::getCountInList($data['LIST_ID']);
			ListTable::update($data['LIST_ID'], $list);
		}

		return $res;
	}

	/**
	 * @method getCountInList
	 * @param $listId
	 *
	 * @return int
	 */
	public static function getCountInList($listId)
	{
		global $USER;
		$arCnt = FavoriteTable::getRow([
			'filter' => ['=USER_ID' => $USER->GetID(), '=LIST_ID' => $listId],
			'select' => [new Entity\ExpressionField('CNT', 'COUNT(*)')],
		]);

		return intval($arCnt['CNT']);
	}

	/**
	 * @method delete
	 * @param mixed $primary
	 *
	 * @return Entity\DeleteResult
	 * @throws \Exception
	 */
	public static function delete($primary)
	{
		$row = parent::getRowById($primary);
		$listId = $row['LIST_ID'];
		$res = parent::delete($primary);

		if($res->isSuccess() && intval($listId) > 0){
			$cnt = self::getCountInList($listId);
			ListTable::update($listId, ['CNT_PRODUCTS' => $cnt]);
		}

		return $res;
	}


	/**
	 * @method createTable
	 */
	public static function createTable()
	{
		MysqlHelper::createTable(self::getEntity(), [
			'ix_fav_userId' => ['USER_ID'],
			'ix_fav_listId' => ['LIST_ID', 'USER_ID'],
		]);
	}
}
<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 23.06.17
 */

namespace UL\Main\Search;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class SearchTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_search_content';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('CONTENT_ENTITY_ID_FIELD'),
			),
			'DATE_CHANGE' => array(
				'data_type' => 'datetime',
				'required' => true,
				'title' => Loc::getMessage('CONTENT_ENTITY_DATE_CHANGE_FIELD'),
			),
			'MODULE_ID' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateModuleId'),
				'title' => Loc::getMessage('CONTENT_ENTITY_MODULE_ID_FIELD'),
			),
			'ITEM_ID' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateItemId'),
				'title' => Loc::getMessage('CONTENT_ENTITY_ITEM_ID_FIELD'),
			),
			'CUSTOM_RANK' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('CONTENT_ENTITY_CUSTOM_RANK_FIELD'),
			),
			'USER_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CONTENT_ENTITY_USER_ID_FIELD'),
			),
			'ENTITY_TYPE_ID' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateEntityTypeId'),
				'title' => Loc::getMessage('CONTENT_ENTITY_ENTITY_TYPE_ID_FIELD'),
			),
			'ENTITY_ID' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateEntityId'),
				'title' => Loc::getMessage('CONTENT_ENTITY_ENTITY_ID_FIELD'),
			),
			'URL' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_URL_FIELD'),
			),
			'TITLE' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_TITLE_FIELD'),
			),
			'BODY' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_BODY_FIELD'),
			),
			'TAGS' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_TAGS_FIELD'),
			),
			'PARAM1' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_PARAM1_FIELD'),
			),
			'PARAM2' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_ENTITY_PARAM2_FIELD'),
			),
			'UPD' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateUpd'),
				'title' => Loc::getMessage('CONTENT_ENTITY_UPD_FIELD'),
			),
			'DATE_FROM' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('CONTENT_ENTITY_DATE_FROM_FIELD'),
			),
			'DATE_TO' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('CONTENT_ENTITY_DATE_TO_FIELD'),
			),
		);
	}
	/**
	 * Returns validators for MODULE_ID field.
	 *
	 * @return array
	 */
	public static function validateModuleId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for ITEM_ID field.
	 *
	 * @return array
	 */
	public static function validateItemId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for ENTITY_TYPE_ID field.
	 *
	 * @return array
	 */
	public static function validateEntityTypeId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for ENTITY_ID field.
	 *
	 * @return array
	 */
	public static function validateEntityId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for UPD field.
	 *
	 * @return array
	 */
	public static function validateUpd()
	{
		return array(
			new Main\Entity\Validator\Length(null, 32),
		);
	}
}
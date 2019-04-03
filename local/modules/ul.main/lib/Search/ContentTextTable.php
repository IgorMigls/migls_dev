<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 23.06.17
 */

namespace UL\Main\Search;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class ContentTextTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_search_content_text';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'SEARCH_CONTENT_ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'title' => Loc::getMessage('CONTENT_TEXT_ENTITY_SEARCH_CONTENT_ID_FIELD'),
			),
			'SEARCH_CONTENT_MD5' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateSearchContentMd5'),
				'title' => Loc::getMessage('CONTENT_TEXT_ENTITY_SEARCH_CONTENT_MD5_FIELD'),
			),
			'SEARCHABLE_CONTENT' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('CONTENT_TEXT_ENTITY_SEARCHABLE_CONTENT_FIELD'),
			),
			'CONTENT' => array(
				'data_type' => SearchTable::getEntity(),
				'reference' => array('=this.SEARCH_CONTENT_ID' => 'ref.ID'),
			),
		);
	}
	/**
	 * Returns validators for SEARCH_CONTENT_MD5 field.
	 *
	 * @return array
	 */
	public static function validateSearchContentMd5()
	{
		return array(
			new Main\Entity\Validator\Length(null, 32),
		);
	}
}
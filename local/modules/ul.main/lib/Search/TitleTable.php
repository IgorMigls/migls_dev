<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 23.06.17
 */

namespace UL\Main\Search;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;

class TitleTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_search_content_title';
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
				'title' => Loc::getMessage('CONTENT_TITLE_ENTITY_SEARCH_CONTENT_ID_FIELD'),
			),
			'SITE_ID' => array(
				'data_type' => 'string',
				'primary' => true,
				'validation' => array(__CLASS__, 'validateSiteId'),
				'title' => Loc::getMessage('CONTENT_TITLE_ENTITY_SITE_ID_FIELD'),
			),
			'POS' => array(
				'data_type' => 'integer',
				'primary' => true,
				'title' => Loc::getMessage('CONTENT_TITLE_ENTITY_POS_FIELD'),
			),
			'WORD' => array(
				'data_type' => 'string',
				'primary' => true,
				'validation' => array(__CLASS__, 'validateWord'),
				'title' => Loc::getMessage('CONTENT_TITLE_ENTITY_WORD_FIELD'),
			),
			'SITE' => array(
				'data_type' => 'Bitrix\Lang\Lang',
				'reference' => array('=this.SITE_ID' => 'ref.LID'),
			),
			'SEARCH_CONTENT' => array(
				'data_type' => SearchTable::getEntity(),
				'reference' => array('=this.SEARCH_CONTENT_ID' => 'ref.ID'),
			),
		);
	}
	/**
	 * Returns validators for SITE_ID field.
	 *
	 * @return array
	 */
	public static function validateSiteId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 2),
		);
	}
	/**
	 * Returns validators for WORD field.
	 *
	 * @return array
	 */
	public static function validateWord()
	{
		return array(
			new Main\Entity\Validator\Length(null, 100),
		);
	}
}
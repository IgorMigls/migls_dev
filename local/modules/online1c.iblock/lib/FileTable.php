<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 26.04.2017
 */

namespace Online1c\Iblock;

use Bitrix\Main\Application;
use Bitrix\Main\Type;
use Bitrix\Main\Entity;
use Bitrix\Main\NotImplementedException;

class FileTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 'b_file';
	}

	public static function getMap()
	{

		$connection = Application::getConnection();
		$helper = $connection->getSqlHelper();

		return array(
			'ID' => new Entity\IntegerField('ID', array(
				'primary' => true,
				'autocomplete' => true,
			)),
			'TIMESTAMP_X' => new Entity\DatetimeField('TIMESTAMP_X', array(
				'default_value' => new Type\DateTime
			)),
			'MODULE_ID' => new Entity\StringField('MODULE_ID', array(
				'validation' => array(__CLASS__, 'validateModuleId'),
			)),
			'HEIGHT' => new Entity\IntegerField('HEIGHT'),
			'WIDTH' => new Entity\IntegerField('WIDTH'),

			'FILE_SIZE' => new Entity\IntegerField('FILE_SIZE'),
			'CONTENT_TYPE' => new Entity\StringField('CONTENT_TYPE', array(
				'validation' => array(__CLASS__, 'validateContentType'),
			)),
			'SUBDIR' => new Entity\StringField('SUBDIR', array(
				'validation' => array(__CLASS__, 'validateSubdir'),
			)),
			'FILE_NAME' => new Entity\StringField('FILE_NAME', array(
				'validation' => array(__CLASS__, 'validateFileName'),
				'required' => true,
			)),
			'ORIGINAL_NAME' => new Entity\StringField('ORIGINAL_NAME', array(
				'validation' => array(__CLASS__, 'validateOriginalName'),
			)),
			'DESCRIPTION' => new Entity\StringField('DESCRIPTION', array(
				'validation' => array(__CLASS__, 'validateDescription'),
			)),
			'HANDLER_ID' => new Entity\StringField('HANDLER_ID', array(
				'validation' => array(__CLASS__, 'validateHandlerId'),
			)),
			'EXTERNAL_ID' => new Entity\StringField('EXTERNAL_ID', array(
				'validation' => array(__CLASS__, 'validateExternalId'),
			)),
			'SRC' => new Entity\ExpressionField(
				'SRC',
				$helper->getConcatFunction("'/upload/'", "%s", "'/'", "%s"),
				['SUBDIR', 'ORIGINAL_NAME']
			),
			'FORMAT_SIZE' => new Entity\ExpressionField(
				'FORMAT_SIZE',
				'%s',
				['FILE_SIZE'],
				['fetch_data_modification' => function () {
					return array(
						function ($value) {
							return \CFile::FormatSize($value);
						}
					);
				}]
			)
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
			new Entity\Validator\Length(null, 50),
		);
	}

	/**
	 * Returns validators for CONTENT_TYPE field.
	 *
	 * @return array
	 */
	public static function validateContentType()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * Returns validators for SUBDIR field.
	 *
	 * @return array
	 */
	public static function validateSubdir()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * Returns validators for FILE_NAME field.
	 *
	 * @return array
	 */
	public static function validateFileName()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * Returns validators for ORIGINAL_NAME field.
	 *
	 * @return array
	 */
	public static function validateOriginalName()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * Returns validators for DESCRIPTION field.
	 *
	 * @return array
	 */
	public static function validateDescription()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	/**
	 * Returns validators for HANDLER_ID field.
	 *
	 * @return array
	 */
	public static function validateHandlerId()
	{
		return array(
			new Entity\Validator\Length(null, 50),
		);
	}

	/**
	 * Returns validators for EXTERNAL_ID field.
	 *
	 * @return array
	 */
	public static function validateExternalId()
	{
		return array(
			new Entity\Validator\Length(null, 50),
		);
	}

	/**
	 * Adds row to entity table
	 *
	 * @param array $data
	 *
	 * @return Entity\AddResult Contains ID of inserted row
	 *
	 * @throws \Exception
	 */
	public static function add(array $data)
	{
		throw new NotImplementedException("Use CFile class.");
	}

	/**
	 * Updates row in entity table by primary key
	 *
	 * @param mixed $primary
	 * @param array $data
	 *
	 * @return Entity\UpdateResult
	 *
	 * @throws \Exception
	 */
	public static function update($primary, array $data)
	{
		throw new NotImplementedException("Use CFile class.");
	}

	/**
	 * Deletes row in entity table by primary key
	 *
	 * @param mixed $primary
	 *
	 * @return Entity\DeleteResult
	 *
	 * @throws \Exception
	 */
	public static function delete($primary)
	{
		throw new NotImplementedException("Use CFile class.");
	}

}
<?php namespace Soft;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class FileTable
 * @package Alvitek\Blocks
 */
class FileTable extends Entity\DataManager
{
	/**
	 * @method getTableName
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_file';
	}

	/**
	 * @method getMap
	 * @return array
	 */
	public static function getMap()
	{
		$map = array(
			'ID' => new Entity\IntegerField('ID', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_ID_FIELD'),
					'primary' => true,
					'autocomplete' => true,
				)
			),
			'TIMESTAMP_X' => new Entity\DatetimeField('TIMESTAMP_X', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_TIMESTAMP_X_FIELD'),
					'required' => true,
				)
			),
			'MODULE_ID' => new Entity\StringField('MODULE_ID', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_MODULE_ID_FIELD'),
				)
			),
			'HEIGHT' => new Entity\IntegerField('HEIGHT', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_HEIGHT_FIELD'),
				)
			),
			'WIDTH' => new Entity\IntegerField('WIDTH', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_WIDTH_FIELD'),
				)
			),
			'FILE_SIZE' => new Entity\StringField('FILE_SIZE', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_FILE_SIZE_FIELD'),
					'fetch_data_modification'=>function() {
						return [
							function ($value, $field, $data, $alias) {
								if(strlen($value) > 0){
									$result = [
										'SIZE'=>$value,
										'FORMAT_SIZE'=>\CFile::FormatSize($value)
									];
								} else {
									$result = $value;
								}
								return $result;
							}
						];
					}
				)
			),
			'CONTENT_TYPE' => new Entity\StringField('CONTENT_TYPE', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_CONTENT_TYPE_FIELD'),
				)
			),
			'SUBDIR' => new Entity\StringField('SUBDIR', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_SUBDIR_FIELD'),
				)
			),
			'FILE_NAME' => new Entity\StringField('FILE_NAME', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_FILE_NAME_FIELD'),
					'required' => true,
				)
			),
			'ORIGINAL_NAME' => new Entity\StringField('ORIGINAL_NAME', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_ORIGINAL_NAME_FIELD'),
				)
			),
			'DESCRIPTION' => new Entity\StringField('DESCRIPTION', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_DESCRIPTION_FIELD'),
				)
			),
			'HANDLER_ID' => new Entity\StringField('HANDLER_ID', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_HANDLER_ID_FIELD'),
				)
			),
			'EXTERNAL_ID' => new Entity\StringField('EXTERNAL_ID', array(
					'title' => Loc::getMessage('PW_FILES_ENTITY_EXTERNAL_ID_FIELD'),
				)
			),
		);

		return $map;
	}

    
	public static function validateModuleId ()
	{
		return array(
			new Entity\Validator\Length(null, 50),
		);
	}

	public static function validateFileSize ()
	{
		return array(
			new Entity\Validator\Length(null, 20),
		);
	}

	public static function validateContentType ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateSubdir ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateFileName ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateOriginalName ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateDescription ()
	{
		return array(
			new Entity\Validator\Length(null, 255),
		);
	}

	public static function validateHandlerId ()
	{
		return array(
			new Entity\Validator\Length(null, 50),
		);
	}

	public static function validateExternalId ()
	{
		return array(
			new Entity\Validator\Length(null, 50),
		);
	}

}
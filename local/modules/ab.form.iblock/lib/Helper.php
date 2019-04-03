<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 21.09.2016
 * Time: 19:17
 */

namespace AB\FormIblock;


class Helper
{
	protected static $ibFields = [
		'NAME' => [
			'NAME' => 'Название',
			'CODE' => 'NAME',
			'SORT' => 100500,
			'REQUIRED' => 'Y'
		],
		'CODE' => [
			'NAME' => 'Код',
			'CODE' => 'CODE',
			'SORT' => 100510,
		],
		'PREVIEW_TEXT' => [
			'NAME' => 'Сообщение',
			'CODE' => 'PREVIEW_TEXT',
			'SORT' => 100520,
		],
		'DETAIL_TEXT' => [
			'NAME' => 'Детальное описание',
			'CODE' => 'DETAIL_TEXT',
			'SORT' => 100530,
		],
		'PREVIEW_PICTURE' => [
			'NAME' => 'Фото',
			'CODE' => 'PREVIEW_PICTURE',
			'SORT' => 100540,
		],
		'DETAIL_PICTURE' => [
			'NAME' => 'Детальное фото',
			'CODE' => 'DETAIL_PICTURE',
			'SORT' => 100550,
		],
	];

	/**
	 * @method getIbFields - get param ibFields
	 * @param $field
	 *
	 * @return array
	 */
	public static function getIbFields($field = '')
	{
		if(isset(self::$ibFields[$field]))
			return self::$ibFields[$field];

		return self::$ibFields;
	}

}
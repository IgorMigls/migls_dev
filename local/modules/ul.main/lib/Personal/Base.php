<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 15.08.2016
 * Time: 17:53
 */

namespace UL\Main\Personal;


class Base
{
	/** @var int */
	protected static $userId = null;

	/**
	 * Profile constructor.
	 */
	public function __construct()
	{
		static::getUser();
	}

	/**
	 * @method generateMonths
	 * @return array
	 */
	public static function generateMonths()
	{
		$arMonths = [
			'Январь',
			'Февраль',
			'Март',
			'Апрель',
			'Май',
			'Июнь',
			'Июль',
			'Август',
			'Сентябрь',
			'Октябрь',
			'Ноябрь',
			'Декабрь',
		];

		return $arMonths;
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public static function getUser()
	{
		global $USER;

		return $USER;
	}

	/**
	 * @method getUserId
	 * @return int
	 */
	public static function getUserId()
	{
		if (is_null(self::$userId) && self::getUser()->IsAuthorized()){
			self::$userId = self::getUser()->GetID();
		}

		return self::$userId;
	}
}
<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 31.01.18
 */

namespace GrandMaster\Console;


class Builder
{
	private static $scripts = [];

	/**
	 * @method getScripts - get param scripts
	 * @return array
	 */
	public static function getScripts()
	{
		return self::$scripts;
	}

	/**
	 * @method setScripts - set param Scripts
	 * @param array $scripts
	 */
	public static function setScripts($scripts)
	{
		self::$scripts = $scripts;
	}

}
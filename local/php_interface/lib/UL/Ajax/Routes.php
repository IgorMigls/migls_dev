<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 14.07.2016
 * Time: 15:11
 */

namespace UL\Ajax;


class Routes
{
	protected static $rout = [];
	/** @var Routes */
	private static $instance = null;

	/**
	 * @method getInstance
	 * @return Routes
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new Routes();
		}

		return self::$instance;
	}

	/**
	 * @method getRout
	 * @param string $class
	 * @param string $nameSpace
	 *
	 * @return array
	 * @throws ExceptionAjax
	 */
	public static function getRout($nameSpace ='', $class)
	{
		$result = false;

		if(strlen($nameSpace) == 0){
			$nameSpace = '\\';
		}

		if(strlen($class) > 0 && strlen($nameSpace) > 0){
			$id = md5($nameSpace.$class);
			foreach (self::$rout as $item) {
				if($item[$id]){
					$result = $item[$id];
					break;
				}
			}
		}

		if(!$result)
			throw new ExceptionAjax('Route in not exist');

		return $result;
	}

	public function getAllRoutes()
	{
		return self::$rout;
	}

	/**
	 * @method setRout - set param Rout
	 * @param array $rout
	 */
	public static function setRout($rout)
	{
		self::$rout = $rout;
	}

	public function addRout($param = [])
	{
		if(strlen($param['NAMESPACE']) == 0){
			$param['NAMESPACE'] = 'GLOBAL';
		}

		if(substr($param['NAMESPACE'], 0, 1) != '\\'){
			$param['NAMESPACE'] = '\\'.$param['NAMESPACE'];
		}

		if(strlen($param['CLASS']) == 0)
			$param['CLASS'] = '\\';

		if(strlen($param['ACTION']) == 0 && strlen($param['ACTIONS']))
			throw new ExceptionAjax('Parameter ACTION is empty');

		$id = md5($param['NAMESPACE'].$param['CLASS']);

		$param['ID'] = $id;

		self::$rout[$param['NAMESPACE']][$id] = $param;

		return $this;
	}
}
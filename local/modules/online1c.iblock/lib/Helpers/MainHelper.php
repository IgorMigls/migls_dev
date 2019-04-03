<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.02.2017
 */

namespace Online1c\Iblock\Helpers;

use Bitrix\Main\Type\Dictionary;
use Online1c\Iblock\IblockException;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class MainHelper
{
	const FILTER_OPERANDS = ['<','>','=','!','%','@'];

	public static function minimizeInputParams(array $arParams = [])
	{
		$data = [];
		foreach ($arParams as $code => $param) {
			switch ($code) {
				case 'select':
				case 'group':
					$data = array_merge($data, array_values($param));
					break;
				case 'filter':
					$data = array_merge($data, self::minimizeFilter($param));
					break;
				case 'order':
					$data = array_merge($data, array_keys($param));
					break;
			}
		}

		if(!is_array($data) || count($data) == 0){
			throw new IblockException(Loc::getMessage('ONLINE1C_IBLOCK_EX_NOT_PARAMS'), 100);
		}

		return array_unique($data);
	}

	private static function minimizeFilter(array $filter = [])
	{
		$result = [];

		foreach ($filter as $code => $value) {
			if((!is_array($value) && !is_numeric($code)) || is_array($value) ){
				$result[] = str_replace(self::FILTER_OPERANDS, '', $code);
			} elseif (is_array($value) && $value['LOGIC']){
				unset($value['LOGIC']);
				$result = array_merge($result, self::minimizeFilter($value));
			}
		}

		return $result;
	}

	/**
	 * @method getParamCodes
	 * @param array $params
	 *
	 * @return array
	 */
	public static function getParamCodes($params = [])
	{
		$props = $scalar = [];
		foreach ($params as $param) {
			$tmpParam = explode('.', $param);
			if(count($tmpParam) > 1 && $tmpParam[0] == 'PROPERTY'){
				unset($tmpParam[0]);
//				self::getTreeProps($tmpParam);
//				PR($tmpParam);
				$props[] = $tmpParam;
			} else {
				$scalar[] = $param;
			}
		}

		return ['fields' => $scalar, 'props' => $props];
	}

	private static function getTreeProps($arProps = [])
	{
		$tree = [];
		foreach ($arProps as $i => $prop) {
			if($i > 0 && $prop == 'PROPERTY'){
				continue;
			}
			$tree[] = $prop;

			PR($prop);
		}
	}

	public static function getIblockData($iblockId)
	{

	}

}
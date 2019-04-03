<?php namespace Soft\IBlock\Tools;

use interes_v2\Tools\Catalog;

class Filter {

	/**
	 * @param array $arFilter
	 * @param null|bool $IBLOCK_ID
	 * @return bool|array
	 */
	public static function prepare($arFilter, $IBLOCK_ID = NULL) {
		if ($arFilter['IBLOCK_ID'])
			$IBLOCK_ID = $arFilter['IBLOCK_ID'];

		if (($IBLOCK_ID = intval($IBLOCK_ID)) == 0 || (!$arPropList = Catalog::getElementProps($IBLOCK_ID)))
			return false;

		foreach ($arFilter as $key => $value) {
			unset($arFilter[$key]);

			if (is_numeric($key) && $key == intval($key) && is_array($value) && count($value) > 0) {
				$arFilter[$key] = self::prepare($value, $IBLOCK_ID);
				continue;
			}

			$sort = false;
			$key = ToUpper($key);
			$key = str_replace('PROPERTYSORT_', 'PROPERTY_', $key, $cnt);
			if ($cnt > 0)
				$sort = true;

			$key = str_replace('PROPERTY_', 'PROPERTY.', $key, $cnt);
			if ($cnt > 0) {
				$arKey = explode('.', $key);
				$bProp = false;
				foreach ($arKey as &$k) {
					if ($bProp) {
						$k = self::getCode($k, $arPropList, NULL, $sort);
					}
					$bProp = (($pos = stripos($k, 'PROPERTY')) !== false && strlen($k) == ($pos + 8)); // strlen('PROPERTY') = 8
				}
				$key = implode('.', $arKey);
			}

			$arFilter[$key] = $value;
		}
		return $arFilter;
	}

	/**
	 * не множ:
	 *  - строка: CODE
	 *  - список: CODE.[ID|VALUE]
	 *  - привязка: CODE
	 * множ:
	 *  - строка: CODE_ENTITY.VALUE
	 *  - список: CODE_ENTITY.VALUE_LIST.[ID|VALUE]
	 *  - привязка: CODE_ENTITY.VALUE
	 *
	 * @param string $code
	 * @param null|array $arProp
	 * @param null|int $IBLOCK_ID
	 * @return bool|string
	 */
	public static function getCode($code, $arProp = NULL, $IBLOCK_ID = NULL, $sort = false) {
		$valueList = false;
		if (substr($code, -6) == '_VALUE') {
			$valueList = true;
			$code = substr($code, 0, -6);
		}

		if (is_array($arProp) && count($arProp) > 0) {
			if (isset($arProp[$code]) && is_array($arProp[$code]) && count($arProp[$code]) > 0)
				$arProp = $arProp[$code];
		} elseif ($IBLOCK_ID > 0) {
			$arProp = Catalog::getElementProps($IBLOCK_ID, $code);
		} else {
			return false;
		}

		if ($arProp['MULTIPLE'] == 'Y') {
			$code .= '_ENTITY';
			if ($arProp['PROPERTY_TYPE'] == 'L') {
				$code .= '.VALUE_LIST';
				$code .= $valueList?'.VALUE':($sort?'.SORT':'.ID');
			} else {
				$code .= '.VALUE';
			}
		} else {
			if ($arProp['PROPERTY_TYPE'] == 'L')
				$code .= $valueList?'.VALUE':($sort?'.SORT':'.ID');
		}
		return $code;
	}
}
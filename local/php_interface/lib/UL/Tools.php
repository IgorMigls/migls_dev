<?php namespace UL;

use Bitrix\Main\Loader;
use Bitrix\Main\Type\Dictionary;
use Bitrix\Main\Data;

Loader::includeModule('sale');

class Tools
{
	/** @var Dictionary */
	protected static $iblocks = null;

	const CACHE_KEY_PRODUCT = 'product_';
	const CACHE_KEY_REMAIN = 'remain_';
	const CACHE_KEY_CITY = 'city_';
	const CACHE_KEY_PROP = 'pw_iblock_meta_props_';
	const CACHE_KEY_IBLOCK = 'iblock_';
	const CACHE_KEY_IBLOCK_TYPE = 'iblock_type_';
	const CACHE_KEY_SECTION_SHOP = 'shop_section_';

	/**
	 * Tools constructor.
	 */
	public function __construct()
	{
	}

	public static function declOfNum($number, $titles)
	{
		$cases = array(2, 0, 1, 1, 1, 2);

		return $number." ".$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}

	public static function formatContProduct($count = 0)
	{
		return self::declOfNum($count, ['товар', 'товара', 'товаров']);
	}

	/**
	 * @method setIblocks - set param Iblocks
	 * @param array $iblocks
	 */
	public static function setIblocks($iblocks)
	{
		if (is_null(self::$iblocks)){
			self::$iblocks = new Dictionary($iblocks);
		}
	}

	/**
	 * @method getIblock
	 * @param $code
	 *
	 * @return null|string
	 */
	public static function getIblock($code)
	{
		return self::$iblocks->get($code);
	}

	public static function formatPrice($val, $onlyVal = true)
	{
		return \SaleFormatCurrency($val, 'RUB', $onlyVal);
	}

	public static function clearCache($type, $id)
	{
		$Cache = Data\Cache::createInstance();
		$CacheTag = new Data\TaggedCache();
		$CacheTag->clearByTag($type.$id);
	}

	/**
	 * @method generateDaysForDelivery
	 * @return array
	 */
	public static function generateDaysForDelivery()
	{
		$result = [];
		$dateNow = new \DateTime();
		$interval = new \DateInterval('P1D');
		for ($i = 0; $i < 7; $i++) {
			if($i > 0){
				$time = $dateNow->add($interval);
			} else {
				$time = $dateNow;
			}

			$result[$i] = array(
				'CLASS' => $i == 0 ? 'active' : '',
				'DAY' => \FormatDate('l', $time->getTimestamp()),
				'NUM' => $time->format('d'),
				'MONTH' => \FormatDate('F', $time->getTimestamp())
			);

//			$result[$i] = $time->format('d').' '.\FormatDate('F', $time->getTimestamp());
		}

		return $result;
	}

	public static function nominativeMonth($search = '')
	{
		$result = null;
		$arMonth = [
			'янаваря',
			'февраля',
			'марта',
			'апреля',
			'мая',
			'июня',
			'июля',
			'августа',
			'сентября',
			'октября',
			'ноября',
			'декабря',
		];
		if(strlen($search) > 0){
			$search = strtolower($search);
			$arr = explode(' ', $search);
			if(preg_match('#\d+#', $arr[0])){
				$month = $arr[1];
			} else {
				$month = $arr[0];
			}
			$month = strtolower($month);
			if($k = array_search($month, $arMonth)){
				$numMonth = $k + 1;
				if(strlen($numMonth) < 2){
					$numMonth = '0'.$numMonth;
				}


				return $result = ['d'=>(int)$arr[0], 'm'=>$numMonth, date('Y')];
			}
		}

		return null;
	}

}
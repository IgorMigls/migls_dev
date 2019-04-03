<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 13.11.17
 */

namespace UL\Main;

use Bitrix\Main;
use function dump;

class Dates extends Main\Type\Dictionary
{
	protected $startItem = null;
	protected $endItem = null;

	/** @var  Main\Type\DateTime */
	protected $startDate;

	/** @var  Main\Type\DateTime */
	protected $endDate;

	/** @var  Main\Type\Dictionary */
	protected $calendar;

	/**
	 * Dates constructor.
	 *
	 * @param array $values
	 */
	public function __construct($values)
	{
		if(is_array($values))
			$values = array_values($values);

		parent::__construct($values);
		$this->calendar = new Main\Type\Dictionary();
	}

	public function last()
	{
		if($this->count() > 0){
			return $this->get($this->count() - 1);
		}

		return null;
	}

	/**
	 * @method first
	 * @return mixed|null
	 */
	public function first()
	{
		if($this->count() > 0){

			foreach ($this->values as $value) {
				if($value['CLOSED']){
					$this->next();
				} else {
					$items = $value['TIMES'];
					$last = array_pop($items);
					if($last['ACTIVE'] != 'Y'){
						$this->next();
						unset($items, $last);
					}
				}
			}

			return $this->current();
		}

		return null;
	}

	/**
	 * @method makeCalendar
	 * @return Main\Type\Dictionary
	 */
	public function makeCalendar(): Main\Type\Dictionary
	{
		$this->setStartItem($this->first());
		return $this->compileCalc();
	}

	/**
	 * @method getStartItem - get param startItem
	 * @return mixed
	 */
	public function getStartItem()
	{
		return $this->startItem;
	}

	/**
	 * @method setStartItem - set param StartItem
	 * @param mixed $startItem
	 */
	public function setStartItem($startItem)
	{
		$this->rewind();
		$this->startItem = $startItem;
		if($startItem['DATE']){
			$this->startDate = new Main\Type\DateTime($startItem['DATE']->format('d.m.Y'));
		}
	}

	/**
	 * @method getEndItem - get param endItem
	 * @return mixed
	 */
	public function getEndItem()
	{
		return $this->endItem;
	}

	/**
	 * @method compileCalc
	 * @return Main\Type\Dictionary
	 */
	protected function compileCalc()
	{
	    //print  debug_backtrace()[1]['file'] . ' ' . debug_backtrace()[1]['line'];
		setlocale(LC_ALL, "ru_RU.UTF-8");
		if(!is_null($this->startItem)){
			for ($i = 1; $i <= 7; $i++){

				if(empty($this->startDate))
					continue;

				if($i == 1){
					$interval = $this->startDate;
				} else {
					$interval = $this->startDate->add('+ 1 days');
				}

				if(empty($interval))
					continue;

				$item = $this->values[(int)$interval->format('N') - 1];
				if(!$item['DATE'] instanceof Main\Type\Date){
					$item['DATE'] = $interval;
				}

				$monthFormat = static::ru_date('%bg', $interval->getTimestamp());
				$item['FORMAT'] = [
					'DAY' => $interval->format('j'),
					'MONTH' => $interval->format('m'),
					'MONTH_LOCALE' => $monthFormat,
				];

				if($i > 1 && (strtolower($item['NAME']) == 'сегодня' || strtolower($item['NAME']) == 'завтра')){
					$item['NAME'] = strftime('%A', $interval->getTimestamp());
				}
				$item['TIMESTAMP'] = $interval->getTimestamp();
				if($i >= $item['DAY_NUMBER']){
					unset($item['CLOSED']);
					foreach ($item['TIMES'] as &$TIME) {
						$TIME['ACTIVE'] = 'Y';
					}
				}

				if (!isset($item['CLOSED_BY_ADMIN'])) {
				    $item['CLOSED_BY_ADMIN'] = 'N';
				}


				$this->calendar->offsetSet(null, $item);

			}
		}

		return $this->calendar;
	}

	/**
	 * @method ru_date
	 * @param $format
	 * @param bool $date
	 *
	 * @return string
	 */
	public static function ru_date($format, $date = false) {
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		if ($date === false) {
			$date = time();
		}
		if ($format === '') {
			$format = '%e&nbsp;%bg&nbsp;%Y&nbsp;г.';
		}
		$months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
		$format = preg_replace("~\%bg~", $months[date('n', $date)], $format);

		return strftime($format, $date);
	}

	/**
	 * @method getStartDate - get param startDate
	 * @return mixed
	 */
	public function getStartDate()
	{
		return $this->startDate;
	}

	/**
	 * @method setStartDate - set param StartDate
	 * @param mixed $startDate
	 */
	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
	}

	/**
	 * @method getEndDate - get param endDate
	 * @return mixed
	 */
	public function getEndDate()
	{
		return $this->endDate;
	}

	/**
	 * @method setEndDate - set param EndDate
	 * @param mixed $endDate
	 */
	public function setEndDate($endDate)
	{
		$this->endDate = $endDate;
	}

}
<?php
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 09.08.2017
 */

namespace AB\Tools\Console\Scripts;

use Bitrix\Main
use AB\Tools\Console\ProgressBar;

class SetTags implements Console\IConsole
{
	/**
	 * @var array - массив параметров CLI
	 */
	protected $params;

	/**
	 * Creator constructor. В конструктор приходят все параметры из CLI
	 *
	 * @param array $params
	 */
	public function __construct($params = [])
	{
		global $argv;

		if (count($params) == 0 || is_null($params)){
			$this->params = $argv;
		}

		$this->params = $params;
	}

	/**
	 * @method getParams - get param params
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * @method setParams - set param Params
	 * @param array $params
	 */
	public function setParams($params)
	{
		$this->params = $params;
	}

	/**
	 * @method run - Это основной метод для запуска скрипта
	 * @throws \Exception
	 */
	public function run()
	{
		Console\ProgressBar::pre($this->params);

		$cnt = 10;
        $Bar = new Console\ProgressBar();
        $Bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $cnt);
        for ($i = 1; $i <= $cnt; $i++){
            sleep(1);
            $Bar->update($i);
        }
	}

}
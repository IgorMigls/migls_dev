<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.10.2017
 */

namespace AB\Tools\HttpFoundation;
use Bitrix\Main;

class Result extends Main\Result
{

	public function addData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getErrorString()
	{
		return implode(', ', $this->getErrorMessages());
	}

	public function getJson()
	{
		return Main\Web\Json::encode($this->data);
	}
}
<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.10.2017
 */

namespace AB\Tools\HttpFoundation;

use Bitrix\Main;

class ErrorCollection extends Main\ErrorCollection
{
	public function __toString()
	{
		return implode(', ', $this->toArray());
	}

}
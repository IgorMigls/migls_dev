<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 05.04.2017
 */

namespace Online1c\Iblock;

use Throwable;

class IblockException extends \Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString()
	{
		parent::__toString();
	}

}
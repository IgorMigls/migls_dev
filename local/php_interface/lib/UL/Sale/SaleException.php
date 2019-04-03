<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 18.07.2016
 * Time: 17:04
 */

namespace UL\Sale;
use Exception;

class SaleException extends Exception
{
	public function __construct($message, $code = 1000, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	public function __toString()
	{
		parent::__toString();
	}

}
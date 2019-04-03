<?php namespace PW\Tools\Generator;

use Exception;

class GenException extends \Exception
{
	/**
	 * GenException constructor.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}
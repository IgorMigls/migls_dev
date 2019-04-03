<?php namespace Soft;

use Exception;

/**
 * Class IBlockException
 * @package Alvitek\Blocks
 */
class IBlockException extends \Exception
{
	/**
	 * IBlockException constructor.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception|null $previous
	 */
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @method __toString
	 */
	public function __toString()
	{
		parent::__toString();
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 14.07.2016
 * Time: 13:09
 */

namespace UL\Ajax;
use Exception;

class ExceptionAjax extends Exception
{
	/**
	 * Construct the exception. Note: The message is NOT binary safe.
	 * @link http://php.net/manual/en/exception.construct.php
	 *
	 * @param string $message [optional] The Exception message to throw.
	 * @param int $code [optional] The Exception code.
	 * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
	 *
	 * @since 5.1.0
	 */
	public function __construct($message, $code, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	 * String representation of the exception
	 * @link http://php.net/manual/en/exception.tostring.php
	 * @return string the string representation of the exception.
	 * @since 5.1.0
	 */
	public function __toString()
	{
		parent::__toString();
	}

}
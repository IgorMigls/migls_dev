<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 22.09.2016
 * Time: 14:31
 */

namespace AB\FormIblock;


use Bitrix\Main\Application;
use Bitrix\Main\Security\Sign\Signer;

class Protect
{
	private $sign = null;
	private $componentParams = [];

	public function __construct($componentParams = [])
	{
		$this->setComponentParams($componentParams);
	}

	private static function getKey()
	{
		global $LICENSE_KEY;
		$server = \Bitrix\Main\Context::getCurrent()->getServer();

		return md5($LICENSE_KEY.$server->get('HTTP_HOST'));
	}

	/**
	 * @method sign
	 * @return string
	 */
	public function sign()
	{
		$key = self::getKey();
		$Signer = new Signer();
		$Signer->setKey($key);
		$paramStr = base64_encode(serialize($this->componentParams));

		return $Signer->sign($paramStr, $key);
	}

	/**
	 * @method unSign
	 * @return string
	 */
	public function unSign()
	{
		$key = self::getKey();
		$Signer = new Signer();
		$Signer->setKey($key);

		return $Signer->unsign($this->componentParams, $key);
	}

	/**
	 * @method getComponentParams - get param componentParams
	 * @return array
	 */
	public function getComponentParams()
	{
		return $this->componentParams;
	}

	/**
	 * @method setComponentParams - set param ComponentParams
	 * @param array $componentParams
	 */
	public function setComponentParams($componentParams)
	{
		$this->componentParams = $componentParams;
	}

}
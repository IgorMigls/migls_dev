<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.10.2017
 */

namespace AB\Tools\HttpFoundation;

use Bitrix\Main;
use Symfony\Component\HttpFoundation\Request;


class TestRequest
{

	/** @var Request  */
	protected $parameters;

	protected $errorCollection;

	protected $result;


	public function __construct(Request $params)
	{
		$this->parameters = $params;
	}

	public function __invoke($params)
	{
		$this->parameters = $params;
	}

	public function testAction($data = [])
	{
		return ['sss' => 123123];
	}

}
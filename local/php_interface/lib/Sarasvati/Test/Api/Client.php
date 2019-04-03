<?php
/**
 * Created by OOO 1C-SOFT.
 * User: Dremin_S
 * Date: 18.06.2018
 */

namespace Sarasvati\Test\Api;

use Bitrix\Main;

class Client extends Main\Web\HttpClient
{

	protected $url = '';
	private $token = '';

	/**
	 * Client constructor.
	 *
	 * @param string $url
	 */
	public function __construct(string $url)
	{
		parent::__construct();

		$this->url = $url;

		$this->setHeader('Accept', 'application/json');
		$this->setHeader('Content-Type', 'application/json');
	}


	public function auth()
	{
		$response = $this->get($this->url);


		$url = $this->buildUri('find_by_phone', ['phone_number' => '1231231231', 'country_code' => '+7']);

		dump($url);
//		$response = $this->get($url);
		dump($this->responseHeaders, $this->getStatus());
		dump($response);
	}

	protected function buildUri(string $action, array $params = [])
	{
		if (substr($action, 0, 1) != '/'){
			$action = '/'.$action;
		}

		$uri = new Main\Web\Uri($this->url.$action);
		$uri->addParams($params);

		return $uri->getUri();
	}
}
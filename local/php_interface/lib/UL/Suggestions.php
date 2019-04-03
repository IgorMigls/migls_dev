<?php
/**
 * Created by PhpStorm.
 * User: Станислав
 * Date: 19.07.2016
 * Time: 22:36
 */

namespace UL;

use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;

class Suggestions
{
	protected $Client;
	protected $countRes = 10;
	protected $type;

	const TOKEN = '6ea97bd241c8a659899f4aa7925d741a16b2a43b';
	const URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/';

	/**
	 * Suggestions constructor.
	 */
	public function __construct()
	{
		$this->Client = new HttpClient();
		$this->Client->setVersion(HttpClient::HTTP_1_1);
		$this->Client->setHeader('Content-Type', 'application/json');
		$this->Client->setHeader('Accept', 'application/json');
		$this->Client->setHeader('Authorization', 'Token ' . self::TOKEN);
	}

	/**
	 * @method getAddress
	 * @param array $data
	 *
	 * @return array|mixed
	 */
	public function getAddress($data = [])
	{
		$this->setType('address');
		$response = $this->send($data);

		return $response;
	}

	public function getAddressAction($data)
	{
		return $this->getAddress($data);
	}

	/**
	 * @method send
	 * @param $data
	 *
	 * @return array|mixed
	 */
	public function send($data)
	{
		$post = Json::encode($data);
		$only = null;
		if (count($data['only']) > 0) {
			$only = $data['only'];
			unset($data['only']);
		}

		$response = $this->Client->post(self::URL . $this->getType(), $post);

		$arCityTypes = [
			'area_type' => 'р-н',
			'city_type' => 'г',
			'settlement_type' => 'с'
		];

		if ($this->Client->getStatus() >= 200) {

			$res = Json::decode($response);
			$result = null;

			foreach ($res['suggestions'] as $k => $item) {
				$item['value'] = str_replace('г ', '', $item['value']);
				$result['suggestions'][] = ['value' => $item['value'], 'data' => $item['data']];
			}

			return $result;
		} else {
			return ['status' => $this->Client->getStatus()];
		}
	}

	/**
	 * @method getType - get param type
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 *
	 * @return Suggestions
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function loadData($post)
	{
		$this->setType('address');
		$post = Json::encode($post);
		$response = $this->Client->post(self::URL . $this->getType(), $post);
		if ($this->Client->getStatus() >= 200) {
			$res = Json::decode($response);
			return $res['suggestions'];
		} else {
			throw new \Exception('error '.$this->Client->getStatus(), 406);
		}

	}
}
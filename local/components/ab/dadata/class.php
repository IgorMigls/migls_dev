<?php namespace AB;

use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Result;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;

Loc::loadMessages(__FILE__);

class DaData extends \CBitrixComponent
{
	const URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/';

	protected $Client;
	protected $countRes = 10;
	protected $type;

	private $Result;
	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component = null)
	{
		parent::__construct($component);

		$this->Client = new HttpClient();
		$this->Client->setVersion(HttpClient::HTTP_1_1);
		$this->Client->setHeader('Content-Type', 'application/json');
		$this->Client->setHeader('Accept', 'application/json');

		$this->Result = new Result();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(strlen($arParams['KEY']) == 0){
			$this->Result->addError(new Error(Loc::getMessage('AB_DADATA_CLASS_NO_KEY')));
		} else {
			$this->Client->setHeader('Authorization', 'Token '.$arParams['KEY']);
		}

		return $arParams;
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
		$response = $this->Client->post(self::URL.$this->arParams['TYPE'], $post);

		if($this->Client->getStatus() >= 200){
			return Json::decode($response);
		} else {
			return ['error' => $this->Client->getStatus()];
		}
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		$this->arResult['ERRORS'] = $this->Result->getErrorMessages();

		$this->includeComponentTemplate();
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;
		return $USER;
	}
}
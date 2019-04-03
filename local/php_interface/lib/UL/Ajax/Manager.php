<?php
/**
 * Created by PhpStorm.
 * User: dremin_s
 * Date: 14.07.2016
 * Time: 13:08
 */

namespace UL\Ajax;

use Bitrix\Main;
use Bitrix\Main\Web;
use PW\Tools\Debug;

class Manager
{
	protected $request;
	protected $server;

	protected $namespace;
	protected $class;
	protected $action;

	/** @var  Main\Type\Dictionary */
	protected $data;
	/** @var  Main\Result */
	private $result;
	private $htmlMode = false;

	/**
	 * Manager constructor.
	 */
	public function __construct()
	{
		$this->request = Main\Context::getCurrent()->getRequest();
		$this->server = Main\Context::getCurrent()->getServer();
	}

	/**
	 * @method parseUrl
	 * @throws ExceptionAjax
	 */
	public function parseUrl()
	{
		$Uri = new Web\Uri($this->request->getRequestUri());
		$url = $Uri->getUri();
		$url = trim($url, '!"#$%&\'()*+,-.@:;<=>[\\]^_`{|}~');

		$arResultList = \CUrlRewriter::GetList(['ID'=>'ajax']);
		$rule = array_shift($arResultList);

		$action = $class = $nameSpace = null;

		preg_match($rule['CONDITION'], $url, $params);

		$arUrl = explode('/', $params[1]);

		$action = array_pop($arUrl);
		$class = array_pop($arUrl);
		$nameSpace = implode('\\', $arUrl);

		if(preg_match('~(.*)\?(.*)+~', $action, $matchAction)){
			$action = $matchAction[1];
		}

		if(strlen($class) == 0){
			$class = '\\';
		}

		if(strlen($action) == 0){
			throw new ExceptionAjax('Action is empty');
		}

		$this->setMainParams($nameSpace, $class, $action);
	}

	/**
	 * @method setMainParams
	 * @param $nameSpace
	 * @param $class
	 * @param $action
	 */
	protected function setMainParams($nameSpace, $class, $action)
	{
		$this->setNamespace($nameSpace);
		$this->setClass($class);
		$this->setAction($action);
		$this->setData();
	}

	/**
	 * @method init
	 * @return $this
	 * @throws ExceptionAjax
	 */
	public function init()
	{
		$result = new Main\Result();
		$resultAction = null;
		try{
			if($this->request->get('sessid') || $this->request->getPost('sessid')){
				if(!check_bitrix_sessid()){
					throw new ExceptionAjax('sessid is not valid');
				}
			}
			if($this->getClass() == '\\'){
				if(is_callable($this->getAction())){
					$resultAction = call_user_func($this->getAction(), $this->getData()->toArray());
				} else {
					throw new ExceptionAjax('Action is not callable');
				}
			} else {
				$resultAction = $this->instanceActionClass();
			}

		} catch (\ReflectionException $err){

			$route = Routes::getRout($this->getNamespace(), $this->getClass());
			$this->setMainParams($route['NAMESPACE'], $route['CLASS'], $this->getAction());
			if(isset($route['COMPONENT'])){
				\CBitrixComponent::includeComponentClass($route['COMPONENT']);
			}
			try{
				$resultAction = $this->instanceActionClass();
			}catch (\ReflectionException $err){
				$result->addError(new Main\Error($err->getMessage(), $err->getCode()));
			}
		} catch (ExceptionAjax $err){
			$result->addError(new Main\Error($err->getMessage(), $err->getCode()));
		} catch (\Exception $err){
			$result->addError(new Main\Error($err->getMessage(), $err->getCode()));
		}

		$out = [
			'DATA' => $resultAction,
			'ERRORS' => count($result->getErrorMessages()) > 0 ? $result->getErrorMessages() : null,
		];

		if(!is_null($out['ERRORS'])){
			$out['STATUS'] = 0;
		} else {
			$out['STATUS'] = 1;
		}

		$result->setData($out);
		$this->setResult($result);

		return $this;
	}

	/**
	 * @method instanceActionClass
	 * @return mixed
	 */
	protected function instanceActionClass()
	{
		$action = $this->getAction();
		$class = $this->getNamespace().'\\'.$this->getClass();

		$initClass = new \ReflectionClass($class);
		$ob = $initClass->newInstance();

		return $ob->$action($this->getData()->toArray());
	}

	/**
	 * @method getResult
	 * @return mixed
	 */
	public function getResult()
	{
		$data = $this->result->getData();

		if($this->getHtmlMode() === true){
			return $data['DATA'];
		} else {
			try{
				$out = Web\Json::encode($data);
			} catch (Main\ArgumentException $err){
				$out['DATA'] = null;
				$out['ERRORS'][] = $err->getMessage();
				$out['STATUS'] = 0;
			}
			return $out;
		}
	}

	/**
	 * @param Main\Result $result
	 *
	 * @return Manager
	 */
	public function setResult($result)
	{
		$this->result = $result;

		return $this;
	}

	/**
	 * @method getAction - get param action
	 * @return mixed
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * @method setAction - set param Action
	 * @param mixed $action
	 */
	public function setAction($action)
	{
		$this->action = $action;
	}

	/**
	 * @method getClass - get param class
	 * @return mixed
	 */
	public function getClass()
	{
		return $this->class;
	}

	/**
	 * @method setClass - set param Class
	 * @param mixed $class
	 */
	public function setClass($class)
	{
		$this->class = $class;
	}

	/**
	 * @method getData - get param data
	 * @return Main\Type\Dictionary
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @method getNamespace - get param namespace
	 * @return mixed
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @method setNamespace - set param Namespace
	 * @param mixed $namespace
	 */
	public function setNamespace($namespace)
	{
		if(substr($namespace, 0, 1) != '\\'){
			$namespace = '\\'.$namespace;
		}

		$this->namespace = $namespace;
	}

	/**
	 * @method setData
	 */
	public function setData()
	{
		$post = null;
		$contentType = $this->server->get('HTTP_ACCEPT');

		if(preg_match('{json}i', $contentType) != false){
			$this->setHtmlMode(false);
		} else {
			$this->setHtmlMode(true);
		}

		if($this->request->isPost()){
			if($this->getHtmlMode() === false){
				$data = Web\Json::decode(file_get_contents('php://input'));
			} else {
				$data = $this->request->getPostList()->toArray();
			}
		} else {
			$data = $this->request->toArray();
		}

		unset($data['type']);
		unset($data['action']);

		$this->data = new Main\Type\Dictionary($data);
	}

	/**
	 * @method addData
	 * @param $k
	 * @param $val
	 *
	 * @return $this
	 */
	public function addData($k, $val)
	{
		$vv = self::sanitizeData($val);

		$this->data->offsetSet($k, $vv);

		return $this;
	}

	/**
	 * @method sanitizeData
	 * @param $data
	 *
	 * @return mixed
	 */
	private static function sanitizeData($data)
	{
		foreach ($data as $code => $value) {
			if(is_array($value)){
				$data[$code] = self::sanitizeData($value);
			} else {
				$data[$code] = htmlspecialcharsbx($value);
			}
		}

		return $data;
	}

	/**
	 * @method getHtmlMode - get param htmlMode
	 * @return boolean
	 */
	public function getHtmlMode()
	{
		return $this->htmlMode;
	}

	/**
	 * @param boolean $htmlMode
	 *
	 * @return Manager
	 */
	public function setHtmlMode($htmlMode)
	{
		$this->htmlMode = $htmlMode;

		return $this;
	}
}
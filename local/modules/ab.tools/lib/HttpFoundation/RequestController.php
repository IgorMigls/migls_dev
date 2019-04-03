<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 09.10.2017
 */

namespace AB\Tools\HttpFoundation;

use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Bitrix\Main;

class RequestController implements HttpKernelInterface
{
	/** @var Routing\RouteCollection  */
	protected $routes;

	/** @var EventDispatcher  */
	protected $dispatcher;

	/** @var  Main\ErrorCollection */
	protected $errors;

	protected $result;

	/** @var  Main\Type\Dictionary */
	protected $options;

	/**
	 * RequestController constructor.
	 *
	 * @param array $option
	 */
	public function __construct($option = [])
	{
		$this->options = new Main\Type\Dictionary($option);
		$this->routes = new Routing\RouteCollection();
		$this->dispatcher = new EventDispatcher();
		$this->errors = new Main\ErrorCollection();
		$this->result = new Result();
	}

	public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
	{
//		$event = new Events\RequestEvent(); // запитываем объект событий
//		$event->setRequest($request); // ставим в события пришедший реквест

		$context = new Routing\RequestContext(); // Объявляем контекст для роутера
		$context->fromRequest($request); // передаем в контекст текущий реквест
		$response = new Response();
		// объявляем обект для сравнения и поиска подходящего роутера из коллекции RouteCollection
		$matcher = new Routing\Matcher\UrlMatcher($this->routes, $context);

		if($request->headers->get('accept') == 'application/json' || $request->headers->get('content-type') == 'application/json'){
			$request->setRequestFormat('json');
			$post = file_get_contents('php://input');
			if(!empty($post)){
				$post = Main\Web\Json::decode($post);
			}
			if(is_array($post))
				$request->request->add($post);

		} elseif($request->headers->get('accept') == 'application/xml' || $request->headers->get('content-type') == 'application/xml'){
			$request->setRequestFormat('xml');
		}
		$content = null;
		try{
			$attributes = $matcher->match($request->getPathInfo()); // поиск роутера

			$attrs = $attributes;

			foreach ($attrs as $code => $attr) {
				if(substr($code, 0, 1) === '_'){
					unset($attrs[$code]);
				}
			}
			$attrs += $request->request->all();
			$attrs += $request->query->all();

			if(!$attrs['files'])
				$attrs['files'] = $request->files->all();

			$controller = $attributes['_controller'];

			$attrs = self::sanitizeData($attrs);

			if($attrs['sessid']){
				$_REQUEST['sessid'] = $attrs['sessid'];
			}

			if($attrs['sessid'] && !check_bitrix_sessid()){
				throw new Routing\Exception\ResourceNotFoundException('Ваша сессия истекла, перезагрузите страницу', Response::HTTP_FORBIDDEN);
			}

			global $USER;
			if($attrs['_auth'] === true){
				if(!$USER->IsAuthorized()){
					throw new Routing\Exception\ResourceNotFoundException('Доступ запрещен', Response::HTTP_FORBIDDEN);
				}
			}

			$arCtrl = explode('::', $controller);
			TrimArr($arCtrl);
			if ($attributes['_component']){
				\CBitrixComponent::includeComponentClass($attributes['_component']);
				$reflection = new \ReflectionClass($arCtrl[0]);
				$instance = $reflection->newInstance();
				$instance->setPostData($attrs);
			} elseif ($attributes['_module']) {
				Main\Loader::includeModule($attributes['_module']);
				$reflection = new \ReflectionClass($arCtrl[0]);
				$instance = $reflection->newInstance($request);
			} else {
				$reflection = new \ReflectionClass($arCtrl[0]);
				$instance = $reflection->newInstance($request);
			}

			$action = $arCtrl[1];
			if (!$action){
				$action = $attributes['method'];
			}

			$this->result->addData($instance->$action());

		} catch (Routing\Exception\ResourceNotFoundException $err){
			$code = $err->getCode();
			if((int)$code == 0){
				$code = Response::HTTP_NOT_FOUND;
			}
			$this->result->addError(new Main\Error($err->getMessage(), $code));
		} catch (\ReflectionException $err){

			$this->result->addError(new Main\Error('not found', Response::HTTP_NOT_ACCEPTABLE));
		} catch (\Exception $err) {
			$this->result->addError(new Main\Error($err->getMessage(), $err->getCode()));
		}

		try {
			if($request->getRequestFormat() == 'json'){
				$content = [
					'ERRORS' => null,
					'STATUS' => 1,
					'DATA' => $this->result->getData(),
				];

				if($this->result->getErrorCollection()->count() > 0){
					$content['STATUS'] = 0;
					$content['ERRORS'] = [];
					/** @var Main\Error $item */
					foreach ($this->result->getErrorCollection() as $item) {
						$content['ERRORS'][] = ['msg' => $item->getMessage(), 'code' => $item->getCode()];
					}
					$content['ERROR_MSG'] = $this->result->getErrorString();
				}

				$response->setContent(Main\Web\Json::encode($content));

				$response->headers->set('accept', 'application/json');
				$response->headers->set('content-type', 'application/json');
			} else {
				$response->setContent($this->result->getData());
			}
		} catch (\Exception $e){
			if(AB_DEBUG === true)
				$c = $e->getMessage();
			else
				$c = 'bad response format';

			$response->setContent($c);
		}

		return $response;
	}

	public function map($path, $controller, $params = []) {
		$path = $this->options->get('baseUrl').$path;
		$params = array_merge(array('_controller' => $controller), $params);
		$this->routes->add($path, new Routing\Route($path, $params));

		return $this;
	}

	protected static function getComponentInstance($name)
	{

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
}

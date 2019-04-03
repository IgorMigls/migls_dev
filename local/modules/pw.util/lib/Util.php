<?namespace PW\Tools;

class Util
{
	protected $pathModuleTheme = '/bitrix/themes/.default/pw.util';
	protected $pathCss;
	protected $pathJs;
	protected $arJSCoreConfig = array();
	/** @var null|Util */
	private static $instance = null;

	/**
	 * @method getInstance
	 * @return null|Util
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new Util();
		}
		return self::$instance;
	}

	/**
	 * @method mb_ucfirst
	 * @param $string
	 *
	 * @return string
	 */
	public static function mb_ucfirst($string)
	{
		$string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
		return $string;
	}

	/**
	 * @method registerCssJs
	 */
	public function registerCssJs()
	{
		$this->pathCss = $this->pathModuleTheme.'/css';
		$this->pathJs = $this->pathModuleTheme.'/js/lib';

		$this->getCore();

		foreach ($this->arJSCoreConfig as $ext => $arExt)
		{
			\CJSCore::RegisterExt($ext, $arExt);
		}
	}

	/**
	 * @method getCore
	 * @param array $config
	 *
	 * @return array
	 */
	public function getCore($config = array())
	{
		$this->arJSCoreConfig = array(
				'angular'=>array(
						'js'=>array(
								$this->pathJs.'/angular.min.js',
								$this->pathJs.'/angular-resource.min.js',
								$this->pathJs.'/angular-animate.min.js',
								$this->pathJs.'/angular-messages.min.js',
								$this->pathJs.'/angular-sanitize.min.js',
								$this->pathJs.'/bootstrap-growl_2.min.js',
								$this->pathJs.'/autofill-event.js',
								$this->pathJs.'/ajax_service.js',
						),
				),
				'bootstrapAngular'=>array(
						'js'=>$this->pathJs.'/ui-bootstrap-tpls-0.14.3.min.js'
				),
				'fontsAw'=>array(
						'css'=>$this->pathCss.'/font-awesome.min.css'
				),
				'bootstrap3'=>array(
						'css'=>$this->pathCss.'/bootstrap.min.css'
				),
				'fancyBox'=>array(
						'js'=>array(
								$this->pathJs.'/jquery.mousewheel-3.0.6.pack.js',
								$this->pathJs.'/fancy/jquery.fancybox.2.1.5.pack.js',
								$this->pathJs.'/fancy/fancy.service.js',
						),
						'css'=>$this->pathCss.'/fancy/jquery.fancybox.css'
				),
				'fancyBoxHelpers'=>array(
						'js'=>array(
								$this->pathJs.'/fancy/jquery.fancybox-buttons.js',
								$this->pathJs.'/fancy/jquery.fancybox-media.js',
								$this->pathJs.'/fancy/jquery.fancybox-thumbs.js',
						),
						'css'=>array(
								$this->pathCss.'/fancy/helpers/jquery.fancybox-buttons.css',
								$this->pathCss.'/fancy/helpers/jquery.fancybox-thumbs.css',
						)
				),
				'router'=>array(
					'js'=>array(
							$this->pathJs.'/angular-route.min.js',
							$this->pathJs.'/angular.ui.router.js',
					)
				)
		);

		if(count($config) > 0){
			$this->arJSCoreConfig = array_merge($this->arJSCoreConfig, $config);
		}

		return $this->arJSCoreConfig;
	}

	/**
	 * @method addCore
	 * @param $name
	 * @param array $arCore
	 */
	public function addCore($name, array $arCore)
	{
		if(strlen($name) > 0)
			$this->arJSCoreConfig[$name] = $arCore;
	}
}
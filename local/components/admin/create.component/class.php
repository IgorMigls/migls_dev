<?php namespace DigitalWand\AdminHelper;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;

Main\Loader::includeModule('esd.main');

Loc::loadLanguageFile(__FILE__);

class ComponentCreator extends \CBitrixComponent
{
	protected $postData;
	protected $root;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->root = Main\Application::getDocumentRoot();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(AB_DEBUG === true){
			Main\Page\Asset::getInstance()->addJs('https://unpkg.com/vue/dist/vue.js');
		} else {
			Main\Page\Asset::getInstance()->addJs('https://unpkg.com/vue/dist/vue.min.js');
		}
		Main\Page\Asset::getInstance()->addJs('https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.min.js');

		if($arParams['ASSETS']){
			\CUtil::InitJSCore($arParams['ASSETS']);
		}

		return $arParams;
	}

	/**
	* @method getUser
	* @return \CUser
	*/
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function getNameSpaces()
	{
		$data = $this->getPostData();
		$folder = '/'.$data['folder'].'/components';

		$dir = new Main\IO\Directory($this->root.$folder);
		$result = [];
		foreach ($dir->getChildren() as $child) {
			if($child->getName() !== 'bitrix')
				$result[] = $child->getName();
		}

		return $result;
	}

	public function createComponent()
	{
		$data = $this->getPostData();

		$folder = '/'.$data['folder'].'/components';
		$namespace = '/'.(strlen($data['newNamespace']) > 0 ? $data['newNamespace'] : $data['namespace']);

		$oFolder = new Main\IO\Directory($this->root.$folder);
		if(!$oFolder->isExists()){
			$oFolder->create();
		}
		$oFolder->createSubdirectory($namespace)->createSubdirectory($data['componentName']);

		$componentFolder = $oFolder->getPath().'/'.$namespace.'/'.$data['componentName'];

		$classTpl = file_get_contents(dirname(__FILE__).'/tpl/simple/class.tpls');

		$componentClass = explode('\\', $data['componentClass']);
		TrimArr($componentClass);

		$className = array_pop($componentClass);

		$componentNamespace = implode('\\', $componentClass);
		$search = ["#NAMESPACE_CMP#", '#CLASS_CMP#'];
		$replace = ['namespace '.$componentNamespace.';', $className];

		$classTpl = str_replace($search, $replace, $classTpl);
		$resClassSave = file_put_contents($componentFolder.'/class.php', $classTpl);
		if((int)$resClassSave == 0){
			throw new \Exception('Не удалось создать файл class.php', 500);
		}


		$createTemplate = CopyDirFiles(
			dirname(__FILE__).'/tpl/simple/templates',
			$componentFolder.'/templates',
			false,
			true
		);

		if(!$createTemplate){
			throw new \Exception('Не удалось создать шаблон для компонента', 500);
		}

		if($data['crateScripts'] != true){
			unlink($componentFolder.'/templates/.default/script.js');
			unlink($componentFolder.'/templates/.default/style.css');
		}

		$namespace = str_replace('/','',$namespace);
		return $namespace.':'.$data['componentName'];
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}

	/**
	 * @method getPostData - get param postData
	 * @return mixed
	 */
	public function getPostData()
	{
		return $this->postData;
	}

	/**
	 * @method setPostData - set param PostData
	 * @param mixed $postData
	 */
	public function setPostData($postData)
	{
		$this->postData = $postData;
	}
}
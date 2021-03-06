<?php #NAMESPACE_CMP#
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

Loc::loadLanguageFile(__FILE__);

class #CLASS_CMP# extends \CBitrixComponent
{

	/** @var  array */
	protected $postData;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
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
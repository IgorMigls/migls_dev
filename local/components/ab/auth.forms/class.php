<?php 
namespace AB\Auth;

/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\UserTable;

class Forms extends \CBitrixComponent
{
	protected $USER;

	/**
	 * @param \CBitrixComponent|null $component
	 */
	public function __construct($component)
	{
		global $USER;
		parent::__construct($component);
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param $arParams
	 *
	 * @return mixed
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(intval($arParams['CACHE_TIME']) == 0)
			$arParams['CACHE_TIME'] = 86400;

		return $arParams;
	}

	/**
	 * @method getUserInfo
	 * @return array|null
	 */
	public function getUserInfo()
	{
		$arUser = null;

		if($this->USER->IsAuthorized()){
			$arUser = UserTable::getRow([
				'select' => ['ID','NAME','LOGIN','EMAIL','PERSONAL_MOBILE'],
				'filter' => ['=ID' => $this->USER->GetID()]
			]);
		}

		return $arUser;
	}

	/**
	 * @method executeComponent
	 */
	public function executeComponent()
	{
		$this->arResult['USER'] = $this->getUserInfo();

		$this->arResult['AUTH'] = true;
		if(is_null($this->arResult['USER'])){
			$this->arResult['AUTH'] = false;
		}

		$this->includeComponentTemplate();
	}

}
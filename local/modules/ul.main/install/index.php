<?
IncludeModuleLangFile(__FILE__);

if (class_exists("ul_main"))
	return;

use Ul\Main\Map\Model\CordTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

class ul_main extends CModule
{
	public $MODULE_ID = "ul.main";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_CSS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	private $error = array();

	/**
	 * pw_landing constructor.
	 */
	function __construct()
	{
		$arModuleVersion = array();

		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("UL_MAIN_MOD_INSTALL");
		$this->MODULE_DESCRIPTION = GetMessage("UL_MAIN_MOD_DESC");
	}

	/**
	 * @method DoInstall
	 * @return bool
	 */
	public function DoInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);
		Loader::includeModule($this->MODULE_ID);
		CordTable::createTable();

		return true;
	}

	/**
	 * @method DoUninstall
	 * @return bool
	 */
	public function DoUninstall()
	{
		Loader::includeModule($this->MODULE_ID);
		CordTable::dropTables();
		ModuleManager::unRegisterModule($this->MODULE_ID);

		return true;
	}
}
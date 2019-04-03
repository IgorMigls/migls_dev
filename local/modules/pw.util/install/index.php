<?
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\ModuleManager;

if (class_exists("pw_util"))
	return;

class pw_util extends CModule
{
	var $MODULE_ID = "pw.util";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $PARTNER_NAME;
	var $PARTNER_URI;

	protected $APP;
	protected $DB;

	function __construct()
	{
		global $APPLICATION, $DB;

		$arModuleVersion = array();

		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("PW_UTIL_INSTALL_NAME");
		$this->PARTNER_NAME = GetMessage("PW_PARTNER_NAME");
//		$this->PARTNER_URI = GetMessage("ST_PARTNER_URI");

		$this->APP = $APPLICATION;
		$this->DB = $DB;
	}

	public function installFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/admin')){
			if ($dir = opendir($p)){
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;

					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$item,
							'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/local/modules/'.$this->MODULE_ID.'/admin/'.$item.'");');
				}
				closedir($dir);
			}
		}

		CopyDirFiles(
				dirname(__FILE__)."/themes",
				$_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/".$this->MODULE_ID,
				true, true
		);
	}

	public function unInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/admin')){
			if ($dir = opendir($p)){
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.' ||  $item == 'menu.php')
						continue;

					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}

		unlink($_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/".$this->MODULE_ID);
	}

	public function DoInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		$this->installFiles();

		return true;
	}

	public function DoUninstall()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);

		$this->unInstallFiles();

		return true;
	}
}
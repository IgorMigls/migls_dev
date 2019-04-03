<?php IncludeModuleLangFile(__FILE__);
if (class_exists("soft_iblock"))
	return;

use Bitrix\Main\Localization\Loc as Loc;

class soft_iblock extends \CModule
{
	public $MODULE_ID = "soft.iblock";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_CSS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	private $APP;
	private $DB;

	function __construct()
	{
		global $DB, $APPLICATION;
		$arModuleVersion = array();

		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = Loc::getMessage("PW_IBLOCK_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("PW_IBLOCK_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = Loc::getMessage("PW_PARTNER_NAME");
//		$this->PARTNER_URI = GetMessage("ST_PARTNER_URI");

		$this->DB = $DB;
		$this->APP = $APPLICATION;
	}
	
	public function InstallDB()
	{
//		$error = $this->DB->RunSQLBatch(dirname(__FILE__)."/sql/install.sql");
//		if($error){
//			$this->APP->ThrowException('sss');
//			throw new Exception(implode("\r\n",$error));
//			return false;
//		}
		RegisterModule($this->MODULE_ID);
//		RegisterModuleDependences("main", "OnBeforeEndBufferContent", $this->MODULE_ID, "\\My\\Stat\\DataTable", "OnBeforeEndBufferContent");
		return true;
	}

	public function UnInstallDB()
	{
//		$error = $this->DB->RunSQLBatch(dirname(__FILE__)."/sql/uninstall.sql");
//		if($error){
//			$this->APP->ThrowException(implode("<br>", $error));
//			throw new Exception(implode("\r\n",$error));
//			return false;
//		}

//		UnRegisterModuleDependences("main", "OnBeforeEndBufferContent", $this->MODULE_ID, "\\My\\Stat\\DataTable", "OnBeforeEndBufferContent");
		UnRegisterModule($this->MODULE_ID);
		return true;
	}

	public function InstallFiles()
	{
//		CopyDirFiles(dirname(__FILE__)."/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
//		CopyDirFiles(dirname(__FILE__)."/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
//		CopyDirFiles(dirname(__FILE__)."/tools", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools", true, true);

		return true;
	}

	public function UnInstallFiles()
	{
//		DeleteDirFiles(dirname(__FILE__)."/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		return true;
	}

	public function DoInstall()
	{
//		$this->InstallFiles();
		$this->InstallDB();
	}

	public function DoUninstall()
	{
		$this->UnInstallDB();
//		$this->UnInstallFiles();
	}
}
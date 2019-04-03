<?php IncludeModuleLangFile(__FILE__);
if (class_exists("ab_form_iblock"))
	return;

use Bitrix\Main\Localization\Loc as Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main;

class ab_form_iblock extends \CModule
{
	const IB_TYPE = 'form_iblock';
	const MAIL_EVENT_TYPE = 'AB_FORMS';
	const MAIL_EVENT_MSG = 'AB_FORMS_MSG';

	public $MODULE_ID = "ab.form_iblock";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_CSS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	protected $iblockId = null;
	protected $arSites = array();

	private $app;
	private $connect;

	function __construct()
	{
		global $DB, $APPLICATION;
		$arModuleVersion = array();

		include(dirname(__FILE__) . "/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = Loc::getMessage("AB_FORM_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("AB_FORM_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = Loc::getMessage("AB_PARTNER_NAME");
//		$this->PARTNER_URI = GetMessage("ST_PARTNER_URI");
		$this->app = $APPLICATION;
		$this->connect = Main\Application::getConnection();

		Loader::includeModule('iblock');
	}

	public function DoInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);
		\Bitrix\Main\Loader::includeModule($this->MODULE_ID);
		$Event = \Bitrix\Main\EventManager::getInstance();

		$Sites = Main\SiteTable::getList();
		while ($site = $Sites->fetch()){
			$this->arSites[] = $site['LID'];
		}

		$resType = $this->addType();

		$this->connect->startTransaction();
		$resIblock = $this->addIblock();

		if (!$resIblock->isSuccess()) {
			$this->connect->rollbackTransaction();
		} else {
			$this->addProperties();
			$this->connect->commitTransaction();
		}
		$this->addMailEvent();

		return true;
	}

	public function DoUninstall()
	{
		\Bitrix\Main\Loader::includeModule($this->MODULE_ID);
		$Event = \Bitrix\Main\EventManager::getInstance();

		CIBlockType::Delete(self::IB_TYPE);
		CEventType::Delete(self::MAIL_EVENT_TYPE);

		ModuleManager::unRegisterModule($this->MODULE_ID);
		return true;
	}

	protected function addType()
	{
		$Result = new Main\Entity\Result();
		$arFieldsType = Array(
			'ID' => self::IB_TYPE,
			'SECTIONS' => 'Y',
			'IN_RSS' => 'N',
			'SORT' => 100500,
			'LANG' => Array(
				'ru' => Array(
					'NAME' => 'Формы',
					'SECTION_NAME' => '',
					'ELEMENT_NAME' => '',
				),
				'en' => Array(
					'NAME' => 'Forms',
					'SECTION_NAME' => '',
					'ELEMENT_NAME' => '',
				),
			),
		);
		$Result->setData($arFieldsType);
		$CIBlockType = new CIBlockType();
		$res = $CIBlockType->Add($arFieldsType);
		if (!$res) {
			$Result->addError(new Main\Error($CIBlockType->LAST_ERROR));
		}
		return $Result;
	}

	protected function addIblock()
	{
		$Result = new Main\Entity\Result();
		$ib = new CIBlock();

		$arFields = Array(
			"ACTIVE" => 'Y',
			"NAME" => 'Заявки',
			"CODE" => 'request_forms',
			"IBLOCK_TYPE_ID" => self::IB_TYPE,
			"SITE_ID" => $this->arSites,
			"SORT" => 100500,
			'GROUP_ID' => array("1"=>"X", "2"=>"R")
		);
		$Result->setData($arFields);
		$arIblock = Bitrix\Iblock\IblockTable::getRow([
			'filter' => ['=CODE' => 'request_forms'],
		]);
		if (!is_null($arIblock)) {
			$ID = $arIblock['ID'];
		} else {
			$ID = $ib->Add($arFields);
		}

		if (intval($ID) == 0) {
			$Result->addError(new Main\Error(strip_tags($ib->LAST_ERROR)));
		} else {
			$this->iblockId = $ID;
		}

		return $Result;
	}

	protected function addProperties()
	{
		$arProps = [
			'FIO' => 'ФИО',
			'PHONE' => 'Телефон',
			'EMAIL' => 'E-mail',
			'IP' => 'IP',
		];
		$ibp = new CIBlockProperty;
		foreach ($arProps as $code => $prop) {
			$field = [
				'NAME' => $prop,
				'ACTIVE' => 'Y',
				'CODE' => $code,
				'PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_STRING,
				'IBLOCK_ID' => $this->iblockId,
			];
			if ($code != 'IP')
				$field['REQUIRED'] = 'Y';

			$ibp->Add($field);
		}
	}

	protected function addMailEvent()
	{
		$arType = CEventType::GetByID(self::MAIL_EVENT_TYPE);
		if(!$arType->Fetch()){
			$DESCRIPTION = '
#EMAIL_TO# - кому отослать
#EMAIL_FROM# - от кого
#TEXT# - текст
			';
			$et = new CEventType();
			$res = $et->Add(array(
				"LID"           => SITE_ID,
				"EVENT_NAME"    => self::MAIL_EVENT_TYPE,
				"NAME"          => 'Формы инфоблоков',
				"DESCRIPTION"   => $DESCRIPTION
			));
			if($res){
				$message = "
Информационное сообщение сайта #SITE_NAME#
------------------------------------------

#TEXT#

				";
				$CEventMessage = new CEventMessage();
				$CEventMessage->Add(array(
					'ACTIVE' => 'Y',
					'EVENT_NAME' => self::MAIL_EVENT_TYPE,
					'LID' => $this->arSites,
					'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
					'EMAIL_TO' => '#EMAIL_TO#',
					'SUBJECT' => 'Заявка с сайта #SITE_NAME#',
					'BODY_TYPE' => 'text',
					'MESSAGE' => $message
				));
			}
		}
	}
}